<?php

$heading = 'Login';
$error = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields.';
    } elseif (authenticate($username, $password)) {
        login($username);
        header('Location: /StudentGrader/dashboard');
        exit();
    } else {
        $error = 'Invalid username or password.';
    }
}

require __DIR__ . "/../views/login.view.php";
