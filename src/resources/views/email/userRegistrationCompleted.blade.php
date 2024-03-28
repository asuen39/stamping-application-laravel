<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ようこそ！</title>
</head>

<body>
    <h1>ようこそ、{{ $user->name }}さん！</h1>
    <p>新規登録ありがとうございます。アカウントが作成されました。</p>
    <p>以下のリンクをクリックしてログインしてください。</p>
    <a href="http://localhost/login">ログインする</a>
    <p>このメールに心当たりがない場合は、このメッセージを無視してください。</p>
</body>

</html>