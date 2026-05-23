<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    /**
     * Bikin service baru di dalam kategori tertentu. Kategori menentukan
     * pricing_model + unit_type service-nya — admin tinggal isi nama & harga.
     */
    public function store(Request $request, ServiceCategory $category)
    {
        $data = $this->validatedData($request);

        $service = new Service($data);
        $service->slug = $this->makeUniqueSlug($data['name']);
        $service->service_category_id = $category->id;
        $service->pricing_model = $category->pricing_model ?: 'per_kg';
        $service->unit_type = $service->pricing_model === 'per_kg' ? 'kg' : 'item';
        // Kolom legacy price_per_kg masih dibaca di beberapa view PDF & receipt,
        // jadi tetap diisi sama dengan unit_price biar konsisten.
        $service->price_per_kg = $data['unit_price'];
        $service->is_active = $request->boolean('is_active', true);
        $service->save();

        return redirect()->route('admin.categories.index')
            ->with('success', "Layanan \"{$service->name}\" ditambahkan ke kategori {$category->name}.");
    }

    public function update(Request $request, Service $service)
    {
        $data = $this->validatedData($request, $service->id);

        // Slug ikut nama kalau nama berubah, biar URL/identifier tetap rapi.
        if ($data['name'] !== $service->name) {
            $service->slug = $this->makeUniqueSlug($data['name'], $service->id);
        }

        $service->name = $data['name'];
        $service->unit_price = $data['unit_price'];
        $service->price_per_kg = $data['unit_price'];
        $service->estimated_hours = $data['estimated_hours'];
        $service->description = $data['description'] ?? null;
        $service->is_active = $request->boolean('is_active', $service->is_active);
        $service->save();

        return redirect()->route('admin.categories.index')
            ->with('success', "Layanan \"{$service->name}\" diperbarui.");
    }

    public function toggle(Service $service)
    {
        $service->update(['is_active' => ! $service->is_active]);

        $statusLabel = $service->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Layanan {$service->name} {$statusLabel}.");
    }

    public function destroy(Service $service)
    {
        // Service yang sudah punya order/order_item gak boleh dihapus karena
        // bakal merusak relasi historis. Sarankan nonaktif saja.
        if ($service->orders()->exists()) {
            return back()->with('error', "Layanan \"{$service->name}\" sudah dipakai pesanan. Nonaktifkan saja, jangan hapus.");
        }

        $name = $service->name;
        $service->delete();

        return back()->with('success', "Layanan \"{$name}\" dihapus.");
    }

    private function validatedData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => [
                'required', 'string', 'max:120',
                Rule::unique('services', 'name')->ignore($ignoreId),
            ],
            'unit_price' => ['required', 'integer', 'min:1000', 'max:1000000'],
            'estimated_hours' => ['required', 'integer', 'min:1', 'max:240'],
            'description' => ['nullable', 'string', 'max:200'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.unique' => 'Sudah ada layanan dengan nama ini.',
            'unit_price.min' => 'Harga minimal Rp 1.000.',
            'estimated_hours.max' => 'Estimasi maksimal 240 jam (10 hari).',
        ]);
    }

    /**
     * Slug harus unik. Kalau bentrok, tambah suffix angka — biar admin gak
     * harus mikirin slug, cukup kasih nama.
     */
    private function makeUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;

        while (
            Service::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }
}
