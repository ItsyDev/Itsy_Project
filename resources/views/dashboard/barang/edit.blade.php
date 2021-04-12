@extends("layouts.app")

@section("title", "Edit Barang Management")

@section("content")
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
          <form class="form" action="/barang/{{$barang->barang_id}}/update" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="row">
              <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="first-name-column">Nama Barang</label>
                    <input type="text" value="{{ $barang->barang_name }}" id="first-name-column" class="form-control" placeholder="Nama Barang"
                    name="barang_name">
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="last-name-column">Description</label>
                    <input type="text" value="{{ $barang->description }}" id="last-name-column" class="form-control" placeholder="Description"
                        name="description">
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="city-column">Total Stock</label>
                    <input type="text" value="{{ $barang->total_stok }}" id="city-column" class="form-control" placeholder="Total Stock" name="total_stok">
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="example">Gambar</label>
                  <input type="file" class="form-control" value="{{ $barang->photo_path }}"  name="photo_path">
              </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="company-column">Berat Barang</label>
                    <input type="text" id="company-column" value="{{ $barang->berat }}" class="form-control" name="berat"
                        placeholder="Berat Barang">
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="company-column">Panjang Barang</label>
                    <input type="text" id="company-column" value="{{ $barang->panjang_meter }}" class="form-control" name="panjang_meter"
                        placeholder="Panjang Barang">
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="company-column">Lebar Barang</label>
                    <input type="text" id="company-column" value="{{ $barang->lebar_meter }}" class="form-control" name="lebar_meter"
                        placeholder="Lebar Barang">
                </div>
              </div>
              <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary mr-1 mb-1">Submit</button>
              </div>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection