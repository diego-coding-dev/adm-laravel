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

        <p class="card-description">Buscando o cliente</p>

        <form class="user" method="get" action="{{route('order.searching-client')}}">
            @csrf
            <div class="form-group row">
                <div class="col-sm-5 col-md-4 mb-3 mb-sm-0">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-2 small" name="name" value="{{ (isset($name) ? $name : '') }}" placeholder="Buscar cliente..." aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                    @if ($errors->has('name'))
                    <h6 class="mt-1 text-danger">&nbsp;*&nbsp;{{$errors->first('name')}}</h6>
                    @endif
                </div>
            </div>
        </form>

        @if ($clientList->isEmpty())

        <h4 class="text-center mt-5">Nenhum registro foi encontrado!</h4>

        @else

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($clientList as $client)

                            <tr>
                                <td>
                                    <a href="{{route('order.register', Crypt::encryptString($client->id))}}" style="text-decoration: none;">{{$client->name}}</a>
                                </td>
                                <td>
                                    {{$client->email}}
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                    
                    {{$clientList->appends((isset($name) ? ['name' => $name] : null))->links('adm.service.order.partials.pagination')}}
                    
                </div>
            </div>
        </div>

        @endif

    </div>
</div>

@endsection