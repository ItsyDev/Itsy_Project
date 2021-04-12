@extends("auth.layouts.app")

@section("title", "Login")

@section("content")
<div class="container">
  <div class="row vh-100 d-flex justify-content-center align-items-center auth">
    <div class="col-md-7 col-lg-5">
      <div class="card">
        <div class="card-body">
          <h3 class="mb-5">LOGIN</h3>
            <form action="{{ route('process_login' )}}" method="POST" id="formLogin">
              @csrf
            <div class="form-group">
              <input type="email" name="user_email" class="form-control" placeholder="Email">
            </div>
            <div class="form-group">
              <input type="password" name="user_password" class="form-control" placeholder="Password">
            </div>
            <div class="row">
              <div class="col-8 d-block ml-auto">
                <a href="forgot.html">Forgot your password?</a>
              </div>
            </div>
            <div class="form-group my-4">
              <button type="submit" class="btn btn-linear-primary btn-rounded px-5">Login</button>
            </div>
            <p>New member? <span onclick="navigateTo('register')" class="text-primary cursor-pointer">Create account</span></p>
          </form>
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
    $("#formLogin").submit(function(){
      loadRequest();
    });
  </script>
@endpush