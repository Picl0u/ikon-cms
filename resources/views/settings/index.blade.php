@extends("ikcms::layouts.admin")

@section("content")
    <nav class="tabs" data-kube="tabs">
        <a href="#website" class="is-active">Site Internet</a>
        <a href="#slider">Slider</a>
        <a href="#information">Informations</a>
        <a href="#social">Réseaux sociaux</a>
        <a href="#seo">Référencement</a>
    </nav>
    {!! IkForm::open(route("ikcms.admin.settings.store"), ['files' => true]) !!}

        <section id="website">
            <div class="content-section">
                <div class="content-title">
                    <h1>Paramètres du site Internet</h1>
                </div>

                {!! IkForm::image("website_logo", "Votre logo", Setting::get('website.logo'), [
                    'desc' => "Télécharger le logo de votre site."
                ]) !!}
                {!! IkForm::text("website_name", "Nom du site", Setting::get('website.name')) !!}
                {!! IkForm::email("website_email", "Email de contact", Setting::get('website.email'),[
                    'desc' => "Les emails depuis le site seront transmis à cet email."
                ]) !!}
                {!! ikForm::checkbox("website_maintenance", "Mode de maintenance", Setting::get('website.maintenance'), [
                    'default' => 1,
                    "desc" => "Permet de mettre votre site en maintenance. Il ne sera plus accessible."
                ]) !!}
                {!! ikForm::checkbox("recaptcha_active", "Activer recaptcha", Setting::get('recaptcha.active'), [
                    'default' => 1,
                    'desc' => "Permet d'activer le captcha sur vos formulaires."
                ]) !!}
                {!! IkForm::text("recaptcha_public", "Recaptcha : Clé client", Setting::get('recaptcha.public')) !!}
                {!! IkForm::text("recaptcha_secret", "Recaptcha : Clé serveur", Setting::get('recaptcha.secret')) !!}
            </div>
        </section>

        <section id="slider">
            <div class="content-section">
                <div class="content-title">
                    <h1>Slider</h1>
                </div>

                {!! ikForm::checkbox("slider_arrows", "Afficher les flêches ?", Setting::get('slider.arrows'), [
                    'default' => 1,
                    "desc" => "Permet d'afficher ou non les flêches pour la navigation du slider"
                ]) !!}

                {!! ikForm::checkbox("slider_dots", "Afficher la pagination ?", Setting::get('slider.dots'), [
                    'default' => 1,
                    "desc" => "Permet d'afficher ou non la pagination pour la navigation du slider"
                ]) !!}
                {!! IkForm::select("slider_type", "Type de slider", Setting::get('slider.type'), [
                    "boxed" => "Boxed",
                    "fullwidth" => "FullWidth",
                    "fullscreen" => "FullScreen"
                ]) !!}
                {!! IkForm::select("slider_transition", "Type de transition", Setting::get('slider.transition'), [
                    "fade" => "Effet fondu",
                    "slide" => "Effet en glissé",
                ]) !!}

                {!! IkForm::text("slider_duration", "Durée entre les slides", Setting::get('slider.duration'),[
                    "desc" => "En milliseconde, exemple : 1 seconde = 1000 ms"
                ]) !!}

                {!! IkForm::text("slider_duration_transition", "Durée de l'effet d'apparition", Setting::get('slider.duration_transition'),[
                    "desc" => "En milliseconde, exemple : 1 seconde = 1000 ms"
                ]) !!}

            </div>

        </section>

        <section id="information">
            <div class="content-section">
                <div class="content-title">
                    <h1>Vos informations</h1>
                </div>

                {!! IkForm::text("company_name", "Nom de votre entreprise", Setting::get('company.name')) !!}
                {!! IkForm::text("company_address", "Adresse de votre entreprise", Setting::get('company.address')) !!}
                {!! IkForm::text("company_zip", "Code postal de votre entreprise", Setting::get('company.zip')) !!}
                {!! IkForm::text("company_city", "Ville de votre entreprise", Setting::get('company.city')) !!}

            </div>

        </section>

        <section id="social">
            <div class="content-section">
                <div class="content-title">
                    <h1>Vos réseaux sociaux</h1>
                </div>

                {!! IkForm::text("facebook", "Facebook", Setting::get('website.facebook')) !!}
                {!! IkForm::text("twitter", "Twitter", Setting::get('website.twitter')) !!}
                {!! IkForm::text("instagram", "Instagram", Setting::get('website.instagram')) !!}
                {!! IkForm::text("pinterest", "Pinterest", Setting::get('website.pinterest')) !!}
                {!! IkForm::text("youtube", "Youtube", Setting::get('website.youtube')) !!}

            </div>

        </section>

        <section id="seo">
            <div class="content-section">
                <div class="content-title">
                    <h1>Référencement</h1>
                </div>

                {!! ikForm::checkbox("seo_robot", "Activer le référencement", Setting::get('seo.robot'), [
                    'default' => 1,
                    'desc' => "Permet d'activer le référencement du site sur les moteurs de recherche."
                ]) !!}
                {!! IkForm::text("seo_title", "Méta Title", Setting::get('seo.title')) !!}
                {!! IkForm::text("seo_description", "Méta Description", Setting::get('seo.description')) !!}
                {!! IkForm::text("seo_twitter", "Pseudo twitter", Setting::get('seo.twitter'),[
                    'desc' => "Permet d'identifier la page twitter lors du partage du site. Exemple : @IkonK"
                ]) !!}

            </div>

        </section>

        {!! IkForm::submit() !!}

    {!! IkForm::close() !!}

@endsection
