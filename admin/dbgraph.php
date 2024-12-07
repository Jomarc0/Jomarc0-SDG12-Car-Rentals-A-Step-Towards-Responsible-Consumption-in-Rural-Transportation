<?php
class AdminDashboardData {
    private $connection;
    private $adminDashboard;

    public function __construct($connection, $adminDashboard) {
        $this->connection = $connection;
        $this->adminDashboard = $adminDashboard;
    }

    public function getDashboardData() {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header("Location: admin.php");
            exit();
        }

        $totalUsers = $this->adminDashboard->getTotalUsers();
        $totalRentedCars = $this->adminDashboard->getTotalRentedCars();
        $pendingRequests = $this->adminDashboard->getPendingRequests();
        $recentActivity = $this->adminDashboard->getRecentActivity();

        try {
            $sql = "SELECT DATE_FORMAT(payment_date, '%Y-%m') as month, SUM(amount) as total_amount
                    FROM payment
                    GROUP BY month
                    ORDER BY month";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $all_months = [];
            $total_amounts = [];

            for ($i = 0; $i < 12; $i++) {
                $month_name = date("F", strtotime("-$i month"));
                $year = date("Y", strtotime("-$i month"));
                $all_months[] = "$month_name $year"; 
                $total_amounts[] = 0; 
            }

            foreach ($payments as $payment) {
                $month_index = array_search($payment['month'], array_map(function($m) {
                    return date("Y-m", strtotime($m));
                }, $all_months));
                
                if ($month_index !== false) {
                    $total_amounts[$month_index] = $payment['total_amount'];
                }
            }

            $all_months = array_reverse($all_months);
            $total_amounts = array_reverse($total_amounts);

            $vehicle_types = [
                'Sedan', 'Fullsize SUV', 'Midsize SUV', 'Pickup',
                'Subcompact Sedan', 'Van', 'Sports Car'
            ];
            $total_used = array_fill(0, count($vehicle_types), 0);
            $sql = "SELECT vehicle_type, COUNT(*) as total_used
                    FROM rentedcar
                    WHERE rent_status = 'completed' or rent_status = 'rented'
                    GROUP BY vehicle_type";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $index = array_search($row['vehicle_type'], $vehicle_types);
                if ($index !== false) {
                    $total_used[$index] = $row['total_used'];
                }
            }

            $sql = "SELECT booking_area, COUNT(*) as car_count 
                    FROM rentedcar 
                    GROUP BY booking_area 
                    ORDER BY booking_area";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $booking_areas = [];
            $car_counts = [];
            foreach ($results as $row) {
                $booking_areas[] = $row['booking_area'];
                $car_counts[] = $row['car_count'];
            }
            
            $booking_areas = array_unique(array_merge($booking_areas));

            $sql = "SELECT COUNT(*) as total_users FROM user"; 
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $total_users = $stmt->fetchColumn();

            $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total_users
                    FROM user
                    GROUP BY month
                    ORDER BY month";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $user_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $total_users_per_month = array_fill(0, 12, 0); 
            $all_months_reversed = array_reverse($all_months);

        
            foreach ($user_data as $user) {
                $month_index = array_search($user['month'], array_map(function($m) {
                    return date("Y-m", strtotime($m));
                }, $all_months_reversed));
                
                if ($month_index !== false) {
                    $total_users_per_month[$month_index] = $user['total_users'];
                }
            }

            return [
                'totalUsers' => $totalUsers,
                'totalRentedCars' => $totalRentedCars,
                'pendingRequests' => $pendingRequests,
                'recentActivity' => $recentActivity,
                'all_months' => $all_months,
                'total_amounts' => $total_amounts,
                'vehicle_types' => $vehicle_types,
                'total_used' => $total_used,
                'booking_areas' => $booking_areas,
                'car_counts' => $car_counts,
                'total_users_per_month' => array_reverse($total_users_per_month)
            ];

        } catch (Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
            exit;
        }
    }
}
?>