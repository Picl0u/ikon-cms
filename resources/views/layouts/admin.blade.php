<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">
    {!! SEO::generate() !!}
    <!-- Styles -->
    @yield('style')
    <link href="/css/admin.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <header>
        <div class="is-navbar-container">
            <div class="is-brand">
                {{ Setting::get('website.name') }}
            </div>
            <div class="is-navbar">
                <nav class="is-push-right">
                    <ul>
                        <li>
                            <a href="/" target="_blank" class="website-link">
                                {{ __('ikcms::admin.navigation_see_website') }}
                            </a>
                        </li>
                        <li>
                            <a href="#" data-kube="dropdown" data-target="#header-dropdown">
                                {{ auth()->user()->name }}
                                <span class="caret is-down"></span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <div id="header-dropdown" class="dropdown is-hidden">
        <a href="{{ route("ikcms.admin.settings.index") }}" class="is-separator">
            Paramètres
        </a>
        <a href="{{ route('logout') }}" class="logout-link">
            Se déconnecter
        </a>
        <form class="logout-form is-hidden" action="{{ route('logout') }}" method="post">
            {{ csrf_field() }}
        </form>
    </div>
    <div id="content">
        <nav class="admin-navbar">
            <ul>
                @include("ikcms::components.navigation")
            </ul>
        </nav>
        <div class="content-container">
            @if(count($errors) > 0)
                @include("ikcms::components.alert-error",['errors' => $errors])
            @endif
            @yield('content')
        </div>
    </div>

    <div class="remodal" data-remodal-id="remodal" data-remodal-options="hashTracking:false">
        <span data-remodal-action="close" class="remodal-close"></span>
        <div class="forImg"></div>
    </div>

    <!-- JavaScript -->
    <script type="text/javascript" src="{{asset('js/admin.js')}}"></script>
    @stack('scripts')
    @if(Session::has('success'))
        <script type="text/javascript">
            toastr.success('{{ Session::get('success') }}');
        </script>
    @endif
    @if(Session::has('error'))
        <script type="text/javascript">
            toastr.error('{{ Session::get('error') }}');
        </script>
    @endif
</body>
</html>