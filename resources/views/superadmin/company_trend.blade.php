@extends('superadmin.layouts.app')
@section('title', 'Defend X - Company Trend')
@section('content')

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center">Company Trend Analysis</h2>
                    <div class="text-center my-3">
                        <button id="technical-btn" class="btn btn-primary button-change" data-target="technical">Technical
                            Report</button>
                        <button id="financial-btn" class="btn btn-secondary button-change" data-target="financial">Financial
                            Report</button>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <div id="technical" class="content-section">
                    <!-- Chart Container -->
                    <div class="row">
                        <div class="col-md-12">
                            <canvas id="trendChart" style="width: 100%; height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
                <div id="financial" class="content-section  d-none">
                    <!-- Chart Container -->
                    <div class="row">
                        <div class="col-md-12">
                            <<div style="width: 100%; height: 60vh;"> <!-- Container to set height dynamically -->
                                <canvas id="financialTrendChart"></canvas>
                        </div>
                        <div id="financialTrendTable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let financialTrend = @json($data['financial_trend']);

            let labels = financialTrend.map(item => item.RiskType);
            let ratings = financialTrend.map(item => item.Rating);
            let ratingMin = financialTrend.map(item => item.RatingMin);
            let ratingMax = financialTrend.map(item => item.RatingMax);

            let ctx = document.getElementById("financialTrendChart").getContext("2d");

            let financialChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Rating',
                            data: ratings,
                            backgroundColor: 'rgba(0, 123, 255, 0.7)',
                            borderColor: 'blue',
                            borderWidth: 2,
                            barThickness: 50
                        },
                        {
                            label: 'Rating Min',
                            data: ratingMin,
                            backgroundColor: 'rgba(40, 167, 69, 0.7)',
                            borderColor: 'green',
                            borderWidth: 2,
                            barThickness: 50
                        },
                        {
                            label: 'Rating Max',
                            data: ratingMax,
                            backgroundColor: 'rgba(255, 99, 132, 0.7)',
                            borderColor: 'red',
                            borderWidth: 2,
                            barThickness: 50
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Allows dynamic height
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: "Risk Type"
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: "Amount ($)"
                            },
                            beginAtZero: true
                        }
                    }
                }
            });

            // Generate table below chart
            let tableHTML = `<table border="1" style="width: 100%; text-align: center; margin-top: 10px;">
                                <thead>
                                    <tr>
                                        <th>Risk Type</th>
                                        <th>Rating Min ($)</th>
                                        <th>Rating ($)</th>
                                        <th>Rating Max ($)</th>
                                    </tr>
                                </thead>
                                <tbody>`;
            financialTrend.forEach(item => {
                tableHTML += `<tr>
                                <td>${item.RiskType}</td>
                                <td>${item.RatingMin.toLocaleString()}</td>
                                <td>${item.Rating.toLocaleString()}</td>
                                <td>${item.RatingMax.toLocaleString()}</td>
                              </tr>`;
            });
            tableHTML += `</tbody></table>`;

            document.getElementById("financialTrendTable").innerHTML = tableHTML;
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".button-change").click(function() {
                // Remove active class and reset button styles
                $(".button-change").removeClass("btn-primary").addClass("btn-secondary");

                // Add active class and primary style to the clicked button
                $(this).removeClass("btn-secondary").addClass("btn-primary");

                // Hide all content sections
                $(".content-section").addClass("d-none");

                // Show the clicked target section
                let target = $(this).data("target");
                $("#" + target).removeClass("d-none");
            });

            // Prevent closing when clicking inside content-section
            $(".content-section").click(function(event) {
                event.stopPropagation();
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let trendData = @json($data['techincal_trend']);
            let labels = trendData.map(item => new Date(item.SnapshotDate).toLocaleDateString('en-US', {
                month: 'short',
                year: 'numeric'
            }));
            let cyberRatings = trendData.map(item => item.CyberRating);
            let industryRatings = trendData.map(item => item.IndustryAverageCyberRating);

            let ctx = document.getElementById("trendChart").getContext("2d");

            let trendChart = new Chart(ctx, {
                type: 'line', // Line chart for stock-like visualization
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Cyber Rating',
                            data: cyberRatings,
                            borderColor: 'blue',
                            backgroundColor: 'rgba(0, 0, 255, 0.2)',
                            fill: true,
                            borderWidth: 2
                        },
                        {
                            label: 'Industry Avg Cyber Rating',
                            data: industryRatings,
                            borderColor: 'red',
                            backgroundColor: 'rgba(255, 0, 0, 0.2)',
                            fill: true,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: "Month & Year"
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: "Rating"
                            },
                            min: 0,
                            max: 100
                        }
                    }
                }
            });
        });
    </script>
@endsection
