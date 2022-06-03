$(document).ready(function () {
  var judul = document.querySelector('#judul').textContent;
  var tanggal = document.querySelector('#tanggal').textContent;
    $('#tabel-laporan').DataTable({
        dom: "Bfrtip",
      order: false,
      scrollX: true,
      lengthChange: false,
      buttons: [
        {
          extend: "excel",
          title: "COba dulu", 
          exportOptions: {
            columns: ":visible",
          },
        },
        {
          extend: "csv",
          exportOptions: {
            columns: ":visible",
          },
        },
        {
          extend: "pdf",
          title: judul,
          messageTop: "Tanggal: " + tanggal + "\n",
          exportOptions: {
            columns: ":visible",
          },
        },
        {
          extend: "print",
          exportOptions: {
            columns: ":visible",
          },
        },
        "colvis",
      ],
    })
    .buttons()
    .container()
    .appendTo("#example_wrapper .col-md-6:eq(0)");
});