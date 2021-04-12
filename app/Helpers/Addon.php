<?php 

use Illuminate\Support\Facades\DB;

function create_response() {
  $response = new stdClass();
  $response->success = FALSE;
  $response->data = [];
  $response->found = FALSE;
  $response->message = "Unknown Failure!";

  return $response;
}

function get_return_id() {
  $id = DB::select("SELECT LAST_INSERT_ID() AS `id`;");
  return $id[0]->id;
}


function encrypt_url($string)
{

  $output = false;
  /*
    * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
    */
  $security       = parse_ini_file("security.ini");
  $secret_key     = $security["encryption_key"];
  $secret_iv      = $security["iv"];
  $encrypt_method = $security["encryption_mechanism"];

  // hash
  $key    = hash("sha256", $secret_key);

  // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
  $iv     = substr(hash("sha256", $secret_iv), 0, 16);

  //do the encryption given text/string/number
  $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
  $output = base64_encode($result);
  return $output;
}

function decrypt_url($string)
{

  $output = false;
  /*
    * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
    */

  $security       = parse_ini_file("security.ini");
  $secret_key     = $security["encryption_key"];
  $secret_iv      = $security["iv"];
  $encrypt_method = $security["encryption_mechanism"];

  // hash
  $key    = hash("sha256", $secret_key);

  // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
  $iv = substr(hash("sha256", $secret_iv), 0, 16);

  //do the decryption given text/string/number

  $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
  return $output;
}