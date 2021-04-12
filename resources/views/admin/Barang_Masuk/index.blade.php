@extends("layouts.app")

@section("title", $title)

@section("content")
  <div class="card">
    <div class="card-body">
      <button class="btn btn-success mb-3" onclick="navigateTo('tambah-barang')"><i class="fas fa-plus mr-1"></i> Add New</button>
    </div>
  </div>
@endsection