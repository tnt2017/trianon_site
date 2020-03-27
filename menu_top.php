<?php
$cat = array ();
$subcat = array ();
$menu = array ();
$rights = array ();
init_menu ( $db, $cat, $subcat, $menu, $rights );
function url_origin($s, $use_forwarded_host = false) {
	$ssl = (! empty ( $s ['HTTPS'] ) && $s ['HTTPS'] == 'on');
	$sp = strtolower ( $s ['SERVER_PROTOCOL'] );
	$protocol = substr ( $sp, 0, strpos ( $sp, '/' ) ) . (($ssl) ? 's' : '');
	$port = $s ['SERVER_PORT'];
	$port = ((! $ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
	$host = ($use_forwarded_host && isset ( $s ['HTTP_X_FORWARDED_HOST'] )) ? $s ['HTTP_X_FORWARDED_HOST'] : (isset ( $s ['HTTP_HOST'] ) ? $s ['HTTP_HOST'] : null);
	$host = isset ( $host ) ? $host : $s ['SERVER_NAME'] . $port;
	return $protocol . '://' . $host;
}
function full_url($s, $use_forwarded_host = false) {
	return url_origin ( $s, $use_forwarded_host ) . $s ['REQUEST_URI'];
}
function go_to_old_site($s) {
	$absolute_url = full_url($s);
	
	$url = str_replace ( 'main', '', str_replace ( 'index.php', '', $absolute_url ) );
	return $url;
}
?>

<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span> 
				<span class="icon-bar"></span> 
				<span class="icon-bar"></span> 
				<span class="icon-bar"></span>
			</button>
			<a href="#menu-toggle" class="navbar-brand" id="menu-toggle" title='Меню' rel="tooltip" data-placement="bottom">
				<i class='glyphicon glyphicon-th-list'></i>
			</a>
			<a class="navbar-brand" href='' onclick="callPage(<?php echo "'dashboard.php', '','',''"; ?>)" title='Рабочий стол' rel="tooltip" data-placement="bottom">
				<i class='glyphicon glyphicon-home'></i>
			</a>
			<span class="navbar-brand padding_left_30">
				<a href='index.php'>Главная (теперь работает)</a>
			</span>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right ">
				<li>
					<a href="<?php echo go_to_old_site($_SERVER); ?>" rel="tooltip" target='_blank' title='На старый сайт' data-placement="bottom">
						<i class='glyphicon glyphicon-globe'></i>
						<span class='visible-xs-inline'>На старый сайт</span>
					</a>
				</li>
				<li class='dropdown '>
					<a href="#" class='dropdown-toggle' data-toggle='dropdown' title='Быстрый доступ' rel="tooltip" data-placement="bottom">
						<i class='glyphicon glyphicon-collapse-down'></i>
						<span class='visible-xs-inline'>Быстрый доступ</span>
					</a>
					<ul class='dropdown-menu'>
            			<?php build_fast_menu ( $cat, $subcat, $menu, $rights, $db ); ?>
            		</ul>
            	</li>   
				<li class='dropdown '>
					<a href='#' class='dropdown-toggle navbar-title' data-toggle='dropdown' title='Нажмите для выхода' rel="tooltip" data-placement="bottom">
						<i class='glyphicon glyphicon-user'></i>
						&nbsp;&nbsp;<?php echo $_SESSION['CUR_USER_FIO'] ?>
					</a>
					<ul class='dropdown-menu'>
						<li>
							<a class='navbar-main navbar-title-dropdown' href='index.php?logout=0'>
								<i class='glyphicon glyphicon-off'></i>
								&nbsp;&nbsp;Выход
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>


