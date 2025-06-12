<?php
require_once(dirname(__FILE__) . '/../functions.php');
require_once(dirname(__FILE__) . '/../config/db_credential.php');
require_once(dirname(__FILE__) . '/../classes/Messages.php');
require_once(dirname(__FILE__) . '/../classes/SqlCrud.php');
require_once(dirname(__FILE__) . '/../classes/Grades.php');
require_once(dirname(__FILE__) . '/../classes/Subjects.php');
require_once(dirname(__FILE__) . '/../classes/Students.php');
require_once(dirname(__FILE__) . '/dashboard-helpers.php');

// Require teacher role
requireRole('teacher');

$heading = 'Teacher Dashboard';
$user_info = getCurrentUser();
$user = is_array($user_info) ? $user_info['ime'] . ' ' . $user_info['prezime'] : $user_info;
$user_id = getCurrentUserId();
$teacher_name = $user; // Set the teacher name for the view

try {
    $messages = new Messages();

    // Initialize classes
    $grades = new Grades($messages);
    $subjects = new Subjects($messages);
    $students = new Students($messages);

    // Get teacher's ID from teachers table
    $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($db->connect_errno) {
        throw new Exception("Database connection failed");
    }

    $stmt = $db->prepare("SELECT id, podrucje FROM teachers WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $teacher = $result->fetch_assoc();
    $teacher_id = $teacher['id'] ?? null;
    $teacher_data = $teacher; // Set teacher data for the view

    if (!$teacher_id) {
        throw new Exception("Teacher record not found");
    }

    // Use helper functions to get teacher data
    $stats = getTeacherStats($db, $teacher_id);

    // Get teacher's subjects - if they haven't graded students yet, get all subjects
    $teacher_subjects = getTeacherSubjects($db, $teacher_id);
    if (empty($teacher_subjects)) {
        // Fallback: Get all subjects if teacher has no grades yet
        $query = "SELECT s.id, s.naziv, s.ects, s.semestar, s.created_at, 
                  0 as student_count
                  FROM subjects s
                  ORDER BY s.semestar, s.created_at DESC";
        $result = $db->query($query);
        $teacher_subjects = [];
        while ($row = $result->fetch_assoc()) {
            $teacher_subjects[] = $row;
        }
    }

    $recent_grades = getRecentGrades($db, 5); // Get 5 most recent grades overall

    // Process recent grades to add additional fields needed by the view
    foreach ($recent_grades as &$grade) {
        $grade['student_name'] = $grade['student_ime'] . ' ' . $grade['student_prezime'];
        // If ishod is missing, add a placeholder
        if (!isset($grade['ishod'])) {
            $grade['ishod'] = 'Unknown Assessment';
        }
    }

    $my_students = getTeacherStudents($db, $teacher_id);

    // Prepare other variables for the view
    $teacher_name = $user;
    $total_students = count($my_students);
    $average_grade = isset($stats['avg_grade']) ? $stats['avg_grade'] : null;

    $stmt->close();
    $db->close();
} catch (Exception $e) {
    // Log the error
    error_log("Teacher dashboard error: " . $e->getMessage());

    // Define variables for error template
    $error_message = "There was a problem loading your teacher dashboard: " . $e->getMessage();
    $error_details = $e->getTraceAsString();

    // Display the error using our error partial
    define('DASHBOARD_ERROR', true);
    require __DIR__ . "/../views/partials/dashboard-error.php";
    exit;
}

require __DIR__ . "/../views/teacher-dashboard.view.php";
