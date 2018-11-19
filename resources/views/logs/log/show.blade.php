@extends("ikcms::layouts.admin")

@section("content")
    <div class="content-section">

        <div class="content-title">
            <h1>Affichage du log : #{{ $log->id }}</h1>
            <div class="button-actions">
                <a href="{{ route("ikcms.admin.logs.list") }}">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    Retour
                </a>
                <div class="clear"></div>
            </div>
        </div>

        <pre style="white-space:normal;line-height:1.5rem;margin:0;">{{ $log->message }}</pre>
        <br><br>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Stack Trace</h3>
            </div>

            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Info</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php $json = json_decode($log->trace, true) @endphp
                        @if($json)
                            @foreach(json_decode($log->trace, true) as $index => $trace)
                                <tr>
                                    <td class="w-1">#{{ $index }}</td>
                                    @if(isset($trace['class']) && isset($trace['function']))
                                        <td>{{ $trace['class'] }}<strong>&#64;{{ $trace['function'] }}</strong></td>
                                    @else
                                        @if(isset($trace['file']))
                                            <td>{{ $trace['file'] }}<strong>:{{ $trace['line'] }}</strong></td>
                                        @endif
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="button-actions">
            <a href="{{ route("ikcms.admin.logs.list") }}">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                Retour
            </a>
            <div class="clear"></div>
        </div>

    </div>
@endsection