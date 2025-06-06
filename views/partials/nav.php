<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-content">
            <img class="navbar-logo" src="https://vub.hr/wp-content/uploads/2021/12/Logo_VUB_hrv.svg" alt="VUB Logo">
            <div class="navbar-links">
                <a href="/StudentGrader/" class="<?= urlIs('/StudentGrader/') ? 'active' : '' ?>">Home</a>
                <?php if (isLoggedIn()): ?>
                    <a href="/StudentGrader/dashboard" class="<?= urlIs('/StudentGrader/dashboard') ? 'active' : '' ?>">Dashboard</a>
                <?php endif; ?>
                <a href="/StudentGrader/about" class="<?= urlIs('/StudentGrader/about') ? 'active' : '' ?>">About</a>
                <a href="/StudentGrader/contact" class="<?= urlIs('/StudentGrader/contact') ? 'active' : '' ?>">Contact</a>

                <?php if (isLoggedIn()): ?>
                    <span>Welcome, <?= htmlspecialchars(getCurrentUser()) ?>!</span>
                    <a href="/StudentGrader/logout" class="btn btn-danger">Logout</a>
                <?php else: ?>
                    <a href="/StudentGrader/login" class="<?= urlIs('/StudentGrader/login') ? 'active' : '' ?> btn btn-primary">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>