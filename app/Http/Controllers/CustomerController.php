<?php

namespace App\Http\Controllers;

use App\Services\Addon;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Rajaongkir;

class CustomerController extends Controller
{
    private $addon, $model;

    public function __construct(Addon $addon)
    {
        $this->addon = $addon;
        $this->model = new Customer();
    }

    public function view_customer_management()
    {
        $data = [
            "title" => "Customer Management",
            "breadcrumb" => $this->addon->draw_breadcrumb("Customer Management", "/customer", TRUE),
            "user_login" => $this->addon->get_user_login()
        ];
        return view("admin.Customer.index", $data);
    }

    public function get_customer_listed(Request $request)
    {
        $input = (object) $request->all();
        $results = $this->model->get_customer_listed($input);
        $data = [];
        $no = $input->start;
        foreach ($results as $item) {
            $no++;
            $id = \encrypt_url($item->customer_id);
            $row = [];
            $row[] = $no;
            $row[] = $item->customer_name;
            $row[] = $item->customer_phone;
            $row[] = !empty($item->level_name) ? $item->level_name : "Customer";
            $row[] = $item->is_active == 1 ? "Active" : "Non-Active";
            $button = "<button class='btn btn-info btn-sm m-1' onclick=\"navigateTo('customer/$id')\"><i class='fas fa-eye fa-sm'></i></button>";
            $button .= "<button class='btn btn-danger btn-sm m-1'><i class='fas fa-trash fa-sm' onclick=\"promptDeleteCustomer('$id')\"></i></button>";
            $row[] = $button;
            $data[] = $row;
        }

        $output = [
            "draw" => $input->draw,
            "recordsTotal" => $this->model->get_customer_filter_count($input),
            "recordsFiltered" => $this->model->get_customer_filter_count($input),
            "data" => $data
        ];

        return \response()->json($output);
    }

    public function view_customer_add()
    {
        $data = [
            "title" => "Add Customer",
            "breadcrumb" => $this->addon->draw_breadcrumb("Add", "/add-customer"),
            "user_login" => $this->addon->get_user_login(),
            "list_province" => Rajaongkir::get_province()
        ];
        return view("admin.Customer.create", $data);
    }

    public function ajax_get_district($province_id)
    {
        $get_district = Rajaongkir::get_district($province_id);
        if ($get_district->success) {
            $response = [
                "success" => TRUE,
                "data" => $get_district->option
            ];
        } else {
            $response = [
                "success" => FALSE,
                "message" => "Error!"
            ];
        }

        return \response()->json($response);
    }

    public function ajax_get_subdistrict($district_id)
    {
        $get_subdistrict = Rajaongkir::get_subdistrict($district_id);
        if ($get_subdistrict->success) {
            $response = [
                "success" => TRUE,
                "data" => $get_subdistrict->option
            ];
        } else {
            $response = [
                "success" => FALSE,
                "message" => "Error!"
            ];
        }

        return \response()->json($response);
    }

