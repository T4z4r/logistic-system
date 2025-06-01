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
     if( $role=='accountant')
     {
        return view('dashboard.accountant');
     }
     if($role=='operation-manager'|| $role=='dispatcher')
     {
         return view('dashboard.fleet');
     }
     else
     {
        return view('dashboard');
     }

    }
}
