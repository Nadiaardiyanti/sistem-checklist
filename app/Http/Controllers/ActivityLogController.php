<?php

namespace App\Http\Controllers;

use App\Models\InspectionActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    /**
     * Daftar log aktivitas inspeksi, dengan filter pencarian.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q'        => ['nullable', 'string', 'max:100'],
            'activity' => ['nullable', 'string', 'max:50'],
            'user'     => ['nullable', 'integer'],
            'dari'     => ['nullable', 'date'],
            'sampai'   => ['nullable', 'date'],
        ]);

        $query = InspectionActivityLog::with(['user', 'inspection']);

        if (! empty($filters['q'])) {
            $search = $filters['q'];

            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('inspection', function ($i) use ($search) {
                        $i->where('inspection_number', 'like', "%{$search}%")
                            ->orWhere('partner_name', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['activity'])) {
            $query->where('activity', $filters['activity']);
        }

        if (! empty($filters['user'])) {
            $query->where('user_id', $filters['user']);
        }

        if (! empty($filters['dari'])) {
            $query->whereDate('created_at', '>=', $filters['dari']);
        }

        if (! empty($filters['sampai'])) {
            $query->whereDate('created_at', '<=', $filters['sampai']);
        }

        $activityLogs = $query
            ->latest()
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        // Pengguna yang pernah punya aktivitas (untuk dropdown filter)
        $users = User::whereIn('id', InspectionActivityLog::select('user_id')->distinct())
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('activity-logs.index', compact('activityLogs', 'users'));
    }
}
