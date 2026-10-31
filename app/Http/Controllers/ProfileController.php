<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Inspection;
use App\Models\InspectionActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Jumlah inspeksi milik pengguna ini, dikelompokkan per status
        $byStatus = Inspection::where('created_by', $user->id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'total'      => (int) $byStatus->sum(),
            'draft'      => (int) ($byStatus['draft'] ?? 0),
            'completed'  => (int) ($byStatus['completed'] ?? 0),
            'activities' => InspectionActivityLog::where('user_id', $user->id)->count(),
        ];

        // Lima aktivitas terakhir pengguna ini
        $recentActivities = InspectionActivityLog::with('inspection')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('profile.edit', compact('user', 'stats', 'recentActivities'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
