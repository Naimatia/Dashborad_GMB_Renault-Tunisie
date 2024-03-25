@extends('layouts.master')


@section('title')
    Home
@stop

@section('css')
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

        <!-- Info boxes -->
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-eye"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Consultations</span>
                        <span class="info-box-number">
                            <span id="currentYearTotal"></span>
                            <span class="description-percentage" id="growthRateDisplay" style="font-weight: normal;"></span>
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Likes</span>
                        <span class="info-box-number">41,410</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Sales</span>
                        <span class="info-box-number">760</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">New Members</span>
                        <span class="info-box-number">2,000</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Monthly Recap Report</h5>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-wrench"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" role="menu">
                                    <a href="#" class="dropdown-item">Action</a>
                                    <a href="#" class="dropdown-item">Another action</a>
                                    <a href="#" class="dropdown-item">Something else here</a>
                                    <a class="dropdown-divider"></a>
                                    <a href="#" class="dropdown-item">Separated link</a>
                                </div>
                            </div>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-center">
                                    <strong>Sales: 1 Jan, 2014 - 30 Jul, 2014</strong>
                                </p>

                                <div class="chart">
                                    <!-- Sales Chart Canvas -->
                                    <canvas id="salesChart" height="180" style="height: 180px;"></canvas>
                                </div>
                                <!-- /.chart-responsive -->
                            </div>
                            <!-- /.col -->
                            <div class="col-md-4">
                                <p class="text-center">
                                    <strong>Goal Completion</strong>
                                </p>

                                <div class="progress-group">
                                    Add Products to Cart
                                    <span class="float-right"><b>160</b>/200</span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-primary" style="width: 80%"></div>
                                    </div>
                                </div>
                                <!-- /.progress-group -->

                                <div class="progress-group">
                                    Complete Purchase
                                    <span class="float-right"><b>310</b>/400</span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-danger" style="width: 75%"></div>
                                    </div>
                                </div>

                                <!-- /.progress-group -->
                                <div class="progress-group">
                                    <span class="progress-text">Visit Premium Page</span>
                                    <span class="float-right"><b>480</b>/800</span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success" style="width: 60%"></div>
                                    </div>
                                </div>

                                <!-- /.progress-group -->
                                <div class="progress-group">
                                    Send Inquiries
                                    <span class="float-right"><b>250</b>/500</span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-warning" style="width: 50%"></div>
                                    </div>
                                </div>
                                <!-- /.progress-group -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- ./card-body -->
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage text-success"><i class="fas fa-caret-up"></i>
                                        17%</span>
                                    <h5 class="description-header">$35,210.43</h5>
                                    <span class="description-text">TOTAL REVENUE</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i>
                                        0%</span>
                                    <h5 class="description-header">$10,390.90</h5>
                                    <span class="description-text">TOTAL COST</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage text-success"><i class="fas fa-caret-up"></i>
                                        20%</span>
                                    <h5 class="description-header">$24,813.53</h5>
                                    <span class="description-text">TOTAL PROFIT</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 col-6">
                                <div class="description-block">
                                    <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i>
                                        18%</span>
                                    <h5 class="description-header">1200</h5>
                                    <span class="description-text">GOAL COMPLETIONS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
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
                                    <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Bàtons</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#sales-chart" data-toggle="tab">Circulaire</a>
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


    <script>
        // Assuming $performanceData is passed from the controller and contains the data
        // Initialize rawData with a placeholder or actual data from the controller
        const rawData = @json($currentYearPerformanceData);

        // Define colors outside of any function to make them globally accessible
        const colors = {
            'Recherche Google-ordinateur': {
                backgroundColor: 'rgb(31, 119, 180)',
                borderColor: 'rgb(31, 119, 180)',
            },
            'Google Maps-mobile': {
                backgroundColor: 'rgb(255, 127, 14)',
                borderColor: 'rgb(255, 127, 14)',
            },
            'Google Maps-ordinateur': {
                backgroundColor: 'rgb(44, 160, 44)',
                borderColor: 'rgb(44, 160, 44)',
            },
            'Recherche Google-mobile': {
                backgroundColor: 'rgb(214, 39, 40)',
                borderColor: 'rgb(214, 39, 40)'
            }
        };

        // A helper function to get the month name from a month index
        function getMonthName(month) {
            const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre",
                "Octobre", "Novembre", "Décembre"
            ];
            return monthNames[month - 1];
        }

        // Define the updateCharts function here to ensure it has access to all needed variables and functions
        function updateCharts(currentYearPerformanceData) {

            // Initialize objects to store the monthly totals for each category
            const monthlyTotals = {
                'Recherche Google-ordinateur': {},
                'Google Maps-mobile': {},
                'Google Maps-ordinateur': {},
                'Recherche Google-mobile': {}
            };

            // Process the performance data
            currentYearPerformanceData.currentYearData.multiDailyMetricTimeSeries.forEach(series => {
                series.dailyMetricTimeSeries.forEach(metricSeries => {
                    metricSeries.timeSeries.datedValues.forEach(datedValue => {
                        const year = datedValue.date.year;
                        const monthName = getMonthName(datedValue.date.month);
                        const metricName = {
                            'BUSINESS_IMPRESSIONS_DESKTOP_SEARCH': 'Recherche Google-ordinateur',
                            'BUSINESS_IMPRESSIONS_MOBILE_MAPS': 'Google Maps-mobile',
                            'BUSINESS_IMPRESSIONS_DESKTOP_MAPS': 'Google Maps-ordinateur',
                            'BUSINESS_IMPRESSIONS_MOBILE_SEARCH': 'Recherche Google-mobile'
                        } [metricSeries.dailyMetric];
                        // Initialize the year if it's not already in the monthlyTotals
                        if (!monthlyTotals[metricName][year]) {
                            monthlyTotals[metricName][year] = {};
                        }
                        // Initialize the month if it's not already in the monthlyTotals for the year
                        if (!monthlyTotals[metricName][year][monthName]) {
                            monthlyTotals[metricName][year][monthName] = 0;
                        }
                        monthlyTotals[metricName][year][monthName] += parseInt(datedValue.value ||
                            0);
                    });
                });
            });
            console.log('Monthly Totals:', monthlyTotals); // Log monthlyTotals to check its structure


            // Generate labels for the line chart
            const labels = [];
            Object.keys(monthlyTotals['Recherche Google-ordinateur']).forEach(year => {
                Object.keys(monthlyTotals['Recherche Google-ordinateur'][year]).forEach(month => {
                    labels.push(`${month} ${year}`);
                });
            });

            // Convert monthly totals to datasets format for Chart.js
            const datasets = Object.entries(monthlyTotals).map(([label, data]) => ({
                label,
                data: Object.values(data).flatMap(yearData => Object.values(yearData)),
                fill: false,
                ...colors[label],
            }));

            // Update the line chart
            myChart.data.labels = labels;
            myChart.data.datasets = datasets;
            myChart.update();

            //Update the sales
            // Initialize salesData object to store the sum of values for each category
            const salesData = {};

            // Process the performance data for each category
            Object.keys(monthlyTotals).forEach(category => {
                // Initialize the sum for the current category
                let sum = 0;
                // Iterate over each year and sum the values for the current category
                Object.values(monthlyTotals[category]).forEach(yearData => {
                    Object.values(yearData).forEach(monthValue => {
                        sum += monthValue;
                    });
                });
                // Store the sum for the current category
                salesData[category] = sum;
            });


            // Use the categories as labels for salesChart
            const salesLabels = Object.keys(monthlyTotals);

            // Update salesChart with the summed data
            salesChart.data.labels = salesLabels;
            salesChart.data.datasets = [{
                data: Object.values(salesData), // Extract values from salesData
                backgroundColor: [
                    'rgb(31, 119, 180)', // Dark blue
                    'rgb(255, 127, 14)', // Dark orange
                    'rgb(44, 160, 44)', // Dark green
                    'rgb(214, 39, 40)' // Dark red
                ],
                borderColor: [
                    'rgb(31, 119, 180)', // Dark blue
                    'rgb(255, 127, 14)', // Dark orange
                    'rgb(44, 160, 44)', // Dark green
                    'rgb(214, 39, 40)' // Dark red
                ],

                borderWidth: 1
            }];
            salesChart.update();


            console.log('Sales Data:', salesData); // Log salesData to check its contents


            // Initialize the total values for both years
            let currentYearTotal = 0;
            let lastYearTotal = 0;

            // Process the performance data for the current year
            currentYearPerformanceData.currentYearData.multiDailyMetricTimeSeries.forEach(series => {
                series.dailyMetricTimeSeries.forEach(metricSeries => {
                    metricSeries.timeSeries.datedValues.forEach(datedValue => {
                        currentYearTotal += parseInt(datedValue.value) || 0;
                    });
                });
            });

            // Process the performance data for the last year
            currentYearPerformanceData.lastYearData.multiDailyMetricTimeSeries.forEach(series => {
                series.dailyMetricTimeSeries.forEach(metricSeries => {
                    metricSeries.timeSeries.datedValues.forEach(datedValue => {
                        lastYearTotal += parseInt(datedValue.value) || 0;
                    });
                });
            });

            // Calculate the growth rate
            const growthRate = ((currentYearTotal - lastYearTotal) / lastYearTotal) * 100;

            // Sélectionner l'élément qui affiche le taux de croissance
            const growthRateDisplay = document.getElementById('growthRateDisplay');

            // Mettre à jour le texte et la classe en fonction du taux de croissance
            if (growthRate < 0) {
                growthRateDisplay.textContent = Math.abs(growthRate).toFixed(1);
                growthRateDisplay.classList.add('text-danger'); // Ajouter la classe pour afficher en rouge
                growthRateDisplay.innerHTML += '% <i class="fas fa-caret-down"></i>';
            } else {
                growthRateDisplay.textContent = growthRate.toFixed(1);
                growthRateDisplay.classList.remove('text-danger');
                growthRateDisplay.classList.add('text-success'); // Retirer la classe pour afficher en vert
                growthRateDisplay.innerHTML += '% <i class="fas fa-caret-up"></i>';
            }

            const Total = document.getElementById('currentYearTotal');
            Total.textContent = currentYearTotal;


        }

        // Render the charts
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('impressionsChart').getContext('2d');

            window.myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [], // Initialize with empty labels
                    datasets: [] // Initialize with empty datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            const salesCtx = document.getElementById('impressionsSales').getContext('2d');
            window.salesChart = new Chart(salesCtx, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: []
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                },
            });


            // Update charts with rawData or fetch new data as needed
            updateCharts(rawData);
        });
    </script>

@endsection
