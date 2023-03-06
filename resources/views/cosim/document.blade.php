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
    </div>
@endsection