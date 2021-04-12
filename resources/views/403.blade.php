@extends("auth.layouts.app")

@section("title", "Forbidden")

@section("content")
<div id="error">
  <div class="container text-center">
    <div class="pt-8">
      <h2>403 Forbidden!</h2>
      <p>You don't allow this module!</p>
      <button class="btn btn-primary btn-sm" onclick="goBack()">Back</button>
    </div>
  </div>
</div>
@endsection