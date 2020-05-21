<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <!-- タイトル -->
    <title>@yield('title')</title>
    <!-- Styles -->
    @yield('css')
</head>
<body>
    <header>
        <h1>フォトギャラリー</h1>
    </header>

    @yield('content')

    <footer>
        &copy; @php echo date("Y") @endphp hibinoy
    </footer>

    <!-- JavaScript -->
    @yield('js')
</body>
</html>