# Woo Category Display Shortcode

## Plugin Information

Display WooCommerce categories via shortcode. Several options are included.

## Installation

1. Unzip the download package
2. Upload `woo-category-display-shortcode` to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

***

## Usage / Features

Use the shortcode `[woo-categories]` to display the WooCommerce categories in posts or pages.

Available parameters:

**show_link**
Display a link to the category archiv
* true - Default

**show_title**
Display the category title/name
* true - Default

**show_desc**
Display the category description (if there is one)
* true - Default

**show_img**
Display the category image (if there is one)
* true - Default

**show_count**
Display the category term count (if there are any terms)
* true - Default

**orderby**
Order of categories
* id
* count
* name - Default
* slug
* term_group - Not fully implemented (avoid using) 
* none

**order**
Order of categories
* ASC - Default
* DESC

**hide_empty**
Hide empty categories
* 1 - Default (i.e. Do not show empty terms) 
* 0

**exclude**
Single ID or comma-separated list of ID´s

**exclude_tree**
Single parent ID or comma-separated list of parent ID´s

**number**
The maximum number of categories to return

**slug**
Returns categories whose "slug" matches this value

**parent**
Get direct children of this category. If 0 is passed, only top-level categories are returned.

**child_of**
Get all descendents of this category (as many levels as are available)
* 0 - Default 

**childless**
Returns categories that have no children if taxonomy is hierarchical, all categories if taxonomy is not hierarchical 
* true
* false - Default


Example `[woo-categories show_desc="false" parent="0" exclude="205,207" number="5"]`


You can override the plugins stylesheet if you put an `woo-cat-display.css` file in your theme-folder.

***

## Changelog

### 1.0.1
* Added new shortcode attribute "show_count"

### 1.0.0
* First push