@extends("ikcms::layouts.admin")

@section("content")
    <div class="dashboard">
        <div class="is-row">
            <div class="is-75 is-col">
                @if(config('larametrics.requestsWatched') || !config('larametrics.hideUnwatchedMenuItems'))
                    <div class="dashboard-stats card">
                        <div class="card-header">
                            <h3 class="card-title">10 requêtes les plus consultées</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="is-bordered is-striped">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Methode</th>
                                    <th>Temps de réponse</th>
                                    <th>Date</th>
                                    <th>URL</th>
                                    <th>Adresse IP</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($requests as $request)
                                    @php
                                        $textClass = 'fa-circle text-info';
                                        if($request->method === 'POST' || $request->method === 'PUT' || $request->method === 'OPTIONS') {
                                            $textClass = 'fa-circle text-warning';
                                        }
                                        if($request->method === 'DELETE') {
                                            $textClass = 'fa-circle text-danger';
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <i class="fa {{ $textClass }}"></i>
                                        </td>
                                        <td>
                                            {{ $request->method }}
                                        </td>
                                        <td>
                                            <strong>{{ floor(($request->end_time - $request->start_time) * 1000) }}ms</strong>
                                        </td>
                                        <td>
                                            {{ IkCms::formatDate($request->created_at) }}
                                            à {{ IkCms::formatDate($request->created_at,"H:i:s") }}
                                        </td>
                                        <td>
                                            <strong>{{ IkCms::str_max($request->uri, 50) }}</strong>
                                        </td>
                                        <td>
                                            {{ $request->ip }}
                                        </td>
                                        <td>
                                            {{ $request->occurrences }}
                                        </td>
                                        <td><a href="{{ route('ikcms.admin.requests.show', $request->id) }}" class="table-button">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                Détail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if(config('larametrics.logsWatched') || !config('larametrics.hideUnwatchedMenuItems'))
                    <div class="dashboard-stats card">
                        <div class="card-header">
                            <h3 class="card-title">10 derniers logs</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="is-bordered is-striped">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Niveau</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($logs as $log)
                                    @php

                                        $textClass = 'fa-exclamation-circle text-info';
                                        if($log->level === 'warning' || $log->level === 'failed') {
                                            $textClass = 'fa-exclamation-circle text-warning';
                                        }
                                        if($log->level === 'error' || $log->level === 'critical' || $log->level === 'alert' || $log->level === 'emergency') {
                                            $textClass = 'fa-exclamation-triangle text-danger';
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <i class="fa {{ $textClass }}"></i>
                                        </td>
                                        <td>
                                            {{ ucwords($log->level) }}
                                        </td>
                                        <td>
                                            {{ IkCms::formatDate($log->created_at) }}
                                             à {{ IkCms::formatDate($log->created_at,"H:i:s") }}
                                        </td>
                                        <td>
                                            <strong>{{ IkCms::str_max($log->message, 70) }}</strong>
                                        </td>
                                        <td>
                                            <a href="{{ route('ikcms.admin.logs.show', $log->id) }}" class="table-button">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                Détail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
            <div class="is-25 is-col">

                <div class="dashboard-date">
                    <div class="clock">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                        {{ date("H:i:s") }}
                    </div>
                    <dif class="date">
                        {{ date("d/m/Y") }}
                    </dif>
                </div>
                @if(count(config('larametrics.modelsWatched')) || !config('larametrics.hideUnwatchedMenuItems'))
                    @foreach($models as $model)
                        <div class="dashboard-log">
                            <div class="type">
                                @php
                                    $methodClass = 'fa-pencil text-info';
                                    if($model->method === 'deleted') {
                                        $methodClass = 'fa-trash text-danger';
                                    } elseif($model->method === 'created') {
                                        $methodClass = 'fa-plus text-success';
                                    }
                                @endphp
                                <div class="add">
                                    <i class="fa {{ $methodClass }}"></i>
                                </div>
                            </div>
                            <div class="log-description">
                                <div class="description">
                                    {{ $model->model . ' #' . json_decode($model->original, true)['id'] }}
                                </div>
                                <div class="user">
                                    {{ ucwords($model->method) }}
                                </div>
                                <div class="date">
                                    Le {{ date('d/m/Y H:i', strtotime($model->created_at))}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <a href="{{ route("ikcms.admin.logs.list") }}" class="log-link">
                        Afficher les interactions avec la BDD
                    </a>
                @endif
            </div>
        </div>

    </div>
@endsection
