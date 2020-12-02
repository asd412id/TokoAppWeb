@extends('layouts.master')
@section('head')
<link rel="stylesheet" href="{{ asset('assets') }}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/plugins/daterangepicker/daterangepicker.css">
@endsection
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <form method="post" action="{{ route('rekapitulasi.rekap') }}" id="form-rekap">
          <div class="card p-0">
            <div class="card-header">
              <div class="row">
                <div class="col-md-3 col-sm-12">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="daterange" type="text" class="form-control float-right datepicker" placeholder="Rentang Waktu">
                  </div>
                </div>
                <div class="col-md-3 col-sm-12">
                  <button type="submit" class="btn btn-info">Proses</button>
                  <button type="button" id="print" class="btn btn-success">Print</button>
                </div>
              </div>
              <div class="clearfix"></div>
            </div>
            <div class="card-body p-3" id="result">
              <h5 class="text-center">Klik "Proses" untuk mulai memproses rekapitulasi</h5>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
@section('foot')
<script src="{{ asset('assets') }}/plugins/moment/moment.min.js"></script>
<script src="{{ asset('assets') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="{{ asset('assets') }}/plugins/daterangepicker/daterangepicker.js"></script>
<script>
@if ($errors->any())
  @foreach ($errors->all() as $e)
  Toast.fire({
    icon: 'error',
    title: '{{ $e }}'
  })
  @endforeach
@endif
@if (session()->has('message'))
  Toast.fire({
    icon: 'success',
    title: '{{ session()->get('message') }}'
  })
@endif
$("#form-rekap").submit(function(e){
  e.preventDefault();
  var _t = $(this);
  var _tb = $(this).find('button[type="submit"]');
  var txt = _tb.html();
  _tb.html('<i class="fas fa-circle-notch fa-spin"></i> Silahkan tunggu ...');
  _tb.prop('disabled',true);
  $("#result").html('<h5 class="text-center text-info"><i class="fas fa-circle-notch fa-spin"></i> Memuat data ...</h5>');
  $.post(_t.attr('action'),{daterange: _t.find('input[name="daterange"]').val(),_token: '{{ csrf_token() }}'},function(res){
    if (res.status == 'success') {
      $("#result").html(res.html);
    }else {
      $("#result").html('<h5 class="text-center text-info">Data tidak ditemukan</h5>');
    }
    _tb.prop('disabled',false);
    _tb.html(txt);
  },'json');
});
$("#print").click(function(){
  var _t = $(this);
  var txt = $(this).html();
  _t.html('<i class="fas fa-circle-notch fa-spin"></i> Silahkan tunggu ...');
  _t.prop('disabled',true);
  $.post('{{ route('rekapitulasi.print') }}',{daterange: _t.closest('form').find('input[name="daterange"]').val(),_token: '{{ csrf_token() }}'},function(res){
    if (res.status == 'success') {
      var HTML = res.html;
      var WindowObject = window.open("", "PrintWindow", "width=1000,height=650,top=50,left=50,toolbars=no,scrollbars=no,status=no,resizable=no");
      WindowObject.document.writeln(HTML);
      WindowObject.document.close();
      WindowObject.focus();
    }else {
      Toast.fire({
        icon: 'error',
        title: res.message
      })
    }
    _t.prop('disabled',false);
    _t.html(txt);
  },'json');
})
</script>
@endsection
