<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wprem_Location_Finder
 * @subpackage Wprem_Location_Finder/admin/partials
 */

$is_save = isset($_POST["save"]) ? $_POST["save"] : '';
if ($is_save == 1) {
    $default_location = $_POST["location"];
    update_option('wp_location_options', $default_location);
}
$location_options = get_option('wp_location_options');
?>
<div class="wrap">
<h1>Location Settings</h1>
<p></p>
<hr/>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<input type="hidden" name="save" value="1" />
<div class="cuztom cuztom--post v-cuztom">
<div class="cuztom__content">
<table class="form-table cuztom-table cuztom-main">
<tbody>
<tr class="cuztom-cell cuztom-tabs">
<td class="cuztom-field" id="_data_tabs" colspan="2">
<div class="js-cuztom-tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="_data_tabs_panel_1" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true">
<a href="#_data_tabs_panel_1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1">
Main Options
</a>
</li>
</ul>
<div id="_data_tabs_panel_1" aria-labelledby="ui-id-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="false">
<table border="0" cellading="0" cellspacing="0" class="form-table cuztom-table">
<tbody>
	<tr class="cuztom-cell">
	<th>
		<label for="_data_title" class="cuztom-field__label">Single Location Content</label>
		<div class="cuztom-field__description"></div>
	</th>
	<td class="cuztom-field cuztom-field--text" data-id="_data_title">
		<select name="location[wp_locations_default_content]">
			<option value="1" <?php echo isset($location_options['wp_locations_default_content']) ? selected($location_options['wp_locations_default_content'], '1', false) : 'selected'; ?>>Show Default</option>
			<option value="0" <?php echo isset($location_options['wp_locations_default_content']) ? selected($location_options['wp_locations_default_content'], '0', false) : '' ?>>Show Only Content</option>
		</select>
	</td>
</tr>
<tr class="cuztom-cell">
<th>
<label for="_data_title" class="cuztom-field__label">Set a default location for your organization.</label>
<div class="cuztom-field__description"></div>
</th>
<td class="cuztom-field cuztom-field--text" data-id="_data_title">
<select name="location[wp_default_location]">
<?php
$args = array('post_type' => WPREM_LOCATIONS_CUSTOM_POST_TYPE, 'orderby' => 'post_title', 'order' => 'ASC', 'posts_per_page' => 999999999);
$locations = get_posts($args);
foreach ($locations as $location):
    setup_postdata($location);
    echo '<option value="' . $location->ID . '" ' . selected($location_options['wp_default_location'], $location->ID, false) . '>' . $location->post_title . '</option>';
