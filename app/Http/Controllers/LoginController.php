<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function login()
    {
        $forget_session =  ["user_id", "user_fullname", "user_name", "toko_id", "is_owner", "login"];
        session()->forget($forget_session);
        return view('auth.login');
    }

    public function process_login(Request $request)
    {
        $input = (object) $request->all();
        $check = User::check_login($input);
        if ($check->success) {
            return \redirect($check->url);
        } else {
            return \redirect("login")->with("message", "<script>sweet('error', 'Failed!', '$check->message')</script>");
        }
    }

    public function logout()
    {
        return \redirect("login")->with("message", "<script>sweet('success', 'Success!', 'Anda berhasil logout!')</script>");
    }
}
