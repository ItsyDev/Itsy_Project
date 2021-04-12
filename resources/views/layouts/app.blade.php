<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>@yield("title")</title>

  {{-- Library CSS --}}
  <link rel="stylesheet" href="{{ asset("assets/css/White.css") }}">
  <link rel="stylesheet" href="{{ asset("assets/css/Dark.css") }}">
  <link rel="stylesheet" href="{{ asset("assets/vendors/fontawesome/css/all.css") }}">
  <link rel="stylesheet" href="{{ asset("assets/vendors/lineawesome/css/line-awesome.min.css") }}">
  <link rel="stylesheet" href="{{ asset("assets/vendors/sweetalert2/sweetalert2.min.css") }}">

  {{-- Bootstrap CSS --}}
  <link rel="stylesheet" href="{{ asset("assets/vendors/datatables-bs4/css/dataTables.bootstrap4.min.css") }}">
  <link rel="stylesheet" href="{{ asset("assets/vendors/bootstrap/css/bootstrap.min.css") }}">
  <link rel="stylesheet" href="{{ asset("assets/css/style.css") }}">


</head>
<body>

  <div class="lds-ring" id="loadAjax">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
  </div>

  <script src="{{ asset("assets/vendors/bootstrap/js/jquery.min.js") }}"></script>
  <script src="{{ asset("assets/vendors/datatables-bs4/js/jquery.dataTables.min.js") }}"></script>
  <script src="{{ asset("assets/vendors/datatables-bs4/js/dataTables.bootstrap4.min.js") }}"></script>
  <script src="{{ asset("assets/vendors/sweetalert2/sweetalert2.min.js") }}"></script>
  <script src="{{ asset("assets/js/core.js") }}"></script>
  
  <script src="{{ asset("assets/vendors/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
  
  @include("layouts.head")
  @include("layouts.sidenav")
  
	<!--Content Start-->
	<div class="content transition">
    <div class="container-fluid">
      {!! $breadcrumb !!}
      @yield("content")
    </div>
	</div>

  @if(session("message"))
    {!! session("message") !!}
  @endif
  
  @include("layouts.footer")
  @stack("script")

</body>
</html>