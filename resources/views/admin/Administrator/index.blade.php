@extends("layouts.app");

@section("title", "Administration")

@section("content")
  <div class="card">
    <div class="card-body">
      <button class="btn btn-success mb-4" onclick="showModal('modalAdd')"><i class="fas fa-plus mr-2"></i>Add New</button>
      <input type="hidden" id="csrfHash" value="{{ csrf_token() }}">
      <input type="hidden" id="csrfDelete" value="{{ csrf_token() }}">
      <div class="table-responsive">
        <table class="table table-dark table-hover table-bordered w-100" id="tableUser">
          <thead>
            <tr>
              <th>No</th>
              <th>Fullname</th>
              <th>Username</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Status</th>
              <th>Role</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalAdd">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add New User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="/validation-user-add" method="POST" enctype="multipart/form-data" onsubmit="loadRequest()">
            @csrf
            <div class="form-row">
              <div class="form-group col-md has-validation">
                <label>Fullname</label>
                <input type="text" class="form-control @error('user_fullname') is-invalid @enderror" name="user_fullname" value="{{ old('user_fullname') }}">
                @error("user_fullname")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
              <div class="form-group col-md has-validation">
                <label>Username</label>
                <input type="text" class="form-control @error('user_name') is-invalid @enderror" name="user_name" value="{{ old('user_name') }}">
                @error("user_name")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md has-validation">
                <label>Email</label>
                <input type="email" class="form-control @error('user_email') is-invalid @enderror" name="user_email" value="{{ old('user_email') }}">
                @error("user_email")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
              <div class="form-group col-md has-validation">
                <label>Phone</label>
                <input type="number" class="form-control @error('user_phone') is-invalid @enderror" name="user_phone" value="{{ old('user_phone') }}">
                @error("user_phone")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>
            <div class="form-group">
              <label>Address</label>
              <textarea name="user_address" class="form-control @error('user_address') is-invalid @enderror" cols="30" rows="5">{{ old("user_address") }}</textarea>
              @error("user_address")
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
            <div class="form-group">
              <label>Role</label>
              <select name="access_id" class="form-control @error('access_id') is-invalid @enderror">
                @foreach ($list_access as $item)
                  <option @if ($item->access_id == old("access_id")) selected @endif value="{{ $item->access_id }}">{{ $item->level_name }}</option>
                @endforeach
              </select>
              @error("access_id")
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
            <div class="form-row">
              <div class="form-group col-md has-validation">
                <label>Password</label>
                <input type="password" name="user_password" class="form-control @error('user_password') is-invalid @enderror">
                @error("user_password")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
              <div class="form-group col-md has-validation">
                <label>Confirm Password</label>
                <input type="password" name="user_password_confirmation" class="form-control @error('user_password_confirmation') is-invalid @enderror">
                @error("user_password_confirmation")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>
            <button type="submit" class="btn btn-success"><i class="fas fa-plus mr-1"></i>Add</button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push("script")
  <script>
    let data = {
      _token: document.getElementById("csrfHash").value
    };
    let tableAjax = getDataTableInput("tableUser", "get-user", data);

    function changeStatusUser(link, deadactive = false) {
      let message = deadactive === false ? "Ingin mengaktifkan user ini?" : "Ingin me-nonaktifkan user ini?";
      promptAction(link, message, tableAjax);
    }

    function promptDeleteUser(userID) {
      promptDeleteItem(`user/${userID}`, tableAjax, "csrfDelete");
    }
  </script>
@endpush