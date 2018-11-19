{!! IkForm::model($pageCategory, ['files' => true]) !!}
<section id="information">
    {!! ikForm::checkbox("published", "Afficher la section ?", $pageCategory->published, [
        'default' => 1,
        "desc" => "Permet d'afficher ou non la section sur votre site."
    ]) !!}

    @if(count(config("ikcms.languages")) > 1)
        <nav class="tabs full-tabs" data-kube="tabs">
            @foreach(config("ikcms.languages") as $lang_key => $lang)
                <a href="#content_{{ $lang_key }}">
                    <img src="/images/flags/{{ strtoupper($lang_key) }}.png" alt="">
                    {{ $lang }}
                </a>
            @endforeach
        </nav>
    @endif
    @foreach(config("ikcms.languages") as $lang_key => $lang)
        <section id="content_{{ $lang_key }}">
            {!! IkForm::text("name[".$lang_key."]", "Titre", $pageCategory->translate("name", $lang_key)) !!}
        </section>
    @endforeach
</section>

{!! IkForm::submit() !!}

{!! IkForm::close() !!}
