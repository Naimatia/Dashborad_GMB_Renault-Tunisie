@extends('layouts.master')


@section('title')
    Home
@stop

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

@endsection

@section('title_head')
    Bienvenue
@endsection

@section('title_page1')
    home
@endsection

@section('title_page2')
@endsection

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif

    <a href="{{ url('/auth/google') }}">Login with Google</a>

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3> {{ count($coordinates) }} </h3>
                        <p>Établissement</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <a href="{{ route('Établissements') }}" class="small-box-footer">Plus d'info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $verifiedCount }} / {{ count($coordinates) }} </h3>

                        <p>Vérifier</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="{{ route('Établissements') }}" class="small-box-footer">Plus d'info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="totalInteractionSum"></h3>
                        <p>Interaction Totales</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <a href="{{ route('Établissements') }}" class="small-box-footer">Plus d'info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="totalImpressionsSum"></h3>

                        <p>Consultations Totales</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <a href="{{ route('Établissements') }}" class="small-box-footer">Plus d'info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <div class="col-md-6">
                <!-- MAP & BOX PANE -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Les coordonnées géographiques des Établissements.</h3>

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
                    <div class="card-body p-0">
                        <div class="d-md-flex">
                            <div class="p-1 flex-fill" style="overflow: hidden">
                                <!-- Map will be created here -->
                                <div style="height: 400px width: 200Px; overflow: hidden">
                                    <div id="map" style="height: 500px;"></div>
                                </div>
                            </div>
                        </div><!-- /.d-md-flex -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- Right col -->
            <div class="col-md-6">
                <div class="card bg-gradient-info">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-th mr-1"></i>
                            Interaction Graphique
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas class="chart" id="line-chart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <div class="card bg-gradient-info">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-th mr-1"></i>
                            Consultation Graphique
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas class="chart" id="conseltation-chart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>

        <!-- /.row -->


    </div><!-- /.container-fluid -->
    <!-- /.content -->

@endsection


@section('scripts')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Incluez les scripts et styles de Leaflet -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        // Initialisez la carte
        var map = L.map('map').setView([33.8869, 9.5375], 6); // coordonnées pour centrer sur la Tunisie

        // Ajoutez un fond de carte (vous pouvez choisir un autre style)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        // Créez un bouton pour centrer la carte sur la position initiale
        var centerButton = L.control({
            position: 'topright'
        });

        centerButton.onAdd = function() {
            var div = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
            div.innerHTML = 'Centre'; // Texte du bouton (vous pouvez le personnaliser)
            div.style.backgroundColor = '#fff';
            div.style.padding = '5px';
            div.style.cursor = 'pointer';
            div.style.borderRadius = '5px';

            // Ajoutez un événement de clic pour centrer la carte sur la position initiale
            div.onclick = function() {
                map.setView([33.8869, 9.5375], 6); // Coordonnées pour centrer sur la Tunisie
            };

            return div;
        };

        // Ajoutez le bouton à la carte
        centerButton.addTo(map);
        var coordinates = @json($coordinates); // Passez les données PHP à JavaScript


        // Ajoutez les marqueurs pour chaque établissement
        coordinates.forEach(function(location) {
            if (location.latlng && location.latlng.latitude !== null && location.latlng.longitude !== null) {
                // Créez un marqueur pour chaque établissement
                var marker = L.marker([location.latlng.latitude, location.latlng.longitude]).addTo(map);

                // Affichez le titre dans une popup lorsque la souris passe sur le marqueur
                marker.bindTooltip(location.title, {
                    permanent: false,
                    direction: 'top',
                    offset: [0, -10] // Ajustez la position de la popup si nécessaire
                });

                // Ajoutez un événement de souris pour afficher et cacher la popup
                marker.on('mouseover', function() {
                    marker.openTooltip();
                });

                marker.on('mouseout', function() {
                    marker.closeTooltip();
                });
            }
        });
    </script>



    <script>


