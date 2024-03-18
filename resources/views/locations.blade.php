@extends('layouts.master')

@section('title')
    Location
@stop

@section('css')
    <!-- Include Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include DataTables CSS for Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@endsection

@section('title_head')
    {{ count($locations['locations']) }} Établissement
@endsection

@section('title_page1')
    home
@endsection

@section('title_page2')
    location
@endsection

@section('content')
    @if(!empty($locations['locations']))

        <div class="table-responsive">
            <table id="locationsTable" class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Nom de l'établissement</th>
                        <th>Ville</th>
                        <th>Addresse</th>
                        <th>RegionCode</th>
                        <th>Code Postal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations['locations'] as $location)
                    <tr data-id="{{ explode('/', $location['name'])[1] }}" data-title="{{ $location['title'] }}">
                        <td>{{ $location['title'] ?? 'No Title' }}</td>
                            <td>{{ $location['storefrontAddress']['locality'] ?? 'No Locality' }}</td>
                            <td>{{ $location['storefrontAddress']['addressLines'][0] ?? 'No Address' }}</td>
                            <td>{{ $location['storefrontAddress']['regionCode'] ?? 'No Region Code' }}</td>
                            <td>{{ $location['storefrontAddress']['postalCode'] ?? 'No Postal Code' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No locations found.</p>
    @endif
@endsection

@section('scripts')
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- Include DataTables Bootstrap 4 JavaScript -->
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#locationsTable').DataTable({
                "language": {
                    "sProcessing": "Traitement en cours...",
                    "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
                    "sZeroRecords": "Aucun résultat trouvé",
                    "sEmptyTable": "Aucune donnée disponible dans le tableau",
                    "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
                    "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
                    "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
                    "sInfoPostFix": "",
                    "sSearch": "Rechercher&nbsp;:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Chargement en cours...",
                    "oPaginate": {
                        "sFirst": "Premier",
                        "sLast": "Dernier",
                        "sNext": "Suivant",
                        "sPrevious": "Précédent"
                    },
                    "oAria": {
                        "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                    }
                }
            });
        });

        // route vers fiche
    </script>

<script>
  $(document).ready(function() {
    $('#locationsTable tbody').on('click', 'tr', function() {
        var id = $(this).data('id');
        var title = $(this).data('title');

        // Construct the URL correctly without duplicating the port number
        var url = new URL('/fiche/' + id, window.location.origin);
        if (title) {
            url.searchParams.append('title', title);
        }

        // Use window.location.href to navigate
        window.location.href = url.toString(); // Correct way to navigate
    });
});



</script>


@endsection
