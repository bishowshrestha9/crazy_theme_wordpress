# Duplicate Post Slug Detector & CSV Exporter

A standalone WordPress plugin to scan all posts, pages, and custom post types, detect duplicate postings by comparing post slugs (e.g. `slug` vs `slug-2`, `slug-3`), and export the list of duplicate posts to CSV format.

## Features
- **Smart Base Slug Matching**: Strips WordPress auto-incremented numbers (`-2`, `-3`, etc.) to group duplicate postings under their original base slug.
- **Multiple Detection Modes**:
  1. **Base Slug Match** (detects `my-post`, `my-post-2`, `my-post-3`).
  2. **Exact Slug Match** (detects exact identical slugs across different post types or post statuses).
  3. **Exact Post Title Match** (detects identical post titles).
- **Comprehensive CSV Export**: Export duplicate group names, occurrences, post IDs, post titles, exact slugs, URLs, edit links, post types, post statuses, and dates.
- **Interactive Dashboard**: Metric summary cards, filters (post types, statuses), real-time search, animated progress bar, and expandable post details.

## Installation
1. Upload the `duplicate-post-detector` folder (or install `duplicate-post-detector.zip`) into `/wp-content/plugins/`.
2. Activate **Duplicate Post Slug Detector & CSV Exporter** in WordPress Admin > Plugins.
3. Click **Duplicate Posts** in the left menu to start scanning and export your CSV.
