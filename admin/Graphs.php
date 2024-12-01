<?php
// Include your database connection file
require_once __DIR__ . '../dbcon/dbcon.php';

try {
    // 1. Monthly Payments Data
    $sql = "SELECT DATE_FORMAT(payment_date, '%Y-%m') as month, SUM(amount) as total_amount
            FROM payment
            GROUP BY month
            ORDER BY month";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $months = [];
    $total_amounts = [];
    $all_months = [];

    // Create an array for the last 12 months in the format "1 January"
    for ($i = 0; $i < 12; $i++) {
        $month_name = date("F", strtotime("-$i month"));
        $year = date("Y", strtotime("-$i month"));
        $all_months[] = "$month_name $year"; // Combine month and year
        $total_amounts[] = 0; // Initialize total amounts to zero
    }

    // Populate the total amounts for the fetched payments
    foreach ($payments as $payment) {
        // Use the original month format (Y-m) for matching
        $month_index = array_search($payment['month'], array_map(function($m) {
            return date("Y-m", strtotime($m));
        }, $all_months));
        
        // If the month exists in the all_months array, set the total amount
        if ($month_index !== false) {
            $total_amounts[$month_index] = $payment['total_amount'];
        }
    }

    // Reverse the arrays to match the order of months from the current month backward
    $all_months = array_reverse($all_months);
    $total_amounts = array_reverse($total_amounts);

    // 2. Total Cars Used Data
    $vehicle_types = [
        'Sedan', 'Fullsize SUV', 'Midsize SUV', 'Pickup',
        'Subcompact Sedan', 'Van', 'Sports Car'
    ];
    $total_used = array_fill(0, count($vehicle_types), 0);
    $sql = "SELECT vehicle_type, COUNT(*) as total_used
            FROM rentedcar
            WHERE rent_status = 'completed' or rent_status = 'rented'
            GROUP BY vehicle_type";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $index = array_search($row['vehicle_type'], $vehicle_types);
        if ($index !== false) {
            $total_used[$index] = $row['total_used'];
        }
    }

    // 3. Booking Areas Data
    $sql = "SELECT booking_area, COUNT(*) as car_count 
            FROM rentedcar 
            GROUP BY booking_area 
            ORDER BY booking_area";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $booking_areas = [];
    $car_counts = [];
    foreach ($results as $row) {
        $booking_areas[] = $row['booking_area'];
        $car_counts[] = $row['car_count'];
    }

    $additional_areas = [
        "Pangasinan", "Ilocos Norte", "Ilocos Sur", "Cagayan", "Isabela",
        "Nueva Vizcaya", "Quirino", "Aurora", "Zambales", "Batangas",
        "Laguna", "Rizal"
    ];
    $booking_areas = array_unique(array_merge($booking_areas, $additional_areas));

    // Close the connection if necessary
    // $connection = null; // Uncomment if your dbcon.php does not handle connection closure
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Statistics</title>
    <link rel="stylesheet" href="style.css"> <!-- Include the CSS file -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        h2 {
            color: #444;
            margin-top: 30px;
        }

        /* Flexbox Container Styles */
        .chart-container {
            display: flex;
            flex-direction: column; /* Stack charts vertically */
            align-items: center; /* Center charts horizontally */
            gap: 20px; /* Space between charts */
        }

        .charts-row {
            display: flex; /* Align charts side by side */
            justify-content: space-between; /* Space between charts */
            width: 100%; /* Full width */
            gap: 20px; /* Space between the two charts */
        }

        .charts-column {
            display: flex; /* Align charts in a column */
            flex-direction: column; /* Stack charts vertically */
            align-items: flex-end; /* Align to the right */
            width: 50%; /* Set width for the right column */
        }

        /* Chart Styles */
        canvas {
            display: block;
            max-width: 300px; /* Set a smaller maximum width for the charts */
            height: 150px; /* Set a smaller height for the charts */
            width: 100%; /* Make charts responsive */
        }

        /* Container Styles */
        .container {
            max-width: 800px; /* Limit the width of the content */
            margin: auto; /* Center the container */
            padding: 20px;
            background: #fff; /* White background for the container */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        /* Responsive Styles */
        @media (max-width: 600px) {
            .charts-row {
                flex-direction: column; /* Stack charts vertically on small screens */
            }
            canvas {
                height: auto !important; /* Maintain aspect ratio */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Car Rental Statistics</h1>

        <div class="chart-container">
            <h2>Total Cars Used by Vehicle Type</h2>
            <div class="charts-row">
                <canvas id="totalCarsUsedChart" width="300" height="150"></canvas>
                <div class="charts-column">
                    <h2>Monthly Payment Statistics</h2>
                    <canvas id="paymentChart" width="300" height="150"></canvas>
                    <h2>Cars Rented by Booking Area</h2>
                    <canvas id="bookingAreaChart" width="300" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx1 = document.getElementById('paymentChart').getContext('2d');
        const paymentChart = new Chart(ctx1, {
            type: 'line', // Changed to line chart
            data: {
                labels: <?php echo json_encode($all_months); ?>,
                datasets: [{
                    label: 'Total Payments per Month',
                    data: <?php echo json_encode($total_amounts); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: true // Fill under the line
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount ($)'
                        }
                    }
                }
            }
        });

        const ctx2 = document.getElementById('totalCarsUsedChart').getContext('2d');
        const totalCarsUsedChart = new Chart(ctx2, {
            type: 'pie', // Changed to pie chart
            data: {
                labels: <?php echo json_encode($vehicle_types); ?>,
                datasets: [{
                    label: 'Total Cars Used',
                    data: <?php echo json_encode($total_used); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ],
 borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Total Cars Used by Vehicle Type'
                    }
                }
            }
        });

        const ctx3 = document.getElementById('bookingAreaChart').getContext('2d');
        const bookingAreaChart = new Chart(ctx3, {
            type: 'bar', // Changed to bar chart
            data: {
                labels: <?php echo json_encode($booking_areas); ?>,
                datasets: [{
                    label: 'Cars Rented by Booking Area',
                    data: <?php echo json_encode($car_counts); ?>,
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Cars'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>