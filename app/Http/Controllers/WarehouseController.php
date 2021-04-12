<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Services\Addon;
use Illuminate\Support\Facades\Validator;
use App\Models\Rajaongkir;

class WarehouseController extends Controller
{
    private $model, $addon;

    public function __construct(Addon $addon)
    {
        $this->model = new Warehouse();
        $this->addon = $addon;
    }

    public function view_warehouse_management()
    {
        // $this->addon = $addon;
        $data = [
            "title" => "Warehouse Management",
            "breadcrumb" => $this->addon->draw_breadcrumb("Warehouse Management", "/warehouse", TRUE),
            "user_login" => $this->addon->get_user_login()
        ];
        return view("admin.Warehouse.index", $data);
    }

    public function ajax_get_warehouse_listed(Request $request)
    {
        $input = (object) $request->all();
        $results = $this->model->get_warehouse_listed($input);
        $data = [];
        $no = $input->start;
        foreach ($results as $item) {
            $no++;
            $id = \encrypt_url($item->warehouse_id);
            $row = [];
            $row[] = $no;
            $row[] = $item->warehouse_name;
            $row[] = $item->warehouse_phone;
            $row[] = $item->pic_name;
            $row[] = $item->is_active == 1 ? "Active" : "Non-Active";
            $button = "<button class='btn btn-info btn-sm m-1' onclick=\"navigateTo('warehouse/$id')\"><i class='fas fa-eye fa-sm'></i></button>";
            $row[] = $button;
            $data[] = $row;
        }

        $output = [
            "draw" => $input->draw,
            "recordsTotal" => $this->model->get_warehouse_filter_count($input),
            "recordsFiltered" => $this->model->get_warehouse_filter_count($input),
            "data" => $data
        ];

        return \response()->json($output);
    }

    public function view_warehouse_add()
    {
        $data = [
            "title" => "Add Warehouse",
            "breadcrumb" => $this->addon->draw_breadcrumb("Add", "/add-warehouse"),
            "user_login" => $this->addon->get_user_login(),
            "list_province" => Rajaongkir::get_province()
        ];
        return view("admin.Warehouse.create", $data);
    }

    public function validate_warehouse_add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "warehouse_name" => "required|alpha_num_spaces",
            "warehouse_phone" => "required|numeric",
            "pic_name" => "required|alpha_spaces",
            "warehouse_note" => "required",
            "province_id" => "required",
            "district_id" => "required",
            "subdistrict_id" => "required",
            "full_address" => "required"
        ]);

        if ($validate->fails()) {
            return \redirect()->back()->withInput()->withErrors($validate)->with("message", "<script>sweet('error', 'Failed!', 'Input not valid!')</script>");
        } else {
            $input = (object) $request->all();
            return $this->process_warehouse_add($input);
        }
    }

    private function process_warehouse_add($input)
    {
        $check = Warehouse::add_warehouse($input);
        if ($check->success) {
            $id = \encrypt_url($check->id);
            return redirect()->to("warehouse/$id")->with("message", "<script>sweet('success', 'Success!', '$check->message')</script>");
        }
        return redirect()->back()->with("message", "<script>sweet(\"error\", \"Failed!\", \"$check->message\")</script>");
    }

    public function view_warehouse_detail($warehouse_id)
    {
        $id = \decrypt_url($warehouse_id);
        $check = Warehouse::get_warehouse_detail($id);
        if ($check->success) {
            $data = [
                "title" => "Warehouse Detail",
                "breadcrumb" => $this->addon->draw_breadcrumb("Detail", "/warehouse/$warehouse_id"),
                "user_login" => $this->addon->get_user_login(),
                "warehouse" => $check->data,
                "warehouse_id" => $warehouse_id
            ];

            return view("admin.Warehouse.detail", $data);
        }
        return \redirect()->back()->with("message", "<script>sweet(\"error\", \"Failed!\", \"$check->message\")</script>");
    }

    public function view_warehouse_edit($warehouse_id)
    {
        $id = \decrypt_url($warehouse_id);
        $check = Warehouse::get_warehouse_detail($id, TRUE);
        if ($check->success) {
            $data = [
                "title" => "Warehouse Detail",
                "breadcrumb" => $this->addon->draw_breadcrumb("Detail", "/warehouse/$warehouse_id"),
                "user_login" => $this->addon->get_user_login(),
                "warehouse" => $check->data,
                "area" => $check->area,
                "warehouse_id" => $warehouse_id
            ];

            return view("admin.Warehouse.edit", $data);
        }
        return \redirect()->back()->with("message", "<script>sweet(\"error\", \"Failed!\", \"$check->message\")</script>");
    }

    public function validate_warehouse_edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "warehouse_id" => "required|alpha_num_spaces",
            "warehouse_name" => "required|alpha_num_spaces",
            "warehouse_phone" => "required|numeric",
            "pic_name" => "required|alpha_spaces",
            "warehouse_note" => "required",
            "is_active" => "required|numeric",
            "province_id" => "required",
            "district_id" => "required",
            "subdistrict_id" => "required",
            "full_address" => "required"
        ]);

        if ($validate->fails()) {
            return \redirect()->back()->withInput()->withErrors($validate)->with("message", "<script>sweet('error', 'Failed!', 'Input not valid!')</script>");
        } else {
            $input = (object) $request->all();
            return $this->process_warehouse_edit($input);
        }
    }

    private function process_warehouse_edit($input)
    {
        $check = Warehouse::edit_warehouse($input);
        if ($check->success) {
            $id = $input->warehouse_id;
            return redirect()->to("warehouse/$id")->with("message", "<script>sweet('success', 'Success!', '$check->message')</script>");
        }
        return redirect()->back()->with("message", "<script>sweet(\"error\", \"Failed!\", \"$check->message\")</script>");
    }
}
