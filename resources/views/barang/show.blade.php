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
          <div class="card-body pb-0">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="kode">Kode Barang</label>
                  <input disabled type="text" name="kode" class="form-control" id="kode" placeholder="Masukkan Kode Barang atau Scan Barcode" value="{{ $data->kode }}" autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="nama">Nama Barang</label>
                  <input disabled type="text" name="nama" class="form-control" id="nama" placeholder="Nama Barang" value="{{ $data->nama }}" autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="harga_modal">Harga Modal</label>
                  <input disabled type="text" name="harga_modal" class="form-control currency" id="harga_modal" placeholder="Harga Modal" value="{{ $data->harga_modal }}" autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="harga_modal">Harga Jual</label>
                  <input disabled type="text" name="harga_jual" class="form-control currency" id="harga_jual" placeholder="Harga Jual" value="{{ $data->harga_jual }}" autocomplete="off">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="jumlah">Jumlah Stok</label>
                  <input disabled type="number" name="jumlah" class="form-control" id="jumlah" placeholder="Total Stok Tersedia" value="{{ $data->jumlah }}">
                </div>
                <div class="form-group">
                  <label>Kategori Barang</label>
                  <div>
                    @if (!count($data->kategori))
                      <span class="badge badge-danger">Barang tidak berkategori</span>
                    @else
                      @foreach ($data->kategori as $key => $k)
                        <span class="badge badge-info" style="font-size: 1em">{{ $k->nama }}</span>
                      @endforeach
                    @endif
                  </div>
                </div>
                <div class="form-group">
                  <label for="user_name">User</label>
                  <input disabled type="text" name="user_name" class="form-control" id="user_name" value="{{ $data->user_name }}">
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <a href="{{ route('barang.index') }}" class="btn btn-default">Kembali</a>
          </div>
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
