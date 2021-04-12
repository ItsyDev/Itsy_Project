<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class Supplier extends Model
{
    use SoftDeletes;
    protected $table = 'list_supplier';
    protected $primaryKey = 'supplier_id';
    protected $fillable = ['toko_id', 'supplier_name', 'supplier_phone', 'supplier_address', 'supplier_note', 'is_active'];
    protected $dates = ['deleted_at'];

    private function query_supplier($input)
    {
        $toko_id = session("toko_id");
        $column_order = ["supplier_name"];
        $column_search = ["supplier_name"];
        $order = ["supplier_id" => "DESC"];

        $result = DB::table("list_supplier")
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


    public function get_supplier($input)
    {
        $query = $this->query_supplier($input);
        if ($input->length != -1) {
            $limit = $query->offset($input->start)->limit($input->length);
            // dd($limit->toSql());
            return $limit->get();
        }
    }

    public static function get_supplier_store()
    {
        $toko_id = session("toko_id");
        $query = DB::table("list_supplier")->select("list_supplier.*", "list_supplier.supplier")
            ->join("list_supplier", "list_supplier.supplier_id", "=", "list_supplier.supplier_id")
            ->where("list_supplier.toko_id", $toko_id)->get();
        return $query;
    }

    public static function get_supplier_all()
    {
        $toko_id = session("toko_id");
        return DB::table("list_supplier")->where("toko_id", $toko_id)->get();
    }

    public function get_supplier_count($input)
    {
        $query = $this->query_supplier($input);

        return $query->count();
    }

    public static function add_supplier($input)
    {
        $response = \create_response();
        try {
            $toko_id = session("toko_id");
            self::insert([
                "toko_id" => $toko_id,
                "supplier_name" => $input->supplier_name,
                "supplier_phone" => $input->supplier_phone,
                "supplier_address" => $input->supplier_address,
                "supplier_note" => $input->supplier_note,
                "is_active" => $input->is_active
            ]);

            $response->id = get_return_id();
            $response->success = TRUE;
            $response->message = "Success added new category!";
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }

        return $response;
    }

    public static function get_supplier_detail($supplier_id)
    {
        $response = create_response();
        try {
            $result = DB::table("list_supplier")->where("supplier_id", $supplier_id)->first();
            $response->success = TRUE;
            $response->data = $result;
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }
        return $response;
    }

    public static function edit_supplier($input)
    {
        $response = \create_response();
        try {
            self::where("supplier_id", $input->supplier_id)
                ->update([
                    "supplier_name" => $input->supplier_name,
                    "supplier_phone" => $input->supplier_phone,
                    "supplier_address" => $input->supplier_address,
                    "supplier_note" => $input->supplier_note,
                    "is_active" => $input->is_active
                ]);

            $response->success = TRUE;
            $response->message = "Success updated supplier!";
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }
        return $response;
    }
    public static function delete_supplier($supplier_id)
    {
        $response = \create_response();
        try {
            self::find($supplier_id)->delete();

            $response->success = TRUE;
            $response->message = "Success deleted supplier!";
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }
        return $response;
    }
}
