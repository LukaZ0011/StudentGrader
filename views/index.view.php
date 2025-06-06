<?php require(__DIR__ . '/partials/head.php') ?>
<?php require(__DIR__ . '/partials/nav.php') ?>
<?php require(__DIR__ . '/partials/banner.php') ?>

<main class="main-wrapper">
    <div class="content-wrapper">
        <?php if (isLoggedIn()): ?>
            <div class="alert alert-success mb-6">
                <h2>Welcome back, <?= htmlspecialchars(getCurrentUser()) ?>!</h2>
                <p>You are successfully logged in to the Student Grader system.</p>
            </div>

            <div class="feature-grid">
                <div class="feature-card">
                    <h3>📚 Assignments</h3>
                    <p>View and manage student assignments.</p>
                    <button class="btn btn-primary">View Assignments</button>
                </div>

                <div class="feature-card">
                    <h3>📊 Grades</h3>
                    <p>Track student performance and grades.</p>
                    <button class="btn btn-success">View Grades</button>
                </div>

                <div class="feature-card">
                    <h3>👥 Students</h3>
                    <p>Manage student information and enrollment.</p>
                    <button class="btn btn-secondary">Manage Students</button>
                </div>
            </div>
        <?php else: ?>
            <div class="welcome-section">
                <h2>Welcome to Student Grader</h2>
                <p>A comprehensive platform for managing student assignments and grades.</p>

                <div class="welcome-card">
                    <h3>Get Started</h3>
                    <p>Please log in to access the grading system.</p>
                    <a href="/StudentGrader/login" class="btn btn-primary">
                        Login Now
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require(__DIR__ . '/partials/footer.php') ?>