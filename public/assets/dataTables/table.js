$(document).ready(function () {
    $('#tabel-data').DataTable({
        lengthMenu: [5, 10, 20],
        order: [[0, "desc"]],
    })
});