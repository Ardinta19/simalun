<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Halaman read-only untuk lihat jejak aksi admin. Admin bisa filter
     * per actor, per action, dan per range tanggal — biar gampang
     * pertanggungjawaban kalau ada perubahan finance/voucher yang
     * dipertanyakan.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('actor')->latest();

        if ($request->filled('actor_id')) {
            $query->where('actor_id', $request->actor_id);
        }

        if ($request->filled('action')) {
            $query->where('action', 'like', $request->action.'%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(30)->withQueryString();

        // Daftar actor unik untuk filter — dibatasi role admin/driver karena
        // customer praktis gak pernah trigger audit log.
        $actors = User::whereIn('role', ['admin', 'driver'])
            ->whereIn('id', AuditLog::query()->select('actor_id')->whereNotNull('actor_id'))
            ->orderBy('name')
            ->get();

        $actionGroups = AuditLog::query()
            ->selectRaw('action, COUNT(*) as total')
            ->groupBy('action')
            ->orderByDesc('total')
            ->get();

        return view('roles.admin.audit.index', compact('logs', 'actors', 'actionGroups'));
    }
}
