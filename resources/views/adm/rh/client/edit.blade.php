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

        <form class="user" method="post" action="{{route('client.update')}}">
            @csrf
            <input type="hidden" name="id" value="{{$client->id}}">
            <div class="form-group row">
                <div class="col-sm-5 col-md-4 mb-3 mb-sm-0">
                    <input type="text" class="form-control" name="name" placeholder="{{$client->name}}">
                    @if ($errors->has('name'))
                    <h6 class="mt-1 text-danger">&nbsp;*&nbsp;{{$errors->first('name')}}</h6>
                    @endif
                </div>
                <div class="col-sm-5 col-md-4 mb-3 mb-sm-0">
                    <input type="email" class="form-control" name="email" placeholder="{{$client->email}}">
                    @if ($errors->has('email'))
                    <h6 class="mt-1 text-danger">&nbsp;*&nbsp;{{$errors->first('email')}}</h6>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="{{route('client.list-search')}}" class="btn btn-secondary">Voltar</a>
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