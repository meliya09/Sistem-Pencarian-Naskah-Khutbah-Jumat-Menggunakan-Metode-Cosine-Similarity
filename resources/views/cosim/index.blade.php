@extends('master')

@section('content')
    <div>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div style="color:red">{{$error}}</div>
            @endforeach
        @endif

        <div class="row">
            <div class="col-12">
                <form action="{{ route('consinesimilarities.check') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="query">Querys:</label>
                        <input type="text" class="form-control" name="query" placeholder="Enter query" id="query">
                    </div>
                    <button type="submit" class="btn-sm btn-primary">Submit</button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h2>Hasil Pencarian Dokumen</h2>
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Rangking</th>
                            <th>Dokumen</th>
                            <th>Judul Naskah</th>
                            <th>Persentase Kemiripan</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rangkings as $rangking)
                            @php
                                $cosim = $sumCrossVector[$rangking['document_code']] / ($sqrtCs['QUERY']*$sqrtCs[$rangking['document_code']])
                            @endphp
                            @if ($cosim != 0)                                
                                <tr>
                                    <td>
                                        {{ $rangking['rangking'] }}
                                    </td>
                                    <td>
                                        {{ $rangking['document_number'] }}
                                    </td>
                                    <td>
                                        {{ $rangking['document_title'] }}
                                    </td>
                                    <td>
                                        {{ number_format($cosim*100, 3,',','.') }}%
                                    </td>
                                    <td>
                                        <a href="{{ route('documents.show', $rangking['document_id']) }}" type="button" class="btn-sm btn-info">Detail</a>
                                    </td>
                                    {{-- <label for="">Cosim ( Q,{{ $index }}) = {{ $sumCrossVector[$index] }} / ({{ $sqrtCs['QUERY'] }}*{{ $value }}) = {{ $cosim }}</label> <br> --}}
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <a href="{{ route('list-documents') }}" target='_blank' class="btn btn-primary" style="margin: 10px; ">Dokumen</a>
                <a href="{{ route('step-1') }}" target='_blank' class="btn btn-primary" style="margin: 10px; ">Step - 1 : Tokenizing</a>
                <a href="{{ route('step-2') }}" target='_blank'  class="btn btn-primary" style="margin: 10px; ">Step - 2 : Filtering</a>
                <a href="{{ route('step-3') }}" target='_blank'  class="btn btn-primary" style="margin: 10px; ">Step - 3 : Stemming</a>
                <a href="{{ route('step-4') }}" target='_blank'  class="btn btn-primary" style="margin: 10px; ">Step - 4 : TF - iDF</a>
                <a href="{{ route('step-5') }}" target='_blank'  class="btn btn-primary" style="margin: 10px; ">Step - 5 : Perkalian Vektor</a>
                <a href="{{ route('step-6') }}" target='_blank'  class="btn btn-primary" style="margin: 10px; ">Step - 6 : Akar Bobot</a>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        } );
    </script>
@endsection