<?php
/*
Plugin Name: Collapsible Elements
Plugin URI: http://deuced.net/collapsible-elements/ 
Description: Creates the code for multiple collapsible elements in a page or post.
Version: 2.2
Author: ..::DeUCeD::..
Author URI: http://www.deuced.net/
*/
/*

Collapsible Elements adds a button to the code editor which creates the code for having multiple collapsible elements in a page or post. It’s a really simple plugin which can help you making long posts smaller and smarter with style.

*/
/*	Copyright 2008 ..::DeUCeD::..

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/
// Pre-2.6 compatibility
if ( !defined('WP_CONTENT_URL') )
  define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
  define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
### Collapsible Elements
if(function_exists('load_plugin_textdomain'))
        load_plugin_textdomain('collapsible-elements', WP_CONTENT_DIR . '/plugins/collapsible-elements');
/* ---> Check if *** Collapsible Comments plugin *** is activated, if YES USE its javascript, if NO then load the Javascript in the HEAD, that way the two plugins can co-exist */
$the_current_plugins = get_option('active_plugins');
  if (!in_array('collapsible-comments/collapsible-comments.php', $the_current_plugins)) 
  {
/* FUNCTION to put COLLJS in HEAD */
function addHeaderCOLLJS() {
echo '<!-- call Collapsible Elements Javascript in HEAD -->'."\n";
  if (function_exists('wp_enqueue_script')) {
      wp_enqueue_script('collapsible-elements', WP_CONTENT_URL . '/plugins/collapsible-elements/xcelements.js', false, '3.3');
      wp_print_scripts('collapsible-elements');
      }
echo '<!-- done Collapsible Elements Javascript in HEAD -->'."\n";
  }
add_action('wp_head', 'addHeaderCOLLJS');
  }
// ---> OK now go ahead...
// -------------------------
// INSERT XCollapse Button
// -------------------------
// edInsertContent function is included @ quicktags.js
// and was made by Alex King @
// http://alexking.org/blog/2003/06/02/inserting-at-the-cursor-using-javascript
// -------------------------
			function XColl_button()
			{
if(strpos($_SERVER['REQUEST_URI'], 'post.php') || strpos($_SERVER['REQUEST_URI'], 'comment.php') || strpos($_SERVER['REQUEST_URI'], 'page.php') || strpos($_SERVER['REQUEST_URI'], 'post-new.php') || strpos($_SERVER['REQUEST_URI'], 'page-new.php') || strpos($_SERVER['REQUEST_URI'], 'bookmarklet.php'))
				{
			?>
			<script language="JavaScript" type="text/javascript"><!--
			var toolbar = document.getElementById("ed_toolbar");
			<?php
					xcoll_ins_button("XCollapse", "collapsible_element", "x_collapse");
			?>
			function collapsible_element()
			{
			var XC_container = "<?php echo get_option( 'XCEcontainer' ); ?>";
			var XC_style = "<?php echo get_option( 'XCEcontainerstyle' ); ?>";
			var random_xc_id = Math.floor(((Math.random()*1001)*11+1));
			if (XC_container == 'table') {
			var collapsible_code="\n<a href=\"#\" onclick=\"xcollapse('X" + random_xc_id + "');return false;\"> REPLACE WITH LINK ELEMENT HERE... </a>\r<br />\r<" + XC_container + " id=\"X" + random_xc_id + "\" style=\"display: none; " + XC_style + "\">\r<tr>\r<td>\rREPLACE WITH COLLAPSIBLE ELEMENT HERE... \r</td>\r</tr>\r</" + XC_container + ">\r\n";
			}
			else {
			var collapsible_code="\n<a href=\"#\" onclick=\"xcollapse('X" + random_xc_id + "');return false;\"> REPLACE WITH LINK ELEMENT HERE... </a>\r<br />\r<" + XC_container + " id=\"X" + random_xc_id + "\" style=\"display: none; " + XC_style + "\">\rREPLACE WITH COLLAPSIBLE ELEMENT HERE... \r</" + XC_container + ">\r\n";
			}
      edInsertContent(document.getElementById('content'), collapsible_code);
			}
			//--></script>
			<?php
				}
			}
			if(!function_exists('xcoll_ins_button'))
			{
// xcoll_ins_button: Inserts a button into the editor
// -------------------------
// xcoll_ins_button function code was taken from IIMAGE BROWSER plugin @ 
// http://fredfred.net/skriker/index.php/iimage-browser
// -------------------------
				function xcoll_ins_button($caption, $js_onclick, $title = '')
				{
				?>
				if(toolbar)
				{
					var theButton = document.createElement('input');
					theButton.type = 'button';
					theButton.value = '<?php echo $caption; ?>';
					theButton.onclick = <?php echo $js_onclick; ?>;
					theButton.className = 'ed_button';
					theButton.title = "<?php echo $title; ?>";
					theButton.id = "<?php echo "ed_{$caption}"; ?>";
					toolbar.appendChild(theButton);
				}
				<?php
				}
			}
