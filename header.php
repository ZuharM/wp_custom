<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<title><?php is_front_page() ? bloginfo('name') : wp_title(''); ?></title>
		<meta http-equiv="Content-language" content="<?php bloginfo('language'); ?>" />
		<meta name="viewport" content="width=device-width" />
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="shortcut icon" href="<?php echo (get_option('favicon')) ? get_option('favicon') : get_template_directory_uri().'/images/icon.png' ?>" type="image/x-icon" />
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/owl.carousel.min.css">
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/owl.theme.default.min.css">
		<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/js/jquery.min.js'></script>
		<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/js/newstickers.js'></script>
		<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/js/jquery.accordion.js'></script>
		<?php wp_head(); ?>
		<script>
        $(function(){
           $("ul#kampus-ticker").liScroll();
        });
        </script>
		<script type="text/javascript">
	    	$(document).ready(function () {
	    		$('ul').accordion();
	     	});
		</script>
		<script type="text/javascript">
    		$("document").ready(function($){
	    		$(".accordion").slideUp();
				$(".open").click(function(){
		    		$(".accordion").slideToggle();
				});
			});
		</script>
		
		<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo (get_option('apikey')) ? get_option('apikey').'' : '' ?>" type="text/javascript"></script>
        <script>
    		var myCenter=new google.maps.LatLng(<?php echo (get_option('maps')) ? get_option('maps').'' : '-5.932330,105.992419' ?>);
			function initialize() {
	    		var mapProp = {
				    center:myCenter,
					zoom:15,
					mapTypeId:google.maps.MapTypeId.ROADMAP
				};
				var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
				var marker=new google.maps.Marker({
			    	position:myCenter,
					title: 'Click to zoom',
					icon:"<?php echo get_template_directory_uri(); ?>/images/maps.png"
				});
			marker.setMap(map);
			}
			google.maps.event.addDomListener(window, 'load', initialize);
		</script>
		
	</head>
	
	<!-- WP Masjid, tema wordpress untuk website masjid -->
	
	<body>
		<div class="wrapper">
		    
			<!-- NEWS TICKER -->
		    <div class="tickercontainer">
				<span class="rleft">
					<div class="sein"><?php echo (get_option('sekilas')) ? get_option('sekilas') : 'SEKILAS INFO' ?></div>
				</span>
		        <div class="mask">
	    	        <?php query_posts('post_type=sekilas-info&showposts=3'); ?>
				    	<?php if (have_posts()): ?>
	    	                <ul id="kampus-ticker" class="newstickers">
	    	                	<?php while (have_posts()): the_post(); ?>
	                                <li><em><?php the_time(); ?></em> - <?php if (function_exists('smart_excerpt')) smart_excerpt(get_the_excerpt(), 55); ?></li>
	     	                	<?php endwhile; ?>
                            </ul>
	    		        <?php endif; ?>
					<?php wp_reset_query(); ?>
	    		</div>
				<span class="rright">
					<div class="wain"><?php echo (get_option('waktu')) ? get_option('waktu') : 'WAKTU' ?> <span id="time"></span>:<span id="minu"></span> <span id="secs"></span></div>
				</span>
	    	</div>
			<!-- NEWS TICKER -->
			
			<!-- HEADER -->
			<div class="header clear">
			
				<div class="logo">
					<a href="<?php echo home_url(); ?>"><img src="<?php echo (get_option('logos')) ? get_option('logos') : get_template_directory_uri().'/images/logo.png' ?>" alt="<?php bloginfo('name'); ?>"/></a>
				</div>
				<div class="timeinfo">
				    <div class="info">
					
					    <div class="ws clear">
						    <?php /*<h3><span>WAKTU SHALAT, </span><em><?php echo date_i18n('l, j-m-Y'); ?></em> <a href="<?php echo home_url(); ?>/jadwal-shalat/<?php echo strtolower(date_i18n('F-Y')); ?>/"><?php echo date_i18n('F Y'); ?> ></a></h3>*/
						    //Custom By Zuhar ~Lensakom
						    function hari_ini(){
								$hari = date_i18n('D');
								switch($hari){
									case 'Sun': $hari_ini = "Minggu"; break;
									case 'Mon':	$hari_ini = "Senin"; break;
									case 'Tue':	$hari_ini = "Selasa"; break;
									case 'Wed': $hari_ini = "Rabu"; break;
									case 'Thu': $hari_ini = "Kamis"; break;
									case 'Fri':	$hari_ini = "Jumat"; break;
									case 'Sat': $hari_ini = "Sabtu"; break;
									default: $hari_ini = "Tidak di ketahui"; break;
								}
								return $hari_ini;
							}
							//Find city code with search for "Prayer Times Jakarta site:muslimpro.com" in google
							//Code city at the last string of the url
							$curl = curl_init();
							curl_setopt_array($curl, array(
							  CURLOPT_URL => "https://www.muslimpro.com/muslimprowidget.js?cityid=1215502",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_TIMEOUT => 30,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "GET",
							  CURLOPT_HTTPHEADER => array(
							    "cache-control: no-cache"
							  ),
							));
							$response = curl_exec($curl);
							$err = curl_error($curl);
							function extractString($string, $start, $end){
							    $string = " ".$string;
							    $ini = strpos($string, $start);
							    if ($ini == 0) return "";
							    $ini += strlen($start);
							    $len = strpos($string, $end, $ini) - $ini;
							    return substr($string, $ini, $len);
							}
							$City =  extractString($response, "Prayer times in ", "</a>");
							$Fajr =  extractString($response, "Fajr", "';body");
							$Sunrise =  extractString($response, "Sunrise", "';body");
							$Dhuhr =  extractString($response, "Dhuhr", "';body");
							$Asr =  extractString($response, "Asr", "';body");
							$Maghrib =  extractString($response, "Maghrib", "';body");
							$Ishaa =  extractString($response, "Isha&#39;a", "';body"); ?>
						    <h3><span>WAKTU SHALAT </span><em><?php echo hari_ini().', '.date_i18n('d-m-Y'); ?></em><a href="<?php echo home_url(); ?>"><?php echo $City; ?></a></h3>
							<?php /*$bulan = strtoupper(date_i18n("F-Y"));
						     	  query_posts('post_type=jadwal-shalat&showposts=1&nama='.$bulan.''); ?>
					        		<?php if (have_posts()) { ?>
			    	        		<?php while (have_posts()): the_post(); ?>
					        		<?php global $post;
	                                 	  $sekarang = date_i18n("j F Y");
										  $repeatable_fields = get_post_meta($post->ID, 'repeatable_fields', true);
									?>
		
				        	        <?php 
					        		    $count = 0;
					        			$tang = date_i18n("j");
					        		    foreach ( $repeatable_fields as $field ) {
					        			$count++;	
									?>
					        	        <?php if ($count == $tang) { */?>
					        			<div class="clear">
					        		    	<div class="subuh jws">
					        		        	<div class="jws-in">
					        		        		<span>TER<span class="jh">BIT</span></span> <?php echo $Sunrise; //esc_attr( $field['imsyaks'] ); ?>
					        			    	</div> 
					        		    	</div>
					        		    	<div class="subuh jws">
					        		        	<div class="jws-in">
					        		    	    	<span>SUB<span class="jh">UH</span></span> <?php echo $Fajr; //esc_attr( $field['subuhs'] ); ?>
					        			    	</div> 
					        		    	</div>
						    				<div class="dzuhur jws">
						    			    	<div class="jws-in">
						    			    		<span>DZU<span class="jh">HUR</span></span> <?php echo $Dhuhr; //esc_attr( $field['dzuhurs'] ); ?>
							    				</div>
							    			</div>
							    			<div class="ashar jws">
							    		    	<div class="jws-in">
							    		    		<span>ASH<span class="jh">AR</span></span> <?php echo $Asr; //esc_attr( $field['ashars'] ); ?>
							    				</div>
							    			</div>
							    			<div class="maghrib jws">
							    		    	<div class="jws-in">
							    		    		<span>MAG<span class="jh">HRIB</span></span> <?php echo $Maghrib; //esc_attr( $field['maghribs'] ); ?>
							    				</div>
							    			</div>
								    		<div class="isya jws">
								    	    	<div class="jws-in">
								    	    		<span>ISYA</span> <?php echo $Ishaa; //esc_attr( $field['isyas'] ); ?>
								    			</div>
							    			</div>
							    		</div>
							
									<?php /* } ?>
						        	<?php } ?>
						        	<?php endwhile; ?>
						        	<?php } ?>
							
							<?php wp_reset_query();*/?>
						</div>
						
					</div>
				</div>
			    
			</div>
			<!-- HEADER -->
			
			<!-- NAVIGASI -->
			<div class="nav-ndeso">
			        <div class="nav-inn clear">
				        <div class="open">
				    	</div>
			        	<div class="inn">
		    	    		<?php wp_nav_menu(array('theme_location' => 'navigation', 'container' => 'div', 'container_class' => 'nav', 'menu_class' => 'dd', 'menu_id' => 'dd', 'fallback_cb' => false)); ?>
		    	    	</div>
						<div class="ndsearch">
			            	<?php get_search_form(); ?>
		        		</div>
			    	</div>
			    	<div class="mob">
		    			<?php wp_nav_menu(array('theme_location' => 'navigation', 'container' => 'div', 'container_class' => 'mobi', 'menu_class' => 'accordion', 'menu_id' => 'acc', 'fallback_cb' => false)); ?>
		        	</div>
			</div>
			<!-- NAVIGASI -->


			<?php if (is_front_page()) { ?>
		    	<div id="ndeslide">
	    	    	<?php get_template_part('homeslide'); ?>	
		    	</div>
			<?php } ?>
			
			<?php dimox_breadcrumbs(); ?>
			
			<!-- Container -->
			<div id="container" class="clear">
				<!-- Content -->
				<div id="content">
