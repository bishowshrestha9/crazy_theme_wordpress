<?php
/**
 * Blocks/Shortcodes Class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class SPB_Blocks
{
    /**
     * Instance of this class
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        // Register shortcodes
        add_action('init', array($this, 'register_shortcodes'));
        add_action('init', array($this, 'register_gutenberg_blocks'));
        
        // Enqueue block assets
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));
    }

    /**
     * Register Gutenberg blocks
     */
    public function register_gutenberg_blocks()
    {
        // Register block category
        add_filter('block_categories_all', function($categories) {
            return array_merge(
                $categories,
                array(
                    array(
                        'slug' => 'supplement-blocks',
                        'title' => __('Supplement Blocks', 'supplement-page-builder'),
                        'icon'  => 'analytics',
                    ),
                )
            );
        });

        // Register dynamic blocks
        register_block_type('supplement-blocks/top-choices', array(
            'render_callback' => array($this, 'render_top_choices_block'),
            'attributes' => array(
                'sectionTitle' => array('type' => 'string', 'default' => 'Top Choices: Best Supplements for Memory and Focus'),
                'items' => array('type' => 'array', 'default' => array())
            )
        ));

        register_block_type('supplement-blocks/research-docs', array(
            'render_callback' => array($this, 'render_research_docs_block'),
            'attributes' => array(
                'intro' => array('type' => 'string', 'default' => ''),
                'excelUrl' => array('type' => 'string', 'default' => ''),
                'excelText' => array('type' => 'string', 'default' => ''),
                'pdfUrl' => array('type' => 'string', 'default' => ''),
                'pdfText' => array('type' => 'string', 'default' => '')
            )
        ));

        register_block_type('supplement-blocks/comparison-table', array(
            'render_callback' => array($this, 'render_comparison_table_block'),
            'attributes' => array(
                'content' => array('type' => 'string', 'default' => '')
            )
        ));

        register_block_type('supplement-blocks/source-docs', array(
            'render_callback' => array($this, 'render_source_docs_block'),
            'attributes' => array(
                'intro' => array('type' => 'string', 'default' => ''),
                'bullets' => array('type' => 'string', 'default' => ''),
                'excelUrl' => array('type' => 'string', 'default' => ''),
                'excelText' => array('type' => 'string', 'default' => ''),
                'pdfUrl' => array('type' => 'string', 'default' => ''),
                'pdfText' => array('type' => 'string', 'default' => '')
            )
        ));

        register_block_type('supplement-blocks/faq', array(
            'render_callback' => array($this, 'render_faq_block'),
            'attributes' => array(
                'items' => array('type' => 'array', 'default' => array())
            )
        ));

        register_block_type('supplement-blocks/disclaimer', array(
            'render_callback' => array($this, 'render_disclaimer_block'),
            'attributes' => array(
                'content' => array('type' => 'string', 'default' => '')
            )
        ));
    }

    /**
     * Render Top Choices Block (server-side)
     */
    public function render_top_choices_block($attributes)
    {
        $items = isset($attributes['items']) ? $attributes['items'] : array();
        $sectionTitle = isset($attributes['sectionTitle']) ? $attributes['sectionTitle'] : 'Top Choices: Best Supplements for Memory and Focus';
        
        if (empty($items)) {
            return '';
        }

        ob_start();
        ?>
        <section id="top-choices">
            <h2 class="t10supp-section-title"><?php echo esc_html($sectionTitle); ?></h2>
            
            <ul class="t10supp-product-list" style="list-style: none; padding: 0; margin: 0; counter-reset: product-counter;">
                <?php foreach ($items as $index => $item): ?>
                    <?php if (empty($item['name'])) continue; ?>
                    <li class="t10supp-product-item" style="background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%); padding: 20px 28px; margin-bottom: 18px; border-radius: 12px; border-left: 5px solid var(--primary-color); box-shadow: 0 2px 8px rgba(0,0,0,0.08); font-size: 17px; line-height: 1.7; position: relative; counter-increment: product-counter; transition: all 0.3s ease;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: var(--primary-color); color: white; border-radius: 50%; margin-right: 15px; font-weight: 700; font-size: 16px; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.4); flex-shrink: 0; vertical-align: middle;">
                            <?php echo ($index + 1); ?>
                        </span>
                        <?php if (!empty($item['badge'])): ?>
                            <strong style="color: #000; font-size: 16px;"><?php echo esc_html($item['badge']); ?> – </strong>
                        <?php endif; ?>
                        <span style="color: #1E40AF; font-weight: 700; font-size: 18px;"><?php echo esc_html($item['name']); ?></span>
                        <?php if (!empty($item['price'])): ?>
                            <span style="color: #374151; font-weight: 600;"> | <?php echo esc_html($item['price']); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($item['rating'])): ?>
                            <span style="color: #374151; font-weight: 600;"> | <?php echo esc_html($item['rating']); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($item['reviewLink'])): ?>
                            | <a href="<?php echo esc_url($item['reviewLink']); ?>" style="color: #1E40AF; font-weight: 700; text-decoration: underline; transition: color 0.2s;">Read Review</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Research Docs Block (server-side)
     */
    public function render_research_docs_block($attributes)
    {
        $intro = isset($attributes['intro']) ? $attributes['intro'] : '';
        $excelUrl = isset($attributes['excelUrl']) ? $attributes['excelUrl'] : '';
        $excelText = isset($attributes['excelText']) ? $attributes['excelText'] : '';
        $pdfUrl = isset($attributes['pdfUrl']) ? $attributes['pdfUrl'] : '';
        $pdfText = isset($attributes['pdfText']) ? $attributes['pdfText'] : '';

        if (empty($intro)) {
            $intro = "To maintain transparency, we've made our full research workbook and supporting analysis publicly available. These documents detail the scoring methodology, evidence standards, ingredient evaluation, and ranking logic used in this article.";
        }
        
        // Set default button text if URL exists but text is empty
        if (!empty($excelUrl) && empty($excelText)) {
            $excelText = 'Download the Full Research Workbook (Excel)';
        }
        if (!empty($pdfUrl) && empty($pdfText)) {
            $pdfText = 'Download the Detailed Research Document (PDF)';
        }
        
        ob_start();
        ?>
        <section id="research-docs">
            <div class="t10supp-research-docs">
                <h3>Downloadable Research Transparency Document</h3>
                <p><?php echo esc_html($intro); ?></p>
                <div class="t10supp-download-links">
                    <?php if (!empty($excelUrl)): ?>
                        <a href="<?php echo esc_url($excelUrl); ?>" class="t10supp-download-btn" target="_blank" rel="noopener"><?php echo esc_html($excelText); ?></a>
                    <?php endif; ?>
                    <?php if (!empty($pdfUrl)): ?>
                        <a href="<?php echo esc_url($pdfUrl); ?>" class="t10supp-download-btn" target="_blank" rel="noopener"><?php echo esc_html($pdfText); ?></a>
                    <?php endif; ?>
                    <?php if (empty($excelUrl) && empty($pdfUrl)): ?>
                        <p style="font-style: italic; color: #6B7280; margin-top: 15px;"><em>Research documents will be available soon.</em></p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Comparison Table Block (server-side)
     */
    public function render_comparison_table_block($attributes)
    {
        $content = isset($attributes['content']) ? $attributes['content'] : '';
        
        // Clean up the content and ensure proper table structure
        if (!empty($content)) {
            $content = trim($content);
            
            // Remove Word/HTML artifacts and clean up
            $content = $this->clean_table_content($content);
            
            // Check if content is pipe-separated (from ChatGPT/Markdown)
            if (strpos($content, '|') !== false && strpos($content, '<table') === false) {
                // Convert pipe-separated table to HTML
                $content = $this->convert_pipe_table_to_html($content);
            }
            // Check if content is tab-separated or has no HTML (plain text from Word)
            elseif (strpos($content, '<table') === false && strpos($content, '<tr') === false && strpos($content, "\t") !== false) {
                // Convert tab-separated to HTML table
                $content = $this->convert_tab_separated_to_html($content);
            }
            // If it's plain text with spaces (Word paste without tabs)
            elseif (strpos($content, '<table') === false && strpos($content, '<tr') === false && strlen($content) > 50) {
                // Try to detect columns and convert
                $content = $this->convert_plain_text_to_html($content);
            }
            // If content doesn't have <table> tags but has <tr>, wrap it
            elseif (strpos($content, '<table') === false && strpos($content, '<tr') !== false) {
                $content = '<table>' . $content . '</table>';
            }
        }
        
        ob_start();
        ?>
        <section id="comparison">
            <h2 class="t10supp-section-title">Comparison Table</h2>
            <p>Each product offers distinct advantages based on your specific goals and health profile. This comparison highlights key decision factors to help you select the most suitable option.</p>
            
            <?php if (!empty($content)): ?>
                <div class="t10supp-comparison-table-wrapper">
                    <?php echo wp_kses_post($content); ?>
                </div>
            <?php else: ?>
                <div class="t10supp-comparison-table-wrapper">
                    <p style="padding: 40px; text-align: center; background: #f9f9f9; border-radius: 8px; color: #666;">
                        <em>No comparison table added yet. Please add table content in the block editor.</em>
                    </p>
                </div>
            <?php endif; ?>
        </section>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Convert tab-separated content to HTML table
     */
    private function convert_tab_separated_to_html($content)
    {
        $lines = array_map('trim', explode("\n", trim($content)));
        $lines = array_filter($lines); // Remove empty lines
        
        if (empty($lines)) {
            return $content;
        }
        
        $html = '<table>';
        $is_first_row = true;
        
        foreach ($lines as $line) {
            $cells = explode("\t", $line);
            $cells = array_map('trim', $cells);
            
            $tag = $is_first_row ? 'th' : 'td';
            
            $html .= '<tr>';
            foreach ($cells as $cell) {
                $html .= '<' . $tag . '>' . esc_html($cell) . '</' . $tag . '>';
            }
            $html .= '</tr>';
            
            $is_first_row = false;
        }
        
        $html .= '</table>';
        return $html;
    }
    
    /**
     * Convert plain text with multiple spaces/columns to HTML table
     */
    private function convert_plain_text_to_html($content)
    {
        // First, check if this looks like Word table data with specific patterns
        // Pattern: "Product Name Best For Description... Rating Score"
        
        // Try to find header row by looking for common column names
        $has_table_headers = preg_match('/\b(Product|Best For|Ingredient|Quality|Certifications|Safety|Profile|User Rating|Expert Rating|Memory Lift|Marobrain)\b/i', $content);
        
        if (!$has_table_headers) {
            return $content; // Not table data
        }
        
        // Split content into potential rows by looking for product names or numeric ratings
        // Pattern: Capital letter words followed by lots of text ending with numeric rating
        preg_match_all('/([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*)\s+([^0-9]+?)\s+([\d.\/]+)\s+([\d.\/]+)/', $content, $matches, PREG_SET_ORDER);
        
        if (empty($matches)) {
            // Try alternate pattern: Look for structured data with clear separators
            // If content has "Memory Lift", "Marobrain", etc. - these are products
            $products = [
                'Memory Lift', 'Marobrain', 'Synaptigen', 'Neuro Sharp', 'Pro Mind Complex',
                'Neuro Zoom', 'MemoShield', 'Brain Savior', 'Nerve Fresh', 'Vyvamind'
            ];
            
            $html = '<table><tr><th>Product</th><th>Best For</th><th>Details</th><th>User Rating</th><th>Expert Rating</th></tr>';
            
            foreach ($products as $product) {
                $pattern = '/' . preg_quote($product, '/') . '\s+([^0-9]+?)\s+([\d.\/]+)\s+([\d.\/]+)/i';
                if (preg_match($pattern, $content, $match)) {
                    $html .= '<tr>';
                    $html .= '<td>' . esc_html($product) . '</td>';
                    $html .= '<td>' . esc_html(trim($match[1])) . '</td>';
                    $html .= '<td></td>';
                    $html .= '<td>' . esc_html($match[2]) . '</td>';
                    $html .= '<td>' . esc_html($match[3]) . '</td>';
                    $html .= '</tr>';
                }
            }
            
            $html .= '</table>';
            
            // Check if we found any data
            if (strpos($html, '<tr><td>') !== false) {
                return $html;
            }
        }
        
        // If we still can't parse, return original
        return $content;
    }
    
    /**
     * Clean table content from Word/HTML artifacts
     */
    private function clean_table_content($content)
    {
        // Remove style tags and their content
        $content = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $content);
        
        // Remove script tags and their content
        $content = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $content);
        
        // Remove Word XML namespace and other Office artifacts
        $content = preg_replace('/<\?xml[^>]*>/i', '', $content);
        $content = preg_replace('/<\/?o:[^>]*>/i', '', $content);
        $content = preg_replace('/<\/?w:[^>]*>/i', '', $content);
        $content = preg_replace('/<\/?v:[^>]*>/i', '', $content);
        $content = preg_replace('/<\/?st1:[^>]*>/i', '', $content);
        
        // Remove class, style, and other attributes from tables
        $content = preg_replace('/<(table|thead|tbody|tr|td|th)([^>]*?)class="[^"]*"([^>]*?)>/i', '<$1$2$3>', $content);
        $content = preg_replace('/<(table|thead|tbody|tr|td|th)([^>]*?)style="[^"]*"([^>]*?)>/i', '<$1$2$3>', $content);
        $content = preg_replace('/<(table|thead|tbody|tr|td|th)([^>]*?)width="[^"]*"([^>]*?)>/i', '<$1$2$3>', $content);
        $content = preg_replace('/<(table|thead|tbody|tr|td|th)([^>]*?)height="[^"]*"([^>]*?)>/i', '<$1$2$3>', $content);
        $content = preg_replace('/<(table|thead|tbody|tr|td|th)([^>]*?)border="[^"]*"([^>]*?)>/i', '<$1$2$3>', $content);
        $content = preg_replace('/<(table|thead|tbody|tr|td|th)([^>]*?)cellpadding="[^"]*"([^>]*?)>/i', '<$1$2$3>', $content);
        $content = preg_replace('/<(table|thead|tbody|tr|td|th)([^>]*?)cellspacing="[^"]*"([^>]*?)>/i', '<$1$2$3>', $content);
        
        // Remove empty paragraphs and spans
        $content = preg_replace('/<p[^>]*>\s*<\/p>/i', '', $content);
        $content = preg_replace('/<span[^>]*>(.*?)<\/span>/i', '$1', $content);
        
        // Remove non-breaking spaces and clean whitespace
        $content = str_replace('&nbsp;', ' ', $content);
        $content = preg_replace('/\s+/', ' ', $content);
        
        // Clean up multiple spaces within table cells
        $content = preg_replace('/<(td|th)([^>]*)>\s+/i', '<$1$2>', $content);
        $content = preg_replace('/\s+<\/(td|th)>/i', '</$1>', $content);
        
        return $content;
    }

    /**
     * Render Source Docs Block (server-side)
     */
    public function render_source_docs_block($attributes)
    {
        $intro = isset($attributes['intro']) ? $attributes['intro'] : '';
        $bullets = isset($attributes['bullets']) ? $attributes['bullets'] : '';
        $excelUrl = isset($attributes['excelUrl']) ? $attributes['excelUrl'] : '';
        $excelText = isset($attributes['excelText']) ? $attributes['excelText'] : 'Download the Full Research Workbook (Excel)';
        $pdfUrl = isset($attributes['pdfUrl']) ? $attributes['pdfUrl'] : '';
        $pdfText = isset($attributes['pdfText']) ? $attributes['pdfText'] : 'Download the Detailed Research Document (PDF)';

        if (empty($intro)) {
            $intro = 'As part of our commitment to evidence-based publishing, SupplementMag provides full access to the internal research materials used to create this ranking.';
        }
        
        if (empty($bullets)) {
            $bullets = "A consolidated comparison sheet ranking all reviewed products\nA weighted scoring model across safety, effectiveness, ingredient quality\nProduct-specific research sheets with detailed analysis\nTransparent formulas and charts supporting all final ratings";
        }
        
        ob_start();
        ?>
        <section id="disclosure">
            <h2 class="t10supp-section-title">Source Documents & Disclosure</h2>
            
            <div class="t10supp-research-docs">
                <p><?php echo esc_html($intro); ?></p>
                
                <?php if (!empty($bullets)): ?>
                    <h4 style="font-size: 22px; margin-top: 28px; margin-bottom: 16px; color: #111827;">The research document includes</h4>
                    <ul style="font-size: 17px; line-height: 1.9;">
                        <?php
                        $bullet_lines = explode("\n", $bullets);
                        foreach ($bullet_lines as $bullet) {
                            $bullet = trim($bullet);
                            if (!empty($bullet)) {
                                echo '<li>' . esc_html($bullet) . '</li>';
                            }
                        }
                        ?>
                    </ul>
                <?php endif; ?>
                
                <h4 style="font-size: 22px; margin-top: 28px; margin-bottom: 20px; color: #111827;">Research Files [Downloadable]</h4>
                <div class="t10supp-download-links">
                    <?php if (!empty($excelUrl)): ?>
                        <a href="<?php echo esc_url($excelUrl); ?>" class="t10supp-download-btn" target="_blank" rel="noopener"><?php echo esc_html($excelText); ?></a>
                    <?php endif; ?>
                    <?php if (!empty($pdfUrl)): ?>
                        <a href="<?php echo esc_url($pdfUrl); ?>" class="t10supp-download-btn" target="_blank" rel="noopener"><?php echo esc_html($pdfText); ?></a>
                    <?php endif; ?>
                    <?php if (empty($excelUrl) && empty($pdfUrl)): ?>
                        <p style="font-style: italic; color: #6B7280; margin-top: 15px;"><em>Download links will be available soon. Please add URLs in block settings.</em></p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render FAQ Block (server-side)
     */
    public function render_faq_block($attributes)
    {
        $items = isset($attributes['items']) ? $attributes['items'] : array();
        if (empty($items)) {
            return '';
        }

        ob_start();
        ?>
        <section class="t10supp-faqs-section" id="faqs">
            <h2 class="t10supp-section-title">Frequently Asked Questions</h2>
            
            <?php foreach ($items as $index => $item): ?>
                <?php if (!empty($item['question'])): ?>
                    <div class="t10supp-faq-item">
                        <button class="t10supp-faq-question-btn" onclick="this.nextElementSibling.classList.toggle('active'); this.querySelector('.t10supp-faq-arrow').classList.toggle('active');">
                            <?php echo esc_html($item['question']); ?>
                            <span class="t10supp-faq-arrow">▼</span>
                        </button>
                        <?php if (!empty($item['answer'])): ?>
                            <div class="t10supp-faq-answer">
                                <?php echo wp_kses_post(wpautop($item['answer'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Disclaimer Block (server-side)
     */
    public function render_disclaimer_block($attributes)
    {
        $content = isset($attributes['content']) ? $attributes['content'] : '';
        
        if (empty($content)) {
            return '';
        }
        
        ob_start();
        ?>
        <div class="t10supp-disclaimer-box">
            <h4>Disclaimer</h4>
            <?php echo wp_kses_post(wpautop($content)); ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Enqueue block editor assets
     */
    public function enqueue_block_editor_assets()
    {
        global $post;
        if (!$post || $post->post_type !== 'supplement_page') {
            return;
        }

        wp_enqueue_script(
            'spb-blocks',
            SPB_PLUGIN_URL . 'assets/js/blocks.js',
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components'),
            SPB_VERSION,
            true
        );

        wp_enqueue_style(
            'spb-blocks-editor',
            SPB_PLUGIN_URL . 'assets/css/blocks-editor.css',
            array('wp-edit-blocks'),
            SPB_VERSION
        );
    }

    /**
     * Register shortcodes
     */
    public function register_shortcodes()
    {
        add_shortcode('top_choices', array($this, 'render_top_choices'));
        add_shortcode('research_docs', array($this, 'render_research_docs'));
        add_shortcode('comparison_table', array($this, 'render_comparison_table'));
        add_shortcode('faq_block', array($this, 'render_faq'));
        add_shortcode('source_docs', array($this, 'render_source_docs'));
        add_shortcode('disclaimer_block', array($this, 'render_disclaimer'));
    }

    /**
     * Render Top Choices Block
     */
    public function render_top_choices($atts)
    {
        $atts = shortcode_atts(array(
            'items' => '',
        ), $atts);

        if (empty($atts['items'])) {
            return '<div class="spb-block-placeholder">Top Choices Block - Configure items in the shortcode</div>';
        }

        $items = json_decode(base64_decode($atts['items']), true);
        if (!$items) return '';

        ob_start();
        ?>
        <section class="spb-block spb-top-choices">
            <h2 class="spb-section-title">Top Choices</h2>
            <div class="spb-choices-list">
                <?php foreach ($items as $index => $item): ?>
                <div class="spb-choice-item">
                    <?php if (!empty($item['badge'])): ?>
                        <div class="spb-choice-badge"><?php echo esc_html($item['badge']); ?></div>
                    <?php endif; ?>
                    <div class="spb-choice-content">
                        <div class="spb-choice-number"><?php echo $index + 1; ?>.</div>
                        <div class="spb-choice-name"><?php echo esc_html($item['name']); ?></div>
                        <?php if (!empty($item['price'])): ?>
                            <div class="spb-choice-price"><?php echo esc_html($item['price']); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($item['rating'])): ?>
                            <div class="spb-choice-rating"><?php echo esc_html($item['rating']); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($item['link_text'])): ?>
                            <div class="spb-choice-link"><?php echo esc_html($item['link_text']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Research Docs Block
     */
    public function render_research_docs($atts)
    {
        $atts = shortcode_atts(array(
            'intro' => '',
            'excel_url' => '',
            'excel_text' => 'Download Excel',
            'pdf_url' => '',
            'pdf_text' => 'Download PDF',
        ), $atts);

        ob_start();
        ?>
        <section class="spb-block spb-research-docs">
            <h3 class="spb-subsection-title">Downloadable Research Transparency Document</h3>
            <?php if (!empty($atts['intro'])): ?>
                <p class="spb-intro"><?php echo esc_html($atts['intro']); ?></p>
            <?php endif; ?>
            <div class="spb-download-buttons">
                <?php if (!empty($atts['excel_url'])): ?>
                    <a href="<?php echo esc_url($atts['excel_url']); ?>" class="spb-download-btn" target="_blank" rel="noopener">
                        <?php echo esc_html($atts['excel_text']); ?>
                    </a>
                <?php endif; ?>
                <?php if (!empty($atts['pdf_url'])): ?>
                    <a href="<?php echo esc_url($atts['pdf_url']); ?>" class="spb-download-btn" target="_blank" rel="noopener">
                        <?php echo esc_html($atts['pdf_text']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Comparison Table Block
     */
    public function render_comparison_table($atts, $content = null)
    {
        ob_start();
        ?>
        <section class="spb-block spb-comparison-table">
            <h2 class="spb-section-title">Comparison Table</h2>
            <div class="spb-table-wrapper">
                <?php echo wp_kses_post($content); ?>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render FAQ Block
     */
    public function render_faq($atts)
    {
        $atts = shortcode_atts(array(
            'items' => '',
        ), $atts);

        if (empty($atts['items'])) {
            return '<div class="spb-block-placeholder">FAQ Block - Configure items in the shortcode</div>';
        }

        $items = json_decode(base64_decode($atts['items']), true);
        if (!$items) return '';

        ob_start();
        ?>
        <section class="spb-block spb-faq">
            <h2 class="spb-section-title">FAQ</h2>
            <div class="spb-faq-list">
                <?php foreach ($items as $item): ?>
                <div class="spb-faq-item">
                    <h4 class="spb-faq-question"><?php echo esc_html($item['question']); ?></h4>
                    <div class="spb-faq-answer"><?php echo wpautop(esc_html($item['answer'])); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Source Docs Block
     */
    public function render_source_docs($atts)
    {
        $atts = shortcode_atts(array(
            'intro' => '',
            'bullets' => '',
            'excel_url' => '',
            'excel_text' => 'Download Excel',
            'pdf_url' => '',
            'pdf_text' => 'Download PDF',
        ), $atts);

        ob_start();
        ?>
        <section class="spb-block spb-source-docs">
            <h2 class="spb-section-title">Source Documents & Disclosure</h2>
            <?php if (!empty($atts['intro'])): ?>
                <p class="spb-intro"><?php echo esc_html($atts['intro']); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($atts['bullets'])): ?>
                <h4 class="spb-subheading">The research document includes</h4>
                <ul class="spb-bullet-list">
                    <?php 
                    $bullets = explode('|', $atts['bullets']);
                    foreach ($bullets as $bullet): 
                        if (!empty(trim($bullet))):
                    ?>
                        <li><?php echo esc_html(trim($bullet)); ?></li>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </ul>
            <?php endif; ?>
            
            <h4 class="spb-subheading">Research Files [Downloadable]</h4>
            <div class="spb-download-buttons">
                <?php if (!empty($atts['excel_url'])): ?>
                    <a href="<?php echo esc_url($atts['excel_url']); ?>" class="spb-download-btn" target="_blank" rel="noopener">
                        <?php echo esc_html($atts['excel_text']); ?>
                    </a>
                <?php endif; ?>
                <?php if (!empty($atts['pdf_url'])): ?>
                    <a href="<?php echo esc_url($atts['pdf_url']); ?>" class="spb-download-btn" target="_blank" rel="noopener">
                        <?php echo esc_html($atts['pdf_text']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Convert pipe-separated table (Markdown/ChatGPT format) to HTML table
     */
    private function convert_pipe_table_to_html($pipe_table)
    {
        $lines = array_map('trim', explode("\n", trim($pipe_table)));
        $html = '<table>';
        $is_first_row = true;
        
        foreach ($lines as $line) {
            // Skip empty lines and separator lines (---|---|---)
            if (empty($line) || preg_match('/^[\|\s\-]+$/', $line)) {
                continue;
            }
            
            // Remove leading/trailing pipes and split by pipe
            $line = trim($line, '| ');
            $cells = array_map('trim', explode('|', $line));
            
            // Determine if this is a header row (first row)
            $tag = $is_first_row ? 'th' : 'td';
            
            $html .= '<tr>';
            foreach ($cells as $cell) {
                $html .= '<' . $tag . '>' . esc_html($cell) . '</' . $tag . '>';
            }
            $html .= '</tr>';
            
            $is_first_row = false;
        }
        
        $html .= '</table>';
        return $html;
    }

    /**
     * Render Disclaimer Block
     */
    public function render_disclaimer($atts, $content = null)
    {
        ob_start();
        ?>
        <section class="spb-block spb-disclaimer">
            <div class="spb-disclaimer-content">
                <?php echo wp_kses_post($content); ?>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
}
