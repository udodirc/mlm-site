<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Http\Request;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $request = app(Request::class);
        $role = $request->input('role');

        if ($role) {
            $user->assignRole($role);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {

    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