    public function validation_customer_add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "customer_name" => "required|alpha_num_spaces",
            "customer_phone" => "required|numeric",
            "customer_note" => "required",
            "is_active" => "required|numeric",
            "province_id.*" => "required",
            "district_id.*" => "required",
            "subdistrict_id.*" => "required",
            "full_address.*" => "required"
        ]);

        if ($validate->fails()) {
            // dd($validate->errors());
            return \redirect("add-customer")->withErrors($validate)->withInput()->with("message", "<script>sweet('error', 'Gagal!', \"Input yang anda masukan salah!\")</script>");
        } else {
            $input = (object) $request->all();
            // echo "Masuk input";
            return $this->process_customer_add($input);
        }
    }

    private function process_customer_add($input)
    {
        $check = Customer::add_customer($input);
        if ($check->success) {
            $id = encrypt_url($check->id);
            return \redirect()->to("/customer/$id")->with("message", "<script>sweet(\"success\", \"Success!\", '$check->message')</script>");
        }
        return \redirect()->back()->with("message", "<script>sweet(\"error\", \"Failed!\", \"$check->message\")</script>");
    }

    public function view_customer_detail($customer_id)
    {
        $id = \decrypt_url($customer_id);
        $check = Customer::get_customer_detail($id);
        if ($check->success) {
            $data = [
                "title" => "Customer Detail",
                "breadcrumb" => $this->addon->draw_breadcrumb("Customer Detail", "/customer-detail/$customer_id"),
                "user_login" => $this->addon->get_user_login(),
                "customer" => $check->data,
                "address_shipment" => Customer::get_address_customer($id),
                "address_bill" => Customer::get_address_customer($id, 2),
                "list_province" => Rajaongkir::get_province()
            ];
            return view("admin.Customer.detail", $data);
        }
        return \redirect()->back()->with("message", "<script>sweet(\"error\", \"Failed!\", \"$check->message\")</script>");
    }

    public function validate_customer_address_add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "province_id" => "required",
            "district_id" => "required",
            "subdistrict_id" => "required",
            "full_address" => "required",
            "customer_id" => "required|alpha_num",
            "address_type_id" => "required|numeric",
        ]);

        if ($validate->fails()) {
            $id = $request->post("customer_id");
            // dd($validate->errors());
            return \redirect("customer-detail/$id")->withErrors($validate)->withInput()->with("message", "<script>sweet('error', 'Gagal!', \"Input yang anda masukan salah!\")</script>");
        } else {
            $input = (object) $request->all();
            // echo "Masuk input";
            return $this->process_customer_address_add($input);
        }
    }

    private function process_customer_address_add($input)
    {
        $check = Customer::add_customer_address($input);
        if ($check->success) {
            $id = $input->customer_id;
            return \redirect()->to("/customer/$id")->with("message", "<script>sweet(\"success\", \"Success!\", '$check->message')</script>");
        }
        return \redirect()->back()->with("message", "<script>sweet(\"error\", \"Failed!\", \"$check->message\")</script>");
    }

    public function process_change_default_address_shipment($customer_id, $address_id)
    {
        $cust_id = \decrypt_url($customer_id);
        $addr_id = \decrypt_url($address_id);
        // dd($cust_id);
        $check = Customer::change_default_address_customer($cust_id, $addr_id, 1);
        if ($check->success) {
            $data = [
                "success" => TRUE,
                "message" => $check->message
            ];
        } else {
            $data = [
                "success" => FALSE,
                "message" => $check->message
            ];
        }

        return response()->json($data);
    }

    public function process_change_default_address_bill($customer_id, $address_id)
    {
        $cust_id = \decrypt_url($customer_id);
        $addr_id = \decrypt_url($address_id);
        // dd($cust_id);
        $check = Customer::change_default_address_customer($cust_id, $addr_id, 2);
        if ($check->success) {
            $data = [
                "success" => TRUE,
                "message" => $check->message
            ];
        } else {
            $data = [
                "success" => FALSE,
                "message" => $check->message
            ];
        }

        return response()->json($data);
    }

    public function validate_customer_edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "customer_name" => "required|alpha_num_spaces",
            "customer_phone" => "required|numeric",
            "customer_note" => "required",
            "is_active" => "required|numeric"
        ]);

        if ($validate->fails()) {
            // dd($validate->errors());
            return \redirect()->back()->withErrors($validate)->withInput()->with("message", "<script>sweet('error', 'Gagal!', \"Input yang anda masukan salah!\")</script>");
        } else {
            $input = (object) $request->all();
            // echo "Masuk input";
            return $this->process_customer_edit($input);
        }
    }

    private function process_customer_edit($input)
    {
        $check = Customer::edit_customer($input);
        if ($check->success) {
            return \redirect()->to("/customer/$input->customer_id")->with("message", "<script>sweet('success', 'Success!', '$check->message')</script>");
        }
        return \redirect()->to("/customer/$input->customer_id")->with("message", "<script>sweet('error', 'Failed!', '$check->message')</script>");
    }

    public function process_customer_delete($customer_id)
    {
        $id = \decrypt_url($customer_id);
        $check = Customer::delete_customer($id);
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
        return response()->json($response);
    }
}
