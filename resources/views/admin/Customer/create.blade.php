@extends("layouts.app")
@section("title", $title)

@section("content")
  <div class="card">
    <div class="card-body">
      <h4>Informasi Customer</h4>
      <form action="/add-customer" method="POST" onsubmit="loadRequest()">
        @csrf
        <div class="form-group">
          <label>Customer Name</label>
          <input type="text" class="form-control @error('customer_name') is-invalid @enderror" name="customer_name">
          @error('customer_name')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Customer Phone</label>
          <input type="number" class="form-control @error('customer_phone') is-invalid @enderror" name="customer_phone">
          @error('customer_phone')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Note</label>
          <textarea name="customer_note" class="form-control @error('customer_note') is-invalid @enderror" cols="30" rows="5"></textarea>
          @error('customer_note')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="is_active" class="form-control @error('is_active') is-invalid @enderror">
            <option value="1">Active</option>
            <option value="0">Non-Active</option>
          </select>
          @error('is_active')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <h4>Alamat Pengiriman</h4>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Province</label>
              <select name="province_id[]" class="form-control" id="listProvince">
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
              <select name="district_id[]" class="form-control" id="listDistrict"></select>
              @error('district_id')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
            <div class="form-group">
              <label>Sub District</label>
              <select name="subdistrict_id[]" class="form-control" id="listSubDistrict"></select>
              @error('subdistrict_id')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Full Address</label>
              <textarea name="full_address[]" class="form-control" cols="30" rows="5" id="fullAddress"></textarea>
              @error('full_address')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
          </div>
        </div>

        <h4>Alamat Penagihan</h4>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
          <label class="form-check-label" for="defaultCheck1">
            Samakan dengan alamat pengiriman
          </label>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Province</label>
              <select name="province_id[]" class="form-control input-bill" id="listProvinceBill">
                @foreach ($list_province as $item)
                  <option value="{{ encrypt_url($item["province_id"]) . ":" . $item["province"] }}">{{ $item["province"] }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>District</label>
              <select name="district_id[]" class="form-control input-bill" id="listDistrictBill"></select>
            </div>
            <div class="form-group">
              <label>Sub District</label>
              <select name="subdistrict_id[]" class="form-control input-bill" id="listSubDistrictBill"></select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Full Address</label>
              <textarea name="full_address[]" class="form-control input-bill" cols="30" rows="5" id="fullAddressBill"></textarea>
            </div>
          </div>
        </div>
        <input type="submit" class="btn btn-success" value="Add">
        <button type="button" class="btn btn-danger" onclick="confirmBack()">Back</button>
      </form>
    </div>
  </div>

  <script>
    const confirmBack = () => {
      let back = confirm("Are you sure?");
      if (back) {
        goBack();
      }
    }

    let listProvince = {!! json_encode($list_province) !!};
    let listDistrict = "";
    let listSubDistrict = "";

    let selectProvince = document.getElementById("listProvince");
    let selectDistrict = document.getElementById("listDistrict");
    let selectSubDistrict = document.getElementById("listSubDistrict");
    let inputFullAddress = document.getElementById("fullAddress");

    let provinceBill = document.getElementById("listProvinceBill");
    let districtBill = document.getElementById("listDistrictBill");
    let subdistrictBill = document.getElementById("listSubDistrictBill");
    let fullAddressBill = document.getElementById("fullAddressBill");

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

    provinceBill.addEventListener("change", function() {
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
            districtBill.innerHTML = option;
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

    districtBill.addEventListener("change", function() {
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
            subdistrictBill.innerHTML = option;
          }
        }
      });
    });

    document.getElementById("defaultCheck1").addEventListener("click", function() {
      if (this.checked) {
        districtBill.innerHTML = "";
        subdistrictBill.innerHTML = "";
        let option = "";
        if (listDistrict.length > 0) {
          // console.log("Draw Distrct")
          listDistrict.forEach(item => {
            option += `<option value="${item.city_id}:${item.city_name}">${item.city_name}</option>`;;
          });
          districtBill.innerHTML = option;
        }
        
        option = "";
        if (listDistrict.length > 0) {
          // console.log("Draw Sub District");
          listSubDistrict.forEach(item => {
            option += `<option value="${item.subdistrict_id}:${item.subdistrict_name}">${item.subdistrict_name}</option>`;
          });
          subdistrictBill.innerHTML = option;
        }

        // console.log(selectDistrict.value);
        // console.log(selectSubDistrict.value);
        provinceBill.value = selectProvince.value;
        districtBill.value = selectDistrict.value;
        subdistrictBill.value = selectSubDistrict.value;
        fullAddressBill.value = inputFullAddress.value;
      }
    });
  </script>
@endsection