<?php

namespace App\Policies;

use App\User;
use App\BahanDapur;
use Illuminate\Auth\Access\HandlesAuthorization;

class BahanDapurPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the bahan dapur.
     *
     * @param  \App\User  $user
     * @param  \App\BahanDapur  $bahanDapur
     * @return mixed
     */
    public function view(User $user)
    {
        return $this->findRole($user);
    }

    public function show(User $user, BahanDapur $bahanDapur)
    {
        return ( $this->findRole($user) && ($user->bisnis_id == $bahanDapur->bisnis_id) );
    }

    /**
     * Determine whether the user can create bahan dapurs.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->findRole($user);
    }

    /**
     * Determine whether the user can update the bahan dapur.
     *
     * @param  \App\User  $user
     * @param  \App\BahanDapur  $bahanDapur
     * @return mixed
     */
    public function update(User $user, BahanDapur $bahanDapur)
    {
        return ( $this->findRole($user) && ($user->bisnis_id == $bahanDapur->bisnis_id) );
    }

    /**
     * Determine whether the user can delete the bahan dapur.
     *
     * @param  \App\User  $user
     * @param  \App\BahanDapur  $bahanDapur
     * @return mixed
     */
    public function delete(User $user, BahanDapur $bahanDapur)
    {
        return ( $this->findRole($user) && ($user->bisnis_id == $bahanDapur->bisnis_id) );
    }

    /**
     * Determine whether the user can restore the bahan dapur.
     *
     * @param  \App\User  $user
     * @param  \App\BahanDapur  $bahanDapur
     * @return mixed
     */
    public function restore(User $user, BahanDapur $bahanDapur)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the bahan dapur.
     *
     * @param  \App\User  $user
     * @param  \App\BahanDapur  $bahanDapur
     * @return mixed
     */
    public function forceDelete(User $user, BahanDapur $bahanDapur)
    {
        //
    }

    private function findRole($user){
        $roles = $user->roleBackoffice();
        foreach($roles as $role){
            foreach($role->child as $child){
                if($child->otorisasi_id == 13 && $child->is_aktif == 1 && $role->is_aktif == 1){
                    return true;
                }
            }
        }
        return false;
    }
}
