const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 5000
});

$(".currency").autoNumeric({
  aSign: 'Rp ',
  aSep: '.',
  aDec: ',',
  mDec: 0
});
if ($(".datepicker").length > 0) {
  setTimeout(()=>{
    $('.datepicker').daterangepicker({
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Simpan",
        "cancelLabel": "Batal",
        "fromLabel": "Dari",
        "toLabel": "Sampai",
        "customRangeLabel": "Kustom",
        "daysOfWeek": [
          "Ahd",
          "Sen",
          "Sel",
          "Rab",
          "Kam",
          "Jum",
          "Sab"
        ],
        "monthNames": [
          "Januari",
          "Februari",
          "Maret",
          "April",
          "Mei",
          "Juni",
          "Juli",
          "Agustus",
          "September",
          "Oktober",
          "Nopember",
          "Desember"
        ],
        "firstDay": 0
      }
    })
  },100)
}
function initDefaultScript() {
  $(".confirm").click(function(){
    var _text = $(this).data('text');
    if (!confirm(_text)) {
      return false;
    }
  })
}
initDefaultScript();
