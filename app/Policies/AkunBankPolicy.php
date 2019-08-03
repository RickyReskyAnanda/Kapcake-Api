<?php

namespace App\Policies;

use App\User;
use App\AkunBank;
use Illuminate\Auth\Access\HandlesAuthorization;

class AkunBankPolicy
{
    use HandlesAuthorization;



    /**
     * Determine whether the user can view the akun bank.
     *
     * @param  \App\User  $user
     * @param  \App\AkunBank  $akunBank
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create akun banks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the akun bank.
     *
     * @param  \App\User  $user
     * @param  \App\AkunBank  $akunBank
     * @return mixed
     */
    public function update(User $user, AkunBank $akunBank)
    {
        return $user->isSuperAdmin() && $user->bisnis_id == $akunBank->bisnis_id;
    }

    /**
     * Determine whether the user can delete the akun bank.
     *
     * @param  \App\User  $user
     * @param  \App\AkunBank  $akunBank
     * @return mixed
     */
    public function delete(User $user, AkunBank $akunBank)
    {
        return $user->isSuperAdmin() && $user->bisnis_id == $akunBank->bisnis_id;
    }

    /**
     * Determine whether the user can restore the akun bank.
     *
     * @param  \App\User  $user
     * @param  \App\AkunBank  $akunBank
     * @return mixed
     */
    public function restore(User $user, AkunBank $akunBank)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the akun bank.
     *
     * @param  \App\User  $user
     * @param  \App\AkunBank  $akunBank
     * @return mixed
     */
    public function forceDelete(User $user, AkunBank $akunBank)
    {
        //
    }
}
