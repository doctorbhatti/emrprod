<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy {
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Determine who can view payment info.
     *
     * @param User $user
     * @return bool
     */
    public function view(User $user) {
        // Only admin users can view payment information
        return $user->isAdmin();
    }
}
