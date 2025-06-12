<?php
require_once __DIR__ . '/../functions.php';

$heading = 'Login';
$error = null;

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: /StudentGrader/dashboard');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $user = authenticate($email, $password);

        if ($user) {
            login($user);
            header('Location: /StudentGrader/dashboard');
            exit();
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

require __DIR__ . "/../views/login.view.php";
