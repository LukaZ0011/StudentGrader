<?php require(__DIR__ . '/partials/head.php') ?>
<?php require(__DIR__ . '/partials/nav.php') ?>
<?php require(__DIR__ . '/partials/banner.php') ?>

<main class="main-wrapper">
    <div class="content-wrapper">
        <!-- Admin Stats Overview -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-card-inner">
                        <div class="stat-icon blue">👥</div>
                        <div class="stat-content">
                            <dt class="stat-label">Total Users</dt>
                            <dd class="stat-value"><?= $stats['total_users'] ?></dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-card-inner">
                        <div class="stat-icon green">🎓</div>
                        <div class="stat-content">
                            <dt class="stat-label">Students</dt>
                            <dd class="stat-value"><?= $stats['total_students'] ?></dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-card-inner">
                        <div class="stat-icon purple">👨‍🏫</div>
                        <div class="stat-content">
                            <dt class="stat-label">Teachers</dt>
                            <dd class="stat-value"><?= $stats['total_teachers'] ?></dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-card-inner">
                        <div class="stat-icon orange">📚</div>
                        <div class="stat-content">
                            <dt class="stat-label">Subjects</dt>
                            <dd class="stat-value"><?= $stats['total_subjects'] ?></dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-card-inner">
                        <div class="stat-icon yellow">📝</div>
                        <div class="stat-content">
                            <dt class="stat-label">Total Grades</dt>
                            <dd class="stat-value"><?= $stats['total_grades'] ?></dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-card-inner">
                        <div class="stat-icon red">📊</div>
                        <div class="stat-content">
                            <dt class="stat-label">Average Grade</dt>
                            <dd class="stat-value"><?= $stats['avg_score'] ?></dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Actions -->
        <div class="dashboard-actions">
            <h2 class="section-title">Quick Actions</h2>
            <div class="action-cards">
                <div class="action-card">
                    <div class="action-icon">👤</div>
                    <h3>Manage Users</h3>
                    <p>Add, edit, and remove users from the system</p>
                    <button class="btn btn-primary" onclick="window.location.href='/StudentGrader/users'">Manage Users</button>
                </div>

                <div class="action-card">
                    <div class="action-icon">🎓</div>
                    <h3>Manage Students</h3>
                    <p>View and manage student records and enrollments</p>
                    <button class="btn btn-primary" onclick="window.location.href='/StudentGrader/students'">Manage Students</button>
                </div>

                <div class="action-card">
                    <div class="action-icon">👨‍🏫</div>
                    <h3>Manage Teachers</h3>
                    <p>View and manage teacher profiles and assignments</p>
                    <button class="btn btn-primary" onclick="window.location.href='/StudentGrader/teachers'">Manage Teachers</button>
                </div>

                <div class="action-card">
                    <div class="action-icon">📚</div>
                    <h3>Manage Subjects</h3>
                    <p>Add, edit, and organize course subjects</p>
                    <button class="btn btn-primary" onclick="window.location.href='/StudentGrader/subjects'">Manage Subjects</button>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="dashboard-section">
            <h2 class="section-title">Recent Activity</h2>
            <div class="dashboard-columns">
                <div class="dashboard-column">
                    <div class="dashboard-card">
                        <h3 class="card-title">Recent Grades</h3>
                        <div class="card-content">
                            <?php if (!empty($recent_grades)): ?>
                                <div class="activity-list">
                                    <?php foreach ($recent_grades as $grade): ?>
                                        <div class="activity-item">
                                            <div class="activity-info">
                                                <strong><?= htmlspecialchars(($grade['ime'] ?? '') . ' ' . ($grade['prezime'] ?? '')) ?></strong>
                                                <span><?= htmlspecialchars($grade['subject_name'] ?? '') ?></span>
                                                <small><?= htmlspecialchars($grade['ishod'] ?? '') ?></small>
                                            </div>
                                            <div class="activity-grade grade-<?= $grade['ocjena'] ?? 0 ?>">
                                                <?= $grade['ocjena'] ?? 'N/A' ?>
                                            </div>
                                            <div class="activity-date">
                                                <?= isset($grade['created_at']) ? date('M j, Y', strtotime($grade['created_at'])) : 'N/A' ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="no-data">No recent grades found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="dashboard-column">
                    <div class="dashboard-card">
                        <h3 class="card-title">Recent Students</h3>
                        <div class="card-content">
                            <?php if (!empty($recent_students)): ?>
                                <div class="activity-list">
                                    <?php foreach ($recent_students as $student): ?>
                                        <div class="activity-item">
                                            <div class="activity-info">
                                                <strong><?= htmlspecialchars(($student['ime'] ?? '') . ' ' . ($student['prezime'] ?? '')) ?></strong>
                                                <span>JMBAG: <?= htmlspecialchars($student['jbmag'] ?? '') ?></span>
                                                <small>Year <?= $student['godina'] ?? '' ?></small>
                                            </div>
                                            <div class="activity-status">
                                                <span class="status-badge status-<?= strtolower($student['status'] ?? 'unknown') ?>">
                                                    <?= ucfirst($student['status'] ?? 'Unknown') ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="no-data">No recent students found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require(__DIR__ . '/partials/footer.php') ?>

<style>
    .dashboard-actions {
        margin: 2rem 0;
    }

    .action-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .action-card {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .action-card:hover {
        transform: translateY(-2px);
    }

    .action-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .action-card h3 {
        margin: 0 0 0.5rem 0;
        color: #333;
    }

    .action-card p {
        color: #666;
        margin: 0 0 1rem 0;
        font-size: 0.9rem;
    }

    .activity-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #eee;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-info {
        flex: 1;
    }

    .activity-info strong {
        display: block;
        margin-bottom: 0.25rem;
    }

    .activity-info span {
        color: #666;
        font-size: 0.9rem;
    }

    .activity-info small {
        display: block;
        color: #999;
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    .activity-grade {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        margin: 0 1rem;
    }

    .grade-1 {
        background-color: #dc3545;
    }

    .grade-2 {
        background-color: #fd7e14;
    }

    .grade-3 {
        background-color: #ffc107;
    }

    .grade-4 {
        background-color: #20c997;
    }

    .grade-5 {
        background-color: #28a745;
    }

    .activity-date {
        font-size: 0.8rem;
        color: #999;
        text-align: right;
    }

    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .status-redovni {
        background-color: #d4edda;
        color: #155724;
    }

    .status-vanredni {
        background-color: #fff3cd;
        color: #856404;
    }
</style>