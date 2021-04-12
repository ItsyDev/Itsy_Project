<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Services\Addon;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    private $model, $addon;

    public function __construct(Addon $addon)
    {
        $this->model = new Supplier();
        $this->addon = $addon;
    }

    public function view_supplier(Addon $addon)
    {
        // $this->addon = $addon;
        $data = [
            "title" => "Supplier Management",
            "breadcrumb" => $this->addon->draw_breadcrumb("Supplier Management", "/supplier", TRUE),
            "user_login" => $this->addon->get_user_login()
        ];
        return view("admin.Supplier.index", $data);
    }
    public function get_supplier(Request $request)
    {
        $input = (object) $request->all();
        $results = $this->model->get_supplier($input);
        $data = [];
        $no = $input->start;
        foreach ($results as $item) {
            $no++;
            $id = \encrypt_url($item->supplier_id);
            $row = [];
            $row[] = $no;
            $row[] = $item->supplier_name;
            $row[] = $item->is_active == 1 ? 'Active' : 'Non Active';
            $button = "<button class='btn btn-info btn-sm m-1' onclick=\"navigateTo('supplier/$id')\">Detail</button>";
            $button .= "<button class='btn btn-danger btn-sm m-1' onclick=\"promptDeleteSupplier('$id')\">Delete</button>";
            // $button = "<button class='btn btn-info btn-sm m-1' onclick=\"navigateTo('supplier/" . encrypt_url($item->supplier_id) . "')\">Detail</button>";
            // $button = "<button class='btn btn-info btn-sm m-1' onclick=\"getCategoryDetail($item->supplier_id)\"><i class='fas fa-edit fa-sm'></i></button>";
            $row[] = $button;
            $data[] = $row;
        }

        $output = [
            "draw" => $input->draw,
            "recordsTotal" => $this->model->get_supplier_count($input),
            "recordsFiltered" => $this->model->get_supplier_count($input),
            "data" => $data
        ];

        return \response()->json($output);
    }

    public function view_supplier_detail($id)
    {
        $supplier_id = decrypt_url($id);
        $check = Supplier::get_supplier_detail($supplier_id);
        $data = [
            "title" => "Supplier",
            "breadcrumb" => $this->addon->draw_breadcrumb("Supplier Detail", "/supplier-detail/$id"),
            "supplier" => $check->data,
            "user_login" => $this->addon->get_user_login()
        ];
        return view("admin.Supplier.detail", $data);
    }

    public function process_supplier_add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "supplier_name" => "required",
            "supplier_phone" => "required|numeric",
            "supplier_address" => "required",
            "supplier_note" => "required",
            "is_active" => "required|numeric"
        ]);

        if ($validate->fails()) {
            return \redirect()->back()->withErrors($validate)->withInput()->with("message", "<script>sweet('error', 'Failed!', 'Input not completed!')</script>");
        } else {
            $input = (object) $request->all();
            $check = Supplier::add_supplier($input);
            if ($check->success = TRUE) {
                $id = \encrypt_url($check->id);
                return \redirect()->to("/supplier/$id")->with("message", "<script>sweet('success', 'Success!', 'Success add new supplier!')</script>");
            } else {
                return \redirect()->back()->withInput()->with("message", "<script>sweet('error', 'Failed!', '$check->message')</script>");
            }
        }
    }

    public function get_supplier_detail($id)
    {
        $check = Supplier::get_supplier_detail($id);
        if ($check->message) {
            $response = [
                "success" => 200,
                "data" => $check->data
            ];
        } else {
            $response = [
                "success" => 201,
                "message" => $check->message
            ];
        }
        return \response()->json($response);
    }

    public function process_supplier_edit(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            "supplier_name" => "required",
            "supplier_phone" => "required|numeric",
            "supplier_address" => "required",
            "supplier_note" => "required"
        ]);

        if ($validate->fails()) {
            return \redirect("supplier/$id")->withErrors($validate)->withInput()->with("message", "<script>sweet('error', 'Failed!', 'Input not valid!')</script>");
        } else {
            $input = (object) $request->all();
            $input->supplier_id = \decrypt_url($id);
            $check = Supplier::edit_supplier($input);
            if ($check->success = TRUE) {
                return \redirect("supplier/$id")->with("message", "<script>sweet('success', 'Success!', '$check->message')</script>");
            } else {
                return \redirect("supplier/$id")->with("message", "<script>sweet('error', 'Failed!', '$check->message')</script>");
            }
        }
    }
    public function process_supplier_delete($id)
    {
        $check = Supplier::delete_supplier(decrypt_url($id));
        if ($check->success) {
            $response = [
                "success" => 200,
                "message" => $check->message,
                "csrf_hash" => csrf_token()
            ];
        } else {
            $response = [
                "success" => 200,
                "message" => $check->message,
                "csrf_hash" => csrf_token()
            ];
        }

        return \response()->json($response);
    }
}
