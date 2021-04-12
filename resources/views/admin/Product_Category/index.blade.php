@extends("layouts.app")

@section("title", $title)

@section("content")
  <div class="card">
    <div class="card-body">
      <button class="btn btn-success btn-sm mb-3" onclick="showModal('addModal')"><i class="fas fa-plus mr-1"></i>Add New</button>
      <input type="hidden" id="csrfHash" value="{{ csrf_token() }}">
      <input type="hidden" id="csrfDelete" value="{{ csrf_token() }}">
      <div class="table-responsive">
        <table class="table table-bordered table-dark table-hover w-100" id="tableCategory">
          <thead>
            <tr>
              <th>No</th>
              <th>Category</th>
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
          <h4 class="modal-title">Tambah Category</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="formAdd">
            @csrf
            <div class="form-group">
              <label>Category</label>
              <input type="text" class="form-control input-insert" name="product_category" placeholder="Input category...">
            </div>
            <button type="button" class="btn btn-success btn-sm" onclick="addCategory()">Simpan</button>
          </form>
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
          <h4 class="modal-title">Edit Category</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="formEdit">
            @csrf
            <div class="form-group">
              <label>Category</label>
              <input type="hidden" class="input-edit" name="product_category_id">
              <input type="text" class="form-control input-edit" name="product_category" placeholder="Input category...">
            </div>
            <button type="button" class="btn btn-success btn-sm" onclick="editCategory()">Simpan</button>
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
      _token: document.getElementById("csrfHash").value
    }
    let tableAjax = getDataTableInput("tableCategory", "json-category-product", data);

    function addCategory() {
      sendDataWithError("formAdd", "process-product-category-add", "addModal", tableAjax);
    }

    function editCategory() {
      sendData("formEdit", "process-product-category-edit", "editModal", tableAjax);
    }

    function deleteCategory(categoryID) {
      promptDeleteItem(`process-product-category-delete/${categoryID}`, tableAjax, "csrfDelete");
    }

    function getCategoryDetail(categoryID) {
      getDataEdit(`get-product-category-detail/${categoryID}`, "editModal");
    }
  </script>
@endpush