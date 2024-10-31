<?php
/*
Plugin Name: Plot Over Time - Extended
Version: 1.4.0
Plugin URI: http://www.save-o-matic.com
Description: Uses the Google Chart Tools API for charting data. Supports two optional components of multiple charts per page and category restrictions! 

Tracks up to 10 different data points, supports Area Chart, Line Chart, Pie Chart, Bar Chart, and Column Chart styles, any custom style options you'd like to include, and number of other options.  Be sure to read the read me for full notes and updates.  Based on MidnightRyder's (http://www.midnightryder.org) Plot Over Time.

Author: Rodger Cravens
Author URI: http://www.save-o-matic.com
*/

/*  Copyright 2014  Rodger Cravens, (email : rodger.cravens@gmail.com)

    This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA */

add_shortcode('plot_ext', 'plotData_ext_Handler');

function plotData_ext_Handler( $atts )
{
	extract(shortcode_atts(array(
		"field1" => '',
		"field2" => '',
		"field3" => '',
		"field4" => '',
		"field5" => '',
		"field6" => '',
		"field7" => '',
		"field8" => '',
		"field9" => '',
		"field10" => '',
		"width" => '400',
		"height" => '300',
		"legend" => "right",
		"type" => "LineChart",
		"options" => '',
		"usepostdate" => '',
		"maxdays" => "1000",
		"dateformat" => "m/d/y",
        	"chart_num" => '1',
		"post_cat" => '*',
		"sort_order" => 'ASC'
	), $atts));

    $dataFromPlot = plotData_ext($field1, $field2 , $field3, $field4, $field5, $field6, $field7, $field8, $field9, $field10, $width, $height, $legend, $type, $options, $usepostdate, $maxdays, $dateformat, $chart_num, $post_cat, $sort_order);
    
    return $dataFromPlot;
}

