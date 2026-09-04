    </main>

    <!-- Footer -->
    <footer class="club-footer">
        <div class="container">
            <div class="footer-grid">
                <!-- Club Info -->
                <div class="footer-col">
                    <h3><?php echo SITE_NAME; ?></h3>
                    <p>Established <?php echo getSettings('club_established') ?: '2026'; ?></p>
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo getSettings('stadium_name') ?: 'Farasi Lane'; ?></p>
                    <p><i class="fas fa-phone"></i> <?php echo getSettings('contact_phone') ?: '+254 700 000 000'; ?></p>
                    <p><i class="fas fa-envelope"></i> <?php echo getSettings('contact_email') ?: 'info@cledanfc.com'; ?></p>
                </div>
                
                <!-- Quick Links -->
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/squad">Squad</a></li>
                        <li><a href="/matches">Fixtures & Results</a></li>
                        <li><a href="/tickets">Tickets</a></li>
                        <li><a href="/news">News</a></li>
                        <li><a href="/about">About Us</a></li>
                    </ul>
                </div>
                
                <!-- Social Media -->
                <div class="footer-col">
                    <h4>Follow Us</h4>
                    <div class="social-links">
                        <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="#" target="_blank"><i class="fab fa-youtube"></i></a>
                        <a href="#" target="_blank"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                
                <!-- Newsletter -->
                <div class="footer-col">
                    <h4>Newsletter</h4>
                    <p>Subscribe for the latest news and updates</p>
                    <form class="newsletter-form" action="/subscribe.php" method="POST">
                        <input type="email" name="email" placeholder="Your email address" required>
                        <button type="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
                <p>Designed with <i class="fas fa-heart" style="color: #e74c3c;"></i> by CLEDAN FC</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="/assets/js/main.js"></script>
    <?php if (isset($extraJs)) echo $extraJs; ?>
</body>
</html>