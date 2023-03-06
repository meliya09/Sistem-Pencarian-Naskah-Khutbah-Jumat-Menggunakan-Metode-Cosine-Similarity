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
            <form action="{{ route('documents.store') }}" method="POST">
                @csrf
                @method('POST')
                <div class="form-group">
                    <label for="comment">Judul</label>
                    <input type="text" class="form-control" name="title"
                    value="{{ old('title') }}">
                </div>
                <div class="form-group">
                    <label for="comment">Isi Naskah:</label>
                    <textarea class="form-control" name="content" rows="5" id="content">
                        {{ old('content') }}
                    </textarea>
                </div>
                <button type="submit" class="btn-sm btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-12">
            <h2>Data Naskah</h2>
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
                            <td width="30%">
                                <a href="{{ route('documents.show', $document) }}" type="button" class="btn-sm btn-info">Detail</a>
                                <a href="{{ route('documents.edit', $document) }}" type="button" class="btn-sm btn-primary">Edit</a>
                                <form action="{{ route('documents.destroy', $document) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
  </div>
@endsection
