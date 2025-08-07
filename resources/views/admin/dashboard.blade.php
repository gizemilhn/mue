
@extends('admin.index')
@section('content')
    <div class="container mt-4">
        <h2 class="h5 no-margin-bottom">Dashboard</h2>
    </div>

    <section class="no-padding-top no-padding-bottom">
        <div class="container-fluid">
            <div class="row">
                <!-- New Clients -->
                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block">
                        <div class="progress-details d-flex align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"><i class="icon-user-1"></i></div>
                                <strong>New Clients</strong>
                            </div>
                            <div class="number dashtext-1">{{ $newClientsCount }}</div>
                        </div>
                        <div class="progress progress-template">
                            <div role="progressbar"
                                 style="width: {{ $newClientsCount }}%"
                                 aria-valuenow="{{ $newClientsCount }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                 class="progress-bar progress-bar-template dashbg-1">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Clients -->
                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block">
                        <div class="progress-details d-flex align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"><i class="icon-user-1"></i></div>
                                <strong>Total Clients</strong>
                            </div>
                            <div class="number dashtext-1">{{ $totalClientsCount }}</div>
                        </div>
                        <div class="progress progress-template">
                            <div role="progressbar"
                                 style="width: {{ $totalClientsCount }}%"
                                 aria-valuenow="{{ $totalClientsCount }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                 class="progress-bar progress-bar-template dashbg-1">
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- row -->
        </div> <!-- container-fluid -->
    </section>

    <!-- Sales and Visit Statistics Section -->
    <section class="no-padding-bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4">
                    <div class="bar-chart block no-margin-bottom">
                        <canvas id="barChartExample1"></canvas>
                    </div>
                    <div class="bar-chart block">
                        <canvas id="barChartExample2"></canvas>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="line-chart block">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
