<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * Guard untuk pengetatan keamanan basic.
 *
 * Cover:
 *  - Mass-assignment privilege: register tidak boleh bisa set role=admin
 *    via field tersembunyi.
 *  - Header keamanan ke-set di response (X-Content-Type-Options dll).
 *  - Throttle reset password aktif.
 */
class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_tidak_bisa_di_inject_role_admin_lewat_request(): void
    {
        // Simulasi attacker kirim field 'role' tambahan di payload register.
        // Karena 'role' di-keluarin dari $fillable, mass-fill bakal ignore.
        // RegisterController emang udah set role hardcoded ke 'customer',
        // jadi test ini juga ngeguard regression seandainya dev nambah
        // User::create($request->all()) di tempat lain.
        $response = $this->post(route('register.post'), [
            'name' => 'Attacker',
            'email' => 'attacker@example.com',
            'phone' => '81234567899',
            'gender' => 'L',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'address' => 'Jl. Attempt No. 1, lengkap',
            'address_note' => null,
            'terms' => 'on',
            'role' => 'admin', // ← injection attempt
            'is_active' => false,
            'email_verified_at' => now()->toDateTimeString(),
        ]);

        $response->assertRedirect();

        $user = User::where('email', 'attacker@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('customer', $user->role);
        // is_active default true di DB; cek truthy bukan strict boolean
        // karena tidak ada cast eksplisit di User model (kolom INT tinyint).
        $this->assertTrue((bool) $user->is_active);
        $this->assertNull($user->email_verified_at);
    }

    public function test_security_headers_di_set_di_response(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('X-XSS-Protection', '0');
        $this->assertNotEmpty($response->headers->get('Permissions-Policy'));
    }

    public function test_reset_password_throttle_aktif(): void
    {
        // 6 request berturut dalam 1 menit — limit 5/menit, request ke-6
        // harus dapet 429 Too Many Requests.
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('password.update.reset'), [
                'token' => 'invalid-token',
                'email' => 'someone@example.com',
                'password' => 'newpassword',
                'password_confirmation' => 'newpassword',
            ]);
        }

        $response = $this->post(route('password.update.reset'), [
            'token' => 'invalid-token',
            'email' => 'someone@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(429);
    }

    public function test_proof_image_upload_tolak_file_non_image(): void
    {
        $driver = User::factory()->create(['role' => 'driver']);
        $service = Service::create([
            'name' => 'Cuci Test MIME',
            'slug' => 'cuci-mime-'.uniqid(),
            'pricing_model' => 'per_kg',
            'unit_price' => 7000,
            'unit_type' => 'kg',
            'price_per_kg' => 7000,
            'estimated_hours' => 24,
            'description' => 'Test',
            'is_active' => true,
        ]);
        $order = Order::create([
            'order_code' => 'ORD-MIME-'.uniqid(),
            'customer_id' => User::factory()->create(['role' => 'customer'])->id,
            'service_id' => $service->id,
            'driver_id' => $driver->id,
            'address' => 'Jl. MIME No. 1',
            'zone' => 'A',
            'pickup_cost' => 5000,
            'pickup_date' => now()->toDateString(),
            'pickup_time' => 'pagi',
            'weight_estimate' => 2,
            'service_cost' => 14000,
            'discount' => 0,
            'total_cost' => 19000,
            'status' => 'dikirim',
            'payment_method' => 'cod',
            'is_paid' => false,
        ]);

        // Upload PDF file dengan ekstensi .pdf — `image` rule + `mimetypes:`
        // dua-duanya harus nolak. Sebelumnya cuma `image` rule yang
        // ngecek lewat getimagesize, masih bisa di-bypass kalau file
        // header dipalsu. mimetypes: rule jalan di level OS-level mime
        // detection (finfo), lebih ketat.
        $nonImage = UploadedFile::fake()->create(
            'document.pdf',
            100,
            'application/pdf'
        );

        $response = $this->actingAs($driver)
            ->post(route('driver.orders.action', $order), [
                'status' => 'selesai',
                'proof_image' => $nonImage,
                'payment_channel' => 'cash',
            ]);

        $response->assertSessionHasErrors(['proof_image']);
        $this->assertSame('dikirim', $order->fresh()->status);
        $this->assertNull($order->fresh()->proof_image);
    }
}
