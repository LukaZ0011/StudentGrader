<?php require(__DIR__ . '/partials/head.php') ?>
<?php require(__DIR__ . '/partials/nav.php') ?>
<?php require(__DIR__ . '/partials/banner.php') ?>

<main class="main-wrapper">
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Teacher Dashboard</h1>
            <p>Welcome, <?= htmlspecialchars($teacher_name) ?>!</p>
            <p class="teacher-info">Department: <?= htmlspecialchars($teacher_data['podrucje']) ?></p>
        </div>

        <div class="dashboard-content">
            <!-- Grade Management Section -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>Grade Students</h2>
                    <span class="section-subtitle">Add or update grades for your subjects</span>
                </div>

                <div class="grade-form-container">
                    <form method="POST" action="/StudentGrader/teacher-dashboard" class="grade-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="subject_id">Subject</label>
                                <select name="subject_id" id="subject_id" required onchange="loadStudents(this.value)">
                                    <option value="">Select Subject</option>
                                    <?php foreach ($teacher_subjects as $subject): ?>
                                        <option value="<?= $subject['id'] ?>"><?= htmlspecialchars($subject['naziv']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="student_id">Student</label>
                                <select name="student_id" id="student_id" required>
                                    <option value="">Select Student</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="ishod">Assessment Type</label>
                                <input type="text" name="ishod" id="ishod" placeholder="e.g., Kolokvij 1, Seminar, Final Exam" required>
                            </div>

                            <div class="form-group">
                                <label for="ocjena">Grade (1-5)</label>
                                <select name="ocjena" id="ocjena" required>
                                    <option value="">Select Grade</option>
                                    <option value="1">1 - Insufficient</option>
                                    <option value="2">2 - Sufficient</option>
                                    <option value="3">3 - Good</option>
                                    <option value="4">4 - Very Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" name="add_grade" class="btn btn-primary">Add Grade</button>
                    </form>
                </div>
            </div>

            <!-- Recent Grades Section -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>Recent Grades</h2>
                    <span class="section-subtitle">Grades you've recently added</span>
                </div>

                <?php if (!empty($recent_grades)): ?>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Subject</th>
                                    <th>Assessment</th>
                                    <th>Grade</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_grades as $grade): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($grade['student_name']) ?></td>
                                        <td><?= htmlspecialchars($grade['subject_name']) ?></td>
                                        <td><?= htmlspecialchars($grade['ishod'] ?? 'Unknown Assessment') ?></td>
                                        <td>
                                            <span class="grade-badge grade-<?= $grade['ocjena'] ?>">
                                                <?= $grade['ocjena'] ?>
                                            </span>
                                        </td>
                                        <td><?= date('M j, Y', strtotime($grade['created_at'])) ?></td>
                                        <td>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="grade_id" value="<?= $grade['id'] ?>">
                                                <button type="submit" name="delete_grade" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this grade?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p>No grades recorded yet.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- My Subjects Section -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>My Subjects</h2>
                    <span class="section-subtitle">Subjects you teach</span>
                </div>

                <?php if (!empty($teacher_subjects)): ?>
                    <div class="subjects-grid">
                        <?php foreach ($teacher_subjects as $subject): ?>
                            <div class="subject-card">
                                <h3><?= htmlspecialchars($subject['naziv']) ?></h3>
                                <p>ECTS: <?= $subject['ects'] ?> | Semester: <?= $subject['semestar'] ?></p>
                                <p>Enrolled Students: <?= $subject['student_count'] ?></p>
                                <a href="/StudentGrader/teacher-dashboard?subject_id=<?= $subject['id'] ?>" class="btn btn-sm btn-outline">
                                    View Details
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p>No subjects assigned yet.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Statistics Section -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>Teaching Summary</h2>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value"><?= count($teacher_subjects) ?></div>
                        <div class="stat-label">Subjects Teaching</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= $total_students ?></div>
                        <div class="stat-label">Total Students</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= count($recent_grades) ?></div>
                        <div class="stat-label">Grades This Month</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">
                            <?= $average_grade ? number_format($average_grade, 2) : 'N/A' ?>
                        </div>
                        <div class="stat-label">Average Grade Given</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="/StudentGrader/js/teacher-dashboard.js"></script>

<?php require(__DIR__ . '/partials/footer.php') ?>