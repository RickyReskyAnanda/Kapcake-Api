<?php

namespace App\Policies;

use App\User;
use App\Transfer;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransferPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the biaya tambahan.
     *
     * @param  \App\User  $user
     * @param  \App\Transfer  $transfer
     * @return mixed
     */
    public function view(User $user)
    {
        $this->findRole($user);
    }

    public function show(User $user, Transfer $transfer)
    {
        return ( $this->findRole($user) && ($user->bisnis_id == $transfer->bisnis_id) );
    }

    /**
     * Determine whether the user can create biaya tambahans.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        $this->findRole($user);
    }

    /**
     * Determine whether the user can update the biaya tambahan.
     *
     * @param  \App\User  $user
     * @param  \App\Transfer  $transfer
     * @return mixed
     */
    public function update(User $user, Transfer $transfer)
    {
        return ( $this->findRole($user) && ($user->bisnis_id == $transfer->bisnis_id) );
    }

    /**
     * Determine whether the user can delete the biaya tambahan.
     *
     * @param  \App\User  $user
     * @param  \App\Transfer  $transfer
     * @return mixed
     */
    public function delete(User $user, Transfer $transfer)
    {
        return ( $this->findRole($user) && ($user->bisnis_id == $transfer->bisnis_id) );
    }

    /**
     * Determine whether the user can restore the biaya tambahan.
     *
     * @param  \App\User  $user
     * @param  \App\Transfer  $transfer
     * @return mixed
     */
    public function restore(User $user, Transfer $transfer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the biaya tambahan.
     *
     * @param  \App\User  $user
     * @param  \App\Transfer  $transfer
     * @return mixed
     */
    public function forceDelete(User $user, Transfer $transfer)
    {
        //
    }

    private function findRole($user){
        $roles = $user->roleBackoffice();
        foreach($roles as $role){
            foreach($role->child as $child){
                if($child->otorisasi_id == 23 && $child->is_aktif == 1 && $role->is_aktif == 1){
                    return true;
                }
            }
        }
        return false;
    }
}
