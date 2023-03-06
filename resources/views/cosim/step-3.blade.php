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
                <h2>Step 3</h2>
                <label for="">Stemming</label>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>PEMBANDING</th>
                            <th>Jumlah Kata</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allStemming as $key => $value)
                            @php
                                $countWord = 0;
                            @endphp
                            <tr>
                                <td>
                                    {{ $key }}
                                </td>
                                <td>
                                    @foreach ($value as $k => $v)
                                        @php
                                            $countWord++;
                                        @endphp                                        
                                        <span class="badge badge-primary">{{ $v }}</span> 
                                    @endforeach
                                </td>
                                <td>
                                    {{ $countWord }}
                                </td>
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