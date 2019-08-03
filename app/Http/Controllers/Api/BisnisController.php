<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Bisnis;
use App\Image\BlobImageConvertion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BisnisController extends Controller
{
	public function show(Bisnis $bisnis){
		return $bisnis;
	}

	public function update(Request $request, Bisnis $bisnis){
		$data = $request->validate([
			'data' => 'required',
			'image' => 'nullable'
		]);
		DB::beginTransaction();
		try {   

			$bisnis->update($data['data']);

			if(isset($data['image']) && $data['image'] != null){
				$image = BlobImageConvertion::image($data['image'], 'bisnis');
				if($image){
					$bisnis
					->logo()
					->delete();
				}
				foreach($image as $d){
					$bisnis
					->logo()
					->create($d);    
				}
			}
			DB::commit();
			return response('success',200);
		} catch (\Exception $e) {
			DB::rollback();
			return response('error',500);
		}
	}
}
