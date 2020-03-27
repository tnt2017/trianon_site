<?php
 //include_once "build_menu.php";
 if (isset ( $_REQUEST ['url'] ) && strlen ( $_REQUEST ['url'] ) > 0) {
	$curURL = $_REQUEST ['url'];
}
$assoc_profile = false;
if (strpos(full_url($_SERVER), 'assoc') !== false) {
	$assoc_profile = true;
}
?>
<div id=hidForm style='display: none;'></div>
<div id=faqtoids>
	<div class=faq>
		<div class=question>Какие вопросы здесь должны быть?</div>
		<div class=answer>
			Определимся позже.
		</div>
	</div>
	<div class=faq>
		<div class=question>Есть вопросы по сайту?</div>
		<div class=answer>
			Обратитесь к вашему руководителю, отдел разработок не может сразу обслужить все входящие запросы, ваше предложение будет рассмотрено в порядке срочности.
		</div>
	</div>
</div>
<div id="wrapper">

	<!-- Sidebar -->
	<div id="sidebar-wrapper">
		<ul class="sidebar-nav">
			<div class="panel-group" id="collapse-group">
	            <?php build_menu ( $cat, $subcat, $menu, $rights, $cur_cat, $cur_subcat, $curURL, $db ); ?>
				<ul class="sidebar-copyright ">
					<hr>
					<p>Copyright &copy; <?php echo date('Y')?> by Trianon LLC</p>
				</ul>
			</div>
		</ul>
	</div>
	<!-- /#sidebar-wrapper -->

	<!-- Page Content -->
	<div id="page-content-wrapper">
		<div class="container-fluid offset-margin">
        	<?php
				if ($curURL != '') 
				{
					include_once "pages/$curURL";
				} 
				else 
				{
					include_once 'pages/dashboard.php';
				}
			?>
        </div>
	</div>
	<!-- /#page-content-wrapper -->

</div>
<!-- /#wrapper -->
<script>
	var navigation_offset = $('.navbar').outerHeight();
	if (mobileAndTabletcheck()) {
		$('.offset-margin').css('margin-top', navigation_offset);
		navigation_offset = 0;
	}
	$("#wrapper").addClass("toggled");
    $("#menu-toggle").click(function(toggleme) 
    {
        toggleme.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    
	$(document.body).ready(function(){
	    //$('#element').tooltip('toggle')
	    $("[rel='tooltip']").tooltip('hide');
    });
</script>
