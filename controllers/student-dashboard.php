<?php
require_once(dirname(__FILE__) . '/../functions.php');
require_once(dirname(__FILE__) . '/../config/db_credential.php');
require_once(dirname(__FILE__) . '/../classes/Messages.php');
require_once(dirname(__FILE__) . '/dashboard-helpers.php');

// Require student role
requireRole('student');

$heading = 'Student Dashboard';
$user_info = getCurrentUser();
$user = is_array($user_info) ? $user_info['ime'] . ' ' . $user_info['prezime'] : $user_info;
$user_id = getCurrentUserId();

try {
    $messages = new Messages();

    // Get student's ID from students table
    $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($db->connect_errno) {
        throw new Exception("Database connection failed");
    }

    $stmt = $db->prepare("SELECT id, jbmag, godina, status FROM students WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $student_id = $student['id'] ?? null;

    if (!$student_id) {
        throw new Exception("Student record not found");
    }

    // Use helper functions to get student data
    $stats = getStudentStats($db, $student_id);
    $my_grades = getStudentGrades($db, $student_id);
    $enrolled_subjects = getEnrolledSubjects($db, $student_id);

    // Get student's grades with subject details
    $grades_query = "
        SELECT g.*, s.naziv as subject_name, s.ects, s.semestar,
               t.podrucje as teacher_area,
               tu.ime as teacher_ime, tu.prezime as teacher_prezime
        FROM grades g
        JOIN subjects s ON g.subject_id = s.id
        JOIN teachers t ON g.teacher_id = t.id
        JOIN users tu ON t.user_id = tu.id
        WHERE g.student_id = ?
        ORDER BY s.semestar, s.naziv
    ";

    $stmt = $db->prepare($grades_query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $grades_result = $stmt->get_result();
    $my_grades = [];

    while ($row = $grades_result->fetch_assoc()) {
        // Add a composed teacher name field
        $row['teacher_name'] = $row['teacher_ime'] . ' ' . $row['teacher_prezime'];
        $my_grades[] = $row;
    }

    // Get enrolled subjects (all subjects student is enrolled in)
    $enrolled_query = "
        SELECT s.*, s.naziv as subject_name, e.godina,
               COALESCE(g.ocjena, 0) as grade,
               COALESCE(g.ocjena, 0) as avg_grade,
               CASE WHEN g.id IS NOT NULL THEN 'Graded' ELSE 'Pending' END as status
        FROM enrollments e
        JOIN subjects s ON e.subject_id = s.id
        LEFT JOIN grades g ON s.id = g.subject_id AND g.student_id = e.student_id
        WHERE e.student_id = ?
        ORDER BY s.semestar, s.naziv
    ";

    $stmt = $db->prepare($enrolled_query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $enrolled_result = $stmt->get_result();
    $enrolled_subjects = [];

    while ($row = $enrolled_result->fetch_assoc()) {
        $enrolled_subjects[] = $row;
    }

    // Calculate statistics
    $total_ects = 0;
    $earned_ects = 0;
    $total_grades = 0;
    $grade_sum = 0;
    $graded_subjects = 0;

    foreach ($enrolled_subjects as $subject) {
        $total_ects += $subject['ects'];
        if ($subject['grade'] > 0) {
            $earned_ects += $subject['ects'];
            $grade_sum += $subject['grade'];
            $graded_subjects++;
        }
    }

    $stats = [
        'total_subjects' => count($enrolled_subjects),
        'graded_subjects' => $graded_subjects,
        'pending_subjects' => count($enrolled_subjects) - $graded_subjects,
        'total_ects' => $total_ects,
        'earned_ects' => $earned_ects,
        'avg_grade' => $graded_subjects > 0 ? round($grade_sum / $graded_subjects, 1) : 0,
        'student_year' => $student['godina'],
        'student_jbmag' => $student['jbmag'],
        'student_status' => $student['status']
    ];

    // Get recent grades (last 5)
    $recent_grades = array_slice($my_grades, -5);
    $recent_grades = array_reverse($recent_grades);

    // Prepare variables for the view
    $student_name = $user;
    $student_data = $student;
    $enrollments = $enrolled_subjects;
    $grades = $my_grades;
    $overall_average = $stats['avg_grade'];

    $stmt->close();
    $db->close();
} catch (Exception $e) {
    // Log the error
    error_log("Student dashboard error: " . $e->getMessage());

    // Define variables for error template
    $error_message = "There was a problem loading your student dashboard: " . $e->getMessage();
    $error_details = $e->getTraceAsString();

    // Display the error using our error partial
    define('DASHBOARD_ERROR', true);
    require __DIR__ . "/../views/partials/dashboard-error.php";
    exit;
}

require __DIR__ . "/../views/student-dashboard.view.php";
