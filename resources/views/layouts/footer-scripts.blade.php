<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- Include the Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<!-- daterangepicker JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


<!-- date period -->
<script>
    $(function() {
        // Stocker la date initiale
        var initialStartDate = moment().startOf('month');
        var initialEndDate = moment().endOf('month');

        $('#dateRangePicker').daterangepicker({
            opens: 'left',
            showDropdowns: true, // Allows dropdowns for year and month
            autoApply: true,
            startDate: initialStartDate, // Utiliser la date initiale
            endDate: initialEndDate, // Utiliser la date initiale
            locale: {
                format: 'MMM YYYY', // Formatting to show only month and year
                applyLabel: 'Appliquer',
                cancelLabel: 'Annuler',
                fromLabel: 'De',
                toLabel: 'À',
                customRangeLabel: 'Période personnalisée',
                daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
                monthNames: [
                    'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                    'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
                ],
                firstDay: 1
            },
            ranges: {
                'Ce mois': [moment().startOf('month'), moment().endOf('month')],
                'Le mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                    'month').endOf('month')],
                'Les 6 derniers mois': [moment().subtract(6, 'months').startOf('month'), moment().endOf(
                    'month')],
                'L’année dernière': [moment().subtract(1, 'year').startOf('month'), moment().endOf(
                    'month')],
            },
            showCustomRangeLabel: false,
            linkedCalendars: false
        });

        // Set the initial value of the input to "Ce mois"
        $('#dateRangePicker').val(initialStartDate.format('MMM YYYY') + ' - ' + initialEndDate.format(
            'MMM YYYY'));
        // Fonction pour effectuer l'appel AJAX
        function performAjaxCall(id, startDate, endDate) {
            $.ajax({
                url: 'http://localhost:8000/fiche/' + id, // Include the id in the URL
                type: 'GET', // ou 'POST'
                data: {
                    startYear: startDate.format('YYYY'),
                    startMonth: startDate.format('M'),
                    endYear: endDate.format('YYYY'),
                    endMonth: endDate.format('M'),
                    startDay: startDate.format('D'),
                    endDay: endDate.format('D'),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Performance data:', response);
                    updateCharts(
                        response
                    ); // Vous devez définir cette fonction pour mettre à jour les graphiques avec les données reçues
                    // Update the startDate and endDate in the view
                    $('#startDateDisplay').text(response.startDate);
                    $('#endDateDisplay').text(response.endDate);
                },
                error: function(error) {
                    console.log('Error fetching performance data:', error);
                }
            });
        }
        // Assuming the URL structure is like http://localhost:8000/fiche/123
        var pathSegments = window.location.pathname.split('/');
        var id = pathSegments[2]; // This will give you the ID part of the URL
        // Appeler l'AJAX lors de l'initialisation de la page
        performAjaxCall(id, initialStartDate, initialEndDate);

        // Vérifier si l'identifiant est défini et différent de null
        if (id !== null) {
            // Événement de changement de la plage de dates sélectionnée
            $('#dateRangePicker').on('apply.daterangepicker', function(ev, picker) {
                var startDate = picker.startDate;
                var endDate = picker.endDate;

                console.log('Plage de dates sélectionnée :', startDate.format('D MMM YYYY'), '-',
                    endDate.format('D MMM YYYY'));

                // Effectuer l'appel AJAX avec l'identifiant et les dates sélectionnées
                performAjaxCall(id, startDate, endDate);
            });
        } else {
            console.log('Identifiant du lieu non trouvé dans l\'URL.');
        }


        // localisation URL
        // Function to perform AJAX call
        function LocationAjaxCall(startDate, endDate) {
            $.ajax({
                url: `http://localhost:8000/`, // URL of the ListeLocalisation function
                type: 'GET',
                data: {
                    startYear: startDate.format('YYYY'),
                    startMonth: startDate.format('M'),
                    startDay: startDate.format('D'),
                    endYear: endDate.format('YYYY'),
                    endMonth: endDate.format('M'),
                    endDay: endDate.format('D'),
                    // Include any other necessary data here
                },
                success: function(response) {
                    console.log('Data received:', response);

                    // Calculer les sommes totales d'interactions et d'impressions
                    const {
                        totalSum,
                        graphInteractionData
                    } = calculateSumOfValues(response);

                    const {
                        totalImpressionsSum,
                        graphConseltationData
                    } = calculateImpressionsSum(response)
                    // Update the startDate and endDate in the view
                    $('#totalInteractionSum').text(totalSum);
                    $('#totalImpressionsSum').text(totalImpressionsSum);

                    // Afficher le graphique des ventes
                    displaySalesGraph(graphInteractionData);
                    displayConseltationGraph(graphConseltationData)
                },
                error: function(error) {
                    console.log('Error fetching data:', error);
                }
            });
        }

        // Initial call with default date range
        LocationAjaxCall(initialStartDate, initialEndDate);

        // Add event listener for date range picker
        $('#dateRangePicker').on('apply.daterangepicker', function(ev, picker) {
            var startDate = picker.startDate;
            var endDate = picker.endDate;
            LocationAjaxCall(startDate, endDate);
        });
    });
</script>

@yield('scripts')
