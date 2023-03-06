@extends('master')

@section('content')
    <div>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div style="color:red">{{$error}}</div>
            @endforeach
        @endif
        @php
            $totalColStep4 = 1;
        @endphp
        <div class="row">
            <div class="col-12">
                <h2>Step 5</h2>
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
@endsection