//XCollapse Admin Management Menu
//////////////////////////////////////
function xcollapse_menu() {
	if (function_exists('add_management_page')) {
		add_management_page(__('XCollapse', 'collapsible-elements'), __('XCollapse', 'collapsible-elements'), 9,  'collapsible-elements.php', 'XCMENU');
	}
}
// ADMIN MENU 
function XCMENU() {
// Check Whether User Can Manage Ban Options
if(!current_user_can('manage_options')) {
	die('Access Denied');
}
echo '<div class="wrap">';
echo '<h2>Collapsible Elements options</h2>';
    $XCE_container = 'XCEcontainer';
    $XCE_container_style = 'XCEcontainerstyle';
// Read in existing option value from database
    $XCE_value_container = get_option( 'XCEcontainer' );
    $XCE_value_container_style = get_option( 'XCEcontainerstyle' );
// See if the user has posted us some information
// If they did, this hidden field will be set to 'Y'
if( $_POST[ 'XCMENU_hidden' ] == 'Y' ) {
// Read their posted value
    $XCE_value_container = $_POST[ 'XCEcontainer' ];
    $XCE_value_container_style = $_POST[ 'XCEcontainerstyle' ];
if ( $_POST[ 'XCEcontainer' ] == '') {
     $XCE_value_container = 'table';
}
if ( $_POST[ 'XCEcontainerstyle' ] == '') {
     $XCE_value_container_style = 'background: transparent;';
}
// Save the posted value in the database
    update_option( 'XCEcontainer', $XCE_value_container );
    update_option( 'XCEcontainerstyle', $XCE_value_container_style );
// Put an options updated message on the screen
?>
<div class="updated"><p><strong><?php _e('Options saved.', 'collapsible-elements' ); ?></strong></p></div>
<?php
    }
?>
    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    <input type="hidden" name="XCMENU_hidden" value="Y">
    <p><em><?php _e('Here you can configure the type and the style of the Collapsible Element container. First TICK  between <font color="#D54E21"><strong>TABLE</strong></font>, <font color="#D54E21"><strong>SPAN</strong></font> or <font color="#D54E21"><strong>DIV</strong></font> and then UPDATE the Container CSS style to the one you preffer but <strong>NO quotes</strong> and <strong>NO line breaks</strong> anywhere in the <strong>CSS STYLE Box</strong>. Just the code that will be put INSIDE STYLE quotes. Remove what you do not need and add anything valid.', 'collapsible-elements' ); ?></em></p>
      <p><?php _e('Current container is: ', 'collapsible-elements' ); ?>
      &nbsp;&nbsp; <font color="#D54E21"><strong><?php echo $XCE_value_container; ?></strong></font></p>
      <p><?php _e('Choose a container:  ', 'collapsible-elements' ); ?>
    	&nbsp;&nbsp; <strong>TABLE</strong> <input type="checkbox" name="XCEcontainer" id="XCEcontainer" value="table" />
    	&nbsp;&nbsp; <strong>SPAN</strong> <input type="checkbox" name="XCEcontainer" id="XCEcontainer" value="span" />
    	&nbsp;&nbsp; <strong>DIV</strong> <input type="checkbox" name="XCEcontainer" id="XCEcontainer" value="div" /></p>
      <p><strong><font color="#D54E21"><?php _e('Update CSS Style:', 'collapsible-elements' ); ?></strong></font><br />
      <textarea name="XCEcontainerstyle" id="XCEcontainerstyle" rows="2" cols="80"><?php echo $XCE_value_container_style; ?></textarea></p>
<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'collapsible-elements' ) ?>" />
</p>
</form>
<?php
	echo '<p><div style="border-bottom: #CC0000 1px dashed;"><em>PREVIEW text inside a container with updated style:</em></div></p><p>';
if ( $XCE_value_container == 'table' ) {
	echo '<' . $XCE_value_container . ' style="' . $XCE_value_container_style . '"><tr><td>';
	echo 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.';
	echo '</td></tr></' . $XCE_value_container . '>';
}
else
{
	echo '<' . $XCE_value_container . ' style="' . $XCE_value_container_style . '">';
	echo 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.';
	echo '</' . $XCE_value_container . '>';
}
	echo '</p></div>';
}
//////////////////////////////////////
add_filter('admin_footer', 'XColl_button');
add_action('admin_menu', 'xcollapse_menu');
/* END, now rest in piece! */
?>