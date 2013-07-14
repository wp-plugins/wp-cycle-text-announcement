<div class="wrap">
<?php
$did = isset($_GET['did']) ? $_GET['did'] : '0';

// First check if ID exist with requested ID
$sSql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".WP_WPCYTXT_CONTENT."
	WHERE `wpcytxt_cid` = %d",
	array($did)
);
$result = '0';
$result = $wpdb->get_var($sSql);

if ($result != '1')
{
	?><div class="error fade"><p><strong>Oops, selected details doesn't exist.</strong></p></div><?php
}
else
{
	$wpcytxt_errors = array();
	$wpcytxt_success = '';
	$wpcytxt_error_found = FALSE;
	
	$sSql = $wpdb->prepare("
		SELECT *
		FROM `".WP_WPCYTXT_CONTENT."`
		WHERE `wpcytxt_cid` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'wpcytxt_ctitle' => $data['wpcytxt_ctitle'],
		'wpcytxt_clink' => $data['wpcytxt_clink'],
		'wpcytxt_cstartdate' => $data['wpcytxt_cstartdate'],
		'wpcytxt_cenddate' => $data['wpcytxt_cenddate'],
		'wpcytxt_csetting' => $data['wpcytxt_csetting'],
		'wpcytxt_cid' => $data['wpcytxt_cid']
	);
}
// Form submitted, check the data
if (isset($_POST['wpcytxt_form_submit']) && $_POST['wpcytxt_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('wpcytxt_form_edit');
	
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
		$sSql = $wpdb->prepare(
				"UPDATE `".WP_WPCYTXT_CONTENT."`
				SET `wpcytxt_ctitle` = %s,
				`wpcytxt_clink` = %s,
				`wpcytxt_cstartdate` = %s,
				`wpcytxt_cenddate` = %s,
				`wpcytxt_csetting` = %s
				WHERE wpcytxt_cid = %d
				LIMIT 1",
				array($form['wpcytxt_ctitle'], $form['wpcytxt_clink'], $form['wpcytxt_cstartdate'], $form['wpcytxt_cenddate'], $form['wpcytxt_csetting'], $did)
			);
		$wpdb->query($sSql);
		
		$wpcytxt_success = 'Details was successfully updated.';
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
      <h3>Update details</h3>
	  
		<label for="tag-title">Announcement</label>
		<textarea name="wpcytxt_ctitle" id="wpcytxt_ctitle" cols="100" rows="3"><?php echo $form['wpcytxt_ctitle']; ?></textarea>
		<p>Enter your announcement text.</p>
		
		<label for="tag-title">Link</label>
		<input name="wpcytxt_clink" type="text" id="wpcytxt_clink" value="<?php echo $form['wpcytxt_clink']; ?>" size="103" />
		<p>Enter your link.</p>
		
		<label for="tag-title">Setting name:</label>
		<select name="wpcytxt_csetting" id="wpcytxt_csetting">
			<option value="">Select</option>
            <?php
            for($i=1; $i<=10; $i++)
			{
				if($form['wpcytxt_csetting'] == 'SETTING'.$i) 
				{ 
					$selected = "selected='selected'" ; 
				}
				else
				{
					$selected = '' ; 
				}
				echo "<option value='SETTING".$i."' $selected>SETTING".$i."</option>";
			}
			?>
          </select>
		<p>Select a setting for your announcement.</p>
	  
	  	<label for="tag-title">Start date</label>
		<input name="wpcytxt_cstartdate" type="text" id="wpcytxt_cstartdate" value="<?php echo substr($form['wpcytxt_cstartdate'], 0, 10); ?>"  size="15" maxlength="10" />
		<p>Enter your announcement display start date, Formate YYYY-MM-DD</p>
		
		<label for="tag-title">Start date</label>
		<input name="wpcytxt_cenddate" type="text" id="wpcytxt_cenddate" value="<?php echo substr($form['wpcytxt_cenddate'], 0, 10); ?>"  size="15" maxlength="10" />
		<p>Enter your announcement display end date, Formate YYYY-MM-DD</p>
	  
      <input name="wpcytxt_cid" id="wpcytxt_cid" type="hidden" value="<?php echo $form['wpcytxt_cid']; ?>">
      <input type="hidden" name="wpcytxt_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button add-new-h2" value="Update Details" type="submit" />&nbsp;
        <input name="publish" lang="publish" class="button add-new-h2" onclick="wpcytxt_content_redirect()" value="Cancel" type="button" />&nbsp;
        <input name="Help" lang="publish" class="button add-new-h2" onclick="wpcytxt_help()" value="Help" type="button" />
      </p>
	  <?php wp_nonce_field('wpcytxt_form_edit'); ?>
    </form>
</div>
<p class="description"><?php echo Wp_wpcytxt_LINK; ?></p>
</div>