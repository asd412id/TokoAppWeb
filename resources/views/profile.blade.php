@extends('layouts.master')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6 col-sm-12">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Ubah Profil</h3>
          </div>
          <form role="form" method="post">
            @csrf
            <div class="card-body pb-0">
              <div class="form-group">
                <label for="name">Nama</label>
                <input required type="text" name="name" class="form-control" id="name" placeholder="Nama User" value="{{ old('name')??$data->name }}" autofocus>
              </div>
              <div class="form-group">
                <label for="username">Username</label>
                <input required type="text" class="form-control" id="username" placeholder="Username" value="{{ $data->username }}" disabled>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Kosongkan jika tidak ingin mengubah password">
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-info">Simpan</button>
            </div>
          </form>
        </div>
      </div>
      <div class="col-md-6 col-sm-12 text-left">
        @php
          $protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
        @endphp
        @foreach ($ipv4 as $key => $ip)
          <div class="text-left p-1">
            <p class="p-0 m-0">{!! $key.'<br>'.$protocol.'://'.$ip !!}</p>
            {!! QrCode::size(150)->generate($protocol.'://'.$ip.':'.getenv('NODE_PORT')) !!}
          </div>
        @endforeach
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
