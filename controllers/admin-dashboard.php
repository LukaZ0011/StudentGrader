<?php
require_once(dirname(__FILE__) . '/../functions.php');
require_once(dirname(__FILE__) . '/../config/db_credential.php');
require_once(dirname(__FILE__) . '/../classes/Messages.php');
require_once(dirname(__FILE__) . '/../classes/SqlCrud.php');
require_once(dirname(__FILE__) . '/../classes/Students.php');
require_once(dirname(__FILE__) . '/../classes/Teachers.php');
require_once(dirname(__FILE__) . '/../classes/Grades.php');
require_once(dirname(__FILE__) . '/../classes/Subjects.php');
require_once(dirname(__FILE__) . '/../classes/Users.php');
require_once(dirname(__FILE__) . '/../classes/Enrollments.php');
require_once(dirname(__FILE__) . '/dashboard-helpers.php');

// Require admin role
requireRole('admin');

$heading = 'Admin Dashboard';
$user_info = getCurrentUser();
$user = is_array($user_info) ? $user_info['ime'] . ' ' . $user_info['prezime'] : $user_info;

try {
    $messages = new Messages();

    // Initialize classes
    $students = new Students($messages);
    $teachers = new Teachers($messages);
    $grades = new Grades($messages);
    $subjects = new Subjects($messages);
    $users = new Users($messages);
    $enrollments = new Enrollments($messages);

    // Connect to database
    $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($db->connect_errno) {
        throw new Exception("Database connection failed");
    }

    // Get statistics using helper functions
    $stats = getAdminStats($db);
    $recent_grades = getRecentGrades($db);
    $recent_students = getRecentStudents($db);

    // Initialize input object for CRUD operations
    $input_obj = new stdClass();
    $input_obj->page = 1;
    $input_obj->perPage = 1;

    // Get total students count
    ob_start();
    $students->get_records($input_obj);
    $students_response = json_decode(ob_get_clean(), true);
    $total_students = $students_response['count'] ?? 0;

    // Get total teachers count
    ob_start();
    $teachers->get_records($input_obj);
    $teachers_response = json_decode(ob_get_clean(), true);
    $total_teachers = $teachers_response['count'] ?? 0;

    // Get total users count
    ob_start();
    $users->get_records($input_obj);
    $users_response = json_decode(ob_get_clean(), true);
    $total_users = $users_response['count'] ?? 0;

    // Get total subjects count
    ob_start();
    $subjects->get_records($input_obj);
    $subjects_response = json_decode(ob_get_clean(), true);
    $total_subjects = $subjects_response['count'] ?? 0;

    // Get grades count and average
    ob_start();
    $grades->get_grades_with_details($input_obj);
    $grades_response = json_decode(ob_get_clean(), true);
    $total_grades = $grades_response['count'] ?? 0;

    // Calculate average score
    $avg_score = 0;
    if (!empty($grades_response['data'])) {
        $total_score = 0;
        $count = 0;
        foreach ($grades_response['data'] as $grade) {
            if (isset($grade['ocjena'])) {
                $total_score += $grade['ocjena'];
                $count++;
            }
        }
        $avg_score = $count > 0 ? round($total_score / $count, 1) : 0;
    }

    // Get enrollments count
    ob_start();
    $enrollments->get_records($input_obj);
    $enrollments_response = json_decode(ob_get_clean(), true);
    $total_enrollments = $enrollments_response['count'] ?? 0;

    $stats = [
        'total_users' => $total_users,
        'total_teachers' => $total_teachers,
        'total_students' => $total_students,
        'total_subjects' => $total_subjects,
        'total_grades' => $total_grades,
        'total_enrollments' => $total_enrollments,
        'avg_score' => $avg_score
    ];

    // Get recent activity (last 10 grades)
    $recent_input = new stdClass();
    $recent_input->page = 1;
    $recent_input->perPage = 10;

    ob_start();
    $grades->get_grades_with_details($recent_input);
    $recent_response = json_decode(ob_get_clean(), true);
    $recent_grades = $recent_response['data'] ?? [];

    // Get recent students (last 5)
    $recent_students_input = new stdClass();
    $recent_students_input->page = 1;
    $recent_students_input->perPage = 5;

    ob_start();
    $students->get_records($recent_students_input);
    $recent_students_response = json_decode(ob_get_clean(), true);
    $recent_students = $recent_students_response['data'] ?? [];
} catch (Exception $e) {
    // Log the error
    error_log("Admin dashboard error: " . $e->getMessage());

    // Define variables for error template
    $error_message = "There was a problem loading the admin dashboard: " . $e->getMessage();
    $error_details = $e->getTraceAsString();

    // Display the error using our error partial
    define('DASHBOARD_ERROR', true);
    require __DIR__ . "/../views/partials/dashboard-error.php";
    exit;
}

require __DIR__ . "/../views/admin-dashboard.view.php";
