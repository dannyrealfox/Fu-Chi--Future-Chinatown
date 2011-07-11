			</div>
		</div>
		<!-- / main body -->

	</div>
	<!-- / wrapper -->
	
	<!-- footer -->
	<div id="footer" class="clearingfix">
 
		<div id="underfooter"></div>
				
		<!-- footer content -->
		<div class="rapidxwpr floatholder">
 
			<!-- footer credits -->
			<div class="footer-credits">
				Copyright &copy; 2011-2012 <a href="http://futurechinatown.com">福氣 Future Chinatown</a>. Powered by the &nbsp;<a href="http://www.ushahidi.com/"><img src="<?php echo url::base(); ?>/media/img/footer-logo-red.png" alt="Ushahidi" style="vertical-align:middle" /></a>&nbsp; Platform
			</div>
			<!-- / footer credits -->
		
 
		</div>
		<!-- / footer content -->
 
	</div>
	<!-- / footer -->
 
	<?php echo $ushahidi_stats; ?>
	<?php echo $google_analytics; ?>
	
	<!-- Task Scheduler -->
	<img src="<?php echo url::base(); ?>media/img/spacer.gif" alt="" height="1" width="1" border="0" onload="runScheduler(this)" />
 
	<?php
	// Action::main_footer - Add items before the </body> tag
	Event::run('ushahidi_action.main_footer');
	?>
</body>
</html>
