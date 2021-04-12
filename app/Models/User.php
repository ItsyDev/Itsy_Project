<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Toko;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;
    protected $table = "list_user";
    protected $primaryKey = "user_id";
    protected $guarded = ["user_id"];
    protected $fillable = ["user_fullname", "user_name", "user_email", "user_password", "user_phone", "user_address", "user_status_id", "access_id", "last_active", "user_photo"];
    protected $dates = ['deleted_at'];
    // private $toko_id = session("toko_id");

    public static function add_user($input)
    {
        $response = create_response();
        DB::beginTransaction();

        try {
            self::create([
                "user_fullname" => $input->user_fullname,
                "user_name" => $input->user_name,
                "user_email" => $input->user_email,
                "user_password" => Hash::make($input->user_password),
                "user_phone" => $input->user_phone,
                "user_address" => $input->user_address,
                "user_status_id" => 1,
                "access_id" => 2
            ]);

            $user_id = get_return_id();

            $add_toko = Toko::add_toko($input);

            Toko::related_user_to_store($user_id, $add_toko->toko_id, TRUE);

            DB::commit();
            $response->success = TRUE;
            $response->message = "Success register! Please login now!";
        } catch (Exception $e) {
            DB::rollBack();
            $response->message = $e->getMessage();
        }

        return $response;
    }

    public static function check_login($input)
    {
        $response = create_response();
        $user = DB::table("list_user")
            ->join("list_access_control", "list_user.access_id", "=", "list_access_control.access_id")
            ->join("list_user_status", "list_user.user_status_id", "=", "list_user_status.user_status_id")
            ->join("rel_user_toko", "list_user.user_id", "=", "rel_user_toko.user_id")
            ->join("list_toko", "rel_user_toko.toko_id", "=", "list_toko.toko_id")
            ->where("list_user.user_email", $input->user_email)->get()->first();
        // dd($query);
        if ($user) {
            if (Hash::check($input->user_password, $user->user_password)) {
                if ($user->user_status_id == 1) {
                    $session = [
                        "user_id" => $user->user_id,
                        "user_fullname" => $user->user_fullname,
                        "user_name" => $user->user_name,
                        "toko_id" => $user->toko_id,
                        "is_owner" => $user->is_owner == 1 ? TRUE : FALSE,
                        "login" => TRUE,
                        "access_id" => $user->access_id,
                        "admin_level" => $user->admin_level
                    ];

                    session($session);
                    $response->success = TRUE;
                    $response->url = "dashboard";
                } else {
                    $response->message = "Maaf, akun anda $user->user_status";
                }
            } else {
                $response->message = "Password salah!";
            }
        } else {
            $response->message = "Akun belum didaftarkan!";
        }

        return $response;
    }

    public static function get_access_list($level_up, $level_down = FALSE)
    {
        $admin_level = [];
        array_push($admin_level, ["list_access_control.admin_level", "<=", $level_up]);
        if ($level_down !== FALSE) {
            array_push($admin_level, ["list_access_control.admin_level", ">=", $level_down]);
        }
        return DB::table("list_access_control")
            ->join("list_division", "list_access_control.division_id", "=", "list_division.division_id")
            ->where($admin_level)
            ->get();
    }

    private function query_get_user($input)
    {
        $toko_id = session("toko_id");
        $column_order = ["list_user.user_fullname", "list_user.user_name", "list_user.user_email", "list_user.user_phone", "list_user.user_address", "list_access_control.level_name"];
        $column_search = ["list_user.user_fullname", "list_user.user_name", "list_user.user_email", "list_user.user_phone", "list_user.user_address", "list_access_control.level_name"];
        $order = ["list_user.user_id" => "DESC"];

        // DB::enableQueryLog();
        $result = DB::table("list_user")
            ->join("list_access_control", "list_access_control.access_id", "=", "list_user.access_id")
            ->join("list_user_status", "list_user_status.user_status_id", "=", "list_user.user_status_id")
            ->join("rel_user_toko", "rel_user_toko.user_id", "=", "list_user.user_id")
            ->where([
                ["list_user.deleted_at", "=", NULL],
                ["rel_user_toko.toko_id", "=", $toko_id]
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

    public function get_user_listed($input)
    {
        $query = $this->query_get_user($input);
        if ($input->length != -1) {
            $limit = $query->offset($input->start)->limit($input->length);
            return $limit->get();
        }
    }

    public function get_user_filter_count($input)
    {
        $query = $this->query_get_user($input);
        return $query->count();
    }

    public function get_user_count()
    {
        return DB::table("list_user")->count();
    }

    public static function add_user_from_owner($input)
    {
        $response = create_response();
        DB::beginTransaction();
        $toko_id = \session("toko_id");

        try {
            $query = DB::table('list_user')
            ->join("rel_user_toko", "rel_user_toko.user_id", "=", "list_user.user_id")
            ->where([
                ["rel_user_toko.toko_id", "=", $toko_id],
                ["list_user.deleted_at", "=", NULL]
            ])->where(function ($query) use ($input) {
                $query->where("list_user.user_name", $input->user_name);
                $query->orWhere("list_user.user_email", $input->user_email);
            })->get();

            if ($query->count() == 0) {
                self::create([
                    "user_fullname" => $input->user_fullname,
                    "user_name" => $input->user_name,
                    "user_email" => $input->user_email,
                    "user_password" => Hash::make($input->user_password),
                    "user_phone" => $input->user_phone,
                    "user_address" => $input->user_address,
                    "user_status_id" => 1,
                    "access_id" => $input->access_id
                ]);

                $response->id = $user_id = get_return_id();

                Toko::related_user_to_store($user_id, $toko_id, TRUE);

                DB::commit();
                $response->success = TRUE;
                $response->message = "Success register new user!";
            } else {
                $response->message = "Username / email already exists!";
            }
        } catch (Exception $e) {
            DB::rollBack();
            $response->message = $e->getMessage();
        }

        return $response;
    }

    public static function get_user_detail($user_id)
    {
        $response = create_response();
        $query = DB::table("list_user")
            ->join("list_access_control", "list_access_control.access_id", "=", "list_user.access_id")
            ->join("list_user_status", "list_user_status.user_status_id", "=", "list_user.user_status_id")
            ->where("list_user.user_id", $user_id);

        if ($query->count() == 1) {
            $response->success = TRUE;
            $response->data = $query->first();
        } else {
            $response->message = "User not found!";
        }

        return $response;
    }

    public static function edit_user($input)
    {
        $response = \create_response();
        $toko_id = \session("toko_id");
        try {
            $query = DB::table('list_user')
            ->join("rel_user_toko", "rel_user_toko.user_id", "=", "list_user.user_id")
            ->where([
                ["rel_user_toko.toko_id", "=", $toko_id],
                ["list_user.deleted_at", "=", NULL],
                ["list_user.user_id", "!=", $input->user_id]
            ])->where(function ($query) use ($input) {
                $query->where("list_user.user_name", $input->user_name);
                $query->orWhere("list_user.user_email", $input->user_email);
            })->get();

            if ($query->count() == 0) {
                $data = [
                    "user_fullname" => $input->user_fullname,
                    "user_name" => $input->user_name,
                    "user_email" => $input->user_email,
                    "user_phone" => $input->user_phone,
                    "user_address" => $input->user_address
                ];
                if (isset($input->user_photo)) {
                    $user_detail = self::where("user_id", $input->user_id)->first();
    
                    $old_photo = $user_detail->user_photo;
                    $array = explode("/", $old_photo);
                    unlink("./images/user_photo/" . end($array));
    
                    do {
                        $random = Str::random(20);
                        $new_name = "$random." . $input->user_photo->getClientOriginalExtension();
    
                        $file_exists = \file_exists("/images/user_photo/$new_name");
                    } while ($file_exists);
                    $input->user_photo->move("images/user_photo/", $new_name);
                    $data["user_photo"] = \url("/images/user_photo/$new_name");
                }
                if (!empty($input->user_password)) {
                    $data["user_password"] = Hash::make($input->user_password);
                }
                if (isset($input->access_id)) {
                    $data["access_id"] = $input->access_id;
                }
                // dd($data);
                self::where("user_id", $input->user_id)
                    ->update($data);
    
                $response->success = TRUE;
                $response->message = "Success edited user!";
            } else {
                $response->message = "Username / email already exists!";
            }
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }

        return $response;
    }

    public static function change_user_status($user_id, $status_id)
    {
        $response = \create_response();
        try {
            $data = [
                "user_status_id" => $status_id
            ];

            self::where("user_id", $user_id)
                ->update($data);

            $response->success = TRUE;
            $response->message = "Success change status user!";
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }

        return $response;
    }

    public static function delete_user($user_id)
    {
        $response = \create_response();
        try {
            self::find($user_id)->delete();

            $response->success = TRUE;
            $response->message = "Success deleted user!";
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }
        return $response;
    }
}
