@extends("layouts.app")

@section("title", $title)

@section("content")
  <div class="card">
    <div class="card-body">
      <button class="btn btn-primary btn-sm mb-2" onclick="{{ "navigateTo('warehouse/$warehouse_id/edit')" }}"><i class="fas fa-edit"></i></button>
      <div class="row">
        <div class="col-md-6">
          <table class="table">
            <tr>
              <td>Warehouse Name</td>
              <td>: {{ $warehouse->warehouse_name }}</td>
            </tr>
            <tr>
              <td>Warehouse Phone</td>
              <td>: {{ $warehouse->warehouse_phone }}</td>
            </tr>
            <tr>
              <td>PIC Name</td>
              <td>: {{ $warehouse->pic_name }}</td>
            </tr>
            <tr>
              <td>Note</td>
              <td>: {{ $warehouse->warehouse_note }}</td>
            </tr>
            <tr>
              <td>Status</td>
              <td>: {{ $warehouse->is_active == 1 ? "Active" : "Non-Active" }}</td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table">
            <tr>
              <td>Province</td>
              <td>: {{ $warehouse->province_name }}</td>
            </tr>
            <tr>
              <td>District</td>
              <td>: {{ $warehouse->district_name }}</td>
            </tr>
            <tr>
              <td>Subdistrict</td>
              <td>: {{ $warehouse->subdistrict_name }}</td>
            </tr>
            <tr>
              <td>Full Address</td>
              <td>: {{ $warehouse->full_address }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection