@extends("auth.layouts.app")

@section("title", "Register")

@section("content")
  <div class="container-fluid">
    <div class="row mt-5" id="sectionUser">
      <div class="col-md-8 mx-auto">
        <div class="card">
          <form id="formRegister" method="POST" action="{{ route("validate_register") }}">
            @csrf
          <div class="card-body">
            <h4 class="text-center">Welcome To Itsy!</h4>
            <h5>Register Your Account</h5>
            <div class="form-group">
              <label>Fullname</label>
                <input type="text" class="form-control required @error("user_fullname") is-invalid @enderror" placeholder="Your fullname..." name="user_fullname" value="{{ old("user_fullname") }}">
                @error("user_fullname")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-row">
              <div class="form-group col-md has-validation">
                <label>Email</label>
                <input type="email" class="form-control required @error("user_email") is-invalid @enderror" placeholder="Your email..." name="user_email" value="{{ old("user_email") }}">
                @error("user_email")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
              <div class="form-group col-md has-validation">
                <label>Phone</label>
                <input type="number" class="form-control required @error("user_phone") is-invalid @enderror" placeholder="Your phone number..." name="user_phone" value="{{ old("user_phone") }}">
                @error("user_phone")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>
            <div class="form-group has-validation">
              <label>Address</label>
              <textarea name="user_address" class="form-control  @error("user_address") is-invalid @enderror" cols="30" rows="5" placeholder="Input your address...">{{ old("user_address") }}</textarea>
              @error("user_address")
                  <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
            <div class="form-group">
              <label>Username</label>
              <input type="text" class="form-control required @error("user_name") is-invalid @enderror" name="user_name" placeholder="Input your username..." value="{{ old("user_name") }}">
              @error("user_name")
                  <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
            <div class="form-row">
              <div class="form-group col-md">
                <label>Password</label>
                <input type="password" name="user_password" class="form-control required" placeholder="Input your password...">
                @error("user_password")
                  <small class="text-danger">{{ $message }}</small>
              @enderror
              </div>
              <div class="form-group col-md">
                <label>Confirm Password</label>
                <input type="password" name="user_password_confirmation" class="form-control required" placeholder="Confirm your password...">
                @error("user_password_confirmation")
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>
            <button type="button" class="btn btn-primary" id="btnNext">Next</button>
          </div>
          <div class="card-footer">
            <p>Have a acoount? <span class="text-primary cursor-pointer" onclick="navigateTo('login')">Login Now!</span></p>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-5 store-card" id="sectionStore">
      <div class="col-md-8 mx-auto">
        <div class="card">
          <div class="card-body">
            <h4 class="text-center">Welcome To Itsy!</h4>
            <h5>Register your store!</h5>
            <div class="form-group">
              <label>Store Name</label>
              <input type="text" name="toko_name" class="form-control @error("toko_name") is-invalid @enderror" placeholder="Input your store name..." value="{{ old("toko_name") }}">
              @error("toko_name")
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
            <div class="form-group">
              <label>Store Category</label>
              <select name="category_toko_id" class="form-control @error("category_toko_id") is-invalid @enderror" value="{{ old("category_toko_id") }}">
                @foreach ($list_category_toko as $item)
                  <option value="{{ $item->category_toko_id }}">{{ $item->category_toko }}</option>
                @endforeach
              </select>
              @error("category_toko_id")
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
            <h6>Address</h6>
            <div class="form-row">
              <div class="form-group col-md">
                <label>Province</label>
                <select name="province_id" class="form-control @error("province_id") is-invalid @enderror" value="{{ old("province_id") }}">
                  <option value="1">Jawa Barat</option>
                  <option value="2">Jawa Tengah</option>
                  <option value="3">Jawa Timur</option>
                </select>
              </div>
              <div class="form-group col-md">
                <label>District</label>
                <select name="district_id" class="form-control" value="{{ old("district_id") }}">
                  <option value="1">Bekasi</option>
                  <option value="2">Semarang</option>
                  <option value="3">Surabaya</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label>Store Address</label>
              <textarea name="toko_address" class="form-control @error("toko_address") is-invalid @enderror" cols="30" rows="5" placeholder="Input your store address...">{{ old("toko_address") }}</textarea>
              @error("toko_address")
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
            <button type="button" class="btn btn-warning" id="btnBack">Back</button>
            <button type="button" class="btn btn-primary" id="btnRegister">Register</button>
          </form>
          </div>
          <div class="card-footer">
            <p>Have a acoount? <span class="text-primary cursor-pointer" onclick="navigateTo('login')">Login Now!</span></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if(session("message"))
      {!! session("message") !!}
  @endif
@endsection

@push("script")
  <script>
    $("#sectionStore").hide();
    // $("#sectionUser").fadeIn();

    $("#btnNext").click(function() {
      // let next = true;
      // let classRequired = document.getElementsByClassName("required");
      // for (let input of classRequired) {
      //   if (input.value == "") {
      //     next = false;
      //     input.classList.add("is-invalid");
      //   }
      // }

      // if (next) {
      //   $("#sectionStore").removeClass("d-none");
      //   $("#sectionStore").fadeIn("slow");
      //   $("#sectionUser").fadeOut("slow");
      // }

      // $("#sectionStore").removeClass("d-none");
      $("#sectionStore").show();
      $("#sectionUser").hide(100);
    });

    $("#btnBack").click(function() {
      $("#sectionStore").hide(100);
      $("#sectionUser").show();
    });

    $("#btnRegister").click(function() {
      loadRequest();
      $("#formRegister").submit();
    });
  </script>
@endpush