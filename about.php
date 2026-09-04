<?php
$currentPage = 'about';
$pageTitle = 'About Us';
require_once 'includes/functions.php';
require_once 'includes/database.php';

$settings = getSettings();

require_once 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>About CLEDAN FC</h1>
        <p>Our story, values, and vision</p>
    </div>
</section>

<section class="py-40">
    <div class="container">
        <div class="about-content">
            <div class="about-hero">
                <div class="about-text">
                    <h2>Welcome to CLEDAN FC</h2>
                    <p>Founded in <?php echo $settings['club_established'] ?? '2026'; ?>, CLEDAN FC is a football club built on passion, pride, and community spirit.</p>
                    <p>Our journey began at <?php echo $settings['stadium_name'] ?? 'Farasi Lane'; ?>, where we continue to inspire and develop talent while fostering a strong connection with our fans and community.</p>
                    
                    <div class="about-values">
                        <div class="value-item">
                            <i class="fas fa-heart"></i>
                            <h4>Passion</h4>
                            <p>We play with heart and dedication, giving our all for the badge and the fans.</p>
                        </div>
                        <div class="value-item">
                            <i class="fas fa-handshake"></i>
                            <h4>Community</h4>
                            <p>We believe in the power of football to unite and inspire our community.</p>
                        </div>
                        <div class="value-item">
                            <i class="fas fa-star"></i>
                            <h4>Excellence</h4>
                            <p>We strive for excellence in everything we do, on and off the pitch.</p>
                        </div>
                    </div>
                </div>
                <div class="about-image">
                    <div style="background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue)); border-radius: var(--border-radius); padding: 40px; text-align: center; color: white; min-height: 300px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                        <i class="fas fa-shield-alt" style="font-size: 6rem; color: var(--gold); margin-bottom: 20px;"></i>
                        <h3>CLEDAN FC</h3>
                        <p style="opacity: 0.8;">Est. <?php echo $settings['club_established'] ?? '2026'; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="about-stadium">
                <h2>Our Home</h2>
                <div class="stadium-info">
                    <div class="stadium-details">
                        <h3><?php echo $settings['stadium_name'] ?? 'Farasi Lane'; ?></h3>
                        <p><?php echo $settings['stadium_location'] ?? 'Farasi Lane Primary School'; ?></p>
                        <p class="stadium-description">Our home ground where we play our home matches and train daily. A place where memories are made and legends are born.</p>
                    </div>
                    <div class="stadium-icon">
                        <i class="fas fa-football" style="font-size: 5rem; color: var(--gold);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.about-hero {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    margin-bottom: 60px;
}

.about-text h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.about-text p {
    color: var(--dark-gray);
    font-size: 1.05rem;
    margin-bottom: 15px;
    line-height: 1.8;
}

.about-values {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 20px;
    margin-top: 30px;
}

.value-item {
    background: var(--white);
    padding: 20px;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    text-align: center;
    transition: all 0.3s ease;
}

.value-item:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-hover);
}

.value-item i {
    font-size: 2rem;
    color: var(--gold);
    margin-bottom: 10px;
}

.value-item h4 {
    margin-bottom: 8px;
}

.value-item p {
    font-size: 0.9rem;
    margin: 0;
}

.about-stadium {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 40px;
    box-shadow: var(--shadow);
}

.about-stadium h2 {
    font-size: 2rem;
    margin-bottom: 30px;
}

.stadium-info {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
    align-items: center;
}

.stadium-details h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
}

.stadium-details p {
    color: var(--dark-gray);
    margin-bottom: 10px;
}

.stadium-description {
    font-size: 0.95rem;
}

.stadium-icon {
    text-align: center;
}

@media (max-width: 768px) {
    .about-hero {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .about-values {
        grid-template-columns: 1fr;
    }
    
    .stadium-info {
        grid-template-columns: 1fr;
        text-align: center;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>