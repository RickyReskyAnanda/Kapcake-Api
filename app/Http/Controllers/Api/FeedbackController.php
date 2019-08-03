<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        
        if(isset($request->paginate) && $request->paginate == 'true')
            return $request->user()->bisnis
                    ->feedback()
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
                    ->paginate();
        else
            return $request->user()->bisnis
                    ->feedback()
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
                    ->get();
    }

    public function store(Request $request)
    {
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            $feedback = $request->user()->bisnis
                            ->feedback()
                            ->create([
                                'nama_feedback' => $data['nama_feedback'],
                                'jumlah' => $data['jumlah']
                            ]);
            foreach ($data['outlet'] as $d) {
                $feedback
                    ->outlet()
                    ->create($d);
            }
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function show(Feedback $feedback)
    {
        return $feedback->load('outlet');
    }

    public function update(Request $request, Feedback $feedback)
    {
        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $feedback
                ->update([
                    'nama_feedback' => $data['nama_feedback'],
                    'jumlah' => $data['jumlah']
                ]);

            $idOutlet = [];
            foreach ($data['outlet'] as $d) {
                $opsi = $feedback
                    ->outlet()
                    ->updateOrCreate($d, $d);
                array_push($idOutlet, $opsi['id_outlet_feedback']); 
            }
            $feedback
                ->outlet()
                ->whereNotIn('id_outlet_feedback', $idOutlet)
                ->delete();

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(Feedback $feedback)
    {
        DB::beginTransaction();
        try {
            $feedback->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama_feedback' => 'required|max:50',
            'jumlah' => 'required|numeric|max:100',
            'outlet' => 'required',
        ];
    }
}
