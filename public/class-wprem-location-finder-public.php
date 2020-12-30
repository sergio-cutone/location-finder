<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wprem_Location_Finder
 * @subpackage Wprem_Location_Finder/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wprem_Location_Finder
 * @subpackage Wprem_Location_Finder/public
 * @author     Sergio Cutone <sergio.cutone@yp.ca>
 */
class Wprem_Location_Finder_Public
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wprem-location-finder-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wprem-location-finder-public.js', array('jquery'), $this->version, false);
    }

    public function location_rest()
    {
        register_rest_field(WPREM_LOCATIONS_CUSTOM_POST_TYPE, 'location_data', array(
            'get_callback' => function ($location_arr) {
                $custom_fields = get_post_custom($location_arr['id']);
                $cus = array(array());

                foreach ($custom_fields as $key => $value) {
                    if ($key == '_data_file') {
                        $cus[$key] = wp_get_attachment_url($value[0]);
                    } else {
                        $cus[$key] = $value;
                    }
                }
                return $cus;
            })
        );
    }

    public function single_location_content($content)
    {
        ob_start();

        global $post;
        $location_options = get_option('wp_location_options');
        if (is_singular(WPREM_LOCATIONS_CUSTOM_POST_TYPE) && $location_options['wp_locations_default_content']) {
            $default_location = get_post_custom(get_the_ID());
            $details = array();

            $details["title"] = (isset($default_location["_data_title"][0]) && $default_location["_data_title"][0]) ? '<div class="wprem-title">' . $default_location["_data_title"][0] . '</div>' : '';
            $details["email"] = (isset($default_location["_data_email_address"][0]) && $default_location["_data_email_address"][0]) ? '<div class="wprem-email"><span class="wprem-label">Email:</span> <a href="mailto:' . $default_location["_data_email_address"][0] . '" class="wprem-link">' . $default_location["_data_email_address"][0] . '</a></div>' : '';
            $details["tel"] = (isset($default_location["_data_telephone"][0]) && $default_location["_data_telephone"][0]) ? '<div class="wprem-telephone"><span class="wprem-label">Telephone:</span> <a href="tel:' . preg_replace("/[^0-9]/", "", $default_location['_data_telephone'][0]) . '" class="wprem-link">' . $default_location["_data_telephone"][0] . '</a></div>' : '';
            $details["tollfree"] = (isset($default_location["_data_tollfree"][0]) && $default_location["_data_tollfree"][0]) ? '<div class="wprem-tollfree"><span class="wprem-label">Toll-free:</span> <a href="tel:' . preg_replace("/[^0-9]/", "", $default_location['_data_tollfree'][0]) . '" class="wprem-link">' . $default_location["_data_tollfree"][0] . '</a></div>' : '';
            $details["fax"] = (isset($default_location["_data_fax"][0]) && $default_location["_data_fax"][0]) ? '<div class="wprem-fax"><span class="wprem-label">Fax:</span> <a href="tel:' . preg_replace("/[^0-9]/", "", $default_location['_data_fax'][0]) . '" class="wprem-link">' . $default_location["_data_fax"][0] . '</a></div>' : '';
            $details["address"] = (isset($default_location["_data_address"][0]) && $default_location["_data_address"][0]) ? '<span>' . $default_location["_data_address"][0] . '</span>' : '';
            $details["unit"] = (isset($default_location["_data_unit"][0]) && $default_location["_data_unit"][0]) ? '<div><span>' . $default_location["_data_unit"][0] . '</span></div>' : '';
            $details["city"] = (isset($default_location["_data_city"][0]) && $default_location["_data_city"][0]) ? '<span>' . $default_location["_data_city"][0] . '</span>' : '';
            $details["prostate"] = (isset($default_location["_data_prostate"][0]) && $default_location["_data_prostate"][0]) ? '<span>' . $default_location["_data_prostate"][0] . '</span>' : '';
            $details["country"] = (isset($default_location["_data_country"][0]) && $default_location["_data_country"][0]) ? '<span>' . $default_location["_data_country"][0] . '</span>' : '';
            $details["postal"] = (isset($default_location["_data_postalcode"][0]) && $default_location["_data_postalcode"][0]) ? '<div>' . $default_location["_data_postalcode"][0] . '</div>' : '';
            $details["filelabel"] = (isset($default_location["_data_filelabel"][0]) && $default_location["_data_filelabel"][0]) ? $default_location["_data_filelabel"][0] : 'Flyer';
            $file = (isset($default_location["_data_file"][0]) && $default_location["_data_file"][0]) ? wp_get_attachment_url($default_location["_data_file"][0], "full") : '';
            $details["file"] = (isset($default_location["_data_file"][0]) && $default_location["_data_file"][0]) ? '<div class="wprem-file"><span class="wprem-label"><a href="' . $pdf[] = $file . '" target="_blank">' . $details["filelabel"] . '</a></span></div>' : '';

            $city_province_country = '<div>' . $details["city"] . ', ' . $details["prostate"] . ' ' . $details["country"] . '</div>';

            // - - - - - Hours of Operation
            $hours = $days = '';
            $days .= (isset($default_location["_data_text_hrs_mon"][0]) && $default_location["_data_text_hrs_mon"][0]) ? '<div><strong>Monday:</strong> ' . $default_location["_data_text_hrs_mon"][0] . '</div>' : '';
            $days .= (isset($default_location["_data_text_hrs_tue"][0]) && $default_location["_data_text_hrs_tue"][0]) ? '<div><strong>Tuesday:</strong> ' . $default_location["_data_text_hrs_tue"][0] . '</div>' : '';
            $days .= (isset($default_location["_data_text_hrs_wed"][0]) && $default_location["_data_text_hrs_wed"][0]) ? '<div><strong>Wednesday:</strong> ' . $default_location["_data_text_hrs_wed"][0] . '</div>' : '';
            $days .= (isset($default_location["_data_text_hrs_thu"][0]) && $default_location["_data_text_hrs_thu"][0]) ? '<div><strong>Thursday:</strong> ' . $default_location["_data_text_hrs_thu"][0] . '</div>' : '';
            $days .= (isset($default_location["_data_text_hrs_fri"][0]) && $default_location["_data_text_hrs_fri"][0]) ? '<div><strong>Friday:</strong> ' . $default_location["_data_text_hrs_fri"][0] . '</div>' : '';
            $days .= (isset($default_location["_data_text_hrs_sat"][0]) && $default_location["_data_text_hrs_sat"][0]) ? '<div><strong>Saturday:</strong> ' . $default_location["_data_text_hrs_sat"][0] . '</div>' : '';
            $days .= (isset($default_location["_data_text_hrs_sun"][0]) && $default_location["_data_text_hrs_sun"][0]) ? '<div><strong>Sunday:</strong> ' . $default_location["_data_text_hrs_sun"][0] . '</div>' : '';
            $days .= (isset($default_location["_data_text_hrs_holidays"][0]) && $default_location["_data_text_hrs_holidays"][0]) ? '<div style="margin-top:15px"><strong>Holiday Schedule:</strong><br/>' . $default_location["_data_text_hrs_holidays"][0] . '</div>' : '';
            // - - - - -

            $location_info = $details["title"] . $details["tel"] . $details["tollfree"] . $details["fax"] . $details["email"] . '<div class="wprem-address">' . $details["address"] . $city_province_country . $details["postal"] . '</div>' . $details["file"];

            $out = '<div class="wprem-extras">';
            if ($days) {
                $hours = '<p><strong>Hours of Operation</strong></p>' . $days;
                $out .= '<div class="wprem_h1">Location Details</div><div class="row"><div class="col-md-6">' . $location_info . '</div><div class="col-md-6">' . $hours . '</div></div>';
            } else {
                $out .= '<div class="wprem_h1">Location Details</div>' . $location_info;
            }

            $content = $content . $out;

            // - - - - - Output Staff Members
            if (defined('WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE')) {
                $service_id = $post->ID;
                $args = array('post_status' => 'published', 'post_type' => WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE);
                $staff = '';
                $staff_query = new WP_Query($args);
                if ($staff_query->have_posts()) {
                    while ($staff_query->have_posts()) {
                        $staff_query->the_post();
                        $staff_members = get_post_custom(get_the_ID());
                        // - - - - - Services
                        if (isset($staff_members['_data_post_locations'][0]) && $staff_members['_data_post_locations'][0]) {
                            $allservices = maybe_unserialize($staff_members['_data_post_locations'][0]);
                            foreach ($allservices as $key => $val) {
                                if ($val == $service_id) {
                                    $staff .= do_shortcode('[wp_staff id=' . get_the_ID() . ']');
                                }
                            }
                        }
                        // - - - - - //
                    }
                }
                wp_reset_postdata();
                if ($staff) {
                    $content = $content . '<div class="wprem-staff-container"><div class="wprem-title wprem_h1">' . $location_options['wp_title_staff_members'] . '</div></div>' . $staff;
                }
            }
            // - - - - - end Output Staff Members//

            // - - - - - Output Promotions
            if (defined('WPREM_PROMOTIONS_CUSTOM_POST_TYPE')) {
                $args = array(
                    'post_status' => 'published',
                    'post_type' => WPREM_PROMOTIONS_CUSTOM_POST_TYPE,
                    'order' => 'ASC',
                    'meta_query' => array(
                        array(
                            'key' => '_data_expiry_date',
                            'value' => time(),
                            'compare' => '>',
                        ),
                    ),
                );
                $promotion = '';
                $promo_query = new WP_Query($args);
                if ($promo_query->have_posts()) {
                    while ($promo_query->have_posts()) {
                        $promo_query->the_post();
                        $promotions = get_post_custom(get_the_ID());
                        // - - - - - Services
                        if (isset($promotions['_data_post_locations'][0]) && $promotions['_data_post_locations'][0]) {
                            $allservices = maybe_unserialize($promotions['_data_post_locations'][0]);
                            foreach ($allservices as $key => $val) {
                                if ($val == $service_id) {
                                    $promotion .= do_shortcode('[wp_promos id=' . get_the_ID() . ']');
                                }
                            }
                        }
                        // - - - - - //
                    }
                }
                wp_reset_postdata();
                if ($promotion) {
                    $content = $content . '<div class="wprem-promotions-container"><div class="wprem-title wprem_h1">' . $location_options['wp_title_promotions'] . '</div></div>' . $promotion;
                }
            }
            // - - - - - end Output Promotions //

            // - - - - - Output Services
            if (defined('WPREM_SERVICES_CUSTOM_POST_TYPE')) {
                $args = array('post_status' => 'published', 'post_type' => WPREM_SERVICES_CUSTOM_POST_TYPE);
                $service = '';
                $services_query = new WP_Query($args);
                if ($services_query->have_posts()) {
                    while ($services_query->have_posts()) {
                        $services_query->the_post();
                        $services = get_post_custom(get_the_ID());
                        // - - - - - Services
                        if (isset($services['_data_post_locations'][0]) && $services['_data_post_locations'][0]) {
                            $allservices = maybe_unserialize($services['_data_post_locations'][0]);
                            foreach ($allservices as $key => $val) {
                                if ($val == $service_id) {
                                    $serv = get_post($val);
                                    $service .= '<a href="' . get_the_permalink() . '">' . get_the_title() . '</a>';
                                }
                            }
                        }
                        // - - - - - //
                    }
                }
                wp_reset_postdata();
                if ($service) {
                    $content = $content . '<div class="wprem-business-services-container"><div class="wprem-title wprem_h1">' . $location_options['wp_title_business_services'] . '</div></div>' . $service;
                }
            }
            // - - - - - end Output Services //

            $content = $content . '</div>';
        }

        echo $content;

        $content = ob_get_clean();
        return $content;
    }

    public function location_shortcode($atts)
    {
        ob_start();

        // #-#-#-#-# SHORTCODES
        extract(shortcode_atts(array(
            'map' => 'lg', // map: (lg,md,sm) - width
            'cat' => 0, // cat: bool - show category in location list
            'filter' => 0, // fliter: bool - show category filter
            'orderby' => "date", // orderby: sort by (title,date)
            'order' => "desc", // order: sort order (asc,desc)
            'titlelink' => 0, // titlelink: bool - link title to singular page or default pin
            'seedetails' => 1, // seedetails: bool - add 'See Details' link to info-window
            'seedetailsmain' => 0, // seedetailsmain: bool - add 'See Details' link to main listings
            'single' => 0, // single: bool - true: show only map and all pins, false: show 2 col with locations details
            'singleid' => 0, // ID # single location to show
            'height' => 'true', // height: px - height of map
            'bw' => 0, // bw: bool - true: black and white map tiles, false: colour
            'columns' => 0, // columns: bool - false; place locations on top (true) or left (true) of map
            'columnsize' => '3', // how many columns
            'title' => 1, // Show title - bool
            'address' => 1, // Show address - bool
            'unit' => 1, // Show unit - bool
            'city' => 1, // Show city - bool
            'prostate' => 1, // Show city - bool
            'country' => 1, // Show country - bool
            'postal' => 1, // Show postal - bool
            'tel' => 1, // Show telephone - bool
            'toll' => 1, // Show toll free - bool
            'fax' => 1, // Show fax - bool
            'email' => 1, // Show email - bool
            'scroll' => 0, // Pin click scroll offset to account for sticky header
            'region' => 0, // Search via first 3 postal code characters
            'defaultfirst' => 0, // Make the default location show up first on init
            'pintel' => 1,
            'pinfax' => 1,
            'pintoll' => 1,
            'pinemail' => 1,
        ), $atts));

        $details = json_encode(array('title' => $title, 'address' => $address, 'unit' => $unit, 'city' => $city, 'prostate' => $prostate, 'country' => $country, 'postal' => $postal, 'email' => $email, 'tel' => $tel, 'toll' => $toll, 'fax' => $fax));

        // md, md-push, lg, lg-push
        $mc = array(8, 4, 9, 3);
        if ($map === 'md') {
            $mc = [8, 4, 8, 4];
        } elseif ($map === 'sm') {
            $mc = [8, 4, 7, 5];
        }
        $left = 'col-md-' . $mc[0] . ' col-md-push-' . $mc[1] . ' col-lg-' . $mc[2] . ' col-lg-push-' . $mc[3];
        $right = 'col-md-' . $mc[1] . ' col-md-pull-' . $mc[0] . ' col-lg-' . $mc[3] . ' col-lg-pull-' . $mc[2];

        global $post;
        // WPML SETTINGS fr / en
        $text = array();
        $text['search'] = 'Search';
        $text['reset'] = 'Reset';
        $text['placeholder1'] = 'Enter first 3 characters in postal code and click search';
        $text['placeholder2'] = 'Enter full address or postal code and click search';
        $text['formerror'] = 'Please enter full address or postal code.';
        $text['baseurl'] = home_url();
        if (defined('ICL_LANGUAGE_CODE')) {
            $text['baseurl'] = apply_filters('wpml_home_url', get_option('home'));
            if (ICL_LANGUAGE_CODE === 'fr') {
                $text['search'] = 'Chercher';
                $text['reset'] = 'Effacez';
                $text['placeholder1'] = "Entrez les 3 premiers caractères du code postal et cliquez sur Rechercher.";
                $text['placeholder2'] = "Entrez l'adresse complète ou le code postal et cliquez sur Rechercher";
                $text['formerror'] = "Veuillez entrer l'adresse complète ou le code postal.";
            }
        }
        // end - WPML SETTINGS

        $args = array('post_type' => WPREM_LOCATIONS_CUSTOM_POST_TYPE, 'order' => 'ASC');
        $location_query = new WP_Query($args);
        $location_options = get_option('wp_location_options');
        $default_location = get_post_custom($location_options["wp_default_location"]);
        $show_radius = isset($location_options["wp_locations_radius_show"]) ? $location_options["wp_locations_radius_show"] : 'y';

        $postal_code = isset($_GET["pc"]) ? $_GET["pc"] : false;

        if ($default_location["_data_address"][0] && !$postal_code) {
            $default_address = $default_location["_data_address"][0] . ' ' . $default_location["_data_city"][0] . ' ' . $default_location["_data_prostate"][0] . ' ' . $default_location["_data_country"][0] . ' ' . $default_location["_data_postalcode"][0];
        } else {
            $default_address = $postal_code;
        }

        $categories = get_terms('wprem_locations_category');
        $categoryfilter = false;
        echo '<div id="marker-cats">';
        if ($filter) {
            $categoryfilter = '<div class="col-xs-3 col-md-2 text-right"><div class="dropdown" style="margin-bottom:15px" title="Category Filter">
            <button class="btn btn-default dropdown-toggle form-control" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="hidden-xs fa  fa-filter"></span> Filter</button>
            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">';
        }
        foreach ($categories as $category) {
            $term_vals = get_term_meta($category->term_id);
            $image = isset($term_vals['_data_image']) ? wp_get_attachment_url($term_vals['_data_image'][0]) : '';
            echo '<span data-id="' . $category->term_id . '"" data-name="' . $category->name . '" class="hidden">' . $image . '</span>';
            if ($filter) {
                $categoryfilter .= '<li class="wprem-location-filter-cat" data-name="' . $category->name . '"><img src="' . $image . '" style="width:25px"/>' . $category->name . '</li> ';
            }
        }
        if ($filter) {
            $categoryfilter .= "</ul></div></div>";
        }
        echo "</div>";

        $collg = 'col-md-6';
        $colmd = 'col-xs-4';
        $colsm = 'col-xs-4';
        if ($categoryfilter) {
            $collg = 'col-md-4';
            $colsm = $colmd = 'col-xs-3';
        }

        $columns_row = ''; // Null top row locations list
        $left_column = '<div class="' . $right . ' wprem-block1" style="height: 500px"><div id="wprem-locations"></div></div>'; // Left column locations list

        if ($columns) { // If you want to show the locations list at the top
            $left = 'col-md-12'; // Make right column for map full width
            $left_column = ''; // Null left column locations list
            $columns_row = '<div class="row"><div id="wprem-locations" style="display: flex; flex-wrap: wrap;"></div></div>'; // top row locations list
        }

        if (!$single) {
            if ($region) {
                $searchinput = '
                <div class="col-xs-12 col-sm-6">
                    <input type="text" id="addressInput" size="15" value="' . $postal_code . '" class="form-control" placeholder="' . $text['placeholder1'] . '" maxlength="3"/>
                    <div class="wprem-locations-error">' . $text['formerror'] . '</div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <button type="submit" id="searchButton" class="form-control wp_location_searchbutton" title="Find Closest Locations"><span class="hidden-xs fa fa-search"></span> ' . $text['search'] . '</button>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <button type="submit" id="refreshButton" class="form-control wp_location_refreshbutton" title="Refresh Map"><span class="hidden-xs fa fa-refresh"></span> ' . $text['reset'] . '</button>
                </div>
                ';
            } else {
                $searchinput = '
                    <div class="' . $collg . '">
                        <input type="text" id="addressInput" size="15" value="' . $postal_code . '" class="form-control" placeholder="' . $text['placeholder2'] . '" />
                        <div class="wprem-locations-error">' . $text['formerror'] . '</div>
                    </div>
                    <div class="' . $colmd . ' col-md-2">
                        <select id="radiusSelect" label="Radius" class="form-control">
                        <option value="50">50 kms</option>
                        <option value="30">30 kms</option>
                        <option value="20">20 kms</option>
                        <option value="10" selected>10 kms</option>
                        </select>
                        <input type="hidden" id="defaultLocation" value="' . $default_address . '"/>
                        <input type="hidden" id="defaultMarker" value="' . $location_options["wp_locations_marker"] . '"/>
                        <input type="hidden" id="defaultMapStyle" value="' . $location_options["wp_locations_map_style"] . '"/>
                    </div>
                    <div class="' . $colsm . ' col-md-2 text-right">
                        <button type="submit" id="searchButton" class="form-control wp_location_searchbutton" title="Find Closest Locations"><span class="hidden-xs fa fa-search"></span> ' . $text['search'] . '</button>
                    </div>
                    <div class="' . $colsm . ' col-md-2 text-right">
                        <button type="submit" id="refreshButton" class="form-control wp_location_refreshbutton" title="Refresh Map"><span class="hidden-xs fa fa-refresh"></span> ' . $text['reset'] . '</button>
                    </div>
                    ' . $categoryfilter . '
                    ';
            }
            $defaultID = $location_options['wp_default_location'];
            if (defined('ICL_LANGUAGE_CODE')) {
                if (ICL_LANGUAGE_CODE === 'en') {
                    $defaultID = apply_filters('wpml_object_id', $location_options['wp_default_location'], 'page', true);
                }
            }
            $out = '<div id="wprem-location-finder" class="wprem-locations-container" data-baseurl="' . $text['baseurl'] . '" data-defaultfirst="' . $defaultfirst . '" data-default="' . $defaultID . '" data-scroll="' . $scroll . '" data-region="' . $region . '" data-details=' . $details . ' data-columnsize="' . $columnsize . '" data-columns="' . $columns . '" data-bw="' . $bw . '" data-num="1" data-cat="' . $cat . '" data-orderby="' . $orderby . '" data-order="' . $order . '" data-titlelink="' . $titlelink . '" data-seedetails="' . $seedetails . '" data-seedetailsmain="' . $seedetailsmain . '" data-pintel="' . $pintel . '" data-pintoll="' . $pintoll . '" data-pinemail="' . $pinemail . '">
			<div class="wprem-locations">
				<div class="row">
                    <div class="wprem-locations-search-container">' . $searchinput . '</div>
				</div>
				<div style="position:relative; margin-top:20px">
					<img id="location-loader" src="/wp-admin/images/wpspin_light-2x.gif" style="position:absolute; top:50%; left:50%; margin:-16px 0 0 -16px"/>
					<div id="location-finder-container" style="opacity:0">
						<div class="row">
							<div class="' . $left . '">
								<div id="wprem-location-finder-map" style="z-index:99; width: 100%; height: 500px; margin-bottom:20px" data-radiusshow="' . $show_radius . '" data-radiuscolor="' . $location_options["wp_locations_radius_colour"] . '"></div>
							</div>
                            ' . $left_column . '
						</div>
					</div>
				</div>
                ' . $columns_row . '
			</div>
		</div>';
        } else {
            $out = '<input type="hidden" id="defaultMarker" value="' . $location_options["wp_locations_marker"] . '"/><input type="hidden" id="defaultMapStyle" value="' . $location_options["wp_locations_map_style"] . '"/><div class="wprem-locations-container" data-baseurl="' . $text['baseurl'] . '" data-singleid="' . $singleid . '" data-details=' . $details . ' data-num="0" data-bw="' . $bw . '" data-cat="' . $cat . '" data-orderby="' . $orderby . '" data-order="' . $order . '" data-titlelink="' . $titlelink . '" data-seedetails="' . $seedetails . '" data-seedetailsmain="' . $seedetailsmain . '" data-pintel="' . $pintel . '" data-pintoll="' . $pintoll . '" data-pinemail="' . $pinemail . '"><div class="wprem-locations"><div id="wprem-location-finder-map" style="z-index:99; width: 100%; height: ' . $height . ';" data-radiusshow="' . $show_radius . '" data-radiuscolor="' . $location_options["wp_locations_radius_colour"] . '"></div></div></div>';
        }
        /* Restore original Post Data */
        wp_reset_postdata();

        echo $out;
        $out = ob_get_clean();
        return $out;
    }

    public function single_location_shortcode($atts)
    {
        ob_start();
        extract(shortcode_atts(array(
            'title' => 0, // Show title - bool
            'titlelink' => 0, // Link title - bool
            'address' => 0, // Show address - bool
            'unit' => 0, // Show unit - bool
            'city' => 0, // Show city - bool
            'prostate' => 0, // Show city - bool
            'country' => 0, // Show country - bool
            'postal' => 0, // Show postal - bool
            'tel' => 0, // Show telephone - bool
            'toll' => 0, // Show toll free - bool
            'fax' => 0, // Show fax - bool
            'email' => 0, // Show email - bool
            'listid' => 0, // List View - show only these locations by id (comma separated)
            'collg' => '3', // Large column grid
            'colmd' => '4', // Medium column grid
            'colsm' => '6', // Small column grid
            'colxs' => '6', // XSmall column grid
            'label' => 0, // List View - show labels (ie. Tel: Toll-free: Fax: Email:)
            'orderby' => 'meta_value',
            'sort' => '_data_title', // Sort by meta_key
            'order' => 'ASC', // Sort order
            'wrap' => 'span', // Element to wrap each variable in
            'prefix' => 0, // Start of output
            'suffix' => 0, // End of output
            'full' => 0, // Make full width, omit collg etc.
            'hours' => 0,
            'holidays' => 0,
        ), $atts));

        global $post;
        switch ($sort) {
            case 'city':
                $meta_key = '_data_city';
                break;
            default:
                $meta_key = $sort;
                break;
        }
        $args = array('post_type' => WPREM_LOCATIONS_CUSTOM_POST_TYPE, 'order' => $order, 'orderby' => $orderby, 'posts_per_page' => -1, 'meta_key' => $meta_key);
        $args['post__in'] = $listid ? explode(',', $listid) : '';
        $location_query = new WP_Query($args);
        $cols = !$full ? 'col-xs-' . $colxs . ' col-sm-' . $colsm . ' col-md-' . $colmd . ' col-lg-' . $collg : 'col-xs-12';
        if ($location_query->have_posts()) {
            echo '<div class="row wprem-single-location">';
            while ($location_query->have_posts()) {
                $location_query->the_post();
                echo '<div class="' . $cols . '">'; // Start Column
                echo '<div class="wprem-single-location-inner">';
                $pid = get_the_ID();
                $details = array();
                //$details[] = ($title) ? $this->wrap(get_the_title(), 'span', 'wprem-title') : '';
                $title_out = ($title && get_post_meta($pid, '_data_title')) ? $this->wrap(get_post_meta($pid, '_data_title', true), $wrap, 'wprem-title') : '';
                // Add link to title
                if ($title_out && $titlelink) {
                    $title_w_link = '<a href="' . get_the_permalink() . '">' . get_post_meta($pid, '_data_title', true) . '</a>';
                    $title_out = $this->wrap($title_w_link, $wrap, 'wprem-title');
                }
                $details[] = $title_out;
                $details[] = ($address && get_post_meta($pid, '_data_address')) ? $this->wrap(get_post_meta($pid, '_data_address', true) . ' ' . get_post_meta($pid, '_data_unit', true), $wrap, 'wprem-address', false, false, $prefix, $suffix) : '';
                $details[] = ($city && get_post_meta($pid, '_data_city')) ? $this->wrap(get_post_meta($pid, '_data_city', true), $wrap, 'wprem-city', false, false, $prefix, $suffix) : '';
                $details[] = ($prostate && get_post_meta($pid, '_data_prostate')) ? $this->wrap(get_post_meta($pid, '_data_prostate', true), $wrap, 'wprem-prostate', false, false, $prefix, $suffix) : '';
                $details[] = ($country && get_post_meta($pid, '_data_country')) ? $this->wrap(get_post_meta($pid, '_data_country', true), $wrap, 'wprem-prostate', false, false, $prefix, $suffix) : '';
                $details[] = ($postal && get_post_meta($pid, '_data_postalcode')) ? $this->wrap(get_post_meta($pid, '_data_postalcode', true), $wrap, 'wprem-postal', false, false, $prefix, $suffix) : '';
                $details[] = ($tel && get_post_meta($pid, '_data_telephone')) ? $this->wrap('<a href="tel:' . get_post_meta($pid, '_data_telephone', true) . '">' . get_post_meta($pid, '_data_telephone', true) . '</a>', $wrap, 'wprem-telephone', 'Tel: ', $label, $prefix, $suffix) : '';
                $details[] = ($toll && get_post_meta($pid, '_data_tollfree')) ? $this->wrap('<a href="tel:' . get_post_meta($pid, '_data_tollfree', true) . '">' . get_post_meta($pid, '_data_tollfree', true) . '</a>', $wrap, 'wprem-tollfree', 'Toll-free: ', $label, $prefix, $suffix) : '';
                $details[] = ($fax && get_post_meta($pid, '_data_fax')) ? $this->wrap('<a href="tel:' . get_post_meta($pid, '_data_fax', true) . '">' . get_post_meta($pid, '_data_fax', true) . '</a>', $wrap, 'wprem-fax', 'Fax: ', $label, $prefix, $suffix) : '';
                $details[] = ($email && get_post_meta($pid, '_data_email_address')) ? $this->wrap('<a href="mailto:' . get_post_meta($pid, '_data_email_address', true) . '">' . get_post_meta($pid, '_data_email_address', true) . '</a>', $wrap, 'wprem-email', 'Email: ', $label, $prefix, $suffix) : '';
                $details[] = ($holidays && get_post_meta($pid, '_data_text_hrs_holidays')) ? $this->wrap(get_post_meta($pid, '_data_text_hrs_holidays', true), $wrap, 'wprem-holiday-hours', false, false, $prefix, $suffix) : '';
                $details[] = ($hours && get_post_meta($pid, '_data_text_custom_hours')) ? $this->wrap(get_post_meta($pid, '_data_text_custom_hours', true), $wrap, 'wprem-custom-hours', false, false, $prefix, $suffix) : '';
                foreach ($details as $detail) {
                    echo $detail;
                }
                echo '</div></div>'; // End Column
            }
            echo '</div>';
            /* Restore original Post Data */
            wp_reset_postdata();

            $out = ob_get_clean();
            return $out;
        }
    }

    // $v = content, $e = element, $c = class, $l = label name, $label = label bool
    public function wrap($v, $e, $c, $l = false, $label = false, $prefix = false, $suffix = false)
    {
        $labelout = '';
        $prefix = $prefix ? '<span class="location-prefix">' . $prefix . '</span>' : '';
        $suffix = $suffix ? '<span class="location-suffix">' . $suffix . '</span>' : '';
        if ($label && $l) {
            $labelout = '<span class="' . $c . '-title">' . $l . '</span>';
        }
        return $labelout . '<' . $e . ' class="' . $c . '">' . $prefix . $v . $suffix . '</' . $e . '> ';
    }

    public function wp_raw_location_shortcode($atts, $content = null)
    {
        ob_start();
        extract(shortcode_atts(array(
            'id' => 0,
        ), $atts));

        $location = get_post($id);
        $beforeStr = $content;
        preg_match_all('/{(\w+)}/', $content, $matches);
        $afterStr = $beforeStr;
        foreach ($matches[0] as $index => $var_name) {
            if ($matches[1][$index]) {
                $string = '{' . $matches[1][$index] . '}';
                $vari = get_post_meta($id, $matches[1][$index], true);
                $afterStr = str_replace($string, $vari, $afterStr);
            }
        }
        echo $afterStr;
        return ob_get_clean();
    }

}
