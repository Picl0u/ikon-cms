@extends("ikcms::layouts.admin")

@section("content")
    <div class="content-section">

        <div class="content-title">
            <h1>Entité : {{ $pageTitle }}</h1>
        </div>
        <div class="button-actions">
            <a href="{{ route('ikcms.admin.models.list') }}">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                Retour
            </a>
            <div class="clear"></div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    {{ count($models) }}
                    changement{{ count($models) !== 1 ? 's' : '' }}
                    en {{ $watchLength }}
                    jour{{ $watchLength !== 1 ? 's' : '' }}
                </h3>
            </div>
            <div class="table-responsive">
                <table class="is-bordered is-striped">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Methode</th>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Changements</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($models as $model)
                        @php
                            $methodClass = 'fa-circle text-info';
                            if($model->method === 'deleted') {
                                $methodClass = 'fa-times-circle text-danger';
                            } elseif($model->method === 'created') {
                                $methodClass = 'fa-plus-circle text-success';
                            }
                        @endphp
                        <tr>
                            <td><i class="fa {{ $methodClass }}"></i></td>
                            <td>{{ ucwords($model->method) }}</td>
                            <td>{{ json_decode($model->original, true)[$modelPrimaryKey] }}</td>
                            <td> {{ IkCms::formatDate($model->created_at) }}
                                à {{ IkCms::formatDate($model->created_at,"H:i:s") }}</td>
                            <td>
                                @php
                                    $original = json_decode($model->original, true);
                                    $changes = json_decode($model->changes, true);
                                @endphp

                                @if($model->method === 'created' && count($original))
                                    <pre style="white-space: pre-wrap;line-height:1.5rem">@foreach($original as $column => $data){{ $column }} <span class="text-success">+{{ strlen($data) }}</span><br>@endforeach</pre>
                                @endif

                                @if($model->method === 'deleted' && count($original))
                                    <pre style="white-space: pre-wrap;line-height:1.5rem">@foreach($original as $column => $data){{ $column }} <span class="text-danger">-{{ strlen($data) }}</span><br>@endforeach</pre>
                                @endif

                                @if($changes && count($changes))
                                    @php
                                        $changeArray = array();

                                        foreach($changes as $column => $change) {

                                            $changeChars = is_array($change) ? str_split($change['date']) : str_split($change);
                                            $originalChars = str_split($original[$column]);
                                            $changeNumbers = array(
                                                'added' => 0,
                                                'subtracted' => 0
                                            );

                                            foreach($originalChars as $index => $char) {
                                                if((isset($changeChars[$index])) && ($char !== $changeChars[$index])) {
                                                    $changeNumbers['added'] += 1;
                                                    $changeNumbers['subtracted'] += 1;
                                                }
                                            }

                                            $diff = (count($originalChars) - count($changeChars));
                                            if($diff < 0) {
                                                $changeNumbers['subtracted'] += abs($diff);
                                            } else if($diff > 0) {
                                                $changeNumbers['added'] += abs($diff);
                                            }

                                            $changeArray[$column] = $changeNumbers;
                                        }
                                    @endphp

                                    <pre style="white-space: pre-wrap;line-height:1.5rem">@foreach($changeArray as $column => $numbers){{ $column }} <span class="text-danger">-{{ $numbers['subtracted'] }}</span> <span class="text-success">+{{ $numbers['added'] }}</span><br>@endforeach</pre>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('ikcms.admin.models.revert', $model->id) }}" class="table-button">
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

        <div class="button-actions">
            <a href="{{ route('ikcms.admin.models.list') }}">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                Retour
            </a>
            <div class="clear"></div>
        </div>
    </div>
@endsection