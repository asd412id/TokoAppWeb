@extends('layouts.master')
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-4 col-12">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ $trDay }}</h3>
            <p>Transaksi Hari Ini</p>
          </div>
          <div class="icon">
            <i class="fas fa-cash-register"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ $trWeek }}</h3>
            <p>Transaksi Pekan Ini</p>
          </div>
          <div class="icon">
            <i class="fas fa-cash-register"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>{{ $trMonth }}</h3>
            <p>Transaksi Bulan Ini</p>
          </div>
          <div class="icon">
            <i class="fas fa-cash-register"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4 col-12">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ $lbDay }}</h3>
            <p>Laba Hari Ini</p>
          </div>
          <div class="icon">
            <i class="fas fa-money-bill"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ $lbWeek }}</h3>
            <p>Laba Pekan Ini</p>
          </div>
          <div class="icon">
            <i class="fas fa-money-bill"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>{{ $lbMonth }}</h3>
            <p>Laba Bulan Ini</p>
          </div>
          <div class="icon">
            <i class="fas fa-money-bill"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4 col-12">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{ $users }}</h3>
            <p>Total User</p>
          </div>
          <div class="icon">
            <i class="fas fa-users"></i>
          </div>
          <a href="{{ route('user.index') }}" class="small-box-footer">
            Selengkapnya <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ $kategori }}</h3>
            <p>Total Kategori</p>
          </div>
          <div class="icon">
            <i class="fas fa-tags"></i>
          </div>
          <a href="{{ route('kategori.index') }}" class="small-box-footer">
            Selengkapnya <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ $barang }}</h3>
            <p>Total Barang</p>
          </div>
          <div class="icon">
            <i class="fas fa-boxes"></i>
          </div>
          <a href="{{ route('barang.index') }}" class="small-box-footer">
            Selengkapnya <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection
