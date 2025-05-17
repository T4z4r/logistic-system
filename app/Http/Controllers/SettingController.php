<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    
    public function mode(Request $request){
            $user=User::where('id',Auth::user()->id)->first();
            $user->mode=$request->mode;
            $user->update();

            return back();
    }
}
