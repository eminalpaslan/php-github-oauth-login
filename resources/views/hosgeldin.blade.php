<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hoşgeldin</title>
</head>
<body>
    <h1>Hoşgeldin</h1>

    @php($u = session('user') ?: [])
    <p>
        Kullanıcı:
        {{ $u['name'] ?? $u['email'] ?? $u['sub'] ?? 'Bilinmiyor' }}
    </p>

    <form method="POST" action="{{ route('logout') }}">
        {{ csrf_field() }}
        <button type="submit">Çıkış</button>
    </form>
</body>
</html>
