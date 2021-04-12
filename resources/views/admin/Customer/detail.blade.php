@extends("layouts.app")
@section("title", $title)

@section("content")
<button class="btn btn-success btn-sm mb-3" onclick="showModal('modalAddress')">Atur Alamat</button>
  <div class="card">
    <div class="card-body">
      <button class="btn btn-primary btn-sm mb-2 d-block ml-auto" onclick="showModal('editModal')"><i class="fas fa-edit"></i></button>
      <div class="row">
        <div class="col-md-6">
          <table class="table">
            <tr>
              <td>Customer Name</td>
              <td>: {{ $customer->customer_name }}</td>
            </tr>
            <tr>
              <td>Customer Phone</td>
              <td>: {{ $customer->customer_phone }}</td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table">
            <tr>
              <td>Customer Note</td>
              <td>: {{ $customer->customer_note }}</td>
            </tr>
            <tr>
              <td>Type</td>
              <td>: {{ !empty($customer->level_name) ? $customer->level_name : "Customer" }}</td>
            </tr>
            <tr>
              <td>Status</td>
              <td>: {{ $customer->is_active == 1 ? "Active" : "Non-Active" }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade text-body" id="modalAddress">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Address List</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#home">Shipment Address</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#menu1">Bill Address</a>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div class="tab-pane container active" id="home">
              <button class="btn btn-success btn-sm mt-2" data-toggle="collapse" data-target="#formAddressShipment">Add New</button>
              <div id="formAddressShipment" class="collapse mt-2">
                <div class="card">
                  <div class="card-body">
                    <form action="/add-customer-address" method="POST" onsubmit="loadRequest()">
                      @csrf
                      <input type="hidden" name="address_type_id" value="1">
                      <input type="hidden" name="customer_id" value="{{ encrypt_url($customer->customer_id) }}">
                      <div class="form-group">
                        <label>Province</label>
                        <select name="province_id" class="form-control" id="listProvince">
                          @foreach ($list_province as $item)
                            <option value="{{ encrypt_url($item["province_id"]) . ":" . $item["province"] }}">{{ $item["province"] }}</option>
                          @endforeach
                        </select>
                        @error('province_id')
                          <small class="text-danger text-bold">{{ $message }}</small>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label>District</label>
                        <select name="district_id" class="form-control" id="listDistrict"></select>
                        @error('district_id')
                          <small class="text-danger text-bold">{{ $message }}</small>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label>Sub District</label>
                        <select name="subdistrict_id" class="form-control" id="listSubDistrict"></select>
                        @error('subdistrict_id')
                          <small class="text-danger text-bold">{{ $message }}</small>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label>Full Address</label>
                        <textarea name="full_address" class="form-control" cols="30" rows="5"></textarea>
                        @error('full_address')
                          <small class="text-danger text-bold">{{ $message }}</small>
                        @enderror
                      </div>
                      <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-plus mr-2"></i>Add New</button>
                    </form>
                  </div>
                </div>
              </div>
              <div class="row mt-1">
                @foreach ($address_shipment as $item)
                <div class="col-md-6">
                  <div class="card card-shipment @if($item->is_default == 1) border border-primary  @endif" id="cardShipment{{ $item->address_id }}">
                    <div class="card-body card-white">
                      <div class="card-title">{{ $item->province_name }}</div>
                      {{ "$item->district_name - $item->subdistrict_name" }} <br>
                      {{ $item->full_address }}
                      <div class="form-check text-right pr-2">
                        <input class="form-check-input check-shipment" type="checkbox" value="" id="{{ "check$item->address_id" }}" @if ($item->is_default == 1) checked @endif onclick="changeDefaultShipment(this, '{{ encrypt_url($item->address_id) }}', 'cardShipment{{ $item->address_id }}')">
                        <label class="form-check-label" for="{{ "check$item->address_id" }}">
                          Jadikan alamat default
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            <div class="tab-pane container fade" id="menu1">
              <button class="btn btn-success btn-sm mt-2" data-toggle="collapse" data-target="#formAddressBill">Add New</button>
              <div id="formAddressBill" class="collapse mt-2">
                <div class="card">
                  <div class="card-body">
                    <form action="/add-customer-address" method="POST" onsubmit="loadRequest()">
                      @csrf
                      <input type="hidden" name="address_type_id" value="2">
                      <input type="hidden" name="customer_id" value="{{ encrypt_url($customer->customer_id) }}">
                      <div class="form-group">
                        <label>Province</label>
                        <select name="province_id" class="form-control" id="listProvinceBill">
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
                        <select name="district_id" class="form-control" id="listDistrictBill"></select>
                        @error('district_id')
                          <small class="text-danger">{{ $message }}</small>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label>Sub District</label>
                        <select name="subdistrict_id" class="form-control" id="listSubDistrictBill"></select>
                        @error('subdistrict_id')
                          <small class="text-danger">{{ $message }}</small>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label>Full Address</label>
                        <textarea name="full_address" class="form-control" cols="30" rows="5"></textarea>
                      </div>
                      <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-plus mr-2"></i>Add New</button>
                    </form>
                  </div>
                </div>
              </div>
              <div class="row mt-1">
                @foreach ($address_bill as $item)
                <div class="col-md-6">
                  <div class="card card-bill @if($item->is_default == 1) border border-primary  @endif" id="cardBill{{ $item->address_id }}">
                    <div class="card-body card-white">
                      <div class="card-title">{{ $item->province_name }}</div>
                      {{ "$item->district_name - $item->subdistrict_name" }} <br>
                      {{ $item->full_address }}
                      <div class="form-check text-right pr-2">
                        <input class="form-check-input check-bill" type="checkbox" value="" id="{{ "checkBill$item->address_id" }}" @if ($item->is_default == 1) checked @endif onclick="changeDefaultBill(this, '{{ encrypt_url($item->address_id) }}', 'cardBill{{ $item->address_id }}')">
                        <label class="form-check-label" for="{{ "checkBill$item->address_id" }}">
                          Jadikan alamat default
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade text-body" id="editModal">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Customer</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form action="/customer" method="POST" onsubmit="loadRequest()">
            @method("PUT")
            @csrf
            <div class="form-group">
              <label>Customer Name</label>
              <input type="text" class="form-control @error('customer_name') is-invalid @enderror" name="customer_name" value="{{ $customer->customer_name }}">
              <input type="hidden" name="customer_id" value="{{ encrypt_url($customer->customer_id) }}">
              @error('customer_name')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
            <div class="form-group">
              <label>Customer Phone</label>
              <input type="number" class="form-control @error('customer_phone') is-invalid @enderror" name="customer_phone" value="{{ $customer->customer_phone }}">
              @error('customer_phone')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
            <div class="form-group">
              <label>Note</label>
              <textarea name="customer_note" class="form-control @error('customer_note') is-invalid @enderror" cols="30" rows="5">{{ $customer->customer_note }}</textarea>
              @error('customer_note')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
            <div class="form-group">
              <label>Status</label>
              <select name="is_active" class="form-control @error('is_active') is-invalid @enderror">
                <option @if($customer->is_active == 1) selected @endif value="1">Active</option>
                <option @if($customer->is_active == 0) selected @endif value="0">Non-Active</option>
              </select>
              @error('is_active')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
            <input type="submit" class="btn btn-success btn-sm" value="Save">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    let listDistrict = "";
    let listSubDistrict = "";
    const customerId = "{{ encrypt_url($customer->customer_id) }}";

    function changeDefaultShipment(inputCheck, addressID, cardID) {
      let listCheck = document.getElementsByClassName("check-shipment");
      let listCard = document.getElementsByClassName("card-shipment");
      let card = document.getElementById(cardID);
      // console.log(inputCheck.checked);
      if (inputCheck.checked) {
        // console.log("Masuk Checked");
        $.ajax({
          url: `${baseUrl}change-default-address-shipment/${customerId}/${addressID}`,
          type: "GET",
          cache: false,
          dataType: "JSON",
          success: function(results) {
            if (results.success) {
              for (let i = 0; i < listCheck.length; i++) {
                listCheck[i].checked = false;
              }
              for (let i = 0; i < listCard.length; i++) {
                listCard[i].classList.remove("border");
                listCard[i].classList.remove("border-primary");
              }
              card.classList.add("border");
              card.classList.add("border-primary");
              inputCheck.checked = true;
              sweet("success", "Success!", results.message);
            } else {
              sweet("error", "Failed!", results.message);
            }
          }
        })
      } else {
        if (listCheck.length > 1) {
          let error = true;
          for (let i = 0; i < listCheck.length; i++) {
            if (listCheck[i].checked) {
              error = false;
            }
          }

          if (error) {
            inputCheck.checked = true;
            sweet("error", "Failed!", "Wajib memiliki satu alamat default!");
          }
        } else {
          inputCheck.checked = true;
          sweet("error", "Failed!", "Wajib memiliki satu alamat default!");
        }
      }
    }

    function changeDefaultBill(inputCheck, addressID, cardID) {
      let listCheck = document.getElementsByClassName("check-bill");
      let listCard = document.getElementsByClassName("card-bill");
      let card = document.getElementById(cardID);
      // console.log(inputCheck.checked);
      if (inputCheck.checked) {
        // console.log("Masuk Checked");
        $.ajax({
          url: `${baseUrl}change-default-address-bill/${customerId}/${addressID}`,
          type: "GET",
          cache: false,
          dataType: "JSON",
          success: function(results) {
            if (results.success) {
              for (let i = 0; i < listCheck.length; i++) {
                listCheck[i].checked = false;
              }
              for (let i = 0; i < listCard.length; i++) {
                listCard[i].classList.remove("border");
                listCard[i].classList.remove("border-primary");
              }
              card.classList.add("border");
              card.classList.add("border-primary");
              inputCheck.checked = true;
              sweet("success", "Success!", results.message);
            } else {
              sweet("error", "Failed!", results.message);
            }
          }
        })
      } else {
        if (listCheck.length > 1) {
          let error = true;
          for (let i = 0; i < listCheck.length; i++) {
            if (listCheck[i].checked) {
              error = false;
            }
          }

          if (error) {
            inputCheck.checked = true;
            sweet("error", "Failed!", "Wajib memiliki satu alamat default!");
          }
        } else {
          inputCheck.checked = true;
          sweet("error", "Failed!", "Wajib memiliki satu alamat default!");
        }
      }
    }

    document.getElementById("listProvince").addEventListener("change", function() {
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
    document.getElementById("listProvinceBill").addEventListener("change", function() {
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
            document.getElementById("listDistrictBill").innerHTML = option;
            listDistrict = results.data;
          }
        }
      });
    });

    document.getElementById("listDistrict").addEventListener("change", function() {
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
    document.getElementById("listDistrictBill").addEventListener("change", function() {
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
            document.getElementById("listSubDistrictBill").innerHTML = option;
            listSubDistrict = results.data;
          }
        }
      });
    });
  </script>
@endsection