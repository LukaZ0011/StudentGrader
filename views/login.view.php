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
                    <label class="form-label" for="email">
                        Email
                    </label>
                    <input
                        class="form-input"
                        id="email"
                        name="email"
                        type="email"
                        placeholder="Enter your email"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
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
                <p><strong>Admin:</strong> ivan.horvat@email.com / lozinka123</p>
                <p><strong>Teacher:</strong> ana.kovac@email.com / lozinka123</p>
                <p><strong>Student:</strong> petra.novak@email.com / lozinka123</p>
            </div>
        </div>
    </div>
</main>

<?php require(__DIR__ . '/partials/footer.php') ?>