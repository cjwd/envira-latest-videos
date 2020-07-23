# Description

Display the most recent videos added to a envira gallery or album via shortcodes

# Requirements

[Envira Gallery](https://enviragallery.com)
The albums shortcode will only work if the Albums add-on is installed and activated

# Usage

This plugin provides 2 shortcodes:

`[elvs-gallery]`

`[elvs-album]`

## Parameters (Shortcode Attributes)

## gallery_id/album_id

The envira gallery or album id. This is required. Default is an empty string.

`[elvs-gallery gallery_id="214"]`

## num_posts

How many gallery items to display. Default: 6

`[elvs-gallery gallery_id="214" num_posts="8"]`

## sort_order

Whether to sort the gallery items ascending or descending. The items are sorted by date. Default: DESC.

`[elvs-gallery gallery_id="214" num_posts="8" sort_order="ASC"]`

# Changelog

## [1.1.0] 2020-07-22

### Added

- Explicit width and height for iframe
- Move lightbox html output to a function

### Changed

- Class name for gallery container is elvs
- Iframe markup no longer has width and height defined. This is defined in the CSS

### Fixed

- Album data variable now references the right meta key for getting album data. Removed \_eg_album_data variable.
- Conditional statements testing for valid gallery or album ids now has the proper condition parameters
- Elvs gallery shortcode now call the correct function

## [1.1.1] 2020-07-22

### Added

- Constant for plugin version

### Changed

- Styles and Javascript now use the plugin version constant for the versioning

### Fixed

- The albums shortcode now skips deleted gallery items/attachment posts
