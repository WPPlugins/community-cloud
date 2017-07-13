<?php
/*
Plugin Name: Community Cloud
Plugin URI: http://www.webaddict.co.za/2007/09/04/community-cloud-wordpress-plugin
Description: This plugin searches your wordpress database and displays a 'tag cloud' of all the people in your community who have contributed to our blog by commenting.  The more comments someone has made on your blog the bigger and bolder their tag.  You also have options to customize the community cloud to your needs.
People can see how big your community is in a more visually impactful way, as well as being able to see who the major contributors are compared to the people who just comment once off.
Author: Miguel dos Santos
Author URI: http://www.webaddict.co.za
Version: 1.2.3
*/
$plugin_basepath = str_replace("/community_cloud.php", "", plugin_basename(__FILE__));
load_plugin_textdomain('cc',$path = 'wp-content/plugins/'.$plugin_basepath);

### Function: Page Navigation Option Menu
add_action('admin_menu', 'community_cloud_menu');
function community_cloud_menu() {
	global $plugin_basepath;
	if (function_exists('add_options_page')) {
		add_options_page(__('Community Cloud', 'cc'), __('Community Cloud', 'cc'), 'manage_options', $plugin_basepath.'/community_cloud-options.php') ;
	}
}

/*Wrapper function which calls the form.*/
function cc_community_cloud( $content , $count=0) {
	global $wpdb;
	$exclude_author = explode(',', get_option('cc_exclude_author'));
	$cloud = '';
	
	$request = "SELECT DISTINCT c.comment_author FROM $wpdb->comments as c LEFT JOIN $wpdb->posts as p ON p.ID=c.comment_post_ID WHERE p.post_status IN ('publish','static') AND c.comment_type <> 'trackback' AND c.comment_type <> 'pingback'";
	if(get_option('cc_empty_urls')==0) $request .= " AND c.comment_author_url <> '' AND c.comment_author_url <> ' ' AND c.comment_author_url <> 'http://'";
	foreach($exclude_author as $e) { $request .= " AND c.comment_author <> '".trim($e)."'"; }
	$request .= " AND p.post_password ='' AND c.comment_approved = '1' ORDER BY ".((get_option('cc_order_by')=='recent')?"c.comment_ID DESC":"RAND()");
	if($count!=0) $request .= " LIMIT 0,".$count;
	$comments = $wpdb->get_results($request);
	
	//if order from top commenter to lowest commenter, we need to count the number of comments each author has written and then reorder the array from most to least
	if(get_option('cc_order_by')=='top') {
		$data = array();
		$author = array();
		$count = array();
		foreach ($comments as $comment) { // for each commenter count the number of his/her comments that have been approved
			$count_result = $wpdb->get_results("SELECT count(*) as counter FROM $wpdb->comments as c WHERE c.comment_author = '".mysql_escape_string($comment->comment_author)."' AND c.comment_approved = '1';");
			if($count_result[0]->counter>=get_option('cc_threshold')) {
				$data[] = array('author' => $comment->comment_author, 'count' => $count_result[0]->counter);
				$author[] = $comment->comment_author;
				$count[] = $count_result[0]->counter;
			}
		}
		array_multisort($count, SORT_DESC, $data);
		//now that we've sorted the author list we can display it
		foreach($data as $key => $row) {
			$comment_author_url = $wpdb->get_results("SELECT c.comment_author_url FROM $wpdb->comments as c WHERE c.comment_author = '".mysql_escape_string($row['author'])."' AND c.comment_approved = '1' AND c.comment_author_url <> '' AND c.comment_author_url <> ' ' AND c.comment_author_url <> 'http://' ORDER BY c.comment_ID DESC LIMIT 1;");
			$link_flag = valid_url($comment_author_url[0]->comment_author_url);
			if($link_flag) $cloud .= " <a href='".$comment_author_url[0]->comment_author_url."' title='".$row['author'].((get_option('cc_show_comment_count')=='tooltip')?(" (".$row['count'].")"):"")."' style='font-size:".cc_GetFontSizeForWeight($row['count']).";".cc_GetColorForWeight($row['count']).";'>";
			else $cloud .= " <span style='font-size:".cc_GetFontSizeForWeight($row['count']).";".cc_GetColorForWeight($row['count']).";'>";
			$cloud .= $row['author'].((get_option('cc_show_comment_count')=='link')?(" (".$row['count'].")"):"");
			if($link_flag) $cloud .= "</a> ";
			else $cloud .= "</span> ";
			$cloud .= get_option('cc_seperator');
		}
	} else {
		foreach ($comments as $comment) {
			$count_result = $wpdb->get_results("SELECT count(*) as counter FROM $wpdb->comments as c WHERE c.comment_author = '".mysql_escape_string($comment->comment_author)."' AND c.comment_approved = '1';");
			if($count_result[0]->counter>=get_option('cc_threshold')) {
				$comment_author_url = $wpdb->get_results("SELECT c.comment_author_url FROM $wpdb->comments as c WHERE c.comment_author = '".mysql_escape_string($comment->comment_author)."' AND c.comment_approved = '1' AND c.comment_author_url <> '' AND c.comment_author_url <> ' ' AND c.comment_author_url <> 'http://' ORDER BY c.comment_ID DESC LIMIT 1;");
				$link_flag = valid_url($comment_author_url[0]->comment_author_url);
				if($link_flag) $cloud .= " <a href='".$comment_author_url[0]->comment_author_url."' title='".$comment->comment_author.((get_option('cc_show_comment_count')=='tooltip')?(" (".$count_result[0]->counter.")"):"")."' style='font-size:".cc_GetFontSizeForWeight($count_result[0]->counter).";".cc_GetColorForWeight($count_result[0]->counter).";'>";
				else $cloud .= " <span style='font-size:".cc_GetFontSizeForWeight($count_result[0]->counter).";".cc_GetColorForWeight($count_result[0]->counter).";'>";
				$cloud .= $comment->comment_author.((get_option('cc_show_comment_count')=='link')?(" (".$count_result[0]->counter.")"):"");
				if($link_flag) $cloud .= "</a> ";
				else $cloud .= "</span> ";
				$cloud .= get_option('cc_seperator');
			}
		}
	}
	if(get_option('cc_creator_credit')==1) $cloud .= "<br /><a href='http://www.webaddict.co.za' title='Your Group of Web AddiCT(s);'>Your Group of Web AddiCT(s);</a>";
	return str_replace('<!--community cloud-->', $cloud, $content);
}

