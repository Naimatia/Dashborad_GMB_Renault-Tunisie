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
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<!-- daterangepicker JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


<!-- date period -->
<script>
    $(function() {
        $('#dateRangePicker').daterangepicker({
            opens: 'left',
            showDropdowns: true, // Allows dropdowns for year and month
            autoApply: true,
            startDate: moment().startOf(
                'month'), // Set start date to the first day of the current month
            endDate: moment().endOf('month'),
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
        $('#dateRangePicker').val(moment().startOf('month').format('MMM YYYY') + ' - ' + moment().endOf('month')
            .format('MMM YYYY'));
    });

// Définition de la fonction pour récupérer le mois et l'année sélectionnés
function getSelectedMonthAndYear() {
    var startDate = $('#dateRangePicker').data('daterangepicker').startDate;
    var endDate = $('#dateRangePicker').data('daterangepicker').endDate;
    var startMonth = startDate.format('M'); // Récupérer le numéro du mois
    var endMonth = endDate.format('M'); // Récupérer le numéro du mois
    var startYear = startDate.format('YYYY');
    var endYear = endDate.format('YYYY');
    return {
        start: startMonth,
        end: endMonth,
        startYear: startYear,
        endYear: endYear
    };
}
 // Événement de changement de la plage de dates sélectionnée
 $('#dateRangePicker').on('apply.daterangepicker', function(ev, picker) {
        var selectedDates = getSelectedMonthAndYear();
        console.log(selectedDates.start , selectedDates.startYear);
        console.log(selectedDates.end , selectedDates.endYear);
    });
</script>

@yield('scripts')
