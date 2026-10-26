<?php
/**
 * The static front page template file based on wireframe
 * Green/White theme with static images
 */

get_header();
?>

<style>
/* Static Home Styles */
:root {
	--primary: #0284c7; /* Primary Vibrant Blue */
	--secondary: #e0f2fe; /* Light Blue bg */
	--dark-green: #0369a1; /* Darker Ocean Blue */
	--gray-100: #f8fafc;
	--gray-200: #e2e8f0;
	--gray-300: #cbd5e1;
	--gray-400: #94a3b8;
	--gray-600: #475569;
	--gray-800: #1e293b;
	--gray-900: #0f172a;
}
body { font-family: 'Roboto', sans-serif; color: var(--gray-800); background-color: white; }
.container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
.img-cover { object-fit: cover; width: 100%; height: 100%; }
.img-circle { border-radius: 50%; object-fit: cover; }

/* ============================================================
/* ============================================================
 * HERO SECTION - Luxury Editorial Longevity Design
 * Physician Background + Deep Sage Palette + Frosted Glass Spotlight Card
 * ============================================================ */
.hero-longevity-wrap {
	width: 100%;
	max-width: 100%;
	margin: 0 0 4rem 0;
	padding: 0;
	box-sizing: border-box;
}

.hero-longevity-banner {
	position: relative;
	width: 100%;
	border-radius: 0;
	overflow: hidden;
	min-height: 740px;
	display: flex;
	align-items: center;
	background-color: #12211c;
	background-image: url('<?php echo esc_url( get_template_directory_uri() . "/assets/images/doctor-hero.jpg" ); ?>');
	background-size: cover;
	background-position: 60% 18%;
	background-repeat: no-repeat;
	box-shadow: 0 16px 40px rgba(0, 0, 0, 0.18);
}

/* Atmospheric organic gradient overlay: rich dark forest-slate on left, reveals natural doctor photo on right */
.hero-longevity-overlay {
	position: absolute;
	inset: 0;
	background: linear-gradient(90deg, 
		rgba(13, 23, 19, 0.97) 0%, 
		rgba(15, 27, 23, 0.92) 35%, 
		rgba(20, 37, 31, 0.68) 58%, 
		rgba(21, 38, 32, 0.22) 80%, 
		rgba(13, 23, 19, 0.45) 100%
	);
	z-index: 1;
	pointer-events: none;
}

.hero-longevity-overlay::after {
	content: '';
	position: absolute;
	inset: 0;
	background: linear-gradient(180deg, rgba(0, 0, 0, 0) 70%, rgba(11, 20, 17, 0.75) 100%);
	pointer-events: none;
}

.hero-longevity-inner {
	position: relative;
	z-index: 2;
	width: 100%;
	max-width: 1380px;
	margin: 0 auto;
	display: grid;
	grid-template-columns: 1.25fr 0.75fr;
	align-items: center;
	justify-content: space-between;
	padding: 6.5rem 3.5rem;
	gap: 4rem;
	box-sizing: border-box;
}

/* Left Column: Bold Editorial Content */
.hero-longevity-left {
	display: flex;
	flex-direction: column;
	align-items: flex-start;
	max-width: 620px;
}

/* Kicker pill badge */
.hero-longevity-kicker {
	display: inline-flex;
	align-items: center;
	gap: 9px;
	background: rgba(255, 255, 255, 0.08);
	border: 1px solid rgba(74, 222, 128, 0.35);
	backdrop-filter: blur(12px);
	-webkit-backdrop-filter: blur(12px);
	color: #86efac;
	font-family: 'Inter', system-ui, -apple-system, sans-serif;
	font-size: 0.8rem;
	font-weight: 700;
	letter-spacing: 0.06em;
	text-transform: uppercase;
	padding: 7px 16px;
	border-radius: 9999px;
	margin-bottom: 1.75rem;
	box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
}

.hero-longevity-kicker .kicker-pulse-dot {
	width: 7px;
	height: 7px;
	background-color: #4ade80;
	border-radius: 50%;
	box-shadow: 0 0 10px #4ade80, 0 0 4px #86efac;
	animation: kickerPulse 2s infinite ease-in-out;
}

@keyframes kickerPulse {
	0%, 100% { transform: scale(1); opacity: 1; }
	50% { transform: scale(1.3); opacity: 0.7; }
}

.hero-longevity-title {
	font-family: 'Inter', system-ui, -apple-system, sans-serif;
	font-size: clamp(3.8rem, 6.2vw, 5.6rem);
	font-weight: 850;
	line-height: 0.98;
	color: #ffffff;
	letter-spacing: -0.04em;
	margin: 0 0 1.5rem 0;
	text-shadow: 0 4px 24px rgba(0, 0, 0, 0.45);
}

.hero-longevity-subtitle {
	font-family: 'Inter', system-ui, -apple-system, sans-serif;
	font-size: clamp(1.05rem, 1.4vw, 1.25rem);
	line-height: 1.6;
	color: #d1e3dc;
	margin: 0 0 2.5rem 0;
	max-width: 520px;
	font-weight: 400;
	text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.hero-longevity-action-row {
	display: flex;
	align-items: center;
	gap: 2.25rem;
	flex-wrap: wrap;
}

.hero-shop-pill-btn {
	background: #ffffff;
	color: #0b1a15;
	font-family: 'Inter', system-ui, -apple-system, sans-serif;
	font-weight: 700;
	font-size: 1rem;
	padding: 15px 32px;
	border-radius: 9999px;
	display: inline-flex;
	align-items: center;
	gap: 10px;
	text-decoration: none;
	transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
	box-shadow: 0 12px 30px -4px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.3);
}

.hero-shop-pill-btn:hover {
	background: #f0fdf4;
	color: #052e16;
	transform: translateY(-3px);
	box-shadow: 0 18px 40px -6px rgba(74, 222, 128, 0.35), 0 0 0 1px #86efac;
}

.hero-arrow-diagonal {
	width: 15px;
	height: 15px;
	transition: transform 0.25s ease;
}

.hero-shop-pill-btn:hover .hero-arrow-diagonal {
	transform: translate(3px, -3px);
}

