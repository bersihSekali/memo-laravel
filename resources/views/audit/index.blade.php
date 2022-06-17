@extends('templates.index')

@section('content')

  <div class="container">
    <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
    @foreach ($datas as $data)
        {{$data->deskripsi}}
    @endforeach

    <div class="card shadow mb-4">
      <div class="card-body py-3">
          <div class="table-responsive">
              <table id="tabel-data" class="table table-bordered" width="100%" cellspacing="0">
                  <thead>
                      <tr>
                          <th class="fs-4" scope="col" width="5%" style="text-align: center">Tanggal</th>
                          <th class="fs-4" scope="col" width="5%" style="text-align: center">User</th>
                          <th class="fs-4" scope="col" width="5%" style="text-align: center">Aktifitas</th>
                          <th class="fs-4" scope="col" width="10%" style="text-align: center">Surat</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach($datas as $data)
                      <tr id="data" data-bs-toggle="modal" data-bs-target="#log-{{$data['id']}}" style="cursor: pointer;">
                          <td class="align-top">{{ date("Y-m-d", strtotime($data->created_at)) }}</td>
                          <td class="align-top">{{ strtoupper($data->userID->name) }}</td>
                          <td class="align-top">{{ strtoupper($data->aktifitas) }} </td>
                          <td class="align-top">{{ strtoupper($data->suratTable->perihal) }}</td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>
          </div>
      </div>
    </div>
  </div> 
@endsection