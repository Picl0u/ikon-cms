@extends("ikcms::layouts.admin")

@section("content")

    <div class="content-section">
        <div class="content-title">
            <h1>Gestion des administrateurs</h1>
            <div class="button-actions">
                <a href="{{ route("ikcms.admin.users.create") }}">
                    <i class="fa fa-plus"></i>
                    Ajouter
                </a>
                <div class="clear"></div>
            </div>
        </div>

        <div class="informations-container">
            <nav class="breadcrumb">
                <a href="">Administration</a>
                <span>Administrateurs</span>
            </nav>

            <div class="infos">
                <div class="infos-detail">
                    <span>
                        <i class="fa fa-database" aria-hidden="true"></i>
                        Total
                    </span>
                    <span class="label">{{ $total }}</span>
                </div>
                <div class="infos-detail">
                    <span>
                        <i class="fa fa-check-square" aria-hidden="true"></i>
                        Activé
                    </span>
                    <span class="label is-success">{{ $active }}</span>
                </div>
                <div class="infos-detail">
                    <span>
                        <i class="fa fa-window-close" aria-hidden="true"></i>
                        Désactivé
                    </span>
                    <span class="label is-error">{{ $desactive }}</span>
                </div>
            </div>
        </div>

        <div class="datatable-container">
            <table class="datatable display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Activé?</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Dernière modification</th>
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
                ajax : "{{ route('ikcms.admin.users.index') }}",
                columns: [
                    { data: 'actions', name: 'actions' },
                    { data: 'id', name: 'id' },
                    { data: 'online', name: 'online' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'role', name: 'role' },
                    { data: 'updated_at', name: 'updated_at' }
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],
                order: [[ 1, "desc" ]],
                stateSave: true,
                responsive: true,
                language : {
                    url : 'http://cdn.datatables.net/plug-ins/1.10.16/i18n/French.json'
                },

            });
        });
    </script>
@endpush