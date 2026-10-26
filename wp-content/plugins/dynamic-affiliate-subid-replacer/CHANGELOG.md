# Changelog

All notable changes to Dynamic Affiliate SubID Replacer will be documented in this file.

## [1.0.0] - 2026-02-02

### Added
- Initial release of Dynamic Affiliate SubID Replacer
- Automatic SubID replacement for outbound affiliate links
- Support for common affiliate tracking parameters (subid, sub1, sub2, etc.)
- Configurable Slot 1 value (site name or custom override)
- Configurable Slot 2 mode (post slug or full path)
- Advanced targeting options:
  - Target specific CSS class
  - Target links with rel="sponsored" attribute
- WordPress Settings API integration
- Admin settings page under Settings → Affiliate SubID
- Frontend JavaScript implementation using vanilla JS (no jQuery)
- Event delegation for dynamic content compatibility
- Cache-friendly operation (client-side processing)
- Multiple WordPress filters for developer customization:
  - `da_subid_slot1_keys` - Modify Slot 1 parameter keys
  - `da_subid_slot2_keys` - Modify Slot 2 parameter keys
  - `da_subid_slot1_value` - Customize Slot 1 value
  - `da_subid_slot2_value` - Customize Slot 2 value
  - `da_subid_should_rewrite_link` - Conditional link rewriting
- JavaScript API for manual URL rewriting
- Comprehensive documentation (README.md, USAGE.md, readme.txt)
- Support for posts, pages, custom post types, archives, and homepage
- Automatic detection of current page context
- URL encoding and security sanitization
- WordPress coding standards compliance
- Translation-ready (text domain setup)

### Features
- Works with all caching plugins
- Compatible with Classic Editor and Gutenberg
- Supports all page builders
- Mobile-friendly
- No external dependencies
- Privacy-focused (no data collection)
- Minimal performance footprint (~3KB JavaScript)

### Security
- Input sanitization for all settings
- Nonce verification for settings updates
- Escape output in admin pages
- URL validation and encoding
- Current user capability checks

### Compatibility
- WordPress 5.0+
- PHP 7.2+
- All modern browsers

---

## Roadmap (Future Versions)

### Planned for 1.1.0
- [ ] Admin analytics dashboard showing click tracking
- [ ] Export settings functionality
- [ ] Import settings functionality
- [ ] Bulk link tester tool
- [ ] Support for hash fragment preservation
- [ ] Additional filter hooks for edge cases

### Planned for 1.2.0
- [ ] Integration with popular affiliate plugins (ThirstyAffiliates, Pretty Links)
- [ ] Custom post type support for link management
- [ ] A/B testing support for different SubID strategies
- [ ] Real-time preview in settings page

### Planned for 2.0.0
- [ ] Optional server-side tracking (for non-JS users)
- [ ] REST API endpoints
- [ ] Integration with Google Analytics
- [ ] Custom affiliate network presets
- [ ] Multi-language support improvements

---

## Version History

**1.0.0** - February 2, 2026
- Initial public release
