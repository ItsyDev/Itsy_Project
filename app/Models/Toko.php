<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Services\Addon;

class Toko extends Model
{
    protected $table = "list_toko";
    protected $primaryKey = "toko_id";
    protected $guarded = ["toko_id"];
    protected $fillable = ["toko_name", "toko_address", "province_id", "district_id", "is_active", "category_toko_id"];
    // private $addon = new Addon();

    public static function add_toko($input)
    {
        $response = \create_response();
        $query = self::create([
            "toko_name" => $input->toko_name,
            "toko_address" => $input->toko_address,
            "province_id" => $input->province_id,
            "district_id" => $input->district_id,
            "is_active" => 1,
            "category_toko_id" => $input->category_toko_id,
        ]);

        if ($query) {
            $response->success = TRUE;
            $response->toko_id = get_return_id();
            // dd(get_return_id());
        } else {
            $response->message = "Query add toko failed!";
        }

        return $response;
    }

    public static function related_user_to_store($user_id, $store_id, $is_owner = FALSE)
    {
        $query = DB::table("rel_user_toko")->insert([
            "user_id" => $user_id,
            "toko_id" => $store_id,
            "is_owner" => $is_owner == FALSE ? 0 : 1
        ]);

        if ($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
