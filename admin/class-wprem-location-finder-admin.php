<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wprem_Location_Finder
 * @subpackage Wprem_Location_Finder/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wprem_Location_Finder
 * @subpackage Wprem_Location_Finder/admin
 * @author     Sergio Cutone <sergio.cutone@yp.ca>
 */
class Wprem_Location_Finder_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->development = true;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wprem_Location_Finder_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wprem_Location_Finder_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wprem-location-finder-admin.css', array(), $this->version, 'all');
        // Add the color picker css file
        wp_enqueue_style('wp-color-picker');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wprem_Location_Finder_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wprem_Location_Finder_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wprem-location-finder-admin.js', array('jquery'), $this->version, false);
    }

    public function menu_settings()
    {
        add_submenu_page(
            'edit.php?post_type=' . WPREM_LOCATIONS_CUSTOM_POST_TYPE,
            'Settings', // The title to be displayed in the browser window for this page.
            'Settings', // The text to be displayed for this menu item
            'manage_options', // Which type of users can see this menu item
            $this->plugin_name, // The unique ID - that is, the slug - for this menu item
            array($this, 'settings_page') // The name of the function to call when rendering this menu's page
        );
    }

    public function settings_page()
    {
        include_once 'partials/wprem-location-finder-admin-display.php';
    }

    public function options_location_callback()
    {
        $is_save = isset($_POST["save"]) ? $_POST["save"] : '';
        if ($is_save == 1) {
            $default_location = $_POST["location"];
            print_r($_POST["location"]);
            update_option('wp_location_options', $default_location);
        }
        $location_options = get_option('wp_location_options');
        ?>
		<h1>Location Settings</h1>
		<p></p>
		<hr/>
		<h3>Set Main Location</h3>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<input type="hidden" name="save" value="1" />
			<select name="location[wp_default_location]">
				<?php
$args = array('post_type' => WPREM_LOCATIONS_CUSTOM_POST_TYPE, 'orderby' => 'post_title', 'order' => 'ASC', 'posts_per_page' => 999999999);
        $locations = get_posts($args);

        foreach ($locations as $location):
            setup_postdata($location);
            echo '<option value="' . $location->ID . '" ' . selected($location_options['wp_default_location'], $location->ID, false) . '>' . count($locations) . $location->post_title . '</option>';
        endforeach;
        ?>
			</select>
			<p>
				<input type="submit" value="Save Location Settings" class="button button-primary button-large" />
			</p>
		</form>
		<?php
wp_reset_postdata();
    }

    public function content_types()
    {

        $labels = array(
            'name' => _x('Locations', 'Post type general name', 'textdomain'),
            'singular_name' => _x('Location', 'Post type singular name', 'textdomain'),
            'menu_name' => _x('Locations', 'Admin Menu text', 'textdomain'),
            'name_admin_bar' => _x('Location', 'Add New on Toolbar', 'textdomain'),
            'add_new' => __('Add New', 'textdomain'),
            'add_new_item' => __('Add New Location', 'textdomain'),
            'new_item' => __('New Location', 'textdomain'),
            'edit_item' => __('Edit Location', 'textdomain'),
            'view_item' => __('View Location', 'textdomain'),
            'all_items' => __('All Locations', 'textdomain'),
            'search_items' => __('Search Locations', 'textdomain'),
            'parent_item_colon' => __('Parent Locations:', 'textdomain'),
            'not_found' => __('No Locations found.', 'textdomain'),
            'not_found_in_trash' => __('No Locations found in Trash.', 'textdomain'),
            'featured_image' => _x('Location Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain'),
            'set_featured_image' => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain'),
            'use_featured_image' => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain'),
            'archives' => _x('Location archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain'),
            'insert_into_item' => _x('Insert into Location', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain'),
            'uploaded_to_this_item' => _x('Uploaded to this Location', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain'),
            'filter_items_list' => _x('Filter Locations list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain'),
            'items_list_navigation' => _x('Locations list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain'),
            'items_list' => _x('Locations list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain'),
        );

        $exludefromsearch = (esc_attr(get_option('wprem_searchable_wprem-location-finder')) === "1") ? false : true;
        $args = array('exclude_from_search' => $exludefromsearch, 'show_in_rest' => true, 'rewrite' => array("slug" => "locations", "with_front" => false), "menu_icon" => "dashicons-location-alt", 'labels' => $labels, "has_archive" => false, 'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'));
        $locations = register_cuztom_post_type(WPREM_LOCATIONS_CUSTOM_POST_TYPE, $args);
        //add_submenu_page('edit.php?post_type='.WPREM_LOCATIONS_CUSTOM_POST_TYPE, 'Settings', 'Location Settings', 'manage_options', 'wprem-locations-options', array(&$this, 'options_location_callback'));
        $category = register_cuztom_taxonomy(
            'wprem_locations_category',
            WPREM_LOCATIONS_CUSTOM_POST_TYPE,
            array(
                'labels' => array('name' => __('Categories', 'cuztom'), 'menu_name' => __('Categories', 'cuztom')),
                'show_admin_column' => true,
                'admin_column_sortable' => true,
                'admin_column_filter' => true,
                'show_in_rest' => true,
            )
        );
        $category = register_cuztom_term_meta('data', 'wprem_locations_category',
            array(
                'fields' => array(
                    array(
                        'id' => '_data_image',
                        'type' => 'image',
                        'label' => 'Marker',
                    ),
                ),
            )
        );

        $box = register_cuztom_meta_box('data', WPREM_LOCATIONS_CUSTOM_POST_TYPE,
            array(
                'title' => 'Location Information',
                'fields' => array(
                    array(
                        'id' => '_data_tabs',
                        'type' => 'tabs',
                        'panels' => array(
                            array(
                                'id' => '_data_tabs_panel_1', 'title' => 'Main Information',
                                'fields' => array(
                                    array(
                                        'id' => '_data_hidedetails', 'label' => 'Hide See Details Link From Map', 'type' => 'checkbox',
                                    ), array(
                                        'id' => '_data_title', 'label' => 'Title', 'type' => 'text', 'show_admin_column' => true, 'admin_column_sortable' => true, 'admin_column_filter' => true,
                                    ), array(
                                        'id' => '_data_email_address', 'label' => 'Email Address', 'type' => 'text', 'show_admin_column' => true, 'admin_column_sortable' => true, 'admin_column_filter' => true,
                                    ), array(
                                        'id' => '_data_telephone', 'label' => 'Telephone', 'type' => 'text',
                                    ), array(
                                        'id' => '_data_tollfree', 'label' => 'Toll-Free Telephone', 'type' => 'text',
                                    ), array(
                                        'id' => '_data_fax', 'label' => 'Fax Number', 'type' => 'text',
                                    ), array(
                                        'id' => '_data_file', 'type' => 'file', 'label' => 'File',
                                    ), array(
                                        'id' => '_data_filelabel', 'label' => 'File Label', 'type' => 'text',
                                    ),
                                ),
                            ),
                            array(
                                'id' => '_data_tabs_panel_3',
                                'title' => 'Address',
                                'fields' => array(
                                    array(
                                        'id' => '_data_address', 'label' => 'Address', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_unit', 'label' => 'Unit', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_city', 'label' => 'City', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_prostate', 'label' => 'Province / State', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_country', 'label' => 'Country', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_postalcode', 'label' => 'Postal Code', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_lat', 'label' => 'Latitude', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_lng', 'label' => 'Longitude', 'type' => 'text',
                                    ),
                                ),
                            ),
                            array(
                                'id' => '_data_tabs_panel_4',
                                'title' => 'Hours of Operation',
                                'fields' => array(
                                    array(
                                        'id' => '_data_text_hrs_mon', 'label' => 'Monday', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_text_hrs_tue', 'label' => 'Tuesday', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_text_hrs_wed', 'label' => 'Wednesday', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_text_hrs_thu', 'label' => 'Thursday', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_text_hrs_fri', 'label' => 'Friday', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_text_hrs_sat', 'label' => 'Saturday', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_text_hrs_sun', 'label' => 'Sunday', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_text_hrs_holidays', 'label' => 'Holiday Message', 'type' => 'textarea',
                                    ),
                                    array(
                                        'id' => '_data_text_custom_hours', 'label' => 'Custom Hours Message', 'type' => 'wysiwyg',
                                    ),
                                ),
                            ),
                            array(
                                'id' => '_data_tabs_panel_2',
                                'title' => 'Social Media',
                                'fields' => array(
                                    array(
                                        'id' => '_data_text_linkedin', 'label' => 'LinkedIn', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_text_facebook', 'label' => 'FaceBook', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_text_twitter', 'label' => 'Twitter', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_text_instagram', 'label' => 'Instagram', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_text_youtube', 'label' => 'YouTube', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_text_google', 'label' => 'Google', 'type' => 'text',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'side',
            'low'
        );
    }
}