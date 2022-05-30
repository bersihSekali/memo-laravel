$(document).ready(function () {
    $('#tabel-laporan').DataTable({
        dom: "Bfrtip",
      order: false,
      scrollX: true,
      lengthChange: false,
      buttons: [
        {
          extend: "excel",
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