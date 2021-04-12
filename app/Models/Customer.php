<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $table = "list_customer";
    protected $primaryKey = "customer_id";
    protected $guarded = ["customer_id", "created_at", "updated_at", "deleted_at"];
    // protected $fillable = ["customer_name", "customer_phone", "customer_note", "toko_id", "access_id", "is_active"];

    private function query_get_customer($input)
    {
        $toko_id = session("toko_id");
        // dd($toko_id);
        $column_order = ["list_customer.customer_name", "list_customer.customer_phone", "list_access_control.level_name"];
        $column_search = ["list_customer.customer_name", "list_customer.customer_phone", "list_access_control.level_name"];
        $order = ["list_customer.customer_id" => "DESC"];

        // DB::enableQueryLog();
        $result = DB::table("list_customer")
            ->leftJoin("list_access_control", "list_access_control.access_id", "=", "list_customer.access_id")
            ->where([
                ["list_customer.deleted_at", "=", NULL],
                ["list_customer.toko_id", "=", $toko_id]
            ])
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
                    $i++;
                }
            });

        if (isset($input->order) && !empty($input->order)) {
            $result->orderBy($column_order[$input->order["0"]["column"] - 1], $input->order["0"]["dir"]);
        } else if (isset($input->order)) {
            $result->orderBy(key($order), $order[key($order)]);
        }
        // dd($result);
        return $result;
    }

    public function get_customer_listed($input)
    {
        $query = $this->query_get_customer($input);
        if ($input->length != -1) {
            $limit = $query->offset($input->start)->limit($input->length);
            return $limit->get();
        }
    }

    public function get_customer_filter_count($input)
    {
        $query = $this->query_get_customer($input);
        return $query->count();
    }

    public static function add_customer($input)
    {
        $response = \create_response();

        DB::beginTransaction();
        try {
            $error = FALSE;
            $toko_id = session("toko_id");
            $query = DB::table('list_customer')->where([
                ["customer_name", "=", $input->customer_name],
                ["toko_id", "=", $toko_id],
                ["deleted_at", "=", NULL]
            ])->get();

            if ($query->count() == 0) {
                self::create([
                    "toko_id" => $toko_id,
                    "customer_name" => $input->customer_name,
                    "customer_phone" => $input->customer_phone,
                    "customer_note" => $input->customer_note,
                    "is_active" => $input->is_active
                ]);

                $response->id = $customer_id = get_return_id();

                $values = [];
                for ($i = 0; $i < \count($input->province_id); $i++) {
                    if (empty($input->province_id[$i]) || empty($input->district_id[$i]) || empty($input->subdistrict_id[$i]) || empty($input->subdistrict_id[$i])) {
                        $error = TRUE;
                        $response->message = "Input alamat tidak boleh ada yang kosong!";
                        break;
                    }
                    $array_province = explode(":", $input->province_id[$i]);
                    $array_district = explode(":", $input->district_id[$i]);
                    $array_subdistrict = explode(":", $input->subdistrict_id[$i]);

                    $address_type_id = $i == 0 ? 1 : 2;
                    $data = [
                        "customer_id" => $customer_id,
                        "address_type_id" => $address_type_id,
                        "province_id" => \decrypt_url($array_province[0]),
                        "province_name" => $array_province[1],
                        "district_id" => \decrypt_url($array_district[0]),
                        "district_name" => $array_district[1],
                        "subdistrict_id" => \decrypt_url($array_subdistrict[0]),
                        "subdistrict_name" => $array_subdistrict[1],
                        "full_address" => $input->full_address[$i],
                        "is_default" => 1
                    ];

                    array_push($values, $data);
                }

                DB::table("list_customer_address")->insert($values);

                if ($error === FALSE) {
                    $response->success = TRUE;
                    $response->message = "Success added new customer!";
                    DB::commit();
                } else {
                    DB::rollback();
                }
            } else {
                $response->message = "Customer already exists";
            }
        } catch (Exception $e) {
            $response->message = $e->getMessage();
            DB::rollback();
        }
        return $response;
    }

    public static function get_customer_detail($customer_id)
    {
        $response = \create_response();
        try {
            $query = DB::table("list_customer")
                ->leftJoin("list_access_control", "list_access_control.access_id", "=", "list_customer.access_id")
                ->where([
                    ["list_customer.customer_id", "=", $customer_id],
                    ["list_customer.deleted_at", "=", NULL]
                ]);
            // dd($query->dump());
            if ($query->count() == 1) {
                $response->success = TRUE;
                $response->data = $query->first();
            } else {
                $response->message = "Customer not found!";
            }
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }
        return $response;
    }

    public static function get_address_customer($customer_id, $type = 1)
    {
        $where = [
            ["customer_id", "=", $customer_id],
            ["address_type_id", "=", $type]
        ];
        $results = DB::table("list_customer_address")
            ->where($where)->get();
        return $results;
    }

    public static function add_customer_address($input)
    {
        $response = \create_response();
        try {
            $array_province = explode(":", $input->province_id);
            $array_district = explode(":", $input->district_id);
            $array_subdistrict = explode(":", $input->subdistrict_id);
            DB::table('list_customer_address')->insert([
                "customer_id" => \decrypt_url($input->customer_id),
                "address_type_id" => $input->address_type_id,
                "province_id" => \decrypt_url($array_province[0]),
                "province_name" => $array_province[1],
                "district_id" => \decrypt_url($array_district[0]),
                "district_name" => $array_district[1],
                "subdistrict_id" => \decrypt_url($array_subdistrict[0]),
                "subdistrict_name" => $array_subdistrict[1],
                "full_address" => $input->full_address
            ]);

            $response->success = TRUE;
            $response->message = "Success added new address shipment!";
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }
        return $response;
    }

    public static function change_default_address_customer($customer_id, $address_id, $type = 1)
    {
        $response = \create_response();
        try {
            // dd($customer_id);
            DB::table('list_customer_address')
                ->where([
                    ["customer_id", "=", $customer_id],
                    ["address_type_id", "=", $type]
                ])
                ->update(["is_default" => 0]);

            DB::table('list_customer_address')
                ->where([
                    ["customer_id", "=", $customer_id],
                    ["address_id", "=", $address_id]
                ])
                ->update(["is_default" => 1]);

            $response->success = TRUE;
            $response->message = "Success change default address!";
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }
        return $response;
    }

    public static function edit_customer($input)
    {
        $response = create_response();
        $toko_id = session("toko_id");
        try {
            $query = DB::table('list_customer')->where([
                ["customer_id", "!=", \decrypt_url($input->customer_id)],
                ["customer_name", "=", $input->customer_name],
                ["toko_id", "=", $toko_id],
                ["deleted_at", "=", NULL]
            ])->get();

            if ($query->count() == 0) {
                self::where("customer_id", \decrypt_url($input->customer_id))
                    ->update([
                        "customer_name" => $input->customer_name,
                        "customer_phone" => $input->customer_phone,
                        "customer_note" => $input->customer_note,
                        "is_active" => $input->is_active
                    ]);

                $response->success = TRUE;
                $response->message = "Success edited customer!";
            } else {
                $response->message = "Nama customer sudah ada!";
            }
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }
        return $response;
    }

    public static function delete_customer($customer_id)
    {
        $response = \create_response();
        try {
            self::find($customer_id)->delete();

            $response->success = TRUE;
            $response->message = "Success deleted customer!";
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }
        return $response;
    }
}
