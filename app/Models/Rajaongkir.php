<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use function GuzzleHttp\json_decode;

class Rajaongkir extends Model
{
    public static function get_province()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: 2122c86a2a12adaa79cec2f6f08aedcd"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        // dd($response);
        curl_close($curl);

        if ($err) {
            return FALSE;
        } else {
            $list = json_decode($response, TRUE);
            // dd($list["rajaongkir"]["results"]);
            return $list["rajaongkir"]["results"];
        }
    }

    public static function get_district($province_id)
    {
        $response = \create_response();
        $curl = curl_init();
        $array = explode(":", $province_id);
        $id = \decrypt_url($array[0]);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/city?province=$id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: 2122c86a2a12adaa79cec2f6f08aedcd"
            ),
        ));

        $results = curl_exec($curl);
        $err = curl_error($curl);
        // dd($id);
        curl_close($curl);

        if ($err) {
            $response->messaget = $err;
        } else {
            $response->success = TRUE;
            $list = json_decode($results, TRUE);
            $list_district = $list["rajaongkir"]["results"];
            $response->option = [];
            foreach ($list_district as $item) {
                $data = [
                    "city_id" => \encrypt_url($item["city_id"]),
                    "city_name" => $item["type"] . " " . $item["city_name"]
                ];
                \array_push($response->option, $data);
            }
        }

        return $response;
    }

    public static function get_subdistrict($district_id)
    {
        $response = \create_response();
        $curl = curl_init();
        $array = explode(":", $district_id);
        $id = \decrypt_url($array[0]);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=$id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: 2122c86a2a12adaa79cec2f6f08aedcd"
            ),
        ));

        $results = curl_exec($curl);
        $err = curl_error($curl);
        // dd($results);
        curl_close($curl);

        if ($err) {
            $response->message = $err;
        } else {
            $response->success = TRUE;
            $list = json_decode($results, TRUE);
            // dd($list);
            $list_district = $list["rajaongkir"]["results"];
            $response->option = [];
            foreach ($list_district as $item) {
                $data = [
                    "subdistrict_id" => encrypt_url($item["subdistrict_id"]),
                    "subdistrict_name" => $item["subdistrict_name"]
                ];

                \array_push($response->option, $data);
            }
        }

        return $response;
    }
}
