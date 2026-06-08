<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with(['actor']);

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('actor')) {
            $query->whereHas('actor', fn ($q) => $q->where('name', 'like', '%' . $request->actor . '%'));
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $logs = $query->latest()->paginate(15)->withQueryString();

        return view('admin.audit-log.index', compact('logs'));
    }
}
