<?php 
namespace App\Http\Controllers\frontend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

use DB;
use Hash;
use Session;


class EmailController extends Controller
{
    public function index($filename){  
        return view('frontend.email.'.$filename);
    }
 
}