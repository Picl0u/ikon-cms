@extends("ikcms::layouts.admin")

@section("content")
    <div class="content-section">
        <div class="content-title">
            <h1>
                Gestion des requêtes
                <small>Les données sont supprimées tout les {{ config("larametrics.requestsWatchedExpireDays") }} jours</small>
            </h1>
        </div>

        <div class="informations-container">
            <nav class="breadcrumb">
                <a href="">Logs</a>
                <span>Requêtes</span>
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
        <div class="datatable-container">
            <table class="datatable display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th></th>
                    <th>Methode</th>
                    <th>Temps de réponse</th>
                    <th>Date</th>
                    <th>URL</th>
                    <th>Adresse IP</th>
                    <th></th>
                </tr>
                </thead>
            </table>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        jQuery(function() {
            jQuery('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax : "{{ route('ikcms.admin.requests.list') }}",
                columns: [
                    { data: 'level', name: 'level' },
                    { data: 'method', name: 'method' },
                    { data: 'time', name: 'time' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'uri', name: 'uri' },
                    { data: 'ip', name: 'ip' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 5
                }],
                order: [[ 3, "desc" ]],
                stateSave: true,
                responsive: true,
                scrollX: true,
                language : {
                    url : 'http://cdn.datatables.net/plug-ins/1.10.16/i18n/French.json'
                },

            });
        });
    </script>
@endpush