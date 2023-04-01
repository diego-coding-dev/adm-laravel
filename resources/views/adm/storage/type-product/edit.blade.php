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
        
        <p class="card-description">Editando dados</p>

        <form class="user" method="post" action="{{route('type-product.update')}}">
            <div class="form-group row">
                @csrf
                <input type="hidden" name="id" value="{{$category->id}}">
                <div class="col-sm-5 col-md-4 mb-3 mb-sm-0">
                    <input type="text" class="form-control" name="description" placeholder="{{$category->description}}">
                    @if ($errors->has('description'))
                    <h6 class="mt-1 text-danger">&nbsp;*&nbsp;{{$errors->first('description')}}</h6>
                    @endif
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="{{route('type-product.list-search')}}" class="btn btn-secondary">Cancelar</a>
        </form>

    </div>
</div>

@endsection