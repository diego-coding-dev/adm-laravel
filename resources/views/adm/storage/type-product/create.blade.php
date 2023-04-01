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
        
        <p class="card-description">Dados da nova categoria</p>
        
        <form class="user" method="post" action="{{route('type-product.insert')}}">
            <div class="form-group row">
                @csrf
                <div class="col-sm-5 col-md-4 mb-3 mb-sm-0">
                    <input type="text" class="form-control" name="description" placeholder="Categoria de produto">
                    @if ($errors->has('description'))
                    <h6 class="mt-1 text-danger">&nbsp;*&nbsp;{{$errors->first('description')}}</h6>
                    @endif
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Criar</button>
            <a href="{{route('type-product.list-search')}}" class="btn btn-secondary">Voltar</a>
        </form>

    </div>
</div>

@endsection
