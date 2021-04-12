@extends("layouts.app")
@section("title", $title)

@section("content")
  <div class="card">
    <div class="card-body">
      <button class="btn btn-success btn-sm mb-4" onclick="navigateTo('add-customer')"><i class="fas fa-plus mr-2"></i>Add New</button>
      <input type="hidden" id="csrfView" value="{{ csrf_token() }}">
      <input type="hidden" id="csrfDelete" value="{{ csrf_token() }}">
      <div class="table-responsive">
        <table class="table table-stripped table-hover table-dark w-100" id="tableCustomer">
          <thead>
            <tr>  
              <th>No</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Type</th>
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

@push('script')
  <script>
    let data = {
      _token: document.getElementById("csrfView").value
    }

    const tableAjax = getDataTableInput("tableCustomer", "get-customer-json", data);

    function promptDeleteCustomer(customerID) {
      promptDeleteItem(`customer/${customerID}`, tableAjax, "csrfDelete");
    }
  </script>
@endpush