<?php
// Include your database connection file
require_once __DIR__ . '/../dbcon/dbcon.php';

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

    // 4. Registered Users Data
    $sql = "SELECT COUNT(*) as total_users FROM user"; // Assume you have a users table
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $total_users = $stmt->fetchColumn();

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
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
   
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            background-color: #f9f9f9;
        }

        .sidebar {
            width: 20%;
            background: #222;
            color: #fff;
            padding: 20px;
            min-height: 100vh;
        }

        .sidebar h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .sidebar nav a {
            display: block;
            color: #aaa;
            text-decoration: none;
            padding: 10px 0;
            margin: 5px 0;
        }

        .sidebar nav a.active,
        .sidebar nav a:hover {
            color: #fff;
        }

        .content {
            width: 80%;
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 30%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .trend {
            font-size: 12px;
            font-weight: bold;
        }

        .trend.up {
            color: green;
        }

        .trend.down {
            color: red;
        }

        .charts {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }

        .chart {
            width: 45%;
        }

        .pie {
            width: 30%;
        }

        canvas {
            display: block;
            max-width: 100%; /* Make charts responsive */
            height: auto; /* Maintain aspect ratio */
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        /* Responsive Styles */
        @media (max-width: 600px) {
            .charts {
                flex-direction: column; /* Stack charts vertically on small screens */
            }
            .chart {
                width: 100%; /* Full width for charts on small screens */
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <h2>Nosura</h2>
        <nav>
            <a href="#" class="active">Dashboard</a>
            <a href="#">Statistics</a>
            <a href="#">Payment</a>
            <a href="#">Transactions</a>
            <a href="#">Products</a>
            <a href="#">Customer</a>
            <a href="#">Messages</a>
            <a href="#">Settings</a>
            <a href="#">Logout</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="content">
        <header>
            <h1>Dashboard</h1>
            <input type="text" placeholder="Search...">
            <div class="profile"></div>
        </header>

        <!-- Stats Cards -->
        <section class="stats">
            <div class="card">
                <h3>$46,345.00</h3>
                <p>Total Balance</p>
                <span class="trend up">+13%</span>
            </div>
            <div class="card">
                <h3>$23,723.00</h3>
                <p>Total Sales</p>
                <span class="trend up">+42%</span>
            </div>
            <div class="card">
                <h3>$25,627.00</h3>
                <p>Total Expenses</p>
                <span class="trend down">-34%</span>
            </div>
        </section>

        <!-- Graph Section -->
        <section class="charts">
            <div class="chart">
                <canvas id="paymentChart"></canvas>
            </div>
            <div class="chart pie">
                <canvas id="totalCarsUsedChart"></canvas>
            </div>
        </section>

        < section class="charts">
            <div class="chart">
                <canvas id="bookingAreaChart"></canvas>
            </div>
        </section>

        <!-- Registered Users Table -->
        <section class="orders">
            <h2>Latest Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Billing Name</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>10 Mar 2020</td>
                        <td>Mahuya Kanika</td>
                        <td>$12,345.00</td>
                        <td>Chargeback</td>
                        <td>▼</td>
                    </tr>
                    <tr>
                        <td>09 Mar 2020</td>
                        <td>Sukla Manusa</td>
                        <td>$23,845.00</td>
                        <td>Paid</td>
                        <td>▼</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Registered Users Count -->
        <section class="orders">
            <h2>Registered Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>Total Registered Users</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $total_users; ?></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Monthly Payment Chart
            const ctx1 = document.getElementById('paymentChart').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($all_months); ?>,
                    datasets: [{
                        label: 'Total Payments per Month',
                        data: <?php echo json_encode($total_amounts); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: true
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

            // Total Cars Used Chart
            const ctx2 = document.getElementById('totalCarsUsedChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($vehicle_types); ?>,
                    datasets: [{
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

            // Booking Area Chart
            const ctx3 = document.getElementById('bookingAreaChart').getContext('2d');
            new Chart(ctx3, {
                type: 'bar',
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
        });
    </script>
</body>
</html>