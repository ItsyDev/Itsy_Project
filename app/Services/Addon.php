<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use Browser;

class Addon
{

  public function draw_breadcrumb($title, $link, $delete = FALSE)
  {
    $this->push_breadcrumb($title, $link, $delete);

    $data = session("breadcrumb");
    $last = count($data);
    $html = "<nav aria-label='breadcrumb'>";
    $html .= "<ol class='breadcrumb'>";
    $i = 1;
    foreach ($data as $item) {
      if ($i != $last) {
        $html .= "<ul class='breadcrumb-item'><a href='#' onclick=\"navigateTo('" . $item["link"] . "', true)\">" . $item["title"] . "</a></ul>";
      } else {
        $html .= "<ul class='breadcrumb-item active'>" . $item["title"] . "</ul>";
      }
      $i++;
    }
    $html .= "</ol>";
    $html .= "</nav>";
    return $html;
  }

  public function push_breadcrumb($title, $link, $delete = FALSE)
  {
    $breadcrumb = session("breadcrumb");

    if ($delete) {
      $breadcrumb = [[
        "link" => $link,
        "title" => $title
      ]];

      session(["breadcrumb" => $breadcrumb]);
    } else {
      if (!empty($breadcrumb)) {
        $index = array_search($title, array_column($breadcrumb, "title"));
        if ($index !== FALSE) {
          $breadcrumb = array_splice($breadcrumb, 0, $index);
        }
      }

      $data = [
        "link" => $link,
        "title" => $title
      ];

      array_push($breadcrumb, $data);
      session(["breadcrumb" => $breadcrumb]);
    }
  }

  public function get_all_toko_category()
  {
    return DB::table("list_category_toko")->get();
  }

  public function get_user_login()
  {
    $user_id = session("user_id");

    return DB::table("list_user")->select("user_fullname", "user_name", "user_photo")->where("user_id", $user_id)->get()->first();
  }

  public function insert_log($module_id, $description = "NULL", $data = "NULL")
  {
    DB::table("list_log")->insert([
      "user_id" => \session("user_id"),
      "module_id" => $module_id,
      "description" => $description,
      "data_ref" => $data,
      "action_date" => date("Y-m-d H:i:s"),
      "operating_system" => Browser::platformName(),
      "ip_address" => $_SERVER["REMOTE_ADDR"] . ":" . $_SERVER["REMOTE_PORT"],
      "browser" => Browser::browserName()
    ]);
  }

  public function get_menu_sidebar()
  {
    $menu = [];
    $menu["menu"] = $this->get_menu();
    $menu["sub_menu"] = DB::table("list_sub_menu")->get();

    return (object) $menu;
  }

  private function get_menu()
  {
    $user_id = session("user_id");
    $query = DB::table("list_menu")
    ->join("list_module", "list_module.menu_id", "=", "list_menu.menu_id")
    ->join("rel_user_module", "rel_user_module.module_id", "=", "list_module.module_id")
    ->where([
      ["rel_user_module.user_id", "=", $user_id],
      ["rel_user_module.is_allow", "=", 1]
    ])->get();

    return $query;
  }
}