<?php

namespace App\Policies;

use App\User;
use App\Menu;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
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
     * @param  \App\Meja  $meja
     * @return mixed
     */
    public function view(User $user)
    {
        return $this->findRole($user);
    }

    public function show(User $user, Meja $meja)
    {
        return ( $this->findRole($user) && ($user->bisnis_id == $meja->bisnis_id) );
    }

    /**
     * Determine whether the user can create biaya tambahans.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->findRole($user);
    }

    /**
     * Determine whether the user can update the biaya tambahan.
     *
     * @param  \App\User  $user
     * @param  \App\Meja  $meja
     * @return mixed
     */
    public function update(User $user, Meja $meja)
    {
        return ( $this->findRole($user) && ($user->bisnis_id == $meja->bisnis_id) );
    }

    /**
     * Determine whether the user can delete the biaya tambahan.
     *
     * @param  \App\User  $user
     * @param  \App\Meja  $meja
     * @return mixed
     */
    public function delete(User $user, Meja $meja)
    {
        return ( $this->findRole($user) && ($user->bisnis_id == $meja->bisnis_id) );
    }

    /**
     * Determine whether the user can restore the biaya tambahan.
     *
     * @param  \App\User  $user
     * @param  \App\Meja  $meja
     * @return mixed
     */
    public function restore(User $user, Meja $meja)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the biaya tambahan.
     *
     * @param  \App\User  $user
     * @param  \App\Meja  $meja
     * @return mixed
     */
    public function forceDelete(User $user, Meja $meja)
    {
        //
    }

    private function findRole($user){
        $roles = $user->roleBackoffice();
        foreach($roles as $role){
            foreach($role->child as $child){
                if($child->otorisasi_id == 4 && $child->is_aktif == 1 && $role->is_aktif == 1){
                    return true;
                }
            }
        }
    }
}
