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

function authenticate($email, $password)
{
    // Database-based authentication
    require_once __DIR__ . '/config/db_credential.php';

    $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($db->connect_errno) {
        return false;
    }

    $stmt = $db->prepare("SELECT id, ime, prezime, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // In production, use password_verify() with hashed passwords
        if ($user['password'] === $password) {
            return $user;
        }
    }

    $stmt->close();
    $db->close();
    return false;
}

function login($user)
{
    safeSessionStart();
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['ime'] . ' ' . $user['prezime'];
    $_SESSION['user_role'] = $user['role'];
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
    // Return user information based on what's available in session
    if (isset($_SESSION['user_id'])) {
        require_once __DIR__ . '/config/db_credential.php';
        $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_errno) {
            // Fallback to just returning the name if DB connection fails
            return isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
        }

        $stmt = $db->prepare("SELECT id, ime, prezime, email, role FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        $db->close();

        return $user;
    }
    return isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
}

function getCurrentUserId()
{
    safeSessionStart();
    return $_SESSION['user_id'] ?? null;
}

function getCurrentUserRole()
{
    safeSessionStart();
    return $_SESSION['user_role'] ?? null;
}

function requireAuth()
{
    if (!isLoggedIn()) {
        header('Location: /StudentGrader/login');
        exit();
    }
}

function requireRole($allowed_roles)
{
    requireAuth();
    $current_role = getCurrentUserRole();

    if (!is_array($allowed_roles)) {
        $allowed_roles = [$allowed_roles];
    }

    if (!in_array($current_role, $allowed_roles)) {
        // Redirect to home instead of dashboard to avoid infinite loops
        header('Location: /StudentGrader/');
        exit();
    }
}

/**
 * Helper function to safely format the current user's name
 * This handles cases where getCurrentUser() returns an array or string
 *
 * @return string The formatted user name 
 */
function formatUserName()
{
    $user = getCurrentUser();
    if (is_array($user)) {
        return htmlspecialchars($user['ime'] . ' ' . $user['prezime']);
    } else {
        return htmlspecialchars($user ?? '');
    }
}
