@extends('templates.index')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
        
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body py-3">
            <div class="table-responsive">
                <table id="tabel-data" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col" style="text-align: center">Name</th>
                            <th scope="col" style="text-align: center">Satuan Kerja</th>
                            <th scope="col" style="text-align: center">Departemen</th>
                            <th scope="col" style="text-align: center" width="18%">Jabatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datas as $data)    
                            <tr>
                                <td class="align-top" style="text-align: center">{{ $data->name}}</td>
                                <td class="align-top" style="text-align: center">{{ $data->satuanKerja['inisial'] }}</td>
                                <td class="align-top" style="text-align: center">{{ $data->departemenTable['inisial'] }}</td>
                                <td class="align-top" style="text-align: center">{{ strtoupper($data->levelTable->jabatan) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection