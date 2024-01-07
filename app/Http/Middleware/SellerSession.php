<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Route;

class SellerSession
{
    public function handle(Request $request, Closure $next)
    {
	    if (!$request->session()->exists('CS_SELLER'))
        {
            return redirect()->route('seller.login');// here you should redirect to login 
        }
			
        return $next($request);
    }
}
