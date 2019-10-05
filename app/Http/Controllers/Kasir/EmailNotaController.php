<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\Nota;
use Mail;
class EmailNotaController extends Controller
{
    public function index(Request $request){
      return $request->pesanan;
    	// $request->validate([
    	// 	'email' => 'required',
    	// 	'kode' => 'required',
    	// ]);
    	// $this->from('sender@example.com')
     //                ->view('mails.demo')
     //                ->text('mails.demo_plain')
    	Mail::to('rra.rickyresky@gmail.com')
        ->send(new Nota('a'));
 
      	if (Mail::failures()) {
           return response('error',500);
      	}else{
           return response('success',200);
        }
    }
}
