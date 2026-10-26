<?php
if (!defined('ABSPATH'))
    exit;
global $amr_target_url;
global $amr_redirect_error;
$target = !empty($amr_target_url) ? esc_url_raw($amr_target_url) : '';

// Get settings
require_once(dirname(__FILE__) . '/meta-redirect.php');
$redirect_delay = amr_get_redirect_delay();
$heading = amr_get_redirect_heading();
$subtitle = amr_get_redirect_subtitle();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <title>Redirecting...</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .redirect-container {
            width: 100%;
            height: 100vh;
            background: #f8f9fa;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        @supports (height: 100dvh) {
            .redirect-container {
                height: 100dvh;
            }
        }

        .skeleton-header {
            height: 70px;
            background: #fff;
            padding: 0 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
        }

        .skeleton-logo {
            width: 120px;
            height: 30px;
            background: #eee;
            border-radius: 4px;
        }

        .skeleton-avatar {
            width: 30px;
            height: 30px;
            background: #eee;
            border-radius: 50%;
        }

        /* Mobile-only skeleton (matches the mobile pattern screenshot) */
        .mobile-skeleton {
            display: none;
            width: 100%;
            max-width: 560px;
            margin: 0 auto;
            padding: 18px 16px 22px;
            flex: 0 0 auto;
        }

        .ms-hero {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 14px;
            padding: 18px 0 10px;
        }

        .ms-hero-left {
            width: 46%;
            height: clamp(300px, 44vh, 420px);
            border-radius: 18px;
        }

        .ms-hero-right {
            width: 34%;
            height: clamp(300px, 44vh, 420px);
            border-radius: 18px;
            margin-top: 36px;
        }

        .ms-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            padding: 10px 0 14px;
        }

        .ms-stat {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .ms-stat-brand {
            height: 14px;
            width: 62px;
            border-radius: 6px;
        }

        .ms-stat-stars {
            height: 12px;
            width: 74px;
            border-radius: 6px;
        }

        .ms-stat-number {
            height: 18px;
            width: 78px;
            border-radius: 8px;
        }

        .ms-stat-label {
            height: 12px;
            width: 70px;
            border-radius: 6px;
        }

        .ms-title {
            height: 34px;
            width: 70%;
            border-radius: 10px;
            margin: 14px auto 10px;
        }

        .ms-desc {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .ms-line {
            height: 14px;
            border-radius: 8px;
        }

        .ms-line.w1 { width: 92%; }
        .ms-line.w2 { width: 86%; }
        .ms-line.w3 { width: 70%; }

        .skeleton-main {
            display: flex;
            padding: 40px 50px;
            gap: 40px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            flex: 1;
        }

        .skeleton-left-thumbs {
            width: 80px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .skeleton-thumb {
            width: 80px;
            height: 80px;
            background: #eee;
            border-radius: 8px;
        }

        .skeleton-middle-image {
            flex: 1.5;
            height: 500px;
            background: #eee;
            border-radius: 12px;
            position: relative;
        }

        .skeleton-right-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .skeleton-title {
            height: 40px;
            width: 90%;
            background: #eee;
            border-radius: 6px;
        }

        .skeleton-rating {
            height: 20px;
            width: 40%;
            background: #eee;
            border-radius: 4px;
        }

        .skeleton-text-block {
            height: 100px;
            width: 100%;
            background: #eee;
            border-radius: 8px;
        }

        .skeleton-bullets {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .skeleton-bullet {
            height: 15px;
            width: 100%;
            background: #eee;
            border-radius: 4px;
        }

        .skeleton-footer {
            height: 80px;
            background: #fff;
            padding: 20px 50px;
            display: flex;
            align-items: center;
            gap: 20px;
            border-top: 1px solid #eee;
        }

        .skeleton-pill {
            height: 40px;
            width: 300px;
            background: #eee;
            border-radius: 20px;
        }

        /* Shimmer effect */
        .shimmer {
            position: relative;
            overflow: hidden;
            background-color: #f0f0f0;
        }

        .shimmer::after {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            transform: translateX(-100%);
            background-image: linear-gradient(
                90deg,
                rgba(255, 255, 255, 0) 0,
                rgba(255, 255, 255, 0.2) 20%,
                rgba(255, 255, 255, 0.5) 60%,
                rgba(255, 255, 255, 0)
            );
            animation: shimmer 1.5s infinite;
            content: '';
        }

        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        .error-container {
            background: #fff;
            border: 2px solid #ff6b6b;
            border-radius: 16px;
            padding: 40px;
            max-width: 400px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        /* Responsive skeleton layout */
        @media (max-width: 900px) {
            .skeleton-header {
                padding: 0 24px;
            }

            .skeleton-main {
                padding: 24px;
                gap: 24px;
            }

            .skeleton-middle-image {
                height: 380px;
            }

            .skeleton-footer {
                padding: 16px 24px;
            }
        }

        @media (max-width: 820px) {
            .redirect-container {
                overflow-y: auto;
                overflow-x: hidden;
                -webkit-overflow-scrolling: touch;
            }

            body {
                padding: 12px;
            }

            .skeleton-header {
                height: 60px;
                padding: 0 16px;
            }

            .skeleton-logo {
                width: 96px;
                height: 24px;
            }

            .skeleton-avatar {
                width: 26px;
                height: 26px;
                border-radius: 6px;
            }

            .skeleton-main,
            .skeleton-footer {
                display: none !important;
            }

            .mobile-skeleton {
                display: block;
            }

            .ms-stats {
                gap: 10px;
            }

            .ms-stat-brand { width: 56px; }
            .ms-stat-stars { width: 66px; }
            .ms-stat-number { width: 70px; }
            .ms-stat-label { width: 62px; }
        }

        @media (max-width: 420px) {
            .mobile-skeleton {
                padding: 16px 14px 20px;
            }

            .ms-hero {
                gap: 12px;
            }

            .ms-hero-left,
            .ms-hero-right {
                height: clamp(260px, 44vh, 380px);
            }

            .ms-hero-right {
                margin-top: 28px;
            }
        }
    </style>
</head>

<body>
    <?php
    if (!$target || !empty($amr_redirect_error)) {
        ?>
        <div class="error-container">
            <h1>Invalid Redirect</h1>
			<p><?php echo esc_html(!empty($amr_redirect_error) ? $amr_redirect_error : 'The destination URL was not specified.'); ?></p>
        </div>
        <?php
    } else {
        ?>
        <div class="redirect-container">
            <!-- Header -->
            <div class="skeleton-header">
                <div class="skeleton-logo shimmer"></div>
                <div class="skeleton-avatar shimmer"></div>
            </div>

            <!-- Mobile Pattern Skeleton (shown only on small screens) -->
            <div class="mobile-skeleton">
                <div class="ms-hero">
                    <div class="ms-hero-left shimmer"></div>
                    <div class="ms-hero-right shimmer"></div>
                </div>

                <div class="ms-stats">
                    <div class="ms-stat">
                        <div class="ms-stat-brand shimmer"></div>
                        <div class="ms-stat-stars shimmer"></div>
                        <div class="ms-stat-number shimmer"></div>
                        <div class="ms-stat-label shimmer"></div>
                    </div>
                    <div class="ms-stat">
                        <div class="ms-stat-brand shimmer"></div>
                        <div class="ms-stat-stars shimmer"></div>
                        <div class="ms-stat-number shimmer"></div>
                        <div class="ms-stat-label shimmer"></div>
                    </div>
                    <div class="ms-stat">
                        <div class="ms-stat-brand shimmer"></div>
                        <div class="ms-stat-stars shimmer"></div>
                        <div class="ms-stat-number shimmer"></div>
                        <div class="ms-stat-label shimmer"></div>
                    </div>
                </div>

                <div class="ms-title shimmer"></div>

                <div class="ms-desc">
                    <div class="ms-line w1 shimmer"></div>
                    <div class="ms-line w2 shimmer"></div>
                    <div class="ms-line w3 shimmer"></div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="skeleton-main">
                <!-- Left Thumbnails -->
                <div class="skeleton-left-thumbs">
                    <div class="skeleton-thumb shimmer"></div>
                    <div class="skeleton-thumb shimmer"></div>
                    <div class="skeleton-thumb shimmer"></div>
                    <div class="skeleton-thumb shimmer"></div>
                </div>

                <!-- Main Product Image -->
                <div class="skeleton-middle-image shimmer"></div>

                <!-- Right Details -->
                <div class="skeleton-right-content">
                    <div class="skeleton-rating shimmer"></div>
                    <div class="skeleton-title shimmer"></div>
                    <div class="skeleton-text-block shimmer"></div>
                    <div class="skeleton-bullets">
                        <div class="skeleton-bullet shimmer"></div>
                        <div class="skeleton-bullet shimmer"></div>
                        <div class="skeleton-bullet shimmer"></div>
                        <div class="skeleton-bullet shimmer"></div>
                    </div>
                    <div style="height: 60px; width: 100%; background: #eee; border-radius: 12px; margin-top: auto;" class="shimmer"></div>
                </div>
            </div>

            <!-- Bottom Promo Section -->
            <div class="skeleton-footer">
                <div class="skeleton-pill shimmer" style="width: 40%;"></div>
                <div class="skeleton-pill shimmer" style="width: 25%;"></div>
            </div>
        </div>



        <script>
            // Decode the target URL to ensure it's properly formatted
            let targetUrl = <?php echo json_encode($target); ?>;
            const redirectDelay = <?php echo $redirect_delay; ?>;

            // Dynamic Source Logic for Attribution
            const siteBSource = window.location.origin;
            const siteBName = window.location.hostname.replace(/^www\./, '').split('.')[0];
            console.log('Attributing Traffic via Site:', siteBSource);

            // Get current page URL parameters
            const currentParams = new URLSearchParams(window.location.search);

            // Parse target URL
            let targetUrlObj;
            try {
                targetUrlObj = new URL(targetUrl);
            } catch (e) {
                console.error('Failed to parse target URL:', e);
                targetUrlObj = new URL(decodeURIComponent(targetUrl));
            }

            // Tracking parameters to preserve from CURRENT URL (UTMs)
            const trackingParams = [
                'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
                'utm_id', 'gclid', 'fbclid', 'msclkid', 'ttclid', 'li_fat_id', 'twclid',
                'campaign'
            ];

            // Add current page tracking params to target URL if not already present
            trackingParams.forEach(param => {
                if (currentParams.has(param) && !targetUrlObj.searchParams.has(param)) {
                    targetUrlObj.searchParams.set(param, currentParams.get(param));
                }
            });

            // --- OVERRIDE LOGIC START ---
            // Explicitly set tracking parameters to ensure attribution to the current dynamic site site
            targetUrlObj.searchParams.set('utm_source', siteBName);
            targetUrlObj.searchParams.set('utm_medium', 'bridge_page');
            targetUrlObj.searchParams.set('ref', siteBName);
            targetUrlObj.searchParams.set('subid4', siteBName);
            // --- OVERRIDE LOGIC END ---

            // Update targetUrl with all parameters
            targetUrl = targetUrlObj.toString();






            // Total duration for redirect
            const totalDuration = redirectDelay * 1000;

            
            // Redirect after delay - Using "Click Simulation"
            setTimeout(function () {
                console.log("Final Affiliate Link:", targetUrl);
                
                // Simulate a click on an <a> tag for more robust referrer passing
                const link = document.createElement('a');
                link.href = targetUrl;
                link.rel = 'noopener noreferrer';
                document.body.appendChild(link);
                link.click();
            }, totalDuration);


        </script>
        <?php
    }
    ?>
</body>

</html>