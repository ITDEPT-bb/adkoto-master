<?php

namespace App\Policies;

use App\Models\User;

class AuctionItemPolicy
{
    public function manage(User $user)
    {
        // change this check to your role system
        return $user->is_filament_admin === true;
    }
}
