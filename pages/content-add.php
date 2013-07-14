<div class="wrap">
<?php
$wpcytxt_errors = array();
$wpcytxt_success = '';
$wpcytxt_error_found = FALSE;

// Preset the form fields
$form = array(
	'wpcytxt_ctitle' => '',
	'wpcytxt_clink' => '',
	'wpcytxt_cstartdate' => '',
	'wpcytxt_cenddate' => '',
	'wpcytxt_csetting' => '',
	'wpcytxt_cid' => ''
);

// Form submitted, check the data
if (isset($_POST['wpcytxt_form_submit']) && $_POST['wpcytxt_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('wpcytxt_form_add');
	
	$form['wpcytxt_ctitle'] = isset($_POST['wpcytxt_ctitle']) ? $_POST['wpcytxt_ctitle'] : '';
	if ($form['wpcytxt_ctitle'] == '')
	{
		$wpcytxt_errors[] = __('Please enter the announcement.', Wp_wpcytxt_UNIQUE_NAME);
		$wpcytxt_error_found = TRUE;
	}
	$form['wpcytxt_clink'] = isset($_POST['wpcytxt_clink']) ? $_POST['wpcytxt_clink'] : '';
	if ($form['wpcytxt_clink'] == '')
	{
		$wpcytxt_errors[] = __('Please enter the link, if no link just enter #.', Wp_wpcytxt_UNIQUE_NAME);
		$wpcytxt_error_found = TRUE;
	}
	$form['wpcytxt_cstartdate'] = isset($_POST['wpcytxt_cstartdate']) ? $_POST['wpcytxt_cstartdate'] : '';
	if ($form['wpcytxt_cstartdate'] == '')
	{
		$wpcytxt_errors[] = __('Please enter the start date, YYYY-MM-DD.', Wp_wpcytxt_UNIQUE_NAME);
		$wpcytxt_error_found = TRUE;
	}
	$form['wpcytxt_csetting'] = isset($_POST['wpcytxt_csetting']) ? $_POST['wpcytxt_csetting'] : '';
	if ($form['wpcytxt_csetting'] == '')
	{
		$wpcytxt_errors[] = __('Please select the setting.', Wp_wpcytxt_UNIQUE_NAME);
		$wpcytxt_error_found = TRUE;
	}
	$form['wpcytxt_cenddate'] = isset($_POST['wpcytxt_cenddate']) ? $_POST['wpcytxt_cenddate'] : '';
	if ($form['wpcytxt_cenddate'] == '')
	{
		$wpcytxt_errors[] = __('Please enter the end date, YYYY-MM-DD.', Wp_wpcytxt_UNIQUE_NAME);
		$wpcytxt_error_found = TRUE;
	}
	
	//	No errors found, we can add this Group to the table
	if ($wpcytxt_error_found == FALSE)
	{
		$sql = $wpdb->prepare(
			"INSERT INTO `".WP_WPCYTXT_CONTENT."`
			(`wpcytxt_ctitle`, `wpcytxt_clink`, `wpcytxt_cstartdate`, `wpcytxt_cenddate`, `wpcytxt_csetting`)
			VALUES(%s, %s, %s, %s, %s)",
			array($form['wpcytxt_ctitle'], $form['wpcytxt_clink'], $form['wpcytxt_cstartdate'], $form['wpcytxt_cenddate'], $form['wpcytxt_csetting'])
		);
		$wpdb->query($sql);
		
		$wpcytxt_success = __('New details was successfully added.', Wp_wpcytxt_UNIQUE_NAME);
		
		// Reset the form fields
		$form = array(
			'wpcytxt_ctitle' => '',
			'wpcytxt_clink' => '',
			'wpcytxt_cstartdate' => '',
			'wpcytxt_cenddate' => '',
			'wpcytxt_csetting' => '',
			'wpcytxt_cid' => ''
		);
	}
}

if ($wpcytxt_error_found == TRUE && isset($wpcytxt_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $wpcytxt_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($wpcytxt_error_found == FALSE && strlen($wpcytxt_success) > 0)
{
	?>
	  <div class="updated fade">
		<p><strong><?php echo $wpcytxt_success; ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=wp-cycle-text-announcement">Click here</a> to view the details</strong></p>
	  </div>
	  <?php
	}
?>
<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/wp-cycle-text-announcement/pages/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php echo Wp_wpcytxt_TITLE; ?></h2>
	<form name="wpcytxt_content_form" method="post" action="#" onsubmit="return wpcytxt_content_submit()"  >
      <h3>Add announcement details</h3>
      
		<label for="tag-title">Announcement</label>
		<textarea name="wpcytxt_ctitle" id="wpcytxt_ctitle" cols="100" rows="3"></textarea>
		<p>Enter your announcement text.</p>
		
		<label for="tag-title">Link</label>
		<input name="wpcytxt_clink" type="text" id="wpcytxt_clink" value="" size="103" />
		<p>Enter your link.</p>
		
		<label for="tag-title">Setting name:</label>
		<select name="wpcytxt_csetting" id="wpcytxt_csetting">
			<option value="">Select</option>
            <?php
            for($i=1; $i<=10; $i++)
			{
				echo "<option value='SETTING".$i."'>SETTING".$i."</option>";
			}
			?>
          </select>
		<p>Select a setting for your announcement.</p>
	  
	  	<label for="tag-title">Start date</label>
		<input name="wpcytxt_cstartdate" type="text" id="wpcytxt_cstartdate" value=""  size="15" maxlength="10" />
		<p>Enter your announcement display start date, Formate YYYY-MM-DD</p>
		
		<label for="tag-title">Start date</label>
		<input name="wpcytxt_cenddate" type="text" id="wpcytxt_cenddate" value=""  size="15" maxlength="10" />
		<p>Enter your announcement display end date, Formate YYYY-MM-DD</p>
		
	  
      <input name="wpcytxt_cid" id="wpcytxt_cid" type="hidden" value="">
      <input type="hidden" name="wpcytxt_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button add-new-h2" value="Insert Details" type="submit" />&nbsp;
        <input name="publish" lang="publish" class="button add-new-h2" onclick="wpcytxt_content_redirect()" value="Cancel" type="button" />&nbsp;
        <input name="Help" lang="publish" class="button add-new-h2" onclick="wpcytxt_help()" value="Help" type="button" />
      </p>
	  <?php wp_nonce_field('wpcytxt_form_add'); ?>
    </form>
</div>
<p class="description"><?php echo Wp_wpcytxt_LINK; ?></p>
</div>