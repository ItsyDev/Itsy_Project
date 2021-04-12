@extends('layouts.app')
@section("title", $title)

@section('content')
  <div class="card">
    <div class="card-body">
      <form action="/warehouse" method="POST" onsubmit="loadRequest()">
        @csrf
        <div class="form-group">
          <label>Warehouse Name</label>
          <input type="text" class="form-control @error('warehouse_name') is-invalid @enderror" name="warehouse_name" value="{{ old('warehouse_name') }}">
          @error('warehouse_name')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Warehouse Phone</label>
          <input type="number" class="form-control @error('warehouse_phone') is-invalid @enderror" name="warehouse_phone" value="{{ old('warehouse_phone') }}">
          @error('warehouse_phone')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>PIC Name</label>
          <input type="text" class="form-control @error('pic_name') is-invalid @enderror" name="pic_name" value="{{ old('pic_name') }}">
          @error('pic_name')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Note</label>
          <textarea name="warehouse_note" class="form-control @error('warehouse_note') is-invalid @enderror" cols="30" rows="5">{{ old('warehouse_note') }}</textarea>
          @error('warehouse_note')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Province</label>
          <select name="province_id" class="form-control @error('province_id') is-invalid @enderror" id="listProvince">
            @foreach ($list_province as $item)
              <option value="{{ encrypt_url($item["province_id"]) . ":" . $item["province"] }}">{{ $item["province"] }}</option>
            @endforeach
          </select>
          @error('province_id')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>District</label>
          <select name="district_id" class="form-control @error('district_id') is-invalid @enderror" id="listDistrict"></select>
          @error('district_id')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Sub District</label>
          <select name="subdistrict_id" class="form-control @error('subdistrict_id') is-invalid @enderror" id="listSubDistrict"></select>
          @error('subdistrict_id')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Full Address</label>
          <textarea name="full_address" class="form-control @error('full_address') is-invalid @enderror" cols="30" rows="5">{{ old('full_address') }}</textarea>
          @error('full_address')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-plus mr-1"></i>Add</button>
        <button type="button" class="btn btn-danger btn-sm" onclick="goBack()"><i class="fas fa-times mr-1"></i>Back</button>
      </form>
    </div>
  </div>
@endsection

@push('script')
  <script>
    let selectProvince = document.getElementById("listProvince");
    let selectDistrict = document.getElementById("listDistrict");

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