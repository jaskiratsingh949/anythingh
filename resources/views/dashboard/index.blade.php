@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Dashboard - Monthly Reports</h2>

    <!-- Filters Section (Only Month Selection) -->
    <div class="card p-4 mb-4">
        <form id="filter-form">
            <div class="row">
                <div class="col-md-6">
                    <label for="start_month">Start Month</label>
                    <input type="month" name="start_month" id="start_month" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="end_month">End Month</label>
                    <input type="month" name="end_month" id="end_month" class="form-control">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="button" id="apply-filters" class="btn btn-primary">Apply Filters</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Graphs Section (Side by Side) -->
    <div class="row">
        <!-- Graph 1: School vs Category -->
        <div class="col-md-4">
            <div class="card p-3">
                <h6 class="text-center">School vs. Category</h6>
                <canvas id="schoolCategoryChart"></canvas>
            </div>
        </div>

        <!-- Graph 2: Pending Tickets Per Person -->
        <div class="col-md-4">
            <div class="card p-3">
                <h6 class="text-center">Pending Tickets Per Person</h6>
                <canvas id="pendingTicketsChart"></canvas>
            </div>
        </div>

        <!-- Graph 3: Resolved Tickets Per Person -->
        <div class="col-md-4">
            <div class="card p-3">
                <h6 class="text-center">Resolved Tickets Per Person</h6>
                <canvas id="resolvedTicketsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    function createChart(canvasId, label, labels, datasets) {
        var ctx = document.getElementById(canvasId).getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels, // X-axis labels (schools)
                datasets: datasets // Data grouped by categories
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true } // Show legend for categories
                },
                scales: {
                    x: { stacked: true },
                    y: { stacked: true }
                }
            }
        });
    }

    // Parse Data for School vs. Category
    var schoolCategoryData = {!! json_encode($schoolCategoryData) !!};
    var schools = [...new Set(schoolCategoryData.map(item => item.school_name))]; // Unique Schools
    var categories = [...new Set(schoolCategoryData.map(item => item.category_name))]; // Unique Categories

    var datasets = categories.map(category => {
        return {
            label: category,
            data: schools.map(school => {
                let found = schoolCategoryData.find(item => item.school_name === school && item.category_name === category);
                return found ? found.count : 0;
            }),
            backgroundColor: `rgba(${Math.random()*255}, ${Math.random()*255}, ${Math.random()*255}, 0.6)`
        };
    });

    createChart("schoolCategoryChart", "Tickets by School & Category", schools, datasets);

    // Keep Pending and Resolved Tickets Charts Unchanged
    createChart("pendingTicketsChart", "Pending Tickets Per Person", 
        {!! json_encode($pendingTicketsData->pluck('assigned_to')) !!}, 
        [{ label: "Pending Tickets", data: {!! json_encode($pendingTicketsData->pluck('count')) !!}, backgroundColor: 'rgba(255, 99, 132, 0.6)' }]
    );

    createChart("resolvedTicketsChart", "Resolved Tickets Per Person", 
        {!! json_encode($resolvedTicketsData->pluck('assigned_to')) !!}, 
        [{ label: "Resolved Tickets", data: {!! json_encode($resolvedTicketsData->pluck('count')) !!}, backgroundColor: 'rgba(75, 192, 192, 0.6)' }]
    );

    document.getElementById('apply-filters').addEventListener('click', function() {
        let formData = new FormData(document.getElementById('filter-form'));

        fetch('{{ route("dashboard.filter") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(response => response.json())
        .then(data => {
            console.log("Updated Data:", data);
        })
        .catch(error => console.error("Error fetching data:", error));
    });
});
</script>

<style>
    .card {
        height: 300px; /* Adjust height to make graphs fit well */
    }

    canvas {
        max-height: 220px; /* Ensure graphs don't stretch too much */
    }
</style>

@endsection
