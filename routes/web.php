<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

/*
Ini komentar untuk Sanjaya
PR Yang belum selesai adalah update/perbaiki untuk table rel_user_module. Table update udh ada di dbdiagram
Nanti tinggal sesuaikan aja lalu buat CRUD nya khusus owner yang bisa
*/

// Authentication
Route::get('/login', 'LoginController@login')->name('login');
Route::get("/register", "Auth\RegisterController@view_register")->name("register");
Route::post("/validate_register", "Auth\RegisterController@process_validate_register")->name("validate_register");
Route::post('/process_login', 'LoginController@process_login')->name('process_login');
Route::get("/logout", "LoginController@logout")->name("logout");
Route::get("/403-page", function () {
    return view("403");
});

// Dashboard
Route::middleware(["auth_login"])->group(function () {
    Route::get("/dashboard", "Dashboard@index")->name('dashboard');

    // Administration
    // Routing ini udh dibuat middleware protect module, jangan dipakai dulu sampai selesai buat rel_module_access nya
    // Route::get("/administrator", "Users@view_user_management")->middleware("auth_module:2,0");
    // Route::post("/get-user", "Users@get_user_listed")->middleware("auth_module:2,1");
    // Route::post("/validation-user-add", "Users@validation_user_add")->middleware("auth_module:2,1");

    Route::get("/administrator", "UserController@view_user_management");
    Route::post("/get-user", "UserController@get_user_listed");
    Route::post("/validation-user-add", "UserController@validation_user_add");
    Route::get("/user/{user_id}", "UserController@view_user_detail");
    Route::put("/user/{user_id}", "UserController@process_user_edit");
    Route::get("/user/{user_id}/deadactive", "UserController@process_user_deadactive");
    Route::get("/user/{user_id}/active", "UserController@process_user_active");
    Route::delete("/user/{user_id}", "UserController@process_user_delete");

    // Supplier
    Route::get("/supplier", "SupplierController@view_supplier");
    Route::post("/json-supplier", "SupplierController@get_supplier");
    Route::post("/supplier-add", "SupplierController@process_supplier_add");
    Route::get("/supplier/{supplier_id}", "SupplierController@view_supplier_detail");
    Route::put("/supplier/{supplier_id}", "SupplierController@process_supplier_edit");
    Route::delete("/supplier/{supplier_id}", "SupplierController@process_supplier_delete");

    // Warehouse
    Route::get("/warehouse", "WarehouseController@view_warehouse_management");
    Route::post("/get-warehouse", "WarehouseController@ajax_get_warehouse_listed");
    Route::get("/add-warehouse", "WarehouseController@view_warehouse_add");
    Route::post("/warehouse", "WarehouseController@validate_warehouse_add");
    Route::get("/warehouse/{warehouse_id}", "WarehouseController@view_warehouse_detail");
    Route::get("/warehouse/{warehouse_id}/edit", "WarehouseController@view_warehouse_edit");
    Route::put("/warehouse", "WarehouseController@validate_warehouse_edit");

    // Customer
    Route::get("/customer", "CustomerController@view_customer_management");
    Route::post("/get-customer-json", "CustomerController@get_customer_listed");
    Route::get("/add-customer", "CustomerController@view_customer_add");

    Route::get("/get-district-json/{province_id}", "CustomerController@ajax_get_district");
    Route::get("/get-subdistrict-json/{province_id}", "CustomerController@ajax_get_subdistrict");
    Route::post("/add-customer-address", "CustomerController@validate_customer_address_add");
    Route::get("/change-default-address-shipment/{customer_id}/{address_id}", "CustomerController@process_change_default_address_shipment");
    Route::get("/change-default-address-bill/{customer_id}/{address_id}", "CustomerController@process_change_default_address_bill");

    Route::post("/add-customer", "CustomerController@validation_customer_add");
    Route::get("/customer/{customer_id}", "CustomerController@view_customer_detail");
    Route::put("/customer", "CustomerController@validate_customer_edit");
    Route::delete("/customer/{customer_id}", "CustomerController@process_customer_delete");

    // Category Product
    Route::get("/category-product", "ProductController@view_product_category");
    Route::post("/json-category-product", "ProductController@get_product_category");
    Route::post("/process-product-category-add", "ProductController@process_product_category_add");
    Route::delete("/process-product-category-delete/{id}", "ProductController@process_product_category_delete");
    Route::get("/get-product-category-detail/{id}", "ProductController@get_product_category_detail");
    Route::post("/process-product-category-edit", "ProductController@process_product_category_edit");
});


// Route::get("/login", function() {
//     return view("auth.login");
// })->name("login");