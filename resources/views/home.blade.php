@extends('master')

@section('content')
<div class="container">
    <br>
    <br>
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
                    <label for="query">Cari Naskah</label>
                    <input type="text" class="form-control" name="query" placeholder="Masukkan kalimat" id="query">
                </div>
                <button type="submit" class="btn-sm btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-12">
            <h2>Naskah Khutbah</h2>
            <table class="table table-hover">
                <thead>
                    <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as $document)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $document->title }}</td>
                            <td>
                                <a href="{{ route('documents.show', $document) }}" class="btn-sm btn-info">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
  </div>
@endsection
