<li {!! (Route::current()->getName() == 'ikcms.admin.dashboard')?'class="is-active"':'' !!}>
    <a href="{{ route("ikcms.admin.dashboard") }}">
        <i class="fa fa-home" aria-hidden="true"></i>
        <span>{{ __('ikcms::admin.navigation_dashboard') }}</span>
    </a>
</li>
<li {!! (Route::current()->getName() == 'ikcms.admin.pagecategories.index' || Route::current()->getName() == 'ikcms.admin.pages.index')?'class="is-active"':'' !!}>
    <a href="#">
        <i class="fa fa-file-text-o" aria-hidden="true"></i>
        <span>{{ __('ikcms::admin.navigation_pages') }}</span>
    </a>
    <ul>
        <li>
            <a href="{{ route("ikcms.admin.pagecategories.index") }}">
                {{ __('ikcms::admin.navigation_sections') }}
            </a>
        </li>
        <li>
            <a href="{{ route("ikcms.admin.pages.index") }}">
                {{ __('ikcms::admin.navigation_contents') }}
            </a>
        </li>
    </ul>
</li>
<li {!! (Route::current()->getName() == 'ikcms.admin.sliders.index')?'class="is-active"':'' !!}>
    <a href="{{ route("ikcms.admin.sliders.index") }}">
        <i class="fa fa-file-image-o" aria-hidden="true"></i>
        <span>Slider</span>
    </a>
</li>
<li {!! (Route::current()->getName() == 'ikcms.admin.users.index')?'class="is-active"':'' !!}>
    <a href="{{ route("ikcms.admin.users.index") }}">
        <i class="fa fa-users" aria-hidden="true"></i>
        <span>{{ __('ikcms::admin.navigation_administrators') }}</span>
    </a>
</li>
<li {!! (Route::current()->getName() == 'ikcms.admin.logs.list')?'class="is-active"':'' !!}>
    <a href="#">
        <i class="fa fa-tasks" aria-hidden="true"></i>
        <span>{{ __('ikcms::admin.navigation_logs') }}</span>
    </a>
    <ul>
        <li><a href="{{ route('ikcms.admin.logs.list') }}">Logs</a></li>
        <li><a href="{{ route('ikcms.admin.requests.list') }}">Requêtes</a></li>
        <li><a href="{{ route('ikcms.admin.models.list') }}">Base de données</a></li>
    </ul>
</li>
<li {!! (Route::current()->getName() == 'ikcms.admin.settings.index')?'class="is-active"':'' !!}>
    <a href="{{ route("ikcms.admin.settings.index") }}">
        <i class="fa fa-cogs" aria-hidden="true"></i>
        <span>{{ __('ikcms::admin.navigation_parameters') }}</span>
    </a>
</li>