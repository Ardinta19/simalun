<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceCategoryController extends Controller
{
    /**
     * Halaman list kategori untuk admin. Tabel pricing_model dipakai walk-in
     * form buat filter mana service kiloan vs satuan, jadi admin perlu bisa
     * tambah/edit kategori sendiri tanpa nyentuh DB.
     */
    public function index()
    {
        // Eager load services biar admin bisa lihat (& kelola) layanan apa
        // saja yang ada di tiap kategori, langsung dari satu halaman.
        $categories = ServiceCategory::withCount('services')
            ->with(['services' => fn ($q) => $q->orderByDesc('is_active')->orderBy('name')])
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get();

        return view('roles.admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $category = ServiceCategory::create($data);

        Audit::log('service-category.create', $category, after: $data,
            summary: "Membuat kategori \"{$data['name']}\"");

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$data['name']}\" berhasil dibuat.");
    }

    public function update(Request $request, ServiceCategory $category)
    {
        $before = $category->only(['name', 'pricing_model', 'description', 'is_active']);
        $data = $this->validatedData($request, $category->id);

        $category->update($data);

        Audit::log('service-category.update', $category,
            before: $before, after: $category->only(array_keys($before)),
            summary: "Mengubah kategori {$category->name}");

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$category->name}\" diperbarui.");
    }

    public function toggle(ServiceCategory $category)
    {
        $before = $category->is_active;
        $category->update(['is_active' => ! $category->is_active]);

        $statusLabel = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';

        Audit::log('service-category.toggle', $category,
            before: ['is_active' => $before],
            after: ['is_active' => $category->is_active],
            summary: "Kategori {$category->name} {$statusLabel}");

        return back()->with('success', "Kategori {$category->name} {$statusLabel}.");
    }

    public function destroy(ServiceCategory $category)
    {
        // Cegah hapus kategori yang masih dipakai service — biar relasi
        // di order lama tetap terbaca (walaupun FK-nya nullOnDelete).
        if ($category->services()->exists()) {
            return back()->with('error', 'Kategori masih dipakai oleh layanan aktif. Pindahkan layanan dulu sebelum hapus.');
        }

        $name = $category->name;
        $snapshot = $category->only(['name', 'pricing_model', 'description']);
        $category->delete();

        Audit::log('service-category.delete', null, before: $snapshot,
            summary: "Menghapus kategori \"{$name}\"");

        return back()->with('success', "Kategori \"{$name}\" dihapus.");
    }

    /**
     * Aturan validasi dipakai bareng oleh store & update. Untuk update kita
     * exclude ID sendiri di rule unique.
     */
    private function validatedData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => [
                'required', 'string', 'max:80',
                Rule::unique('service_categories', 'name')->ignore($ignoreId),
            ],
            'pricing_model' => ['required', 'in:per_kg,per_item'],
            'description' => ['nullable', 'string', 'max:200'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.unique' => 'Sudah ada kategori dengan nama ini.',
            'pricing_model.in' => 'Model harga harus per_kg atau per_item.',
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
