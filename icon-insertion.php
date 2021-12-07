<?php
/*
Plugin Name: Icon Insertion
Plugin URI: https://github.com/riequilibrium/icon-insertion
Description: This plugin adds a set of icons in the short description of a product.
Version: 1.0.0
Text Domain: icon-insertion-riequilibrium
Author: Riequilibrium Web Agency
Author URI: https://riequilibrium.com
License: GPLv3
*/

/**
 * Author: Simone Di Paolo
 * Company: Riequilibrium Web Agency
 * Contact: it@riequilibrium.com
 * Date: 2021-11-15
 * Description: Loads existing translations based on installation's language
 */
function icon_insertion_init(){
    $current_user = wp_get_current_user();
    if(!($current_user instanceof WP_User))
        return;
    if(function_exists('get_user_locale'))
        $language = get_user_locale($current_user);
    else
        $language = get_locale();
    load_textdomain("icon-insertion-riequilibrium", plugin_dir_path(__FILE__) . "/languages/" . $language . ".mo");
}
add_action("plugins_loaded", "icon_insertion_init");

/**
 * Author: Simone Di Paolo
 * Company: Riequilibrium Web Agency
 * Contact: it@riequilibrium.com
 * Date: 2021-11-15
 * Description: Creates multiple buttons picked from a certain folder, shown in short description of products
 */
function icon_insertion_riequilibrium_box(){
	$screens = ["product"]; // Select types of screens
	foreach($screens as $screen){
		add_meta_box(
			"icon_insertion_riequilibrium", // Unique ID
			__("Add icons", "icon-insertion-riequilibrium"), // Box title
			"icon_insertion_riequilibrium_html", // Content callback, must be of type callable
			$screen, // Post type
			"normal", // Context
			"default" // Priority
		);
	}
}
add_action("add_meta_boxes", "icon_insertion_riequilibrium_box", 1);

/**
 * Author: Simone Di Paolo
 * Company: Riequilibrium Web Agency
 * Contact: it@riequilibrium.com
 * Date: 2020-11-03
 * Description: HTML callback for the creation of the meta box
 */
function icon_insertion_riequilibrium_html($post){
    ?>
    <script>
        function addIcon(fullPath){
            jQuery("#excerpt-html").click();
            var html = "<img style='margin: 0 0 9px 0;' src='"+fullPath+"' alt='' width='50' height='42' />";
            jQuery("#excerpt").val(html + jQuery("#excerpt").val());
            jQuery("#excerpt-tmce").click();
        }
    </script>
    <?php
    $icon_dir = wp_upload_dir()["basedir"] . "/Icone";
    $icons_raw = array_diff(scandir($icon_dir), array('.', '..'));
    $icons = array();
    foreach($icons_raw as $icon){
        if(!preg_match("/(\d+)x(\d+)/i", $icon)){
            array_push($icons,$icon);
        }
    }
    foreach($icons as $icon){
        $fullPath = wp_upload_dir()["baseurl"]."/Icone/$icon";
        echo "<a style='cursor: pointer;' onclick='addIcon(\"$fullPath\");'><img style='margin: 0 0 9px 0;' src='$fullPath' alt='' width='50' height='42' /></a>";
    }
}