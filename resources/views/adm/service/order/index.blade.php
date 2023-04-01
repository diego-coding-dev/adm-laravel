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
        
        <p class="card-description">Lista de pedidos</p>

        @if ($orderList->isEmpty())

        <h4 class="text-center mt-5">Nenhum registro foi encontrado!</h4>

        @else

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:150px;">Pedido</th>
                                <th>Cliente</th>
                                <th style="width:150px;">Data</th>
                                <!-- <th class="text-center" style="width: 100px;">Ação</th> -->
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($orderList as $order)

                            <tr>
                                <td style="width:150px;">
                                    {{$order->register}}
                                </td>
                                <td>
                                    {{$order->client->name}}
                                </td>
                                <td style="width:150px;">
                                    {{$order->created_at}}
                                </td>
                                <!-- <td class="text-center" style="width: 100px;">
                                    <a href="#" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td> -->
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                    
                    {{$orderList->links('adm.service.order.partials.pagination')}}
                    
                </div>
            </div>
        </div>

        @endif

    </div>
</div>

@endsection