function plotData_ext( $field1, $field2 , $field3, $field4, $field5, $field6, $field7, $field8, $field9, $field10, $width, $height, $legend, $type, $options, $usepostdate, $maxdays, $dateformat, $chart_num, $post_cat, $sort_order)
{
 global $wpdb;
 $postDate = strtotime(strip_tags(get_the_date()));
 $skipf = 1; 	
 
if ($sort_order == 'ASC')
{
	$sub_sorting = 'DESC';
} else {
	$sub_sorting = 'ASC';
}

 // Query the database for the posts that contain this custom field
 $query_string = "(SELECT * FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
 WHERE ( ($wpdb->postmeta.meta_key = '" . $field1 . "' AND $wpdb->postmeta.meta_value * 1 > 1)";

 if($field2){
 	$query_string .= " OR ($wpdb->postmeta.meta_key = '" . $field2 . "' AND $wpdb->postmeta.meta_value * 1 > 1)"; $skipf = 2; }
 if($field3){
 	$query_string .= " OR ($wpdb->postmeta.meta_key = '" . $field3 . "' AND $wpdb->postmeta.meta_value * 1 > 1)"; $skipf = 3; }
 if($field4){
 	$query_string .= " OR ($wpdb->postmeta.meta_key = '" . $field4 . "' AND $wpdb->postmeta.meta_value * 1 > 1)"; $skipf = 4; }
  if($field5){
 	$query_string .= " OR ($wpdb->postmeta.meta_key = '" . $field5 . "' AND $wpdb->postmeta.meta_value * 1 > 1)"; $skipf = 5; }
 if($field6){
 	$query_string .= " OR ($wpdb->postmeta.meta_key = '" . $field6 . "' AND $wpdb->postmeta.meta_value * 1 > 1)"; $skipf = 6; } 
 if($field7){
 	$query_string .= " OR ($wpdb->postmeta.meta_key = '" . $field7 . "' AND $wpdb->postmeta.meta_value * 1 > 1)"; $skipf = 7; } 
 if($field8){
 	$query_string .= " OR ($wpdb->postmeta.meta_key = '" . $field8 . "' AND $wpdb->postmeta.meta_value * 1 > 1)"; $skipf = 8; } 
 if($field9){
 	$query_string .= " OR ($wpdb->postmeta.meta_key = '" . $field9 . "' AND $wpdb->postmeta.meta_value * 1 > 1)"; $skipf = 9; } 
 if($field10){
 	$query_string .= " OR ($wpdb->postmeta.meta_key = '" . $field10 . "' AND $wpdb->postmeta.meta_value * 1 > 1)"; $skipf = 10; } 

$query_string .= " )  AND $wpdb->posts.post_status = 'publish'";

if(!$usepostdate == '')
{
$query_string .= " AND ID IN (Select object_id FROM wp_term_relationships, wp_terms WHERE wp_term_relationships.term_taxonomy_id = " . $post_cat . ")";
}

$query_string .= " AND $wpdb->posts.post_type = 'post'";

if(!$usepostdate == '')
{
$query_string .= " AND $wpdb->posts.post_date <= '" . strip_tags(get_the_date()) . " 23:59:59'";
}

$query_string .= " ORDER BY $wpdb->posts.post_date " . $sub_sorting . " LIMIT " . $maxdays . ") ORDER BY post_date " . $sort_order;

//echo 'String: ' . $query_string;
//echo '<br>Days: ' . $maxdays;

// List the posts
$series_posts = $wpdb->get_results($query_string, OBJECT);

$firstDate = "";
$numOfPoints = sizeof($series_posts);
$googleJSAPIData = "";

$googleJSAPI = '<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script type="text/javascript">
// Load the Visualization API and the piechart package.
google.load(\'visualization\', \'1.0\', {\'packages\':[\'corechart\']});

google.setOnLoadCallback(drawChart'.$chart_num.');
function drawChart'.$chart_num.'() {
  var data'.$chart_num.' = new google.visualization.DataTable();
  data'.$chart_num.'.addColumn("string", "Date");
  data'.$chart_num.'.addColumn("number", "' . $field1 . '");';
  if($field2) $googleJSAPI .= 'data'.$chart_num.'.addColumn("number", "' . $field2 . '");';
  if($field3) $googleJSAPI .= 'data'.$chart_num.'.addColumn("number", "' . $field3 . '");';
  if($field4) $googleJSAPI .= 'data'.$chart_num.'.addColumn("number", "' . $field4 . '");';
  if($field5) $googleJSAPI .= 'data'.$chart_num.'.addColumn("number", "' . $field5 . '");';
  if($field6) $googleJSAPI .= 'data'.$chart_num.'.addColumn("number", "' . $field6 . '");';
  if($field7) $googleJSAPI .= 'data'.$chart_num.'.addColumn("number", "' . $field7 . '");';
  if($field8) $googleJSAPI .= 'data'.$chart_num.'.addColumn("number", "' . $field8 . '");';
  if($field9) $googleJSAPI .= 'data'.$chart_num.'.addColumn("number", "' . $field9 . '");';
  if($field10) $googleJSAPI .= 'data'.$chart_num.'.addColumn("number", "' . $field10 . '");';	
  $googleJSAPIEnd = 'var chart'.$chart_num.' = new google.visualization.' . $type . '(document.getElementById(\'chart_div_'.$chart_num.'\'));
  chart'.$chart_num.'.draw(data'.$chart_num.', options);
} 
</script>
<div id="chart_div_'.$chart_num.'" style="padding:0; spacing:0; border: 1px solid black; width:' . $width . '; height: ' . $height . '"></div>';

 if ($series_posts):
   foreach ($series_posts as $post):
	   $i++;
	   $postdate = (strtotime($post->post_date));
	   //if(($postdate > $minDate) && ($postdate < $maxDate))
	   //{ 
	   		if($i == $skipf) {
	   	  		$googleJSAPIData .= "data$chart_num.addRow(['" . date($dateformat, $postdate) . "', " . ( get_post_meta($post->ID, $field1, true)); //' . date_format($post->post_date, "m-d")  . "', " .
				if($field2) 
					$googleJSAPIData .= ", " . ( get_post_meta($post->ID, $field2, true));
				if($field3) 
					$googleJSAPIData .= ", " . ( get_post_meta($post->ID, $field3, true));
				if($field4) 
					$googleJSAPIData .= ", " . ( get_post_meta($post->ID, $field4, true));
				if($field5) 
					$googleJSAPIData .= ", " . ( get_post_meta($post->ID, $field5, true));
				if($field6) 
					$googleJSAPIData .= ", " . ( get_post_meta($post->ID, $field6, true));
				if($field7) 
					$googleJSAPIData .= ", " . ( get_post_meta($post->ID, $field7, true));
				if($field8) 
					$googleJSAPIData .= ", " . ( get_post_meta($post->ID, $field8, true));
				if($field9) 
					$googleJSAPIData .= ", " . ( get_post_meta($post->ID, $field9, true));
				if($field10) 
					$googleJSAPIData .= ", " . ( get_post_meta($post->ID, $field10, true));
				$googleJSAPIData .= "]);";
				$i = 0;
			}
		//}
   endforeach;
 endif;

$googleJSAPIOptions = "var options= {" ;
if($options)
  $googleJSAPIOptions .= $options . ", ";
  $googleJSAPIOptions .= "'width . ': " . $width . ", 'height': " . $height . "};";
   
  return  $debugReturn . $googleJSAPI . $googleJSAPIData . $googleJSAPIOptions . $googleJSAPIEnd;
}

add_filter('the_content', 'filterPlot_ext');

function filterPlot_ext($content) 
{
  if(preg_match("[plot_ext]",$content))
  {
    $posOfFilter = strpos($content,'[[plot_ext]]');
    if ($posOfFilter !== FALSE)
    {
      $contentStart = substr($content,0,$posOfFilter); 
      $contentEnd   = substr($content,$posOfFilter+strlen('[[plot_ext]]'),strlen($content));
      $content = $contentStart . plotData_ext("",1,0) . $contentEnd;
    }
  }
  return $content;
} 
?>