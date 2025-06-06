<?php

function dd($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}

function urlIs($value)
{
    if (!isset($_SERVER['REQUEST_URI'])) {
        return false;
    }
    return $_SERVER['REQUEST_URI'] === $value;
}

function safeSessionStart()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function authenticate($username, $password)
{
    // Simple authentication - in production, you'd check against a database
    $users = [
        'admin' => 'password123',
        'teacher' => 'teach2024',
        'student' => 'study2024'
    ];

    return isset($users[$username]) && $users[$username] === $password;
}

function login($username)
{
    safeSessionStart();
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username;
}

function logout()
{
    safeSessionStart();
    session_destroy();
}

function isLoggedIn()
{
    safeSessionStart();
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

function getCurrentUser()
{
    safeSessionStart();
    return $_SESSION['username'] ?? null;
}

function requireAuth()
{
    if (!isLoggedIn()) {
        header('Location: /StudentGrader/login');
        exit();
    }
}

