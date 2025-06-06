<?php

// Require authentication to view dashboard
requireAuth();

$heading = 'Dashboard';
$user = getCurrentUser();

// Mock data for demonstration
$stats = [
    'total_assignments' => 15,
    'pending_grades' => 8,
    'total_students' => 42,
    'avg_score' => 87.3
];

$recent_assignments = [
    ['title' => 'Math Quiz #3', 'due_date' => '2025-06-10', 'submissions' => 35, 'total' => 42],
    ['title' => 'Science Lab Report', 'due_date' => '2025-06-12', 'submissions' => 28, 'total' => 42],
    ['title' => 'History Essay', 'due_date' => '2025-06-15', 'submissions' => 12, 'total' => 42]
];

require __DIR__ . "/../views/dashboard.view.php";
