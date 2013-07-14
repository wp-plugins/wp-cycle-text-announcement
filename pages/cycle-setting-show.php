<?php
// Form submitted, check the data
if (isset($_POST['frm_wpcytxt_display']) && $_POST['frm_wpcytxt_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	
	$wpcytxt_success = '';
	$wpcytxt_success_msg = FALSE;
	
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
		?><div class="error fade"><p><strong>Oops, selected details doesn't exist (1).</strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('wpcytxt_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_WPCYTXT_SETTINGS."`
					WHERE `wpcytxt_sid` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$wpcytxt_success_msg = TRUE;
			$wpcytxt_success = __('Selected record was successfully deleted ('.$did.').', wpcytxt_UNIQUE_NAME);
		}
	}
	
	if ($wpcytxt_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $wpcytxt_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php echo Wp_wpcytxt_TITLE; ?></h2>
    <h3>Setting management<!--<a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=wp-cycle-text-announcement&amp;ac=addcycle">Add New</a>--></h3>
	<div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_WPCYTXT_SETTINGS."` order by wpcytxt_sid asc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/wp-cycle-text-announcement/pages/setting.js"></script>
		<form name="frm_wpcytxt_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th class="check-column" scope="col" style="width:15px;"><input type="checkbox" name="wpcytxt_group_item[]" /></th>
			<th scope="col">Setting name</th>
			<th scope="col">Short code</th>
			<th scope="col">Link</th>
            <th scope="col">Direction</th>
			<th scope="col">Speed</th>
			<th scope="col">Timeout</th>
			<th scope="col">Random</th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <th class="check-column" scope="col" style="height:15px;"><input type="checkbox" name="wpcytxt_group_item[]" /></th>
			<th scope="col">Setting name</th>
			<th scope="col">Short code</th>
			<th scope="col">Link</th>
            <th scope="col">Direction</th>
			<th scope="col">Speed</th>
			<th scope="col">Timeout</th>
			<th scope="col">Random</th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td align="left"><input type="checkbox" value="<?php echo $data['wpcytxt_sid']; ?>" name="wpcytxt_group_item[]"></td>
						<td><?php echo stripslashes($data['wpcytxt_sname']); ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=wp-cycle-text-announcement&amp;ac=editcycle&amp;did=<?php echo $data['wpcytxt_sid']; ?>">Edit</a></span>
							<!--<span class="trash"><a onClick="javascript:wpcytxt_content_delete('<?php //echo $data['wpcytxt_sid']; ?>')" href="javascript:void(0);">Delete</a></span> -->
						</div>
						</td>
						<td>[cycle-text setting="<?php echo(stripslashes($data['wpcytxt_sname'])); ?>"]</td>
						<td><?php echo stripslashes($data['wpcytxt_slink']); ?></td>
						<td><?php echo stripslashes($data['wpcytxt_sdirection']); ?></td>
						<td><?php echo stripslashes($data['wpcytxt_sspeed']); ?></td>
						<td><?php echo stripslashes($data['wpcytxt_stimeout']); ?></td>
						<td><?php echo stripslashes($data['wpcytxt_srandom']); ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				} 	
			}
			else
			{
				?><tr><td colspan="8" align="center">No records available.</td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('wpcytxt_form_show'); ?>
		<input type="hidden" name="frm_wpcytxt_display" value="yes"/>
      </form>	
	  <div class="tablenav">
	  <h2>
	  <!--<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=wp-cycle-text-announcement&amp;ac=add">Add New</a>-->
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=wp-cycle-text-announcement&amp;ac=show">Announcement Management</a>
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=wp-cycle-text-announcement&amp;ac=showcycle">Setting Management</a>
	  <a class="button add-new-h2" target="_blank" href="<?php echo Wp_wpcytxt_FAV; ?>">Help</a>
	  </h2>
	  </div>
	  <div style="height:5px"></div>
	<h3>Plugin configuration option</h3>
	<ol>
		<li>Add the plugin in the posts or pages using short code.</li>
		<li>Add directly in to the theme using PHP code.</li>
		<li>Drag and drop the widget to your sidebar.</li>
	</ol>
	<p class="description"><?php echo Wp_wpcytxt_LINK; ?></p>
	</div>
</div>