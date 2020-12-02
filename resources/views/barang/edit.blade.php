@extends('layouts.master')
@section('head')
<link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endsection
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Barang: <strong>{{ $data->kode.' - '.$data->nama }}</strong></h3>
          </div>
          <form role="form" method="post">
            @csrf
            <div class="card-body pb-0">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="kode">Kode Barang</label>
                    <input type="text" name="kode" class="form-control" id="kode" placeholder="Masukkan Kode Barang atau Scan Barcode" value="{{ old('kode')??$data->kode }}" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="nama">Nama Barang</label>
                    <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama Barang" value="{{ old('nama')??$data->nama }}" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="harga_modal">Harga Modal</label>
                    <input type="text" name="harga_modal" class="form-control currency" id="harga_modal" placeholder="Harga Modal" value="{{ old('harga_modal')??$data->harga_modal }}" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="harga_modal">Harga Jual</label>
                    <input type="text" name="harga_jual" class="form-control currency" id="harga_jual" placeholder="Harga Jual" value="{{ old('harga_jual')??$data->harga_jual }}" autocomplete="off">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="jumlah">Jumlah Stok</label>
                    <input type="number" name="jumlah" class="form-control" id="jumlah" placeholder="Total Stok Tersedia" value="{{ old('jumlah')??$data->jumlah }}">
                  </div>
                  <div class="form-group">
                    <label>Kategori Barang</label>
                    <select class="select2" data-dropdown-css-class="select2-purple" multiple="multiple" data-placeholder="Pilih Kategori Barang" style="width: 100%;" name="kategori[]">
                      @foreach ($kategori as $k)
                        <option {{ $data->kategori&&in_array($k->id,$data->kategori->pluck('id')->toArray())?'selected':'' }} value="{{ $k->id }}">{{ $k->nama }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-info">Simpan</button>
              <a href="{{ route('barang.index') }}" class="btn btn-default">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('foot')
<script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>
<script src="{{ asset('assets') }}/theme/js/socket.io.js"></script>
<script>
var j = Number($("#jumlah").val());
var socket = io(location.hostname+":{{ getenv('NODE_PORT') }}");
socket.on('kode',(data)=>{
  j++;
  $("#kode").val(data);
  $("#jumlah").val(j)
  audio.play();
  $("#nama").focus();
})
$('.select2').select2({
  theme: 'bootstrap4'
})
@if ($errors->any())
  @foreach ($errors->all() as $e)
  Toast.fire({
    icon: 'error',
    title: '{{ $e }}'
  })
  @break
  @endforeach
@endif
</script>
@endsection
