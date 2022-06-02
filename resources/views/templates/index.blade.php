<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Pencatatan Memo</title>
  <!-- CSS files -->
  <link rel="icon" type="image/x-icon" href="{{url('assets/img/bcasTab.jpeg')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/fontawesome.min.css" integrity="sha512-P9vJUXK+LyvAzj8otTOKzdfF1F3UYVl13+F8Fof8/2QNb8Twd6Vb+VD52I7+87tex9UXxnzPgWA3rH96RExA7A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/brands.min.css" integrity="sha512-sVSECYdnRMezwuq5uAjKQJEcu2wybeAPjU4VJQ9pCRcCY4pIpIw4YMHIOQ0CypfwHRvdSPbH++dA3O4Hihm/LQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/solid.min.css" integrity="sha512-tk4nGrLxft4l30r9ETuejLU0a3d7LwMzj0eXjzc16JQj+5U1IeVoCuGLObRDc3+eQMUcEQY1RIDPGvuA7SNQ2w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/regular.min.css" integrity="sha512-d2x1oQUT6HACW9UlXxWI6XrIBDrEE5z2tit/+kWEdXdVYuift7sm+Q6ucfGWQr1F0+GD9/6eYoYDegw2nm05Vw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/svg-with-js.min.css" integrity="sha512-T+W8aCLi/CrpBhX5eX42Kw0/oGb/cxhQHYsQDIXx7UgK8c0A4CWw+TOQIqNJms1AZHI+6eKE4U1GubeLrguNww==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/v4-shims.min.css" integrity="sha512-fHavkBby/gcFEB2taaBfG0DLdHRGrnvkWQNXVZ5Yb/Fj6LkogecQUd6oyvBVsrWPaHSxs5tNza6LUW/Y6Az9lQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="{{url('assets/dist/css/tabler.min.css')}}" rel="stylesheet" />
  <link href="{{url('assets/dist/css/tabler-flags.min.css')}}" rel="stylesheet" />
  <link href="{{url('assets/dist/css/tabler-payments.min.css')}}" rel="stylesheet" />
  <link href="{{url('assets/dist/css/tabler-vendors.min.css')}}" rel="stylesheet" />
  <link href="{{url('assets/dist/css/demo.min.css')}}" rel="stylesheet" />
  <link href="{{url('assets/dist/css/row-table-surat-masuk.css')}}" rel="stylesheet" />

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" integrity="sha512-Tn2m0TIpgVyTzzvmxLNuqbSJH3JP8jm+Cy3hvHrW7ndTDcJ1w5mBiksqDBb8GpE2ksktFvDB/ykZ0mDpsZj20w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/brands.min.js" integrity="sha512-8Jp5PI7qKEn304XONokQQRgiu/1P9kTBlvpLc7zRukkTBYGKt6z4CkwJUJhCwwWYnjSdxJcGqW9ifT7ZxPNgbg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/solid.min.js" integrity="sha512-+KCv9G3MmyWnFnFrd2+/ccSx5ejo1yED85HZOvNDhtyHu2tuLL8df5BtaLXqsiF68wGLgxxMb4yL5oUyXjqSgw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/fontawesome.min.js" integrity="sha512-ywaT8M9b+VnJ+jNG14UgRaKg+gf8yVBisU2ce+YJrlWwZa9BaZAE5GK5Yd7CBcP6UXoAnziRQl40/u/qwVZi4Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/regular.min.js" integrity="sha512-KCmp/CWP6HlqzQKsFeYpN2NwNzVOLyYKscxXcJiocH28Wnyu0i63FKUv5DMRr0dTbyFbks+GvZMrme3lAFwBxQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/conflict-detection.min.js" integrity="sha512-mlikr4n3Ne5e6sshv2aY1evOMYJAOfUphySaCDYqM06Vfj6fGbJjTGJNGusERIEJmVgsuwowRCC3FqbsEq+QRg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <link rel="stylesheet" type="text/css" media="screen" href="{{url('assets/dataTables/jquery.dataTables.min.css')}}">
  <script src="{{url('assets/dataTables/jquery.min.js')}}"></script>
  <script src="{{url('assets/dataTables/jquery.dataTables.min.js')}}"></script>
  <link rel="stylesheet" type="text/css" href="{{url('assets/dataTables/datatables.min.css')}}" />
</head>

<body>
  <div class="page">
    @include('templates.header')

    <div class="page-wrapper">
      <div class="page-body">
        <div class="container-xl">
          @yield('content')
        </div>
      </div>
    </div>
  </div>
  <!-- Libs JS -->
  <!-- Tabler Core -->
  <script src="{{url('assets/dist/js/tabler.min.js')}}" defer></script>
  <script src="{{url('assets/dist/js/demo.min.js')}}" defer></script>
  <script type="text/javascript" src="{{url('assets/dataTables/datatables.min.js')}}"></script>
  <script src="{{url('assets/dataTables/table.js')}}"></script>
  <script src="{{url('assets/dataTables/tableLaporan.js')}}"></script>

  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>

</html>