.hero-reviews-badge {
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.hero-stars-row {
	display: flex;
	align-items: center;
	gap: 8px;
}

.hero-stars-row .stars-icons {
	color: #fbbf24;
	font-size: 1.15rem;
	line-height: 1;
	letter-spacing: 2px;
	filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
}

.hero-stars-row .stars-score {
	color: #ffffff;
	font-family: 'Inter', system-ui, -apple-system, sans-serif;
	font-weight: 700;
	font-size: 0.95rem;
	letter-spacing: -0.01em;
}

.hero-reviews-text {
	color: #a4c1b6;
	font-family: 'Inter', system-ui, -apple-system, sans-serif;
	font-size: 0.85rem;
	font-weight: 500;
}

/* Right Column: Floating Glass Spotlight Card */
.hero-longevity-right {
	position: relative;
	display: flex;
	flex-direction: column;
	align-items: flex-end;
	justify-content: center;
}

/* Crisp Minimalist White Card with Emerald Accent */
.hero-wireframe-card {
	position: relative;
	z-index: 2;
	width: 345px;
	max-width: 100%;
	background: #ffffff;
	border-radius: 20px;
	overflow: hidden;
	box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.9);
	transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s ease;
}

.hero-wireframe-card:hover {
	transform: translateY(-6px);
	box-shadow: 0 35px 75px -15px rgba(0, 0, 0, 0.55), 0 0 0 1px rgba(255, 255, 255, 1);
}

.hero-wireframe-card .hero-card-img-wrap {
	position: relative;
	margin: 0;
	background: #f8fafc;
	border-bottom: 1px solid #f1f5f9;
}

.hero-wireframe-card .hero-card-pill-tag {
	position: absolute;
	top: 14px;
	left: 14px;
	z-index: 3;
	display: inline-flex;
	align-items: center;
	gap: 5px;
	background: rgba(255, 255, 255, 0.95);
	color: #065f46;
	border: 1px solid #e2e8f0;
	font-family: 'Inter', system-ui, -apple-system, sans-serif;
	font-size: 0.72rem;
	font-weight: 700;
	letter-spacing: 0.04em;
	text-transform: uppercase;
	padding: 5px 11px;
	border-radius: 9999px;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.hero-wireframe-card .hero-card-score-badge {
	position: absolute;
	top: 14px;
	right: 14px;
	z-index: 3;
	display: inline-flex;
	align-items: baseline;
	gap: 2px;
	background: #059669;
	color: #ffffff;
	font-family: 'Inter', system-ui, -apple-system, sans-serif;
	padding: 5px 10px;
	border-radius: 8px;
	box-shadow: 0 4px 12px rgba(5, 150, 105, 0.35);
}

.hero-wireframe-card .hero-card-score-badge .score-val {
	font-size: 0.92rem;
	font-weight: 800;
	line-height: 1;
}

.hero-wireframe-card .hero-card-score-badge .score-total {
	font-size: 0.7rem;
	font-weight: 600;
	opacity: 0.85;
}

.hero-wireframe-card .hero-card-img {
	height: 210px;
	width: 100%;
	display: flex;
	align-items: center;
	justify-content: center;
	position: relative;
	overflow: hidden;
}

.hero-wireframe-card .hero-card-img img {
	width: 100%;
	height: 100%;
	object-fit: contain;
	padding: 20px;
	display: block;
	transition: transform 0.4s ease;
}

.hero-wireframe-card:hover .hero-card-img img {
	transform: scale(1.06);
}

.hero-wireframe-card .hero-card-img-placeholder {
	width: 100%;
	height: 100%;
	background: #f8fafc;
}

.hero-wireframe-card .hero-card-content {
	padding: 1.4rem 1.6rem 1.5rem;
	background: #ffffff;
	font-family: 'Inter', system-ui, -apple-system, sans-serif;
	box-sizing: border-box;
}

.hero-wireframe-card .hero-card-meta {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 0.65rem;
}

.hero-wireframe-card .meta-item {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	color: #64748b;
	font-size: 0.78rem;
	font-weight: 500;
}

.hero-wireframe-card .meta-bullet {
	width: 6px;
	height: 6px;
	background-color: #10b981;
	display: inline-block;
	border-radius: 50%;
	flex-shrink: 0;
}

.hero-wireframe-card .hero-card-title {
	font-weight: 800;
	font-size: 1.12rem;
	line-height: 1.35;
	margin: 0 0 1.25rem 0;
}

.hero-wireframe-card .hero-card-title a {
	color: #0f172a;
	text-decoration: none;
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	overflow: hidden;
	transition: color 0.2s ease;
}

.hero-wireframe-card .hero-card-title a:hover {
	color: #059669;
}

.hero-wireframe-card .hero-card-footer {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding-top: 1rem;
	border-top: 1px solid #f1f5f9;
}

.hero-wireframe-card .author-block {
	display: inline-flex;
	align-items: center;
	gap: 9px;
}

.hero-wireframe-card .author-avatar-badge {
	width: 28px;
	height: 28px;
	border-radius: 50%;
	background: #ecfdf5;
	border: 1px solid #a7f3d0;
	color: #059669;
	font-size: 0.68rem;
	font-weight: 800;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-shrink: 0;
}

.hero-wireframe-card .author-info-col {
	display: flex;
	flex-direction: column;
}

.hero-wireframe-card .author-name {
	color: #0f172a;
	font-size: 0.84rem;
	font-weight: 700;
	line-height: 1.2;
}

.hero-wireframe-card .author-badge-sub {
	color: #64748b;
	font-size: 0.7rem;
	font-weight: 500;
	line-height: 1.2;
}

.hero-wireframe-card .read-article-link {
	background: #ecfdf5;
	color: #047857;
	font-size: 0.8rem;
	font-weight: 700;
	text-decoration: none;
	display: inline-flex;
	align-items: center;
	gap: 5px;
	padding: 7px 14px;
	border-radius: 9999px;
	border: 1px solid #a7f3d0;
	transition: all 0.22s ease;
}

.hero-wireframe-card .read-article-link:hover {
	background: #059669;
	color: #ffffff;
	border-color: #059669;
	box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
}

/* Dark mode compatibility */
[data-theme="dark"] .hero-wireframe-card, body.dark-mode .hero-wireframe-card {
	background: #1e293b !important;
	box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.1) !important;
}
[data-theme="dark"] .hero-wireframe-card .hero-card-img-wrap, body.dark-mode .hero-wireframe-card .hero-card-img-wrap {
	background: #0f172a !important;
	border-bottom-color: #334155 !important;
}
[data-theme="dark"] .hero-wireframe-card .hero-card-content, body.dark-mode .hero-wireframe-card .hero-card-content {
	background: #1e293b !important;
}
[data-theme="dark"] .hero-wireframe-card .hero-card-title a, body.dark-mode .hero-wireframe-card .hero-card-title a {
	color: #f8fafc !important;
}
[data-theme="dark"] .hero-wireframe-card .hero-card-title a:hover, body.dark-mode .hero-wireframe-card .hero-card-title a:hover {
	color: #34d399 !important;
}
[data-theme="dark"] .hero-wireframe-card .author-name, body.dark-mode .hero-wireframe-card .author-name {
	color: #f1f5f9 !important;
}
[data-theme="dark"] .hero-wireframe-card .author-badge-sub, body.dark-mode .hero-wireframe-card .author-badge-sub {
	color: #94a3b8 !important;
}
[data-theme="dark"] .hero-wireframe-card .hero-card-footer, body.dark-mode .hero-wireframe-card .hero-card-footer {
	border-top-color: #334155 !important;
}

