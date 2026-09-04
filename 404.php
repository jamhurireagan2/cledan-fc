<?php
$pageTitle = 'Page Not Found';
require_once 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>404 - Page Not Found</h1>
        <p>Oops! The page you're looking for doesn't exist.</p>
    </div>
</section>

<section class="py-40">
    <div class="container">
        <div class="error-page">
            <div class="error-icon">
                <i class="fas fa-frown"></i>
            </div>
            <h2>Page Not Found</h2>
            <p>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
            <div class="error-actions">
                <a href="/" class="btn btn-primary">Go to Homepage</a>
                <a href="/squad" class="btn btn-secondary">View Squad</a>
            </div>
        </div>
    </div>
</section>

<style>
.error-page {
    text-align: center;
    padding: 40px 20px;
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.error-icon {
    font-size: 5rem;
    color: var(--gold);
    margin-bottom: 20px;
}

.error-page h2 {
    font-size: 2rem;
    margin-bottom: 15px;
}

.error-page p {
    color: var(--dark-gray);
    font-size: 1.1rem;
    max-width: 500px;
    margin: 0 auto 30px;
}

.error-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}
</style>

<?php require_once 'includes/footer.php'; ?>