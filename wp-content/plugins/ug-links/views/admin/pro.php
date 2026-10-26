<?php
/**
 * UglyLinks Pro Upsell View
 *
 * @package UglyLinks\Views\Admin
 */

declare(strict_types=1);

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wrap uglylinks-wrap" id="uglylinks-app" style="margin-top: 32px;">
    
    <div style="text-align: center; max-width: 800px; margin: 0 auto 40px;">
        <span class="uglylinks-logo-icon" aria-hidden="true" style="color: #2563eb; display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; margin-bottom: 16px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="-50 -50 100 100" fill="currentColor"><g><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(0)" /><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(60)" /><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(120)" /><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(180)" /><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(240)" /><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(300)" /><circle cx="0" cy="0" r="12" /></g></svg>
        </span>
        <h1 style="font-size: 32px; font-weight: 300; margin-bottom: 12px;">Upgrade to <span style="font-weight: 700;">UglyLinks Pro</span></h1>
        <p style="font-size: 16px; color: var(--ul-text-muted); line-height: 1.6;">Take your link routing to the next level. UglyLinks Pro unlocks advanced geotargeting, A/B split testing, pixel integration, and deep analytics.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; max-width: 900px; margin: 0 auto;">
        
        <!-- Feature 1 -->
        <div class="uglylinks-stat-card" style="padding: 24px;">
            <div style="color: var(--ul-primary); margin-bottom: 12px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            </div>
            <h3 style="margin: 0 0 8px; font-size: 16px; font-weight: 600;">Geotargeting</h3>
            <p style="margin: 0; font-size: 13px; color: var(--ul-text-muted);">Route users to different destination URLs based on their physical country or state.</p>
        </div>

        <!-- Feature 2 -->
        <div class="uglylinks-stat-card" style="padding: 24px;">
            <div style="color: var(--ul-primary); margin-bottom: 12px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V10"></path><path d="M18 20V4"></path><path d="M6 20v-4"></path></svg>
            </div>
            <h3 style="margin: 0 0 8px; font-size: 16px; font-weight: 600;">Advanced Analytics</h3>
            <p style="margin: 0; font-size: 13px; color: var(--ul-text-muted);">See exactly which devices, browsers, and referrers are driving the most clicks to your links.</p>
        </div>

        <!-- Feature 3 -->
        <div class="uglylinks-stat-card" style="padding: 24px;">
            <div style="color: var(--ul-primary); margin-bottom: 12px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="18" r="3"></circle><circle cx="6" cy="6" r="3"></circle><path d="M13 6h3a2 2 0 0 1 2 2v7"></path><line x1="6" y1="9" x2="6" y2="21"></line></svg>
            </div>
            <h3 style="margin: 0 0 8px; font-size: 16px; font-weight: 600;">A/B Split Testing</h3>
            <p style="margin: 0; font-size: 13px; color: var(--ul-text-muted);">Distribute traffic evenly across multiple destination URLs to see which converts best.</p>
        </div>

        <!-- Feature 4 -->
        <div class="uglylinks-stat-card" style="padding: 24px;">
            <div style="color: var(--ul-primary); margin-bottom: 12px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <h3 style="margin: 0 0 8px; font-size: 16px; font-weight: 600;">Retargeting Pixels</h3>
            <p style="margin: 0; font-size: 13px; color: var(--ul-text-muted);">Fire Facebook, Google, or custom tracking pixels before the redirect executes.</p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 48px;">
        <a href="#" class="uglylinks-btn uglylinks-btn--primary" style="font-size: 16px; padding: 12px 32px; font-weight: 600;">
            Get UglyLinks Pro Now
        </a>
        <p style="font-size: 13px; color: var(--ul-text-muted); margin-top: 16px;">Backed by a 14-day money-back guarantee.</p>
    </div>

</div>
