<?php
/**
 * Add Dummy Data for Top 10 Plugin
 * 
 * Usage: Visit this file directly in browser or run via WP-CLI
 * Example: https://yoursite.com/wp-content/plugins/Top10Plugin/add-dummy-data.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('You do not have permission to access this page.');
}

// Dummy product data
$dummy_products = array(
    array(
        'title' => 'Sony WH-1000XM5 Wireless Headphones',
        'description' => 'Industry-leading noise canceling with dual noise sensor technology. Next-level music with Edge-AI, for a premium listening experience. Up to 30-hour battery life with quick charging (3 min charge for 3 hours of playback).',
        'price' => '$399.99',
        'rating' => '4.8',
        'buy_link' => 'https://amazon.com/sony-wh-1000xm5',
        'image' => 'https://picsum.photos/seed/headphone1/400/400',
        'badge_text' => 'Best Overall Battery Heated Gloves',
        'specs' => array(
            'best_for' => 'daily commuting, resort skiing, casual winter tasks',
            'heating_levels' => '3 (Low/Mid/High)',
            'battery' => '7.4V 3000mAh lithium-ion',
            'battery_life' => '3-8 hours',
            'heating_zones' => 'Fingers, Back of hand',
            'materials' => 'Softshell, synthetic leather palm',
            'waterproofing' => 'DWR membrane',
            'sizes' => 'S-XXL',
            'amazon_rating' => '4.5',
            'trustpilot_rating' => '4.7',
            'google_rating' => '4.3',
            'research_observation' => 'Testing showed steady warmth across fingers during 6-hour cold exposure simulations, outperforming basic insulated gloves. Dexterity remained strong for zippers and steering. Battery consistently impressed on medium setting. Overall Rating: 9.6/10, liked balanced performance and fit.',
            'customer_review_summary' => 'Customers consistently praise warmth reliability and comfort during commutes and light outdoor work, though some note high-heat battery limits.',
            'featured_review' => 'Finally kept my fingers warm on -17°C bike commutes. Medium heat lasted entire trip.'
        ),
        'features' => array(
            'Three heat levels conserve battery for all-day use',
            'Full finger heating prevents cold fingertips',
            'Waterproof shell blocks wet snow penetration',
            'Pre-curved design improves tool grip',
            'Touchscreen fingertips enable phone use',
            'Secure battery pocket withstands active wear',
            'Adjustable cuff seals drafts effectively'
        ),
        'pros' => array(
            'Consistent full-hand warmth',
            'Versatile for multiple activities',
            'Strong waterproof performance'
        ),
        'cons' => array(
            'High setting drains battery faster',
            'Sizing runs slightly snug',
            'Lacks wrist leashes'
        )
    ),
    array(
        'title' => 'Apple AirPods Pro (2nd Generation)',
        'description' => 'Active Noise Cancellation with Adaptive Transparency. Personalized Spatial Audio with dynamic head tracking. Up to 6 hours of listening time with ANC enabled. MagSafe Charging Case included.',
        'price' => '$249.99',
        'rating' => '4.9',
        'buy_link' => 'https://amazon.com/apple-airpods-pro-2',
        'image' => 'https://picsum.photos/seed/headphone2/400/400',
        'badge_text' => 'Best Extreme Sub-Zero Heated Gloves',
        'specs' => array(
            'best_for' => 'extreme cold exposure, ice fishing, snowmobiling',
            'heating_levels' => '4 (Off/Low/Med/High)',
            'battery' => '7.4V 2200mAh rechargeable',
            'battery_life' => '2-10 hours',
            'heating_zones' => 'Entire hand including thumb',
            'materials' => 'Goose down insulation, leather palm',
            'waterproofing' => 'Gore-Tex waterproof breathable',
            'sizes' => 'XS-XL',
            'amazon_rating' => '4.8',
            'trustpilot_rating' => '4.9',
            'google_rating' => '4.6',
            'research_observation' => 'Delivered exceptional warmth in -30°C conditions during extended outdoor exposure. Bulkier design trades dexterity for superior insulation. High heat setting maintained comfort for 3+ hours in extreme wind chill.',
            'customer_review_summary' => 'Users rave about extreme cold performance for ice fishing and snowmobiling, though note reduced dexterity for detailed tasks.',
            'featured_review' => 'Kept hands toasty during 4-hour ice fishing in -25°C. Worth every penny for serious winter use.'
        ),
        'features' => array(
            'Extra insulation for sub-zero temperatures',
            'Four heat settings for precise control',
            'Extended cuff prevents snow entry',
            'Reinforced palms for durability',
            'Quick-release battery system',
            'Reflective accents for visibility'
        ),
        'pros' => array(
            'Exceptional warmth in extreme cold',
            'Long battery life on low setting',
            'Excellent build quality'
        ),
        'cons' => array(
            'Bulky design limits dexterity',
            'Higher price point',
            'Heavy when wet'
        )
    ),
    array(
        'title' => 'Bose QuietComfort 45',
        'description' => 'World-class noise cancellation. TriPort acoustic architecture for deep, clear sound. Up to 24 hours of battery life. Comfortable around-ear fit perfect for long listening sessions.',
        'price' => '$329.00',
        'rating' => '4.7',
        'buy_link' => 'https://amazon.com/bose-quietcomfort-45',
        'image' => 'https://picsum.photos/seed/headphone3/400/400',
        'badge_text' => 'Best Heated Ski Gloves',
        'specs' => array(
            'best_for' => 'skiing, snowboarding, winter sports',
            'heating_levels' => '3 (Low/Med/High)',
            'battery' => '7.4V 2600mAh lithium',
            'battery_life' => '4-9 hours',
            'heating_zones' => 'Fingers and back of hand',
            'materials' => 'Synthetic insulation, water-resistant shell',
            'waterproofing' => 'Water-resistant coating',
            'sizes' => 'S-XL',
            'amazon_rating' => '4.6',
            'trustpilot_rating' => '4.5',
            'google_rating' => '4.4',
            'research_observation' => 'Optimized for active winter sports with excellent grip and flexibility. Heating distribution favors fingertips for pole grip. Quick-dry materials performed well in wet snow conditions.',
            'customer_review_summary' => 'Skiers appreciate the balance between warmth and dexterity, with particular praise for grip on ski poles.',
            'featured_review' => 'Perfect for full-day skiing. Hands stayed warm without overheating, and I could still feel my poles perfectly.'
        ),
        'features' => array(
            'Articulated fingers for natural movement',
            'Silicon palm grip for poles',
            'Wrist cinch prevents snow entry',
            'Breathable back panel reduces sweat',
            'Compatible with ski glove clips',
            'Low-profile battery pocket'
        ),
        'pros' => array(
            'Excellent dexterity for sports',
            'Good breathability prevents sweat',
            'Secure grip on equipment'
        ),
        'cons' => array(
            'Less insulation than gauntlet styles',
            'Battery life moderate in cold',
            'Wrist strap could be longer'
        )
    ),
    array(
        'title' => 'Samsung Galaxy Buds2 Pro',
        'description' => 'Intelligent ANC with machine learning. 360 Audio for immersive listening. Hi-Fi sound quality with 2-way speakers. IPX7 water resistance for workouts.',
        'price' => '$229.99',
        'rating' => '4.6',
        'buy_link' => 'https://amazon.com/samsung-galaxy-buds2-pro',
        'image' => 'https://picsum.photos/seed/headphone4/400/400',
        'badge_text' => 'Best Lightweight Commuter Heated Gloves',
        'specs' => array(
            'best_for' => 'urban commuting, dog walking, everyday use',
            'heating_levels' => '3 (Eco/Normal/Boost)',
            'battery' => '3.7V 2000mAh per glove',
            'battery_life' => '3-6 hours',
            'heating_zones' => 'Fingertips primarily',
            'materials' => 'Fleece lining, polyester shell',
            'waterproofing' => 'Water-resistant (not waterproof)',
            'sizes' => 'M-XL',
            'amazon_rating' => '4.3',
            'trustpilot_rating' => '4.4',
            'google_rating' => '4.2',
            'research_observation' => 'Best suited for moderate cold (0°C to -10°C). Lightweight design prioritizes comfort for everyday wear. Battery life adequate for typical commute durations.',
            'customer_review_summary' => 'Commuters value the lightweight feel and quick heating, though some wish for more robust waterproofing.',
            'featured_review' => 'Perfect for my 20-minute walk to work. Heats up fast and I barely notice I\'m wearing them.'
        ),
        'features' => array(
            'Lightweight for all-day comfort',
            'Fast heating startup (under 30 seconds)',
            'Touch-sensitive controls on cuff',
            'Smartphone compatible fingertips',
            'Compact battery design',
            'Machine washable (remove batteries)'
        ),
        'pros' => array(
            'Very lightweight and comfortable',
            'Quick heat-up time',
            'Affordable price point'
        ),
        'cons' => array(
            'Limited warmth in extreme cold',
            'Water resistance could be better',
            'Shorter battery life'
        )
    ),
    array(
        'title' => 'Jabra Elite 85h',
        'description' => 'SmartSound audio that adapts to your surroundings. Up to 36 hours of battery with ANC. Rain-resistant with 2-year warranty against dust and water. 8-microphone technology for crystal clear calls.',
        'price' => '$299.99',
        'rating' => '4.5',
        'buy_link' => 'https://amazon.com/jabra-elite-85h',
        'image' => 'https://picsum.photos/seed/headphone5/400/400',
        'badge_text' => 'Best Work Gloves with Heating',
        'specs' => array(
            'best_for' => 'work gloves, construction, outdoor labor',
            'heating_levels' => '2 (Normal/High)',
            'battery' => '7.4V 3500mAh',
            'battery_life' => '6-12 hours',
            'heating_zones' => 'Full hand coverage',
            'materials' => 'Heavy-duty synthetic leather, reinforced palms',
            'waterproofing' => 'Fully waterproof sealed seams',
            'sizes' => 'L-XXL',
            'amazon_rating' => '4.7',
            'trustpilot_rating' => '4.6',
            'google_rating' => '4.5',
            'research_observation' => 'Exceptionally durable construction withstood heavy use and rough conditions. Excellent battery capacity supports full work shifts. Palm reinforcement effective for tool grip.',
            'customer_review_summary' => 'Construction workers and tradespeople appreciate the durability and all-day battery, though note the gloves run large.',
            'featured_review' => 'Survived a month of construction work in freezing temps. Still heating strong and showing minimal wear.'
        ),
        'features' => array(
            'Reinforced high-wear areas',
            'Extended battery for long shifts',
            'Cut-resistant material in key zones',
            'Easy-clean synthetic leather',
            'Secure velcro wrist closure',
            'Reflective safety strips'
        ),
        'pros' => array(
            'Extremely durable construction',
            'Long battery life for work shifts',
            'Excellent for heavy-duty use'
        ),
        'cons' => array(
            'Bulkier than other options',
            'Limited sizes (large only)',
            'Takes time to break in'
        )
    ),
    array(
        'title' => 'Sennheiser Momentum 4 Wireless',
        'description' => 'Exceptional sound quality with audiophile drivers. Up to 60 hours of battery life. Adaptive Noise Cancellation. Lightweight and foldable design for travel.',
        'price' => '$379.95',
        'rating' => '4.8',
        'buy_link' => 'https://amazon.com/sennheiser-momentum-4',
        'image' => 'https://picsum.photos/seed/headphone6/400/400',
        'badge_text' => 'Best for Cycling & Active Sports',
        'specs' => array(
            'best_for' => 'cycling, running, active outdoor sports',
            'heating_levels' => '3 (Low/Med/High)',
            'battery' => '3.7V 1800mAh',
            'battery_life' => '2.5-5 hours',
            'heating_zones' => 'Fingers only',
            'materials' => 'Lightweight synthetic, touchscreen compatible',
            'waterproofing' => 'Splash-resistant',
            'sizes' => 'S-L',
            'amazon_rating' => '4.2',
            'trustpilot_rating' => '4.3',
            'google_rating' => '4.1',
            'research_observation' => 'Designed for high-output activities where breathability matters. Thin design maintains maximum dexterity. Best for cool conditions rather than extreme cold.',
            'customer_review_summary' => 'Athletes like the minimal bulk for cycling and running, though wish for longer battery life.',
            'featured_review' => 'Great for morning bike rides in cool weather. Barely feel them on, but fingers stay warm enough.'
        ),
        'features' => array(
            'Ultra-thin for maximum feel',
            'Highly breathable to prevent sweat',
            'Reflective elements for visibility',
            'Secure fit doesn\'t shift during activity',
            'Touchscreen compatible throughout'
        ),
        'pros' => array(
            'Minimal bulk maximizes dexterity',
            'Breathable for active use',
            'Lightweight and comfortable'
        ),
        'cons' => array(
            'Not warm enough for extreme cold',
            'Short battery life',
            'Limited weather protection'
        )
    ),
    array(
        'title' => 'Anker Soundcore Life Q30',
        'description' => 'Hybrid Active Noise Cancellation. Hi-Res audio certified. 40-hour playtime in ANC mode. Multiple modes for travel, outdoor, and indoor use.',
        'price' => '$79.99',
        'rating' => '4.6',
        'buy_link' => 'https://amazon.com/anker-soundcore-q30',
        'image' => 'https://picsum.photos/seed/headphone7/400/400',
        'badge_text' => 'Best for Photography & Wildlife',
        'specs' => array(
            'best_for' => 'photography, wildlife observation, outdoor hobbies',
            'heating_levels' => '3 (Low/Med/High)',
            'battery' => '7.4V 2800mAh',
            'battery_life' => '4-8 hours',
            'heating_zones' => 'Fingers and palm',
            'materials' => 'Soft fleece, silent outer shell',
            'waterproofing' => 'Water-resistant treated',
            'sizes' => 'M-XL',
            'amazon_rating' => '4.5',
            'trustpilot_rating' => '4.4',
            'google_rating' => '4.3',
            'research_observation' => 'Quiet materials don\'t rustle during movement, ideal for nature activities. Good finger dexterity for camera controls. Moderate warmth suitable for active pursuits.',
            'customer_review_summary' => 'Photographers and birdwatchers appreciate the quiet operation and ability to use camera controls while wearing.',
            'featured_review' => 'Essential for winter wildlife photography. Quiet shell doesn\'t spook animals and I can operate my camera perfectly.'
        ),
        'features' => array(
            'Silent outer fabric for stealth',
            'Articulated trigger finger',
            'Soft interior won\'t scratch lenses',
            'Neutral colors for nature work',
            'Flip-back mitten option available'
        ),
        'pros' => array(
            'Quiet for wildlife observation',
            'Excellent camera control dexterity',
            'Comfortable for extended wear'
        ),
        'cons' => array(
            'Moderate warmth only',
            'Limited color options',
            'Price slightly high for features'
        )
    ),
    array(
        'title' => 'Beats Studio Pro',
        'description' => 'Personalized spatial audio with dynamic head tracking. Lossless audio via USB-C. Up to 40 hours of battery life. Premium design with enhanced comfort.',
        'price' => '$349.99',
        'rating' => '4.5',
        'buy_link' => 'https://amazon.com/beats-studio-pro',
        'image' => 'https://picsum.photos/seed/headphone8/400/400',
        'badge_text' => 'Best Heated Motorcycle Gloves',
        'specs' => array(
            'best_for' => 'motorcycle riding, winter motorcycling',
            'heating_levels' => '3 (Low/Med/High)',
            'battery' => '7.4V 3200mAh',
            'battery_life' => '3-7 hours',
            'heating_zones' => 'Full hand with knuckle reinforcement',
            'materials' => 'Leather and textile blend',
            'waterproofing' => 'Waterproof membrane',
            'sizes' => 'S-XXL',
            'amazon_rating' => '4.8',
            'trustpilot_rating' => '4.7',
            'google_rating' => '4.6',
            'research_observation' => 'Built specifically for motorcycle use with reinforced impact zones. Heating effective against wind chill. Gauntlet style prevents air entry at highway speeds.',
            'customer_review_summary' => 'Motorcyclists praise the protection and warmth for winter riding, noting excellent grip feel on controls.',
            'featured_review' => 'Game changer for winter motorcycle commuting. Hands stay warm even at highway speeds in freezing temps.'
        ),
        'features' => array(
            'Reinforced knuckle protection',
            'Extended gauntlet cuff',
            'Pre-curved racing fit',
            'Carbon fiber knuckles',
            'Waterproof and windproof',
            'Palm sliders for protection'
        ),
        'pros' => array(
            'Excellent protection for riding',
            'Effective against wind chill',
            'Good control feel'
        ),
        'cons' => array(
            'Very bulky for non-riding use',
            'Expensive',
            'Stiff until broken in'
        )
    ),
    array(
        'title' => 'Audio-Technica ATH-M50xBT2',
        'description' => 'Professional studio sound quality. Multi-point pairing for seamless device switching. Up to 50 hours of battery life. Low latency mode for gaming and videos.',
        'price' => '$199.00',
        'rating' => '4.7',
        'buy_link' => 'https://amazon.com/audio-technica-m50xbt2',
        'image' => 'https://picsum.photos/seed/headphone9/400/400',
        'badge_text' => 'Best for Hunting & Shooting Sports',
        'specs' => array(
            'best_for' => 'hunting, fishing, outdoor shooting sports',
            'heating_levels' => '2 (Med/High)',
            'battery' => '7.4V 2400mAh',
            'battery_life' => '4-8 hours',
            'heating_zones' => 'Fingers and thumb',
            'materials' => 'Camouflage fleece, grip-enhanced palms',
            'waterproofing' => 'Water-resistant',
            'sizes' => 'M-XXL',
            'amazon_rating' => '4.4',
            'trustpilot_rating' => '4.5',
            'google_rating' => '4.3',
            'research_observation' => 'Trigger finger design allows safe firearm handling while maintaining warmth. Quiet operation and camo pattern suited for hunting. Grip enhancement effective for wet conditions.',
            'customer_review_summary' => 'Hunters value the trigger finger access and quiet materials, though some want more aggressive heating.',
            'featured_review' => 'Perfect for deer hunting in November cold. Quiet, warm, and I can still feel my trigger safely.'
        ),
        'features' => array(
            'Trigger finger access',
            'Camouflage patterns available',
            'Quiet movement',
            'Enhanced grip texture',
            'Scent-control treatment',
            'Convertible mitten cover'
        ),
        'pros' => array(
            'Safe trigger finger design',
            'Quiet for hunting',
            'Good grip in wet conditions'
        ),
        'cons' => array(
            'Only two heat settings',
            'Camo limits versatility',
            'Moderate battery life'
        )
    ),
    array(
        'title' => 'JBL LIVE 660NC',
        'description' => 'Adaptive Noise Cancelling technology. JBL Signature Sound. Up to 50 hours of playback time. Voice assistant integration (Alexa, Google).',
        'price' => '$149.95',
        'rating' => '4.4',
        'buy_link' => 'https://amazon.com/jbl-live-660nc',
        'image' => 'https://picsum.photos/seed/headphone10/400/400',
        'badge_text' => 'Best Budget Heated Gloves',
        'specs' => array(
            'best_for' => 'budget-conscious users, occasional winter use',
            'heating_levels' => '3 (Low/Med/High)',
            'battery' => '3.7V 1500mAh',
            'battery_life' => '2-4 hours',
            'heating_zones' => 'Fingertips',
            'materials' => 'Basic fleece, synthetic shell',
            'waterproofing' => 'Water-resistant coating',
            'sizes' => 'M-L',
            'amazon_rating' => '4.0',
            'trustpilot_rating' => '4.1',
            'google_rating' => '3.9',
            'research_observation' => 'Entry-level option provides basic heating for moderate cold. Construction adequate but not premium. Best for occasional use rather than daily reliance.',
            'customer_review_summary' => 'Budget buyers find good value for occasional use, though note shorter battery life and less robust construction.',
            'featured_review' => 'Good starter heated gloves. Not for extreme cold, but work fine for shoveling snow and running errands.'
        ),
        'features' => array(
            'Affordable entry point',
            'Basic three heat settings',
            'Lightweight construction',
            'Easy to use controls',
            'USB rechargeable'
        ),
        'pros' => array(
            'Very affordable',
            'Adequate for moderate cold',
            'Simple operation'
        ),
        'cons' => array(
            'Short battery life',
            'Basic construction',
            'Limited durability'
        )
    )
);

// Create products
$created_products = array();
$product_ids = array();

echo '<h1>Adding Dummy Products...</h1>';

foreach ($dummy_products as $index => $product) {
    $post_data = array(
        'post_title'    => $product['title'],
        'post_content'  => $product['description'],
        'post_status'   => 'publish',
        'post_type'     => 'top10_product',
        'post_author'   => get_current_user_id()
    );

    $product_id = wp_insert_post($post_data);

    if ($product_id) {
        // Add meta data
        update_post_meta($product_id, '_top10_price', $product['price']);
        update_post_meta($product_id, '_top10_rating', $product['rating']);
        update_post_meta($product_id, '_top10_affiliate_link', $product['buy_link']);
        update_post_meta($product_id, '_top10_button_text', 'Check Price');
        
        // Badge text
        if (isset($product['badge_text'])) {
            update_post_meta($product_id, '_top10_badge_text', $product['badge_text']);
        }
        
        // Add specifications as array
        if (isset($product['specs'])) {
            update_post_meta($product_id, '_top10_specs', $product['specs']);
        }
        
        // Features as array
        if (isset($product['features'])) {
            update_post_meta($product_id, '_top10_features', $product['features']);
        }
        
        // Pros as array
        if (isset($product['pros'])) {
            update_post_meta($product_id, '_top10_pros', $product['pros']);
        }
        
        // Cons as array
        if (isset($product['cons'])) {
            update_post_meta($product_id, '_top10_cons', $product['cons']);
        }
        
        // Download and set the featured image
        $image_url = $product['image'];
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        
        $tmp = download_url($image_url);
        if (!is_wp_error($tmp)) {
            $file_array = array(
                'name' => basename($image_url) . '.jpg',
                'tmp_name' => $tmp
            );
            
            $image_id = media_handle_sideload($file_array, $product_id, $product['title']);
            
            if (!is_wp_error($image_id)) {
                set_post_thumbnail($product_id, $image_id);
            } else {
                @unlink($file_array['tmp_name']);
            }
        }

        $product_ids[] = $product_id;
        $created_products[] = $product['title'];
        
        echo '<p style="color: green;">✓ Created: ' . $product['title'] . ' (ID: ' . $product_id . ')</p>';
    } else {
        echo '<p style="color: red;">✗ Failed to create: ' . $product['title'] . '</p>';
    }
}

echo '<hr>';
echo '<h2>Creating Top 10 Page...</h2>';

// Create a Top 10 Page
$page_data = array(
    'post_title'    => 'Best Wireless Headphones 2026',
    'post_content'  => 'We\'ve tested over 50 wireless headphones to bring you this comprehensive guide. Our expert reviewers spent hundreds of hours evaluating sound quality, comfort, battery life, and features to help you find the perfect pair for your needs. Whether you\'re a music enthusiast, frequent traveler, or fitness buff, we\'ve got you covered with detailed reviews and honest recommendations.',
    'post_status'   => 'publish',
    'post_type'     => 'top10_page',
    'post_author'   => get_current_user_id()
);

$page_id = wp_insert_post($page_data);

if ($page_id) {
    // Add products to the page
    update_post_meta($page_id, '_top10_selected_products', $product_ids);
    update_post_meta($page_id, '_top10_layout', 'grid');
    
    // Add Buying Guide (Things to Consider)
    $buying_guide = array(
        array(
            'title' => 'Battery Capacity vs Weight',
            'description' => 'Higher mAh extends runtime but adds bulk affecting comfort during active use. Match capacity to exposure length—3000mAh+ for all-day, 2000mAh for short trips.'
        ),
        array(
            'title' => 'Heating Coverage Patterns',
            'description' => 'Full finger heating prevents numbness better than back-of-hand only, critical below freezing. Check element placement matches your cold spots.'
        ),
        array(
            'title' => 'Fit and Layering Room',
            'description' => 'Too-tight gloves restrict circulation, negating heat benefits. Allow space for liners if layering; measure hand circumference accurately.'
        ),
        array(
            'title' => 'Weather Resistance Level',
            'description' => 'Wet snow demands waterproof membranes over DWR treatments alone. Ski/work gloves need higher ratings than commuter styles.'
        ),
        array(
            'title' => 'Runtime Realism',
            'description' => 'Manufacturer claims assume room temperature; cold reduces capacity 20-30%. Test medium setting matches your typical day length.'
        )
    );
    update_post_meta($page_id, '_top10_buying_guide', $buying_guide);
    
    // Add Research Methodology
    update_post_meta($page_id, '_top10_research_intro', 'Research combined spec analysis, controlled cold tests (-10°C chamber), field simulations (skiing, commuting), and 5,000+ customer reviews across platforms. Methodology weighted warmth (30%), battery (25%), durability/waterproofing (20%), dexterity/comfort (15%), value (10%).');
    
    $research_criteria = array(
        array(
            'criteria' => 'Warmth Output',
            'weight' => '30%',
            'method' => 'Thermal sensor mapping'
        ),
        array(
            'criteria' => 'Battery Runtime',
            'weight' => '25%',
            'method' => 'Timed cold discharge'
        ),
        array(
            'criteria' => 'Waterproofing',
            'weight' => '20%',
            'method' => 'Wet snow submersion'
        ),
        array(
            'criteria' => 'Dexterity',
            'weight' => '15%',
            'method' => 'Timed tasks (zippers/tools)'
        ),
        array(
            'criteria' => 'Value',
            'weight' => '10%',
            'method' => 'Price/performance ratio'
        )
    );
    update_post_meta($page_id, '_top10_research_criteria', $research_criteria);
    
    // Add Research Sources
    update_post_meta($page_id, '_top10_research_sources', 'Data sources included manufacturer specs, independent lab tests, Amazon/Walmart analytics, Reddit/outdoor forums. Expert panel (3 reviewers) validated final rankings for objectivity.');
    
    // Add Conclusion
    update_post_meta($page_id, '_top10_conclusion', 'ThermoMax Pro X9 leads as versatile best heated gloves for extreme cold, while ArcticHeat Ultra Pro handles sub-zero extremes and FrostGuard Summit excels for skiers. These tested recommendations balance warmth, durability, and value across budgets. Choose based on your specific cold exposure and activities for hands that stay functional all winter.');
    
    // Add Hero Section
    update_post_meta($page_id, '_top10_hero_title', 'Best Heated Gloves for Extreme Cold 2026');
    update_post_meta($page_id, '_top10_hero_subtitle', 'Expert-tested heated gloves that actually work in freezing temperatures');
    
    // Add FAQs
    $faqs = array(
        array(
            'question' => 'What makes the best heated gloves for extreme cold different from regular winter gloves?',
            'answer' => 'Heated gloves use battery-powered heating elements to actively warm your hands, while regular winter gloves rely only on insulation. This makes heated gloves far more effective in extreme cold conditions.'
        ),
        array(
            'question' => 'How long should battery heated gloves last on a charge?',
            'answer' => 'Battery life varies by heat setting, but expect 2-3 hours on high, 4-6 hours on medium, and 8+ hours on low settings. Quality gloves like the ThermoMax Pro X9 offer 3-8 hours of runtime.'
        ),
        array(
            'question' => 'Are heated ski gloves worth it for resort skiing?',
            'answer' => 'Yes, especially for all-day skiing or if you have circulation issues. They keep hands warm on chairlifts and during breaks, greatly improving comfort and safety on the slopes.'
        ),
        array(
            'question' => 'Can heated work gloves handle construction sites?',
            'answer' => 'Yes, many models feature durable materials and maintain dexterity for tool use. Look for reinforced palms and waterproofing for outdoor work in winter conditions.'
        ),
        array(
            'question' => 'What battery size do I need for extreme cold?',
            'answer' => 'For extreme cold, aim for 3000mAh or higher batteries. Cold temperatures reduce battery capacity by 20-30%, so larger batteries ensure adequate runtime in harsh conditions.'
        )
    );
    update_post_meta($page_id, '_top10_faqs', $faqs);
    
    // Section settings
    update_post_meta($page_id, '_top10_show_comparison', '1');
    update_post_meta($page_id, '_top10_show_faqs', '1');
    update_post_meta($page_id, '_top10_show_buying_guide', '1');
    
    // Theme color (default blue)
    update_post_meta($page_id, '_top10_theme_color', 'blue');
    
    echo '<p style="color: green;">✓ Created Top 10 Page: Best Wireless Headphones 2026 (ID: ' . $page_id . ')</p>';
    echo '<p style="color: green;">✓ Added buying guide (5 items)</p>';
    echo '<p style="color: green;">✓ Added research methodology with criteria table</p>';
    echo '<p style="color: green;">✓ Added conclusion</p>';
    echo '<p style="color: green;">✓ Added 5 FAQs</p>';
    echo '<p><a href="' . get_permalink($page_id) . '" target="_blank">View Page →</a></p>';
} else {
    echo '<p style="color: red;">✗ Failed to create Top 10 Page</p>';
}

echo '<hr>';
echo '<h2>Summary</h2>';
echo '<p>Products created: ' . count($created_products) . '</p>';
echo '<ul>';
foreach ($created_products as $product_name) {
    echo '<li>' . $product_name . '</li>';
}
echo '</ul>';

echo '<p><strong>Next Steps:</strong></p>';
echo '<ol>';
echo '<li>Go to <a href="' . admin_url('edit.php?post_type=top10_page') . '">Top 10 Pages</a> in WordPress admin</li>';
echo '<li>Edit the page to customize products or create new pages</li>';
echo '<li>View your page on the frontend</li>';
echo '</ol>';

echo '<p style="color: #666; margin-top: 30px;"><em>Note: You can safely delete this file (add-dummy-data.php) after adding the data.</em></p>';
