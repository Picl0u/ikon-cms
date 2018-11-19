@extends("ikcms::layouts.admin")

@section("content")
    <div class="content-section">
        <div class="content-title">
            <h1>Ajouter un administrateur</h1>
            <div class="button-actions">
                <a href="{{ route("ikcms.admin.users.index") }}">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    Retour
                </a>
                <div class="clear"></div>
            </div>
        </div>
        @include("ikcms::users.form")
    </div>
@endsection