<?php
class Dbh {
    // Database connection details
    private $host       = "localhost";
    private $dbname     = "imagine";
    private $dbusername = "root";
    private $dbpassword = "";
    private $bdd        = null;

    protected $cost;

    public function __construct() {
        $this->findAppropriateCost(); // Ensure the cost value is set
    }

    public function findAppropriateCost($timeTarget = 0.350) {
        $cost = 10; // Initial cost value for the password_hash function

        do {
            $cost++;                                                   // Increment the cost value
            $start = microtime(true);                                  // Record the start time in microseconds
            password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]); // Hash the password with the current cost
            $end = microtime(true);                                    // Record the end time in microseconds
        } while (($end - $start) < $timeTarget);                   // Continue until the execution time meets or exceeds the target

        $this->cost = $cost; // Store the appropriate cost value
        return $cost;        // Return the appropriate cost value
    }

    public function getCost() {
        return $this->cost;
    }

    // Method to establish a database connection
    protected function connect() {
        try {
            $this->bdd = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->dbusername, $this->dbpassword);
            $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed, better luck next time: " . $e->getMessage());
        }
    }

    // Method to get the database connection
    public function getConnection() {
        if ($this->bdd === null) {
            $this->connect();
        }
        return $this->bdd;
    }
}