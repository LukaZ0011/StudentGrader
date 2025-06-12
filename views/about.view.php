<?php require(__DIR__ . '/partials/head.php') ?>
<?php require(__DIR__ . '/partials/nav.php') ?>
<?php require(__DIR__ . '/partials/banner.php') ?>

<main class="main-wrapper">
    <div class="content-wrapper">
        <div class="protected-banner">
            <div class="protected-banner-content">
                <div class="protected-banner-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="protected-banner-text">
                    <h3>Protected Area</h3>
                    <p>This page requires authentication. You are logged in as: <strong><?= formatUserName() ?></strong></p>
                </div>
            </div>
        </div>

        <div class="prose">
            <h2>About Student Grader</h2>
            <p>Welcome to our comprehensive student grading system. This platform is designed to help educators efficiently manage student assignments, track progress, and provide meaningful feedback.</p>

            <h3>Key Features</h3>
            <ul>
                <li><strong>Assignment Management:</strong> Create, distribute, and collect student assignments seamlessly.</li>
                <li><strong>Automated Grading:</strong> Leverage AI-powered grading for objective assessments.</li>
                <li><strong>Progress Tracking:</strong> Monitor individual student progress and class performance.</li>
                <li><strong>Feedback System:</strong> Provide detailed, constructive feedback to help students improve.</li>
                <li><strong>Analytics Dashboard:</strong> Gain insights into learning patterns and outcomes.</li>
            </ul>

            <h3>Our Mission</h3>
            <p>We believe that effective assessment is crucial for student success. Our platform empowers educators with the tools they need to provide timely, accurate, and meaningful evaluations that support student growth and learning.</p>

            <div class="content-section">
                <h4>Getting Started</h4>
                <p>As an authenticated user, you now have access to all features of the Student Grader system. Explore the dashboard to begin managing your assignments and grades.</p>
            </div>
        </div>
    </div>
</main>

<?php require(__DIR__ . '/partials/footer.php') ?>