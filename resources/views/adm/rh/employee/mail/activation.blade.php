<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de email</title>
</head>
<body>
    <h4>Olá {{$employee['name']}}</h4>
    <p>{{$employee['email']}}</p>
    <a href="{{route('employee.active', $employee['token'])}}">Ativar conta</a>
</body>
</html>