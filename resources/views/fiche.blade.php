@extends('layouts.master')


@section('title')
    Home
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- daterangepicker CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Custom CSS for the date range picker -->
    <style>
        .daterangepicker {
            border-radius: 4px;
            color: hsl(0, 0%, 20%);
            font-size: 14px;
        }

        .daterangepicker .ranges ul li.active {
            background-color: #007bff;
            color: white;
        }

        .daterangepicker .drp-calendar {
            border: none;
        }

        .daterangepicker .drp-calendar.left {
            border-right: none;
        }
    </style>

@endsection

@section('title_head')
    Bienvenue
@endsection

@section('title_page1')
    home
@endsection

@section('title_page2')
    location
@endsection

@section('content')
    <div class="container-fluid">


        <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3>150</h3>

              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>53<sup style="font-size: 20px">%</sup></h3>

              <p>Bounce Rate</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>44</h3>

              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>65</h3>

              <p>Unique Visitors</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
        <!-- Main row -->
        <div class="row">

            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">
                <!-- Custom tabs (Charts with tabs)-->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-1"></i>
                            Répartition des plates-formes et appareils
                        </h3>
                        <div class="card-tools">
                            <ul class="nav nav-pills ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                                </li>
                            </ul>
                        </div>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content p-0">
                            <!-- Morris chart - Sales -->
                            <div class="chart tab-pane active" id="revenue-chart"
                                style="position: relative; height: 300px;">
                                <canvas id="impressionsChart" height="300" style="height: 300px;"></canvas>
                            </div>
                            <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                                <canvas id="impressionsSales" height="300" style="height: 300px;"></canvas>
                            </div>
                        </div>
                    </div><!-- /.card-body -->
                </div>
            </section>
        </div>

    </div>
    </div>
@endsection

@section('scripts')
    <!-- Date Range Picker JavaScript -->



    <script>
        // Assuming $performanceData is passed from the controller and contains the data
        const rawData = @json($performanceData);

        // Initialize objects to store the monthly totals for each category
        const monthlyTotals = {
            'Recherche Google-ordinateur': {},
            'Google Maps-mobile': {},
            'Google Maps-ordinateur': {},
            'Recherche Google-mobile': {}
        };

        // A helper function to get the month name from a month index
        function getMonthName(month) {
            const monthNames = [
                "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
            ];
            return monthNames[month - 1];
        }

        rawData.multiDailyMetricTimeSeries.forEach(series => {
            series.dailyMetricTimeSeries.forEach(metricSeries => {
                metricSeries.timeSeries.datedValues.forEach(datedValue => {
                    const monthName = getMonthName(datedValue.date.month);
                    const metricName = {
                        'BUSINESS_IMPRESSIONS_DESKTOP_SEARCH': 'Recherche Google-ordinateur',
                        'BUSINESS_IMPRESSIONS_MOBILE_MAPS': 'Google Maps-mobile',
                        'BUSINESS_IMPRESSIONS_DESKTOP_MAPS': 'Google Maps-ordinateur',
                        'BUSINESS_IMPRESSIONS_MOBILE_SEARCH': 'Recherche Google-mobile'
                    } [metricSeries.dailyMetric];

                    monthlyTotals[metricName][monthName] = (monthlyTotals[metricName][monthName] ||
                        0) + (parseInt(datedValue.value) || 0);
                });
            });
        });

        // Color configuration for each category
        const colors = {
            'Recherche Google-ordinateur': {
                backgroundColor: 'rgb(54, 162, 235)',
                borderColor: 'rgb(54, 162, 235)'
            }, // Blue
            'Google Maps-mobile': {
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)'
            }, // Red
            'Google Maps-ordinateur': {
                backgroundColor: 'rgb(75, 192, 192)',
                borderColor: 'rgb(75, 192, 192)'
            }, // Green
            'Recherche Google-mobile': {
                backgroundColor: 'rgb(255, 205, 86)',
                borderColor: 'rgb(255, 205, 86)'
            } // Yellow
        };

        // Convert monthly totals to datasets format for Chart.js
        const datasets = Object.entries(monthlyTotals).map(([label, data]) => ({
            label,
            data: Object.values(data),
            fill: false,
            ...colors[label],
        }));

        // Use the months as labels
        const labels = Object.keys(monthlyTotals['Recherche Google-ordinateur']);

        // Render the line chart
        const ctx = document.getElementById('impressionsChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // This will allow the chart to resize based on container's aspect ratio
                scales: {
                    x: {
                        display: true
                    },
                    y: {
                        display: true
                    }
                }
            }
        });

        // Assuming you have sales data to display in a doughnut chart
        const salesData = {
            labels: [
                'Sales 1',
                'Sales 2',
                'Sales 3'
            ],
            datasets: [{
                data: [300, 50, 100],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ],
                hoverOffset: 4
            }]
        };

        // Render the doughnut chart for Sales
        const salesCtx = document.getElementById('impressionsSales').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'doughnut',
            data: salesData,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>


    <script>
        $(function() {
            $('#dateRangePicker').daterangepicker({
                opens: 'left',
                showDropdowns: true, // Allows dropdowns for year and month
                locale: {
                    format: 'MMM YYYY',
                    applyLabel: 'Appliquer',
                    cancelLabel: 'Annuler',
                    fromLabel: 'De',
                    toLabel: 'À',
                    customRangeLabel: 'Période personnalisée',
                    daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
                    monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                        'Septembre', 'Octobre', 'Novembre', 'Décembre'
                    ],
                    firstDay: 1
                },
                ranges: {
                    'Aujourd\'hui': [moment(), moment()],
                    'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 derniers jours': [moment().subtract(6, 'days'), moment()],
                    '30 derniers jours': [moment().subtract(29, 'days'), moment()],
                    'Ce mois': [moment().startOf('month'), moment().endOf('month')],
                    'Le mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                },
                alwaysShowCalendars: true, // Always show the calendars
            });
        });
    </script>
@endsection
