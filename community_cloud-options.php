<?php
/*
Author: Miguel dos Santod
Author URI: http://www.webaddict.co.za
Description: Administrative options for Community Cloud
Version: 1.2.3
*/
$plugin_basepath = str_replace("/community_cloud-options.php", "", plugin_basename(__FILE__));
load_plugin_textdomain('cc',$path = 'wp-content/plugins/'.$plugin_basepath);
$location = get_option('siteurl').'/wp-admin/admin.php?page='.$plugin_basepath.'/community_cloud-options.php'; // Form Action URI

/*Lets add some default options if they don't exist*/
add_option('cc_exclude_author', __('', 'cc'));
add_option('cc_order_by', __('random', 'cc'));
add_option('cc_threshold', __('1', 'cc'));
add_option('cc_maxtagsize', __('500', 'cc'));
add_option('cc_mintagsize', __('90', 'cc'));
add_option('cc_fontunits', __('%', 'cc'));
add_option('cc_maxtagcolour', __('#AA1111', 'cc'));
add_option('cc_mintagcolour', __('#CC7733', 'cc'));
add_option('cc_creator_credit', __('1', 'cc'));
add_option('cc_empty_urls', __('0', 'cc'));
add_option('cc_show_comment_count', __('tooltip', 'cc'));
add_option('cc_seperator', __('.', 'cc'));

/*check form submission and update options*/
if ('process' == $_POST['stage']) {
	update_option('cc_exclude_author', $_POST['cc_exclude_author']);
	update_option('cc_order_by', $_POST['cc_order_by']);
	update_option('cc_threshold', $_POST['cc_threshold']);
	update_option('cc_maxtagsize', $_POST['cc_maxtagsize']);
	update_option('cc_mintagsize', $_POST['cc_mintagsize']);
	update_option('cc_fontunits', $_POST['cc_fontunits']);
	update_option('cc_maxtagcolour', $_POST['cc_maxtagcolour']);
	update_option('cc_mintagcolour', $_POST['cc_mintagcolour']);
	update_option('cc_creator_credit', $_POST['cc_creator_credit']);
	update_option('cc_empty_urls', $_POST['cc_empty_urls']);
	update_option('cc_show_comment_count', $_POST['cc_show_comment_count']);
	update_option('cc_seperator', $_POST['cc_seperator']);
}
/*Get options for form fields*/
$cc_exclude_author = stripslashes(get_option('cc_exclude_author'));
$cc_order_by = get_option('cc_order_by');
$cc_threshold = stripslashes(get_option('cc_threshold'));
$cc_maxtagsize = stripslashes(get_option('cc_maxtagsize'));
$cc_mintagsize = stripslashes(get_option('cc_mintagsize'));
$cc_fontunits = stripslashes(get_option('cc_fontunits'));
$cc_maxtagcolour = stripslashes(get_option('cc_maxtagcolour'));
$cc_mintagcolour = stripslashes(get_option('cc_mintagcolour'));
$cc_creator_credit = get_option('cc_creator_credit');
$cc_empty_urls = get_option('cc_empty_urls');
$cc_show_comment_count = get_option('cc_show_comment_count');
$cc_seperator = get_option('cc_seperator');
?>

