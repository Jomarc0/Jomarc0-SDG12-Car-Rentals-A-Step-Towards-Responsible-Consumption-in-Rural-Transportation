<?php
require_once '../main/calculateprice.php';

class PaymentProcessor {
    private $conn;
    private $rentId;
    private $userId;
    private $error;
    private $successMessage;
    private $totalPrice = 0.00; // store as a float value

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
        session_start();
        $this->rentId = $_SESSION['rent_id'] ?? null;  //session the rent
        $this->userId = $_SESSION['user_id'] ?? null;  //session the user
        if ($this->userId === null) {
            die("User  ID is not set in the session.");
        }
        $this->rentId = $this->fetchRentId($this->userId); //calling the fetchrentid function
        if ($this->rentId === null) {
            die("No booking found for the specified user.");
        }
        $this->calculateTotalPrice();
    }

    private function fetchRentId($userId) { //retrive the rent_id usng the user id and rent ID as the highest id
        $sql = "SELECT rent_id FROM rentedcar WHERE user_id = :user_id ORDER BY rent_id DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        
        if ($stmt->rowCount() > 0) {
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);
            return $booking['rent_id'];
        }
        return null;
    }

    private function calculateTotalPrice() {
        if (isset($this->rentId)) {
            $rentalCalculator = new RentalCalculator($this->conn); //calling the rentalcalculator class from caculateprice.php
            $rentalCalculator->setBookingId($this->rentId);
            $totalPrice = $rentalCalculator->calculateRentalPrice();

            if ($totalPrice === null) {
                die("Failed to fetch the rental price. Check your database.");
            } else {
                $this->totalPrice = $totalPrice; // store as numeric value
            }
        }
    }

    public function processPayment() {
        $checkRentIdStmt = $this->conn->prepare("SELECT COUNT(*) FROM rentedcar WHERE rent_id = :rent_id"); // Check if the rent id exists
        $checkRentIdStmt->execute([':rent_id' => $this->rentId]);
        $count = $checkRentIdStmt->fetchColumn();
    
        if ($count == 0) { // If no rent id found
            $this->error = "The specified rent ID does not exist.";
            return;
        }
    
        // PayMongo API
        $amountInCents = $this->totalPrice * 100; // Convert to cents because the API requires it
        $data = [
            'data' => [
                'attributes' => [
                    'amount' => $amountInCents, 
                    'currency' => 'PHP',
                    'payment_method' => [
                        'type' => 'gcash',
                        'details' => ''
                    ],
                    'description' => 'Payment for rental ID ' . $this->rentId,
                ]
            ]
        ];
    
        // Call PayMongo API
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paymongo.com/v1/links", // PayMongo API
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data), // The data array
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "authorization: Basic " . base64_encode("sk_test_Nn5hZUay5rVNvqPwchzhzV5d:"), // Secret key from PayMongo
                "content-type: application/json"
            ],
        ]);
    
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
    
        if ($err) {
            $this->error = "cURL Error: " . $err;
        } else {
            $responseData = json_decode($response, true);
            if (isset($responseData['data']['attributes']['checkout_url'])) { // Check if array has a data
                $checkoutUrl = $responseData['data']['attributes']['checkout_url']; // Store the responseData to checkoutURL
                
                $paymentStatus = 'pending'; // Insert payment record into the database
                
                
                $stmt = $this->conn->prepare("INSERT INTO payment (amount, payment_status, rent_id, payment_link, user_id) VALUES (:amount, :payment_status, :rent_id, :payment_link, :user_id)");
                if ($stmt->execute([':amount' => $this->totalPrice, ':payment_status' => $paymentStatus, ':rent_id' => $this->rentId, ':payment_link' => $checkoutUrl, ':user_id' => $this->userId])) {
                    $this->successMessage = "Payment of " . htmlspecialchars($this->totalPrice) . " is pending!";
                    $this->updateRentStatus();
                } else {
                    $this->error = "Error processing payment: " . implode(", ", $stmt->errorInfo());
                }
    
                // Redirect to the PayMongo link
                header("Location: " . $checkoutUrl);
                exit();
            } else { // If no URL detected
                $this->error = "Failed to create a payment link: " . json_encode($responseData);
            }
        }
    }
    public function getError() {
        return $this->error;
    }

    public function getSuccessMessage() {
        return $this->successMessage;
    }

    private function updateRentStatus() {
        $sql = "UPDATE rentedcar SET rent_status='pending' WHERE rent_id = :rent_id";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt->execute([':rent_id' => $this->rentId])) {
            $this->error = "Error updating rent status: " . implode(", ", $stmt->errorInfo());
        }
    }
}