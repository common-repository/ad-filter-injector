<?php
/*
Plugin Name: WBP Ad Filter Injector
Plugin URI:  http://www.webuildplugins.com/
Description: Choose if you want your advertisement or Call To Action to appear on Pages/Posts/Both and how far into the content.
Version:     1.0.1
Author:      We Build Plugins
Author URI:  http://www.webuildplugins.com/
License:     GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Prevent direct access to the script
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


// define constant variables
$wbpAdFilterNameLong = "WeBuildPlugins Ad Filter Injector";
$wbpAdFilterNameShort = "WBP AFJ";
$wbpAdFilterNameAdminPanel = "WBP Ad Filter Injector";
$wbpAdFilterHomeLink = "http://www.webuildplugins.com/";
$wbpAdFilterAdminPageSlug = "wbpAdFilterPluginMenuPageDisplay";


// define public facing styles
function AdFilterLoadStyles() {
	$wbpAdFilterStylesHandle = "wbp_ssms_styles";
	$wbpAdFilterPluginURL = plugin_dir_url( __FILE__ ) . 'css/styles.css';
	wp_enqueue_style( $wbpAdFilterStylesHandle, $wbpAdFilterPluginURL );
}
add_action( 'wp_enqueue_scripts', 'AdFilterLoadStyles' );


// define admin menu plugin page display controls/content
function wbpAdFilterPluginMenuPageDisplay( $active_tab = '' ) {
	global $wbpAdFilterAdminPageSlug;
    global $wbpAdFilterNameLong;

	?>
	<!-- Create a header in the default WordPress "wrap" container -->
	<div class="wrap">	
		<h2><?php echo _e( '<img src="' . plugin_dir_url( __FILE__ ) . 'images/wbp-logo.png" /> ' . $wbpAdFilterNameLong, 'wbp_ssms_options' ); ?></h2>
		
		<?php settings_errors(); ?>
		
		<?php if( isset( $_GET[ 'tab' ] ) ) {
			$active_tab = $_GET[ 'tab' ];
		} else if( $active_tab == 'wbpAdFilterTabTools' ) {
			$active_tab = 'wbpAdFilterTabTools';
		} else if( $active_tab == 'wbpAdFilterTabAbout' ) {
			$active_tab = 'wbpAdFilterTabAbout';
		} else {
			$active_tab = 'wbpAdFilterTabHome';
		}
        ?>
		
		<h2 class="nav-tab-wrapper">
			<a href="?page=<?php echo $wbpAdFilterAdminPageSlug; ?>&tab=wbpAdFilterTabHome" class="nav-tab <?php echo $active_tab == 'wbpAdFilterTabHome' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Settings', 'wbpAdFilterTabHome' ); ?></a>
			<a href="?page=<?php echo $wbpAdFilterAdminPageSlug; ?>&tab=wbpAdFilterTabTools" class="nav-tab <?php echo $active_tab == 'wbpAdFilterTabTools' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Tools', 'wbpAdFilterTabTools' ); ?></a>
			<a href="?page=<?php echo $wbpAdFilterAdminPageSlug; ?>&tab=wbpAdFilterTabAbout" class="nav-tab <?php echo $active_tab == 'wbpAdFilterTabAbout' ? 'nav-tab-active' : ''; ?>"><?php _e( 'About', 'wbpAdFilterTabAbout' ); ?></a>
		</h2>
	
		<?php if( $active_tab == 'wbpAdFilterTabTools' ) {
			wbpAdFilterTabTools();
		} else if( $active_tab == 'wbpAdFilterTabAbout' ) {
			wbpAdFilterTabAbout();
		} else {
			wbpAdFilterTabHome();
		}
        ?>
	</div>
	
<?php
}


// define admin menu plugin page
function wbpAdFilterPluginMenuPage() {
	global $wbpAdFilterNameLong;
	global $wbpAdFilterNameAdminPanel;

    // Enqueue the admin styles
	add_action( 'admin_enqueue_scripts', 'AdFilterLoadStyles' );	
	
	// Add Plugin Page in Admin Panel
	add_plugins_page(
	$wbpAdFilterNameLong,	                // The value used to populate the browser's title bar when the menu page is active
	$wbpAdFilterNameAdminPanel,	                // The text of the menu in the administrator's sidebar
	'administrator',		            	// What roles are able to access the menu
	'wbpAdFilterPluginMenuPageDisplay',     // The ID used to bind submenu items to this menu 
	'wbpAdFilterPluginMenuPageDisplay'		// The callback function used to render this menu
	);
}
add_action( 'admin_menu', 'wbpAdFilterPluginMenuPage' );


// Register default values
function wbpAdFilterDefaultValues() {
	add_option( 'wbp-ad-filter-settings-target-content', 'off' );
	add_option( 'wbp-ad-filter-settings-word-count', '1000' );
	add_option( 'wbp-ad-filter-settings-shortcode', '' );
	add_option( 'wbp-ad-filter-settings-home', 'no' );
}
register_activation_hook( __FILE__, 'wbpAdFilterDefaultValues' );


// register the admin menu plugin page: home settings
function wbpAdFilterRegisterSettingHome() {
    add_settings_section(
        'my_settings_section',	// Section ID
        '',							// Section Title
        'wbpAdFilterSectionCallback',							// Section Callback Function
        'wbpAdFilterTabHome'			// Menu slug from add_options_page()
    );
    
    // Add Field 
    add_settings_field(
        'wbp-ad-filter-settings-target-content', 		// Field ID
        'Where to Inject Ad Shortcode?', 		// Field Title
        'wbpAdFilterTargetContentField',	// Field Callback Function
        'wbpAdFilterTabHome',							// Menu slug from add_options_page()
        'my_settings_section'						// The Section ID this field should appear in.
    );
    
    // Add Field 
    add_settings_field(
        'wbp-ad-filter-settings-word-count', 		// Field ID
        'How Many Characters Before Ad Shortcode Injection?', 		// Field Title
        'wbpAdFilterWordCountField',	// Field Callback Function
        'wbpAdFilterTabHome',							// Menu slug from add_options_page()
        'my_settings_section'						// The Section ID this field should appear in.
    );
    
    // Add Field 
    add_settings_field(
        'wbp-ad-filter-settings-shortcode', 		// Field ID
        'Ad Shortcode:', 		// Field Title
        'wbpAdFilterShortcodeField',	// Field Callback Function
        'wbpAdFilterTabHome',							// Menu slug from add_options_page()
        'my_settings_section'						// The Section ID this field should appear in.
    );
    
    // Add Field 
    add_settings_field(
        'wbp-ad-filter-settings-home', 		// Field ID
        'Display on Home Page?:', 		// Field Title
        'wbpAdFilterHomeField',	// Field Callback Function
        'wbpAdFilterTabHome',							// Menu slug from add_options_page()
        'my_settings_section'						// The Section ID this field should appear in.
    );

    register_setting( 'wbpAdFilterTabHome', 'wbp-ad-filter-settings-target-content' );
    register_setting( 'wbpAdFilterTabHome', 'wbp-ad-filter-settings-word-count' );
    register_setting( 'wbpAdFilterTabHome', 'wbp-ad-filter-settings-shortcode' );
    register_setting( 'wbpAdFilterTabHome', 'wbp-ad-filter-settings-home' );
}
add_action( 'admin_init', 'wbpAdFilterRegisterSettingHome' );


function wbpAdFilterSectionCallback() {
    ?>
    <h2>Settings</h2>
    <?php    
}
// define admin menu plugin page tab: home
function wbpAdFilterTabHome() {    
?>
        <div>
            <form method="post" action="options.php">
	            <?php
				settings_fields( 'wbpAdFilterTabHome' );	
				do_settings_sections( 'wbpAdFilterTabHome' );
				submit_button(); 
				?>
			</form>
        </div>
		<div class="clear"></div>
<?php
}

function wbpAdFilterTargetContentField() {
    ?>    
    <select name="wbp-ad-filter-settings-target-content"><option value="<?php echo get_option('wbp-ad-filter-settings-target-content') ?>" selected><?php echo get_option('wbp-ad-filter-settings-target-content') ?></option><option value="Post">Post</option><option value="Page">Page</option><option value="Both">Both</option><option value="Off">Off</option></select>
    <?php
}

function wbpAdFilterWordCountField() {
    ?>
    <input type="text" name="wbp-ad-filter-settings-word-count" value="<?php echo get_option( 'wbp-ad-filter-settings-word-count' ) ?>" /> (default: at end of Post/Page content)
    <?php
}

function wbpAdFilterShortcodeField() {
    ?>
    <input type="text" name="wbp-ad-filter-settings-shortcode" value="<?php echo get_option( 'wbp-ad-filter-settings-shortcode' ) ?>" />
    <?php
}

function wbpAdFilterHomeField() {
    ?>
    <select name="wbp-ad-filter-settings-home"><option value="<?php echo get_option('wbp-ad-filter-settings-home') ?>" selected><?php echo get_option('wbp-ad-filter-settings-home') ?></option><option value="Yes">Yes</option><option value="No">No</option></select>
    <?php
}


// define admin menu plugin page tab: tools
function wbpAdFilterTabTools() {
	$result = '
		<h2>Tools</h2>
		<div class="ssms-admin-second">
			(Placeholder)
		</div>';

	echo $result;    
}


// define admin menu plugin page tab: about
function wbpAdFilterTabAbout() {
	global $wbpAdFilterNameLong;
	global $wbpAdFilterHomeLink;
	
	?>
	<h2>About</h2>
	<?php
	echo '
	<div class="ssms-top-container">
		<div class="ssms-admin">
			<img src="' . plugin_dir_url( __FILE__ ) . 'images/wbp-logo.png" alt="' . $wbpAdFilterNameLong . '" />
			<strong>' . $wbpAdFilterNameLong . '</strong>
			<br /><br />
			<img src="' . plugin_dir_url( __FILE__ ) . 'images/injector-icon.png" alt="' . $wbpAdFilterNameLong . '" />
			<p>
				Updates, news, and more useful <strong>WordPress</strong> plugins available at <a href="' . $wbpAdFilterHomeLink . '" target="_blank" title="' . $wbpAdFilterNameLong . '">WeBuildPlugins.com</a>
			</p>
			<div><strong>How to use:</strong>
				<br />1. Use any ad rotator plugin shortcode. We tested with the <a href="https://wordpress.org/plugins/ads-by-datafeedrcom/">Ads by Datafeeder</a> plugin.
				<br />2. Choose if you want your advertisement or Call To Action to appear on Pages/Posts/Both.
				<br />3. Choose how far into your post/page that you want your ads to appear.
				<br /><br />Boom! You don\'t have to edit <strong>ANYTHING</strong> else!
				<br /><br />Don\'t forget to rate this plugin so others can get injected too!
			</div>
		</div>
	</div>
	<div class="wbp-ad-filter-clearfix"></div>';
}


// define the core ad filter function
function wbpAdFilterMain( $content ){
    global $wbpAdFilterNameLong;
    $wbpAdFilterWordCount = 110;

    $wbpAdFilterCharacterCount = 1000;
    $wbpAdFilterCharacterCount = get_option( 'wbp-ad-filter-settings-word-count' );

    $wbpAdFilterSearchString = ".";
    $wbpAdFilterContentLength = "";
    
    $wbpAdFilterSampleAd = "";
    $wbpAdFilterSampleAd = " " . get_option( 'wbp-ad-filter-settings-shortcode' ) . " ";
	
	$wbpAdFilterHome = "no";
	$wbpAdFilterHome = strtolower( get_option( 'wbp-ad-filter-settings-home' ) );
    
	$wbpAdFilterTargetContent = "post";
	$wbpAdFilterTargetContent = strtolower( get_option( 'wbp-ad-filter-settings-target-content' ) );

	// Plugin set to off
	if( $wbpAdFilterTargetContent == "off" ) {
		return $content;
	}
	
	// Display on home page or not
	if( is_home() && $wbpAdFilterHome == "no" ) {
		return $content;
	} elseif( is_home() && $wbpAdFilterHome == "yes" && ( $wbpAdFilterTargetContent == "page" || $wbpAdFilterTargetContent == "both") ) {
		return wbpAdFilterModContent( $content, $wbpAdFilterContentLength, $wbpAdFilterSearchString, $wbpAdFilterCharacterCount, $wbpAdFilterSampleAd);
	}

	if( $wbpAdFilterTargetContent == "both"	) {
			return wbpAdFilterModContent( $content, $wbpAdFilterContentLength, $wbpAdFilterSearchString, $wbpAdFilterCharacterCount, $wbpAdFilterSampleAd);
	} elseif ( $wbpAdFilterTargetContent == "post" ) {
		if( is_single() ) {
			return wbpAdFilterModContent( $content, $wbpAdFilterContentLength, $wbpAdFilterSearchString, $wbpAdFilterCharacterCount, $wbpAdFilterSampleAd);
		}    
	} elseif ( $wbpAdFilterTargetContent == "page" ) {
		if( is_page() ) {
			return wbpAdFilterModContent( $content, $wbpAdFilterContentLength, $wbpAdFilterSearchString, $wbpAdFilterCharacterCount, $wbpAdFilterSampleAd);
		}    
	}
   
    return $content;
}
add_filter( 'the_content', 'wbpAdFilterMain' );


function wbpAdFilterModContent( $content, $wbpAdFilterContentLength, $wbpAdFilterSearchString, $wbpAdFilterCharacterCount, $wbpAdFilterSampleAd) {
	$wbpAdFilterPosition = 0;
	// get the string length of the content
	$wbpAdFilterContentLength = strlen( $content );
	
	// insert or append the ad
	if( $wbpAdFilterContentLength > $wbpAdFilterCharacterCount ) {
		// move x character/words/spaces into the content and get the position after the next end of sentence.
		$wbpAdFilterPosition = stripos( $content, $wbpAdFilterSearchString, $wbpAdFilterCharacterCount ) + 1;
	
		$content = substr_replace( $content, $wbpAdFilterSampleAd, $wbpAdFilterPosition, 0 );
	} else {
		$content = substr_replace( $content, $wbpAdFilterSampleAd, $wbpAdFilterContentLength );
	}
	
	return $content;
}









































// End full page scroll
