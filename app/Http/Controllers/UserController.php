<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function user_management()
    {
      dd('sdasdsad');
        $get_users = User::where('role_as' , '0')->orderBy('id' , 'desc')->get();
        dd($get_users);
         return view('backend.users.index');
    }
  

    public function user_data()
    {
      // return 'dfd';
      // $get_users = User::where('role_as' , '0')->orderBy('id' , 'desc')->get();
      dd('gfgfgfg');
        // return response()->json($users, 200);
      // return view('backend.users');
    }



}
