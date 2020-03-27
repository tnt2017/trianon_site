	<style>
		.back-to-top {
			background: none;
			margin: 0;
			position: fixed;
			bottom: 0;
			right: 0;
			width: 70px;
			height: 70px;
			z-index: 100;
			display: none;
			text-decoration: none;
			color: #ffffff;
		}
	</style>
	
	<script>
		jQuery(document).ready(function() {
			var offset = 250;
			var duration = 300;
			jQuery(window).scroll(function() {
				if (jQuery(this).scrollTop() > offset) {
					jQuery('.back-to-top').fadeIn(duration);
				} else {
					jQuery('.back-to-top').fadeOut(duration);
				}
			});
			 
			jQuery('.back-to-top').click(function(event) {
				event.preventDefault();
				jQuery('html, body').animate({scrollTop: 0}, duration);
				return false;
			})
		});
	</script>
	
	<a href='#' class='back-to-top' style='display: inline;'>
		<div class='float-button btn-warning' data-spy="affix">
		<table width='100%'>
			<tr height='48px'>
				<td valign='center'>
					<span class="glyphicon glyphicon-arrow-up"></span>
				</td>
			</tr>
		</table>
	</div>
	</a>
</body>
</html>