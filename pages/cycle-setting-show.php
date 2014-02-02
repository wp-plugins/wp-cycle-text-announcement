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
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist', 'wp-cycle-text'); ?></strong></p></div><?php
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
			$wpcytxt_success = __('Selected record was successfully deleted ('.$did.').', 'wp-cycle-text');
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
    <h2><?php _e('Wp cycle text announcement', 'wp-cycle-text'); ?></h2>
    <h3><?php _e('Setting management', 'wp-cycle-text'); ?>
	<!--<a class="add-new-h2" href="<?php echo WP_wpcytxt_ADMIN_URL; ?>&amp;ac=addcycle">Add New</a>--></h3>
	<div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_WPCYTXT_SETTINGS."` order by wpcytxt_sid asc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo WP_wpcytxt_PLUGIN_URL; ?>/pages/setting.js"></script>
		<form name="frm_wpcytxt_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th class="check-column" scope="col" style="width:15px;"><input type="checkbox" name="wpcytxt_group_item[]" /></th>
			<th scope="col"><?php _e('Setting name', 'wp-cycle-text'); ?></th>
			<th scope="col"><?php _e('Short code', 'wp-cycle-text'); ?></th>
			<th scope="col"><?php _e('Link', 'wp-cycle-text'); ?></th>
            <th scope="col"><?php _e('Direction', 'wp-cycle-text'); ?></th>
			<th scope="col"><?php _e('Speed', 'wp-cycle-text'); ?></th>
			<th scope="col"><?php _e('Timeout', 'wp-cycle-text'); ?></th>
			<th scope="col"><?php _e('Random', 'wp-cycle-text'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <th class="check-column" scope="col" style="height:15px;"><input type="checkbox" name="wpcytxt_group_item[]" /></th>
			<th scope="col"><?php _e('Setting name', 'wp-cycle-text'); ?></th>
			<th scope="col"><?php _e('Short code', 'wp-cycle-text'); ?></th>
			<th scope="col"><?php _e('Link', 'wp-cycle-text'); ?></th>
            <th scope="col"><?php _e('Direction', 'wp-cycle-text'); ?></th>
			<th scope="col"><?php _e('Speed', 'wp-cycle-text'); ?></th>
			<th scope="col"><?php _e('Timeout', 'wp-cycle-text'); ?></th>
			<th scope="col"><?php _e('Random', 'wp-cycle-text'); ?></th>
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
						<span class="edit"><a title="Edit" href="<?php echo WP_wpcytxt_ADMIN_URL; ?>&amp;ac=editcycle&amp;did=<?php echo $data['wpcytxt_sid']; ?>"><?php _e('Edit', 'wp-cycle-text'); ?></a></span>
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
				?><tr><td colspan="8" align="center"><?php _e('No records available.', 'wp-cycle-text'); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('wpcytxt_form_show'); ?>
		<input type="hidden" name="frm_wpcytxt_display" value="yes"/>
      </form>	
	  <div class="tablenav">
	  <h2>
	  <!--<a class="button add-new-h2" href="<?php echo WP_wpcytxt_ADMIN_URL; ?>&amp;ac=add">Add New</a>-->
	  <a class="button add-new-h2" href="<?php echo WP_wpcytxt_ADMIN_URL; ?>&amp;ac=show"><?php _e('Announcement Management', 'wp-cycle-text'); ?></a>
	  <a class="button add-new-h2" href="<?php echo WP_wpcytxt_ADMIN_URL; ?>&amp;ac=showcycle"><?php _e('Setting Management', 'wp-cycle-text'); ?></a>
	  <a class="button add-new-h2" target="_blank" href="<?php echo Wp_wpcytxt_FAV; ?>"><?php _e('Help', 'wp-cycle-text'); ?></a>
	  </h2>
	  </div>
	  <div style="height:5px"></div>
	<h3><?php _e('Plugin configuration option', 'wp-cycle-text'); ?></h3>
	<ol>
		<li><?php _e('Add the plugin in the posts or pages using short code.', 'wp-cycle-text'); ?></li>
		<li><?php _e('Add directly in to the theme using PHP code.', 'wp-cycle-text'); ?></li>
		<li><?php _e('Drag and drop the widget to your sidebar.', 'wp-cycle-text'); ?></li>
	</ol>
	<p class="description">
		<?php _e('Check official website for more information', 'wp-cycle-text'); ?>
		<a target="_blank" href="<?php echo Wp_wpcytxt_FAV; ?>"><?php _e('click here', 'wp-cycle-text'); ?></a>
	</p>
	</div>
</div>