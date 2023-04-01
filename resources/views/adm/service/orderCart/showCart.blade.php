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

        <div class="mb-3">
            <a href="{{route('order.confirm-cancel', $orderId)}}" class="btn btn-danger btn-sm">Cancelar</a>
            <a href="{{route('order.confirm', $orderId)}}" class="btn btn-success btn-sm ml-3">Finalizar</a>
        </div>

        <p class="card-description">Itens do pedido</p>

        <div class="col-sm-5 col-md-12 mb-3 mb-sm-0">
            <label><strong>Cliente:</strong></label>
            <h5>{{$dataOrder->client->name}}</h5>
            <label><strong>Email:</strong></label>
            <h5>{{$dataOrder->client->email}}</h5>
            <hr>
            <p class="card-description">Carrinho de itens...</p>
            <label><a href="{{route('order.searching-item', Crypt::encryptString($dataOrder->id))}}" class="btn btn-primary btn-sm"><i class="fas fa-cart-plus"></i></a>&nbsp;<strong>Total:&nbsp;{{0}}</strong></label>

            @if ($itemList->isEmpty())

            <h4 class="text-center mt-5">Não há itens no carrinho!</h4>

            @else

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th class="text-center">Quantidade</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($itemList as $item)

                                <tr>
                                    <td>
                                        {{$item->description}}
                                    </td>
                                    <td class="text-center" style="width: 150px;y">
                                        {{$item->quantity}}
                                    </td>
                                    <td class="text-center" style="width: 150px;y">
                                        <a href="{{route('order.remove-item', Crypt::encryptString($item->id))}}" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                @endforeach

                            </tbody>
                        </table>
                        
                        {{$itemList->links('adm.service.orderCart.partials.pagination')}}
                        
                    </div>
                </div>
            </div>

            @endif

        </div>

    </div>
</div>

@endsection