<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
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

        ServiceCategory::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$data['name']}\" berhasil dibuat.");
    }

    public function update(Request $request, ServiceCategory $category)
    {
        $data = $this->validatedData($request, $category->id);

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$category->name}\" diperbarui.");
    }

    public function toggle(ServiceCategory $category)
    {
        $category->update(['is_active' => ! $category->is_active]);

        $statusLabel = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';

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
        $category->delete();

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
