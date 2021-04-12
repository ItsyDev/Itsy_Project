@extends('layouts.app')
@section("title", $title)

@section('content')
  <div class="card">
    <div class="card-body">
      <form action="/warehouse" method="POST" onsubmit="loadRequest()">
        @method("PUT")
        @csrf
        <div class="form-group">
          <label>Warehouse Name</label>
          <input type="hidden" name="warehouse_id" value="{{ $warehouse_id }}">
          <input type="text" class="form-control @error('warehouse_name') is-invalid @enderror" name="warehouse_name" value="{{ $warehouse->warehouse_name }}">
          @error('warehouse_name')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Warehouse Phone</label>
          <input type="number" class="form-control @error('warehouse_phone') is-invalid @enderror" name="warehouse_phone" value="{{ $warehouse->warehouse_phone }}">
          @error('warehouse_phone')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>PIC Name</label>
          <input type="text" class="form-control @error('pic_name') is-invalid @enderror" name="pic_name" value="{{ $warehouse->pic_name }}">
          @error('pic_name')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Note</label>
          <textarea name="warehouse_note" class="form-control @error('warehouse_note') is-invalid @enderror" cols="30" rows="5">{{ $warehouse->warehouse_note }}</textarea>
          @error('warehouse_note')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="is_active" class="form-control">
            <option @if($warehouse->is_active == 1) selected @endif value="1">Active</option>
            <option @if($warehouse->is_active == 0) selected @endif value="0">Non-Active</option>
          </select>
        </div>
        <div class="form-group">
          <label>Province</label>
          <select name="province_id" class="form-control @error('province_id') is-invalid @enderror" id="listProvince">
            @foreach ($area["list_province"] as $item)
              <option @if($item["province_id"] == $warehouse->province_id) selected @endif value="{{ encrypt_url($item["province_id"]) . ":" . $item["province"] }}">{{ $item["province"] }}</option>
            @endforeach
          </select>
          @error('province_id')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>District</label>
          <select name="district_id" class="form-control @error('district_id') is-invalid @enderror" id="listDistrict">
            @foreach ($area["list_district"]->option as $item)
              <option @if(decrypt_url($item["city_id"]) == $warehouse->district_id) selected @endif value="{{ $item['city_id'] . ':' . $item['city_name'] }}">{{ $item["city_name"] }}</option>
            @endforeach
          </select>
          @error('district_id')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Sub District</label>
          <select name="subdistrict_id" class="form-control @error('subdistrict_id') is-invalid @enderror" id="listSubDistrict">
            @foreach ($area["list_subdistrict"]->option as $item)
              <option @if(decrypt_url($item["subdistrict_id"]) == $warehouse->subdistrict_id) selected @endif value="{{ $item['subdistrict_id'] . ':' . $item['subdistrict_name'] }}">{{ $item["subdistrict_name"] }}</option>
            @endforeach
          </select>
          @error('subdistrict_id')  
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Full Address</label>
          <textarea name="full_address" class="form-control @error('full_address') is-invalid @enderror" cols="30" rows="5">{{ $warehouse->full_address }}</textarea>
          @error('full_address')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check mr-1"></i>Save</button>
        <button type="button" class="btn btn-danger btn-sm" onclick="goBack()"><i class="fas fa-times mr-1"></i>Back</button>
      </form>
    </div>
  </div>
@endsection

@push('script')
  <script>
    let selectProvince = document.getElementById("listProvince");
    let selectDistrict = document.getElementById("listDistrict");
    let selectSubDistrict = document.getElementById("listSubDistrict");

    selectProvince.addEventListener("change", function() {
      let value = this.value;
      $.ajax({
        url: `${baseUrl}get-district-json/${value}`,
        type: "GET",
        cache: false,
        dataType: "JSON",
        success: function(results) {
          if (results.success) {
            let option = "";
            results.data.forEach(item => {
              option += `<option value="${item.city_id}:${item.city_name}">${item.city_name}</option>`;
            })
            document.getElementById("listDistrict").innerHTML = option;
            listDistrict = results.data;
            selectSubDistrict.innerHTML = "";
          }
        }
      });
    });

    selectDistrict.addEventListener("change", function() {
      let value = this.value;
      $.ajax({
        url: `${baseUrl}get-subdistrict-json/${value}`,
        type: "GET",
        cache: false,
        dataType: "JSON",
        success: function(results) {
          if (results.success) {
            let option = "";
            results.data.forEach(item => {
              option += `<option value="${item.subdistrict_id}:${item.subdistrict_name}">${item.subdistrict_name}</option>`;
            });
            document.getElementById("listSubDistrict").innerHTML = option;
            listSubDistrict = results.data;
          }
        }
      });
    });
  </script>
@endpush