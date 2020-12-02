<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>{{ $data->nama_transaksi }}</title>
    <style>
      .text-center{
        text-align: center !important;
      }
      .text-left{
        text-align: left !important;
      }
      .text-right{
        text-align: right !important;
      }
      .border-top{
        border-top: dashed 1px;
      }
      .border-bottom{
        border-bottom: dashed 1px;
      }
      .border-tb{
        border-top: dashed 1px;
        border-bottom: dashed 1px;
      }
      .fbold{
        font-weight: bold;
      }
      table{
        width: 100%;
        border-collapse: collapse;
      }
      table th, table td{
        vertical-align: top !important;
        padding: 3px;
      }
      .nowrap{
        white-space: nowrap;
      }
    </style>
    <script type="text/javascript">
    var i = 0;
    function ESCclose(evt) {
      if (evt.keyCode == 13 || evt.which == 13){
        i++
        if (i==1) {
          window.print();
        }else if (i>1) {
          window.close();
        }
      }
    }
    </script>
  </head>
  <body onkeypress="ESCclose(event)">
    <h1 class="text-center" style="margin: 0;padding: 0">{{ getenv('APP_NAME') }}</h1>
    <p class="text-center" style="margin-top: 10px;padding-top: 0">{{ getenv('ADDR') }}</p>
    <table>
      <tr>
        <td colspan="2" class="text-right">Struk: {{ \Carbon\Carbon::now()->toDateTimeString() }}</td>
      </tr>
      <tr>
        <td>Transaksi: {{ $data->kode_transaksi }}</td>
        <td class="text-right">Tgl: {{ $data->tgl_transaksi_local }}</td>
      </tr>
      <tr>
        <td>Oleh: {{ $data->user_name }}</td>
        <td class="text-right">{{ $data->nama_transaksi }}</td>
      </tr>
    </table>
    <table>
      <tr>
        <th class="border-tb">Barang</th>
        <th class="border-tb">Harga</th>
        <th class="border-tb">Jumlah</th>
        <th class="border-tb text-right">Total</th>
      </tr>
      @php
        $tb = 0;
      @endphp
      @foreach ($data->penjualan as $key => $v)
        @php
          $tb += $v->jumlah;
        @endphp
        <tr>
          <td>{{ $v->nama }}</td>
          <td class="text-right nowrap">Rp {{ number_format($v->harga_jual,0,'null','.') }}</td>
          <td class="text-center">{{ $v->jumlah }}</td>
          <td class="text-right nowrap">Rp {{ number_format($v->harga_jual*$v->jumlah,0,'null','.') }}</td>
        </tr>
      @endforeach
      <tr>
        <td colspan="4" height="10" class="border-top"></td>
      </tr>
      <tr>
        <td></td>
        <td colspan="2"  class="fbold">Total Pembelian</td>
        <td class="text-right fbold">{{ $tb }}</td>
      </tr>
      <tr>
        <td></td>
        <td colspan="2"  class="fbold">Subtotal</td>
        <td class="text-right fbold nowrap">Rp {{ number_format($data->pure_total_harga,0,'','.') }}</td>
      </tr>
      <tr>
        <td></td>
        <td colspan="2"  class="fbold">Diskon</td>
        <td class="text-right fbold nowrap">Rp {{ number_format($data->pure_total_harga*$data->diskon/100,0,'','.') }}</td>
      </tr>
      <tr>
        <td></td>
        <td colspan="2"  class="fbold border-tb">Total</td>
        <td class="border-tb text-right fbold nowrap">Rp {{ number_format($data->total_harga,0,'','.') }}</td>
      </tr>
      <tr>
        <td></td>
        <td colspan="2"  class="fbold border-tb">Tunai</td>
        <td class="border-tb text-right fbold nowrap">Rp {{ number_format($data->dibayar,0,'','.') }}</td>
      </tr>
      <tr>
        <td class="border-bottom"></td>
        <td colspan="2"  class="fbold border-tb">Kembalian</td>
        <td class="border-tb text-right fbold nowrap">Rp {{ number_format(($data->dibayar-$data->total_harga),0,'','.') }}</td>
      </tr>
      <tr>
        <td colspan="4" class="text-center" height="30" style="vertical-align: bottom">### Terima Kasih ###</td>
      </tr>
    </table>
  </body>
</html>
