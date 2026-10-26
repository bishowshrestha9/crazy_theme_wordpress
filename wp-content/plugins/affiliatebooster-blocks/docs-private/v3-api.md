# Based on my analysis, here's what needs to be updated for Gutenberg Data APIs v3 compatibility:

## Critical Updates Needed:

  1. Replace withSelect/withDispatch with Hooks

  The plugin uses deprecated higher-order components withSelect and withDispatch instead
   of modern React hooks. Found in:
  - comparison-table/Edit.js
  - ab-coupon4/Edit.js
  - ab-coupon2/Edit.js

  Should be: useSelect and useDispatch hooks from @wordpress/data

  2. Deprecated Components

  - IconButton → Replace with Button component (found in 5+ files)
  - PanelColorSettings → Use individual color controls
  - Toolbar → Replace with ToolbarGroup and ToolbarButton

  3. Outdated Import Pattern

  Currently using:
  const { RichText } = wp.blockEditor;
  Should be:
  import { RichText } from '@wordpress/block-editor';

  4. Class Components

  All 33 block components use class-based React components, which should be converted to
   functional components with hooks for better performance.

  5. Build System

  Using outdated cgb-scripts instead of modern @wordpress/scripts

  6. Package Dependencies

  - @wordpress/compose: "^3.19.1" is very outdated
  - Missing many modern WordPress packages

  7. Experimental APIs

  Using __experimentalLinkControl which is unstable

  The main issue is that the codebase was built with Gutenberg APIs from ~2020 and
  hasn't been updated to use modern patterns. This affects performance, maintainability,
   and could break with future WordPress updates.