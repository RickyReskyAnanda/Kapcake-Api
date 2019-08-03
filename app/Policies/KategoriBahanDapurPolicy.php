<?php

namespace App\Policies;

use App\User;
use App\KategoriBahanDapur;
use Illuminate\Auth\Access\HandlesAuthorization;

class KategoriBahanDapurPolicy
{
    use HandlesAuthorization;


    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }
    /**
     * Determine whether the user can view the kategori bahan dapur.
     *
     * @param  \App\User  $user
     * @param  \App\KategoriBahanDapur  $kategoriBahanDapur
     * @return mixed
     */
    public function view(User $user)
    {
        return $this->findRole($user);
    }

    public function show(User $user, KategoriBahanDapur $kategoriBahanDapur)
    {
        return $this->findRole($user);
    }

    /**
     * Determine whether the user can create kategori bahan dapurs.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->findRole($user);
    }

    /**
     * Determine whether the user can update the kategori bahan dapur.
     *
     * @param  \App\User  $user
     * @param  \App\KategoriBahanDapur  $kategoriBahanDapur
     * @return mixed
     */
    public function update(User $user, KategoriBahanDapur $kategoriBahanDapur)
    {
        return ( $this->findRole($user) && ($user->bisnis_id == $kategoriBahanDapur->bisnis_id) );
    }

    /**
     * Determine whether the user can delete the kategori bahan dapur.
     *
     * @param  \App\User  $user
     * @param  \App\KategoriBahanDapur  $kategoriBahanDapur
     * @return mixed
     */
    public function delete(User $user, KategoriBahanDapur $kategoriBahanDapur)
    {
        return ( $this->findRole($user) && ($user->bisnis_id == $kategoriBahanDapur->bisnis_id) );
    }

    /**
     * Determine whether the user can restore the kategori bahan dapur.
     *
     * @param  \App\User  $user
     * @param  \App\KategoriBahanDapur  $kategoriBahanDapur
     * @return mixed
     */
    public function restore(User $user, KategoriBahanDapur $kategoriBahanDapur)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the kategori bahan dapur.
     *
     * @param  \App\User  $user
     * @param  \App\KategoriBahanDapur  $kategoriBahanDapur
     * @return mixed
     */
    public function forceDelete(User $user, KategoriBahanDapur $kategoriBahanDapur)
    {
        //
    }

    private function findRole($user){
        $roles = $user->roleBackoffice();
        foreach($roles as $role){
            foreach($role->child as $child){
                if($child->otorisasi_id == 12 && $child->is_aktif == 1 && $role->is_aktif == 1){
                    return true;
                }
            }
        }
        return false;
    }
}
