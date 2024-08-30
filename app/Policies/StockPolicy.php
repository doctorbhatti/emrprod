<?php

namespace App\Policies;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockPolicy {
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
     * Determine if the user can add stocks.
     * Nurses cannot add stocks.
     * 
     * @param User $user
     * @return bool
     */
    public function add(User $user) {
        return !($user->isNurse());
    }

    /**
     * Determine if the user can delete a stock.
     * Only admins can delete stocks.
     *
     * @param User $user
     * @param Stock $stock
     * @return bool
     */
    public function delete(User $user, Stock $stock) {
        return $user->isAdmin() && $user->clinic->id === $stock->drug->clinic->id;
    }

    /**
     * Determine who can view stocks that are running low.
     * 
     * @param User $user
     * @return bool
     */
    public function seeRunningLow(User $user) {
        return true;
    }
}