<div class="wrap">
	<h2><?php _e('Community Cloud Options', 'cc') ?></h2>
  <form name="form1" method="post" action="<?php echo $location ?>&amp;updated=true">
		<input type="hidden" name="stage" value="process" />
    <table width="100%" cellspacing="2" cellpadding="5" class="editform">
      <tr valign="top">
				<th scope="row" width="30%"><?php _e('Excluded Authors:', 'cc') ?></th>
        <td><input name="cc_exclude_author" type="text" id="cc_exclude_author" value="<?php echo $cc_exclude_author; ?>" size="60" />
        <br />
				<?php _e('This is a comma delimited list of all the commenters you want to exclude from the community cloud. Usually the blog authors as they are often the most frequent commenters on their own blog', 'cc') ?></td>
      </tr>
			<tr valign="top">
				<th scope="row"><?php _e('Commenter Threshold:', 'cc') ?></th>
        <td><input name="cc_threshold" type="text" id="cc_threshold" value="<?php echo $cc_threshold; ?>" size="5" />
        <br />
				<?php _e("Enter the number of comments a person must write befor he/she appears in the community cloud. eg. 1, means first-time commenters appear in the cloud. 2, means first-time commenters don't appear in the cloud. More than that means people need to comment more to appear in the community cloud", 'cc') ?></td>
      </tr>
      <tr valign="top">
				<th scope="row"><?php _e('Maximum Font Size:', 'cc') ?></th>
        <td><input name="cc_maxtagsize" type="text" id="cc_maxtagsize" value="<?php echo $cc_maxtagsize; ?>" size="10" />
        <br />
				<?php _e('Enter the font size of the most popular commenter.', 'cc') ?></td>
      </tr>
		  <tr valign="top">
				<th scope="row"><?php _e('Minimum Font Size:', 'cc') ?></th>
				<td><input name="cc_mintagsize" type="text" id="cc_mintagsize" value="<?php echo $cc_mintagsize; ?>" size="10" />
				<br />
				<?php _e('Enter the font size of the least popular commenter.', 'cc') ?></td>
		  </tr>
		  <tr valign="top">
				<th scope="row"><?php _e('Font Unit:', 'cc') ?></th>
				<td><select name="cc_fontunits" type="text" id="cc_fontunits">
					<option value='%'<?php if($cc_fontunits=='%') echo " selected='selected'"; ?>>%</option>
					<option value='pt'<?php if($cc_fontunits=='pt') echo " selected='selected'"; ?>>pt</option>
					<option value='px'<?php if($cc_fontunits=='px') echo " selected='selected'"; ?>>px</option>
					<option value='em'<?php if($cc_fontunits=='em') echo " selected='selected'"; ?>>em</option>
				</select>
				<br />
				<?php _e('Enter the font size unit of each link.', 'cc') ?></td>
		  </tr>
			<tr valign="top">
				<th scope="row"><?php _e('Link Seperator:', 'cc') ?></th>
				<td><input name="cc_seperator" type="text" id="cc_seperator" value="<?php echo $cc_seperator; ?>" size="5" />
				<br />
				<?php _e('Enter the character that seperates each link in your community cloud.', 'cc') ?></td>
		  </tr>
			<tr valign="top">
				<th scope="row"><?php _e('Display Authors with empty URLs:', 'cc') ?></th>
        <td><input name="cc_empty_urls" type="checkbox" id="cc_empty_urls" value="1"<?php if($cc_empty_urls==1) echo " checked='checked'"; ?> />
        <br />
				<?php _e('Do you want to display comment authors who have not entered their website URLs as well.', 'cc') ?></td>
      </tr>
			<tr valign="top">
				<th scope="row"><?php _e('Show Comment Count:', 'cc') ?></th>
				<td><select name="cc_show_comment_count" type="text" id="cc_show_comment_count">
					<option value='none'<?php if($cc_show_comment_count=='none') echo " selected='selected'"; ?>>Don't show comment count</option>
					<option value='tooltip'<?php if($cc_show_comment_count=='tooltip') echo " selected='selected'"; ?>>Show comment count n tooltip text</option>
					<option value='link'<?php if($cc_show_comment_count=='link') echo " selected='selected'"; ?>>Show comment count inside link after the author's name</option>
				</select>
        <br />
				<?php _e('Show the number of comments each author has written, eg. John (5), either in the link tooltip text, in the link itself, or not at all.', 'cc') ?></td>
      </tr>
			<tr valign="top">
				<th scope="row"><?php _e('Order Commenters By:', 'cc') ?></th>
				<td><select name="cc_order_by" type="text" id="cc_order_by">
					<option value='random'<?php if($cc_order_by=='random') echo " selected='selected'"; ?>>Random</option>
					<option value='recent'<?php if($cc_order_by=='recent') echo " selected='selected'"; ?>>Recent Commenter -> Earliest Commenter</option>
					<option value='top'<?php if($cc_order_by=='top') echo " selected='selected'"; ?>>Top Commenter -> Lowest Commenter</option>
				</select>
				<br />
				<?php _e('Select the order in which the cloud will display', 'cc') ?></td>
		  </tr>
			<tr valign="top">
				<th scope="row"><?php _e('Maximum Font Colour:', 'cc') ?></th>
        <td><input name="cc_maxtagcolour" type="text" id="cc_maxtagcolour" value="<?php echo $cc_maxtagcolour; ?>" size="10" />
        <br />
				<?php _e('Enter the font colour of the most popular commenter.', 'cc') ?></td>
      </tr>
		  <tr valign="top">
				<th scope="row"><?php _e('Minimum Font Colour:', 'cc') ?></th>
				<td><input name="cc_mintagcolour" type="text" id="cc_mintagcolour" value="<?php echo $cc_mintagcolour; ?>" size="10" />
				<br />
				<?php _e('Enter the font colour of the least popular commenter.', 'cc') ?></td>
		  </tr>
			<tr valign="top">
				<th scope="row"><?php _e('Author Credit:', 'cc') ?></th>
				<td><input name="cc_creator_credit" type="checkbox" id="cc_creator_credit" value="1"<?php if($cc_creator_credit==1) echo " checked='checked'"; ?> />
				<br />
				<?php _e('Share some link love with the author of this plugin. Thank you.', 'cc') ?></td>
		  </tr>
		</table>
    <p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Update Options', 'cc') ?> &raquo;" />
    </p>
  </form>
</div>