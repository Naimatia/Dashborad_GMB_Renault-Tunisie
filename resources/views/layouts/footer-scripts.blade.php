<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI -->
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

<!-- Include the Chart.js library from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Include jQuery and Bootstrap from CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<!-- Include daterangepicker from CDN -->
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!-- Include Leaflet -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    $(window).on('load', function() {
        // Debounce function to limit the rate at which a function can fire
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func.apply(this, args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Define function for the date range picker
        function initializeDateRangePicker(initialStartDate, initialEndDate) {
            $('#dateRangePicker').daterangepicker({
                opens: 'left',
                showDropdowns: true,
                autoApply: true,
                startDate: initialStartDate,
                endDate: initialEndDate,
                locale: {
                    format: 'MMM YYYY',
                    applyLabel: 'Appliquer',
                    cancelLabel: 'Annuler',
                    fromLabel: 'De',
                    toLabel: 'À',
                    customRangeLabel: 'Période personnalisée',
                    daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
                    monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                    firstDay: 1
                },
                ranges: {
                    'Ce mois': [moment().startOf('month'), moment().endOf('month')],
                    'Le mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Le mois avant dernier': [moment().subtract(2, 'months').startOf('month'), moment().subtract(2, 'months').endOf('month')],
                    'Les 3 derniers mois': [moment().subtract(3, 'months').startOf('month'), moment().endOf('month')],
                    'Les 6 derniers mois': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],
                    'L’année dernière': [moment().subtract(1, 'year').startOf('month'), moment().endOf('month')]
                },
                showCustomRangeLabel: false,
                linkedCalendars: false
            });

            $('#dateRangePicker').val(initialStartDate.format('MMM YYYY') + ' - ' + initialEndDate.format('MMM YYYY'));

            // Add event listener for the date range picker
            $('#dateRangePicker').on('apply.daterangepicker', debounce(function(ev, picker) {
                const startDate = picker.startDate;
                const endDate = picker.endDate;

                // Call function for the corresponding page based on URL
                if (currentURL.includes('fiche')) {
                    performAjaxCall(id, startDate, endDate);
                } else if (currentURL === '/') {
                    LocationAjaxCall(startDate, endDate);
                }
            }, 300)); // 300ms debounce time
        }

        // Define function to perform AJAX call for 'fiche' page
        function performAjaxCall(id, startDate, endDate) {
            $.ajax({
                url: '/fiche/' + id,
                type: 'GET',
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

                    // Update charts and view elements
                    updateCharts(response);
                    $('#startDateDisplay').text(response.startDate);
                    $('#endDateDisplay').text(response.endDate);
                },
                error: function(error) {
                    console.log('Error fetching performance data:', error);
                }
            });
        }

        // Define function for AJAX call to retrieve data for the '/' URL
        function LocationAjaxCall(startDate, endDate) {
            $.ajax({
                url: '/',
                type: 'GET',
                data: {
                    startYear: startDate.format('YYYY'),
                    startMonth: startDate.format('M'),
                    startDay: startDate.format('D'),
                    endYear: endDate.format('YYYY'),
                    endMonth: endDate.format('M'),
                    endDay: endDate.format('D')
                },
                success: function(response) {
                    console.log('Data received:', response);

                    // Calculate totals and graph data
                    const { totalSum, graphInteractionData } = calculateSumOfValues(response);
                    const { totalImpressionsSum, graphConseltationData } = calculateImpressionsSum(response);

                    // Update view with totals
                    $('#totalInteractionSum').text(totalSum);
                    $('#totalImpressionsSum').text(totalImpressionsSum);

                    // Display the sales and consultation graphs
                    displaySalesGraph(graphInteractionData);
                    displayConseltationGraph(graphConseltationData);
                },
                error: function(error) {
                    console.log('Error fetching data:', error);
                }
            });
        }

        // Determine the current URL
        const currentURL = window.location.pathname;

        // Extract the ID from the URL if present
        const pathSegments = currentURL.split('/');
        const id = pathSegments[2] || null;

        // Initialize date range picker with the initial start and end dates
        const initialStartDate = moment().startOf('month');
        const initialEndDate = moment().endOf('month');
        initializeDateRangePicker(initialStartDate, initialEndDate);

        // Call function based on the current URL
        if (currentURL.includes('fiche') && id !== null) {
            // If on 'fiche' page, perform AJAX call with ID and initial dates
            performAjaxCall(id, initialStartDate, initialEndDate);
        } else if (currentURL === '/') {
            // If on root URL ('/'), perform AJAX call with initial dates
            LocationAjaxCall(initialStartDate, initialEndDate);
        } else {
            console.log('No appropriate page found for the current URL.');
        }
    });
</script>

@yield('scripts')
