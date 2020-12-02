@extends('layouts.master')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div>
          <a href="{{ route('barang.create') }}" class="btn btn-sm btn-info col-sm-12 col-md-2 mb-2">Tambah Data</a>
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
                  <th>Kode</th>
                  <th>Nama Barang</th>
                  <th>Kategori</th>
                  <th>Harga</th>
                  <th>Stok</th>
                  <th width="10"></th>
                </thead>
                <tbody>
                  @if (count($data))
                    @foreach ($data as $key => $v)
                      <tr>
                        <td>{{ $data->firstItem()+$key.'.' }}</td>
                        <td>{{ $v->kode??'-' }}</td>
                        <td>{{ $v->nama??'-' }}</td>
                        <td>{{ count($v->kategori)?implode(' / ',$v->kategori->pluck('nama')->toArray()):'-' }}</td>
                        <td class="currency">{{ $v->harga_jual }}</td>
                        <td>{{ $v->jumlah }}</td>
                        <td>
                          <div class="btn-group btn-group-sm">
                            <a href="{{ route('barang.show',['uuid'=>$v->uuid]) }}" class="btn btn-info" title="Detail Barang"><i class="fas fa-info-circle"></i></a>
                            <a href="{{ route('barang.edit',['uuid'=>$v->uuid]) }}" class="btn btn-warning" title="Ubah Data"><i class="fas fa-edit"></i></a>
                            <a href="{{ route('barang.destroy',['uuid'=>$v->uuid]) }}" class="confirm btn btn-danger" title="Hapus Data" data-text="Yakin ingin menghapus data ini?"><i class="fas fa-trash-alt"></i></a>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="7" class="text-center">{{ isset(request()->cari)?'Data tidak ditemukan':'Data tidak tersedia' }}</td>
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
var socket = io(location.hostname+":{{ getenv('NODE_PORT') }}");
socket.on('kode',(data)=>{
  $("#cari").val(data);
  $("#cari").closest('form').submit();
  audio.play();
})
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
</script>
@endsection
