<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ana Sayfa</title>
</head>
<body>
    <h1>Ana Sayfa</h1>

    @if(session()->has('user'))
        <a href="/hosgeldin">Hoşgeldin sayfasına git</a>
    @else
        <a href="{{ route('login') }}">Giriş Yap</a>
    @endif
</body>
</html>
