@extends('layouts.master')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div>
          <a href="{{ route('user.create') }}" class="btn btn-sm btn-info col-sm-12 col-md-2 mb-2">Tambah Data</a>
          <div class="col-md-4 pl-0 pr-0 pb-2 float-right">
            <form>
              <div class="input-group input-group-sm">
                <input type="text" name="cari" class="form-control" placeholder="Pencarian ..." value="{{ request()->cari }}">
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
                  <th>Username</th>
                  <th>Nama</th>
                  <th>Role</th>
                  <th width="10"></th>
                </thead>
                <tbody>
                  @if (count($data))
                    @foreach ($data as $key => $v)
                      <tr>
                        <td>{{ $data->firstItem()+$key.'.' }}</td>
                        <td>{{ $v->username??'-' }}</td>
                        <td>{{ $v->name??'-' }}</td>
                        <td>{{ $v->role?ucwords($v->role):'-' }}</td>
                        <td>
                          <div class="btn-group btn-group-sm">
                            <a href="{{ route('user.edit',['uuid'=>$v->uuid]) }}" class="btn btn-warning" title="Ubah Data"><i class="fas fa-edit"></i></a>
                            <a href="{{ route('user.destroy',['uuid'=>$v->uuid]) }}" class="confirm btn btn-danger" title="Hapus Data" data-text="Yakin ingin menghapus data ini?"><i class="fas fa-trash-alt"></i></a>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="5" class="text-center">{{ isset(request()->cari)?'Data tidak ditemukan':'Data tidak tersedia' }}</td>
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
@if ($errors->any())
<script>
  @foreach ($errors->all() as $e)
  Toast.fire({
    icon: 'error',
    title: '{{ $e }}'
  })
  @endforeach
</script>
@endif
@if (session()->has('message'))
<script>
  Toast.fire({
    icon: 'success',
    title: '{{ session()->get('message') }}'
  })
</script>
@endif
@endsection
