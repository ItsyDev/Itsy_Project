@extends("layouts.app")

@section("title", "User Detail")

@section("content")
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <img src="{{ $user->user_photo }}" class="d-block mx-auto w-100" alt="User Image" onerror="onErrorImage(this)">
        </div>
        <div class="col-md-8">
          <div class="row">
            <div class="col-md-6">
              <table class="table">
                <tr>
                  <td>Fullname</td>
                  <td>: {{ $user->user_fullname }}</td>
                </tr>
                <tr>
                  <td>Username</td>
                  <td>: {{ $user->user_name }}</td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td>: {{ $user->user_email }}</td>
                </tr>
                <tr>
                  <td>Phone</td>
                  <td>: {{ $user->user_phone }}</td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <table class="table">
                <tr>
                  <td>Role</td>
                  <td>: {{ $user->level_name }}</td>
                </tr>
                <tr>
                  <td>Status</td>
                  <td>: {{ $user->user_status }}</td>
                </tr>
                <tr>
                  <td>Last Active</td>
                  <td>: {{ empty($user->last_active) ? "-" : date("d M Y", strtotime($user->last_active)) }}</td>
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
          <h5 class="modal-title">Edit User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{ "/user/".encrypt_url($user->user_id) }}" method="POST" onsubmit="loadRequest()" enctype="multipart/form-data">
            @csrf
            @method("PUT")

            <div class="form-row">
              <div class="form-group col-md has-validation">
                <label>Fullname</label>
                <input type="text" class="form-control @error('user_fullname') is-invalid @enderror" name="user_fullname" value="{{ old("user_fullname") != NULL ? old("user_fullname") : $user->user_fullname }}">
                @error("user_fullname")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
              <div class="form-group col-md has-validation">
                <label>Username</label>
                <input type="text" class="form-control @error('user_name') is-invalid @enderror" name="user_name" value="{{ old("user_name") != NULL ? old("user_name") : $user->user_name }}">
                @error("user_name")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md has-validation">
                <label>Email</label>
                <input type="email" class="form-control @error('user_email') is-invalid @enderror" name="user_email" value="{{ old("user_email") != NULL ? old("user_email") : $user->user_email }}">
                @error("user_email")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
              <div class="form-group col-md has-validation">
                <label>Phone</label>
                <input type="number" class="form-control @error('user_phone') is-invalid @enderror" name="user_phone" value="{{ old("user_phone") != NULL ? old("user_phone") : $user->user_phone }}">
                @error("user_phone")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>
            <div class="form-group">
              <label>Address</label>
              <textarea name="user_address" class="form-control @error('user_address') is-invalid @enderror" cols="30" rows="5">{{ old("user_address") != NULL ? old("user_address") : $user->user_address }}</textarea>
              @error("user_address")
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
            <div class="form-group">
              <label>Role</label>
              @if ($user->admin_level == 90)
              <input type="text" name="access_name" class="form-control" value="{{ $user->level_name }}" disabled>
              @else
              <select name="access_id" class="form-control @error('access_id') is-invalid @enderror">
                @foreach ($list_access as $item)
                  <option {{ old("access_id") != NULL && old("access_id") == $item->access_id ? "selected" : ($user->access_id == $item->access_id ? "selected" : "") }} value="{{ $item->access_id }}">{{ $item->level_name }}</option>
                @endforeach
              </select>
              @error("access_id")
                <small class="text-danger">{{ $message }}</small>
              @enderror
              @endif
            </div>
            <div class="form-group">
              <label>Photo</label>
              <input type="file" name="user_photo" class="form-control mb-2" id="inputFile" onchange="readerImage('inputFile', 'imgUser')">
              @error("user_photo")
                <small class="text-danger">{{ $message }}</small>
              @enderror
              <img src="" id="imgUser" class="w-100">
            </div>
            <div class="form-row">
              <div class="form-group col-md has-validation">
                <label>Password</label>
                <input type="password" name="user_password" class="form-control">
                @error("user_password")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
              <div class="form-group col-md has-validation">
                <label>Confirm Password</label>
                <input type="password" name="user_password_confirmation" class="form-control">
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