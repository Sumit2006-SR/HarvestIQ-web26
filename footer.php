<?php
/**
 * HarvestIQ — Global Footer
 * Glass-Agri themed footer with newsletter signup
 */
?>
<footer class="hiq-footer">
    <div class="container footer-grid-layout">

        <div class="footer-brand-col">
            <div class="hiq-footer-brand">
                <div class="footer-icon-box">
            <i class="fa-solid fa-seedling footer-brand-icon"></i>
        </div>
        <h3 style="margin-bottom: 0;">Harvest<span>IQ</span></h3>
            </div>
            <p style="line-height:1.65; margin-bottom:0;">Empowering farmers with real-time market intelligence, weather advisories, and AI-driven crop guidance — built for rural India, accessible in every language.</p>

            <div class="socials">
                <a href="#" class="social-icon-btn" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="#" class="social-icon-btn" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#" class="social-icon-btn" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="social-icon-btn" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="social-icon-btn" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
            </div>
        </div>

        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul class="footer-links">
                <li><a href="index.php#home"><i class="fa-solid fa-chevron-right me-2" style="font-size:0.65rem;"></i> Home</a></li>
                <li><a href="index.php#features"><i class="fa-solid fa-chevron-right me-2" style="font-size:0.65rem;"></i> Features</a></li>
                <li><a href="index.php#mission"><i class="fa-solid fa-chevron-right me-2" style="font-size:0.65rem;"></i> Our Mission</a></li>
                <li><a href="market_prices.php"><i class="fa-solid fa-chevron-right me-2" style="font-size:0.65rem;"></i> Live Mandi Prices</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Platform</h4>
            <ul class="footer-links">
                <li><a href="advisory.php">Weather & Advisory</a></li>
                <li><a href="dashboard.php">Farmer Dashboard</a></li>
                <li><a href="profile.php">My Profile</a></li>
                <li><a href="login_signup.php">Create Account</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Contact</h4>
            <div class="footer-contact-item">
                <i class="fa-solid fa-location-dot"></i>
                <span>AgriTech Innovation Hub,<br>Green Valley, India</span>
            </div>
            <div class="footer-contact-item">
                <i class="fa-solid fa-envelope"></i>
                <span>support@harvestiq.ai</span>
            </div>
            <div class="footer-contact-item">
                <i class="fa-solid fa-phone"></i>
                <span>+91 1800-HARVEST</span>
            </div>
        </div>

        <div class="footer-col">
            <h4>Price Alerts</h4>
            <p style="font-size:0.88rem; line-height:1.55; margin-bottom:1rem;">Get daily mandi price updates and weather warnings delivered to your inbox.</p>
            <form class="newsletter-form" onsubmit="event.preventDefault(); this.querySelector('.newsletter-btn').innerText='Subscribed!';">
                <input type="email" class="newsletter-input" placeholder="your@email.com" required aria-label="Email for alerts">
                <button type="submit" class="newsletter-btn">Subscribe</button>
            </form>
        </div>

    </div>

    <div class="container footer-bottom-bar">
        <div class="copyright">&copy; <?php echo date('Y'); ?> HarvestIQ. Cultivating smarter futures. All rights reserved.</div>
        <ul class="footer-legal-links">
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Service</a></li>
            <li><a href="#">Cookie Settings</a></li>
        </ul>
    </div>
</footer>
