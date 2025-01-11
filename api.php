<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "country_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all countries or a specific country
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM country_details WHERE id=$id";
    } else {
        $sql = "SELECT * FROM country_details WHERE display = 1";
    }
    
    $result = $conn->query($sql);
    $countries = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $countries[] = $row;
        }
    }
    echo json_encode($countries);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $country_name = $_POST['country_name'];
    $flag_image = $_POST['flag_image'];
    $capital = $_POST['capital'];
    $region = $_POST['region'];
    $country_code = $_POST['country_code'];

    $sql = "INSERT INTO country_details (country_name, flag_image, capital, region, country_code, created_at, updated_at)
            VALUES ('$country_name', '$flag_image', '$capital', '$region', '$country_code', NOW(), NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Update country
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);
    $id = $_PUT['id'];
    $country_name = $_PUT['country_name'];
    $flag_image = $_PUT['flag_image'];
    $capital = $_PUT['capital'];
    $region = $_PUT['region'];
    $country_code = $_PUT['country_code'];

    $sql = "UPDATE country_details SET 
            country_name='$country_name', flag_image='$flag_image', capital='$capital', region='$region', country_code='$country_code', updated_at=NOW()
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete country
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $id = $_DELETE['id'];

    $sql = "DELETE FROM country_details WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>