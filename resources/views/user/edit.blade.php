@extends('layouts.master')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6 col-sm-12">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Ubah User: {{ $data->name.' ('.$data->username.')' }}</h3>
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
                <input required type="text" name="username" class="form-control" id="username" placeholder="Username" value="{{ old('username')??$data->username }}">
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Kosongkan jika tidak ingin mengubah password">
              </div>
              <div class="form-group">
                <label for="role">Role</label>
                <select required class="form-control" name="role">
                  @php
                  $roles = ['cashier','stoker','admin'];
                  $rd = old('role')??$data->role;
                  @endphp
                  @foreach ($roles as $key => $r)
                    <option {{ $r == $rd?'selected':'' }} value="{{ $r }}">{{ ucwords($r) }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-info">Simpan</button>
              <a href="{{ route('user.index') }}" class="btn btn-default">Batal</a>
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