/* Responsive Overrides */
@media (max-width: 1120px) {
	.hero-longevity-inner {
		grid-template-columns: 1.15fr 0.85fr;
		gap: 2.5rem;
		padding: 5rem 2.5rem;
	}
	.hero-wireframe-card {
		width: 310px;
	}
}

@media (max-width: 992px) {
	.hero-longevity-banner {
		min-height: auto;
		background-position: 70% center;
	}
	.hero-longevity-inner {
		grid-template-columns: 1fr;
		gap: 3.5rem;
		padding: 5rem 2rem;
	}
	.hero-longevity-left {
		max-width: 100%;
	}
	.hero-longevity-right {
		align-items: flex-start;
		justify-content: flex-start;
	}
	.hero-wireframe-card {
		width: 100%;
		max-width: 360px;
	}
}

@media (max-width: 640px) {
	.hero-longevity-inner {
		padding: 4rem 1.25rem;
		gap: 2.75rem;
	}
	.hero-longevity-title {
		font-size: clamp(2rem, 8vw, 3rem);
		line-height: 1.15;
		margin-bottom: 1.25rem;
	}
	.hero-longevity-subtitle {
		font-size: 1rem;
		margin-bottom: 2rem;
	}
	.hero-longevity-action-row {
		gap: 1.5rem;
		flex-direction: column;
		align-items: flex-start;
	}
	.hero-shop-pill-btn {
		width: 100%;
		justify-content: center;
	}
	.hero-wireframe-card {
		max-width: 100%;
	}
}

/* Clinical Categories */
.categories { padding: 4rem 0; }
.section-title { font-size: 2rem; font-weight: 800; margin-bottom: 2rem; color: var(--dark-green); }
.section-header { display: flex; justify-content: space-between; align-items: end; margin-bottom: 2rem; }
.view-all { color: var(--primary); text-decoration: none; font-size: 0.9rem; font-weight: 700; }
.cat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
.cat-card { padding: 2.25rem 1.75rem 2.5rem; text-align: left; border: 1px solid var(--secondary); border-radius: 14px; position: relative; height: 100%; display: flex; flex-direction: column; align-items: flex-start; justify-content: flex-start; background: white; transition: all 0.3s ease; text-decoration: none; color: inherit; cursor: pointer; box-sizing: border-box; }
.cat-card:hover { border-color: var(--primary); box-shadow: 0 10px 15px -3px rgba(108, 185, 66, 0.2); transform: translateY(-3px); }
.cat-card:hover .cat-arrow { transform: translateX(4px); }
.cat-card.active { background: var(--primary); color: white; border-color: var(--primary); }
.cat-card.active .cat-desc { color: var(--secondary); }
.cat-card.active .cat-title { color: white; }
.cat-title { font-weight: 800; font-size: 1.25rem; margin-bottom: 0.65rem; color: var(--dark-green); line-height: 1.3; }
.cat-desc { font-size: 0.95rem; color: var(--gray-600); line-height: 1.6; margin: 0; }
.cat-arrow { position: absolute; bottom: 1rem; right: 1.25rem; font-size: 1.2rem; color: var(--primary); transition: transform 0.2s ease; }
.cat-card.active .cat-arrow { color: white; }

