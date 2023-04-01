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

        <p class="card-description">Lista de funcionários</p>

        <form class="user" method="get" action="{{route('employee.list-search')}}">
            @csrf
            <div class="form-group row">
                <div class="col-sm-5 col-md-4 mb-3 mb-sm-0">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-2 small" name="name" value="{{ (isset($name) ? $name : '') }}" placeholder="Buscar funcionário..." aria-describedby="basic-addon2">
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


        @if ($employeeList->isEmpty())

        <h4 class="text-center mt-5">Nenhum registro foi encontrado!</h4>

        @else

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th style="width: 150px;">Status</th>
                                <th style="width: 100px;">Ação</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($employeeList as $employee)

                            <tr>
                                <td style="padding-top: 15px;">{{$employee->name}}</td>
                                <td style="padding-top: 15px;">{{$employee->email}}</td>
                                <td style="padding-top: 15px;">{{$employee->is_active ? 'Ativado' : 'Não ativado'}}</td>
                                <td style="width: 150px;" style="width: 100px;">
                                    <a href="{{route('employee.edit', Crypt::encryptString($employee->id))}}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{route('employee.remove', Crypt::encryptString($employee->id))}}" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>

                    {{ $employeeList->appends(isset($name) ? ['name' => $name] : null)->links('adm.rh.employee.partials.pagination') }}

                </div>
            </div>
        </div>



        @endif

    </div>
</div>

@endsection