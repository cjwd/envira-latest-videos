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
