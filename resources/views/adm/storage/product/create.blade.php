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
        
        <p class="card-description">Dados do novo produto</p>

        <form class="user" method="post" action="{{route('product.insert')}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
                <div class="col-sm-5 col-md-4 mb-3 mb-sm-0">
                    <input type="text" class="form-control" name="description" placeholder="Produto">
                    @if ($errors->has('description'))
                    <h6 class="mt-1 text-danger">&nbsp;*&nbsp;{{$errors->first('description')}}</h6>
                    @endif
                </div>
                <div class="col-sm-5 col-md-4 mb-3 mb-sm-0">
                    <select class="form-control" name="type_product_id">
                        <option value="">Escolha...</option>
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

            <button type="submit" class="btn btn-primary">Criar</button>
            <a href="{{route('product.list-search')}}" class="btn btn-secondary">Voltar</a>
        </form>

        <script>
            // Add the following code if you want the name of the file appear on select
            $(".customFile").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
        </script>

    </div>
</div>

@endsection