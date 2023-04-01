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

        <form class="user" method="post" action="{{route('product.update')}}" enctype="multipart/form-data">
            <div class="form-group row">
                @csrf
                <input type="hidden" name="id" value="{{$product->id}}">
                <div class="col-sm-5 col-md-4 mb-3 mb-sm-0">
                    <input type="text" class="form-control" name="description" placeholder="{{$product->description}}">
                    @if ($errors->has('description'))
                    <h6 class="mt-1 text-danger">&nbsp;*&nbsp;{{$errors->first('description')}}</h6>
                    @endif
                </div>
                <div class="col-sm-5 col-md-4 mb-3 mb-sm-0">
                    <select class="form-control" name="type_product_id">
                        <option value="{{$product->typeProduct->id}}">{{$product->typeProduct->description}}</option>
                        @foreach ($typeProductList as $typeProduct)
                        <option value="{{$typeProduct->id}}" @if($typeProduct->id == old('type_product_id')) selected @endif >{{$typeProduct->description}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('type_product_id'))
                    <h6 class="mt-1 text-danger">&nbsp;*&nbsp;{{$errors->first('type_product_id')}}</h6>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 col-md-4 mb-3 mb-sm-0">
                    <div class="custom-file">
                        <input type="file" name="image" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                        @if ($errors->has('image'))
                        <h6 class="mt-1 text-danger">&nbsp;*&nbsp;{{$errors->first('image')}}</h6>
                        @endif
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="{{route('product.list-search')}}" class="btn btn-secondary">Cancelar</a>
        </form>

    </div>
</div>

@endsection