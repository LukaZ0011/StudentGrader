<?php

/**
 * Dashboard statistics helper functions
 * This file contains functions to fetch statistics for the different dashboards
 */

/**
 * Get admin dashboard statistics
 * 
 * @param object $db Database connection
 * @return array Statistics array
 */
function getAdminStats($db)
{
    $stats = [
        'total_users' => 0,
        'total_students' => 0,
        'total_teachers' => 0,
        'total_subjects' => 0,
        'total_grades' => 0,
        'total_enrollments' => 0,
        'avg_score' => 0
    ];

    // Count users
    $result = $db->query("SELECT COUNT(*) AS count FROM users");
    if ($result && $row = $result->fetch_assoc()) {
        $stats['total_users'] = $row['count'];
    }

    // Count students
    $result = $db->query("SELECT COUNT(*) AS count FROM students");
    if ($result && $row = $result->fetch_assoc()) {
        $stats['total_students'] = $row['count'];
    }

    // Count teachers
    $result = $db->query("SELECT COUNT(*) AS count FROM teachers");
    if ($result && $row = $result->fetch_assoc()) {
        $stats['total_teachers'] = $row['count'];
    }

    // Count subjects
    $result = $db->query("SELECT COUNT(*) AS count FROM subjects");
    if ($result && $row = $result->fetch_assoc()) {
        $stats['total_subjects'] = $row['count'];
    }

    // Count grades
    $result = $db->query("SELECT COUNT(*) AS count FROM grades");
    if ($result && $row = $result->fetch_assoc()) {
        $stats['total_grades'] = $row['count'];
    }

    // Count enrollments
    $result = $db->query("SELECT COUNT(*) AS count FROM enrollments");
    if ($result && $row = $result->fetch_assoc()) {
        $stats['total_enrollments'] = $row['count'];
    }

    // Average grade
    $result = $db->query("SELECT AVG(ocjena) AS avg_score FROM grades");
    if ($result && $row = $result->fetch_assoc()) {
        $stats['avg_score'] = !is_null($row['avg_score']) ? number_format($row['avg_score'], 2) : '0.00';
    } else {
        $stats['avg_score'] = '0.00';
    }

    return $stats;
}

/**
 * Get teacher dashboard statistics
 * 
 * @param object $db Database connection
 * @param int $teacher_id Teacher ID
 * @return array Statistics array
 */
function getTeacherStats($db, $teacher_id)
{
    $stats = [
        'subjects_taught' => 0,
        'students_graded' => 0,
        'total_grades' => 0,
        'avg_grade' => 0
    ];

    // Count subjects taught
    $stmt = $db->prepare("SELECT COUNT(DISTINCT subject_id) AS count FROM grades WHERE teacher_id = ?");
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $stats['subjects_taught'] = $row['count'];
    }
    $stmt->close();

    // Count grades given
    $stmt = $db->prepare("SELECT COUNT(*) AS count, COUNT(DISTINCT student_id) AS students, AVG(ocjena) AS avg 
                         FROM grades WHERE teacher_id = ?");
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $stats['total_grades'] = $row['count'];
        $stats['students_graded'] = $row['students'];
        if (!is_null($row['avg'])) {
            $stats['avg_grade'] = number_format($row['avg'], 2);
        }
    }
    $stmt->close();

    return $stats;
}

/**
 * Get student dashboard statistics
 * 
 * @param object $db Database connection
 * @param int $student_id Student ID
 * @return array Statistics array
 */
function getStudentStats($db, $student_id)
{
    $stats = [
        'total_subjects' => 0,
        'average_grade' => 0,
        'total_grades' => 0,
        'highest_grade' => 0,
        'student_year' => 0,
        'student_jbmag' => '',
        'student_status' => ''
    ];

    // Get student info
    $stmt = $db->prepare("SELECT jbmag, godina, status FROM students WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $stats['student_jbmag'] = $row['jbmag'];
        $stats['student_year'] = $row['godina'];
        $stats['student_status'] = $row['status'];
    }
    $stmt->close();

    // Count enrolled subjects
    $stmt = $db->prepare("SELECT COUNT(*) AS count FROM enrollments WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $stats['total_subjects'] = $row['count'];
    }
    $stmt->close();

    // Get grade statistics
    $stmt = $db->prepare("SELECT COUNT(*) AS count, AVG(ocjena) AS avg, MAX(ocjena) AS max 
                         FROM grades WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $stats['total_grades'] = $row['count'];
        if (!is_null($row['avg'])) {
            $stats['average_grade'] = number_format($row['avg'], 2);
        }
        $stats['highest_grade'] = $row['max'] ?? 0;
    }
    $stmt->close();

    return $stats;
}

