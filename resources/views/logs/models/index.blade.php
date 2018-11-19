@extends("ikcms::layouts.admin")

@section("content")
    <div class="content-section">
        <div class="content-title">
            <h1>
                Gestion des interactions avec la base de données
                <small>Les données sont supprimées tout les {{ config("larametrics.modelsWatchedExpireDays") }} jours</small>
            </h1>
        </div>
        <div class="informations-container">
            <nav class="breadcrumb">
                <a href="">Logs</a>
                <span>Base de données</span>
            </nav>

            <div class="infos">
                <div class="infos-detail">
                    <span>
                        <i class="fa fa-database" aria-hidden="true"></i>
                        Total
                    </span>
                    <span class="label">{{ $total }}</span>
                </div>
            </div>
        </div>
        <div style="display:flex">
            @foreach($modelsAmounts as $model => $info)
                <div class="model-card col-33">
                    <div class="is-flex">
                        <span class="count">
                            {{ $info['count'] }}
                        </span>
                        <div>
                            <h4 class="m-0">
                                <a href="{{ route('ikcms.admin.models.show', str_replace('\\', '+', $model)) }}">
                                    <small>{{ $model }}</small>
                                </a>
                            </h4>
                            <small class="text-muted">
                                {{ $info['changes'] }} changement{{ $info['changes'] !== 1 ? 's' : '' }} en
                                {{ $watchLength }} jour{{ $watchLength !== 1 ? 's' : '' }}
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection