<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Rajaongkir;
use Exception;

class Warehouse extends Model
{
    protected $table = "list_warehouse";
    protected $primaryKey = "warehouse_id";
    protected $fillable = ["toko_id", "warehouse_name", "warehouse_phone", "warehouse_note", "pic_name", "province_id", "province_name", "district_id", "district_name", "subdistrict_id", "subdistrict_name", "user_create", "user_update", "full_address"];

    private function query_get_warehouse($input)
    {
        $toko_id = session("toko_id");
        $column_order = ["warehouse_name", "warehouse_phone", "pic_name", "is_active"];
        $column_search = ["warehouse_name", "warehouse_phone", "pic_name", "is_active"];
        $order = ["warehouse_id" => "DESC"];

        $result = DB::table("list_warehouse")
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

    public function get_warehouse_listed($input)
    {
        $query = $this->query_get_warehouse($input);
        if ($input->length != -1) {
            $limit = $query->offset($input->start)->limit($input->length);
            return $limit->get();
        }
    }

    public function get_warehouse_filter_count($input)
    {
        $query = $this->query_get_warehouse($input);
        return $query->count();
    }

    public static function add_warehouse($input)
    {
        $response = \create_response();
        try {
            $toko_id = session("toko_id");
            $warehouse_exists = DB::table('list_warehouse')->where([
                ["toko_id", "=", $toko_id],
                ["warehouse_name", "=", $input->warehouse_name]
            ]);

            if ($warehouse_exists->count() == 0) {
                $array_province = explode(":", $input->province_id);
                $array_district = explode(":", $input->district_id);
                $array_subdistrict = explode(":", $input->subdistrict_id);

                self::create([
                    "toko_id" => $toko_id,
                    "warehouse_name" => $input->warehouse_name,
                    "warehouse_phone" => $input->warehouse_phone,
                    "pic_name" => $input->pic_name,
                    "warehouse_note" => $input->warehouse_note,
                    "province_id" => \decrypt_url($array_province[0]),
                    "province_name" => $array_province[1],
                    "district_id" => \decrypt_url($array_district[0]),
                    "district_name" => $array_district[1],
                    "subdistrict_id" => \decrypt_url($array_subdistrict[0]),
                    "subdistrict_name" => $array_subdistrict[1],
                    "full_address" => $input->full_address,
                    "user_create" => session("user_id")
                ]);

                $response->id = \get_return_id();
                $response->success = TRUE;
                $response->message = "Success added new warehouse!";
            } else {
                $response->message = "Warehouse already exists!";
            }
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }
        return $response;
    }

    public static function get_warehouse_detail($warehouse_id, $page_edit = FALSE)
    {
        $response = create_response();
        try {
            $warehouse_exists = DB::table('list_warehouse')->where([
                ["warehouse_id", "=", $warehouse_id],
                ["toko_id", "=", session("toko_id")]
            ]);

            if ($warehouse_exists->count() == 1) {
                $response->data = $warehouse = $warehouse_exists->first();

                $district_id = \encrypt_url($warehouse->district_id) . ":Sanjaya";
                $province_id = \encrypt_url($warehouse->province_id) . ":Sanjaya";
                $response->success = TRUE;
                if ($page_edit) {
                    $response->area = [];
                    $response->area["list_province"] = Rajaongkir::get_province();
                    $response->area["list_district"] = Rajaongkir::get_district($province_id);
                    $response->area["list_subdistrict"] = Rajaongkir::get_subdistrict($district_id);
                }
            } else {
                $response->message = "Warehouse tidak ditemukan!";
            }
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }
        return $response;
    }

    public static function edit_warehouse($input)
    {
        $response = \create_response();
        try {
            $toko_id = session("toko_id");
            $warehouse_exists = DB::table('list_warehouse')->where([
                ["toko_id", "=", $toko_id],
                ["warehouse_name", "=", $input->warehouse_name],
                ["warehouse_id", "!=", \decrypt_url($input->warehouse_id)]
            ]);

            if ($warehouse_exists->count() == 0) {
                $array_province = explode(":", $input->province_id);
                $array_district = explode(":", $input->district_id);
                $array_subdistrict = explode(":", $input->subdistrict_id);

                self::where("warehouse_id", \decrypt_url($input->warehouse_id))->update([
                    "warehouse_name" => $input->warehouse_name,
                    "warehouse_phone" => $input->warehouse_phone,
                    "pic_name" => $input->pic_name,
                    "warehouse_note" => $input->warehouse_note,
                    "is_active" => $input->is_active,
                    "province_id" => \decrypt_url($array_province[0]),
                    "province_name" => $array_province[1],
                    "district_id" => \decrypt_url($array_district[0]),
                    "district_name" => $array_district[1],
                    "subdistrict_id" => \decrypt_url($array_subdistrict[0]),
                    "subdistrict_name" => $array_subdistrict[1],
                    "full_address" => $input->full_address,
                    "user_update" => session("user_id")
                ]);

                $response->success = TRUE;
                $response->message = "Success edited warehouse!";
            } else {
                $response->message = "Warehouse already exists!";
            }
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }
        return $response;
    }
}
