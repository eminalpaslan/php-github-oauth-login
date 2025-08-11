<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hoşgeldin</title>
</head>
<body>
    <h1>Hoşgeldin, {{ auth()->user()->name }}</h1>
    <form action="{{ url('/logout') }}" method="POST">
        {{ csrf_field() }}
        <button type="submit">Çıkış Yap</button>
    </form>
</body>
</html>
