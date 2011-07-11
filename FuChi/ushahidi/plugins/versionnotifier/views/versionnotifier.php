<strong>
<div style="float:left"><?php echo ucfirst(Kohana::lang('ui_admin.database')); ?> <?php echo Kohana::lang('ui_admin.version'); ?> <?php echo $version_in_db; ?></div>
<?php if($needs_upgrade) { ?>
<?php
	echo form::open(NULL, array('id' => 'versionnotifier_update', 'name' => 'versionnotifier_update', 'style' => 'float:left;padding-left:15px;'));
	echo form::hidden('versionnotifier_update','1');
	echo form::submit('submit', 'Upgrade from version '.$version_in_db.' to '.($version_in_db+1));
	echo form::close();
?>
	
	
	
	
<?php } ?>
<div style="clear:both;"></div>
</strong>
