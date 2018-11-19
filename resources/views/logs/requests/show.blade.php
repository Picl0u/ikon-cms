@extends("ikcms::layouts.admin")

@section("content")
    @php $headers = json_decode($request->headers, true); @endphp

    <div class="content-section">
        <div class="content-title">
            <h1>Affichage de la requête : #{{ $request->id }}</h1>
            <div class="button-actions">
                <a href="{{ route("ikcms.admin.requests.list") }}">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    Retour
                </a>
                <div class="clear"></div>
            </div>
        </div>

        <div style="width:100%;max-width:1100px;">
            <pre style="white-space:normal;line-height:1.5rem;margin:0;">
                {{ $request->headers }}
            </pre>
        </div>
        <br><br>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informations</h3>
            </div>

            <div class="is-table-container">
                <table>
                    <thead>
                    <tr>
                        <th>Champ</th>
                        <th>Valeur</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Methode</td>
                        <td><strong>{{ $request->method }}</strong></td>
                    </tr>
                    <tr>
                        <td>URL</td>
                        <td><strong>{{ $request->uri }}</strong></td>
                    </tr>
                    <tr>
                        <td>Address IP</td>
                        <td><strong>{{ $request->ip }}</strong></td>
                    </tr>
                    <tr>
                        <td>Temps de réponse</td>
                        <td><strong>{{ floor(($request->end_time - $request->start_time) * 1000) }}ms</strong></td>
                    </tr>
                    @if($headers)
                        @if(isset($headers['host']) && isset($headers['host'][0]))
                            <tr>
                                <td>Hôte</td>
                                <td><strong>{{ $headers['host'][0] }}</strong></td>
                            </tr>
                        @endif
                        @if(isset($headers['user-agent']) && isset($headers['user-agent'][0]))
                            <tr>
                                <td>User Agent</td>
                                <td><strong>{{ $headers['user-agent'][0] }}</strong></td>
                            </tr>
                        @endif
                        @if(isset($headers['referer']) && isset($headers['referer'][0]))
                            <tr>
                                <td>Referer</td>
                                <td><strong>{{ $headers['referer'][0] ?: '-' }}</strong></td>
                            </tr>
                        @endif
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="button-actions">
            <a href="{{ route("ikcms.admin.requests.list") }}">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                Retour
            </a>
            <div class="clear"></div>
        </div>

    </div>
@endsection