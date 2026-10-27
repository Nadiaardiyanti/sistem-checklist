<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\InspectionFinding;
use App\Models\InspectionActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik inspeksi
        $totalInspections = Inspection::count();

        $draftInspections = Inspection::where('status', 'draft')->count();

        // Statistik temuan
        $totalFindings = InspectionFinding::count();

        // Lima inspeksi terbaru
        $latestInspections = Inspection::with('creator')
            ->latest()
            ->take(5)
            ->get();

        // Lima aktivitas terbaru
        $latestActivities = InspectionActivityLog::with('user', 'inspection')
            ->latest()
            ->take(5)
            ->get();

        $totalActivities = InspectionActivityLog::count();

        return view('dashboard', compact(
            'totalInspections',
            'draftInspections',
            'totalFindings',
            'totalActivities',
            'latestInspections',
            'latestActivities'
        ));
    }
}