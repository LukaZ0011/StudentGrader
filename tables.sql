-- Drop existing tables (in reverse dependency order)
DROP TABLE IF EXISTS grades;
DROP TABLE IF EXISTS enrollments;
DROP TABLE IF EXISTS subjects;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS teachers;
DROP TABLE IF EXISTS users;

-- =============================
-- USERS table
-- =============================
CREATE TABLE users (
   id         INT AUTO_INCREMENT PRIMARY KEY,
   ime        VARCHAR(50) NOT NULL,
   prezime    VARCHAR(50) NOT NULL,
   email      VARCHAR(60) NOT NULL UNIQUE,
   password   VARCHAR(255) NOT NULL,
   role       VARCHAR(20) NOT NULL,
   created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
   updated_at DATETIME,
   CONSTRAINT chk_users_role CHECK (role IN ('student', 'teacher', 'admin'))
);

-- =============================
-- STUDENTS table
-- =============================
CREATE TABLE students (
   id         INT AUTO_INCREMENT PRIMARY KEY,
   user_id    INT NOT NULL,
   jbmag      VARCHAR(20) UNIQUE NOT NULL,
   godina     INT NOT NULL,
   status     VARCHAR(20) NOT NULL,
   created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
   updated_at DATETIME,
   CONSTRAINT fk_students_user FOREIGN KEY (user_id)
      REFERENCES users (id) ON DELETE CASCADE,
   CONSTRAINT chk_students_godina CHECK (godina BETWEEN 1 AND 6),
   CONSTRAINT chk_students_status CHECK (status IN ('redovni', 'vanredni'))
);

-- =============================
-- TEACHERS table
-- =============================
CREATE TABLE teachers (
   id         INT AUTO_INCREMENT PRIMARY KEY,
   user_id    INT NOT NULL,
   podrucje   VARCHAR(100),
   created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
   updated_at DATETIME,
   CONSTRAINT fk_teachers_user FOREIGN KEY (user_id)
      REFERENCES users (id) ON DELETE CASCADE
);

-- =============================
-- SUBJECTS table
-- =============================
CREATE TABLE subjects (
   id         INT AUTO_INCREMENT PRIMARY KEY,
   naziv      VARCHAR(100) NOT NULL,
   ects       INT NOT NULL,
   semestar   INT NOT NULL,
   created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
   updated_at DATETIME,
   CONSTRAINT chk_subjects_ects CHECK (ects > 0),
   CONSTRAINT chk_subjects_semestar CHECK (semestar BETWEEN 1 AND 12)
);

-- =============================
-- ENROLLMENTS table
-- =============================
CREATE TABLE enrollments (
   id         INT AUTO_INCREMENT PRIMARY KEY,
   student_id INT NOT NULL,
   subject_id INT NOT NULL,
   godina     INT NOT NULL,
   created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
   updated_at DATETIME,
   CONSTRAINT fk_enroll_student FOREIGN KEY (student_id)
      REFERENCES students (id) ON DELETE CASCADE,
   CONSTRAINT fk_enroll_subject FOREIGN KEY (subject_id)
      REFERENCES subjects (id) ON DELETE CASCADE,
   CONSTRAINT uq_student_subject UNIQUE (student_id, subject_id, godina)
);

-- =============================
-- GRADES table
-- =============================
CREATE TABLE grades (
   id            INT AUTO_INCREMENT PRIMARY KEY,
   student_id    INT NOT NULL,
   subject_id    INT NOT NULL,
   teacher_id    INT NOT NULL,
   ishod         VARCHAR(100) NOT NULL,
   ocjena        INT NOT NULL,
   created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
   updated_at    DATETIME,
   CONSTRAINT fk_grade_subject FOREIGN KEY (subject_id)
      REFERENCES subjects (id) ON DELETE CASCADE,
   CONSTRAINT fk_grade_teacher FOREIGN KEY (teacher_id)
      REFERENCES teachers (id) ON DELETE CASCADE,
   CONSTRAINT fk_grade_student FOREIGN KEY (student_id)
      REFERENCES students (id) ON DELETE CASCADE,
   CONSTRAINT chk_grades_ocjena CHECK (ocjena BETWEEN 1 AND 5)
);

-- =============================
-- Triggers for audit fields
-- =============================
DELIMITER $$

CREATE TRIGGER trg_update_timestamp_users
BEFORE UPDATE ON users
FOR EACH ROW
BEGIN
   SET NEW.updated_at = CURRENT_TIMESTAMP;
END$$

CREATE TRIGGER trg_update_timestamp_students
BEFORE UPDATE ON students
FOR EACH ROW
BEGIN
   SET NEW.updated_at = CURRENT_TIMESTAMP;
END$$

CREATE TRIGGER trg_update_timestamp_teachers
BEFORE UPDATE ON teachers
FOR EACH ROW
BEGIN
   SET NEW.updated_at = CURRENT_TIMESTAMP;
END$$

CREATE TRIGGER trg_update_timestamp_subjects
BEFORE UPDATE ON subjects
FOR EACH ROW
BEGIN
   SET NEW.updated_at = CURRENT_TIMESTAMP;
END$$

CREATE TRIGGER trg_update_timestamp_enrollments
BEFORE UPDATE ON enrollments
FOR EACH ROW
BEGIN
   SET NEW.updated_at = CURRENT_TIMESTAMP;
END$$

CREATE TRIGGER trg_update_timestamp_grades
BEFORE UPDATE ON grades
FOR EACH ROW
BEGIN
   SET NEW.updated_at = CURRENT_TIMESTAMP;
END$$

DELIMITER ;
