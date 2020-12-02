<h4 class="t-center m-0">Laporan Rekapitulasi</h4>
<p class="t-center m-0">
  @if ($start->startOfDay()->timestamp < $end->startOfDay()->timestamp)
    {{ $start->locale('id')->translatedFormat('j F Y').' s.d. '.$end->locale('id')->translatedFormat('j F Y') }}
  @else
    {{ $end->locale('id')->translatedFormat('j F Y') }}
  @endif
</p>
<div class="mt-3">
  <table class="table">
    <thead>
      <tr>
        <th class="border-tb t-left">Kode Transaksi</th>
        <th class="border-tb t-left">Tanggal</th>
        <th class="border-tb t-right">Total Harga</th>
        <th class="border-tb t-right">Diskon</th>
        <th class="border-tb t-right">Total Jual</th>
        <th class="border-tb t-right">Total Modal</th>
        <th class="border-tb t-right">Laba Kotor</th>
      </tr>
    </thead>
    <tbody>
      @php
      $total_jual = 0;
      $total_modal = 0;
      $total_laba = 0;
      @endphp
      @foreach ($data as $key => $v)
        @php
          $total_jual += $v->total_harga;
          $total_modal += $v->total_modal;
          $total_laba += $v->laba;
        @endphp
        <tr>
          <td class="border-tb">{{ $v->kode_transaksi }}</td>
          <td class="border-tb">{{ $v->tgl_transaksi->format('d/m/Y') }}</td>
          <td class="border-tb t-right nowrap">Rp {{ number_format($v->pure_total_harga,0,'','.') }}</td>
          <td class="border-tb t-right nowrap">Rp {{ number_format($v->pure_total_harga*$v->diskon/100,0,'','.') }}</td>
          <td class="border-tb t-right nowrap">Rp {{ number_format($v->total_harga,0,'','.') }}</td>
          <td class="border-tb t-right nowrap">Rp {{ number_format($v->total_modal,0,'','.') }}</td>
          <td class="border-tb t-right nowrap">Rp {{ number_format($v->laba,0,'','.') }}</td>
        </tr>
      @endforeach
      <tr>
        <th colspan="4" rowspan="3"></th>
        <th rowspan="3" class="t-left">TOTAL KESELURUHAN</th>
        <th class="border-tb t-left">Total Jual:</th>
        <td class="border-tb t-right nowrap">Rp {{ number_format($total_jual,0,'','.') }}</td>
      </tr>
      <tr>
        <th class="border-tb t-left">Total Modal:</th>
        <td class="border-tb t-right nowrap">Rp {{ number_format($total_modal,0,'','.') }}</td>
      </tr>
      <tr>
        <th class="border-tb t-left">Total Laba Kotor:</th>
        <td class="border-tb t-right nowrap">Rp {{ number_format($total_laba,0,'','.') }}</td>
      </tr>
    </tbody>
  </table>
</div>
