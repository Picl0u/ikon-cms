@extends("ikcms::layouts.admin")

@section("content")
    @php
        $original = json_decode($model->original, true);
        $changes = json_decode($model->changes, true);
    @endphp

    <div class="content-section">
        <div class="content-title">
            <h1>
                ID: {{ $original[$modelPrimaryKey] }} -
                {{ ucwords($model->method) }}
                le {{ IkCms::formatDate($model->created_at) }}
                à {{ IkCms::formatDate($model->created_at,"H:i:s") }}
            </h1>
            <div class="button-actions">
                <a href="{{ route('ikcms.admin.models.show', str_replace('\\', '+', $pageTitle)) }}">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    Retour
                </a>
                <div class="clear"></div>
            </div>
        </div>
        <div class="card">
            @if($changes && count($changes))
                <div class="card-body">
                    @foreach($changes as $column => $content)
                        <h5>Nom du champ : {{ $column }}</h5>
                        <div class="is-row">
                            <div class="is-col is-50">
                                <pre style="white-space:normal;line-height:1.5rem;margin:0;">
                                    @php
                                        $changeChars = is_array($content) ? str_split($content['date']) : str_split($content);
                                        $originalChars = str_split($original[$column]);
                                        foreach($originalChars as $index => $char) {
                                            if(isset($changeChars[$index])) {
                                                if($changeChars[$index] !== $char) {
                                                    if($index === 0 || ($originalChars[$index - 1] === $changeChars[$index - 1])) {
                                                        echo '<span class="text-danger">';
                                                    }
                                                } else if($index >= 1) {
                                                    if($originalChars[$index - 1] !== $changeChars[$index - 1]) {
                                                        echo '</span>';
                                                    }
                                                }
                                            }
                                            echo htmlspecialchars($char);
                                        }
                                    @endphp
                                </pre>
                            </div>
                            <div class="is-col is-50">
                                <pre style="white-space:normal;line-height:1.5rem;margin:0;">
                                    @php
                                        foreach($changeChars as $index => $char) {
                                            if(isset($originalChars[$index])) {
                                                if($originalChars[$index] !== $char) {
                                                    if($index === 0 || ($originalChars[$index - 1] === $changeChars[$index - 1])) {
                                                        echo '<span class="text-success">';
                                                    }
                                                } else if($index >= 1) {
                                                    if($originalChars[$index - 1] !== $changeChars[$index - 1]) {
                                                        echo '</span>';
                                                    }
                                                }
                                            }
                                            echo htmlspecialchars($char);
                                        }
                                    @endphp
                                </pre>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif($original && count($original))
                <div class="card-body">
                    @foreach($original as $column => $data)
                        <h5>Nom du champ : {{ $column }}</h5>
                        <pre style="white-space:normal;line-height:1.5rem;margin:0;">
                            @if($model->method === 'created')
                                <span class="text-success">{{ $data }}</span>
                            @elseif($model->method === 'deleted')
                                <span class="text-danger">{{ $data }}</span>
                            @endif
                        </pre>
                    @endforeach
                </div>
            @endif
        </div>
        <br><br>
        <div class="button-actions">
            <a href="{{ route('ikcms.admin.models.show', str_replace('\\', '+', $pageTitle)) }}">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                Retour
            </a>
            <div class="clear"></div>
        </div>
    </div>
@endsection