<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>勤怠打刻アプリケーション</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header__utilities">
                <h1 class="header__logo">Atte</h1>
                <nav>
                    <!--ログイン状態のチェック: ログイン状態なら下記を表示-->
                    @if (Auth::check())
                    <ul class="header__nav">
                        <li class="header__nav-item">
                            <a href="/" class="header__nav__button">ホーム</a>
                        </li>
                        <li class="header__nav-item">
                            <a href="/attendance" class="header__nav__button">日付一覧</a>
                        </li>
                        <li class="header__nav-item">
                            <form class="form" action="{{ route('logout') }}" method="post">
                                @csrf
                                <button class="header__nav__button">ログアウト</button>
                            </form>
                        </li>
                    </ul>
                    @endif
                </nav>
            </div>
        </div>
    </header>
    <main>
        @yield('content')
        <div class="footer-bottom">
            Atte,&nbsp;inc.
            <div class="footer-line"></div>
        </div>
    </main>
</body>

</html>