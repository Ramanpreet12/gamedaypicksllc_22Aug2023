<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\Visitor;

class VisitorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // if (Auth::check() && Auth::user()->role_as == 1) {
        //         return $next($request);

        // } else {
        //     return redirect('admin/login')->with('status' , 'You have not admin access');
        // }


        // $ip = $request->ip();
        // // $ip = hash('sha512', $request->ip());
        // $check_ip_address = Visitor::where('ip_address', $ip)->first();
        // // dd($check_ip_address);
        // if ($check_ip_address)
        // {
        //     Visitor::create([
        //         'ip_address' => $ip,
        //     ]);

        //     // Visitor::firstOrCreate(['ip_address' => $ip]);
           

        // }
        // // else {
        // //     return redirect()->route('home');
        // // }

        // return $next($request);

       
     //    $ip = $request->ip();
      //   $get_visitor = Visitor::where('ip_address' , '=' , $ip)->first();
      //  if ($get_visitor)
     //   {
            // Visitor::create([
            //     'ip_address' => $ip,
            // ]);
       //     return $next($request);
            // return redirect()->route('visitors');
       // }
       // else{
      //      return redirect()->route('visitors');
       // }
        return $next($request);
        // return $next($request);



    }
}
