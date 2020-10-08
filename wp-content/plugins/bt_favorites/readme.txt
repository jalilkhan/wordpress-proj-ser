=== BT Favorites ===
Plugin Name: BT Favorites
Description: Simple and flexible Favorite Buttons for any post type.
Version: 1.0.0
Author: BoldThemes
Author URI: http://bold-themes.com
Text Domain: bt_favorites
Domain Path: /languages/

Favorites for any post type. Easily add favoriting functionality using the developer-friendly API.


== Installation =========================================

1. Upload the BT Favorites plugin directory to the wp-content/plugins/ directory
2. Activate the plugin through the Plugins menu in WordPress
3. Visit the plugin settings to configure display options
4. Use the template functions, display settings or shortcodes to display the Favorite Buttons and/or User Favorites Lists.



==  Features ============================================ 

* Use with Any Post Type

        Enable or disable favorite functionality per post type while automatically adding a Favorite Button before and/or after the content. 
        Or, use the included functions to display the button anywhere in your template.

* Available for All Users 

        BT Favorites includes an option to save not-logged users favorites by cookie. Logged-In users favorites are saved as user meta.

* Multisite Compatible

        BT Favorites is multisite compatible. User favorites are saved for each site, and may be retrieved and displayed across sites.



== Usage =================================================

* Content Favorite Button ( if enabled, automatically added before and/or after the post content  )

    - The Content Favorite Button can be added automatically to the post content by enabling specific post types and position of the button in the plugin settings.
    - The Content Favorite Button can be added before and/or after the post content by enabling specific positions in the plugin settings.
      If neither position is enabled, the Content Favorite Button will not be added to the content
    - The Content Favorite Button Class and Style parameters are optional and can be set in the plugin settings.

* Favorite Button ( shortcode )

    - The Favorite Button can be added through the content editor using the included shortcode.
    - Favorite Button displays a button that allows users to add/remove posts from favorites.
    - The site id parameter is optional, for use in multisite installations (defaults to current site).
    - The post id parameter is optional. If the post id parameter is omitted, the favorites default to the current post.
    - The user id parameter is optional. If the user id parameter is omitted, the favorites default to the current user.

    Shortcode (prints favorites button):

    [bt_favorites_button site_id="1" post_id="2909" user_id="1" class="bt_bb_favs" style="background-color: red;"]
  
* User Favorites List ( shortcode )

    - User favorites are stored as an array of post ids. 
    - Logged-in users favorites are stored as a custom user meta field, while not-logged users favorites are stored in a cookie. 
    - The site id parameter is optional, for use in multisite installations (defaults to current site).
    - User Favorites List class and style parameters are optional and can be set in the plugin settings. Parameters in shortcode overwrites parameters in the plugin settings.
    - User Favorites List displays a button that allows logged or anonymus users to clear all of their favorites.

    Shortcode (prints an html list of favorites posts titles with links and Clear all favorites button):
    
    [bt_favorites_list site_id="1" class="bt_bb_favs" style="background-color: red;"]

* Simple Favorite Button ( function )

    - Simple Favorite Button can  be added to template files using the included function. 
    - The post id may be left blank for current post. 
    - The site id parameter is optional, for use in multisite installations (defaults to current site).
    - The user id parameter is optional.

    Print function:

    if ( function_exists( 'bt_simple_favorites_button' ) ) { 
       bt_simple_favorites_button( $post_id, $site_id, $user_id )
    }

 