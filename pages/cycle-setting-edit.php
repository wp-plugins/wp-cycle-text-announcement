<div class="wrap">
<?php
$did = isset($_GET['did']) ? $_GET['did'] : '0';

// First check if ID exist with requested ID
$sSql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".WP_WPCYTXT_SETTINGS."
	WHERE `wpcytxt_sid` = %d",
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
		FROM `".WP_WPCYTXT_SETTINGS."`
		WHERE `wpcytxt_sid` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'wpcytxt_sname' => $data['wpcytxt_sname'],
		'wpcytxt_slink' => $data['wpcytxt_slink'],
		'wpcytxt_sdirection' => $data['wpcytxt_sdirection'],
		'wpcytxt_sspeed' => $data['wpcytxt_sspeed'],
		'wpcytxt_stimeout' => $data['wpcytxt_stimeout'],
		'wpcytxt_srandom' => $data['wpcytxt_srandom'],
		'wpcytxt_sextra' => $data['wpcytxt_sextra'],
		'wpcytxt_sid' => $data['wpcytxt_sid']
	);
}
// Form submitted, check the data
if (isset($_POST['wpcytxt_form_submit']) && $_POST['wpcytxt_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('wpcytxt_form_edit');
	
	$form['wpcytxt_sname'] = isset($_POST['wpcytxt_sname']) ? $_POST['wpcytxt_sname'] : '';
	$form['wpcytxt_slink'] = isset($_POST['wpcytxt_slink']) ? $_POST['wpcytxt_slink'] : '';
	$form['wpcytxt_sdirection'] = isset($_POST['wpcytxt_sdirection']) ? $_POST['wpcytxt_sdirection'] : '';
	$form['wpcytxt_stimeout'] = isset($_POST['wpcytxt_stimeout']) ? $_POST['wpcytxt_stimeout'] : '';
	if ($form['wpcytxt_stimeout'] == '')
	{
		$wpcytxt_errors[] = __('Please enter the slider timeout, only number.', Wp_wpcytxt_UNIQUE_NAME);
		$wpcytxt_error_found = TRUE;
	}
	$form['wpcytxt_sspeed'] = isset($_POST['wpcytxt_sspeed']) ? $_POST['wpcytxt_sspeed'] : '';
	if ($form['wpcytxt_sspeed'] == '')
	{
		$wpcytxt_errors[] = __('Please enter the slider speed, only number.', Wp_wpcytxt_UNIQUE_NAME);
		$wpcytxt_error_found = TRUE;
	}

	//	No errors found, we can add this Group to the table
	if ($wpcytxt_error_found == FALSE)
	{	
		$sSql = $wpdb->prepare(
				"UPDATE `".WP_WPCYTXT_SETTINGS."`
				SET `wpcytxt_slink` = %s,
				`wpcytxt_sdirection` = %s,
				`wpcytxt_stimeout` = %s,
				`wpcytxt_sspeed` = %s,
				`wpcytxt_stimeout` = %s
				WHERE wpcytxt_sid = %d
				LIMIT 1",
				array($form['wpcytxt_slink'], $form['wpcytxt_sdirection'], $form['wpcytxt_stimeout'], $form['wpcytxt_sspeed'], $form['wpcytxt_stimeout'], $did)
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
    <p><strong><?php echo $wpcytxt_success; ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=wp-cycle-text-announcement&ac=showcycle">Click here</a> to view the details</strong></p>
  </div>
  <?php
}
?>
<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/wp-cycle-text-announcement/pages/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php echo Wp_wpcytxt_TITLE; ?></h2>
	<form name="wpcytxt_setting_form" method="post" action="#" onsubmit="return wpcytxt_setting_submit()"  >
      <h3>Update details</h3>
	  
	  <label for="tag-title">Setting name</label>
		<select name="wpcytxt_sname" id="wpcytxt_sname" disabled="disabled">
			<option value="">Select</option>
            <?php
            for($i=1; $i<=10; $i++)
			{
				if($form['wpcytxt_sname'] == 'SETTING'.$i) 
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
		<p>Select a setting name.</p>
		
		<label for="tag-title">Link</label>
		<select name="wpcytxt_slink" id="wpcytxt_slink">
			<option value='_blank' <?php if($form['wpcytxt_slink'] == '_blank' ) { echo "selected='selected'" ; } ?>>Open in new window</option>
			<option value='_self' <?php if($form['wpcytxt_slink'] == '_self' ) { echo "selected='selected'" ; } ?>>Open in same window</option>
		</select>
		<p>Selct your link setting.</p>
		
		<label for="tag-title">Speed</label>
		<input name="wpcytxt_sspeed" type="text" id="wpcytxt_sspeed" value="<?php echo $form['wpcytxt_sspeed']; ?>" maxlength="5" />
		<p>Enter your speed. Ex: 700</p>
		
		<label for="tag-title">Timeout</label>
		<input name="wpcytxt_stimeout" type="text" id="wpcytxt_stimeout" value="<?php echo $form['wpcytxt_stimeout']; ?>" maxlength="5" />
		<p>Enter your timeout. Ex: 5000</p>
		
		<label for="tag-title">Direction</label>
		<select name="wpcytxt_sdirection" id="wpcytxt_sdirection">
            <option value='scrollLeft' <?php if($form['wpcytxt_sdirection']== 'scrollLeft') { echo 'selected' ; } ?>>scrollLeft</option>
            <option value='scrollRight' <?php if($form['wpcytxt_sdirection'] == 'scrollRight') { echo 'selected' ; } ?>>scrollRight</option>
            <option value='scrollUp' <?php if($form['wpcytxt_sdirection'] == 'scrollUp') { echo 'selected' ; } ?>>scrollUp</option>
            <option value='scrollDown' <?php if($form['wpcytxt_sdirection'] == 'scrollDown') { echo 'selected' ; } ?>>scrollDown</option>
          </select>
		<p>Selct cycle direction.</p>
		
	  
      <input name="wpcytxt_sid" id="wpcytxt_sid" type="hidden" value="<?php echo $form['wpcytxt_sid']; ?>">
      <input type="hidden" name="wpcytxt_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button add-new-h2" value="Update Details" type="submit" />&nbsp;
        <input name="publish" lang="publish" class="button add-new-h2" onclick="wpcytxt_setting_redirect()" value="Cancel" type="button" />&nbsp;
        <input name="Help" lang="publish" class="button add-new-h2" onclick="wpcytxt_help()" value="Help" type="button" />
      </p>
	  <?php wp_nonce_field('wpcytxt_form_edit'); ?>
    </form>
</div>
<p class="description"><?php echo Wp_wpcytxt_LINK; ?></p>
</div>