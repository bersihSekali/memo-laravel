$(document).ready(function () {
    // $('#loading-indicator').fadeOut(1500)
    $('#tabel-data').DataTable({
        lengthMenu: [5, 10, 20],
        order: [[0, "desc"]],
        // "initComplete": function () {
        //      $('.page').fadeIn(3000)
        // }
    })
});