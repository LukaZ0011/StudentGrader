-- Clear existing data in reverse dependency order
DELETE FROM grades;
DELETE FROM enrollments;
DELETE FROM subjects;
DELETE FROM students;
DELETE FROM teachers;
DELETE FROM users;

-- Reset auto-increment counters
ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE students AUTO_INCREMENT = 1;
ALTER TABLE teachers AUTO_INCREMENT = 1;
ALTER TABLE subjects AUTO_INCREMENT = 1;
ALTER TABLE enrollments AUTO_INCREMENT = 1;
ALTER TABLE grades AUTO_INCREMENT = 1;

-- =============================
-- USERS Data
-- =============================
INSERT INTO users (ime, prezime, email, password, role) VALUES 
('Ivan', 'Horvat', 'ivan.horvat@email.com', 'lozinka123', 'admin'),
('Ana', 'Kovac', 'ana.kovac@email.com', 'lozinka123', 'teacher'),
('Marko', 'Babic', 'marko.babic@email.com', 'lozinka123', 'teacher'),
('Petra', 'Novak', 'petra.novak@email.com', 'lozinka123', 'student'),
('Luka', 'Vidic', 'luka.vidic@email.com', 'lozinka123', 'student'),
('Marija', 'Kralj', 'marija.kralj@email.com', 'lozinka123', 'student'),
('Tomislav', 'Juric', 'tomislav.juric@email.com', 'lozinka123', 'student'),
('Ivana', 'Lovric', 'ivana.lovric@email.com', 'lozinka123', 'student');

-- =============================
-- STUDENTS Data
-- =============================
INSERT INTO students (user_id, jbmag, godina, status) VALUES 
(4, 'ST12345', 2, 'redovni'),
(5, 'ST12346', 3, 'redovni'),
(6, 'ST12347', 1, 'vanredni'),
(7, 'ST12348', 4, 'redovni'),
(8, 'ST12349', 2, 'vanredni');

-- =============================
-- TEACHERS Data
-- =============================
INSERT INTO teachers (user_id, podrucje) VALUES 
(2, 'sestrinstvo'),
(3, 'racunarstvo');

-- =============================
-- SUBJECTS Data
-- =============================
INSERT INTO subjects (naziv, ects, semestar) VALUES 
('Matematika 1', 6, 1),
('Programiranje 1', 7, 1),
('Baze podataka', 6, 3),
('Web dizajn', 5, 4),
('Operacijski sustavi', 6, 5),
('Strukture podataka', 6, 3),
('Arhitektura racunala', 5, 2);

-- =============================
-- ENROLLMENTS Data
-- =============================
INSERT INTO enrollments (student_id, subject_id, godina) VALUES 
(1, 1, 2023),
(1, 2, 2023),
(2, 3, 2023),
(2, 6, 2023),
(3, 1, 2023),
(3, 7, 2023),
(4, 4, 2023),
(4, 5, 2023),
(5, 2, 2023),
(5, 3, 2023);

-- =============================
-- GRADES Data
-- =============================
INSERT INTO grades (student_id, subject_id, teacher_id, ishod, ocjena) VALUES 
(1, 1, 1, 'Polozen ispit', 5),
(2, 2, 2, 'Nije polozen ispit', 2),
(3, 3, 1, 'Polozen ispit', 4),
(4, 4, 1, 'Polozen ispit', 3),
(5, 5, 1, 'Nije polozen ispit', 1),
(1, 6, 2, 'Polozen ispit', 5),
(2, 7, 1, 'Polozen ispit', 4),
(3, 1, 2, 'Nije polozen ispit', 2),
(4, 2, 1, 'Polozen ispit', 3),
(5, 3, 2, 'Polozen ispit', 5),
(1, 2, 2, 'Kolokvij 1', 4),
(1, 2, 2, 'Kolokvij 2', 5),
(1, 3, 1, 'Seminar', 3),
(2, 2, 2, 'Kolokvij 1', 3),
(2, 2, 2, 'Kolokvij 2', 4),
(2, 3, 1, 'Seminar', 5),
(3, 2, 2, 'Kolokvij 1', 2),
(3, 2, 2, 'Kolokvij 2', 3),
(3, 4, 1, 'Projekt', 5),
(4, 3, 1, 'Seminar', 4);
