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

        <p class="card-description">Lista de categorias de produtos</p>

        <form class="user" method="get" action="{{route('type-product.list-search')}}">
            @csrf
            <div class="form-group row">
                <div class="col-sm-5 col-md-4 mb-3 mb-sm-0">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-2 small" name="description" value="{{ (isset($description) ? $description : '') }}" placeholder="Buscar categoria..." aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                    @if ($errors->has('name'))
                    <h6 class="mt-1 text-danger">&nbsp;*&nbsp;{{$errors->first('description')}}</h6>
                    @endif
                </div>
            </div>
        </form>

        @if ($productTypeList->isEmpty())

        <h4 class="text-center mt-5">Nenhum registro foi encontrado!</h4>

        @else

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Categoria</th>
                                <th style="width: 100px;">Ação</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($productTypeList as $productType)

                            <tr>
                                <td style="padding-top: 15px;">{{$productType->description}}</td>
                                <td style="width: 100px;">
                                    <a href="{{route('type-product.edit', Crypt::encryptString($productType->id))}}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{route('type-product.remove', Crypt::encryptString($productType->id))}}" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>

                    {{ $productTypeList->appends(isset($description) ? ['description' => $description] : null)->links('adm.storage.type-product.partials.pagination') }}

                </div>
            </div>
        </div>



        @endif

    </div>
</div>

@endsection