endforeach;
?>
</select>
</td>
</tr>
<tr class="cuztom-cell">
<th>
<label for="_data_title" class="cuztom-field__label">Title for displaying Staff Members at Location</label>
<div class="cuztom-field__description"></div>
</th>
<td class="cuztom-field cuztom-field--text" data-id="_data_title">
<input type="text" name="location[wp_title_staff_members]" value="<?php echo (isset($location_options['wp_title_staff_members']) && $location_options['wp_title_staff_members']) ? $location_options['wp_title_staff_members'] : '' ?>" />
</td>
</tr>
<tr class="cuztom-cell">
<th>
<label for="_data_title" class="cuztom-field__label">Title for displaying Promotions at Location</label>
<div class="cuztom-field__description"></div>
</th>
<td class="cuztom-field cuztom-field--text" data-id="_data_title">
<input type="text" name="location[wp_title_promotions]" value="<?php echo (isset($location_options['wp_title_promotions']) && $location_options['wp_title_promotions']) ? $location_options['wp_title_promotions'] : '' ?>" />
</td>
</tr>
<tr class="cuztom-cell">
<th>
<label for="_data_title" class="cuztom-field__label">Title for displaying Services at Location</label>
<div class="cuztom-field__description"></div>
</th>
<td class="cuztom-field cuztom-field--text" data-id="_data_title">
<input type="text" name="location[wp_title_business_services]" value="<?php echo (isset($location_options['wp_title_business_services']) && $location_options['wp_title_business_services']) ? $location_options['wp_title_business_services'] : '' ?>" />
</td>
</tr>
<tr class="cuztom-cell">
<th>
<label for="_data_title" class="cuztom-field__label">Show radius circle</label>
<div class="cuztom-field__description"></div>
</th>
<td class="cuztom-field cuztom-field--text" data-id="_data_title">
<p>
<select name="location[wp_locations_radius_show]">
<option value="y" <?php echo isset($location_options['wp_locations_radius_show']) ? selected($location_options['wp_locations_radius_show'], 'y', false) : 'selected'; ?>>Yes</option>
<option value="n" <?php echo isset($location_options['wp_locations_radius_show']) ? selected($location_options['wp_locations_radius_show'], 'n', false) : '' ?>>No</option>
</select>
</p>
</td>
</tr>
<tr class="cuztom-cell">
<th>
<label for="_data_title" class="cuztom-field__label">Set radius colour</label>
<div class="cuztom-field__description"></div>
</th>
<td class="cuztom-field cuztom-field--text" data-id="_data_title">
<p>
<input type="text" class="wprem-radius-color" name="location[wp_locations_radius_colour]" value="<?php echo (isset($location_options['wp_locations_radius_colour']) && $location_options['wp_locations_radius_colour']) ? $location_options['wp_locations_radius_colour'] : '' ?>">
</p>
</td>
</tr>
<tr class="cuztom-cell">
<th>
<label for="_data_title" class="cuztom-field__label">Set map style</label>
<div class="cuztom-field__description"></div>
</th>
<td class="cuztom-field cuztom-field--text" data-id="_data_title">
<p>
<input type="text" name="location[wp_locations_map_style]" value="<?php echo (isset($location_options['wp_locations_map_style']) && $location_options['wp_locations_map_style']) ? $location_options['wp_locations_map_style'] : '' ?>">
<br/><strong>Tiles based on OpenStreetMap</strong> - <i>make sure tiles are working and up to date</i>
</p>
</td>
</tr>
<tr class="cuztom-cell">
<th>
<label for="_data_title" class="cuztom-field__label">Set location marker (pin)</label>
<div class="cuztom-field__description"></div>
</th>
<td class="cuztom-field cuztom-field--text" data-id="_data_title">
<p id="wprem-locations-marker-preview">
<?php echo (isset($location_options['wp_locations_marker']) && $location_options['wp_locations_marker']) ? '<img src="' . $location_options['wp_locations_marker'] . '" style="max-width:45px" />' : '' ?>
</p>
<p>
<input type="text" name="location[wp_locations_marker]" value="<?php echo (isset($location_options['wp_locations_marker']) && $location_options['wp_locations_marker']) ? $location_options['wp_locations_marker'] : '' ?>" id="wprem-locations-marker">
<button class="wprem-location-marker button">Set Marker</button> <button class="wprem-location-marker-remove button">Remove</button>
</p>
<p>
<strong><a href="<?php echo plugin_dir_url(__FILE__) ?>marker_sample.png" target="_blank">Download marker sample and modify for best results</a>
</p>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<p>
<input type="submit" value="Save Location Settings" class="button button-primary button-large" />
</p>
<h2>Single Location Raw Data</h2>
<p>Shortcode use:<br/>
[wp_raw_location id="xx"] &lt;div&gt;{_data}&lt;/div&gt; [/wp_raw_location]</p>
<p>Variables in curly braces will be rendered. Can mix with HTML.</p>
<p>
<strong>Data Variables</strong>
</p>
<p>
<strong>Title:</strong> _data_title (Location Title not post title)<br/>
<strong>Email:</strong> _data_email_address<br/>
<strong>Telephone:</strong> _data_telephone<br/>
<strong>Toll-free:</strong> _data_tollfree<br/>
<strong>Fax:</strong> _data_fax<br/>
<strong>Address:</strong> _data_address, _data_unit, _data_city, _data_prostate, _data_country, _data_postalcode<br/>
<strong>Hours:</strong> _data_text_hrs_mon, _data_text_hrs_tue, _data_text_hrs_wed, _data_text_hrs_thu, _data_text_hrs_fri, _data_text_hrs_sat, _data_text_hrs_sun<br/>
<strong>Holiday Hours:</strong> _data_text_hrs_holidays<br/>
<strong>Custom Hours:</strong> _data_text_custom_hours<br/>
<strong>Social Media:</strong> _data_text_linkedin, _data_text_facebook, _data_text_instagram, _data_text_youtube, _data_text_google
</p>
<h2>Multi Location Shortcode Parameters</h2>
<strong>[wp_location map='lg' cat=1 filter=1 ... ]</strong><br/>
1 = true, 0 = false<br/><br/>
<strong>map</strong> = 'lg' // Width of map (lg / md / sm)<br/>
<strong>cat</strong> = 0 // Show category in location list (1 / 0)<br/>
<strong>filter</strong> = 0 // Show category filter (1 / 0)<br/>
<strong>orderby</strong> = 'date' // Sort by (title / date)<br/>
<strong>order</strong> = 'desc' // Sort order Ascending or Descending (asc / desc)<br/>
<strong>titlelink</strong> = 0 // Link title to singular page or default pin (1 / 0)<br/>
<strong>seedetails</strong> = 1 // Add 'See Details' link to info-window  (1 / 0)<br/>
<strong>seedetailsmain</strong> = 0 // Add 'See Details' link to main listings (1 / 0)<br/>
<strong>single</strong> = 0 // Show only map and all pins, false: show 2 col with locations details (1 / 0)<br/>
<strong>singleid</strong> = 0 // ID # single location to show<br/>
<strong>height</strong> = 'true' // Height of map in px<br/>
<strong>bw</strong> = 0 // bw: Black and white map tiles, 1 = bw, 0 = colour (1 / 0)<br/>
<strong>columns</strong> = 0 // Place locations on bottom (1) or left (0) of map<br/>
<strong>columnsize</strong> = '3' // How many columns<br/>
<strong>title</strong> = 1 // Show title (1 / 0)<br/>
<strong>address</strong> = 1 // Show address (1 / 0)<br/>
<strong>unit</strong> = 1 // Show unit (1 / 0)<br/>
<strong>city</strong> = 1 // Show city (1 / 0)<br/>
<strong>prostate</strong> = 1 // Show city (1 / 0)<br/>
<strong>country</strong> = 1 // Show country (1 / 0)<br/>
<strong>postal</strong> = 1 // Show postal (1 / 0)<br/>
<strong>tel</strong> = 1 // Show telephone (1 / 0)<br/>
<strong>toll</strong> = 1 // Show toll free (1 / 0)<br/>
<strong>fax</strong> = 1 // Show fax (1 / 0)<br/>
<strong>email</strong> = 1 // Show email (1 / 0)<br/>
<strong>scroll</strong> = 0 // Pin click scroll offset to account for sticky header (1 / 0)<br/>
<strong>region</strong> = 0 // Search via first 3 postal code characters (1 / 0)<br/>
<strong>defaultfirst</strong> = 0 // Make the default location show up first on init (1 / 0)<br/>
<strong>pintel</strong> = 1 // Show telephone in the pin info-window (1 / 0)<br/>
<strong>pinfax</strong> = 1 // Show fax in the pin info-window (1 / 0)<br/>
<strong>pintoll</strong> = 1 // Show toll free in the pin info-window (1 / 0)<br/>
<strong>pinemail</strong> = 1 // Show email in the pin info-window (1 / 0)<br/>
</form>
</div>
<?php
wp_reset_postdata();
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

