<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Laporan Rekapitulasi
      @if ($start->startOfDay()->timestamp < $end->startOfDay()->timestamp)
        {{ $start->locale('id')->translatedFormat('j F Y').' s.d. '.$end->locale('id')->translatedFormat('j F Y') }}
      @else
        {{ $end->locale('id')->translatedFormat('j F Y') }}
      @endif
    </title>
    <style>
      .t-center{
        text-align: center !important;
      }
      .t-left{
        text-align: left !important;
      }
      .t-right{
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
        padding: 10px 5px;
      }
      .nowrap{
        white-space: nowrap;
      }
      .mt-3{
        margin-top: 15px;
      }
      .m-0{
        margin: 0;
      }
      h3{
        font-size: 1.5em;
      }
      h4{
        font-size: 1.2em;
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
    <h3 class="t-center m-0">{{ getenv('APP_NAME') }}</h3>
    <p class="t-center m-0" style="margin-bottom: 15px;">{{ getenv('ADDR') }}</p>
    @include('rekapitulasi.table')
  </body>
</html>
