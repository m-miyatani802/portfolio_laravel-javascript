<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
    <link rel="stylesheet" href="{{ '/css/7-2-2.css' }}"><!-- Styles-->
    @yield('style')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-dark bg-success">
            <div class="d-flex flex-column">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="offcanvas offcanvas-start text-bg-dark bg-success d-inline-block" tabindex="-1"
                        id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                        <div class="offcanvas-header">
                            <a href="{{ route('top') }}" class="offcanvas-title"
                                id="offcanvasDarkNavbarLabel">トップページ</a>
                            <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas"
                                aria-label="閉じる"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3 dropdown-menu-dark bg-success"">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('words.index') }}">My page</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('words.create') }}">単語登録</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link active dropdown-toggle" href="#"
                                        id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        favorite
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                                        <li><a class="dropdown-item nav-link active bg-white text-dark"
                                                href="{{ route('favorite.users_index') }}">Users</a>
                                        </li>
                                        <li><a class="dropdown-item nav-link active bg-white text-dark"
                                                href="{{ route('favorite.words_index') }}">Words</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link active dropdown-toggle" href="#"
                                        id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        Mylist
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                                        <li><a class="dropdown-item nav-link active bg-white text-dark"
                                                href="{{ Route('mylist.listIndex') }}">マイリスト一覧</a>
                                        </li>
                                        <li><a class="dropdown-item nav-link active bg-white text-dark"
                                                href="{{ Route('mylist.create') }}">マイリスト作成</a>
                                        </li>
                                    </ul>
                                </li>
                                @guest
                                    @if (Route::has('login'))
                                        <li class="nav-item">
                                            <a class="nav-link active" href="{{ route('login') }}">{{ __('Login') }}</a>
                                        </li>
                                    @endif
                                    @if (Route::has('register'))
                                        <li class="nav-item">
                                            <a class="nav-link active"
                                                href="{{ route('register') }}">{{ __('Register') }}</a>
                                        </li>
                                    @endif
                                @else
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link active dropdown-toggle" href="#"
                                            role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false" v-pre>
                                            {{ Auth::user()->name }}
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item nav-link active bg-white text-dark"
                                                href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    </li>
                                @endguest
                            </ul>
                        </div>

                    </div>
                    <div class="title p-2 d-inline-block justify-content-sm-center">
                        <a class="navbar-brand" href="{{ route('top') }}">単語帳</a>
                    </div>
                </div>
            </div>
            <div class="open-btn inline-block"></div>
            <div id="search-wrap">
                <div class="close-btn"><span></span><span></span></div>
                <div class="search-area">
                    <form role="search" method="get" action="{{ Route('search.action') }}">
                        <div class="form-check form-check-inline" id="s1">
                            <input class="form-check-input" type="checkbox" name="users_search" id="users"
                                value="1">
                            <label class="form-check-label" for="users" id="users_s">ユーザー名</label>
                        </div>
                        <div class="form-check form-check-inline" id="s2">
                            <input class="form-check-input" type="checkbox" name="words_search" id="words"
                                value="1" checked>
                            <label class="form-check-label" for="words" id="words_s">単語</label>
                        </div>
                        <!-- </fieldset>
                            <fieldset>
                                <legend>選択肢</legend> -->
                        <div class="form-check" id="s3">
                            <input class="form-check-input" type="checkbox" name="reading" id="search_reading"
                                value="1">
                            <label class="form-check-label" for="search_reading" id="reading_s">
                                読み
                            </label>
                        </div>
                        <div class="form-check" id="s4">
                            <input class="form-check-input" type="checkbox" name="phrases" id="search_phrases"
                                value="1" checked>
                            <label class="form-check-label" for="search_phrases" id="phrases_s">
                                語句
                            </label>
                        </div>
                        <div class="form-check" s5>
                            <input class="form-check-input" type="checkbox" name="meaning" id="search_meaning"
                                value="1">
                            <label class="form-check-label" for="search_meaning" id="meaning_s">
                                意味
                            </label>
                        </div>
                        <!-- </fieldset> -->
                        <input type="text" value="" name="search_word" id="search-text"
                            placeholder="search" aria-label="Search" class="form-control me-2">
                        <input type="submit" id="searchsubmit" value="">
                    </form>
                </div>
            </div>
        </nav>
        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>
    </div>
    @yield('script')
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <!--自作のJS-->
    <script src="{{ asset('js/7-2-2.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>

</html>
