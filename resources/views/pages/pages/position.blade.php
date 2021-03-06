@extends("ikcms::layouts.admin")

@section("content")

    <div class="content-section">
        <div class="content-title">
            <h1>Gestion des contenus <small>Gestion des positions</small></h1>
            <div class="button-actions">
                <a href="{{ route("ikcms.admin.pages.index") }}">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    Retour
                </a>
                <div class="clear"></div>
            </div>
        </div>

        <div class="informations-container">
            <nav class="breadcrumb">
                <a href="">Administration</a>
                <a href="{{ route("ikcms.admin.pages.index") }}">Contenus</a>
                <span>Gestion des positions</span>
            </nav>

        </div>

        <div class="nested-section" data-url="{{ route('ikcms.admin.pages.positions.store') }}">
            <ol class="simple-sortable">
                @foreach($positions as $position)
                    <li id="menuItem_{{ $position->id }}">
                        <div>{{ $position->name }}</div>
                    </li>
                @endforeach
            </ol>
        </div>

    </div>
@endsection
