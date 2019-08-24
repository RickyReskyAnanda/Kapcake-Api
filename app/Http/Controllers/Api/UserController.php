<?php

namespace App\Http\Controllers\Api;

use Auth;
use DB;
use Hash;
use Validator;
use App\User;
use App\Bisnis;
use App\Role;
use Carbon\Carbon;
use App\Http\Resources\UserLogin as UserLoginResource;
use App\Http\Resources\Bisnis as BisnisResource;
use App\Http\Resources\Outlet as OutletResource;
use App\Notifications\SignupActivate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public $successStatus = 200;

    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6|max:50',
            'remember_me' => 'nullable|boolean'
        ]);

        $credentials = request(['email', 'password']);
        $credentials['is_active'] = 1;
        $credentials['deleted_at'] = null;

        if(Auth::attempt($credentials)){
            
            $user = Auth::user();
            $user->load([
                'bisnis',
                'outlet',
                'role.aplikasi' => function($query){
                    $query->where('aplikasi_id','1');     //// ini belum selesai
                    $query->with('otorisasi.child');
                }]
            );

            return response()->json([ 
                'bisnis' => $user->bisnis ? new BisnisResource($user->bisnis): [], 
                'user' => new UserLoginResource($user), 
                'outlet' => $user->bisnis->outlet ? OutletResource::collection($user->bisnis->outlet) : [], 
                'token' => $user->api_token
            ], $this->successStatus);
        }else{
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'telpon' => 'required|numeric',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'nama_bisnis' => 'required|string',
            'provinsi' => 'required|string',
            'is_setuju' => 'required|numeric',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telpon' => '+62'.$request->telpon,
            'password' => bcrypt($request->password),
            'activation_token' => str_random(60)
        ]);

        $bisnis = Bisnis::create([
            'user_id' => $user->id ?? 0,
            'nama_bisnis' => $request->nama_bisnis,
            'provinsi' => $request->provinsi,
            'is_aktif_pajak' => 0,
            'is_aktif_biaya_tambahan' => 0,
            'is_gabung_biaya_dan_harga' => 0,
            'is_aktif_pembulatan_harga' => 0,
        ]);

        $user->update([
            'bisnis_id' => $bisnis->id_bisnis,
            'role_id' => (
                            $bisnis
                                ->role()
                                ->where('nama_role','Administrator')
                                ->where('is_paten', 1)
                                ->first()
                            )->id_role
        ]);
 
        // $user->notify(new SignupActivate($user));

        return response()->json('success', 200);
    }

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

    public function update(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'telpon' => 'required',
            'alamat' => 'required',
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

    public function changePassword(Request $request){
        $data = $request->validate([
            'current_password' => 'required|min:6|max:32',
            'password' => 'required|min:6|max:32',
            'confirm' => 'required|same:password',
        ]);
        DB::beginTransaction();
        try {   
            $current_password = Auth::User()->password;           
            if(Hash::check($data['current_password'], $current_password)){           
                $user_id = auth()->User()->id;                       
                $obj_user = User::find($user_id);
                $obj_user->password = Hash::make($data['password']);;
                $obj_user->save(); 
            }else{
                return response('error',500);
            }
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function updateOutletTerpilih(Request $request){
        $data = $request->validate([
            'outlet_terpilih_id' => 'required',
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
