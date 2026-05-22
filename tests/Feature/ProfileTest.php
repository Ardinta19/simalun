<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_edit_profil_dapat_ditampilkan(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'phone' => '81234567890',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/profile/edit');

        $response->assertOk();
    }

    public function test_profil_dapat_diperbarui_dengan_nama_email_dan_phone(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'phone' => '81234567890',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Nama Baru',
                'email' => 'baru@example.com',
                'phone' => '81299990000',
            ]);

        $response->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertSame('Nama Baru', $user->name);
        $this->assertSame('baru@example.com', $user->email);
        $this->assertSame('81299990000', $user->phone);
        $this->assertNull($user->email_verified_at);
    }

    public function test_status_verifikasi_email_tidak_berubah_ketika_email_tidak_diubah(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'phone' => '81234567890',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_dapat_menghapus_akunnya_sendiri(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'phone' => '81234567890',
        ]);

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        // User pakai SoftDeletes — terhapus berarti deleted_at terisi.
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_password_salah_membatalkan_penghapusan_akun(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'phone' => '81234567890',
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/profile/edit')
            ->delete('/profile', [
                'password' => 'salah-bukan-password',
            ]);

        $response->assertSessionHasErrors('password');
        $this->assertNotNull($user->fresh());
    }
}
