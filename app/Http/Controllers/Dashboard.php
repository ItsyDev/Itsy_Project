<?php

namespace App\Http\Controllers;

use App\Services\Addon;

class Dashboard extends Controller
{
    protected $addon;

    public function __construct(Addon $addon)
    {
        $this->addon = $addon;
    }

    public function index()
    {
        // dd($this->addon->get_user_login());
        $data = [
            "title" => "Dashbaord",
            "breadcrumb" => $this->addon->draw_breadcrumb("Dashboard", "/dashboard", TRUE),
            "user_login" => $this->addon->get_user_login()
        ];
        return view("admin.dashboard", $data);
    }
}
