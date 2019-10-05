<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Http\Resources\Kasir\Printer as PrinterResource;

class PrinterController extends Controller
{
    // public function index(Request $request){

    //     $data = $request->user()->bisnis
    //             ->printer()
    //             ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : '0')
    //             ->get();

    //     return response()->json([
    //         'data' => $data,
    //     ], 200);
    // }

    public function store(Request $request){
        // $this->authorize('create', Barang::class);
        $data = $request->validate($this->validation());
        // DB::beginTransaction();
        // try {   
            foreach ($data['printer'] as $d) {
                if($d['is_hapus'] == 1 && isset($d['id_printer']) )
                    $request->user()->bisnis
                        ->printer()->where('id_printer',$d['id_printer'])->delete();
                else
                    $request->user()->bisnis
                    ->printer()
                    ->updateOrCreate(
                        [
                            'mac' =>  $d['mac'],
                            'perangkat_id' =>  $data['perangkat_id'],
                            'user_id' =>  $request->user()->id,
                        ],
                        [
                            'perangkat_id' => $data['perangkat_id'],
                            'outlet_id' => $d['outlet_id'],
                            'nama_printer' => $d['nama_printer'],
                            'jenis_printer' =>  $d['jenis_printer'],
                            'lebar_kertas' =>  $d['lebar_kertas'],
                            'mac' =>  $d['mac'],
                        ]
                    );
            }
            // DB::commit();
            $printer = $request->user()->bisnis
                ->printer()
                ->where('perangkat_id',$data['perangkat_id'])
                ->get();
            return response(PrinterResource::collection($printer), 200);
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return response('error',500);
        // }
    }

    private function validation(){
        return [
            'perangkat_id' =>  'required',
            'printer' =>  'required',
        ];
    }
}
