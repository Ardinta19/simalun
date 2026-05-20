<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Form "Lapor Kendala" — shared untuk semua role.
     */
    public function create()
    {
        $myReports = Auth::user()->reports()
            ->latest()
            ->take(5)
            ->get();

        return view('reports.create', compact('myReports'));
    }

    /**
     * Simpan laporan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category'    => ['required', 'in:bug,saran,komplain'],
            'description' => ['required', 'string', 'min:10', 'max:1000'],
            'screenshot'  => ['nullable', 'image', 'max:5120'],
        ], [
            'description.min' => 'Deskripsi minimal 10 karakter agar kami bisa memahami kendala kamu.',
            'screenshot.max'  => 'Ukuran gambar maksimal 5 MB.',
        ]);

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('reports', 'public');
        }

        Report::create([
            'user_id'         => Auth::id(),
            'category'        => $request->category,
            'description'     => $request->description,
            'screenshot_path' => $screenshotPath,
            'status'          => 'open',
        ]);

        return back()->with('success', 'Laporan berhasil dikirim. Terima kasih atas masukannya!');
    }

    /**
     * Admin: daftar semua laporan masuk.
     */
    public function adminIndex(Request $request)
    {
        $status   = $request->get('status');
        $category = $request->get('category');

        $query = Report::with('user')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($category) {
            $query->where('category', $category);
        }

        $reports = $query->paginate(15)->withQueryString();

        $countOpen       = Report::where('status', 'open')->count();
        $countInProgress = Report::where('status', 'in_progress')->count();
        $countResolved   = Report::whereIn('status', ['resolved', 'closed'])->count();

        return view('roles.admin.reports', compact(
            'reports', 'countOpen', 'countInProgress', 'countResolved', 'status', 'category'
        ));
    }

    /**
     * Admin: update status laporan.
     */
    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status'      => ['required', 'in:open,in_progress,resolved,closed'],
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $data = ['status' => $request->status];

        if ($request->filled('admin_notes')) {
            $data['admin_notes'] = $request->admin_notes;
        }

        if (in_array($request->status, ['resolved', 'closed']) && !$report->resolved_at) {
            $data['resolved_at'] = now();
        }

        $report->update($data);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}
