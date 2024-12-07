<?php
session_start();
require_once 'dbdashboard.php'; 
require_once __DIR__ . '/../dbcon/dbcon.php'; 
require_once 'dbgraph.php'; 

$adminDashboard = new AdminDashboard();
$dashboardData = new AdminDashboardData($connection, $adminDashboard);

$data = $dashboardData->getDashboardData();

if (isset($data) && is_array($data)) {
    $totalUsers = $data['totalUsers'] ?? 0; 
    $totalRentedCars = $data['totalRentedCars'] ?? 0;
    $pendingRequests = $data['pendingRequests'] ?? 0;
    $recentActivity = $data['recentActivity'] ?? [];
    $all_months = $data['all_months'] ?? [];
    $total_amounts = $data['total_amounts'] ?? [];
    $vehicle_types = $data['vehicle_types'] ?? [];
    $total_used = $data['total_used'] ?? [];
    $booking_areas = $data['booking_areas'] ?? [];
    $car_counts = $data['car_counts'] ?? [];
    $total_users_per_month = $data['total_users_per_month'] ?? [];
} else {
    echo "Error: Dashboard data could not be retrieved.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/admindashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/adminhome.css">
</head>
<body>
<?php include('../sidebar/adminsidebar.php');?>

    <main class="content">
        <header>
            <h1>Dashboard</h1>
            <input type="text" placeholder="Search...">
        </header>

        <section class="stats">
            <div class="card">
                <h3>Total Users</h3>
                <h1><?php echo htmlspecialchars($totalUsers); ?></h1>
            </div>
            <div class="card">
                <h3>Total Rented Cars</h3>
                <h1><?php echo htmlspecialchars($totalRentedCars); ?></h1>
            </div>
            <div class="card">
                <h3>Pending Requests</h3>
                <h1><?php echo htmlspecialchars($pendingRequests); ?></h1>
            </div>
        </section>

        <section class="charts">
            <div class="chart">
                <canvas id="paymentChart"></canvas>
            </div>
            <div class="chart">
                <canvas id="totalCarsUsedChart"></canvas>
            </div>
        </section>

        <section class="charts">
            <div class="chart">
                <canvas id="userChart"></canvas>
            </div>
            <div class="chart">
                <canvas id="bookingArea"></canvas>
            </div>
        </section>

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

                // Total Cars Used 
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
                                text: 'Total Cars Used by Type'
                            }
                        }
                    }
                });

                // User Registration 
                const ctx3 = document.getElementById('userChart').getContext('2d');
                new Chart(ctx3, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode($all_months); ?>,
                        datasets: [{
                            label: 'New Users per Month',
                            data: <?php echo json_encode($total_users_per_month); ?>,
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
                            borderColor: 'rgba(255, 159, 64, 1)',
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
                                    text: 'Number of Users'
                                }
                            }
                        }
                    }
                });

                // Booking Area 
                const ctx4 = document.getElementById('bookingArea').getContext('2d');
                new Chart(ctx4, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode($booking_areas); ?>,
                        datasets: [{
                            label: 'Cars Booked by Area',
                            data: <?php echo json_encode($car_counts); ?>,
                            backgroundColor: 'rgba(153, 102, 255, 0.6)',
                            borderColor: 'rgba(153, 102, 255, 1)',
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
    </main>

    <script>
        $(document).ready(function() {
            $('#recentActivityTable').DataTable(); // Initialize DataTable
        });
    </script>
</body>
</html>