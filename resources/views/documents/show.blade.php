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
            <h3>Detail Naskah</h3>
            <strong>
                {{ $document->title }}
            </strong>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <h3>Isi Naskah</h3>
            {{ $document->content }}
        </div>
    </div>
    <br>
  </div>
@endsection
