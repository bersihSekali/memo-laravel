$(document).ready(function () {
    $('#tabel-data').DataTable({
        lengthMenu: [5, 10, 20],
        order: [[0, "desc"]],
    })
    $('#tabel-data2').DataTable({
        lengthMenu: [5, 10, 20],
        order: [[0, "desc"]],
    })
    $('#tabel-list-user').DataTable({
        lengthMenu: [50, 100, 200],
        order: [[0, "asc"]],
    })
    $('#tabel-list').DataTable({
        lengthMenu: [50, 100, 200],
        ordering: false,
    })
    $('#tabel-list-departemen').DataTable({
        lengthMenu: [50, 100, 200],
        ordering: false,
    })
});