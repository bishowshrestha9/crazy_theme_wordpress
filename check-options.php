<?php
require_once('wp-load.php');
echo "amr_redirect_delay: " . get_option('amr_redirect_delay', 'DEFAULT(5)') . "\n";
echo "amr_redirect_heading: " . get_option('amr_redirect_heading', 'DEFAULT') . "\n";
echo "amr_redirect_subtitle: " . get_option('amr_redirect_subtitle', 'DEFAULT') . "\n";
unlink(__FILE__);
