<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Models\CsUsers;
use App\Models\CsTransactions;
use App\Models\CsUniqueIds; 
use Hash;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendingEmail;

class CallbackController extends Controller
{
    function shiprocketCallback(Request $request) 
    { 
        CsTransactions::where('trans_id',6)->update(['trans_response'=>json_encode($request->all())]);
        return 'success';
        exit;
    }  
}