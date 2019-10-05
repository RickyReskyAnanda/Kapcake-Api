<?php

namespace App\Http\Controllers\Kasir;

use Auth;
use DB;
// use Hash;
// use Validator;
use App\User;
use App\Perangkat;
// use App\Bisnis;
// use App\Role;
use Carbon\Carbon;
use App\Http\Resources\Kasir\UserLogin as UserLoginResource;
// use App\Http\Resources\Bisnis as BisnisResource;
// use App\Http\Resources\Outlet as OutletResource;
// use App\Notifications\SignupActivate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public $successStatus = 200;
    public $userLimit = 5;

    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'operating_system' => 'required|string',
            'mac' => 'required|string',
            'remember_me' => 'nullable|boolean'
        ]);

        $credentials = request(['email', 'password']);
        $credentials['is_active'] = 1;
        $credentials['deleted_at'] = null;
        $bisnisId = (User::where('email', $request->email)->first())->bisnis_id ?? 0;
        if($bisnisId == 0 )
            return response()->json([
                'message' => 'Unauthorized'
            ], 204);

        $jumlahPerangkat = Perangkat::where('bisnis_id',  $bisnisId)
                                    ->where('perangkat', 'pos')
                                    ->where('is_aktif', '1')
                                    ->where('email','<>',$request->email)
                                    ->count();
        $kondisi = '';
        if($jumlahPerangkat >= $this->userLimit){
            return response()->json([
                'message' => 'Limited Access'
            ], 403);
        }else{
            $perangkat  = Perangkat::where('bisnis_id',  $bisnisId)
                                        ->where('perangkat', 'pos')
                                        ->where('email', $request->email)
                                        ->where('mac', $request->mac)
                                        ->get();

            if(count($perangkat) == 0){
                $kondisi = 'create';
            }else if(count($perangkat) > 0){
                $kondisi = 'next';
            }
        }
        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $user->load([
                'outlet'
                ]
            );

            if($kondisi == 'create')
                $perangkat[0] = Perangkat::create([
                    'bisnis_id' => $user->bisnis_id,
                    'email' => $user->email,
                    'operating_system' => $request->operating_system,
                    'perangkat' => 'pos',
                    'mac' => $request->mac,
                    'is_aktif' =>  '1'
                ]);
            else
                Perangkat::where('bisnis_id',  $bisnisId)
                        ->where('perangkat', 'pos')
                        ->where('email', $request->email)
                        ->where('mac', $request->mac)
                        ->update([
                            'is_aktif' => 1
                        ]);

            return response()->json([ 
                'user' => new UserLoginResource($user),
                'perangkat' => $perangkat[0],
                'token' => $user->api_token
            ], $this->successStatus);
        }else{
            return response()->json([
                'message' => 'Unauthorized'
            ], 204);
        }
    }

    // public function signup(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string',
    //         'telpon' => 'required|numeric',
    //         'email' => 'required|string|email|unique:users',
    //         'password' => 'required|string|min:6',
    //         'nama_bisnis' => 'required|string',
    //         'provinsi' => 'required|string',
    //         'is_setuju' => 'required|numeric',
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'telpon' => '+62'.$request->telpon,
    //         'password' => bcrypt($request->password),
    //         'activation_token' => str_random(60)
    //     ]);

    //     $bisnis = Bisnis::create([
    //         'user_id' => $user->id ?? 0,
    //         'nama_bisnis' => $request->nama_bisnis,
    //         'provinsi' => $request->provinsi,
    //         'is_aktif_pajak' => 0,
    //         'is_aktif_biaya_tambahan' => 0,
    //         'is_gabung_biaya_dan_harga' => 0,
    //         'is_aktif_pembulatan_harga' => 0,
    //     ]);

    //     $user->update([
    //         'bisnis_id' => $bisnis->id_bisnis,
    //         'role_id' => (
    //                         $bisnis
    //                             ->role()
    //                             ->where('nama_role','Administrator')
    //                             ->where('is_paten', 1)
    //                             ->first()
    //                         )->id_role
    //     ]);
 
    //     // $user->notify(new SignupActivate($user));

    //     return response()->json('success', 200);
    // }

    public function profil()
    {
        $user = auth()->user();
        $user->load([
            'bisnis',
            'outlet',
            'role.aplikasi' => function($query){
                $query->where('aplikasi_id','1');    
                $query->with('otorisasi.child');
            }]
        );
        return response()->json([ 
            'bisnis' => $user->bisnis ? new BisnisResource($user->bisnis): [], 
            'user' => new UserLoginResource($user), 
            'outlet' => $user->bisnis->outlet ? OutletResource::collection($user->bisnis->outlet) : [], 
        ], $this->successStatus);
    }

    public function updateProfil(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'pin' => 'required',
            'alamat' => 'nullable',
        ]);
        DB::beginTransaction();
        try {   
            $request->user()->update($data);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function updatePassword(Request $request){
        $data = $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|different:password_lama',
            'confirm' => 'required|same:password_baru',
        ]);
        DB::beginTransaction();
        try {   
            if(\Hash::check($request->password_lama, $request->user()->password)){
                $request->user()->update([
                    'password' => bcrypt($request->password_baru)
                ]);
            }else{
                DB::rollback();
                return response('wrong',500); 
            }
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    // public function changePassword(Request $request){
    //     $data = $request->validate([
    //         'current_password' => 'required|min:6|max:32',
    //         'password' => 'required|min:6|max:32',
    //         'confirm' => 'required|same:password',
    //     ]);
    //     DB::beginTransaction();
    //     try {   
    //         $current_password = Auth::User()->password;           
    //         if(Hash::check($data['current_password'], $current_password)){           
    //             $user_id = auth()->User()->id;                       
    //             $obj_user = User::find($user_id);
    //             $obj_user->password = Hash::make($data['password']);;
    //             $obj_user->save(); 
    //         }else{
    //             return response('error',500);
    //         }
    //         DB::commit();
    //         return response('success',200);
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return response('error',500);
    //     }
    // }

    // public function updateOutletTerpilih(Request $request){
    //     $data = $request->validate([
    //         'outlet_terpilih_id' => 'required',
    //     ]);
    //     DB::beginTransaction();
    //     try {   
    //         $userData = User::find(auth()->user()->id);
    //         $userData->update($data);
    //         DB::commit();
    //         return response('success',200);
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return response('error',500);
    //     }
    // }

    public function updateJenisItemTerpilih(Request $request){
        $data = $request->validate([
            'jenis_item_terpilih' => 'required',
        ]);
        DB::beginTransaction();
        try {   
            $userData = User::find(auth()->user()->id);
            $userData->update($data);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }
}
