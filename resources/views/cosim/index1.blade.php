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
        {{-- <div class="row">
            <div class="col-12">
                <h2>Data Documents</h2>
                <table class="table table-hover">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>PEMBANDING</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documentAndQuery as $key => $value)
                            <tr>
                                <td>
                                    @if ($loop->first)
                                        QUERY
                                    @else
                                        D{{ $key }}
                                    @endif
                                </td>
                                <td>{{ $value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h2>Step 1</h2>
                <label for="">Tokenizing</label>
                <table class="table table-hover">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>PEMBANDING</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allTokenizing as $key => $value)
                            <tr>
                                <td>
                                    {{ $key }}
                                </td>
                                <td>
                                    @foreach ($value as $k => $v)                                        
                                        <span class="badge badge-primary">{{ $v }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h2>Step 2</h2>
                <label for="">Filtering</label>
                <table class="table table-hover">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>PEMBANDING</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allFiltering as $key => $value)
                            <tr>
                                <td>
                                    {{ $key }}
                                </td>
                                <td>
                                    @foreach ($value as $k => $v)                                        
                                        <span class="badge badge-primary">{{ $v }}</span> 
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h2>Step 3</h2>
                <label for="">Stemming</label>
                <table class="table table-hover">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>PEMBANDING</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allStemming as $key => $value)
                            <tr>
                                <td>
                                    {{ $key }}
                                </td>
                                <td>
                                    @foreach ($value as $k => $v)                                        
                                        <span class="badge badge-primary">{{ $v }}</span> 
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h2>Step 4</h2>
                <label for="">Pembobotan TF-IDF</label>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>TERM</th>
                            @foreach ($allTokenizing as $key => $value)
                            <th>
                                TF {{ $key }}
                            </th>
                            @endforeach
                            <th>
                                df
                            </th>
                            <th>
                                D/df
                            </th>
                            <th>
                                Idf = log(D/df)
                            </th>
                            <th>
                                Idf+1
                            </th>
                            @foreach ($allTokenizing as $key => $value)
                            <th>
                                W {{ $key }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($terms as $key => $values)
                            <tr>
                                <td>
                                    {{ $key }}
                                </td>
                                @foreach ($values['tf'] as $tf)
                                <td>
                                    {{ $tf }}
                                </td>
                                @endforeach
                                <td>
                                    {{ $values['df'] }}
                                </td>
                                <td>
                                    {{ $values['D/df'] }}
                                </td>
                                <td>
                                    {{ $values['Idf'] }}
                                </td>
                                <td>
                                    {{ $values['Idf+1'] }}
                                </td>
                                @foreach ($values['W'] as $w)
                                <td>
                                    {{ $w }}
                                </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @php
            $totalColStep4 = 1;
        @endphp
        <div class="row">
            <div class="col-12">
                <h2>Step 4</h2>
                <label for="">Hasil Perkalian Vektor</label>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>TERM</th>
                            @foreach ($allTokenizing as $key => $value)
                            @php
                                $totalColStep4++
                            @endphp
                            <th>
                                    W : {{ $key }}
                            </th>
                            @endforeach
                            @foreach ($allTokenizing as $key => $value)
                            @if ($key != 'QUERY')                                
                                <th>
                                        Q*{{ $key }}
                                </th>
                            @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($terms as $key => $values)
                            <tr>
                                <td>
                                    {{ $key }}
                                </td>
                                @foreach ($values['W'] as $w)
                                <td>
                                    {{ $w }}
                                </td>
                                @endforeach
                                @foreach ($values['CV'] as $cv)
                                <td>
                                    {{ $cv }}
                                </td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="{{ $totalColStep4 }}">
                                TOTAL PERKALIAN VEKTOR
                            </td>
                            @foreach ($sumCrossVector as $scv)
                                <td>
                                    {{ $scv }}
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @php
            $totalColStep4 = 1;
        @endphp
        <div class="row">
            <div class="col-12">
                <h2>Step 5</h2>
                <label for="">Proses perhitungan akar bobot dokumen dan query</label>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>TERM</th>
                            @foreach ($allTokenizing as $key => $value)
                            @php
                                $totalColStep4++
                            @endphp
                            <th>
                                    W : {{ $key }}
                            </th>
                            @endforeach
                            @foreach ($allTokenizing as $key => $value)
                            <th>
                                    {{ $key }}^2
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($terms as $key => $values)
                            <tr>
                                <td>
                                    {{ $key }}
                                </td>
                                @foreach ($values['W'] as $w)
                                <td>
                                    {{ $w }}
                                </td>
                                @endforeach
                                @foreach ($values['CS'] as $cs)
                                <td>
                                    {{ $cs }}
                                </td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="{{ $totalColStep4 }}">
                                TOTAL PERKALIAN VEKTOR
                            </td>
                            @foreach ($sqrtCs as $acs)
                                <td>
                                    {{ $acs }}
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h2>Step 6</h2>
                <label for="">perhitungan cosine similarity</label> <br>
                @foreach ($sqrtCs as $index => $value)
                    @if ($index != 'QUERY')
                        @php
                            $cosim = $sumCrossVector[$index] / ($sqrtCs['QUERY']*$value)
                        @endphp
                        <label for="">Cosim ( Q,{{ $index }}) = {{ $sumCrossVector[$index] }} / ({{ $sqrtCs['QUERY'] }}*{{ $value }}) = {{ $cosim }}</label> <br>
                    @endif          
                @endforeach
            </div>
        </div> --}}
        {{-- <div class="row">
            <div class="col-12">
                <h2>Hasil Perhitungan Cosine Similarity</h2>
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>DOKUMEN</th>
                            <th>NILAI SIMILARITY</th>
                            <th>PRESENTASE</th>
                            <th>RANGKING</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sqrtCs as $index => $value)
                            <tr>
                                @if ($index != 'QUERY')
                                    <td>
                                        {{ $index }}
                                    </td>
                                    @php
                                        $cosim = $sumCrossVector[$index] / ($sqrtCs['QUERY']*$value)
                                    @endphp
                                    <td>
                                        {{ $cosim }}
                                    </td>
                                    <td>
                                        {{ $cosim*100 }}%
                                    </td>
                                    <td>
                                        {{ $rangking[$index] }}
                                    </td>
                                    <label for="">Cosim ( Q,{{ $index }}) = {{ $sumCrossVector[$index] }} / ({{ $sqrtCs['QUERY'] }}*{{ $value }}) = {{ $cosim }}</label> <br>
                                @endif     
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-12">
                <h2>Hasil Perhitungan Cosine Similarity</h2>
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>DOKUMEN</th>
                            <th>NILAI SIMILARITY</th>
                            <th>PRESENTASE</th>
                            <th>RANGKING</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rangking as $index => $value)
                            @php
                                $cosim = $sumCrossVector[$index] / ($sqrtCs['QUERY']*$sqrtCs[$index])
                            @endphp
                            @if ($cosim != 0)                                
                                <tr>
                                    <td>
                                        {{ $index }}
                                    </td>
                                    <td>
                                        {{ $cosim }}
                                    </td>
                                    <td>
                                        {{ $cosim*100 }}%
                                    </td>
                                    <td>
                                        {{ $rangking[$index] }}
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