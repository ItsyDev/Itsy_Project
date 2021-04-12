@extends("layouts.app")

@section("title", $title)

@section("content")
  <div class="card">
    <div class="card-body">
      <button class="btn btn-success btn-sm mb-3" onclick="showModal('addModal')"><i class="fas fa-plus mr-1"></i>Add New</button>
      <input type="hidden" id="csrfHash" value="{{ csrf_token() }}">
      <input type="hidden" id="csrfDelete" value="{{ csrf_token() }}">
      <div class="table-responsive">
        <table class="table table-bordered table-dark table-hover w-100" id="tableSupplier">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama supplier</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade text-body" id="addModal">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah supplier</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form action="/supplier-add" method="POST" onsubmit="loadRequest()">
            @csrf
            <div class="form-group">
              <label>Supplier Name</label>
              <input type="text" class="form-control @error("supplier_name") is-invalid @enderror" name="supplier_name" placeholder="Input supplier Name..." value="{{ old("supplier_name") }}">
              @error("supplier_name")
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
            <div class="form-group">
              <label>Supplier Phone</label>
                <input type="text" class="form-control @error("supplier_phone") is-invalid @enderror" placeholder="Input supplier Phone..." name="supplier_phone" value="{{ old("supplier_phone") }}">
                @error("supplier_phone")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
              <label>Supplier Address</label>
                <textarea class="form-control @error("supplier_address") is-invalid @enderror" placeholder="Input supplier Address..." name="supplier_address" cols="30" rows="5">{{ old("supplier_address") }}</textarea>
                @error("supplier_address")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
              <label>Supplier Note</label>
                <textarea class="form-control @error("supplier_note") is-invalid @enderror" placeholder="Input supplier Note..." name="supplier_note" cols="30" rows="5">{{ old("supplier_note") }}</textarea>
                @error("supplier_note")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
              <label >Status</label>
              <select name="is_active" class="form-control" value="{{ old("is_active") }}">
                <option value="1">Active</option>
                <option value="0">Non Active</option>
              </select>
            </div>
            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-plus fa-sm mr-2"></i>Simpan</button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push("script")
  <script>
    let data = {
      _token : document.getElementById("csrfHash").value
    }
    let tableAjax = getDataTableInput("tableSupplier", "json-supplier",data);

    function addSupplier() {
      sendDataWithError("formAdd", "process-supplier-add", "addModal", tableAjax);
    }

    function editSupplier() {
      sendData("formEdit", "process-supplier-edit", "editModal", tableAjax);
    }

    function getsupplierDetail(supplierID) {
      getDataEdit(`get-supplier-detail/${supplierID}`, "editModal");
    }

    function promptDeleteSupplier(supplierID) {
      promptDeleteItem(`supplier/${supplierID}`, tableAjax, "csrfDelete");
    }
  </script>
@endpush