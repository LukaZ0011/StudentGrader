<?php require(__DIR__ . '/partials/head.php') ?>
<?php require(__DIR__ . '/partials/nav.php') ?>
<?php require(__DIR__ . '/partials/banner.php') ?>

<main class="main-wrapper">
    <div class="content-wrapper">
        <!-- Stats Overview -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-card-inner">
                        <div class="stat-icon blue">📝</div>
                        <div class="stat-content">
                            <dt class="stat-label">Total Assignments</dt>
                            <dd class="stat-value"><?= $stats['total_assignments'] ?></dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-card-inner">
                        <div class="stat-icon yellow">⏳</div>
                        <div class="stat-content">
                            <dt class="stat-label">Pending Grades</dt>
                            <dd class="stat-value"><?= $stats['pending_grades'] ?></dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-card-inner">
                        <div class="stat-icon green">👥</div>
                        <div class="stat-content">
                            <dt class="stat-label">Total Students</dt>
                            <dd class="stat-value"><?= $stats['total_students'] ?></dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-card-inner">
                        <div class="stat-icon purple">📊</div>
                        <div class="stat-content">
                            <dt class="stat-label">Average Score</dt>
                            <dd class="stat-value"><?= $stats['avg_score'] ?>%</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Assignments -->
        <div class="table-container">
            <div class="table-header">
                <h3 class="table-title">Recent Assignments</h3>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead class="table-head">
                        <tr>
                            <th>Assignment</th>
                            <th>Due Date</th>
                            <th>Submissions</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        <?php foreach ($recent_assignments as $assignment): ?>
                            <tr>
                                <td class="font-medium">
                                    <?= htmlspecialchars($assignment['title']) ?>
                                </td>
                                <td>
                                    <?= date('M j, Y', strtotime($assignment['due_date'])) ?>
                                </td>
                                <td>
                                    <?= $assignment['submissions'] ?> / <?= $assignment['total'] ?>
                                </td>
                                <td>
                                    <?php
                                    $completion = ($assignment['submissions'] / $assignment['total']) * 100;
                                    $status_class = $completion >= 80 ? 'status-success' : ($completion >= 50 ? 'status-warning' : 'status-danger');
                                    ?>
                                    <span class="status-badge <?= $status_class ?>">
                                        <?= round($completion) ?>% Complete
                                    </span>
                                </td>
                                <td class="table-actions">
                                    <a href="#">Grade</a>
                                    <a href="#">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <div class="action-card">
                <h4>📝 Create Assignment</h4>
                <p>Create a new assignment for your students.</p>
                <button class="btn btn-primary">New Assignment</button>
            </div>

            <div class="action-card">
                <h4>📊 Grade Submissions</h4>
                <p>Review and grade pending submissions.</p>
                <button class="btn btn-success">Start Grading</button>
            </div>

            <div class="action-card">
                <h4>👥 Manage Students</h4>
                <p>Add or update student information.</p>
                <button class="btn btn-secondary">Manage Students</button>
            </div>
        </div>
    </div>
</main>

<?php require(__DIR__ . '/partials/footer.php') ?>