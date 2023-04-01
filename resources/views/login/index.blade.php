<!DOCTYPE html>
<html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>{{$title}}</title>
        @include('layout.adm-style')
    </head>

    <body>
        <div class="container-scroller">
            <div class="container-fluid page-body-wrapper full-page-wrapper">
                <div class="content-wrapper d-flex align-items-center auth px-0">
                    <div class="row w-100 mx-0">
                        <div class="col-lg-4 mx-auto mt-5">
                            @include('layout.adm-messages')
                            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                                <div class="brand-logo">
                                    <img src="{{@asset('img/free_delivery_dark.png')}}" alt="logo" width="50px;" height="50px;">
                                </div>
                                <h4 class="mt-3">Área restrita!</h4>
                                <h6 class="fw-light">Por favor, digite suas credênciais.</h6>
                                <form class="pt-3" action="{{route('authenticate')}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-lg" name="email" placeholder="Email">
                                        @if ($errors->has('email'))
                                        <h6 class="mt-1 text-danger">&nbsp;*&nbsp;{{$errors->first('email')}}</h6>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-lg" name="password" placeholder="Senha">
                                        @if ($errors->has('password'))
                                        <h6 class="mt-1 text-danger">&nbsp;*&nbsp;{{$errors->first('password')}}</h6>
                                        @endif
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium">Entrar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        @include('layout.adm-script')
    </body>

</html>