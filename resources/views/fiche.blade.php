@extends('layouts.master')


@section('title')
    Fiche
@stop

@section('css')
    <!-- Include Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
    <style>
        /* Style pour activer visuellement le lien */
        .active-link {
            font-weight: bold;
            /* Par exemple, utilisez une police en gras */
            color: blue !important;
            /* Couleur du texte lorsque le lien est actif */
        }

        .star-icon {
            font-size: 20px;
            /* Changer la taille des étoiles */
            color: gold;
            /* Changer la couleur des étoiles */
        }

        .response-row td {
            width: 100% !important;
        }
    </style>
@endsection

@section('link1')
    <a href="javascript:void(0);" id="link1" class="nav-link active-link"
        onclick="toggleSection('section1', 'link1')">Perfermance</a>
@endsection

@section('link2')
    <a href="javascript:void(0);" id="link2" class="nav-link" onclick="toggleSection('section2', 'link2')">Reviews</a>
@endsection

@section('title_head')
    @if (request()->has('title'))
        {{ request()->get('title') }}
    @endif
@endsection


@section('title_page1')
    Établissement
@endsection

@section('title_page2')
    Statistiques
@endsection

@section('content')

    <div id="section1">
        @if (isset($currentYearPerformanceData) && isset($lastYearPerformanceData))
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
                                    <span class="description-percentage" id="growthRateDisplay"
                                        style="font-weight: normal;"></span>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>

                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-info-circle"></i>

                            </span>

                            <div class="info-box-content">
                                <span class="info-box-text">Interactions</span>
                                <span class="info-box-number">
                                    <span id="currentYearTotalInteraction"></span>
                                    <span class="description-percentage" id="growthRateInteraction"
                                        style="font-weight: normal;"></span>
                                </span>
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
                            <span class="info-box-icon bg-success elevation-1"><i
                                    class="fas fa-external-link-alt"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Clic Vers Site Web</span>
                                <span class="info-box-number">
                                    <span id="TotalWebInteraction"></span>
                                    <span class="description-percentage" id="growthRateWeb"
                                        style="font-weight: normal;"></span>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-book"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Réservation</span>
                                <span class="info-box-number">
                                    <span id="TotalConversationInteraction"></span>
                                    <span class="description-percentage" id="growthRateReservation"
                                        style="font-weight: normal;"></span>
                                </span>
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
                                <h5 class="card-title">Interactions avec la fiche de l'établissement
                                </h5>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
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
                                            <strong>Période: <span id="startDateDisplay"></span> - <span
                                                    id="endDateDisplay"></span></strong>
                                        </p>

                                        <div class="chart">
                                            <!-- Sales Chart Canvas -->
                                            <canvas id="InteractionChart" height="250" style="height: 250px;"></canvas>
                                        </div>
                                        <!-- /.chart-responsive -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-4">
                                        <p class="text-center">
                                            <strong>Statistiques des Interactions</strong>
                                        </p>

                                        <div class="progress-group">
                                            Clics vers le site Web effectués
                                            <span class="float-right"><b id="websiteClicksCount"></b>/<span
                                                    id="cartTotalInteraction1"></span></span>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-primary" id="websiteClicksProgressBar"
                                                    style="width: 0%"></div>
                                            </div>
                                        </div>
                                        <!-- /.progress-group -->

                                        <div class="progress-group">
                                            Appels effectués
                                            <span class="float-right"><b id="callsCount"></b>/<span
                                                    id="cartTotalInteraction2"></span></span>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-danger" id="callsProgressBar"
                                                    style="width: 0%" title=""></div>
                                            </div>
                                        </div>

                                        <!-- /.progress-group -->
                                        <div class="progress-group">
                                            Demandes d'itinéraire effectuées
                                            <span class="float-right"><b id="itineraireCount"></b>/
                                                <span id="cartTotalInteraction3"></span></span>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-warning" id="itineraireProgressBar"
                                                    style="width: 0%" title="">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- /.progress-group -->
                                        <div class="progress-group">
                                            Réservations effectuées
                                            <span class="float-right"><b id="reservationsCount"></b>/
                                                <span id="cartTotalInteraction4"></span></span>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-success" id="reservationsProgressBar"
                                                    style="width: 0%">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.progress-group -->

                                        <div class="progress-group">
                                            Messages envoyés
                                            <span class="float-right"><b id="messagesCount"></b>/
                                                <span id="cartTotalInteraction5"></span></span>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-danger" id="messagesProgressBar"
                                                    style="width: 0%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col -->

                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </div>
                            <!-- ./card-body -->
                            <div class="card-footer">
                                <div class="row justify-content-center text-center">
                                    <div class="col-sm-2 col-6">
                                        <div class="description-block border-right">
                                            <span class="description-percentage" data-interaction="WEBSITE_CLICKS"></span>
                                            <h5 class="description-header"></h5>
                                            <span class="description-text">Clic Web Total</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-2 col-6">
                                        <div class="description-block border-right">
                                            <span class="description-percentage" data-interaction="CALL_CLICKS"></span>
                                            <h5 class="description-header"></h5>
                                            <span class="description-text">Appel Total</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <div class="col-sm-2 col-6">
                                        <div class="description-block border-right">
                                            <span class="description-percentage"
                                                data-interaction="BUSINESS_DIRECTION_REQUESTS"></span>
                                            <h5 class="description-header"></h5>
                                            <span class="description-text">Itinéraire Total</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-2 col-6">
                                        <div class="description-block border-right">
                                            <span class="description-percentage"
                                                data-interaction="BUSINESS_BOOKINGS"></span>
                                            <h5 class="description-header"></h5>
                                            <span class="description-text">Réservation Total</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-2 col-6">
                                        <div class="description-block">
                                            <span class="description-percentage"
                                                data-interaction="BUSINESS_CONVERSATIONS"></span>
                                            <h5 class="description-header"></h5>
                                            <span class="description-text">Message Total</span>
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
                                    <div class="chart tab-pane" id="sales-chart"
                                        style="position: relative; height: 300px;">
                                        <canvas id="impressionsSales" height="300" style="height: 300px;"></canvas>
                                    </div>
                                </div>
                            </div><!-- /.card-body -->
                        </div>
                    </section>
                </div>




            </div>
        @endif

    </div>

    <div id="section2" style="display: none;">
        <div class="container-fluid">

            @if (!empty($allReviews))


                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <div class="info-box-content text-center">
                                <span class="info-box-text">Evaluation</span>
                                <span class="info-box-number">
                                    <span class="info-box-decimal">{{ round($averageRating, 1) }} / 5</span>
                                    @for ($i = 0; $i < 5; $i++)
                                        @if ($i < floor($averageRating))
                                            <i class="fas fa-star" style="color: gold;"></i>
                                        @elseif ($i - $averageRating < 0.5)
                                            <i class="fas fa-star-half-alt" style="color: gold;"></i>
                                        @else
                                            <i class="far fa-star" style="color: gold;"></i>
                                        @endif
                                    @endfor
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>

                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <div class="info-box-content text-center">
                                <span class="info-box-text">No. de commentaires</span>
                                <span class="info-box-number">
                                    {{ $totalReviewCount }}
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">

                            <div class="info-box-content text-center">
                                <span class="info-box-text">Pourcentage de réponse</span>
                                <span class="info-box-number">
                                    @php
                                        // Nombre total d'avis
