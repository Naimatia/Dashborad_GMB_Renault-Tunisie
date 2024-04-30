@extends('layouts.master')

@section('title')
    Établissement
@stop

@section('css')
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <style>
    /* Hover effect on table rows */
    table tr:hover {
        background-color: #f5f5f5; /* Change the background color on hover */
    }
    </style>
  @endsection

  @section('title_head')
  {{ count($locations) }} Établissement
@endsection



@section('title_page1')
    Accueil
@endsection

@section('title_page2')
    Établissements
@endsection

@section('content')

@if (!empty($locations))

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="locationsTable" class="table table-bordered table-hover" style="cursor: grab">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nom de l'établissement</th>
                                            <th>Ville</th>
                                            <th>Addresse</th>
                                            <th>Télephone</th>
                                            <th>État</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($locations as $location)
                                        <tr data-id="{{ explode('/', $location['name'])[1] }}"
                                                data-title="{{ $location['title'] }}">
                                                <td>{{ $location['title'] ?? 'Aucune Titre' }}</td>
                                                <td>{{ $location['storefrontAddress']['locality'] ?? 'Aucune Localité' }}</td>
                                                <td>
                                                    @if (isset($location['storefrontAddress']['addressLines']) && is_array($location['storefrontAddress']['addressLines']))
                                                        @foreach ($location['storefrontAddress']['addressLines'] as $addressLine)
                                                            {{ htmlspecialchars($addressLine) }}
                                                        @endforeach
                                                    @else
                                                        Aucune adresse
                                                    @endif
                                                </td>
                                                                                                </td>
                                                <td>{{ $location['phoneNumbers']['primaryPhone'] ?? 'Aucune Phone Number' }}
                                                </td>
                                                <td>
                                                    @if ($location['verified'])
                                                        <span class="badge badge-success">Valide</span>
                                                    @else
                                                        <span class="badge badge-warning">Validation requise</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
    @else
        <p>No locations found.</p>
    @endif
@endsection

@section('scripts')

    </script>
    <!-- jQuery -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../../plugins/jszip/jszip.min.js"></script>
    <script src="../../plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../../plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

    <script>
        $(function() {
            $("#locationsTable").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#locationsTable_wrapper .col-md-6:eq(0)');
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#locationsTable tbody').on('click', 'tr', function() {
                var id = $(this).data('id');

                // Vérifier si l'ID est valide
                if (id) {
                    // Construct the URL correctly without duplicating the port number
                    var url = new URL('/fiche/' + id, window.location.origin);
                    // Ajouter le titre à l'URL si présent dans les paramètres de requête
                    var title = $(this).data('title');
                    if (title) {
                        url.searchParams.append('title', title);
                    }
                    // Utiliser window.location.href pour naviguer
                    window.location.href = url.toString();
                    // Correct way to navigate
                } else {
                    console.error("Identifiant du lieu non trouvé.");
                    // Afficher un message d'erreur ou prendre une autre action appropriée si l'identifiant du lieu n'est pas trouvé
                }
            });
        });
    </script>

@endsection
