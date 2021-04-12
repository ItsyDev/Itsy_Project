@extends("layouts.app")

@section("title", "$title")

@section("content")
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
            <h4 class="card-title text-white">Tambah Data Barang</h4>
        </div>
        <div class="card-content">
          <div class="card-body">
            <form action="" onsubmit="loadRequest()">
              <div class="form-row">
                
              </div>
              <div class="d-flex justify-content-end">
                <button type="button" onclick="goBack()" class="btn btn-warning mr-1 mb-1">Back</button>
                <button type="submit" class="btn btn-primary mr-1 mb-1">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection