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
                        <th style="width: 150px;">Qtde. estoque</th>
                        <th style="width: 150px;" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($storageList as $storage)

                    <tr>
                        <td>
                            {{$storage->product->description}}
                        </td>
                        <td style="width: 150px;">
                            {{(!$storage->quantity) ? '0' : $storage->quantity}}
                        </td>
                        <td style="width: 100px;" class="text-center">
                            <a href="{{route('order.add-item', Crypt::encryptString($storage->id))}}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i>
                            </a>
                        </td>
                    </tr>

                    @endforeach

                </tbody>
            </table>
            
            {{$storageList->appends((isset($description) ? ['description' => $description] : null))->links('adm.service.orderCart.partials.pagination')}}
            
        </div>
    </div>
</div>

@endif