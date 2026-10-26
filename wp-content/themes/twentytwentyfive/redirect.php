<?php
/* Template Name: Meta Redirect Page */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <?php wp_head(); ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body.redirect-page {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f4f5f7;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }
        
        .redirect-wrapper {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 40px 50px;
            max-width: 550px;
            width: 90%;
            text-align: center;
            border-top: 4px solid #0073aa;
        }
        
        .redirect-wrapper h1 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .redirect-wrapper .subheading {
            font-size: 16px;
            color: #525f6f;
            margin-bottom: 30px;
        }

        .destination-info {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 30px;
            text-align: left;
        }

        .destination-info span {
            font-size: 14px;
            color: #6c757d;
        }

        .destination-info strong {
            display: block;
            font-size: 16px;
            color: #343a40;
            margin-top: 4px;
            word-break: break-all;
        }
        
        .timer-message {
            margin-bottom: 25px;
            font-size: 15px;
            color: #525f6f;
        }

        .timer-message #timer {
            font-weight: bold;
            color: #0073aa;
        }
        
        .continue-button {
            display: inline-block;
            background-color: #0073aa;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
            transition: background-color 0.2s ease;
            border: 1px solid #0073aa;
        }
        
        .continue-button:hover {
            background-color: #005a87;
            color: white;
        }
        
        .footer-note {
            margin-top: 30px;
            font-size: 13px;
            color: #909da9;
        }
        
        .error-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 40px 50px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            border-top: 4px solid #d63638;
        }
        
        .error-container h1 {
            color: #d63638;
            font-size: 22px;
            margin-bottom: 15px;
        }
        
        .error-container p {
            color: #525f6f;
            font-size: 16px;
        }
        
        @media (max-width: 640px) {
            .redirect-wrapper {
                padding: 30px 25px;
            }
        }
    </style>
</head>
<body class="redirect-page">
    <?php
    $target = isset($_GET['target']) ? esc_url_raw($_GET['target']) : '';
    
    if(!$target) {
        ?>
        <div class="error-container">
            <h1>Invalid Redirect</h1>
            <p>The destination URL was not specified. Please go back and try again.</p>
        </div>
        <?php
    } else {
        $parsed_url = parse_url($target);
        $display_domain = $parsed_url['host'] ?? 'N/A';
        ?>
        <div class="redirect-wrapper">
            <h1>One Moment Please...</h1>
            <p class="subheading">We're redirecting you to the official site for your product.</p>
            
            <div class="destination-info">
                <span>Going to:</span>
                <strong><?php echo esc_html($display_domain); ?></strong>
            </div>

            <div class="timer-message">
                You will be redirected in <span id="timer">5</span> seconds...
            </div>
            
            <a href="<?php echo esc_url($target); ?>" class="continue-button">Continue Now</a>
            
            <p class="footer-note">You are leaving <?php echo esc_html(get_bloginfo('name')); ?></p>
        </div>

        <script>
            let seconds = 5;
            const targetUrl = <?php echo json_encode($target); ?>;
            const timerElement = document.getElementById("timer");

            function countdown() {
                if (seconds < 0) {
                    window.location.href = targetUrl;
                } else {
                    timerElement.textContent = seconds;
                    seconds--;
                    setTimeout(countdown, 1000);
                }
            }
            
            countdown();
        </script>
        <?php
    }
    ?>
    <?php wp_footer(); ?>
</body>
</html>
