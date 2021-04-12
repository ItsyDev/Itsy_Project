<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{

    public static function get_category_all()
    {
        $toko_id = session("toko_id");
        return DB::table("list_product_category")->where("toko_id", $toko_id)->get();
    }

    private function query_product_category($input)
    {
        $toko_id = session("toko_id");
        $column_order = ["product_category"];
        $column_search = ["product_category"];
        $order = ["product_category_id" => "DESC"];

        $result = DB::table("list_product_category")
            ->where("toko_id", $toko_id)
            ->where(function ($query) use ($column_search, $input) {
                $i = 1;
                foreach ($column_search as $item) {
                    if ($input->search["value"]) {
                        if ($i == 1) {
                            $query->where($item, "like", "%" . $input->search["value"] . "%");
                        } else {
                            $query->orWhere($item, "like", "%" . $input->search["value"] . "%");
                        }
                    }
                }
            });

        if (isset($input->order) && !empty($input->order)) {
            $result->orderBy($column_order[$input->order["0"]["column"] - 1], $input->order["0"]["dir"]);
        } else if (isset($input->order)) {
            $result->orderBy(key($order), $order[key($order)]);
        }
        return $result;
    }

    public function get_category_product($input)
    {
        $query = $this->query_product_category($input);
        if ($input->length != -1) {
            $limit = $query->offset($input->start)->limit($input->length);
            // dd($limit->toSql());
            return $limit->get();
        }
    }

    public function get_category_product_count($input)
    {
        $query = $this->query_product_category($input);

        return $query->count();
    }

    public static function add_product_category($input)
    {
        $response = \create_response();
        try {
            $toko_id = session("toko_id");
            DB::table("list_product_category")->insert([
                "toko_id" => $toko_id,
                "product_category" => $input->product_category
            ]);

            $response->success = TRUE;
            $response->message = "Success added new category!";
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }

        return $response;
    }

    public static function delete_product_category($id)
    {
        $response = create_response();
        try {
            DB::table("list_product_category")->where("product_category_id", $id)->delete();
            $response->success = TRUE;
            $response->message = "Success delete category!";
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }
        return $response;
    }

    public static function get_product_category_detail($id)
    {
        $response = create_response();
        try {
            $result = DB::table("list_product_category")->where("product_category_id", $id)->first();
            $response->success = TRUE;
            $response->data = $result;
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }
        return $response;
    }

    public static function edit_product_category($input)
    {
        $response = \create_response();
        try {
            DB::table("list_product_category")->where("product_category_id", $input->product_category_id)
                ->update([
                    "product_category" => $input->product_category
                ]);

            $response->success = TRUE;
            $response->message = "Success updated category!";
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }

        return $response;
    }
}
