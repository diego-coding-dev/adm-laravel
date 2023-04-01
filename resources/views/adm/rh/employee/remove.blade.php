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

        <h4>Remover: {{$employee->name}}?</h4>

        <form action="{{route('employee.delete', Crypt::encryptString($employee->id))}}" method="post">
            @csrf
            <button type="submit" class="btn btn-success" style="margin: 15px;">Confirmar</button>
            <a href="{{route('employee.list')}}" class="btn btn-danger" style="margin: 15px;">Cancelar</a>
        </form>

    </div>
</div>
@endsection