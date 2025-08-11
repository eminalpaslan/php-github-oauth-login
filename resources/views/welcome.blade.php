<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <title>Giriş</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    html,body{height:100%;margin:0}
    body{display:flex;align-items:center;justify-content:center;background:#f7f7f8;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial}
    .card{background:#fff;padding:28px 32px;border-radius:14px;box-shadow:0 6px 30px rgba(0,0,0,.08);text-align:center;min-width:280px}
    h1{margin:0 0 14px 0;font-weight:600;font-size:22px}
    p{margin:0 0 18px 0;color:#555}
    a.button{display:inline-block;padding:12px 16px;border-radius:10px;border:1px solid #ddd;text-decoration:none}
  </style>
</head>
<body>
  <div class="card">
    <h1>Hoş geldin</h1>
    <p>GitHub ile giriş yaparak devam et.</p>
    <a class="button" href="{{ url('/auth/github/redirect') }}">GitHub ile Giriş Yap</a>
  </div>
</body>
</html>