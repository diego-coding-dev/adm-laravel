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

        <div class="mb-3">
            <a href="{{route('employee.list-search')}}" class="btn btn-secondary btn-sm">Voltar</a>
        </div>

        <!--<div class="container bootstrap snippet">-->
        <div class="row">
            <!--                <div class="col-sm-3">left col
            
                                <div class="text-center">
                                    <img src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" class="avatar img-circle img-thumbnail" alt="avatar">
                                    <h6>Upload a different photo...</h6>
                                    <input type="file" class="text-center center-block file-upload">
                                </div>
                                </hr><br>
            
                                <ul class="list-group">
                                    <li class="list-group-item text-muted">Activity <i class="fa fa-dashboard fa-1x"></i></li>
                                    <li class="list-group-item text-right"><span class="pull-left"><strong>Shares</strong></span> 125</li>
                                    <li class="list-group-item text-right"><span class="pull-left"><strong>Likes</strong></span> 13</li>
                                    <li class="list-group-item text-right"><span class="pull-left"><strong>Posts</strong></span> 37</li>
                                    <li class="list-group-item text-right"><span class="pull-left"><strong>Followers</strong></span> 78</li>
                                </ul>
            
                            </div>/col-3-->
            <div class="col-sm-9">
                <div class="tab-content">
                    <div class="tab-pane active" id="home">
                        <div class="col-xs-6 mb-3">
                            <label>Nome</label>
                            <h5> <strong>{{$employee->name}}</strong> </h5>
                        </div>

                        <div class="col-xs-6 mb-3">
                            <label>Email</label>
                            <h5><strong>{{$employee->email}}</strong> </h5>
                        </div>

                        <div class="col-xs-6">
                            <label>Cadastrado em</label>
                            <h5><strong>{{$employee->created_at}}</strong> </h5>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="tab-content">
                    <div class="tab-pane active" id="home">
                        <div class="col-xs-6">
                            <label>Situação</label>
                            <h5><strong>{{$employee->is_active ? 'Ativado' : 'Não ativado'}}</strong> </h5>
                            @if ($employee->is_active)
                            <form action="{{route('employee.changeStatus', Crypt::encryptString($employee->id))}}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-button btn-danger btn-sm">Desativar</button>
                            </form>
                            @else
                            <form action="{{route('employee.changeStatus', Crypt::encryptString($employee->id))}}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-button btn-primary btn-sm">Ativar</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

            </div><!--/tab-pane-->
        </div><!--/tab-content-->

    </div><!--/col-9-->
</div>

<!--</div>-->

@endsection