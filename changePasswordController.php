<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use App\Models\User;

class ChangePasswordController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index(){
        return view('auth.passwords.change');
    }
    public function changePassword(Request $request){
        // return $request->all();

        $this->validate($request,[
            'oldPassword'=>'required',
            'password'=>'required|confirmed'
        ]);

        $hashedPassword = Auth::user()->password; //if login then can get this static method
        if(Hash::check($request->oldPassword,$hashedPassword)){ //accept two parameter: 2.already has 1. new password
            $user = User::find(Auth::id()); // get current login user id
            $user->password = Hash::make($request->password);
            $user->save();
            Auth::logout(); // update and logout

            return redirect()->route('login')->with('successMsg','Password is change successfully!');
        }else{
            return redirect()->back()->with('errorMsg','Password is invalid!');
        }
    }
}
