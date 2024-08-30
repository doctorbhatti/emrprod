<?php

namespace App\Policies;

use App\Models\Drug;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DrugPolicy
{
    use HandlesAuthorization;

    /**
     * DrugPolicy constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Any user can add drugs.
     *
     * @param User $user
     * @param $class
     * @return bool
     */
    public function add()
    {
        
        // Allow any user to add drugs
        return true;
    }

    /**
     * Only a drug of the same clinic can be viewed by a user.
     *
     * @param User $user
     * @param Drug $drug
     * @return bool
     */
    public function view(User $user, Drug $drug)
    {
        // Check if the drug belongs to the same clinic as the user
        return $user->clinic->id === $drug->clinic->id;
    }

    /**
     * Define who can edit the drug details.
     *
     * @param User $user
     * @param Drug $drug
     * @return bool
     */
    public function edit(User $user, Drug $drug)
    {
        // Users can edit the drug if they belong to the same clinic and are not nurses
        return $user->clinic->id === $drug->clinic->id && !$user->isNurse();
    }

    /**
     * Only the admin can delete a drug.
     *
     * @param User $user
     * @param Drug $drug
     * @return bool
     */
    public function delete(User $user, Drug $drug)
    {
        // Only admins can delete drugs
        return $user->isAdmin() && $user->clinic->id === $drug->clinic->id;
    }

    /**
     * Determine who can add stocks to a particular drug.
     *
     * @param User $user
     * @param Drug $drug
     * @return bool
     */
    public function addStocks(User $user, Drug $drug)
    {
        // Users can add stocks if they belong to the same clinic as the drug
        return $user->clinic->id === $drug->clinic->id;
    }
}
