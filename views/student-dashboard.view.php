<?php require(__DIR__ . '/partials/head.php') ?>
<?php require(__DIR__ . '/partials/nav.php') ?>
<?php require(__DIR__ . '/partials/banner.php') ?>

<main class="main-wrapper">
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Student Dashboard</h1>
            <p>Welcome, <?= htmlspecialchars($student_name) ?>!</p>
            <p class="student-info">Student ID: <?= htmlspecialchars($student_data['jbmag']) ?> | Year: <?= $student_data['godina'] ?> | Status: <?= htmlspecialchars($student_data['status']) ?></p>
        </div>

        <div class="dashboard-content">
            <!-- My Grades Section -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>My Grades</h2>
                    <span class="section-subtitle">All your grades across enrolled subjects</span>
                </div>

                <?php if (!empty($grades)): ?>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Assessment</th>
                                    <th>Grade</th>
                                    <th>Teacher</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($grades as $grade): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($grade['subject_name']) ?></td>
                                        <td><?= htmlspecialchars($grade['ishod']) ?></td>
                                        <td>
                                            <span class="grade-badge grade-<?= $grade['ocjena'] ?>">
                                                <?= $grade['ocjena'] ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($grade['teacher_name']) ?></td>
                                        <td><?= date('M j, Y', strtotime($grade['created_at'])) ?></td>
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

            <!-- Enrolled Subjects Section -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>Enrolled Subjects</h2>
                    <span class="section-subtitle">Subjects you are currently enrolled in</span>
                </div>

                <?php if (!empty($enrollments)): ?>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>ECTS</th>
                                    <th>Semester</th>
                                    <th>Enrollment Year</th>
                                    <th>Average Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($enrollments as $enrollment): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($enrollment['subject_name']) ?></td>
                                        <td><?= $enrollment['ects'] ?></td>
                                        <td><?= $enrollment['semestar'] ?></td>
                                        <td><?= $enrollment['godina'] ?></td>
                                        <td>
                                            <?php if ($enrollment['avg_grade']): ?>
                                                <span class="grade-badge grade-<?= round($enrollment['avg_grade']) ?>">
                                                    <?= number_format($enrollment['avg_grade'], 2) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">No grades</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p>You are not enrolled in any subjects.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Statistics Section -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>Academic Summary</h2>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value"><?= count($enrollments) ?></div>
                        <div class="stat-label">Enrolled Subjects</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= count($grades) ?></div>
                        <div class="stat-label">Total Grades</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">
                            <?= $overall_average ? number_format($overall_average, 2) : 'N/A' ?>
                        </div>
                        <div class="stat-label">Overall Average</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= array_sum(array_column($enrollments, 'ects')) ?></div>
                        <div class="stat-label">Total ECTS</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require(__DIR__ . '/partials/footer.php') ?>