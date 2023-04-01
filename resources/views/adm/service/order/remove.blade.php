@extends('layout.adm-page')

@section('title')
{{$title}}
@endsection

@section('dashboard')
{{$dashboard}}
@endsection

@section('content')
<div class="col-sm-12">
    <div class="home-tab">

        <h4>Remover: {{$product->description}}?</h4>
        
        <a href="{{route('product.delete', $product->id)}}" class="btn btn-success" style="margin: 15px;">Confirmar</a>
        <a href="{{route('product.list')}}" class="btn btn-danger" style="margin: 15px;">Cancelar</a>

    </div>
</div>
@endsection