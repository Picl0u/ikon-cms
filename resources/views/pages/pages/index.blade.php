
@extends("ikcms::layouts.admin")

@section("content")

    <div class="content-section">
        <div class="content-title">
            <h1>Gestion des contenus</h1>
            <div class="button-actions">
                <a href="{{ route("ikcms.admin.pages.create") }}">
                    <i class="fa fa-plus"></i>
                    Ajouter
                </a>
                <a href="{{ route("ikcms.admin.pages.position") }}">
                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                    Positions
                </a>
                <div class="clear"></div>
            </div>
        </div>

        <div class="informations-container">
            <nav class="breadcrumb">
                <a href="">Administration</a>
                <span>Pages</span>
                <span>Contenus</span>
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
                        Publié
                    </span>
                    <span class="label is-success">{{ $active }}</span>
                </div>
                <div class="infos-detail">
                    <span>
                        <i class="fa fa-window-close" aria-hidden="true"></i>
                        Non publié
                    </span>
                    <span class="label is-error">{{ $desactive }}</span>
                </div>
                <div class="infos-detail">
                    <span>
                        <i class="fa fa-unlock" aria-hidden="true"></i>
                        Public
                    </span>
                    <span class="label is-success">{{ $public }}</span>
                </div>
                <div class="infos-detail">
                    <span>
                        <i class="fa fa-lock" aria-hidden="true"></i>
                        Privé
                    </span>
                    <span class="label is-error">{{ $private }}</span>
                </div>
            </div>
        </div>

        <div class="datatable-container">
            <table class="datatable display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Publié?</th>
                    <th>Image</th>
                    <th>Titre</th>
                    <th>Section</th>
                    <th>Visibilité</th>
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
                ajax : "{{ route('ikcms.admin.pages.index') }}",
                columns: [
                    { data: 'actions', name: 'actions' },
                    { data: 'id', name: 'id' },
                    { data: 'published', name: 'published' },
                    { data: 'image', name: 'image' },
                    { data: 'name', name: 'name' },
                    { data: 'section_name', name: 'section_name' },
                    { data: 'visibility', name: 'visibility' },
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