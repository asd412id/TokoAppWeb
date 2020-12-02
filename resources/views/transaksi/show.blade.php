@extends('layouts.master')
@section('head')
<link rel="stylesheet" href="{{ asset('assets') }}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
@endsection
@section('content')
<div class="container-fluid" id="transaksi">
  <div class="row">
    <div class="col-12">
      <div class="row mb-2">
        <div class="col-sm-12 col-md-4">
          <div class="form-group mb-2">
            <label class="mb-1">Tanggal Transaksi</label>
            <div class="input-group date" id="reservationdate" data-target-input="nearest">
              <div class="input-group-prepend" data-target="#reservationdate" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
              <input disabled type="text" class="form-control datetimepicker-input" data-target="#reservationdate" value="{{ $data->tgl_transaksi->format('d/m/Y') }}" name="tgl_transaksi"/>
            </div>
          </div>
          <div class="form-group mb-2">
            <label class="mb-1">Kode Transaksi</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-receipt"></i></div>
              </div>
              <input disabled type="text" class="form-control" name="kode_transaksi" value="{{ $data->kode_transaksi }}"/>
            </div>
          </div>
          <div class="form-group mb-2">
            <label class="mb-1">Nama Transaksi</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-tag"></i></div>
              </div>
              <input disabled type="text" class="form-control" name="nama_transaksi" value="{{ $data->nama_transaksi }}"/>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-4">
          <div class="form-group mb-2">
            <label class="mb-1">Total Bayar</label>
            <h2 class="m-0 p-0 currency" id="total-bayar">0</h2>
          </div>
          <div class="form-group mb-2">
            <div class="row">
              <div class="col-sm-8">
                <label class="mb-1">Dibayar</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa fa-money-bill-wave"></i></div>
                  </div>
                  <input disabled type="text" class="form-control currency" name="dibayar" id="dibayar" value="{{ $data->dibayar }}" autocomplete="off"/>
                </div>
              </div>
              <div class="col-sm-4">
                <label class="mb-1">Diskon</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa fa-percent"></i></div>
                  </div>
                  <input disabled type="number" name="diskon" class="form-control" id="diskon" value="{{ $data->diskon }}" autocomplete="off"/>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group mb-2">
            <label class="mb-1">Kembalian</label>
            <h2 class="m-0 p-0 currency" id="kembalian">0</h2>
          </div>
        </div>
        <div class="col-sm-12 col-md-4">
          <div class="form-group">
            <label class="mb-1">User/Kasir</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-user"></i></div>
              </div>
              <input disabled type="text" name="user_name" class="form-control" id="user_name" value="{{ $data->user_name }}" autocomplete="off"/>
            </div>
          </div>
          <div class="form-group text-center">
            <a href="{{ route('transaksi.index') }}" class="btn btn-lg btn-default">KEMBALI</a>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <th width="10">No.</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total Bayar</th>
              </thead>
              <tbody id="list">
                @if (count($data->penjualan))
                  @foreach ($data->penjualan as $key => $dp)
                    <tr id="{{ $dp->barang_id }}" class="ls">
                    <input disabled type="hidden" name="id[]" value="{{ $dp->barang_id }}"/>
                    <td class="no">{{ $key+1 }}</td>
                    <td>{{ $dp->kode }}</td>
                    <td>{{ $dp->nama }}</td>
                    <td class="harga currency">{{ $dp->harga_jual }}</td>
                    <td width="100"><input disabled type="number" max="{{ $dp->barangByKode->jumlah }}" name="jumlah[]" class="jumlah form-control" value="{{ $dp->jumlah }}"/></td>
                    <td class="tharga currency">{{ $dp->harga_jual*$dp->jumlah }}</td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="7" class="text-center">Scan Barcode/QRCode Barang atau cari barang untuk menambahkan di transaksi</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('foot')
<script src="{{ asset('assets') }}/plugins/moment/moment.min.js"></script>
<script src="{{ asset('assets') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="{{ asset('assets') }}/theme/js/socket.io.js"></script>
<script>
$('#reservationdate').datetimepicker({
    format: 'DD/MM/YYYY'
});
var j = Number($("#jumlah").val());
var process = false;
var load;
function getBarang(kode,scan=null){
  kode = kode.split('*');
  $.get(location.href,{kode: kode[0]},function(res){
    if (res.status == 'success' && res.data) {
      var brg = `<li
      class="d-barang"
      data-id="`+res.data.id+`"
      data-kode="`+res.data.kode+`"
      data-nama="`+res.data.nama+`"
      data-harga="`+res.data.harga_jual+`"
      data-jumlah="`+res.data.jumlah+`"
      >`+res.data.nama+` (<span class="hj">`+res.data.harga_jual+`</span>)</li>`;
      $("#list-barang").html(brg);
      $(".hj").autoNumeric({
        aSign: 'Rp ',
        aSep: '.',
        aDec: ',',
        mDec: false
      });
      clickTarget(scan);
    }else{
      $("#list-barang").html('<li>Barang tidak tersedia</li>');
    }
    process = false;
  },'json');
}

function initAutoNumeric() {
  $(".harga,.tharga,#total-bayar,#kembalian").autoNumeric('destroy');
  $(".harga,.tharga,#total-bayar,#kembalian").autoNumeric({
    aSign: 'Rp ',
    aSep: '.',
    aDec: ',',
    mDec: false
  });
  var totalbayar = 0;
  $(".tharga").each(function(){
    var th = Number($(this).autoNumeric('get'));
    totalbayar += th;
  });
  var diskon = Number($("#diskon").val());
  $("#total-bayar").autoNumeric('set',(totalbayar-(totalbayar*(diskon/100))));
  $(".ls .no").each(function(i,v){
    $(this).text(i+1);
  });
  if ($("#list").find("tr").length == 0) {
    $("#list").html('<tr><td colspan="7" class="text-center">Scan Barcode/QRCode Barang atau cari barang untuk menambahkan di transaksi</td></tr>');
  }
  initKem();
}

function initKem(){
  var tb = Number($("#total-bayar").autoNumeric('get'));
  var db = Number($("#dibayar").autoNumeric('get'));
  var tk = db-tb;
  $("#kembalian").removeClass('text-success');
  $("#kembalian").removeClass('text-danger');
  if (db < tb) {
    tk = tb-db;
    var cn = 'text-danger';
  }else {
    var cn = 'text-success';
  }
  $("#kembalian").autoNumeric("set",tk);
  $("#kembalian").addClass(cn);
}
initAutoNumeric();
</script>
@endsection
