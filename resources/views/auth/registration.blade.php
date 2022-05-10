<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Nomor Surat | <?= $title; ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/bcasTab.jpeg" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.2.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <img src="assets/img/bcasLogo.jpeg" alt="" width="250">
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-2 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Penomoran Surat BCASyariah</h5>
                    <p class="text-center small">Silahkan Daftarkan Nama Pengguna dan Kata Sandi</p>
                  </div>

                  <form action="/registration" method="post" class="row g-3 needs-validation">
                    @csrf
                    <div class="col-12">
                      <label for="name" class="form-label">Nama Pengguna</label>
                      <div class="input-group has-validation">
                        <input type="text" name="name" class="form-control @error('login') is-invalid @enderror" value="{{ old('login') }}" id="name" autocomplete="off" required>

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                      </div>
                    </div>

                    <div class="col-12">
                        <label for="satuan_kerja" class="form-label">Satuan Kerja</label>
                        <select class="form-select" aria-label="Default select example" name="satuan_kerja" id="satuan_kerja">
                            <option selected>Pilih Satuan Kerja</option>
                            <option value="1">SKTI & Log</option>
                            <option value="2">PPO</option>
                            <option value="3">MRK</option>
                            <option value="4">OPR</option>
                            <option value="5">PTI</option>
                          </select>
                    </div>
                    
                    <div class="col-12">
                        <label for="departemen" class="form-label">Departemen</label>
                        <select class="form-select" aria-label="Default select example" name="departemen" id="departemen">
                            <option selected>Pilih Departemen</option>
                            <option value="1">Departemen 1</option>
                            <option value="2">Departemen 2</option>
                            <option value="3">Departemen 3</option>
                            <option value="4">Departemen 4</option>
                            <option value="5">Departemen 5</option>
                          </select>
                    </div>

                    <div class="col-12">
                      <label for="password" class="form-label">Kata Sandi</label>
                      <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" required>

                      @error('password')
                          <div class="invalid-feedback">
                              {{ $message }}
                          </div>
                      @enderror
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Masuk</button>
                    </div>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src=assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src=assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src=assets/vendor/chart.js/chart.min.js"></script>
  <script src=assets/vendor/echarts/echarts.min.js"></script>
  <script src=assets/vendor/quill/quill.min.js"></script>
  <script src=assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src=assets/vendor/tinymce/tinymce.min.js"></script>
  <script src=assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src=assets/js/main.js"></script>

</body>

</html>