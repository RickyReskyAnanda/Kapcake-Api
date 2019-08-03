<?php

namespace App\Policies;

use App\User;
use App\KategoriMenu;
use Illuminate\Auth\Access\HandlesAuthorization;

class KategoriMenuPolicy
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
     * @param  \App\KategoriMenu  $kategoriMenu
     * @return mixed
     */
    public function view(User $user)
    {
        return $this->findRole($user);
    }

    public function show(User $user, KategoriMenu $kategoriMenu)
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
     * @param  \App\KategoriMenu  $kategoriMenu
     * @return mixed
     */
    public function update(User $user, KategoriMenu $kategoriMenu)
    {
        return ( $this->findRole($user) && ($user->bisnis_id == $kategoriMenu->bisnis_id) );
    }

    /**
     * Determine whether the user can delete the kategori bahan dapur.
     *
     * @param  \App\User  $user
     * @param  \App\KategoriMenu  $kategoriMenu
     * @return mixed
     */
    public function delete(User $user, KategoriMenu $kategoriMenu)
    {
        return ( $this->findRole($user) && ($user->bisnis_id == $kategoriMenu->bisnis_id) );
    }

    /**
     * Determine whether the user can restore the kategori bahan dapur.
     *
     * @param  \App\User  $user
     * @param  \App\KategoriMenu  $kategoriMenu
     * @return mixed
     */
    public function restore(User $user, KategoriMenu $kategoriMenu)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the kategori bahan dapur.
     *
     * @param  \App\User  $user
     * @param  \App\KategoriMenu  $kategoriMenu
     * @return mixed
     */
    public function forceDelete(User $user, KategoriMenu $kategoriMenu)
    {
        //
    }

    private function findRole($user){
        $roles = $user->roleBackoffice();
        foreach($roles as $role){
            foreach($role->child as $child){
                if($child->otorisasi_id == 5 && $child->is_aktif == 1 && $role->is_aktif == 1){
                    return true;
                }
            }
        }
        return false;
    }
}
