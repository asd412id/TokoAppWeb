@extends('layouts.master')
@section('head')
<link rel="stylesheet" href="{{ asset('assets') }}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
@endsection
@section('content')
  <form method="post" id="tr-form">
  @csrf
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
                <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" value="{{ $tgl_transaksi }}" name="tgl_transaksi"/>
              </div>
            </div>
            <div class="form-group mb-2">
              <label class="mb-1">Kode Transaksi</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fa fa-receipt"></i></div>
                </div>
                <input type="text" class="form-control" name="kode_transaksi" value="{{ $kode_transaksi }}"/>
              </div>
            </div>
            <div class="form-group mb-2">
              <label class="mb-1">Nama Transaksi</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fa fa-tag"></i></div>
                </div>
                <input type="text" class="form-control" name="nama_transaksi" value="{{ $nama_transaksi }}"/>
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
                    <input type="text" class="form-control currency" name="dibayar" id="dibayar" value="0" autocomplete="off"/>
                  </div>
                </div>
                <div class="col-sm-4">
                  <label class="mb-1">Diskon</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text"><i class="fa fa-percent"></i></div>
                    </div>
                    <input type="number" name="diskon" class="form-control" id="diskon" value="0" autocomplete="off"/>
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
            <div class="form-group text-center">
              <button type="submit" class="btn btn-lg btn-info">SIMPAN TRANSAKSI</button>
            </div>
            <div class="form-group text-center">
              <a href="{{ route('transaksi.index') }}" class="btn btn-lg btn-default">KEMBALI</a>
            </div>
          </div>
        </div>
        <div id="penjualan"></div>
        <div class="mb-2" id="list-barang-wrapper">
          <div class="row">
            <div class="col-sm-12">
              <div class="input-group">
                <span class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-shopping-cart"></i>
                  </span>
                </span>
                <input id="cari" type="text" class="form-control" placeholder="Cari Barang ..." autofocus autocomplete="off">
              </div>
            </div>
          </div>
          <ul id="list-barang"></ul>
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
                  <th width="10"></th>
                </thead>
                <tbody id="list">
                  <tr>
                    <td colspan="7" class="text-center">Scan Barcode/QRCode Barang atau cari barang untuk menambahkan di transaksi</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </form>
@endsection
@section('foot')
<script src="{{ asset('assets') }}/plugins/moment/moment.min.js"></script>
<script src="{{ asset('assets') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="{{ asset('assets') }}/theme/js/socket.io.js"></script>
<script>
@if ($errors->any())
  @foreach ($errors->all() as $e)
  Toast.fire({
    icon: 'error',
    title: '{{ $e }}'
  });
  @break
  @endforeach
@endif
@if (session()->has('message'))
  Toast.fire({
    icon: 'success',
    title: '{{ session()->get('message') }}'
  })
@endif
var socket = io(location.hostname+":{{ getenv('NODE_PORT') }}");
socket.on('kode',(data)=>{
  getBarang(data,true);
});
$(document).keyup(function(e){
  if (e.which == 192 || e.keyCode == 192) {
    $("#dibayar").focus();
  }
})
$("#dibayar").on("focus",function(){
  $(this).select();
})
$("#tr-form").submit(function(){
  var tb = Number($("#total-bayar").autoNumeric('get'));
  var db = Number($("#dibayar").autoNumeric('get'));
  if ($(this).find('#dibayar').val() == '' || $("#list .ls").length <= 0 || $("#cari").is(":focus") || db < tb) {
    return false;
  }
});
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

$("#cari").on('keydown',function(e){
  clearTimeout(load);
  if (e.which == 13 || e.keyCode == 13) {
    $("#list-barang li").first().click();
  }else if(e.which == 192 || e.keyCode == 192){
    return false;
  }else{
    var _this = $(this);
    if (_this.val()=='') {
      $("#list-barang").html('');
    }else{
      $("#list-barang").html('<li>Mencari barang ...</li>');
      if (!process) {
        load = setTimeout(()=>{
          process = true;
          getBarang(_this.val());
        },250);
      }
    }
  }
});

function clickTarget(scan=null) {
  var row = null;
  $(".d-barang").click(function(){
    if ($("#list").find(".ls").length == 0) {
      $("#list").html('');
    }
    var _t = $(this);
    row = $("#"+_t.data('id'));
    if (row.length > 0) {
      var jumlah = Number(row.find('.jumlah').val());
      jumlah++;
      if (jumlah > Number(_t.data('jumlah'))) {
        jumlah--;
      }
      row.find('.jumlah').val(jumlah);
      row.find('.tharga').text(Number(_t.data('harga'))*jumlah);
    }else{
      if (Number(_t.data('jumlah'))>0) {
        row = null;
        var jumlah = 1;
        var ls = `<tr id="`+_t.data('id')+`" class="ls">
        <input type="hidden" name="id[]" value="`+_t.data('id')+`"/>
        <td class="no"></td>
        <td>`+_t.data('kode')+`</td>
        <td>`+_t.data('nama')+`</td>
        <td class="harga">`+_t.data('harga')+`</td>
        <td width="100"><input type="number" min="1" max="`+_t.data('jumlah')+`" name="jumlah[]" class="jumlah form-control" value="`+jumlah+`"/></td>
        <td class="tharga">`+(Number(_t.data('harga'))*jumlah)+`</td>
        <td><button type="button" class="btn btn-xs btn-danger hapus-barang" data-text="Hapus barang ini?"><i class="fas fa-trash-alt" title="Hapus Barang"></i></button></td>
        </tr>`;
        $("#list").append(ls);
      }else {
        Toast.fire({
          icon: 'error',
          title: 'Stok barang tidak tersedia'
        });
      }
    }
    _t.remove();
    $("#cari").val('');
    $("#cari").focus();

    $("input.jumlah").on("change keyup",function(){
      var max = Number($(this).attr("max"));
      var jj = $(this).val();
      if (jj > max) {
        jj = max;
        $(this).val(max);
      }
      var hh = Number($(this).closest("tr").find(".harga").autoNumeric('get'));
      $(this).closest("tr").find(".tharga").text(hh*jj);
      initAutoNumeric();
    });
    $(".hapus-barang").click(function(e){
      e.stopImmediatePropagation();
      if (!confirm("Hapus barang ini?")) {
        return false;
      }
      $(this).closest("tr").remove();
      initAutoNumeric();
      $("#cari").focus();
    });
    $("#diskon").on('change keyup',function(){
      initAutoNumeric();
    });
    initAutoNumeric();
  });
  if (scan) {
    $("#list-barang li").first().click();
    if (!row) {
      $("#list input.jumlah").last().focus();
    }else{
      row.find('input.jumlah').focus();
    }
    audio.play();
  }
}
$("#dibayar").on("keyup change",function(){
  initKem();
});
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
  initDefaultScript();
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
</script>
@endsection
