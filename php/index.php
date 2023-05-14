<?php

$fname = $lname = $username = $gender = $email = $password = $password2 = "";

function validateInput($data) {
    $data = htmlspecialchars($data);
    $data = trim($data);
    $data = stripslashes($data);
    return  $data;
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $fname = validateInput($_POST['fname']);
    $lname = validateInput($_POST['lname']);
    $username = validateInput($_POST['username']);
    $gender = validateInput($_POST['gender']);
    $email = validateInput($_POST['email']);
    $password = validateInput($_POST['password']);
    $password2 = validateInput($_POST['confirm_password']);
}

$res_array = array();
$filtered_data = array();
$error_response = array();

$res_array['fname'] = $fname;
$res_array['lname'] = $lname;
$res_array['username'] = $username;
$res_array['gender'] = $gender;
$res_array['email'] = $email;
$res_array['password'] = $password;
$res_array['password2'] = $password2;

$filtered_data = array_filter($res_array);

// Validation
if (count($filtered_data) < 7) {
    $error_response['error'] = "Please fill all the fields!";
    echo json_encode($error_response);
    exit();
}

if (!preg_match("/^[a-zA-Z]*$/", $fname)) {
    $error_response['error'] = "Invalid First Name!";
    echo json_encode($error_response);
    exit();
}

if (!preg_match("/^[a-zA-Z]*$/", $lname)) {
    $error_response['error'] = "Invalid Last Name!";
    echo json_encode($error_response);
    exit();
}

if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    $error_response['error'] = "Invalid Username!";
    echo json_encode($error_response);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_response['error'] = "Invalid Email!";
    echo json_encode($error_response);
    exit();
}

if ($password !== $password2) {
    $error_response['error'] = "Passwords do not match!";
    echo json_encode($error_response);
    exit();
}

// echo json_encode($filtered_data);

// DATABASE
$server_name = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "php_form";

// Hash Password
$password = password_hash($password, PASSWORD_BCRYPT);

try {
    $conn = new PDO("mysql:host=$server_name;dbname=$db_name", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch Data from Database and Check if Username or Email already exists
    $statement_response = $conn->prepare("SELECT Username, Email FROM REGISTER");
    $statement_response->execute();

    $result = $statement_response->setFetchMode(PDO::FETCH_ASSOC);
    $result = $statement_response->fetchAll();
    foreach ($result as $key => $value) {
        if ($value['Username'] === $username) {
            $error_response['error'] = "Username already exists!";
            echo json_encode($error_response);
            exit();
        }
        if ($value['Email'] === $email) {
            $error_response['error'] = "Email already exists!";
            echo json_encode($error_response);
            exit();
        }
    }


    // Prepare Statement
    $statement_request = $conn->prepare("INSERT INTO REGISTER (FirstName, LastName, Username, Gender, Email, Password)
    VALUES (:FirstName, :LastName, :Username, :Gender, :Email, :Password)");
    // Bind Parameters
    $statement_request->bindParam(':FirstName', $fname);
    $statement_request->bindParam(':LastName', $lname);
    $statement_request->bindParam(':Username', $username);
    $statement_request->bindParam(':Gender', $gender);
    $statement_request->bindParam(':Email', $email);
    $statement_request->bindParam(':Password', $password);
    // Execute Statement
    $statement_request->execute();

    // Redirect to Login Page
    // header("Location: login.html");
    // exit();


    // Fetch Data
    $statement_response = $conn->prepare("SELECT * FROM REGISTER");
    $statement_response->execute();

    $result = $statement_response->setFetchMode(PDO::FETCH_ASSOC);
    $result = $statement_response->fetchAll();
    echo json_encode($result);

} catch (PDOException $e) {
    echo "Connection Failed! " . $e->getMessage();
}


$conn = null;
?>