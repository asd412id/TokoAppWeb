@extends('layouts.master')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div>
          <a href="{{ route('transaksi.create') }}" class="btn btn-sm btn-info col-sm-12 col-md-2 mb-2" id="newitem">Transaksi Baru</a>
          <div class="col-md-4 pl-0 pr-0 pb-2 float-right">
            <form>
              <div class="input-group input-group-sm">
                <input id="cari" type="text" name="cari" class="form-control" placeholder="Pencarian ..." value="{{ request()->cari }}">
                <span class="input-group-append">
                  <button type="submit" class="btn btn-info"><i class="fas fa-search"></i></button>
                </span>
              </div>
            </form>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="card">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <th width="10">No.</th>
                  <th>Tanggal Transaksi</th>
                  <th>Kode Transaksi</th>
                  <th>Nama Transaksi</th>
                  <th>Diskon</th>
                  <th>Total Bayar</th>
                  @if (auth()->user()->isAdmin)
                    <th>User/Kasir</th>
                  @endif
                  <th width="10"></th>
                </thead>
                <tbody>
                  @if (count($data))
                    @foreach ($data as $key => $v)
                      <tr>
                        <td>{{ $data->firstItem()+$key.'.' }}</td>
                        <td>{{ $v->tgl_transaksi_local??'-' }}</td>
                        <td>{{ $v->kode_transaksi??'-' }}</td>
                        <td>{{ $v->nama_transaksi??'-' }}</td>
                        <td>{{ $v->diskon.'%' }}</td>
                        <td class="currency">{{ $v->total_harga }}</td>
                        @if (auth()->user()->isAdmin)
                          <td>{{ $v->user_name??'-' }}</td>
                        @endif
                        <td>
                          <div class="btn-group btn-group-sm">
                            <a href="{{ route('transaksi.print',['uuid'=>$v->uuid]) }}" class="btn btn-success print" id="print-{{ $v->uuid }}" title="Cetak Struk"><i class="fas fa-print"></i></a>
                            @if (auth()->user()->isAdmin)
                              <a href="{{ route('transaksi.show',['uuid'=>$v->uuid]) }}" class="btn btn-info" title="Detail Transaksi"><i class="fas fa-info-circle"></i></a>
                              <a href="{{ route('transaksi.edit',['uuid'=>$v->uuid]) }}" class="btn btn-warning" title="Ubah Data"><i class="fas fa-edit"></i></a>
                              <a href="{{ route('transaksi.destroy',['uuid'=>$v->uuid]) }}" class="confirm btn btn-danger" title="Hapus Data" data-text="Yakin ingin menghapus data ini?"><i class="fas fa-trash-alt"></i></a>
                            @endif
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="8" class="text-center">{{ isset(request()->cari)?'Data tidak ditemukan':'Data tidak tersedia' }}</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
            <div class="p-3">
              {!! $data->links() !!}
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('foot')
<script src="{{ asset('assets') }}/theme/js/socket.io.js"></script>
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
var socket = io(location.hostname+":{{ getenv('NODE_PORT') }}");
socket.on('kode',(data)=>{
  audio.play();
  location.href = '{{ route('transaksi.create') }}';
});
$("#newitem").focus();
$(".print").click(function(e){
  var _t = $(this);
  e.preventDefault();
  printStruk(_t.attr('href'));
})
function printStruk(url) {
  $.get(url,{},function(res){
    if (res.status == 'success') {
      var HTML = res.html;
      var WindowObject = window.open("", "PrintWindow", "width=450,height=650,top=50,left=50,toolbars=no,scrollbars=no,status=no,resizable=no");
      WindowObject.document.writeln(HTML);
      WindowObject.document.close();
      WindowObject.focus();
    }else {
      Toast.fire({
        icon: 'error',
        title: res.message
      })
    }
  },'json');
}
@if (session()->has('print'))
  $("#print-{{ session()->get('print') }}").click();
@endif
</script>
@endsection
