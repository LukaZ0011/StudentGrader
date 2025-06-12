<?php
require_once(dirname(__FILE__) . '/../functions.php');

// Check authentication
requireAuth();

// Get user role and route to appropriate dashboard
$user_role = getCurrentUserRole();

switch ($user_role) {
    case 'admin':
        require __DIR__ . '/admin-dashboard.php';
        break;
    case 'teacher':
        require __DIR__ . '/teacher-dashboard.php';
        break;
    case 'student':
        require __DIR__ . '/student-dashboard.php';
        break;
    default:
        // For unknown roles, redirect to login
        header('Location: /StudentGrader/login');
        exit();
}
