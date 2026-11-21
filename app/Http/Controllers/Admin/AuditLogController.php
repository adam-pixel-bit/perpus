<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * Display a listing of system audit logs.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $action = $request->query('action');

        $query = AuditLog::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($action && $action !== 'all') {
            $query->where('action', $action);
        }

        $auditLogs = $query->latest('id')->paginate(20)->withQueryString();

        // Get distinct actions for filtering
        $availableActions = AuditLog::select('action')->distinct()->pluck('action');

        return view('admin.audit_logs.index', compact('auditLogs', 'search', 'action', 'availableActions'));
    }
}
