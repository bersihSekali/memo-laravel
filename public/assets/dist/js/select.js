jQuery(document).ready(function () {
    $('.forward-form').select2({
        theme: "bootstrap-5",
    });
    $('.form-select').select2({
        theme: "bootstrap-5",
    });

    $('#user_id').select2({
        dropdownParent: $('#modalAktivitas')
    });
});