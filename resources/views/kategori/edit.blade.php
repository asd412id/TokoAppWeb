@extends('layouts.master')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6 col-sm-12">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Kategori Barang: <strong>{{ $data->nama }} ({{ count($data->barang) }} Barang)</strong></h3>
          </div>
          <form role="form" method="post">
            @csrf
            <div class="card-body pb-0">
              <div class="form-group">
                <label for="nama">Nama Kategori</label>
                <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama Kategori Barang" value="{{ old('nama')??$data->nama }}">
              </div>
              <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" rows="5" class="form-control" id="keterangan">{{ old('keterangan')??$data->keterangan }}</textarea>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-info">Simpan</button>
              <a href="{{ route('kategori.index') }}" class="btn btn-default">Batal</a>
            </div>
          </form>
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
@endsection
