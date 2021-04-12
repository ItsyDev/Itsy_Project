@extends("layouts.app")

@section("title", $title)

@section("content")
<h4 class="mb-3">Data Supplier Details</h4>
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-6">
              <table class="table">
                <tr>
                  <td>Nama Supplier</td>
                  <td>: {{ $supplier->supplier_name }}</td>
                </tr>
                <tr>
                  <td>Nomor Supplier</td>
                  <td>: {{ $supplier->supplier_phone }}</td>
                </tr>
                <tr>
                  <td>Alamat Supplier</td>
                  <td>: {{ $supplier->supplier_address }}</td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <table class="table">
                <tr>
                  <td>Catatan Supplier</td>
                  <td>: {{ $supplier->supplier_note }}</td>
                </tr>
                <tr>
                  <td>Status</td>
                  <td>: {{ $supplier->is_active == 1 ? 'Active' : 'Non Active' }}</td>
                </tr>
              </table>
              <button class="d-block ml-auto btn btn-info btn-sm mt-3" onclick="showModal('modalEdit')"><i class="fas fa-edit mr-1"></i>Edit</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalEdit">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Supplier</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{ "/supplier/".encrypt_url($supplier->supplier_id) }}" method="POST" onsubmit="loadRequest()">
            @csrf
            @method("PUT")
            <div class="form-row">
              <div class="form-group col-md has-validation">
                <label>Nama Supplier</label>
                <input type="text" class="form-control @error('supplier_name') is-invalid @enderror" name="supplier_name" value="{{ old("supplier_name") != NULL ? old("supplier_name") : $supplier->supplier_name }}">
                @error("supplier_name")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
              <div class="form-group col-md has-validation">
                <label>Number Supplier</label>
                <input type="number" class="form-control @error('supplier_phone') is-invalid @enderror" name="supplier_phone" value="{{ old("supplier_phone") != NULL ? old("supplier_phone") : $supplier->supplier_phone }}">
                @error("supplier_phone")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>
            <div class="form-group has-validation">
              <label>Alamat Supplier</label>
              <textarea class="form-control @error('supplier_address') is-invalid @enderror" name="supplier_address" cols="30" rows="5">{{ old("supplier_address") != NULL ? old("supplier_address") : $supplier->supplier_address }}</textarea>
                @error("supplier_address")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group has-validation">
              <label>Note Supplier</label>
              <textarea class="form-control @error('supplier_note') is-invalid @enderror" name="supplier_note" cols="30" rows="5">{{ old("supplier_note") != NULL ? old("supplier_note") : $supplier->supplier_note }}</textarea>
                @error("supplier_note")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group has-validation">
              <label>Status</label>
              <select name="is_active" class="form-control" value="{{ old("is_active") != NULL ? old("is_active") : $supplier->is_active }}">
                <option {{ $supplier->is_active == 0 ? "selected" : "" }} value="0">Non Active</option>
                <option {{ $supplier->is_active == 1 ? "selected" : "" }} value="1">Active</option>
              </select>
                @error("is_active")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-success"><i class="fas fa-check mr-1"></i>Edit Supplier</button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection