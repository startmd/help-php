		</main>
		<div class='bottom-fixed-menu'>
			<div class='pull-right'>Powered By <a href=''>StartMD</a></div>
		</div>
		<script type='text/javascript' src='<?php echo $url; ?>assets/js/zepto.js'></script>
		<script type='text/javascript' src='<?php echo $url; ?>assets/js/velocity.js'></script>
		<script type='text/javascript' src='<?php echo $url; ?>assets/js/jquery-rebox.js'></script>
		<script>
		function closeOverlay() {
			$(".overlay").velocity("fadeOut",{duration:700}).velocity("slideUp", { duration: 900 });
		}
		function openOverlay(id) {
			$("#"+id).velocity("fadeIn",{duration:700}).velocity("slideDown", { duration: 900 });
		}
		$(".lightbox-gallery .lightbox-item").rebox();
		</script>
	</body>
</html>