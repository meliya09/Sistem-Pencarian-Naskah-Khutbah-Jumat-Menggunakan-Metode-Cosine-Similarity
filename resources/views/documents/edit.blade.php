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
            <form action="{{ route('documents.update', $document) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="comment">Title</label>
                    <input type="text" class="form-control" name="title"
                    value="{{ old('title') ?? $document->title }}">
                </div>
                <div class="form-group">
                    <label for="comment">Document:</label>
                    <textarea class="form-control" name="content" rows="5" id="content">
                        {{ old('content') ?? $document->content }}
                    </textarea>
                </div>
                <button type="submit" class="btn-sm btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <br>
  </div>
@endsection
