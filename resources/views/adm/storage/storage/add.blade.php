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

        <p class="card-description">Adicionando a quantidade</p>

        <form class="user" method="post" action="{{route('storage.add')}}">
            @csrf
            <input type="hidden" name="id" value="{{Crypt::encryptString($storage->id)}}">
            <div class="col-sm-5 col-md-2 mb-3 mb-sm-0">
                <div class="input-group row mb-3">
                    <div class="input-group-prepend" id="button-addon3">
                        <button onclick="decrease()" class="btn btn-danger" type="button">-</button>
                        <button onclick="increase()" class="btn btn-success" type="button">+</button>
                    </div>
                    <input type="text" name="quantity" id="quantity" class="form-control" onkeypress="isNumber(event)" placeholder="{{(!$storage->quantity) ? '0' : $storage->quantity}}" value="{{(!$storage->quantity) ? '0' : $storage->quantity}}">
                    @if ($errors->has('quantity'))
                    <h6 class="mt-1 text-danger">&nbsp;*&nbsp;{{$errors->first('quantity')}}</h6>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="{{route('storage.list-search')}}" class="btn btn-secondary">Voltar</a>
        </form>
        <script>
            let qte = document.getElementById('quantity').value;

            function increase() {
                qte++;
                document.getElementById('quantity').value = qte;
            }

            function decrease() {
                if (qte > 0) {
                    qte--;
                }

                if (qte < 0) {
                    qte = 0;
                }

                document.getElementById('quantity').value = qte;
            }

            function isNumber(e) {
                let value = String.fromCharCode(e.which);

                if (!(/[0-9]/.test(value))) {
                    e.preventDefault();
                }
            }
        </script>

    </div>
</div>

@endsection