@extends("layouts.app")

@section("title", "Barang Management")

@section("content")
  <h4 class="pb-2 pt-3 pl-3">Data Barang Management</h4>
  <a href="{{ route('create_barang') }}" class="ml-3 mb-3 btn btn-primary btn-icon"><i class='fas fa-edit icon-button'></i>Tambah Data</a>
  @if(session('sukses'))
    <div class="container">
    <div class="alert alert-success alert-icon" role="alert"><i class='fas fa-check-circle'></i>
        {{session('sukses')}}
    </div>
    </div>
  @endif
  <div class="container-fluid">
    <table class="table table-dark table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Barang</th>
          <th>Description</th>
          <th>Total Stock</th>
          <th>Foto</th>
          <th>Berat</th>
          <th>Panjang</th>
          <th>Lebar</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($barang as $data)
        <tr>
          <th>{{ $loop->iteration }}</th>
          <td>{{ $data->barang_name }}</td>
          <td>{{ $data->description }}</td>
          <td>{{ $data->total_stok }}</td>
          <td>{{ $data->photo_path }}</td>
          <td>{{ $data->berat }}</td>
          <td>{{ $data->panjang_meter }}</td>
          <td>{{ $data->lebar_meter }}</td>
          <td>
            <a href="/barang/{{$data->barang_id}}/edit" class="btn btn-warning btn-sm">Edit</a>
            <a href="/barang/{{$data->barang_id}}/delete" onclick="return confirm('Apakah yakin ingin dihapus ?')" class="btn btn-danger btn-sm">Delete</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection