@extends("layouts.app")

@section("title", $title)

@section("content")
  <div class="card">
    <div class="card-body">
      <button class="btn btn-success btn-sm mb-3" onclick="navigateTo('add-warehouse')"><i class="fas fa-plus mr-2"></i>Add New</button>
      <input type="hidden" id="csrfView" value="{{ csrf_token() }}">
      <div class="table-responsive">
        <table class="table table-dark table-bordered table-hover w-100" id="tableWarehouse">
          <thead>
            <tr>
              <th>No</th>
              <th>Warehouse Name</th>
              <th>Warehouse Phone</th>
              <th>PIC Name</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@push("script")
  <script>
    let data = {
      _token : document.getElementById("csrfView").value
    }
    let tableAjax = getDataTableInput("tableWarehouse", "get-warehouse",data);
  </script>
@endpush