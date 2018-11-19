{!! IkForm::model($slider, ['files' => true]) !!}
<div class="is-row">
    <div class="is-col is-20">
        <nav class="vertical-tab" data-kube="tabs">
            <a href="#information">Informations</a>
            <a href="#contenus">Contenus</a>
            <a href="#medias">Médias</a>
        </nav>
    </div>
    <div class="is-col is-80">
        <section id="information">
            <div class="content-title">
                <h1>Informations</h1>
            </div>
            {!! ikForm::checkbox("published", "Publier la slide?", $slider->published, [
                'default' => 1,
                "desc" => "Permet d'afficher ou non la slide sur votre site."
            ]) !!}
            {!! IkForm::text("link", "Lien", $slider->link,[
                "desc" => "Lien vers une page ou un site"
            ]) !!}
            {!! IkForm::select("position", "Position", $slider->position, [
                "left" => "Gauche",
                "center" => "Centrer",
                "right" => "Droite"
            ],[
                "desc" => "Position de la description dans la slide."
            ]) !!}
        </section>

        <section id="contenus">
            <div class="content-title">
                <h1>Contenus</h1>
            </div>
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
                    {!! IkForm::text("name[".$lang_key."]", "Titre", $slider->translate("name", $lang_key)) !!}
                    {!! IkForm::editor("description[".$lang_key."]", "Contenu", $slider->translate("description", $lang_key)) !!}
                </section>
            @endforeach
        </section>

        <section id="medias">
            <div class="content-title">
                <h1>Médias</h1>
            </div>
            <div class="is-row">
                <div class="is-col is-75">
                    <table class="is-bordered is-striped is-responsive">
                        <thead>
                        <tr>
                            <th>
                                {{ __('ikcms::admin.medias_image') }}
                            </th>
                            <th>
                                {{ __('ikcms::admin.medias_title') }}
                            </th>
                            <th>
                                {{ __('ikcms::admin.medias_description') }}
                            </th>
                            <th>
                                {{ __('ikcms::admin.medias_type') }}
                            </th>
                            <th>

                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!$slider->image)
                            <tr>
                                <td colspan="5">{{ __('ikcms::admin.no_data') }}</td>
                            </tr>
                        @else
                            @php $medias = $slider->getMedias("image"); @endphp
                            <tr>
                                <td data-label="{{ __('ikcms::admin.medias_image') }}">
                                    <img src="{{  (new IkCms())->resizeImage($medias['target_path'], 30 ,30) }}"
                                         alt="{{ $medias['alt'] }}"
                                         class="remodalImg"
                                         data-src="/{{ $medias['target_path'] }}"
                                    >
                                </td>
                                <td data-label="{{ __('ikcms::admin.medias_title') }}">
                                    <input type="text" name="medias_alt" value="{{ $medias['alt'] }}">
                                </td>
                                <td data-label="{{ __('ikcms::admin.medias_description') }}">
                                    <input type="text" name="medias_description" value="{{ $medias['description'] }}">
                                </td>
                                <td data-label="{{ __('ikcms::admin.medias_type') }}">
                                    {{ $medias['file_type'] }}
                                </td>
                                <td>
                                    <a href="{{ route('ikcms.admin.sliders.image.update',['uuid' => $slider->uuid]) }}"
                                       class="table-button edit-media" title="Modifier"
                                    >
                                        <i class="fa fa-floppy-o"></i>
                                    </a>
                                    <a href="{{ route('ikcms.admin.sliders.image.delete',['uuid' => $slider->uuid]) }}"
                                       class="table-button delete-media confirm-alert" title="Supprimer"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="is-col is-25">
                    <label for="form-file">
                        {{ __('ikcms::admin.medias_single_upload') }}
                    </label>
                    <input type="file" name="image" id="form-file">
                </div>
            </div>


        </section>

    </div>
</div>

{!! IkForm::submit() !!}

{!! IkForm::close() !!}
