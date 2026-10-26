<style>
/* Footer */
.site-footer-new { background: #0369a1; color: white; padding: 4rem 0 2rem; border-top: none; font-family: 'Atkinson Hyperlegible', sans-serif; }
.footer-top { border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 3rem; margin-bottom: 3rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 2rem; }
.footer-top-left { flex: 1; min-width: 300px; }
.footer-top-left h2 { margin: 0 0 1.5rem; font-size: 2.8rem; color: white; font-weight: 800; line-height: 1.2; }
.footer-top-btn { background: #0284c7; color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 6px; font-weight: 700; text-decoration: none; display: inline-block; transition: background 0.2s; }
.footer-top-btn:hover { background: #0274b3; color: white; }
.footer-top-right { flex: 1; min-width: 300px; text-align: right; display: flex; justify-content: flex-end; }
.footer-top-desc { color: #ffffff; max-width: 450px; line-height: 1.6; font-size: 1.05rem; margin: 0; text-align: right; }
.footer-cols { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; margin-bottom: 3rem; }
.footer-col h4 { color: white; font-weight: 800; margin-bottom: 1.5rem; font-size: 1.1rem; }
.footer-col a, .footer-col p { color: #e2e8f0; text-decoration: none; display: block; margin-bottom: 0.75rem; font-size: 0.95rem; }
.footer-col a:hover { color: white; text-decoration: underline; }
.footer-bottom { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.2); color: #cbd5e1; padding-top: 2rem; font-size: 0.85rem; flex-wrap: wrap; gap: 1rem; }
.footer-bottom a { color: #cbd5e1; margin-left: 1.5rem; text-decoration: none; }
.footer-bottom a:hover { color: white; }
@media (max-width: 768px) {
	.footer-cols { grid-template-columns: 1fr 1fr; }
	.footer-top { flex-direction: column; text-align: left; align-items: flex-start; }
	.footer-top-right { justify-content: flex-start; text-align: left; }
	.footer-top-desc { text-align: left; }
	.footer-bottom { flex-direction: column; text-align: left; align-items: flex-start; }
	.footer-bottom a { margin-left: 0; margin-right: 1.5rem; }
}
@media (max-width: 480px) {
	.footer-cols { grid-template-columns: 1fr; gap: 1.5rem; }
	.footer-top-left h2 { font-size: clamp(1.8rem, 7vw, 2.2rem); }
	.footer-top { padding-bottom: 2rem; margin-bottom: 2rem; }
	.site-footer-new { padding: 3rem 0 1.5rem; }
	.footer-top-btn { width: 100%; text-align: center; }
	.footer-bottom a { margin-right: 0.75rem; margin-bottom: 0.5rem; display: inline-block; }
}

/* Pagination Styles for Search/Archive pages */
.navigation.pagination { margin: 3rem 0; text-align: center; }
.nav-links { display: inline-flex; gap: 0.5rem; align-items: center; justify-content: center; }
.nav-links .page-numbers { padding: 0.5rem 1rem; background: white; border: 1px solid #e2e8f0; border-radius: 6px; color: #1e293b; text-decoration: none; font-weight: 600; transition: all 0.2s; }
.nav-links .page-numbers:hover { background: #f8fafc; border-color: #cbd5e1; }
.nav-links .page-numbers.current { background: #0284c7; color: white; border-color: #0284c7; }
</style>

<footer class="site-footer-new">
	<div class="container">
		<div class="footer-top">
			<div class="footer-top-left">
				<h2>Know What<br>You're Taking</h2>
				<a href="#" class="footer-top-btn">Get your 50% off &rarr;</a>
			</div>
			<div class="footer-top-right">
				<p class="footer-top-desc">We investigate supplements, break down their claims, and help you understand what the evidence really says.</p>
			</div>
		</div>
		<div class="footer-cols">
			<div class="footer-col">
				<h4>Site Name</h4>
				<p>Call: +01 - 1234578000</p>
				<p>WhatsApp: +01 - 3288578200</p>
				<p>Customer Support Time: 24 | 7</p>
				<p>Email: support@sitename.com</p>
			</div>
			<div class="footer-col">
				<h4>Links</h4>
				<a href="#">Home</a>
				<a href="#">Latest Articles</a>
				<a href="#">Ingredients</a>
				<a href="#">Research</a>
				<a href="#">About Us</a>
			</div>
			<div class="footer-col">
				<h4>Categories</h4>
				<a href="#">Male Enhancement</a>
				<a href="#">Weight Loss</a>
				<a href="#">Brain Enhancement</a>
				<a href="#">Joint Pain Relief</a>
				<a href="#">Hair Growth</a>
				<a href="#">Prostate Health</a>
				<a href="#">Anti-Aging Products</a>
				<a href="#">Blood Pressure</a>
			</div>
			<div class="footer-col">
				<h4>Socials</h4>
				<a href="#">Facebook</a>
				<a href="#">Instagram</a>
			</div>
		</div>
		<div class="footer-bottom">
			<div>&copy; <?php echo date('Y'); ?> Site Name Ltd. All rights reserved. Evidence-based supplement reviews & health journals.</div>
			<div>
				<a href="#">Privacy Policy</a>
				<a href="#">Terms of Service</a>
				<a href="#">Disclaimer</a>
				<a href="#">Contact Us</a>
			</div>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
