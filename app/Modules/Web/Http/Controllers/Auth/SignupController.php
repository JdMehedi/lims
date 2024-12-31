<?php

namespace App\Modules\Web\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Models\User;
use App\Modules\Users\Models\UserTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use function view;

class SignupController extends Controller
{
    function signupView() {
        return view('public_home.signup');
    }

    function signupEmailView() {
        return view('public_home.signup-email');
    }

    function signupMobileView() {
        return view('public_home.signup-mobile');
    }

    public function signupEmailAction(Request $request){

        try {

            $addUserObj = new User();
            $addUserObj->user_type = '5x505';
            $addUserObj->user_first_name = $request->name;
            $addUserObj->user_email = $request->email;
            $addUserObj->user_mobile = $request->mobile;
            $addUserObj->user_DOB = $request->gender;
            $pass = rand(000000,999999);
            $addUserObj->password = Hash::make($pass);
            $addUserObj->save();

//            dd($addUserObj->id,$pass);


//            return view("Users::new-user", compact("user_types", "logged_user_type"));
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage() . '[UC-1127]');
            return \redirect()->back();
        }

    }
}
