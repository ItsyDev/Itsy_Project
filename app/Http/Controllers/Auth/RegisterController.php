<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Addon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class RegisterController extends Controller
{
  private $addon;

  public function __construct(Addon $addon)
  {
    $this->addon = $addon;
  }

  public function view_register()
  {
    $list_category_toko = $this->addon->get_all_toko_category();
    // dd($list_category_toko);
    return view("auth.register", compact(["list_category_toko"]));
  }

  public function process_validate_register(Request $request)
  {
    $validate = Validator::make($request->all(), [
      "user_fullname" => "required|alpha_spaces",
      "user_email" => "required|email|unique:list_user,user_email",
      "user_phone" => "required|numeric",
      "user_address" => "required",
      "user_name" => "required|alpha_num_spaces|unique:list_user,user_name",
      "user_password" => "required|alpha_num_spaces|confirmed",
      "toko_name" => "required|alpha_num_spaces",
      "toko_address" => "required|unique:list_toko,toko_name",
      "category_toko_id" => "required|numeric",
      "province_id" => "required|numeric",
      "district_id" => "required|numeric",
      "toko_address" => "required"
    ]);

    if ($validate->fails()) {
      // dd($validate);
      return \redirect("register")->withErrors($validate)->withInput()->with("message", "<script>sweet('error', 'Gagal!', 'Data tidak lengkap!')</script>");
    } else {
      $input = (object) $request->all();
      $add_user = User::add_user($input);
      if ($add_user->success === TRUE) {
        return \redirect("login")->with("message", "<script>sweet('success', 'Success!', '$add_user->message')</script>");
      } else {
        return \redirect("register")->with("message", "<script>sweet('error', 'Failed!', '$add_user->message')</script>");
      }
    }
  }
}