function cc_GetFontSizeForWeight($weight) {
	$maxtagsize = (get_option('cc_maxtagsize'))?get_option('cc_maxtagsize'):400;
	$mintagsize = (get_option('cc_mintagsize'))?get_option('cc_mintagsize'):90;
	$fontunits = (get_option('cc_fontunits'))?get_option('cc_fontunits'):'%';
	if ($maxtagsize > $mintagsize) {
		$fontsize = (($weight/100) * ($maxtagsize - $mintagsize)) + $mintagsize;
	} else {
		$fontsize = (((100-$weight)/100) * ($maxtagsize - $mintagsize)) + $maxtagsize;
	}
	return intval($fontsize) . $fontunits;
}

function cc_GetColorForWeight($weight) {
	if(!get_option('cc_maxtagcolour') && !get_option('cc_mintagcolour')) return "";
	$maxtagcolour = get_option('cc_maxtagcolour');
	$mintagcolour = get_option('cc_mintagcolour');
	if ($weight) {
		$weight = $weight/100;

		$minr = hexdec(substr($mintagcolour, 1, 2));
		$ming = hexdec(substr($mintagcolour, 3, 2));
		$minb = hexdec(substr($mintagcolour, 5, 2));

		$maxr = hexdec(substr($maxtagcolour, 1, 2));
		$maxg = hexdec(substr($maxtagcolour, 3, 2));
		$maxb = hexdec(substr($maxtagcolour, 5, 2));

		$r = dechex(intval((($maxr - $minr) * $weight) + $minr));
		$g = dechex(intval((($maxg - $ming) * $weight) + $ming));
		$b = dechex(intval((($maxb - $minb) * $weight) + $minb));

		if (strlen($r) == 1) $r = "0" . $r;
		if (strlen($g) == 1) $g = "0" . $g;
		if (strlen($b) == 1) $b = "0" . $b;

		return "color:#$r$g$b";
	}
}

/* Action calls for all functions */
add_filter('the_content', 'cc_community_cloud');

// This gets called at the plugins_loaded action
function widget_community_cloud_init() {
	
	// Check for the required API functions
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	// This saves options and prints the widget's config form.
	function widget_community_cloud_control() {
		$options = $newoptions = get_option('widget_community_cloud');
		if ( $_POST['community_cloud-submit'] ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST['community_cloud-title']));
			$newoptions['count'] = (int) $_POST['community_cloud-count'];
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_community_cloud', $options);
		}
	?>
		<div style="text-align:right">
		<label for="community_cloud-title" style="line-height:35px;display:block;"><?php _e('Widget Title:', 'widgets'); ?> <input type="text" id="community_cloud-title" name="community_cloud-title" value="<?php echo wp_specialchars($options['title'], true); ?>" /></label>
		<label for="community_cloud-count" style="line-height:35px;display:block;"><?php _e('Number of Links in Cloud:', 'widgets'); ?>
		<select id="community_cloud-count" name="community_cloud-count">
			<option value="0"<?php if(0==$options['count']) echo "selected=selected"; ?>>Show All</option>
			<option value="100"<?php if(100==$options['count']) echo "selected=selected"; ?>>100</option>
			<option value="50"<?php if(50==$options['count']) echo "selected=selected"; ?>>50</option>
			<option value="25"<?php if(25==$options['count']) echo "selected=selected"; ?>>25</option>
			<option value="10"<?php if(10==$options['count']) echo "selected=selected"; ?>>10</option>
		</select></label>
		<input type="hidden" name="community_cloud-submit" id="community_cloud-submit" value="1" />
		</div>
	<?php
	}

	// This prints the widget
	function widget_community_cloud($args) {
		extract($args);
		$defaults = array('count' => 0, 'title' => 'Community Cloud');
		$options = (array) get_option('widget_community_cloud');

		foreach ( $defaults as $key => $value ) {
			if ( !isset($options[$key]) ) $options[$key] = $defaults[$key];
		}
		echo $before_widget; 
		echo $before_title . $options['title'] . $after_title; 
		echo cc_community_cloud( "<!--community cloud-->",  $options['count']);
		echo $after_widget; 
	}

	// Tell Dynamic Sidebar about our new widget and its control
	register_sidebar_widget(array('Community Cloud', 'widgets'), 'widget_community_cloud');
	register_widget_control(array('Community Cloud', 'widgets'), 'widget_community_cloud_control');
	
}

// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first
add_action('widgets_init', 'widget_community_cloud_init');

function valid_url( $url ) {
	return preg_match ("/http:\/\/(.*)\.(.*)/i", $url);
}
?>