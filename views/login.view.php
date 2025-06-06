<?php require(__DIR__ . '/partials/head.php') ?>
<?php require(__DIR__ . '/partials/nav.php') ?>
<?php require(__DIR__ . '/partials/banner.php') ?>

<main class="main-wrapper">
    <div class="form-container">
        <div class="form-card">
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/StudentGrader/login">
                <div class="form-group">
                    <label class="form-label" for="username">
                        Username
                    </label>
                    <input
                        class="form-input"
                        id="username"
                        name="username"
                        type="text"
                        placeholder="Enter your username"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                        required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">
                        Password
                    </label>
                    <input
                        class="form-input"
                        id="password"
                        name="password"
                        type="password"
                        placeholder="Enter your password"
                        required>
                </div>

                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">
                        Sign In
                    </button>
                </div>
            </form>

            <div class="form-demo-info">
                <h3>Demo Accounts:</h3>
                <p><strong>Admin:</strong> admin / password123</p>
                <p><strong>Teacher:</strong> teacher / teach2024</p>
                <p><strong>Student:</strong> student / study2024</p>
            </div>
        </div>
    </div>
</main>

<?php require(__DIR__ . '/partials/footer.php') ?>