/* Dark mode overrides for cat-card */
[data-theme="dark"] .cat-card, body.dark-mode .cat-card { background-color: #1e1e1e !important; border-color: #333333 !important; color: #e0e0e0 !important; }
[data-theme="dark"] .cat-card .cat-title, body.dark-mode .cat-card .cat-title { color: #ffffff !important; }
[data-theme="dark"] .cat-card .cat-desc, body.dark-mode .cat-card .cat-desc { color: #9ca3af !important; }
[data-theme="dark"] .cat-card.active, body.dark-mode .cat-card.active { background: var(--dark-green) !important; border-color: var(--primary) !important; }
[data-theme="dark"] .cat-card.active .cat-title, body.dark-mode .cat-card.active .cat-title { color: #ffffff !important; }
[data-theme="dark"] .cat-card.active .cat-desc, body.dark-mode .cat-card.active .cat-desc { color: var(--secondary) !important; }
[data-theme="dark"] .cat-card.active .cat-arrow, body.dark-mode .cat-card.active .cat-arrow { color: #ffffff !important; }

/* Articles Grid */
.articles { padding: 4rem 0; }
.art-grid { display: grid; grid-template-columns: 1fr 2fr 1fr; gap: 2rem; }

.art-col-left { display: flex; flex-direction: column; gap: 2rem; }
.art-small { display: flex; flex-direction: column; gap: 1rem; }
.art-small img { height: 160px; width: 100%; border-radius: 8px; }
.art-meta { font-size: 0.75rem; color: var(--primary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
.art-title { font-weight: 800; font-size: 1.1rem; line-height: 1.3; color: var(--dark-green); }
.art-date { font-size: 0.75rem; color: var(--gray-600); }

.art-main img { height: 350px; width: 100%; margin-bottom: 1.5rem; border-radius: 12px; }
.art-main .art-title { font-size: 1.8rem; margin: 0.5rem 0 1rem; text-align: center; color: var(--dark-green); }
.art-main .art-meta { text-align: center; }
.art-main .art-desc { text-align: center; font-size: 0.95rem; color: var(--gray-600); line-height: 1.6; padding: 0 2rem; }

.art-list { display: flex; flex-direction: column; gap: 1.5rem; }
.list-item { display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; align-items: start; }
.list-item img { height: 70px; width: 100%; border-radius: 6px; }
.list-item .art-title { font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--dark-green); line-height: 1.3; }

/* Ingredients */
.ingredients { padding: 4rem 0; background-color: var(--gray-100); border-radius: 24px; margin-bottom: 4rem; }
.ing-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 2.5rem 1rem; text-align: center; margin-top: 2rem;}
.ing-item { display: flex; flex-direction: column; align-items: center; gap: 1rem; transition: transform 0.2s; cursor: pointer; }
.ing-item:hover { transform: scale(1.05); }
.ing-item img { width: 100px; height: 100px; border: 4px solid white; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
.ing-name { font-size: 0.9rem; font-weight: 700; color: var(--dark-green); }

/* Footer Override for Static Layout */
.site-footer-new { background: var(--dark-green); color: white; padding: 4rem 0 2rem; font-family: 'Roboto', sans-serif; }
.footer-top { border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 3rem; margin-bottom: 3rem; display: flex; justify-content: space-between; align-items: center; }
.footer-top h2 { font-size: 2.5rem; font-weight: 800; margin: 0 0 1rem; color: white; }
.footer-top-btn { background: var(--primary); color: white; padding: 0.75rem 2rem; border-radius: 4px; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: background 0.2s; }
.footer-top-btn:hover { background: #005fa8; }
.footer-top-desc { max-width: 400px; font-size: 0.9rem; line-height: 1.6; color: var(--secondary); text-align: right; }
.footer-cols { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 2rem; margin-bottom: 3rem; }
.footer-col h4 { font-size: 1rem; font-weight: 800; margin-bottom: 1.5rem; color: white; }
.footer-col p, .footer-col a { color: var(--secondary); font-size: 0.85rem; text-decoration: none; display: block; margin-bottom: 0.75rem; }
.footer-col a:hover { color: white; text-decoration: underline; }
.footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.5rem; display: flex; justify-content: space-between; font-size: 0.75rem; color: #94a3b8; }
.footer-bottom a { color: #94a3b8; text-decoration: none; margin-left: 1rem; }
.footer-bottom a:hover { color: white; }

/* Responsive Styles */
@media (max-width: 1200px) {
	.hero-layout { grid-template-columns: 1fr 1fr !important; gap: 3rem !important; }
}
@media (max-width: 992px) {
	.hero-layout { grid-template-columns: 1fr !important; gap: 3rem !important; }
	.hero-features { flex-direction: row !important; flex-wrap: wrap !important; justify-content: center !important; gap: 1rem !important; }
	.hero-box { text-align: center !important; padding: 2rem !important; margin: 0 auto !important; max-width: 600px !important; }
	.cat-grid { grid-template-columns: 1fr 1fr !important; }
	.art-grid { grid-template-columns: 1fr !important; gap: 2.5rem !important; }
	.art-main { order: -1; }
	.art-main img { height: auto !important; max-height: 380px !important; }
	.footer-cols { grid-template-columns: 1fr 1fr !important; }
	.footer-top { flex-direction: column !important; text-align: center !important; gap: 1.5rem !important; }
	.footer-top-desc { text-align: center !important; margin: 0 auto !important; }
	.ing-grid { grid-template-columns: repeat(3, 1fr) !important; gap: 2rem 1rem !important; }
}
@media (max-width: 768px) {
	.cat-grid { grid-template-columns: 1fr !important; }
	.footer-cols { grid-template-columns: 1fr !important; text-align: center !important; }
	.hero-title { font-size: clamp(1.8rem, 6vw, 2.2rem) !important; }
	.hero-center { text-align: center !important; }
	.art-meta { text-align: center !important; }
	.art-main .art-desc { padding: 0 0.5rem !important; }
	.footer-bottom { flex-direction: column !important; text-align: center !important; gap: 1rem !important; }
	.footer-bottom div { display: flex; flex-wrap: wrap; justify-content: center; gap: 0.5rem; }
	.footer-bottom a { margin-left: 0 !important; margin: 0 0.5rem !important; }
	.section-title { font-size: 1.6rem !important; }
}
@media (max-width: 540px) {
	.ing-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 1.5rem 0.75rem !important; }
	.ing-item img { width: 80px !important; height: 80px !important; }
	.ingredients { padding: 2.5rem 1rem !important; border-radius: 16px !important; }
	.list-item { grid-template-columns: 1fr auto !important; }
	.categories { padding: 2.5rem 0 !important; }
	.articles { padding: 2.5rem 0 !important; }
}
@media (max-width: 480px) {
	.hero-title { font-size: 1.5rem !important; }
	.hero-box { margin: 0 !important; padding: 1.5rem 1rem !important; }
	.section-header { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
}

/* Top Supplements Section */
.top-supplements-section {
	padding: 3rem 1rem 5rem;
}
.top-supp-header {
	display: flex;
	justify-content: space-between;
	align-items: flex-end;
	margin-bottom: 2rem;
	gap: 1rem;
}
.top-supp-header-left {
	display: flex;
	flex-direction: column;
	gap: 0.35rem;
}
.top-supp-title-main {
	font-size: 2.25rem;
	font-weight: 800;
	color: #000000;
	letter-spacing: -0.02em;
	font-family: 'Inter', sans-serif;
	margin: 0;
	line-height: 1.15;
}
.top-supp-subtitle {
	color: #64748b;
	font-size: 0.95rem;
	font-family: 'Inter', sans-serif;
	margin: 0;
}
.top-supp-view-all {
	color: #0f172a;
	font-size: 0.95rem;
	font-weight: 500;
	text-decoration: none;
	font-family: 'Inter', sans-serif;
	white-space: nowrap;
	transition: opacity 0.2s;
}
.top-supp-view-all:hover {
	opacity: 0.7;
	text-decoration: underline;
}
.top-supp-grid {
	display: grid;
	grid-template-columns: repeat(5, 1fr);
	gap: 1.25rem;
}
.top-supp-card {
	display: flex;
	flex-direction: column;
	background: transparent;
	font-family: 'Inter', sans-serif;
}
.top-supp-img-wrap {
	position: relative;
	width: 100%;
	aspect-ratio: 1 / 1;
	background-color: #ededed;
	border-radius: 14px;
	overflow: hidden;
	display: flex;
	align-items: center;
	justify-content: center;
	margin-bottom: 0.85rem;
}
.top-supp-img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	display: block;
}
.top-supp-badge {
	position: absolute;
	top: 12px;
	right: 12px;
	z-index: 2;
	background: #ffffff;
	color: #000000;
	font-size: 0.72rem;
	font-weight: 500;
	padding: 3px 10px;
	border-radius: 9999px;
	border: 1px solid #111111;
	line-height: 1.3;
	white-space: nowrap;
}
.top-supp-card-title {
	font-size: 1.2rem;
	font-weight: 700;
	margin: 0 0 0.5rem 0;
	line-height: 1.25;
}
.top-supp-card-title a {
	color: #000000;
	text-decoration: none;
	display: block;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
}
.top-supp-card-title a:hover {
	text-decoration: underline;
}
.top-supp-meta-row {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 0.85rem;
	font-size: 0.85rem;
}
.top-supp-rating {
	display: inline-flex;
	align-items: center;
	gap: 4px;
	color: #374151;
	font-weight: 600;
	font-size: 0.85rem;
}
.top-supp-star {
	color: #f59e0b;
	font-size: 0.95rem;
}
.top-supp-reviews {
	color: #9ca3af;
	font-weight: 400;
	font-size: 0.82rem;
}
.top-supp-price {
	font-weight: 700;
	font-size: 1.05rem;
	color: #000000;
	white-space: nowrap;
}
.top-supp-actions {
	display: flex;
	gap: 0.5rem;
	align-items: center;
	margin-top: auto;
}
.top-supp-btn-read {
	flex: 1;
	text-align: center;
	padding: 0.55rem 0.5rem;
	border-radius: 9999px;
	border: 1.5px solid #000000;
	background: transparent;
	color: #000000;
	font-size: 0.82rem;
	font-weight: 600;
	text-decoration: none;
	transition: all 0.2s;
	white-space: nowrap;
}
.top-supp-btn-read:hover {
	background: #000000;
	color: #ffffff;
}
.top-supp-btn-buy {
	flex: 1;
	text-align: center;
	padding: 0.55rem 0.5rem;
	border-radius: 9999px;
	border: 1.5px solid #000000;
	background: #000000;
	color: #ffffff;
	font-size: 0.82rem;
	font-weight: 600;
	text-decoration: none;
	transition: all 0.2s;
	white-space: nowrap;
}
.top-supp-btn-buy:hover {
	background: #27272a;
	border-color: #27272a;
}

/* Dark Mode for Top Supplements */
[data-theme="dark"] .top-supp-title-main, body.dark-mode .top-supp-title-main { color: #ffffff !important; }
[data-theme="dark"] .top-supp-subtitle, body.dark-mode .top-supp-subtitle { color: #9ca3af !important; }
[data-theme="dark"] .top-supp-view-all, body.dark-mode .top-supp-view-all { color: #f3f4f6 !important; }
[data-theme="dark"] .top-supp-img-wrap, body.dark-mode .top-supp-img-wrap { background-color: #27272a !important; }
[data-theme="dark"] .top-supp-badge, body.dark-mode .top-supp-badge { background: #18181b !important; color: #f4f4f5 !important; border-color: #52525b !important; }
[data-theme="dark"] .top-supp-card-title a, body.dark-mode .top-supp-card-title a { color: #ffffff !important; }
[data-theme="dark"] .top-supp-rating, body.dark-mode .top-supp-rating { color: #e4e4e7 !important; }
[data-theme="dark"] .top-supp-reviews, body.dark-mode .top-supp-reviews { color: #9ca3af !important; }
[data-theme="dark"] .top-supp-price, body.dark-mode .top-supp-price { color: #ffffff !important; }
[data-theme="dark"] .top-supp-btn-read, body.dark-mode .top-supp-btn-read { border-color: #f4f4f5 !important; color: #ffffff !important; }
[data-theme="dark"] .top-supp-btn-read:hover, body.dark-mode .top-supp-btn-read:hover { background: #ffffff !important; color: #000000 !important; }
[data-theme="dark"] .top-supp-btn-buy, body.dark-mode .top-supp-btn-buy { background: #ffffff !important; color: #000000 !important; border-color: #ffffff !important; }
[data-theme="dark"] .top-supp-btn-buy:hover, body.dark-mode .top-supp-btn-buy:hover { background: #e4e4e7 !important; border-color: #e4e4e7 !important; }

@media (max-width: 1200px) {
	.top-supp-grid { grid-template-columns: repeat(4, 1fr); }
}
@media (max-width: 992px) {
	.top-supp-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 768px) {
	.top-supp-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
	.top-supp-title-main { font-size: 1.85rem; }
}
@media (max-width: 480px) {
	.top-supp-grid { grid-template-columns: 1fr; }
	.top-supp-header { flex-direction: column; align-items: flex-start; }
}
</style>

<main id="primary" class="site-main">

	<!-- Hero Section -->
	<?php
	// Query the spotlight/featured review post or latest published post
	$hero_query = new WP_Query( array(
		'posts_per_page'      => 1,
		'post_status'         => 'publish',
		'post_type'           => 'post',
		'ignore_sticky_posts' => 1,
	) );

	$hero_title_left    = "Slow<br>Down<br>Ageing";
	$hero_post_title    = 'Lipo-Flow Review: A Positive, Evidence-Aware Look at This Liquid Metabolism Formula';
	$hero_post_date     = 'October 1, 2026';
	$hero_reading_time  = '20';
	$hero_author_name   = 'Dr. Emma Myers';
	$hero_post_link     = '#';
	$hero_card_img      = '';
	$hero_review_count  = 5256;

	if ( $hero_query->have_posts() ) {
		while ( $hero_query->have_posts() ) {
			$hero_query->the_post();
			$hp_id = get_the_ID();
			$hero_post_title = get_the_title();
			$hero_post_link  = get_permalink();
			$hero_post_date  = get_the_date( 'F j, Y' );

			$post_content = get_post_field( 'post_content', $hp_id );
			$word_count   = str_word_count( strip_tags( strip_shortcodes( $post_content ) ) );
			$hero_reading_time = max( 1, ceil( $word_count / 200 ) );

			// Author Name
			$author_val = '';
			if ( function_exists( 'get_field' ) ) {
				$author_val = get_field( 'author_name', $hp_id ) ?: get_field( 'author', $hp_id );
			}
			if ( empty( $author_val ) ) {
				$author_val = get_post_meta( $hp_id, 'author_name', true );
			}
			if ( empty( $author_val ) ) {
				$author_val = get_the_author();
			}
			if ( empty( $author_val ) || in_array( strtolower( $author_val ), array( 'admin', 'bishow' ), true ) ) {
				$author_val = 'Dr. Emma Myers';
			}
			$hero_author_name = $author_val;

			// Post / Product Image
			if ( function_exists( 'get_field' ) ) {
				$acf_img = get_field( 'product_image', $hp_id );
				if ( is_array( $acf_img ) && ! empty( $acf_img['url'] ) ) {
					$hero_card_img = $acf_img['url'];
				} elseif ( is_numeric( $acf_img ) && (int) $acf_img > 0 ) {
					$hero_card_img = wp_get_attachment_image_url( (int) $acf_img, 'large' );
				} elseif ( is_string( $acf_img ) && ! empty( $acf_img ) ) {
					$hero_card_img = $acf_img;
				}
			}
			if ( empty( $hero_card_img ) ) {
				$thumb_id = get_post_thumbnail_id( $hp_id );
				if ( $thumb_id ) {
					$hero_card_img = wp_get_attachment_image_url( $thumb_id, 'large' );
				}
			}
			if ( empty( $hero_card_img ) ) {
				$side_img = get_post_meta( $hp_id, '_sidebar_product_image', true );
				if ( ! empty( $side_img ) ) {
					$hero_card_img = $side_img;
				}
			}

			// Reviews Count
			$comments_num = get_comments_number( $hp_id );
			if ( $comments_num > 0 ) {
				$hero_review_count = $comments_num;
			}

			// Optional Hero Title from ACF
			if ( function_exists( 'get_field' ) ) {
				$acf_hero_title = get_field( 'hero_title', $hp_id );
				if ( ! empty( $acf_hero_title ) ) {
					$hero_title_left = nl2br( esc_html( $acf_hero_title ) );
				}
			}
		}
		wp_reset_postdata();
	}
	?>
	<div class="hero-longevity-wrap">
		<div class="hero-longevity-banner">
			<div class="hero-longevity-overlay"></div>
			<div class="hero-longevity-inner">
				<!-- Left Column: Title, Subtitle, Shop Button & Reviews -->
				<div class="hero-longevity-left">
					<h1 class="hero-longevity-title"><?php echo wp_kses_post( $hero_title_left ); ?></h1>

					<p class="hero-longevity-subtitle">
						<?php echo esc_html( get_theme_mod( 'hero_description', 'Independent, clinical evaluations of longevity supplements, NAD+ protocols, and evidence-backed metabolic formulas.' ) ); ?>
					</p>

					<div class="hero-longevity-action-row">
						<a href="<?php echo esc_url( home_url( '/category/supplements/' ) ); ?>" class="hero-shop-pill-btn">
							<span>Shop All Protocols</span>
							<svg class="hero-arrow-diagonal" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
								<line x1="7" y1="17" x2="17" y2="7"></line>
								<polyline points="7 7 17 7 17 17"></polyline>
							</svg>
						</a>
						<div class="hero-reviews-badge">
							<div class="hero-stars-row">
								<span class="stars-icons">★★★★★</span>
								<span class="stars-score">4.9 / 5</span>
							</div>
							<span class="hero-reviews-text"><?php echo number_format( (int) $hero_review_count ); ?> verified evaluations</span>
						</div>
					</div>
				</div>

				<!-- Right Column: Crisp Minimalist White Card with Emerald Accent -->
				<div class="hero-longevity-right">
					<div class="hero-wireframe-card">
						<div class="hero-card-img-wrap">
							<span class="hero-card-pill-tag">
								<svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
								Top Rated
							</span>
							<div class="hero-card-score-badge">
								<span class="score-val">9.8</span>
								<span class="score-total">/10</span>
							</div>
							<div class="hero-card-img">
								<?php if ( ! empty( $hero_card_img ) ) : ?>
									<img src="<?php echo esc_url( $hero_card_img ); ?>" alt="<?php echo esc_attr( $hero_post_title ); ?>">
								<?php else : ?>
									<div class="hero-card-img-placeholder"></div>
								<?php endif; ?>
							</div>
						</div>
						<div class="hero-card-content">
							<div class="hero-card-meta">
								<span class="meta-item"><span class="meta-bullet"></span><?php echo esc_html( $hero_post_date ); ?></span>
								<span class="meta-item"><span class="meta-bullet"></span><?php echo esc_html( $hero_reading_time ); ?> min read</span>
							</div>
							<h3 class="hero-card-title">
								<a href="<?php echo esc_url( $hero_post_link ); ?>"><?php echo esc_html( $hero_post_title ); ?></a>
							</h3>
							<div class="hero-card-footer">
								<div class="author-block">
									<span class="author-avatar-badge">MD</span>
									<div class="author-info-col">
										<span class="author-name"><?php echo esc_html( $hero_author_name ); ?></span>
										<span class="author-badge-sub">Medical Reviewer</span>
									</div>
								</div>
								<a href="<?php echo esc_url( $hero_post_link ); ?>" class="read-article-link">
									<span>Read</span>
									<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Categories -->
	<?php
	$categories_list = get_categories( array(
		'orderby'    => 'name',
		'order'      => 'ASC',
		'hide_empty' => false,
	) );

	// Filter out 'uncategorized'
	$clinical_categories = array();
	if ( ! empty( $categories_list ) && ! is_wp_error( $categories_list ) ) {
		foreach ( $categories_list as $cat_item ) {
			if ( strtolower( $cat_item->slug ) !== 'uncategorized' ) {
				$clinical_categories[] = $cat_item;
			}
		}
	}
	?>
	<section class="container categories" id="categories">
		<div class="section-header">
			<h2 class="section-title">Explore Our<br>Clinical Categories</h2>
			<a href="<?php echo esc_url( home_url( '/#categories' ) ); ?>" class="view-all">View All</a>
		</div>
		<div class="cat-grid">
			<?php
			if ( ! empty( $clinical_categories ) ) :
				foreach ( $clinical_categories as $index => $cat ) :
					$cat_id    = $cat->term_id;
					$cat_name  = $cat->name;
					$cat_desc  = ! empty( $cat->description ) ? $cat->description : 'Explore comprehensive research, evidence-based reviews, and clinical insights for ' . $cat_name . '.';
					$cat_link  = get_category_link( $cat_id );
			?>
				<a href="<?php echo esc_url( $cat_link ); ?>" class="cat-card <?php echo ( 0 === $index ) ? 'active' : ''; ?>">
					<h3 class="cat-title"><?php echo esc_html( $cat_name ); ?></h3>
					<p class="cat-desc"><?php echo esc_html( $cat_desc ); ?></p>
					<span class="cat-arrow">&rarr;</span>
				</a>
			<?php
				endforeach;
			else :
				// Fallback if no custom categories found
				$fallback_cats = array(
					array( 'title' => "Male's Enhancement", 'desc' => "Explore products focused on supporting men's vitality, energy, confidence, and overall sexual wellness." ),
					array( 'title' => "Weight Loss", 'desc' => "Explore supplement reviews and research on ingredients marketed for weight management." ),
					array( 'title' => "Brain Enhancement", 'desc' => "Discover articles examining supplements marketed for memory, focus, concentration." ),
					array( 'title' => "Joint Pain Relief", 'desc' => "Read reviews and research on supplements marketed for joint comfort, mobility." ),
					array( 'title' => "Hair Growth", 'desc' => "Learn about natural extracts and vitamins supporting healthy hair follicles and growth." ),
					array( 'title' => "Anti-Aging", 'desc' => "Explore science-backed compounds focused on longevity, cellular health, and skin elasticity." ),
					array( 'title' => "Prostate Health", 'desc' => "Detailed analysis of botanical extracts supporting men's prostate and urinary function." ),
					array( 'title' => "Blood Pressure", 'desc' => "Examine dietary additions and herbal support targeting cardiovascular wellness." ),
				);
				foreach ( $fallback_cats as $idx => $def ) :
			?>
				<div class="cat-card <?php echo ( 0 === $idx ) ? 'active' : ''; ?>">
					<h3 class="cat-title"><?php echo esc_html( $def['title'] ); ?></h3>
					<p class="cat-desc"><?php echo esc_html( $def['desc'] ); ?></p>
					<span class="cat-arrow">&rarr;</span>
				</div>
			<?php
				endforeach;
			endif;
			?>
		</div>
	</section>

	<!-- Articles -->
	<section class="container articles">
		<div class="art-grid">
			<!-- Left Column -->
			<div class="art-col-left">
				<div class="art-small">
					<img src="https://picsum.photos/seed/a1/400/300" class="img-cover">
					<div>
						<div class="art-meta">Men's Health</div>
						<h3 class="art-title">Prostate Defense: Clinical Trials And Evidence...</h3>
						<div class="art-date">October 2, 2026 &middot; By Dr. Smith</div>
					</div>
				</div>
				<div class="art-small">
					<img src="https://picsum.photos/seed/a2/400/300" class="img-cover">
					<div>
						<div class="art-meta">Longevity</div>
						<h3 class="art-title">Resveratrol Deep Dive: What The Science Says...</h3>
						<div class="art-date">October 1, 2026 &middot; By Dr. Myers</div>
					</div>
				</div>
			</div>

			<!-- Center Column -->
			<div class="art-main">
				<img src="https://picsum.photos/seed/a3/800/500" class="img-cover">
				<div class="art-meta" style="margin-bottom: 0.5rem;">Editor's Pick &middot; October 3, 2026 &middot; By Dr. Emily Carter</div>
				<h2 class="art-title">The Complete Guide To Nootropics & Cognitive Support</h2>
				<p class="art-desc">Our mission is to eliminate supplement industry deception. Every product review undergoes multi-step verification including published clinical trial scrutiny, dosage validation, refund policy audits, and medical contributor review.</p>
			</div>

			<!-- Right Column -->
			<div class="art-list">
				<div class="art-meta" style="color: var(--dark-green); font-weight: 800; font-size: 1rem;">Latest Updates</div>
				<?php for($i=0; $i<5; $i++): ?>
				<div class="list-item">
					<div>
						<a href="<?php echo home_url('/?p=1'); ?>" style="text-decoration: none;">
							<h3 class="art-title">Analyzing Joint Matrix: Turmeric Extract Bioavailability...</h3>
						</a>
						<div class="art-date">Sept 2<?php echo 8-$i; ?>, 2026 &middot; By Jane Doe</div>
					</div>
					<img src="https://picsum.photos/seed/l<?php echo $i; ?>/150/150" class="img-cover">
				</div>
				<?php endfor; ?>
			</div>
		</div>
	</section>

	<!-- Top Supplements Section -->
	<?php
	$supp_source    = get_theme_mod( 'top_supplements_source', 'latest' );
	$supp_title     = get_theme_mod( 'top_supplements_title', 'Top Supplements' );
	$supp_subtitle  = get_theme_mod( 'top_supplements_subtitle', 'Science-backed components with proven wellness benefits' );
	$supp_view_all  = get_theme_mod( 'top_supplements_view_all_url', '#' );
	$supp_count     = absint( get_theme_mod( 'top_supplements_count', 5 ) );
	if ( ! $supp_count ) {
		$supp_count = 5;
	}

	$supp_args = array(
		'post_type'           => 'post',
		'posts_per_page'      => $supp_count,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
	);

	if ( 'top_clicks' === $supp_source ) {
		$supp_args['meta_key'] = '_post_clicks';
		$supp_args['orderby']  = 'meta_value_num';
		$supp_args['order']    = 'DESC';
	} else {
		$supp_args['orderby']  = 'date';
		$supp_args['order']    = 'DESC';
	}

	$supp_query = new WP_Query( $supp_args );

	// Fallback if top_clicks has no posts with _post_clicks yet
	if ( 'top_clicks' === $supp_source && ! $supp_query->have_posts() ) {
		$fallback_args = array(
			'post_type'           => 'post',
			'posts_per_page'      => $supp_count,
			'post_status'         => 'publish',
			'orderby'             => 'comment_count',
			'order'               => 'DESC',
			'ignore_sticky_posts' => 1,
		);
		$supp_query = new WP_Query( $fallback_args );
	}
	?>
	<section class="container top-supplements-section">
		<div class="top-supp-header">
			<div class="top-supp-header-left">
				<h2 class="top-supp-title-main"><?php echo esc_html( $supp_title ); ?></h2>
				<?php if ( ! empty( $supp_subtitle ) ) : ?>
					<p class="top-supp-subtitle"><?php echo esc_html( $supp_subtitle ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $supp_view_all ) ) : ?>
				<a href="<?php echo esc_url( $supp_view_all ); ?>" class="top-supp-view-all">View All</a>
			<?php endif; ?>
		</div>

		<div class="top-supp-grid">
			<?php
			$rendered_count = 0;
			if ( $supp_query->have_posts() ) :
				while ( $supp_query->have_posts() ) :
					$supp_query->the_post();
					$rendered_count++;
					$post_id    = get_the_ID();
					$prod_name  = get_post_meta( $post_id, '_sidebar_product_name', true );
					$card_title = ! empty( $prod_name ) ? $prod_name : get_the_title();

					$card_img = get_post_meta( $post_id, '_sidebar_product_image', true );
					if ( empty( $card_img ) && has_post_thumbnail( $post_id ) ) {
						$card_img = get_the_post_thumbnail_url( $post_id, 'medium_large' );
					}

					$cats = get_the_category( $post_id );
					$badge_text = ! empty( $cats ) ? $cats[0]->name : 'Loss Weight';

					$rating_raw = get_post_meta( $post_id, '_sidebar_product_rating', true );
					$rating_val = ! empty( $rating_raw ) ? number_format( (float) $rating_raw, 1 ) : '4.2';

					$disc_price_raw = get_post_meta( $post_id, '_sidebar_product_disc_price', true );
					$price_text = ! empty( $disc_price_raw ) ? '$ ' . number_format( (float) $disc_price_raw, 2 ) : '$ 34.99';

					$buy_url = get_post_meta( $post_id, '_sidebar_product_buy_url', true );
					if ( empty( $buy_url ) ) {
						$buy_url = get_permalink( $post_id );
					}
			?>
				<div class="top-supp-card">
					<div class="top-supp-img-wrap">
						<span class="top-supp-badge"><?php echo esc_html( $badge_text ); ?></span>
						<?php if ( ! empty( $card_img ) ) : ?>
							<img src="<?php echo esc_url( $card_img ); ?>" alt="<?php echo esc_attr( $card_title ); ?>" class="top-supp-img">
						<?php endif; ?>
					</div>
					<h3 class="top-supp-card-title">
						<a href="<?php the_permalink(); ?>"><?php echo esc_html( $card_title ); ?></a>
					</h3>
					<div class="top-supp-meta-row">
						<div class="top-supp-rating">
							<span class="top-supp-star">&#9733;</span> <?php echo esc_html( $rating_val ); ?> <span class="top-supp-reviews">(2.4k Reviews)</span>
						</div>
						<div class="top-supp-price"><?php echo esc_html( $price_text ); ?></div>
					</div>
					<div class="top-supp-actions">
						<a href="<?php the_permalink(); ?>" class="top-supp-btn-read">Read Article</a>
						<a href="<?php echo esc_url( $buy_url ); ?>" class="top-supp-btn-buy">Buy Now</a>
					</div>
				</div>
			<?php
				endwhile;
				wp_reset_postdata();
			endif;

			// If fewer posts than requested count, fill remaining with demo cards matching the wireframe
			for ( $k = $rendered_count; $k < $supp_count; $k++ ) :
			?>
				<div class="top-supp-card">
					<div class="top-supp-img-wrap">
						<span class="top-supp-badge">Loss Weight</span>
					</div>
					<h3 class="top-supp-card-title">
						<a href="<?php echo esc_url( home_url( '/?p=1' ) ); ?>">Gluco Reset</a>
					</h3>
					<div class="top-supp-meta-row">
						<div class="top-supp-rating">
							<span class="top-supp-star">&#9733;</span> 4.2 <span class="top-supp-reviews">(2.4k Reviews)</span>
						</div>
						<div class="top-supp-price">$ 34.99</div>
					</div>
					<div class="top-supp-actions">
						<a href="<?php echo esc_url( home_url( '/?p=1' ) ); ?>" class="top-supp-btn-read">Read Article</a>
						<a href="<?php echo esc_url( home_url( '/?p=1' ) ); ?>" class="top-supp-btn-buy">Buy Now</a>
					</div>
				</div>
			<?php endfor; ?>
		</div>
	</section>

</main>

<?php get_footer(); ?>