/**
 * Get recent grades for admin dashboard
 * 
 * @param object $db Database connection
 * @param int $limit Number of grades to return
 * @return array Recent grades
 */
function getRecentGrades($db, $limit = 5)
{
    $grades = [];

    // Use consistent field names with get_grades_with_details to avoid issues
    $query = "SELECT g.id, g.ocjena, g.ishod, g.created_at, s.naziv as subject_name, 
              u1.ime, u1.prezime,  
              u2.ime as teacher_ime, u2.prezime as teacher_prezime 
              FROM grades g
              LEFT JOIN subjects s ON g.subject_id = s.id
              LEFT JOIN students st ON g.student_id = st.id
              LEFT JOIN users u1 ON st.user_id = u1.id
              LEFT JOIN teachers t ON g.teacher_id = t.id
              LEFT JOIN users u2 ON t.user_id = u2.id
              ORDER BY g.created_at DESC
              LIMIT ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $grades[] = $row;
    }

    $stmt->close();
    return $grades;
}

/**
 * Get recent students for admin dashboard
 * 
 * @param object $db Database connection
 * @param int $limit Number of students to return
 * @return array Recent students
 */
function getRecentStudents($db, $limit = 5)
{
    $students = [];

    $query = "SELECT s.id, s.jbmag, s.godina, s.status, s.created_at, u.ime, u.prezime, u.email
              FROM students s
              LEFT JOIN users u ON s.user_id = u.id
              ORDER BY s.created_at DESC
              LIMIT ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    $stmt->close();
    return $students;
}

/**
 * Get student grades 
 * 
 * @param object $db Database connection
 * @param int $student_id Student ID
 * @param int $limit Number of grades to return
 * @return array Student grades
 */
function getStudentGrades($db, $student_id, $limit = 10)
{
    $grades = [];

    $query = "SELECT g.id, g.ocjena, g.created_at, s.naziv as subject_name, 
              u.ime as teacher_ime, u.prezime as teacher_prezime 
              FROM grades g
              LEFT JOIN subjects s ON g.subject_id = s.id
              LEFT JOIN teachers t ON g.teacher_id = t.id
              LEFT JOIN users u ON t.user_id = u.id
              WHERE g.student_id = ?
              ORDER BY g.created_at DESC
              LIMIT ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $student_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $grades[] = $row;
    }

    $stmt->close();
    return $grades;
}

/**
 * Get teacher's subjects
 * 
 * @param object $db Database connection
 * @param int $teacher_id Teacher ID
 * @return array Teacher subjects
 */
function getTeacherSubjects($db, $teacher_id)
{
    $subjects = [];

    $query = "SELECT DISTINCT s.id, s.naziv, s.ects, s.semestar, s.created_at, 
             (SELECT COUNT(DISTINCT student_id) FROM grades WHERE subject_id = s.id AND teacher_id = ?) as student_count
              FROM grades g
              JOIN subjects s ON g.subject_id = s.id
              WHERE g.teacher_id = ?
              GROUP BY s.id, s.naziv, s.ects, s.semestar, s.created_at
              ORDER BY s.semestar, s.created_at DESC";

    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $teacher_id, $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }

    $stmt->close();
    return $subjects;
}

/**
 * Get teacher's students
 * 
 * @param object $db Database connection
 * @param int $teacher_id Teacher ID
 * @param int $limit Number of students to return
 * @return array Teacher's students
 */
function getTeacherStudents($db, $teacher_id, $limit = 10)
{
    $students = [];

    $query = "SELECT DISTINCT s.id, s.jbmag, s.godina, s.status, u.ime, u.prezime
              FROM grades g
              JOIN students s ON g.student_id = s.id
              JOIN users u ON s.user_id = u.id
              WHERE g.teacher_id = ?
              ORDER BY u.prezime, u.ime
              LIMIT ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $teacher_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    $stmt->close();
    return $students;
}

/**
 * Get student's enrolled subjects
 * 
 * @param object $db Database connection
 * @param int $student_id Student ID
 * @return array Enrolled subjects
 */
function getEnrolledSubjects($db, $student_id)
{
    $subjects = [];

    $query = "SELECT s.id, s.naziv, s.ects, s.semestar, e.godina, e.created_at as enrollment_date
              FROM enrollments e
              JOIN subjects s ON e.subject_id = s.id
              WHERE e.student_id = ?
              ORDER BY e.godina DESC, s.semestar";

    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }

    $stmt->close();
    return $subjects;
}
