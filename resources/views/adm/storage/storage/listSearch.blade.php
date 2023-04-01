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
        
        <p class="card-description">Produtos no estoque</p>

        <form class="user" method="get" action="{{route('storage.list-search')}}">
            @csrf
            <div class="form-group row">
                <div class="col-sm-5 col-md-4 mb-3 mb-sm-0">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-2 small" name="description" value="{{ (isset($description) ? $description : '') }}" placeholder="Buscar cliente..." aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                    @if ($errors->has('description'))
                    <h6 class="mt-1 text-danger">&nbsp;*&nbsp;{{$errors->first('description')}}</h6>
                    @endif
                </div>
            </div>
        </form>

        @if ($storageList->isEmpty())

        <h4 class="text-center mt-5">Nenhum registro foi encontrado!</h4>

        @else

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th class="text-center">Quantidade</th>
                                <th class="text-center" style="width: 100px;">Ação</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($storageList as $storage)

                            <tr>
                                <td style="padding-top: 15px;">
                                    <img src="{{url('storage/' . $storage->image)}}" alt="" width="50px;" height="50px;">
                                    &nbsp;{{$storage->description}}
                                </td>
                                <td class="text-center" style="width: 100px;">{{(!$storage->quantity) ? '0' : $storage->quantity}}</td>
                                <td class="text-center" style="width: 100px;">
                                    <a href="{{route('storage.adding', Crypt::encryptString($storage->id))}}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                    <a href="{{route('storage.remove', Crypt::encryptString($storage->id))}}" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                    
                    {{ $storageList->appends(isset($description) ? ['description' => $description] : null)->links('adm.storage.storage.partials.pagination') }}
                    
                </div>
            </div>
        </div>



        @endif

    </div>
</div>

@endsection