function calculateImpressionsSum(rawData) {
    // Initialiser les sommes des métriques d'impressions
    const sums = {
        'BUSINESS_IMPRESSIONS_DESKTOP_SEARCH': 0,
        'BUSINESS_IMPRESSIONS_MOBILE_MAPS': 0,
        'BUSINESS_IMPRESSIONS_DESKTOP_MAPS': 0,
        'BUSINESS_IMPRESSIONS_MOBILE_SEARCH': 0
    };

    // Initialiser les données pour le graphique
    const graphConseltationData = [];

    // Parcourir toutes les localisations dans rawData
    for (const locationId in rawData.performanceData) {
        const locationData = rawData.performanceData[locationId];

        // Vérifier si les données de l'année actuelle existent
        if (locationData && locationData.performance && locationData.performance.original && locationData.performance.original.currentYearData) {
            const currentYearData = locationData.performance.original.currentYearData;

            // Initialiser les sommes pour chaque localité
            const titleSums = {
                'BUSINESS_IMPRESSIONS_DESKTOP_SEARCH': 0,
                'BUSINESS_IMPRESSIONS_MOBILE_MAPS': 0,
                'BUSINESS_IMPRESSIONS_DESKTOP_MAPS': 0,
                'BUSINESS_IMPRESSIONS_MOBILE_SEARCH': 0
            };

            // Vérifier s'il y a des `multiDailyMetricTimeSeries`
            if (currentYearData.multiDailyMetricTimeSeries) {
                for (const metricData of currentYearData.multiDailyMetricTimeSeries) {
                    for (const metricTimeSeries of metricData.dailyMetricTimeSeries) {
                        const { dailyMetric, timeSeries } = metricTimeSeries;

                        // Vérifier si la métrique est pertinente
                        if (sums.hasOwnProperty(dailyMetric) && timeSeries && timeSeries.datedValues) {
                            for (const datedValue of timeSeries.datedValues) {
                                const value = parseFloat(datedValue.value);
                                if (!isNaN(value)) {
                                    // Ajouter la valeur à la somme appropriée
                                    sums[dailyMetric] += value;
                                    titleSums[dailyMetric] += value;
                                }
                            }
                        }
                    }
                }
            }

            // Ajouter les données de titre et les sommes au graphique
            graphConseltationData.push({
                title: locationData.title,
                sums: titleSums
            });
        }
    }

    // Calculer la somme totale de toutes les impressions
    const totalImpressionsSum = Object.values(sums).reduce((acc, value) => acc + value, 0);

    // Retourner l'objet contenant la somme totale et les données pour le graphique
    return { totalImpressionsSum, graphConseltationData };
}
function displayConseltationGraph(graphData) {
    // Préparer les labels et les datasets pour le graphique
    const labels = graphData.map(data => data.title);
    const dataset = {
        label: 'Consultation Totales',
        data: graphData.map(data => {
            // Calculer la somme totale des impressions pour chaque localité
            return data.sums.BUSINESS_IMPRESSIONS_DESKTOP_SEARCH +
                   data.sums.BUSINESS_IMPRESSIONS_MOBILE_MAPS +
                   data.sums.BUSINESS_IMPRESSIONS_DESKTOP_MAPS +
                   data.sums.BUSINESS_IMPRESSIONS_MOBILE_SEARCH;
        }),
        fill: false,
        borderColor: 'white', // Changer la couleur de la ligne du graphique en blanc
        tension: 0.1
    };

    // Récupérer le contexte du canevas
    const ctx = document.getElementById('conseltation-chart').getContext('2d');

    // Créer le graphique linéaire
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [dataset]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: 'white' // Changer la couleur du titre du label en blanc
                    }
                }
            }
        }
    });
}

function calculateSumOfValues(rawData) {
    // Initialiser les sommes des métriques
    const sums = {
        CALL_CLICKS: 0,
        WEBSITE_CLICKS: 0,
        BUSINESS_CONVERSATIONS: 0,
        BUSINESS_DIRECTION_REQUESTS: 0,
        BUSINESS_BOOKINGS: 0
    };

    // Initialiser les données pour le graphique
    const graphInteractionData = [];

    // Parcourir toutes les localisations dans rawData
    for (const locationId in rawData.performanceData) {
        const locationData = rawData.performanceData[locationId];

        // Vérifier si les données de l'année actuelle existent
        if (locationData && locationData.performance && locationData.performance.original && locationData.performance.original.currentYearData) {
            const currentYearData = locationData.performance.original.currentYearData;

            // Initialiser les sommes pour chaque titre
            const titleSums = {
                CALL_CLICKS: 0,
                WEBSITE_CLICKS: 0,
                BUSINESS_CONVERSATIONS: 0,
                BUSINESS_DIRECTION_REQUESTS: 0,
                BUSINESS_BOOKINGS: 0
            };

            // Vérifier s'il y a des `multiDailyMetricTimeSeries`
            if (currentYearData.multiDailyMetricTimeSeries) {
                for (const metricData of currentYearData.multiDailyMetricTimeSeries) {
                    for (const metricTimeSeries of metricData.dailyMetricTimeSeries) {
                        const { dailyMetric, timeSeries } = metricTimeSeries;

                        if (sums.hasOwnProperty(dailyMetric) && timeSeries && timeSeries.datedValues) {
                            for (const datedValue of timeSeries.datedValues) {
                                const value = parseFloat(datedValue.value);
                                if (!isNaN(value)) {
                                    sums[dailyMetric] += value;
                                    titleSums[dailyMetric] += value;
                                }
                            }
                        }
                    }
                }
            }

            // Ajouter les données de titre et les sommes au graphique
            graphInteractionData.push({
                title: locationData.title,
                sums: titleSums
            });
        }
    }

    // Calculer la somme totale de toutes les métriques
    const totalSum = Object.values(sums).reduce((acc, value) => acc + value, 0);

    // Retourner l'objet contenant la somme totale et les données pour le graphique
    return { totalSum, graphInteractionData };
}

function displaySalesGraph(graphData) {
    // Préparer les labels et les datasets pour le graphique
    const labels = graphData.map(data => data.title);
    const dataset = {
        label: 'Interactions Totales',
        data: graphData.map(data => {
            return Object.values(data.sums).reduce((acc, value) => acc + value, 0);
        }),
        fill: false,
        borderColor: 'white',
        tension: 0.1
    };

    // Récupérer le contexte du canevas
    const ctx = document.getElementById('line-chart').getContext('2d');

    // Créer le graphique linéaire
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [dataset]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: 'white' // Changer la couleur du titre du label à blanc
                    }
                }
            }
        }
    });
}
    </script>
@endsection