$totalReviewsCount = count($allReviews);

// Nombre d'avis avec réponse
                                        $reviewsWithReplyCount = 0;
                                        foreach ($allReviews as $review) {
                                            if (
                                                isset($review['reviewReply']) &&
                                                isset($review['reviewReply']['comment'])
                                            ) {
                                                $reviewsWithReplyCount++;
                                            }
                                        }

                                        // Calcul du pourcentage de réponse
                                        $responsePercentage =
                                            $totalReviewsCount > 0
                                                ? ($reviewsWithReplyCount / $totalReviewsCount) * 100
                                                : 0;

                                        // Affichage du pourcentage de réponse
                                        echo round($responsePercentage, 2) . '%';
                                    @endphp
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">

                            <div class="info-box-content text-center">
                                <span class="info-box-text">Temps moyen de réponse</span>
                                <span class="info-box-number">
                                    @php
                                        $totalResponseTime = 0;
                                        $totalReviewsWithReply = 0;

                                        foreach ($allReviews as $review) {
                                            if (
                                                isset($review['reviewReply']) &&
                                                isset($review['createTime']) &&
                                                isset($review['reviewReply']['updateTime'])
                                            ) {
                                                $commentUpdateTime = strtotime($review['createTime']);
                                                $replyUpdateTime = strtotime($review['reviewReply']['updateTime']);
                                                $responseTime = $replyUpdateTime - $commentUpdateTime;
                                                $totalResponseTime += $responseTime;
                                                $totalReviewsWithReply++;
                                            }
                                        }

                                        // Calcul du temps moyen de réponse en secondes
                                        $averageResponseTimeInSeconds =
                                            $totalReviewsWithReply > 0
                                                ? $totalResponseTime / $totalReviewsWithReply
                                                : 0;

                                        // Convertir le temps moyen de réponse en heures, minutes et secondes
                                        $hours = floor($averageResponseTimeInSeconds / 3600);
                                        $minutes = floor(($averageResponseTimeInSeconds % 3600) / 60);
                                        $seconds = $averageResponseTimeInSeconds % 60;

                                        // Affichage du temps moyen de réponse
                                        echo $hours . ' H' . ' ' . $minutes . ' M';

                                    @endphp
                                </span>

                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <table id="reviewsTable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Evaluation</th>
                            <th>Date</th>
                            <th>Réviseur</th>
                            <th>Commentaire</th>
                            <th>Répondre</th>
                            <th>Temps de réponse</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allReviews as $review)
                            <tr class="review-row" data-review-id="{{ $review['reviewId'] }}">
                                <td>
                                    @php
                                        $starRating = $review['starRating'];
                                        $starIcons = '';

                                        switch ($starRating) {
                                            case 'ONE':
                                                $starIcons = '★☆☆☆☆';
                                                break;
                                            case 'TWO':
                                                $starIcons = '★★☆☆☆';
                                                break;
                                            case 'THREE':
                                                $starIcons = '★★★☆☆';
                                                break;
                                            case 'FOUR':
                                                $starIcons = '★★★★☆';
                                                break;
                                            case 'FIVE':
                                                $starIcons = '★★★★★';
                                                break;
                                            default:
                                                $starIcons = 'No rating';
                                        }
                                    @endphp
                                    <span class="star-icon">{{ $starIcons }}</span>
                                </td>
                                <td>
                                    @php
                                        $dateTime = new DateTime($review['createTime']);
                                        $formattedDateTime = $dateTime->format('Y-m-d H:i:s'); // Format de date et heure
                                    @endphp
                                    {{ $formattedDateTime }}
                                </td>
                                <td>
                                    <img src="{{ $review['reviewer']['profilePhotoUrl'] }}"
                                        alt="{{ $review['reviewer']['displayName'] }} profile photo" class="reviewer-icon"
                                        width="50" height="50">
                                    {{ $review['reviewer']['displayName'] }}
                                </td>
                                <td>
                                    @if (isset($review['comment']))
                                        {!! nl2br(explode('(Translated by Google)', $review['comment'])[0] ?? $review['comment']) !!}
                                    @else
                                        Pas de commentaire disponible
                                    @endif
                                </td>
                                <td>
                                    @if (isset($review['reviewReply']) && isset($review['reviewReply']['comment']))
                                        {!! nl2br(
                                            explode('(Translated by Google)', $review['reviewReply']['comment'])[0] ?? $review['reviewReply']['comment'],
                                        ) !!}
                                    @else
                                        Pas de réponse disponible
                                    @endif
                                </td>





                                <td>
                                    @if (isset($review['reviewReply']) && isset($review['reviewReply']['updateTime']))
                                        @php
                                            $commentUpdateTime = strtotime($review['createTime']);
                                            $replyUpdateTime = strtotime($review['reviewReply']['updateTime']);
                                            $responseTime = $replyUpdateTime - $commentUpdateTime;
                                            $responseHours = floor($responseTime / 3600);
                                            $responseMinutes = floor(($responseTime % 3600) / 60);
                                            echo $responseHours . ' heures et ' . $responseMinutes . ' minutes';
                                        @endphp
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Aucun avis disponible pour le moment.</p>
            @endif
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
        function updateCharts(PerformanceData) {

            // Initialize objects to store the monthly totals for each category
            const monthlyTotals = {
                'Recherche Google-ordinateur': {},
                'Google Maps-mobile': {},
                'Google Maps-ordinateur': {},
                'Recherche Google-mobile': {}
            };

            // Process the performance data
            PerformanceData.currentYearData.multiDailyMetricTimeSeries.forEach(series => {
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

                        // Check if metricName is undefined
                        if (metricName === undefined) {
                            //  console.warn(`Unknown dailyMetric: ${metricSeries.dailyMetric}`);
                            return; // Skip this iteration
                        }

                        // Initialize the year if it's not already in the monthlyTotals
                        if (!monthlyTotals[metricName]) {
                            monthlyTotals[metricName] = {};
                        }
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



            // Generate labels for the line chart
            const labels = [];
            Object.keys(monthlyTotals['Recherche Google-ordinateur']).forEach(year => {
                Object.keys(monthlyTotals['Recherche Google-ordinateur'][year]).forEach(month => {
                    labels.push(`${month} ${year}`);
                });
            });
            console.log(monthlyTotals);

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

            // Appeler cette fonction pour calculer la somme des données d'interaction
            const interactionData = calculateInteractionTotal(PerformanceData);

            // Mettre à jour les données et les étiquettes du graphique d'interaction
            //   const interactionLabels = Object.keys(interactionData);
            const newLabels = ['Clics vers site Web', 'Appels', 'Itinéraire', 'Réservation',
                'Message'
            ]; // Example new labels
            const interactionDataset = [{
                label: 'Interaction',
                data: Object.values(interactionData),
                fill: false,
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Couleur de fond pour la ligne
                borderColor: 'rgba(75, 192, 192, 1)', // Couleur de la bordure de la ligne
                borderWidth: 2
            }];
            InteractionChart.data.labels = newLabels;
            InteractionChart.data.datasets = interactionDataset;
            InteractionChart.update();
            // Rate to Imression data
            let currentYearTotal = 0;
            let lastYearTotal = 0;

            // Process the performance data for the current year
            PerformanceData.currentYearData.multiDailyMetricTimeSeries.forEach(series => {
                series.dailyMetricTimeSeries.forEach(metricSeries => {
                    if (['BUSINESS_IMPRESSIONS_DESKTOP_SEARCH', 'BUSINESS_IMPRESSIONS_MOBILE_MAPS',
                            'BUSINESS_IMPRESSIONS_DESKTOP_MAPS', 'BUSINESS_IMPRESSIONS_MOBILE_SEARCH'
                        ].includes(metricSeries.dailyMetric)) {
                        metricSeries.timeSeries.datedValues.forEach(datedValue => {
                            currentYearTotal += parseInt(datedValue.value) || 0;
                        });
                    }
                });
            });

            // Process the performance data for the last year
            PerformanceData.lastYearData.multiDailyMetricTimeSeries.forEach(series => {
                series.dailyMetricTimeSeries.forEach(metricSeries => {
                    if (['BUSINESS_IMPRESSIONS_DESKTOP_SEARCH', 'BUSINESS_IMPRESSIONS_MOBILE_MAPS',
                            'BUSINESS_IMPRESSIONS_DESKTOP_MAPS', 'BUSINESS_IMPRESSIONS_MOBILE_SEARCH'
                        ].includes(metricSeries.dailyMetric)) {
                        metricSeries.timeSeries.datedValues.forEach(datedValue => {
                            lastYearTotal += parseInt(datedValue.value) || 0;
                        });
                    }
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

            // Rate to Interaction data
            // Initialize variables to store the total interactions for the current and last year
            let currentYearInteractionTotal = 0;
            let lastYearInteractionTotal = 0;

            // Define the interaction metrics
            const interactionMetrics = ["WEBSITE_CLICKS", "CALL_CLICKS", "BUSINESS_DIRECTION_REQUESTS", "BUSINESS_BOOKINGS",
                "BUSINESS_CONVERSATIONS"
            ];

            // Process the performance data for the current year
            PerformanceData.currentYearData.multiDailyMetricTimeSeries.forEach(series => {
                series.dailyMetricTimeSeries.forEach(metricSeries => {
                    if (interactionMetrics.includes(metricSeries.dailyMetric)) {
                        metricSeries.timeSeries.datedValues.forEach(datedValue => {
                            currentYearInteractionTotal += parseInt(datedValue.value) || 0;
                        });
                    }
                });
            });

            // Process the performance data for the last year
            PerformanceData.lastYearData.multiDailyMetricTimeSeries.forEach(series => {
                series.dailyMetricTimeSeries.forEach(metricSeries => {
                    if (interactionMetrics.includes(metricSeries.dailyMetric)) {
                        metricSeries.timeSeries.datedValues.forEach(datedValue => {
                            lastYearInteractionTotal += parseInt(datedValue.value) || 0;
                        });
                    }
                });
            });

            // Calculate the growth rate for interactions
            const interactionGrowthRate = ((currentYearInteractionTotal - lastYearInteractionTotal) /
                lastYearInteractionTotal) * 100;

            // Select the element to display the interaction growth rate
            const growthRateInteractionDisplay = document.getElementById('growthRateInteraction');

            // Update the text and class based on the growth rate of interactions
            if (interactionGrowthRate < 0) {
                growthRateInteractionDisplay.textContent = Math.abs(interactionGrowthRate).toFixed(1);
                growthRateInteractionDisplay.classList.add('text-danger'); // Add class to display in red
                growthRateInteractionDisplay.innerHTML += '% <i class="fas fa-caret-down"></i>';
            } else if (interactionGrowthRate > 0) {
                growthRateInteractionDisplay.textContent = interactionGrowthRate.toFixed(1);
                growthRateInteractionDisplay.classList.remove('text-danger');
                growthRateInteractionDisplay.classList.add('text-success'); // Remove class to display in green
                growthRateInteractionDisplay.innerHTML += '% <i class="fas fa-caret-up"></i>';
            } else {
                growthRateInteractionDisplay.textContent = '0';
                growthRateInteractionDisplay.classList.add('text-warning');
                growthRateInteractionDisplay.innerHTML += '% <i class="fas fa-caret-left"></i>';

            }

            // Select the element to display the total interactions for the current year
            const totalInteractionElement = document.getElementById('currentYearTotalInteraction');
            totalInteractionElement.textContent = currentYearInteractionTotal;

            // Sélectionner tous les éléments avec l'ID 'cartTotalInteraction' suivi d'un numéro
            for (let i = 1; i <= 5; i++) {
                const cartTotalInteractionElement = document.getElementById(`cartTotalInteraction${i}`);
                cartTotalInteractionElement.textContent = currentYearInteractionTotal;
            }



            // Sélectionner tous les éléments où vous voulez afficher les totaux d'interaction
            const interactionElements = document.querySelectorAll('.progress-group b');

            // Mettre à jour le contenu de chaque élément avec les totaux d'interaction correspondants
            interactionElements.forEach((element, index) => {
                // Le tableau interactionMetrics contient les noms des interactions dans le même ordre que les éléments
                const interactionMetric = interactionMetrics[index];
                // Mettre à jour le contenu de l'élément avec le total d'interaction correspondant
                element.textContent = interactionData[interactionMetric];
            });

            const interactionPercentage = calculateInteractionPercentage(PerformanceData, currentYearInteractionTotal);

            // Mettre à jour la barre de progression des clics vers le site Web
            const websiteClicksProgressBar = document.getElementById('websiteClicksProgressBar');
            websiteClicksProgressBar.style.width = interactionPercentage.WEBSITE_CLICKS + '%';
            websiteClicksProgressBar.innerText = interactionPercentage.WEBSITE_CLICKS + '%';
            websiteClicksProgressBar.title = interactionPercentage.WEBSITE_CLICKS + '%';



            // Mettre à jour la barre de progression des appels effectués
            const callsProgressBar = document.getElementById('callsProgressBar');
            callsProgressBar.style.width = interactionPercentage.CALL_CLICKS + '%';
            callsProgressBar.innerText = interactionPercentage.CALL_CLICKS + '%';
            callsProgressBar.title = interactionPercentage.CALL_CLICKS + '%';


            // Mettre à jour la barre de progression des demandes d'itinéraire effectuées
            const itineraireProgressBar = document.getElementById('itineraireProgressBar');
            const itinerairePercentage = interactionPercentage.BUSINESS_DIRECTION_REQUESTS;
            itineraireProgressBar.style.width = itinerairePercentage + '%';
            itineraireProgressBar.innerText = itinerairePercentage + '%';
            itineraireProgressBar.title = itinerairePercentage + '%'; // Ajouter le pourcentage en tant que tooltip (hover)

            // Mettre à jour la barre de progression des réservations effectuées
            const reservationsProgressBar = document.getElementById('reservationsProgressBar');
            reservationsProgressBar.style.width = interactionPercentage.BUSINESS_BOOKINGS + '%';
            reservationsProgressBar.innerText = interactionPercentage.BUSINESS_BOOKINGS + '%';
            reservationsProgressBar.title = interactionPercentage.BUSINESS_BOOKINGS + '%';


            // Mettre à jour la barre de progression des messages envoyés
            const messagesProgressBar = document.getElementById('messagesProgressBar');
            messagesProgressBar.style.width = interactionPercentage.BUSINESS_CONVERSATIONS + '%';
            messagesProgressBar.innerText = interactionPercentage.BUSINESS_CONVERSATIONS + '%';
            messagesProgressBar.title = interactionPercentage.BUSINESS_CONVERSATIONS + '%';

            // Sélectionner tous les éléments où vous voulez afficher les totaux d'interaction
            const h5Elements = document.querySelectorAll('.description-header');

            // Mettre à jour le contenu de chaque élément avec les totaux d'interaction correspondants
            h5Elements.forEach((element, index) => {
                // Le tableau interactionMetrics contient les noms des interactions dans le même ordre que les éléments
                const interactionMetric = interactionMetrics[index];
                // Mettre à jour le contenu de l'élément avec le total d'interaction correspondant
                element.textContent = interactionData[interactionMetric];
            });

            const InteractionGrowthRate = calculateInteractionGrowthRate(PerformanceData);
            console.log(InteractionGrowthRate);
            // Sélectionner les éléments où vous souhaitez afficher les données de croissance
            const growthElements = document.querySelectorAll('.card-footer .description-percentage');

            // Mettre à jour le contenu de chaque élément avec les données de croissance correspondantes
            Object.entries(InteractionGrowthRate).forEach(([interaction, percentage]) => {
                const growthElement = Array.from(growthElements).find(element => {
                    return element.dataset.interaction === interaction;
                });

                if (growthElement) {
                    if (isNaN(parseFloat(percentage))) {
                        growthElement.textContent = 'N/A'; // Afficher N/A si la valeur n'est pas un nombre
                        growthElement.classList.add('text-warning'); // Ajouter la classe pour afficher en vert

                    } else {
                        const growthRate = parseFloat(percentage);
                        if (growthRate < 0) {
                            growthElement.innerHTML =
                                `<i class="fas fa-caret-down"></i> ${Math.abs(growthRate).toFixed(1)}%`;
                            growthElement.classList.add(
                                'text-danger'); // Ajouter la classe pour afficher en orange
                        } else if (growthRate > 0) {
                            growthElement.innerHTML =
                                `<i class="fas fa-caret-up"></i> ${Math.abs(growthRate).toFixed(1)}%`;
                            growthElement.classList.remove('text-danger');
                            growthElement.classList.add('text-success'); // Ajouter la classe pour afficher en vert
                        } else {
                            growthElement.textContent = '0%';
                            growthElement.classList.add('text-warning'); // Ajouter la classe pour afficher en vert
                        }
                    }
                }

            });
            // statistique de Clic Vers Site Web
            const growthRateWebElement = document.getElementById('growthRateWeb');

            // Vérifier si la croissance des clics vers le site web est disponible dans InteractionGrowthRate
            if (InteractionGrowthRate.hasOwnProperty('WEBSITE_CLICKS')) {
                const growthRate = InteractionGrowthRate['WEBSITE_CLICKS'];
                if (!isNaN(parseFloat(growthRate))) {
                    // Si le taux de croissance est un nombre
                    if (growthRate < 0) {
                        growthRateWebElement.innerHTML =
                            `<i class="fas fa-caret-down"></i> ${Math.abs(growthRate).toFixed(1)}%`;
                        growthRateWebElement.classList.add('text-danger');
                    } else if (growthRate > 0) {
                        growthRateWebElement.innerHTML =
                            `<i class="fas fa-caret-up"></i> ${Math.abs(growthRate).toFixed(1)}%`;
                        growthRateWebElement.classList.remove('text-danger');
                        growthRateWebElement.classList.add('text-success');
                    } else {
                        growthRateWebElement.textContent = '0%';
                    }
                } else {
                    // Si la croissance n'est pas un nombre
                    growthRateWebElement.textContent = 'N/A';
                    growthRateWebElement.classList.add('text-warning');
                }
            } else {
                // Si les données de croissance pour les clics vers le site web ne sont pas disponibles
                growthRateWebElement.textContent = 'N/A';
                growthRateWebElement.classList.add('text-warning');
            }

            const totalWebInteractionElement = document.getElementById('TotalWebInteraction');

            // Vérifier si le total des interactions avec le site web est disponible dans interactionData
            if (interactionData.hasOwnProperty('WEBSITE_CLICKS')) {
                const totalWebInteraction = interactionData['WEBSITE_CLICKS'];
                // Mettre à jour le contenu avec le total des interactions avec le site web
                totalWebInteractionElement.textContent = totalWebInteraction;
            } else {
                // Si les données du total des interactions avec le site web ne sont pas disponibles
                totalWebInteractionElement.textContent = 'N/A';
                totalWebInteractionElement.classList.add('text-warning');
            }
            // statistique des Reservation
            // Statistiques des Conversations Commerciales
            const growthRateReservationElement = document.getElementById('growthRateReservation');

            // Vérifier si la croissance des conversations commerciales est disponible dans InteractionGrowthRate
            if (InteractionGrowthRate.hasOwnProperty('BUSINESS_BOOKINGS')) {
                const growthRate = InteractionGrowthRate['BUSINESS_BOOKINGS'];
                if (!isNaN(parseFloat(growthRate))) {
                    // Si le taux de croissance est un nombre
                    if (growthRate < 0) {
                        growthRateReservationElement.innerHTML =
                            `<i class="fas fa-caret-down"></i> ${Math.abs(growthRate).toFixed(1)}%`;
                        growthRateReservationElement.classList.add('text-danger');
                    } else if (growthRate > 0) {
                        growthRateReservationElement.innerHTML =
                            `<i class="fas fa-caret-up"></i> ${Math.abs(growthRate).toFixed(1)}%`;
                        growthRateReservationElement.classList.remove('text-danger');
                        growthRateReservationElement.classList.add('text-success');
                    } else {
                        growthRateReservationElement.textContent = '0%';
                    }
                } else {
                    // Si la croissance n'est pas un nombre
                    growthRateReservationElement.textContent = 'N/A';
                    growthRateReservationElement.classList.add('text-warning');
                }
            } else {
                // Si les données de croissance pour les conversations commerciales ne sont pas disponibles
                growthRateReservationElement.textContent = 'N/A';
                growthRateReservationElement.classList.add('text-warning');
            }

            // Total des Interactions Commerciales avec le Site Web
            const totalConversationInteractionElement = document.getElementById('TotalConversationInteraction');

            // Vérifier si le total des interactions commerciales avec le site web est disponible dans interactionData
            if (interactionData.hasOwnProperty('BUSINESS_BOOKINGS')) {
                const totalConversationInteraction = interactionData['BUSINESS_BOOKINGS'];
                // Mettre à jour le contenu avec le total des interactions commerciales avec le site web
                totalConversationInteractionElement.textContent = totalConversationInteraction;
            } else {
                // Si les données du total des interactions commerciales avec le site web ne sont pas disponibles
                totalConversationInteractionElement.textContent = 'N/A';
                totalConversationInteractionElement.classList.add('text-warning');
            }



        }

        // Ajouter cette fonction pour calculer la somme des données d'interaction
        function calculateInteractionTotal(PerformanceData) {
            const interactionMetrics = ["WEBSITE_CLICKS", "CALL_CLICKS", "BUSINESS_DIRECTION_REQUESTS", "BUSINESS_BOOKINGS",
                "BUSINESS_CONVERSATIONS"
            ];
            const interactionData = {};

            interactionMetrics.forEach(metric => {
                let sum = 0;
                PerformanceData.currentYearData.multiDailyMetricTimeSeries.forEach(series => {
                    series.dailyMetricTimeSeries.forEach(metricSeries => {
                        if (metricSeries.dailyMetric === metric) {
                            metricSeries.timeSeries.datedValues.forEach(datedValue => {
                                sum += parseInt(datedValue.value) || 0;
                            });
                        }
                    });
                });
                interactionData[metric] = sum;
            });

            return interactionData;
        }

        // Add this function to calculate the percentage of each interaction type relative to the currentYearInteractionTotal
        function calculateInteractionPercentage(PerformanceData, currentYearInteractionTotal) {
            const interactionMetrics = ["WEBSITE_CLICKS", "CALL_CLICKS", "BUSINESS_DIRECTION_REQUESTS", "BUSINESS_BOOKINGS",
                "BUSINESS_CONVERSATIONS"
            ];
            const interactionData = {};

            interactionMetrics.forEach(metric => {
                let sum = 0;
                PerformanceData.currentYearData.multiDailyMetricTimeSeries.forEach(series => {
                    series.dailyMetricTimeSeries.forEach(metricSeries => {
                        if (metricSeries.dailyMetric === metric) {
                            metricSeries.timeSeries.datedValues.forEach(datedValue => {
                                sum += parseInt(datedValue.value) || 0;
                            });
                        }
                    });
                });
                // Calculate the percentage
                const percentage = (sum / currentYearInteractionTotal) * 100;
                interactionData[metric] = percentage.toFixed(2); // Round to 2 decimal places
            });

            return interactionData;
        }

        // Ajouter cette fonction pour calculer le taux de croissance entre l'année en cours et l'année précédente pour chaque type d'interaction
        function calculateInteractionGrowthRate(PerformanceData) {
            const interactionMetrics = ["WEBSITE_CLICKS", "CALL_CLICKS", "BUSINESS_DIRECTION_REQUESTS", "BUSINESS_BOOKINGS",
                "BUSINESS_CONVERSATIONS"
            ];
            const interactionGrowthRates = {};

            // Parcourir les métriques d'interaction
            interactionMetrics.forEach(metric => {
                // Initialiser les totaux pour l'année en cours et l'année précédente
                let currentYearTotal = 0;
                let lastYearTotal = 0;

                // Calculer le total pour l'année en cours
                PerformanceData.currentYearData.multiDailyMetricTimeSeries.forEach(series => {
                    series.dailyMetricTimeSeries.forEach(metricSeries => {
                        if (metricSeries.dailyMetric === metric) {
                            metricSeries.timeSeries.datedValues.forEach(datedValue => {
                                currentYearTotal += parseInt(datedValue.value) || 0;
                            });
                        }
                    });
                });

                // Calculer le total pour l'année précédente
                PerformanceData.lastYearData.multiDailyMetricTimeSeries.forEach(series => {
                    series.dailyMetricTimeSeries.forEach(metricSeries => {
                        if (metricSeries.dailyMetric === metric) {
                            metricSeries.timeSeries.datedValues.forEach(datedValue => {
                                lastYearTotal += parseInt(datedValue.value) || 0;
                            });
                        }
                    });
                });

                // Calculer le taux de croissance
                const growthRate = ((currentYearTotal - lastYearTotal) / lastYearTotal) * 100;
                interactionGrowthRates[metric] = growthRate.toFixed(1); // Arrondir à une décimale

            });

            return interactionGrowthRates;
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

            const InteractionCtx = document.getElementById('InteractionChart').getContext('2d');
            window.InteractionChart = new Chart(InteractionCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: []
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                    }
                },
            });

            // Update charts with rawData or fetch new data as needed
            updateCharts(rawData);
        });
    </script>

    <script>
        // Fonction pour basculer entre les sections et activer le lien correspondant
        function toggleSection(sectionId, linkId) {
            // Masquer toutes les sections
            var sections = document.querySelectorAll('[id^="section"]');
            sections.forEach(function(section) {
                section.style.display = 'none';
            });

            // Afficher la section spécifiée
            document.getElementById(sectionId).style.display = 'block';

            // Supprimer la classe active-link de tous les liens
            var links = document.querySelectorAll('.nav-link');
            links.forEach(function(link) {
                link.classList.remove('active-link');
            });

            // Ajouter la classe active-link au lien cliqué
            document.getElementById(linkId).classList.add('active-link');
        }
    </script>


    <!-- Include Bootstrap 4 JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#reviewsTable').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "order": [
                    [1, "desc"]
                ], // Sort by the second column (index 1) in descending order
                "initComplete": function(settings, json) {
                    // Add event listener for showing child rows
                    $('#reviewsTable tbody').on('click', 'tr.review-row', function() {
                        var tr = $(this);
                        var row = table.row(tr);

                        // Check if the response is available
                        var responseAvailable = tr.find('td:nth-child(5)').text().trim() ==
                            'Pas de réponse disponible';

                        if (row.child.isShown()) {
                            // This row is already open - close it
                            row.child.hide();
                            tr.removeClass('shown');
                        } else if (responseAvailable) {
                            // Close other response rows
                            $('#reviewsTable tbody tr.response-row').hide();
                            $('.review-row.shown').removeClass('shown');
                            // Open this row
                            row.child(format(tr.data('review-id')), 'response-row').show();
                            tr.addClass('shown');
                        }
                    });
                }
            });

            function format(reviewId) {
                // Example of generating the form dynamically
                var formHtml =
                    '<form class="response-form" data-review-id="' + reviewId + '">' +
                    '<div class="form-group">' +
                    '<label for="response">Votre réponse :</label>' +
                    '<textarea class="form-control" name="response" rows="3" maxlength="4000" required></textarea>' +
                    '<small class="text-muted"><span id="char-count">0</span>/4000 </small>' +
                    '</div>' +
                    '<div class="form-group btn-group">' +
                    '<button type="submit" class="btn btn-primary">Répondre</button>' +
                    '<button type="button" class="btn btn-secondary cancel-response">Annuler</button>' +
                    '</div>' +
                    '</form>';
                return formHtml;
            }

            // Event handler for character count update
            $('#reviewsTable').on('input', '.response-form textarea', function() {
                var charCount = $(this).val().length;
                $(this).closest('.response-form').find("#char-count").text(charCount);
            });

            // Event handler for cancel response button
            $('#reviewsTable').on('click', '.cancel-response', function() {
                $(this).closest(".response-row").hide();
            });

            $('#reviewsTable tbody').on('click', 'tr.review-row', function() {
                var tr = $(this);
                var reviewId = tr.data('review-id'); // Directly retrieve the data attribute

                if (typeof reviewId !== 'undefined') {
                    // Proceed with your logic, e.g., AJAX request
                    console.log('Review ID when click:', reviewId);
                    // Assuming you have a form element in your format function
                    var formHtml = format(reviewId);
                    // Store the reviewId in the form's data attribute
                    $(formHtml).data('review-id', reviewId);
                } else {
                    console.error('Review ID is undefined');
                }
            });

            // Event handler for response form submission
            $('#reviewsTable').on('submit', '.response-form', function(event) {
                event.preventDefault(); // Prevent default form submission

                // Use the reviewId variable obtained from the click event handler
                var reviewId = $(this).data('review-id');

                var responseText = $(this).find('textarea[name="response"]').val();

                // Assuming you have the locationId and accessToken available
                var locationId = '{{ $id }}'; // Retrieve locationId from the controller
                var accessToken = '{{ $token }}'; // Retrieve accessToken from the controller

                // Prepare the request data
                var requestData = {
                    "comment": responseText // Use the responseText as the comment for the reply
                };

                // Send a PUT request to the GMB API
                $.ajax({
                    url: 'https://mybusiness.googleapis.com/v4/accounts/110996943980669817062/locations/' +
                        locationId + '/reviews/' + reviewId + '/reply',
                    type: 'PUT', // Corrected to uppercase 'PUT'
                    headers: {
                        'Authorization': 'Bearer ' + accessToken,
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify(requestData), // Correctly stringify the requestData
                    success: function(response) {
                        console.log('Response posted successfully:', response);
                        // Optionally hide the response form and display a success message
                        $(this).closest('.response-row').hide();
                    }.bind(this), // Ensure 'this' refers to the form
                    error: function(xhr, status, error) {
                        console.error('Error posting the response:', error);
                        // Optionally display an error message or perform other actions
                    }
                });
            });
        });
    </script>



@endsection
