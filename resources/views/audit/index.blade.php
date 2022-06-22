@extends('templates.index')

@section('content')

  <div class="container">
    <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

    <div class="card shadow mb-4">
      <div class="card-body py-3">
          <div class="table-responsive">
              <table id="tabel-data" class="table table-bordered" width="100%" cellspacing="0">
                  <thead>
                      <tr>
                          <th class="fs-4" scope="col" style="text-align: center" width="10%">Tanggal</th>
                          <th class="fs-4" scope="col" style="text-align: center" width="8%">User</th>
                          <th class="fs-4" scope="col" style="text-align: center"width="8%">Aktifitas</th>
                          <th class="fs-4" scope="col" style="text-align: center">Surat</th>
                          <th class="fs-4" scope="col" style="text-align: center" width="15%">MAC Address</th>
                          <th class="fs-4" scope="col" style="text-align: center"width="10%">IP Address</th>
                          <th class="fs-4" scope="col" style="text-align: center"width="15%">User Agent</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach($datas as $data)
                      <tr id="data" data-bs-toggle="modal" data-bs-target="#log-{{$data['id']}}" style="cursor: pointer;">
                          <td class="align-top" style="text-align: center">{{ date("Y-m-d", strtotime($data->created_at)) }}</td>
                          <td class="align-top">{{ strtoupper($data->userID->name) }}</td>
                          <td class="align-top" style="text-align: center">{{ strtoupper($data->aktifitas) }} </td>
                          <td class="align-top">{{ strtoupper($data->suratTable['perihal']) }}</td>
                          <td class="align-top">{{ strtoupper($data->mac_address) }}</td>
                          <td class="align-top" style="text-align: center">{{ strtoupper($data->ip_address) }}</td>
                          <td class="align-top">{{ strtoupper($data->user_agent) }}</td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>
          </div>
      </div>
    </div>
  </div> 
@endsection