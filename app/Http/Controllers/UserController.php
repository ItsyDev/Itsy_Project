<?php

namespace App\Http\Controllers;

use App\Services\Addon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
    private $addon, $model;

    public function __construct(Addon $addon)
    {
        $this->addon = $addon;
        $this->model = new User();
    }

    public function view_user_management()
    {
        $data = [
            "title" => "Administration",
            "list_access" => User::get_access_list(80),
            "breadcrumb" => $this->addon->draw_breadcrumb("Administration", "/administrator", TRUE),
            "user_login" => $this->addon->get_user_login()
        ];
        return view("admin.Administrator.index", $data);
    }

    public function get_user_listed(Request $request)
    {
        $input = (object) $request->all();
        $results = $this->model->get_user_listed($input);
        $data = [];
        $no = $input->start;
        foreach ($results as $item) {
            $no++;
            $id = \encrypt_url($item->user_id);
            $row = [];
            $row[] = $no;
            $row[] = $item->user_fullname;
            $row[] = $item->user_name;
            $row[] = $item->user_email;
            $row[] = $item->user_phone;
            $row[] = $item->user_status;
            $row[] = $item->level_name;
            $button = "<button class='btn btn-info btn-sm m-1' onclick=\"navigateTo('user/$id')\">Detail</button>";
            if ($item->user_id != \session("user_id")) {
                $button .= $item->user_status_id == 1 ? "<button class='btn btn-danger btn-sm m-1' onclick=\"changeStatusUser('user/" . \encrypt_url($item->user_id) . "/deadactive', true)\">Non Active</button>" : "<button class='btn btn-primary btn-sm m-1' onclick=\"changeStatusUser('user/" . \encrypt_url($item->user_id) . "/active', false)\">Active</button>";
            }
            if ($request->session()->get("admin_level") >= 90 && $item->user_id != $request->session()->get("user_id")) {
                $button .= "<button class='btn btn-danger btn-sm m-1' onclick=\"promptDeleteUser('$id')\">Delete</button>";
            }
            $row[] = $button;
            $data[] = $row;
        }

        $output = [
            "draw" => $input->draw,
            "recordsTotal" => $this->model->get_user_filter_count($input),
            "recordsFiltered" => $this->model->get_user_filter_count($input),
            "data" => $data
        ];

        return \response()->json($output);
    }

    public function validation_user_add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "user_fullname" => "required|alpha_spaces",
            "user_email" => "required|email",
            "user_phone" => "required|numeric",
            "user_address" => "required",
            "user_name" => "required|alpha_num_spaces",
            "user_password" => "required|alpha_num_spaces|confirmed"
        ]);

        if ($validate->fails()) {
            return \redirect("administrator")->withErrors($validate)->withInput()->with("message", "<script>sweet('error', 'Gagal!', 'Data tidak lengkap!')</script>");
        } else {
            $input = (object) $request->all();
            $check = User::add_user_from_owner($input);

            if ($check->success) {
                return \redirect()->to("user/" . \encrypt_url($check->id))->with("message", "<script>sweet('success', 'Success!', '$check->message')</script>");
            } else {
                return \redirect()->back()->with("message", "<script>sweet(\"error\", \"Failed!\", \"$check->message\")</script>");
            }
        }
    }

    public function view_user_detail($id)
    {
        $user_id = decrypt_url($id);
        $check = User::get_user_detail($user_id);
        if ($check->success === TRUE) {
            $data = [
                "title" => "Administration",
                "list_access" => User::get_access_list($check->data->admin_level),
                "breadcrumb" => $this->addon->draw_breadcrumb("User Detail", "/user-detail/$id"),
                "user_login" => $this->addon->get_user_login(),
                "user" => $check->data
            ];

            return view("admin.Administrator.detail", $data);
        }
        return \redirect()->back();
    }

    public function process_user_edit(Request $request, $id)
    {
        $user_id = \decrypt_url($id);
        $validate = Validator::make($request->all(), [
            "user_fullname" => "required|alpha_spaces",
            "user_email" => "required|email",
            "user_phone" => "required|numeric",
            "user_address" => "required",
            "user_name" => "required|alpha_num_spaces",
            "user_photo" => "max:5048|image",
            "user_password" => "confirmed"
        ]);

        if ($validate->fails()) {
            // dd($validate->errors());
            return \redirect("user/$id")->withErrors($validate)->withInput()->with("message", "<script>sweet('error', 'Failed!', 'Input not completed!')</script>");
        } else {
            $input = (object) $request->all();
            $input->user_id = $user_id;
            $insert = User::edit_user($input);

            if ($insert->success) {
                return \redirect("user/$id")->with("message", "<script>sweet('success', 'Success!', '$insert->message')</script>");
            }
            return \redirect("user/$id")->with("message", "<script>sweet('error', 'Failed!', '$insert->message')</script>");
        }
    }

    public function process_user_deadactive($id)
    {
        $user_id = \decrypt_url($id);
        $change_status = User::change_user_status($user_id, 2);
        if ($change_status->success) {
            $data = [
                "success" => 200,
                "message" => $change_status->message
            ];
        } else {
            $data = [
                "success" => 201,
                "message" => $change_status->message
            ];
        }
        return \response()->json($data);
    }

    public function process_user_active($id)
    {
        $user_id = \decrypt_url($id);
        $change_status = User::change_user_status($user_id, 1);
        if ($change_status->success) {
            $data = [
                "success" => 200,
                "message" => $change_status->message
            ];
        } else {
            $data = [
                "success" => 201,
                "message" => $change_status->message
            ];
        }
        return \response()->json($data);
    }

    public function process_user_delete($id)
    {
        $check = User::delete_user(decrypt_url($id));
        if ($check->success) {
            $response = [
                "success" => 200,
                "message" => $check->message,
                "csrf_hash" => csrf_token()
            ];
        } else {
            $response = [
                "success" => 201,
                "message" => $check->message,
                "csrf_hash" => csrf_token()
            ];
        }

        return \response()->json($response);
    }
}
