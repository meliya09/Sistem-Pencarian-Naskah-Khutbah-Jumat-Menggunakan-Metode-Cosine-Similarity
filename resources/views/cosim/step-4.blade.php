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