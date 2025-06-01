<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //accessing dashboard
    public function index()
    {
        $role = Auth::user()->getRoleNames()->first();

     if($role=='admin'  ){
           return view('dashboard.admin');
     }
     elseif( $role=='accountant')
     {
        return view('dashboard.accountant');
     }
     else
     {
        return view('dashboard');
     }

    }
}
