@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dashboard - Monthly Reports</h2>

    <!-- School vs. Category Graph -->
    <div class="card p-3 my-3">
        <h4>School vs. Category</h4>
        <canvas id="schoolCategoryChart"></canvas>
    </div>

    <!-- Pending Tickets Per Person Graph -->
    <div class="card p-3 my-3">
        <h4>Pending Tickets Per Person</h4>
        <canvas id="pendingTicketsChart"></canvas>
    </div>

    <!-- Resolved Tickets Per Person Graph -->
    <div class="card p-3 my-3">
        <h4>Resolved Tickets Per Person</h4>
        <canvas id="resolvedTicketsChart"></canvas>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // School vs. Category Chart
        var schoolCategoryData = @json($schoolCategoryData);
        var schoolNames = [...new Set(schoolCategoryData.map(item => item.school_name))]; // Unique School Names
        var categoryNames = [...new Set(schoolCategoryData.map(item => item.category_name))]; // Unique Categories

        var categoryCounts = categoryNames.map(category => ({
            label: category,
            data: schoolNames.map(school => {
                var match = schoolCategoryData.find(item => item.school_name === school && item.category_name === category);
                return match ? match.count : 0;
            }),
            borderWidth: 2
        }));

        new Chart(document.getElementById("schoolCategoryChart"), {
            type: 'bar',
            data: {
                labels: schoolNames,
                datasets: categoryCounts
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Pending Tickets Per Person Chart
        var pendingTicketsData = @json($pendingTicketsData);
        new Chart(document.getElementById("pendingTicketsChart"), {
            type: 'line',
            data: {
                labels: pendingTicketsData.map(item => item.assigned_to),
                datasets: [{
                    label: 'Pending Tickets',
                    data: pendingTicketsData.map(item => item.count),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            }
        });

        // Resolved Tickets Per Person Chart
        var resolvedTicketsData = @json($resolvedTicketsData);
        new Chart(document.getElementById("resolvedTicketsChart"), {
            type: 'line',
            data: {
                labels: resolvedTicketsData.map(item => item.assigned_to),
                datasets: [{
                    label: 'Resolved Tickets',
                    data: resolvedTicketsData.map(item => item.count),
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            }
        });
    });
</script>

@endsection
