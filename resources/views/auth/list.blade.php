@extends('templates.index')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
        
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Satuan Kerja</th>
                            <th scope="col">Departemen</th>
                            <th scope="col">Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datas as $data)    
                            <tr>
                                <td class="align-top">{{ $data->name}}</td>
                                <td class="align-top">{{ $data->satuanKerja['satuan_kerja'] }}</td>
                                <td class="align-top">{{ $data->departemenTable['departemen'] }}</td>
                                <td class="align-top">{{ $data->level }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection