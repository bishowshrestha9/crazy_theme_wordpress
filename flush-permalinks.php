<?php
/**
 * Flush Permalinks Script
 * Visit this file in browser: http://redirect.local/flush-permalinks.php
 */

// Load WordPress
require_once('wp-load.php');

// Flush rewrite rules
flush_rewrite_rules();

echo '<h1>Rewrite Rules Flushed!</h1>';
echo '<p>The permalink structure has been refreshed.</p>';
echo '<p><a href="' . admin_url('edit.php?post_type=supplement_page') . '">Go to Supplement Pages</a></p>';
echo '<p><strong>Now try viewing your published supplement page again.</strong></p>';
