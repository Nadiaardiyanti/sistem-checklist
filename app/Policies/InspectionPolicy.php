<?php

namespace App\Policies;

use App\Models\Inspection;
use App\Models\User;

/**
 * KSI - Otorisasi tingkat data: inspeksi hanya boleh diubah oleh
 * pembuatnya atau admin. Semua pengguna login boleh melihat.
 */
class InspectionPolicy
{
    public function view(User $user, Inspection $inspection): bool
    {
        return true;
    }

    public function update(User $user, Inspection $inspection): bool
    {
        return $user->isAdmin() || $inspection->created_by === $user->id;
    }
}
