<?php
require_once(dirname(__FILE__) . '/config/db_credential.php');
require_once(dirname(__FILE__) . '/classes/Messages.php');
require_once(dirname(__FILE__) . '/classes/Users.php');
require_once(dirname(__FILE__) . '/classes/Students.php');
require_once(dirname(__FILE__) . '/classes/Teachers.php');
require_once(dirname(__FILE__) . '/classes/Subjects.php');
require_once(dirname(__FILE__) . '/classes/Enrollments.php');
require_once(dirname(__FILE__) . '/classes/Grades.php');

error_reporting(E_ERROR | E_PARSE);
header('Access-Control-Allow-Origin: http://localhost');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

session_start();
$messages = new Messages();

// Parse input JSON
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $input_json = file_get_contents('php://input');
        if (empty($input_json)) {
            $input_json = json_encode($_POST);
        }
        break;
    case 'GET':
        $input_json = json_encode($_GET);
        break;
    default:
        echo $messages->request_err;
        exit;
}

$input_obj = json_decode($input_json);

if (!isset($input_obj->procedure)) {
    echo $messages->procedure_err;
    exit;
}

// Handle different procedures
switch ($input_obj->procedure) {
    // User procedures
    case 'p_login':
        try {
            $users = new Users($messages);
            $users->login($input_obj, $messages);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    case 'p_logout':
        session_destroy();
        echo $messages->logout;
        break;

    case 'p_get_users':
        try {
            $users = new Users($messages);
            $users->get_records($input_obj);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    case 'p_save_user':
        try {
            $users = new Users($messages);
            $users->save($input_json, $messages);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    case 'p_delete_user':
        try {
            $users = new Users($messages);
            $users->delete($input_obj->id, $messages);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    // Student procedures
    case 'p_get_students':
        try {
            $students = new Students($messages);
            $students->get_records($input_obj);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    case 'p_save_student':
        try {
            $students = new Students($messages);
            $students->save($input_json, $messages);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    case 'p_delete_student':
        try {
            $students = new Students($messages);
            $students->delete($input_obj->id, $messages);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    // Teacher procedures
    case 'p_get_teachers':
        try {
            $teachers = new Teachers($messages);
            $teachers->get_records($input_obj);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    case 'p_save_teacher':
        try {
            $teachers = new Teachers($messages);
            $teachers->save($input_json, $messages);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    case 'p_delete_teacher':
        try {
            $teachers = new Teachers($messages);
            $teachers->delete($input_obj->id, $messages);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    // Subject procedures
    case 'p_get_subjects':
        try {
            $subjects = new Subjects($messages);
            $subjects->get_records($input_obj);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    case 'p_save_subject':
        try {
            $subjects = new Subjects($messages);
            $subjects->save($input_json, $messages);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    case 'p_delete_subject':
        try {
            $subjects = new Subjects($messages);
            $subjects->delete($input_obj->id, $messages);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    // Enrollment procedures
    case 'p_get_enrollments':
        try {
            $enrollments = new Enrollments($messages);
            $enrollments->get_enrollments_with_details($input_obj);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    case 'p_save_enrollment':
        try {
            $enrollments = new Enrollments($messages);
            $enrollments->save($input_json, $messages);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    case 'p_delete_enrollment':
        try {
            $enrollments = new Enrollments($messages);
            $enrollments->delete($input_obj->id, $messages);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    // Grade procedures
    case 'p_get_grades':
        try {
            $grades = new Grades($messages);
            $grades->get_grades_with_details($input_obj);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    case 'p_save_grade':
        try {
            $grades = new Grades($messages);
            $grades->save($input_json, $messages);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    case 'p_delete_grade':
        try {
            $grades = new Grades($messages);
            $grades->delete($input_obj->id, $messages);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    default:
        echo $messages->procedure_err;
}

// Handle GET requests for enrolled students
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_enrolled_students') {
    header('Content-Type: application/json');

    // Basic error checking
    if (!isset($_GET['subject_id'])) {
        echo json_encode(['error' => 'Subject ID required']);
        exit;
    }

    $subject_id = intval($_GET['subject_id']);

    try {
        require_once(dirname(__FILE__) . '/config/db_credential.php');
        $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

        if ($db->connect_error) {
            echo json_encode(['error' => 'Database connection failed: ' . $db->connect_error]);
            exit;
        }

        // Simple query to get enrolled students
        $query = "
            SELECT s.id, u.ime, u.prezime, s.jbmag
            FROM students s
            JOIN users u ON s.user_id = u.id
            JOIN enrollments e ON s.id = e.student_id
            WHERE e.subject_id = ?
            ORDER BY u.prezime, u.ime
        ";

        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $subject_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }

        $stmt->close();
        $db->close();

        echo json_encode($students);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}
