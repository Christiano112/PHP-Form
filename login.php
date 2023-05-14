<?php

$username = $password = "";

function validateInput($data)
{
    $data = htmlspecialchars($data);
    $data = trim($data);
    $data = stripslashes($data);
    return  $data;
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $username = validateInput($_POST['username']);
    $password = validateInput($_POST['password']);
}

$res_array = array();
$filtered_data = array();
$error_response = array();

$res_array['username'] = $username;
$res_array['password'] = $password;

$filtered_data = array_filter($res_array);

// Validation
if (count($filtered_data) < 2) {
    $error_response['error'] = "Please fill all the fields!";
    echo json_encode($error_response);
    exit();
}

if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    $error_response['error'] = "Invalid Username!";
    echo json_encode($error_response);
    exit();
}

// DATABASE
$server_name = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "php_form";

try {
    $conn = new PDO("mysql:host=$server_name;dbname=$db_name", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch Username and Password from Database
    $statement_response = $conn->prepare("SELECT Username, Password FROM REGISTER");
    $statement_response->execute();

    $result = $statement_response->setFetchMode(PDO::FETCH_ASSOC);
    $result = $statement_response->fetchAll();
    foreach ($result as $key => $value) {
        if ($value['Username'] === $username) {
            if (password_verify($password, $value['Password'])) {
                echo json_encode(['data' => 'Login Successful!']);
                exit();
            } else {
                $error_response['error'] = "Invalid Password!";
                echo json_encode($error_response);
                exit();
            }
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>