@extends("ikcms::layouts.admin")

@section("content")
    <div class="content-section">
        <div class="content-title">
            <h1>Modifier une slide : {{ $slider->name }}</h1>
            <div class="button-actions">
                <a href="{{ route("ikcms.admin.sliders.index") }}">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    Retour
                </a>
                <div class="clear"></div>
            </div>
        </div>
        @include("ikcms::sliders.form")
    </div>
@endsection