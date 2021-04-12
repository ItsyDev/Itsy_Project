<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\Addon;
use Illuminate\Support\Facades\Validator;

// Semua Barang, ganti ke Product (Nama saja)

class ProductController extends Controller
{
    private $model, $addon;

    public function __construct(Addon $addon)
    {
        $this->model = new Product();
        $this->addon = $addon;
    }

    public function view_product_category(Addon $addon)
    {
        // $this->addon = $addon;
        $data = [
            "title" => "Category product",
            "breadcrumb" => $this->addon->draw_breadcrumb("Category product", "/category-product", TRUE),
            "user_login" => $this->addon->get_user_login()
        ];
        return view("admin.Product_Category.index", $data);
    }

    public function get_product_category(Request $request)
    {
        $input = (object) $request->all();
        $results = $this->model->get_category_product($input);
        $data = [];
        $no = $input->start;
        foreach ($results as $item) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $item->product_category;
            $button = "<button class='btn btn-info btn-sm m-1' onclick=\"getCategoryDetail($item->product_category_id)\"><i class='fas fa-edit fa-sm'></i></button>";
            $button .= "<button class='btn btn-danger btn-sm m-1' onclick=\"deleteCategory($item->product_category_id)\"><i class='fas fa-trash fa-sm'></i></button>";
            $row[] = $button;
            $data[] = $row;
        }

        $output = [
            "draw" => $input->draw,
            "recordsTotal" => $this->model->get_category_product_count($input),
            "recordsFiltered" => $this->model->get_category_product_count($input),
            "data" => $data
        ];

        return \response()->json($output);
    }

    public function process_product_category_add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "product_category" => "required|alpha_spaces"
        ]);

        if ($validate->fails()) {
            $response = [
                "success" => 201,
                "message" => "Data not valid!",
                "error" => $validate->errors(),
                "csrf_hash" => csrf_token()
            ];

            return \response()->json($response);
        } else {
            $input = (object) $request->all();
            $check = Product::add_product_category($input);
            if ($check->success = TRUE) {
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

    public function process_product_category_delete($id)
    {
        $check = Product::delete_product_category($id);
        if ($check->message) {
            $response = [
                "success" => 200,
                "message" => $check->message
            ];
        } else {
            $response = [
                "success" => 201,
                "message" => $check->message
            ];
        }
        return \response()->json($response);
    }

    public function get_product_category_detail($id)
    {
        $check = Product::get_product_category_detail($id);
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

    public function process_product_category_edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "product_category" => "required|alpha_spaces",
            "product_category_id" => "required|numeric"
        ]);

        if ($validate->fails()) {
            $response = [
                "success" => 201,
                "message" => $validate->errors()->all(),
                "csrf_hash" => csrf_token()
            ];

            return \response()->json($response);
        } else {
            $input = (object) $request->all();
            $check = Product::edit_product_category($input);
            if ($check->success = TRUE) {
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

    // public function index()
    // {
    //     $product = Barang::get();
    //     return view('dashboard.barang.index', compact('barang'));
    // }

    // public function create()
    // {
    //     return view('dashboard.barang.create');
    // }

    // public function store(Request $request)
    // {
    //     Barang::create($request->all());
    //     if ($request->hasFile('photo_path')) {
    //         $request->file('photo_path')->move('images/', $request->file('photo_path')->getClientOriginalName());
    //         $barang->photo_path = $request->file('photo_path')->getClientOriginalName();
    //         $barang->save();
    //     }
    //     return redirect('/barang')->with('sukses', 'Data Berhasil Di Tambahkan');
    // }

    // public function show($id)
    // {
    //     //
    // }

    // public function edit($id)
    // {
    //     $barang = \App\Barang::find($id);
    //     return view('dashboard.barang.edit', compact('barang'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $barang = \App\Barang::find($id);
    //     $barang->update($request->all());
    //     if ($request->hasFile('photo_path')) {
    //         $request->file('photo_path')->move('images/', $request->file('photo_path')->getClientOriginalName());
    //         $barang->photo_path = $request->file('photo_path')->getClientOriginalName();
    //         $barang->save();
    //     }
    //     return redirect('/barang')->with('sukses', 'Data Berhasil Diupdate');
    // }

    // public function delete($id)
    // {
    //     $barang = Barang::find($id);
    //     $barang->delete();
    //     return redirect('/barang')->with('sukses', 'Data Berhasil diHapus');
    // }
}
