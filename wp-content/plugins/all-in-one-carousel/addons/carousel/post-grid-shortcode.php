<?php
function svc_carousel_layout_shortcode($attr,$content=null){
	extract(shortcode_atts( array(
		'title' => '',
		'svc_type' => 'post_layout',
		'query_loop' => '',
		'skin_type' => 's1',
		'images_car' => '',
		'video_car' => '',
		'video_height' => '315',
		'svc_teasr_carousel_layout' => '',
		'image_view' => 'square',
		'car_display_item' => '4',
		'car_desktop_display_item' => '4',
		'car_desktopsmall_display_item' => '3',
		'car_tablet_display_item' => '2',
		'car_mobile_display_item' => '1',
		'car_pagination' => '',
		'car_pagination_num' => '',
		'car_navigation' => '',
		'car_autoplay' => '',
		'car_autoplay_time' => '5',
		'lazyload' => '',
		'synced' => '',
		'synced_display' => '10',
		'loadmore' => '',
		'car_margin' => '10',
		'car_transition' => '',
		'grid_link_target' => 'sw',
		'exclude_texo' => '',
		'grid_thumb_size' => 'full',
		's5_min_height' => '150',
		'svc_excerpt_length' => '20',
		'read_more' => '',
		'loadmore_text' => '',
		'svc_class' => '',
		'dexcerpt' => '',
		'dcategory' => '',
		'dmeta_data' => '',
		'dsocial' => '',
		'pbgcolor' => '',
		'pbghcolor' => '',
		'line_color' => '',
		'tcolor' => '',
		'thcolor' => '',
		'car_navigation_color' => '',
		'paged' => '1',
		'svc_grid_id' => '',
		'ajax' => '0'
	), $attr));

	wp_register_style( 'svc-carousel-css', plugins_url('css/css.css', __FILE__));
	wp_enqueue_style( 'svc-carousel-css');
	wp_enqueue_style( 'svc-bootstrap-css' );
	//wp_enqueue_style( 'svc-megnific-css' );
	//wp_enqueue_style( 'svc-animate-css');
	
	wp_enqueue_script('svc-imagesloaded-js');
	//wp_enqueue_script('svc-isotop-js');
	//wp_enqueue_script('svc-script-js');
	//wp_enqueue_script('svc-megnific-js');
	//wp_enqueue_script('svc-ddslick-js');
	wp_enqueue_script('svc-carousel-js');

if($svc_type == 'post_layout'){
	$var = get_defined_vars();
	$loop=$query_loop;
	$posts = array();
	if(empty($loop)) return;

	//$paged = 1;
	$query=$query_loop;
	$query=explode('|',$query);
	
	$query_posts_per_page=10;
	$query_post_type='post';
	$query_meta_key='';
	$query_orderby='date';
	$query_order='ASC';
	
	$query_by_id='';
	$query_by_id_not_in='';
	$query_by_id_in='';
	
	$query_categories='';
	$query_cat_not_in='';
	$query_cat_in='';

	$query_tags='';
	$query_tags_in='';
	$query_tags_not_in='';
	
	$query_author='';
	$query_author_in='';
	$query_author_not_in='';
	
	$query_tax_query='';
	
	foreach($query as $query_part)
	{
		$q_part=explode(':',$query_part);
		switch($q_part[0])
		{
			case 'post_type':
				$query_post_type=explode(',',$q_part[1]);
			break;
			
			case 'size':
				$query_posts_per_page=($q_part[1]=='All' ? -1:$q_part[1]);
			break;
			
			case 'order_by':
				
				$query_meta_key='';
				$query_orderby='';
				
				$public_orders_array=array('ID','date','author','title','modified','rand','comment_count','menu_order');
				if(in_array($q_part[1],$public_orders_array))
				{
					$query_orderby=$q_part[1];
				}else
				{
					$query_meta_key=$q_part[1];
					$query_orderby='meta_value_num';
				}
				
			break;
			
			case 'order':
				$query_order=$q_part[1];
			break;
			
			case 'by_id':
				$query_by_id=explode(',',$q_part[1]);
				$query_by_id_not_in=array();
				$query_by_id_in=array();
				foreach($query_by_id as $ids)
				{
					if($ids<0)
					{
						$query_by_id_not_in[]=$ids;
					}else{
						$query_by_id_in[]=$ids;
					}
				}
			break;
			
			case 'categories':
				$query_categories=explode(',',$q_part[1]);
				$query_cat_not_in=array();
				$query_cat_in=array();
				foreach($query_categories as $cat)
				{
					if($cat<0)
					{
						$query_cat_not_in[]=$cat;
					}else
					{
						$query_cat_in[]=$cat;
					}
				}
			break;
			
			case 'tags':
				$query_tags=explode(',',$q_part[1]);
				$query_tags_not_in=array();
				$query_tags_in=array();
				foreach($query_tags as $tags)
				{
					if($tags<0)
					{
						$query_tags_not_in[]=$tags;
					}else
					{
						$query_tags_in[]=$tags;
					}
				}
			break;
			
			case 'authors':
				$query_author=explode(',',$q_part[1]);
				$query_author_not_in=array();
				$query_author_in=array();
				foreach($query_author as $author)
				{
					if($tags<0)
					{
						$query_author_not_in[]=$author;
					}else
					{
						$query_author_in[]=$author;
					}
				}
				
			break;

			case 'tax_query':
				$all_tax=get_object_taxonomies( $query_post_type );

				$tax_query=array();
				$query_tax_query=array('relation' => 'AND');
				foreach ( $all_tax as $tax ) {
					$values=$tax;
					$query_taxs_in=array();
					$query_taxs_not_in=array();
					
					$query_taxs=explode(',',$q_part[1]);
					foreach($query_taxs as $taxs)
					{
						if(term_exists( absint($taxs), $tax )){
							if($taxs<0)
							{
								$query_taxs_not_in[]=absint($taxs);
							}else
							{
								$query_taxs_in[]=$taxs;
							}
						}
					}

					if(count($query_taxs_not_in)>0)
					{
						$query_tax_query[]=array(
							'taxonomy' => $tax,
							'field'    => 'id',
							'terms'    => $query_taxs_not_in,
							'operator' => 'NOT IN',
						);
					}else if(count($query_taxs_in)>0)
					{
						$query_tax_query[]=array(
							'taxonomy' => $tax,
							'field'    => 'id',
							'terms'    => $query_taxs_in,
							'operator' => 'IN',
						);
					}
					
					break;
				}
				
			//break;
		}
	}

	$query_final=array(
		'post_type' => $query_post_type,
		'post_status'=>'publish',
		'posts_per_page'=>$query_posts_per_page,
		'meta_key' => $query_meta_key,
		'orderby' => $query_orderby,
		'order' => $query_order,
		'paged'=>$paged,
		
		'post__in'=>$query_by_id_in,
		'post__not_in'=>$query_by_id_not_in,
		
		'category__in'=>$query_cat_in,
		'category__not_in'=>$query_cat_not_in,
		
		'tag__in'=>$query_tags_in,
		'tag__not_in'=>$query_tags_not_in,
		
		'author__in'=>$query_author_in,
		'author__not_in'=>$query_author_not_in,
		
		'tax_query'=>$query_tax_query
	 );

	$exclude_texo_array = explode(',',$exclude_texo);
	$my_query = new WP_Query($query_final);	
	if(!$ajax){
		$svc_grid_id = rand(50,5000);
	}
	$var['svc_grid_id'] = $svc_grid_id;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	ob_start();
	if(!$ajax){
	?>
	<style type="text/css">
	div.svc_post_grid_<?php echo $svc_grid_id;?> article{ background:<?php echo $pbgcolor;?> !important;}
	div.svc_post_grid_<?php echo $svc_grid_id;?> article:hover{ background:<?php echo $pbghcolor;?> !important;}
	<?php if($skin_type == 's1' && $line_color != ''){?>
	div.svc_post_grid_<?php echo $svc_grid_id;?> article header{border-bottom: 3px solid <?php echo $line_color;?> !important;}
	<?php }
	if($skin_type == 's2' && $line_color != ''){?>
	div.svc_post_grid_<?php echo $svc_grid_id;?> article{border-bottom: 3px solid <?php echo $line_color;?> !important;}
	<?php }
	if($skin_type == 's4' && $line_color != ''){?>
	div.svc_post_grid_<?php echo $svc_grid_id;?> article{border-top: 5px solid <?php echo $line_color;?> !important;}
	<?php }
	if($skin_type == 's1' || $skin_type == 's2' || $skin_type == 's4' || $skin_type == 's5'){?>
	div.svc_post_grid_<?php echo $svc_grid_id;?> article section p a.svc_title{color:<?php echo $tcolor;?> !important;}
	div.svc_post_grid_<?php echo $svc_grid_id;?> article section p a.svc_title:hover{color:<?php echo $thcolor;?> !important;}
	<?php }
	if($skin_type == 's5' && $s5_min_height!=''){?>
	div.svc_post_grid_<?php echo $svc_grid_id;?> article .relative_div{ min-height:<?php echo $s5_min_height;?>px !important;}
	<?php }
	if($skin_type == 's5' && $pbghcolor!=''){?>
	div.svc_post_grid_<?php echo $svc_grid_id;?> article:hover{ border-bottom:5px solid <?php echo $pbghcolor;?> !important;}
	<?php }
	if($skin_type == 's5' && $pbgcolor!=''){?>
	div.svc_post_grid_<?php echo $svc_grid_id;?> article{ border-bottom:5px solid <?php echo $pbgcolor;?> !important;}
	<?php }
	if($skin_type == 's3' || $skin_type == 's6'){?>
	div.svc_post_grid_<?php echo $svc_grid_id;?> article header p a.svc_title{color:<?php echo $tcolor;?> !important;}
	div.svc_post_grid_<?php echo $svc_grid_id;?> article header p a.svc_title:hover{color:<?php echo $thcolor;?> !important;}
	<?php }
	if($car_navigation_color != ''){?>
	.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-controls .owl-buttons div,.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-controls .owl-page span{ background:<?php echo $car_navigation_color;?> !important;}
	.svc_carousel2_container_<?php echo $svc_grid_id;?> .owl-item.synced:after{border-bottom: 8px solid <?php echo $car_navigation_color;?> !important;}
	.svc_carousel2_container_<?php echo $svc_grid_id;?> .owl-item.synced:before {border-bottom: 3px solid <?php echo $car_navigation_color;?> !important;}
		<?php if($loadmore == 'yes'){?>
		nav.svc_infinite_<?php echo $svc_grid_id;?> div.loading-spinner .ui-spinner .side .fill{ background:<?php echo $car_navigation_color;?> !important;}
		<?php }
	}?>
	</style>
	<div class="svc_post_grid_list <?php echo $svc_class;?>">
	<?php if($title != ''){?>
	<div class="svc_grid_title"><?php echo $title;?></div>
	<?php }?>
	<div class="svc_mask <?php echo $svc_class;?>" id="svc_mask_<?php echo $svc_grid_id;?>">
		<div id="loader"></div>
	</div>

	<div class="svc_post_grid_list_container <?php echo $svc_class;?>" id="svc_post_grid_list_container_<?php echo $svc_grid_id;?>">
	
	<div class="svc_post_grid svc_post_grid_<?php echo $skin_type;?> svc_post_grid_<?php echo $svc_grid_id;?> svc_carousel_container_<?php echo $svc_grid_id;?> <?php echo $svc_class;?>" id="svc_carousel_container_<?php echo $svc_grid_id;?>">
		<?php 
		$grid_columns_count_for_desktop = $grid_columns_count_for_tablet = $grid_columns_count_for_mobile = '';
	}
	
	$link_target = $grid_link_target;
	$lt = '';
	if($link_target == 'sw'){
		$lt = 'target="_self"';
	}elseif($link_target == 'nw'){
		$lt = 'target="_blank"';
	}
	$img_array = array();
	while ( $my_query->have_posts() ) {
		$my_query->the_post(); // Get post from query
		$post = new stdClass(); // Creating post object.
		$post->id = get_the_ID();
		$post->link = get_permalink($post->id);
		$img_id=get_post_meta( $post->id , '_thumbnail_id' ,true );
		$img_array[] = $img_id;
		
		$post_thumbnail = wpb_getImageBySize(array( 'post_id' => $post->id, 'thumb_size' => $grid_thumb_size ));
		$current_img_large = $post_thumbnail['thumbnail'];
		//$current_img_full = wp_get_attachment_image_src( $img_id[$img_counter++] , 'full' );
		
		$post_type = get_post_type( $post->id );
		$post_taxonomies = get_object_taxonomies($post_type);
		//echo "<pre>";print_r($post_taxonomies);
		for($i = 0;$i < count($post_taxonomies); $i++){
			if($post_taxonomies[$i] == 'post_format'){
				unset($post_taxonomies[$i]);
			}
		}
		$tax_counter = 0;

		if ($skin_type=='s1'){
			$filter_class = '';
			foreach ($post_taxonomies as $taxonomy){
				$khj = get_the_terms( $post->id, $taxonomy );
				if (!empty( $khj )){
					foreach( $khj as $term_m ){
						$filter_class .= 'svc-grid-cat-'.$term_m->term_id.' ';
					}
				}
			}?>
		<article class="<?php echo $grid_columns_count_for_desktop.' '.$grid_columns_count_for_tablet.' '.$grid_columns_count_for_mobile.' '.$filter_class;?>">
		<?php if($img_id != ''){?>
		  <header>
			<a href="<?php echo $post->link;?>" <?php echo $lt;?>>
				<?php echo wp_get_attachment_image( $img_id, $grid_thumb_size,false,array('class' => 'svc_post_image') );?>
			</a>			
		  </header>
		<?php }?>
		  <section>
			<p><a href="<?php echo $post->link;?>" <?php echo $lt;?> class="svc_title"><?php echo get_the_title();?></a></p>
			<?php
			if($dcategory != 'yes'){
			$tax_co= 0;
			$output = '';
			foreach ($post_taxonomies as $taxonomy){
				if($taxonomy != 'post_tag'){
					$terms = get_the_terms( $post_id, $taxonomy );
					if ( (!empty( $terms )) && ($tax_co<=1) ) {
						if ($tax_co==0){
							$output .='<div class="svc_post_cat">
										<i class="fa fa-tags"></i> ';
						}
						foreach ( $terms as $term ){
							if($tax_co>0){$output .= ', ';}
							$output .= '<a href="' .get_term_link($term->slug, $taxonomy) .'">'.$term->name.'</a>';
							$tax_co++;
						}
					}
				}
			}
			if($tax_co!= 0 ){
				$output.='</div>';
			}
			echo $output;
			}
			if($dexcerpt != 'yes'){?>
			<p class="svc_info"><?php echo svc_carousel_layout_excerpt(get_the_excerpt(),$svc_excerpt_length);?></p>
			<?php }?>
		  </section>
		  <footer>
			<div>
			 <?php if($dmeta_data != 'yes'){?>
			  <ul class="svg_post_meta">
				<li class="time">
					<span><i class="fa fa-calendar-o"></i> <?php echo get_the_date();?>&nbsp;&nbsp;</span>
					<span><i class="fa fa-pencil"></i> <?php the_author();?>&nbsp;&nbsp;</span>
					<span><a class="fa fa-comments-o" href="<?php echo get_comments_link();?>"> <?php echo get_comments_number( '0', '1', '% responses' );?></a></span>
				</li>
			  </ul>
			 <?php }
			 if($dsocial != 'yes'){?>
			  <div class="svc_social_share">
				<ul>
				  <li><a href="https://twitter.com/intent/tweet?text=&url=<?php echo $post->link?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
				  <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post->link?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
				  <li><a href="https://plusone.google.com/share?url=<?php echo $post->link?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
				</ul>
			  </div>
			  <?php }?>
			  <a href="<?php echo $post->link;?>" class="svc_read_more" <?php echo $lt;?>>
              	<?php if($read_more == ''){ _e('Read More','svc_carousel');}else{ _e($read_more,'svc_carousel');}?>&nbsp;<i class="fa fa-angle-double-right"></i></a>
			</div>
		  </footer>
		</article>
	<?php
		}
		if ($skin_type=='s2'){
			$filter_class = '';
			foreach ($post_taxonomies as $taxonomy){
				$khj = get_the_terms( $post->id, $taxonomy );
				if (!empty( $khj )){
					foreach( $khj as $term_m ){
						$filter_class .= 'svc-grid-cat-'.$term_m->term_id.' ';
					}
				}
			}?>
		<article class="element-item <?php echo $grid_columns_count_for_desktop.' '.$grid_columns_count_for_tablet.' '.$grid_columns_count_for_mobile.' '.$filter_class;?>" svc-animation="<?php if($effect != ''){ echo $effect;}?>" sort="<?php echo $order_value;?>">
			<section>
			<p><a href="<?php echo $post->link;?>" <?php echo $lt;?> class="svc_title"><?php echo get_the_title();?></a></p>
		  </section>
		<?php if($img_id != ''){?>
		  <header>
			<a href="<?php echo $post->link;?>" <?php echo $lt;?>>
				<?php echo wp_get_attachment_image( $img_id, $grid_thumb_size,false,array('class' => 'svc_post_image') );?>
			</a>
		  </header>
		<?php }?>
		<section>
		<?php
		if($dcategory != 'yes'){
			$tax_co= 0;
			$output = '';
			foreach ($post_taxonomies as $taxonomy){
				if($taxonomy != 'post_tag'){
				$terms = get_the_terms( $post->id, $taxonomy );
					if ( (!empty( $terms )) && ($tax_co<=1) ) {
						if ($tax_co==0){
							$output .='<div class="svc_post_cat">
										<i class="fa fa-tags"></i> ';
						}
						foreach ( $terms as $term ){
							if($tax_co>0){$output .= ', ';}
							$output .= '<a href="' .get_term_link($term->slug, $taxonomy) .'">'.$term->name.'</a>';
							$tax_co++;
						}
					}
				}
			}
			if($tax_co!= 0 ){
				$output.='</div>';
			}
			echo $output;
			}
			if($dexcerpt != 'yes'){?>
			<p class="svc_info"><?php echo svc_carousel_layout_excerpt(get_the_excerpt(),$svc_excerpt_length);?></p>
			<?php }?>
			</section>
		  <footer>
			<div>
			<?php if($dmeta_data != 'yes'){?>
			  <ul class="svg_post_meta">
				<li class="time">
					<span><i class="fa fa-calendar-o"></i> <?php echo get_the_date();?>&nbsp;&nbsp;</span>
					<span><i class="fa fa-pencil"></i> <?php the_author();?>&nbsp;&nbsp;</span>
					<span><a class="fa fa-comments-o" href="<?php echo get_comments_link();?>"> <?php echo get_comments_number( '0', '1', '% responses' );?></a></span>
				</li>
			  </ul>
			 <?php }
			 if($dsocial != 'yes'){?>
			  <div class="svc_social_share">
				<ul>
				  <li><a href="https://twitter.com/intent/tweet?text=&url=<?php echo $post->link?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
				  <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post->link?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
				  <li><a href="https://plusone.google.com/share?url=<?php echo $post->link?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
				</ul>
			  </div>
			  <?php }?>
			  <a href="<?php echo $post->link;?>" class="svc_read_more" <?php echo $lt;?>>
              <?php if($read_more == ''){ _e('Read More','svc_carousel');}else{ _e($read_more,'svc_carousel');}?>&nbsp;<i class="fa fa-angle-double-right"></i></a>
			</div>
		  </footer>
		</article>
	<?php
		}
		if ($skin_type=='s3'){
			$filter_class = '';
			foreach ($post_taxonomies as $taxonomy){
				$khj = get_the_terms( $post->id, $taxonomy );
				if (!empty( $khj )){
					foreach( $khj as $term_m ){
						$filter_class .= 'svc-grid-cat-'.$term_m->term_id.' ';
					}
				}
			}?>
		<article class="element-item <?php echo $grid_columns_count_for_desktop.' '.$grid_columns_count_for_tablet.' '.$grid_columns_count_for_mobile.' '.$filter_class;?>" svc-animation="<?php if($effect != ''){ echo $effect;}?>" sort="<?php echo $order_value;?>">
		  <header>
          <?php if($img_id != ''){?>
          	<div class="svc-col-md-4 svc-col-sm-4 svc-col-xs-4 svc_tac">
                <a href="<?php echo $post->link;?>" <?php echo $lt;?>>
                    <?php echo wp_get_attachment_image( $img_id, $grid_thumb_size,false,array('class' => 'svc_post_image') );?>
                </a>
            </div>
         <?php }
		 if($img_id == ''){?>
         	<div class="svc-col-md-12 svc-col-sm-12 svc-col-xs-12" style="width:100%;">
         <?php }else{?>
            <div class="svc-col-md-8 svc-col-sm-8 svc-col-xs-8" style="padding-left:10px;">
         <?php }?>
            	<p><a href="<?php echo $post->link;?>" <?php echo $lt;?> class="svc_title"><?php echo get_the_title();?></a></p>
                <?php if($dexcerpt != 'yes'){?>
                <p class="svc_info"><?php echo svc_carousel_layout_excerpt(get_the_excerpt(),$svc_excerpt_length);?></p>
                <?php }
				if($dcategory != 'yes'){
					$tax_co= 0;
					$output = '';
					foreach ($post_taxonomies as $taxonomy){
						if($taxonomy != 'post_tag'){
							$terms = get_the_terms( $post->id, $taxonomy );
							if ( (!empty( $terms )) && ($tax_co<=1) ) {
								if ($tax_co==0){
									$output .='<div class="svc_post_cat">
												<i class="fa fa-tags"></i> ';
								}
								foreach ( $terms as $term ){
									if($tax_co>0){$output .= ', ';}
									$output .= '<a href="' .get_term_link($term->slug, $taxonomy) .'">'.$term->name.'</a>';
									$tax_co++;
								}
							}
						}
					}
					if($tax_co!= 0 ){ $output.='</div>';}
					echo $output;
					}
				?>
            </div>
		  </header>
		  <footer>
			<div>
			<?php if($dmeta_data != 'yes'){?>
			  <ul class="svg_post_meta">
				<li class="time">
					<span><i class="fa fa-calendar-o"></i> <?php echo get_the_date();?>&nbsp;&nbsp;</span>
					<span><i class="fa fa-pencil"></i> <?php the_author();?>&nbsp;&nbsp;</span>
					<span><a class="fa fa-comments-o" href="<?php echo get_comments_link();?>"> <?php echo get_comments_number( '0', '1', '% responses' );?></a></span> 
				</li>
			  </ul>
			 <?php }
			 if($dsocial != 'yes'){?>
			  <div class="svc_social_share">
				<ul>
				  <li><a href="https://twitter.com/intent/tweet?text=&url=<?php echo $post->link?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
				  <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post->link?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
				  <li><a href="https://plusone.google.com/share?url=<?php echo $post->link?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
				</ul>
			  </div>
			  <?php }?>
			  <a href="<?php echo $post->link;?>" class="svc_read_more" <?php echo $lt;?>>
              <?php if($read_more == ''){ _e('Read More','svc_carousel');}else{ _e($read_more,'svc_carousel');}?>&nbsp;<i class="fa fa-angle-double-right"></i></a>
			</div>
		  </footer>
		</article>
	<?php
		}
		if ($skin_type=='s4'){
			$filter_class = '';
			foreach ($post_taxonomies as $taxonomy){
				$khj = get_the_terms( $post->id, $taxonomy );
				if (!empty( $khj )){
					foreach( $khj as $term_m ){
						$filter_class .= 'svc-grid-cat-'.$term_m->term_id.' ';
					}
				}
			}?>
		<article class="element-item <?php echo $grid_columns_count_for_desktop.' '.$grid_columns_count_for_tablet.' '.$grid_columns_count_for_mobile.' '.$filter_class;?>" svc-animation="<?php if($effect != ''){ echo $effect;}?>" sort="<?php echo $order_value;?>">
		<?php if($img_id != ''){?>
		  <header>
			<a href="<?php echo $post->link;?>" <?php echo $lt;?>>
				<?php echo wp_get_attachment_image( $img_id, $grid_thumb_size,false,array('class' => 'svc_post_image') );?>
			</a>
		  </header>
		<?php }?>
		  <section>
			<p><a href="<?php echo $post->link;?>" <?php echo $lt;?> class="svc_title"><?php echo get_the_title();?></a></p>
			<?php
			if($dcategory != 'yes'){
			$tax_co= 0;
			$output = $slash = '';
			foreach ($post_taxonomies as $taxonomy){
				if($taxonomy != 'post_tag'){
					$terms = get_the_terms( $post_id, $taxonomy );
					if ( (!empty( $terms )) && ($tax_co<=1) ) {
						if ($tax_co==0){
							$output .='<div class="svc_post_cat">';
						}
						foreach ( $terms as $term ){
							if($tax_co>0){$slash = ' / ';}
							$output .= '<a href="' .get_term_link($term->slug, $taxonomy) .'">'.$slash.$term->name.'</a>';
							$tax_co++;
						}
					}
				}
			}
			if($tax_co!= 0 ){
				$output.='</div>';
			}
			echo $output;
			}
			if($dexcerpt != 'yes'){?>
			<p class="svc_info"><?php echo svc_carousel_layout_excerpt(get_the_excerpt(),$svc_excerpt_length);?></p>
			<?php }?>
            <p class="svc_read_more_p"><a href="<?php echo $post->link;?>" class="svc_read_more" <?php echo $lt;?>>
            <?php if($read_more == ''){ _e('Read More','svc_carousel');}else{ _e($read_more,'svc_carousel');}?></a></p>
		  </section>
		<?php if($dmeta_data != 'yes' || $dsocial != 'yes'){?>
		  <footer>
			<div>
		<?php if($dmeta_data != 'yes'){?>
			  <ul class="svg_post_meta">
				<li class="time">
					<span><i class="fa fa-calendar-o"></i> <?php echo get_the_date();?>&nbsp;&nbsp;</span>
					<span><i class="fa fa-pencil"></i> <?php the_author();?></span>
				</li>
			  </ul>
		<?php }
		if($dsocial != 'yes'){?>
			  <div class="svc_social_share">
				<ul>
				  <li><a href="https://twitter.com/intent/tweet?text=&url=<?php echo $post->link?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
				  <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post->link?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
				  <li><a href="https://plusone.google.com/share?url=<?php echo $post->link?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
				</ul>
			  </div>
		<?php }?>
			</div>
		  </footer>
		<?php }?>
		</article>
	<?php
		}
		if ($skin_type=='s5'){
			$filter_class = '';
			foreach ($post_taxonomies as $taxonomy){
				$khj = get_the_terms( $post->id, $taxonomy );
				if (!empty( $khj )){
					foreach( $khj as $term_m ){
						$filter_class .= 'svc-grid-cat-'.$term_m->term_id.' ';
					}
				}
			}?>
		<article class="element-item <?php echo $grid_columns_count_for_desktop.' '.$grid_columns_count_for_tablet.' '.$grid_columns_count_for_mobile.' '.$filter_class;?>" svc-animation="<?php if($effect != ''){ echo $effect;}?>" sort="<?php echo $order_value;?>">
        <div class="relative_div">
        	<header>
		<?php if($img_id != ''){?>		  
			<a href="<?php echo $post->link;?>" <?php echo $lt;?>>
				<?php echo wp_get_attachment_image( $img_id, $grid_thumb_size,false,array('class' => 'svc_post_image') );?>
			</a>
		<?php }?>
        	</header>
		  <section>
			<p><a href="<?php echo $post->link;?>" <?php echo $lt;?> class="svc_title"><?php echo get_the_title();?></a></p>
			<?php
			if($dcategory != 'yes'){
			$tax_co= 0;
			$output = $slash = '';
			foreach ($post_taxonomies as $taxonomy){
				if($taxonomy != 'post_tag'){
					$terms = get_the_terms( $post_id, $taxonomy );
					if ( (!empty( $terms )) && ($tax_co<=1) ) {
						if ($tax_co==0){
							$output .='<div class="svc_post_cat">';
						}
						foreach ( $terms as $term ){
							if($tax_co>0){$slash = ',';}
							$output .= '<a href="' .get_term_link($term->slug, $taxonomy) .'">'.$slash.$term->name.'</a>';
							$tax_co++;
						}
					}
				}
			}
			if ($tax_co!=0){
				$output.='</div>';
			}
			echo $output;
			}
			if($dmeta_data != 'yes'){?>
            <ul class="svg_post_meta">
               <li class="time">
                  <span><i class="fa fa-calendar-o"></i> <?php echo get_the_date();?>&nbsp;&nbsp;</span>
                  <span><i class="fa fa-pencil"></i> <?php the_author();?></span>
               </li>
            </ul>
			<?php }
			if($dsocial != 'yes'){?>
            <div class="svc_social_share">
                <ul>
                  <li><a href="https://twitter.com/intent/tweet?text=&url=<?php echo $post->link?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                  <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post->link?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                  <li><a href="https://plusone.google.com/share?url=<?php echo $post->link?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                </ul>
             </div>
		  <?php }?>
		  </section>
          </div>
		</article>
	<?php
		}
		if ($skin_type=='s6'){
			$filter_class = '';
			foreach ($post_taxonomies as $taxonomy){
				$khj = get_the_terms( $post->id, $taxonomy );
				if (!empty( $khj )){
					foreach( $khj as $term_m ){
						$filter_class .= 'svc-grid-cat-'.$term_m->term_id.' ';
					}
				}
			}?>
		<article class="element-item <?php echo $filter_class;?>" svc-animation="<?php if($effect != ''){ echo $effect;}?>" sort="<?php echo $order_value;?>">
		  <header>
          <?php if($img_id != ''){?>
          	<div class="svc-col-md-4 svc-col-sm-4 svc-col-xs-4 svc_tac">
                <a href="<?php echo $post->link;?>" <?php echo $lt;?>>
                    <?php echo wp_get_attachment_image( $img_id, $grid_thumb_size,false,array('class' => 'svc_post_image') );?>
                </a>
            </div>
         <?php }
		 if($img_id == ''){?>
         	<div class="svc-col-md-12 svc-col-sm-12 svc-col-xs-12" style="width:100%;">
         <?php }else{?>
            <div class="svc-col-md-8 svc-col-sm-8 svc-col-xs-8" style="padding-left:10px;">
         <?php }?>
            	<p><a href="<?php echo $post->link;?>" <?php echo $lt;?> class="svc_title"><?php echo get_the_title();?></a></p>
                <?php if($dexcerpt != 'yes'){?>
                <p class="svc_info"><?php echo svc_carousel_layout_excerpt(get_the_excerpt(),$svc_excerpt_length);?></p>
                <?php }
				if($dcategory != 'yes'){
					$tax_co= 0;
					$output = '';
					foreach ($post_taxonomies as $taxonomy){
						if($taxonomy != 'post_tag'){
							$terms = get_the_terms( $post->id, $taxonomy );
							if ( (!empty( $terms )) && ($tax_co<=1) ) {
								if ($tax_co==0){
									$output .='<div class="svc_post_cat">
												<i class="fa fa-tags"></i> ';
								}
								foreach ( $terms as $term ){
									if($tax_co>0){$output .= ', ';}
									$output .= '<a href="' .get_term_link($term->slug, $taxonomy) .'">'.$term->name.'</a>';
									$tax_co++;
								}
							}
						}
					}
					if($tax_co!= 0 ){ $output.='</div>';}
					echo $output;
					}
				?>
                <div>
			<?php if($dmeta_data != 'yes'){?>
			  <ul class="svg_post_meta">
				<li class="time">
					<span><i class="fa fa-calendar-o"></i> <?php echo get_the_date();?>&nbsp;&nbsp;</span>
					<span><i class="fa fa-pencil"></i> <?php the_author();?>&nbsp;&nbsp;</span>
					<span><a class="fa fa-comments-o" href="<?php echo get_comments_link();?>"> <?php echo get_comments_number( '0', '1', '% responses' );?></a></span> 
				</li>
			  </ul>
			 <?php }
			 if($dsocial != 'yes'){?>
			  <div class="svc_social_share">
				<ul>
				  <li><a href="https://twitter.com/intent/tweet?text=&url=<?php echo $post->link?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
				  <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post->link?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
				  <li><a href="https://plusone.google.com/share?url=<?php echo $post->link?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
				</ul>
			  </div>
			  <?php }?>
			  <a href="<?php echo $post->link;?>" class="svc_read_more" <?php echo $lt;?>>
			  <?php if($read_more == ''){ _e('Read More','svc_carousel');}else{ _e($read_more,'svc_carousel');}?>&nbsp;<i class="fa fa-angle-double-right"></i></a>
			</div>
            </div>
		  </header>
		</article>
	<?php
		}
	}
	wp_reset_query();
	if(!$ajax){
		if($loadmore == 'yes'){?>
		<article style="min-height:100px;">
			<nav id="svc_infinite" class="svc_infinite_<?php echo $svc_grid_id;?>">
			  <div class="loading-spinner svc_carousel_loading-spinner">
				<div class="ui-spinner">
				  <span class="side side-left">
					<span class="fill"></span>
				  </span>
				  <span class="side side-right">
					<span class="fill"></span>
				  </span>
				</div>
			  </div>
			  <a href="javascript:;" class="svc_load_more_<?php echo $svc_grid_id;?> svc_carousel_loadmore" rel="<?php echo $svc_grid_id;?>"><?php if($loadmore_text != ''){ _e($loadmore_text,'svc_carousel');}else{ _e('Show More','svc_carousel');}?></a>
			</nav>
		</article>
		<?php }?>
	</div>
	<?php if($synced == 'yes' && $car_display_item == 1 && $loadmore != 'yes'){?>
	<div id="svc_carousel2_container_<?php echo $svc_grid_id;?>" class="svc_carousel2_container svc_carousel2_container_<?php echo $svc_grid_id;?>">
		<?php foreach ( $img_array as $img_arr ) {
		$img_src = wp_get_attachment_image_src( $img_arr, 'thumbnail');?>
		<div>
		<?php if($img_arr != ''){?>
			<img src="<?php echo $img_src[0];?>" class="svc_post_image"/>
		<?php }else{?>
			<img src="<?php echo plugins_url( 'css/one_pix.jpg', __FILE__ );?>">
		<?php }?>
		</div>
		<?php }?>
	</div>
	<?php }?>
	<?php 
	//if($svc_type != 'carousel'){
	$all_page_number=$my_query->max_num_pages;
	$fields='';
	$arr=$var;
	
	foreach($arr as $key => $value){
		if($key != 'paged'){
			$fields.='<input type="hidden" name="'.$key.'" value="'.$value.'" id="'.$key.'_'.$svc_grid_id.'"/>';
		}
	}
	?>
	<form id="svc_form_load_more_<?php echo $svc_grid_id;?>">
		<?php echo $fields;?>
		<input type="hidden" name="_wpnonce " value="<?php echo rand(0,100000);?>"/>
		<input type="hidden" name="paged" value="<?php echo $paged;?>" id="svc_paged_<?php echo $svc_grid_id;?>"/>
		<input type="hidden" name="total_paged" value="<?php echo $all_page_number;?>" id="svc_total_paged_<?php echo $svc_grid_id;?>"/>
	</form>
	<?php //}//end not carousel?>
	</div>
	</div>
	<script>
	jQuery(document).ready(function(){
	var sync1 = jQuery("#svc_carousel_container_<?php echo $svc_grid_id;?>");
	//var slider = jQuery("#svc_carousel_container_<?php echo $svc_grid_id;?>").imagesLoaded().done(function(){
		 sync1.owlCarousel({
			<?php if($car_autoplay == 'yes'){?>
			autoPlay: <?php echo $car_autoplay_time*1000;?>,
			<?php }?>
			items : <?php echo $car_display_item;?>,
			itemsDesktop : [1199,<?php echo $car_desktop_display_item;?>],
			itemsDesktopSmall : [979,<?php echo $car_desktopsmall_display_item;?>],
			itemsTablet : [768,<?php echo $car_tablet_display_item;?>],
			itemsMobile : [479,<?php echo $car_mobile_display_item;?>],
			pagination:<?php if($car_pagination == 'yes'){echo 'true';}else{echo 'false';}?>,
			navigation: <?php if($car_navigation == 'yes'){echo 'false';}else{echo 'true';}?>,
			<?php if($car_pagination == 'yes' && $car_pagination_num == 'yes'){?>
			paginationNumbers:true,
			<?php }
			if($car_display_item == 1 && $car_transition != ''){?>
			transitionStyle : "<?php echo $car_transition;?>",
			<?php }
			if($car_display_item == 1){?>
			autoHeight:true,
			singleItem:true,
			<?php }
			if($synced == 'yes' && $car_display_item == 1 && $loadmore != 'yes'){?>
			afterAction : svc_syncPosition_<?php echo $svc_grid_id;?>,
			responsiveRefreshRate : 200,
			<?php }?>
			 navigationText: [
				"<i class='fa fa-chevron-left icon-white'></i>",
				"<i class='fa fa-chevron-right icon-white'></i>"
			],
			afterInit:function(){
				jQuery('#svc_mask_<?php echo $svc_grid_id;?>').hide();
				jQuery('#svc_post_grid_list_container_<?php echo $svc_grid_id;?>').show();
			}
		});
		
		<?php if($loadmore == 'yes'){?>
		setTimeout(function(){
		var lh = jQuery('.svc_carousel_container_<?php echo $svc_grid_id;?> .owl-wrapper-outer').innerHeight();
		jQuery('.svc_carousel_container_<?php echo $svc_grid_id;?> .owl-wrapper-outer .owl-item:last article').css('height',(lh-15)+'px');
		},2000);
		function svc_load_more_<?php echo $svc_grid_id;?>(){
			jQuery('.svc_load_more_<?php echo $svc_grid_id;?>').click(function(e) {
				e.preventDefault();
				jQuery('nav.svc_infinite_<?php echo $svc_grid_id;?> a').css('visibility','hidden');
				jQuery('nav.svc_infinite_<?php echo $svc_grid_id;?> div.loading-spinner').show();
				jQuery('#svc_paged_<?php echo $svc_grid_id;?>').val(Number(jQuery('#svc_paged_<?php echo $svc_grid_id;?>').val())+1);
		
				var params=jQuery('#svc_form_load_more_<?php echo $svc_grid_id;?>').serialize();
				jQuery.ajax({
					type: 'POST',
					url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					data:  params+'&action=svc_layout_carousel',
					success: function(response) {
						var content = response;
						var tpg = jQuery('#svc_total_paged_<?php echo $svc_grid_id;?>').val();
						var cpg = jQuery('#svc_paged_<?php echo $svc_grid_id;?>').val();
						var ht = jQuery("#svc_carousel_container_<?php echo $svc_grid_id;?> .owl-item").last().html();
						if(content != '' && tpg != cpg){
							content = content+ht;
						}else{
							content = content;
						}
						jQuery("#svc_carousel_container_<?php echo $svc_grid_id;?>").data('owlCarousel').removeItem();
						sync1.data('owlCarousel').addItem(content);
						var owl = sync1.data('owlCarousel');
						owl.goTo((cpg-1)*<?php echo $query_posts_per_page;?>);
						jQuery('nav.svc_infinite_<?php echo $svc_grid_id;?> a').css('visibility','visible');
						jQuery('nav.svc_infinite_<?php echo $svc_grid_id;?> div.loading-spinner').hide();
						svc_load_more_<?php echo $svc_grid_id;?>();
					}
				});
			});
		}
		svc_load_more_<?php echo $svc_grid_id;?>();
		<?php }?>
		
		<?php if($synced == 'yes' && $car_display_item == 1 && $loadmore != 'yes'){?>
		var sync2 = jQuery("#svc_carousel2_container_<?php echo $svc_grid_id;?>");
		 sync2.owlCarousel({
			items : <?php echo $synced_display;?>,
			itemsDesktop : [1199,10],
			itemsDesktopSmall : [979,10],
			itemsTablet : [768,8],
			itemsMobile : [479,4],
			pagination:false,
			responsiveRefreshRate : 100,
			afterInit : function(el){
				el.find(".owl-item").eq(0).addClass("synced");
			}
		});

		function svc_syncPosition_<?php echo $svc_grid_id;?>(el){
			var current = this.currentItem;
			jQuery("#svc_carousel2_container_<?php echo $svc_grid_id;?>")
			.find(".owl-item")
			.removeClass("synced")
			.eq(current)
			.addClass("synced")
			if(jQuery("#svc_carousel2_container_<?php echo $svc_grid_id;?>").data("owlCarousel") !== undefined){
				svc_center_<?php echo $svc_grid_id;?>(current)
			}
		}
		jQuery("#svc_carousel2_container_<?php echo $svc_grid_id;?>").on("click", ".owl-item", function(e){
			e.preventDefault();
			var number = jQuery(this).data("owlItem");
			sync1.trigger("owl.goTo",number);
		});
		function svc_center_<?php echo $svc_grid_id;?>(number){
			var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
			var num = number;
			var found = false;
			for(var i in sync2visible){
				if(num === sync2visible[i]){
					var found = true;
				}
			}
			if(found===false){
				if(num>sync2visible[sync2visible.length-1]){
					sync2.trigger("owl.goTo", num - sync2visible.length+2)
				}else{
					if(num - 1 === -1){
						num = 0;
					}
				sync2.trigger("owl.goTo", num);
				}
			}else if(num === sync2visible[sync2visible.length-1]){
				sync2.trigger("owl.goTo", sync2visible[1])
			}else if(num === sync2visible[0]){
				sync2.trigger("owl.goTo", num-1)
			}
		}
		<?php }?>
	//});
	});
	</script>
<?php
	}
	$message = ob_get_clean();
	return $message;
}elseif($svc_type == 'carousel'){
	$images = explode(',',$images_car);
	$svc_grid_id = rand(50,5000);
	ob_start();?>
	<style type="text/css">
	<?php if($car_navigation_color != ''){?>
	.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-controls .owl-buttons div,.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-controls .owl-page span{ background:<?php echo $car_navigation_color;?> !important;}
	.svc_carousel2_container_<?php echo $svc_grid_id;?> .owl-item.synced:after{border-bottom: 8px solid <?php echo $car_navigation_color;?> !important;}
	.svc_carousel2_container_<?php echo $svc_grid_id;?> .owl-item.synced:before {border-bottom: 3px solid <?php echo $car_navigation_color;?> !important;}
	<?php }?>
	.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-item div{ margin:<?php echo $car_margin;?>px;}
	<?php if($image_view == 'round'){?>
	.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-item div .svc_img_view{ border-radius:50%;}
	<?php }?>
	</style>
	<div class="svc_post_grid_list <?php echo $svc_class;?>">
	<?php if($title != ''){?>
	<div class="svc_grid_title"><?php echo $title;?></div>
	<?php }?>

	<div class="svc_post_grid_list_container <?php echo $svc_class;?>" id="svc_post_grid_list_container_<?php echo $svc_grid_id;?>">
	
	<div class="svc_post_grid_<?php echo $svc_grid_id;?> svc_carousel_container_<?php echo $svc_grid_id;?> <?php echo $svc_class;?> svc_carousel_container" id="svc_carousel_container_<?php echo $svc_grid_id;?>">
		<?php foreach($images as $img){
		$img_src = wp_get_attachment_image_src( $img, $grid_thumb_size);?>
		<div>
		<?php if($lazyload == 'yes'){?>
			<img data-src="<?php echo $img_src[0];?>" class="svc_post_image svc_img_view lazyOwl"/>
		<?php }else{?>
			<img src="<?php echo $img_src[0];?>" class="svc_post_image svc_img_view"/>
		<?php }?>
		</div>
		<?php }?>
	</div>
	<?php if($synced == 'yes' && $car_display_item == 1){?>
	<div id="svc_carousel2_container_<?php echo $svc_grid_id;?>" class="svc_carousel2_container svc_carousel2_container_<?php echo $svc_grid_id;?>">
		<?php foreach($images as $img){
		$img_src = wp_get_attachment_image_src( $img, 'thumbnail');?>
		<div>
			<img src="<?php echo $img_src[0];?>" class="svc_post_image"/>
		</div>
		<?php }?>
	</div>
	<?php }?>
	</div>
	</div>
	<script>
	jQuery(document).ready(function(){
	var sync1 = jQuery("#svc_carousel_container_<?php echo $svc_grid_id;?>");
		 sync1.owlCarousel({
			<?php if($car_autoplay == 'yes'){?>
			autoPlay: <?php echo $car_autoplay_time*1000;?>,
			<?php }
			if($lazyload == 'yes'){?>
			lazyLoad : true,
			<?php }?>
			items : <?php echo $car_display_item;?>,
			itemsDesktop : [1199,<?php echo $car_desktop_display_item;?>],
			itemsDesktopSmall : [979,<?php echo $car_desktopsmall_display_item;?>],
			itemsTablet : [768,<?php echo $car_tablet_display_item;?>],
			itemsMobile : [479,<?php echo $car_mobile_display_item;?>],
			pagination:<?php if($car_pagination == 'yes'){echo 'true';}else{echo 'false';}?>,
			navigation: <?php if($car_navigation == 'yes'){echo 'false';}else{echo 'true';}?>,
			<?php if($car_pagination == 'yes' && $car_pagination_num == 'yes'){?>
			paginationNumbers:true,
			<?php }
			if($car_display_item == 1 && $car_transition != ''){?>
			transitionStyle : "<?php echo $car_transition;?>",
			<?php }
			if($car_display_item == 1){?>
			autoHeight:true,
			singleItem:true,
			<?php }
			if($synced == 'yes' && $car_display_item == 1){?>
			afterAction : svc_syncPosition_<?php echo $svc_grid_id;?>,
			responsiveRefreshRate : 200,
			<?php }?>
			 navigationText: [
				"<i class='fa fa-chevron-left icon-white'></i>",
				"<i class='fa fa-chevron-right icon-white'></i>"
			],
			afterInit:function(){
				jQuery('#svc_mask_<?php echo $svc_grid_id;?>').hide();
				jQuery('#svc_post_grid_list_container_<?php echo $svc_grid_id;?>').show();
			}
		});
		
		<?php if($synced == 'yes' && $car_display_item == 1){?>
		var sync2 = jQuery("#svc_carousel2_container_<?php echo $svc_grid_id;?>");
		 sync2.owlCarousel({
			items : <?php echo $synced_display;?>,
			itemsDesktop : [1199,10],
			itemsDesktopSmall : [979,10],
			itemsTablet : [768,8],
			itemsMobile : [479,4],
			pagination:false,
			responsiveRefreshRate : 100,
			afterInit : function(el){
				el.find(".owl-item").eq(0).addClass("synced");
			}
		});

		function svc_syncPosition_<?php echo $svc_grid_id;?>(el){
			var current = this.currentItem;
			jQuery("#svc_carousel2_container_<?php echo $svc_grid_id;?>")
			.find(".owl-item")
			.removeClass("synced")
			.eq(current)
			.addClass("synced")
			if(jQuery("#svc_carousel2_container_<?php echo $svc_grid_id;?>").data("owlCarousel") !== undefined){
				svc_center_<?php echo $svc_grid_id;?>(current)
			}
		}
		jQuery("#svc_carousel2_container_<?php echo $svc_grid_id;?>").on("click", ".owl-item", function(e){
			e.preventDefault();
			var number = jQuery(this).data("owlItem");
			sync1.trigger("owl.goTo",number);
		});
		function svc_center_<?php echo $svc_grid_id;?>(number){
			var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
			var num = number;
			var found = false;
			for(var i in sync2visible){
				if(num === sync2visible[i]){
					var found = true;
				}
			}
			if(found===false){
				if(num>sync2visible[sync2visible.length-1]){
					sync2.trigger("owl.goTo", num - sync2visible.length+2)
				}else{
					if(num - 1 === -1){
						num = 0;
					}
				sync2.trigger("owl.goTo", num);
				}
			}else if(num === sync2visible[sync2visible.length-1]){
				sync2.trigger("owl.goTo", sync2visible[1])
			}else if(num === sync2visible[0]){
				sync2.trigger("owl.goTo", num-1)
			}
		}
		<?php }?>
	});
	</script>
	<?php $message = ob_get_clean();
	return $message;
}elseif($svc_type == 'video'){
	$videos = explode(',',$video_car);
	$svc_grid_id = rand(50,5000);
	ob_start();?>
	<style type="text/css">
	<?php if($car_navigation_color != ''){?>
	.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-controls .owl-buttons div,.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-controls .owl-page span{ background:<?php echo $car_navigation_color;?> !important;}
	.svc_carousel2_container_<?php echo $svc_grid_id;?> .owl-item.synced:after{border-bottom: 8px solid <?php echo $car_navigation_color;?> !important;}
	.svc_carousel2_container_<?php echo $svc_grid_id;?> .owl-item.synced:before {border-bottom: 3px solid <?php echo $car_navigation_color;?> !important;}
	<?php }?>
	.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-item div{ margin:<?php echo $car_margin;?>px;}
	<?php if($image_view == 'round'){?>
	<?php }?>
	</style>
	<div class="svc_post_grid_list <?php echo $svc_class;?>">
	<?php if($title != ''){?>
	<div class="svc_grid_title"><?php echo $title;?></div>
	<?php }?>

	<div class="svc_post_grid_list_container <?php echo $svc_class;?>" id="svc_post_grid_list_container_<?php echo $svc_grid_id;?>">
	<div class="svc_post_grid_<?php echo $svc_grid_id;?> svc_carousel_container_<?php echo $svc_grid_id;?> <?php echo $svc_class;?> svc_carousel_container" id="svc_carousel_container_<?php echo $svc_grid_id;?>">
		<?php foreach($videos as $video){?>
		<div>
			<?php echo svc_carousel_video($video,$video_height);?>
		</div>
		<?php }?>
	</div>
    <?php if($synced == 'yes' && $car_display_item == 1){?>
	<div id="svc_carousel2_container_<?php echo $svc_grid_id;?>" class="svc_carousel2_container svc_carousel2_container_<?php echo $svc_grid_id;?>">
		<?php foreach($videos as $video){?>
		<div>
			<img src="<?php echo svc_video_image($video);?>" class="svc_post_image"/>
		</div>
		<?php }?>
	</div>
	<?php }?>
	</div>
	</div>
	<script>
	jQuery(document).ready(function(){
	var sync1 = jQuery("#svc_carousel_container_<?php echo $svc_grid_id;?>");
		 sync1.owlCarousel({
			<?php if($car_autoplay == 'yes'){?>
			autoPlay: <?php echo $car_autoplay_time*1000;?>,
			<?php }
			if($lazyload == 'yes'){?>
			lazyLoad : true,
			<?php }?>
			items : <?php echo $car_display_item;?>,
			itemsDesktop : [1199,<?php echo $car_desktop_display_item;?>],
			itemsDesktopSmall : [979,<?php echo $car_desktopsmall_display_item;?>],
			itemsTablet : [768,<?php echo $car_tablet_display_item;?>],
			itemsMobile : [479,<?php echo $car_mobile_display_item;?>],
			pagination:<?php if($car_pagination == 'yes'){echo 'true';}else{echo 'false';}?>,
			navigation: <?php if($car_navigation == 'yes'){echo 'false';}else{echo 'true';}?>,
			<?php if($car_pagination == 'yes' && $car_pagination_num == 'yes'){?>
			paginationNumbers:true,
			<?php }
			if($car_display_item == 1 && $car_transition != ''){?>
			transitionStyle : "<?php echo $car_transition;?>",
			<?php }
			if($car_display_item == 1){?>
			autoHeight:true,
			singleItem:true,
			<?php }
			if($synced == 'yes' && $car_display_item == 1){?>
			afterAction : svc_syncPosition_<?php echo $svc_grid_id;?>,
			responsiveRefreshRate : 200,
			<?php }?>
			 navigationText: [
				"<i class='fa fa-chevron-left icon-white'></i>",
				"<i class='fa fa-chevron-right icon-white'></i>"
			],
			afterInit:function(){
				jQuery('#svc_mask_<?php echo $svc_grid_id;?>').hide();
				jQuery('#svc_post_grid_list_container_<?php echo $svc_grid_id;?>').show();
			}
		});
		
		<?php if($synced == 'yes' && $car_display_item == 1){?>
		var sync2 = jQuery("#svc_carousel2_container_<?php echo $svc_grid_id;?>");
		 sync2.owlCarousel({
			items : <?php echo $synced_display;?>,
			itemsDesktop : [1199,10],
			itemsDesktopSmall : [979,10],
			itemsTablet : [768,8],
			itemsMobile : [479,4],
			pagination:false,
			responsiveRefreshRate : 100,
			afterInit : function(el){
				el.find(".owl-item").eq(0).addClass("synced");
			}
		});

		function svc_syncPosition_<?php echo $svc_grid_id;?>(el){
			var current = this.currentItem;
			jQuery("#svc_carousel2_container_<?php echo $svc_grid_id;?>")
			.find(".owl-item")
			.removeClass("synced")
			.eq(current)
			.addClass("synced")
			if(jQuery("#svc_carousel2_container_<?php echo $svc_grid_id;?>").data("owlCarousel") !== undefined){
				svc_center_<?php echo $svc_grid_id;?>(current)
			}
		}
		jQuery("#svc_carousel2_container_<?php echo $svc_grid_id;?>").on("click", ".owl-item", function(e){
			e.preventDefault();
			var number = jQuery(this).data("owlItem");
			sync1.trigger("owl.goTo",number);
		});
		function svc_center_<?php echo $svc_grid_id;?>(number){
			var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
			var num = number;
			var found = false;
			for(var i in sync2visible){
				if(num === sync2visible[i]){
					var found = true;
				}
			}
			if(found===false){
				if(num>sync2visible[sync2visible.length-1]){
					sync2.trigger("owl.goTo", num - sync2visible.length+2)
				}else{
					if(num - 1 === -1){
						num = 0;
					}
				sync2.trigger("owl.goTo", num);
				}
			}else if(num === sync2visible[sync2visible.length-1]){
				sync2.trigger("owl.goTo", sync2visible[1])
			}else if(num === sync2visible[0]){
				sync2.trigger("owl.goTo", num-1)
			}
		}
		<?php }?>
	});
	</script>
	<?php $message = ob_get_clean();
	return $message;
}elseif($svc_type == 'custom_layout'){
	$var = get_defined_vars();
	$loop=$query_loop;
	$posts = array();
	if(empty($loop)) return;

	//$paged = 1;
	$query=$query_loop;
	$query=explode('|',$query);
	
	$query_posts_per_page=10;
	$query_post_type='post';
	$query_meta_key='';
	$query_orderby='date';
	$query_order='ASC';
	
	$query_by_id='';
	$query_by_id_not_in='';
	$query_by_id_in='';
	
	$query_categories='';
	$query_cat_not_in='';
	$query_cat_in='';

	$query_tags='';
	$query_tags_in='';
	$query_tags_not_in='';
	
	$query_author='';
	$query_author_in='';
	$query_author_not_in='';
	
	$query_tax_query='';
	
	foreach($query as $query_part)
	{
		$q_part=explode(':',$query_part);
		switch($q_part[0])
		{
			case 'post_type':
				$query_post_type=explode(',',$q_part[1]);
			break;
			
			case 'size':
				$query_posts_per_page=($q_part[1]=='All' ? -1:$q_part[1]);
			break;
			
			case 'order_by':
				
				$query_meta_key='';
				$query_orderby='';
				
				$public_orders_array=array('ID','date','author','title','modified','rand','comment_count','menu_order');
				if(in_array($q_part[1],$public_orders_array))
				{
					$query_orderby=$q_part[1];
				}else
				{
					$query_meta_key=$q_part[1];
					$query_orderby='meta_value_num';
				}
				
			break;
			
			case 'order':
				$query_order=$q_part[1];
			break;
			
			case 'by_id':
				$query_by_id=explode(',',$q_part[1]);
				$query_by_id_not_in=array();
				$query_by_id_in=array();
				foreach($query_by_id as $ids)
				{
					if($ids<0)
					{
						$query_by_id_not_in[]=$ids;
					}else{
						$query_by_id_in[]=$ids;
					}
				}
			break;
			
			case 'categories':
				$query_categories=explode(',',$q_part[1]);
				$query_cat_not_in=array();
				$query_cat_in=array();
				foreach($query_categories as $cat)
				{
					if($cat<0)
					{
						$query_cat_not_in[]=$cat;
					}else
					{
						$query_cat_in[]=$cat;
					}
				}
			break;
			
			case 'tags':
				$query_tags=explode(',',$q_part[1]);
				$query_tags_not_in=array();
				$query_tags_in=array();
				foreach($query_tags as $tags)
				{
					if($tags<0)
					{
						$query_tags_not_in[]=$tags;
					}else
					{
						$query_tags_in[]=$tags;
					}
				}
			break;
			
			case 'authors':
				$query_author=explode(',',$q_part[1]);
				$query_author_not_in=array();
				$query_author_in=array();
				foreach($query_author as $author)
				{
					if($tags<0)
					{
						$query_author_not_in[]=$author;
					}else
					{
						$query_author_in[]=$author;
					}
				}
				
			break;

			case 'tax_query':
				$all_tax=get_object_taxonomies( $query_post_type );

				$tax_query=array();
				$query_tax_query=array('relation' => 'AND');
				foreach ( $all_tax as $tax ) {
					$values=$tax;
					$query_taxs_in=array();
					$query_taxs_not_in=array();
					
					$query_taxs=explode(',',$q_part[1]);
					foreach($query_taxs as $taxs)
					{
						if($taxs<0)
						{
							$query_taxs_not_in[]=$taxs;
						}else
						{
							$query_taxs_in[]=$taxs;
						}
					}

					if(count($query_taxs_not_in)>0)
					{
						$query_tax_query[]=array(
							'taxonomy' => $tax,
							'field'    => 'id',
							'terms'    => $query_taxs_not_in,
							'operator' => 'NOT IN',
						);
					}else if(count($query_taxs_in)>0)
					{
						$query_tax_query[]=array(
							'taxonomy' => $tax,
							'field'    => 'id',
							'terms'    => $query_taxs_in,
							'operator' => 'IN',
						);
					}
					
					break;
				}
				
			break;
		}
	}

	$query_final=array(
		'post_type' => $query_post_type,
		'post_status'=>'publish',
		'posts_per_page'=>$query_posts_per_page,
		'meta_key' => $query_meta_key,
		'orderby' => $query_orderby,
		'order' => $query_order,
		'paged'=>$paged,
		
		'post__in'=>$query_by_id_in,
		'post__not_in'=>$query_by_id_not_in,
		
		'category__in'=>$query_cat_in,
		'category__not_in'=>$query_cat_not_in,
		
		'tag__in'=>$query_tags_in,
		'tag__not_in'=>$query_tags_not_in,
		
		'author__in'=>$query_author_in,
		'author__not_in'=>$query_author_not_in,
		
		'tax_query'=>$query_tax_query
	 );

	$exclude_texo_array = explode(',',$exclude_texo);
	$my_query = new WP_Query($query_final);	
	if(!$ajax){
		$svc_grid_id = rand(50,5000);
	}
	$var['svc_grid_id'] = $svc_grid_id;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	ob_start();?>
	<style type="text/css">
	<?php if($car_navigation_color != ''){?>
	.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-controls .owl-buttons div,.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-controls .owl-page span{ background:<?php echo $car_navigation_color;?> !important;}
	<?php }?>
	.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-item div{ margin:<?php echo $car_margin;?>px;}
	.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-item div{background:<?php echo $pbgcolor;?> !important;}
	.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-item div:hover{background:<?php echo $pbghcolor;?> !important;}
	.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-item div .svc_title{ color:<?php echo $tcolor;?> !important;}
	.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-item div .svc_title:hover{ color:<?php echo $thcolor;?> !important;}
	</style>
	<div class="svc_post_grid_list <?php echo $svc_class;?>">
	<?php if($title != ''){?>
	<div class="svc_grid_title"><?php echo $title;?></div>
	<?php }?>

	<div class="svc_post_grid_list_container <?php echo $svc_class;?>" id="svc_post_grid_list_container_<?php echo $svc_grid_id;?>">
	<div class="svc_post_grid_<?php echo $svc_grid_id;?> svc_carousel_container_<?php echo $svc_grid_id;?> <?php echo $svc_class;?> svc_carousel_container svc_custom_carousel" id="svc_carousel_container_<?php echo $svc_grid_id;?>">
		<?php 
		$link_target = $grid_link_target;
	$lt = '';
	if($link_target == 'sw'){
		$lt = 'target="_self"';
	}elseif($link_target == 'nw'){
		$lt = 'target="_blank"';
	}
	$img_array = array();
	while ( $my_query->have_posts() ) {
		$my_query->the_post(); // Get post from query
		$post = new stdClass(); // Creating post object.
		$post->id = get_the_ID();
		$post->link = get_permalink($post->id);
		$img_id=get_post_meta( $post->id , '_thumbnail_id' ,true );
		$img_array[] = $img_id;
		
		$post_thumbnail = wpb_getImageBySize(array( 'post_id' => $post->id, 'thumb_size' => $grid_thumb_size ));
		$current_img_large = $post_thumbnail['thumbnail'];
		//$current_img_full = wp_get_attachment_image_src( $img_id[$img_counter++] , 'full' );
		
		$post_type = get_post_type( $post->id );
		$post_taxonomies = get_object_taxonomies($post_type);
		//echo "<pre>";print_r($post_taxonomies);
		for($i = 0;$i < count($post_taxonomies); $i++){
			if($post_taxonomies[$i] == 'post_format'){
				unset($post_taxonomies[$i]);
			}
		}
		
		?>
        <div>
        <?php
		$post_orders = explode(',',$svc_teasr_carousel_layout);
		foreach ( $post_orders as $order ){
		$order_type = explode('|',$order);
			switch ($order_type[0]){
			case 'image':
				?>
                <header>
                <a href="<?php echo $post->link;?>" <?php echo $lt;?>>
                    <?php echo wp_get_attachment_image( $img_id, $grid_thumb_size,false,array('class' => 'svc_post_image') );?>
                </a>
                </header>
                <?php
				 break;
			case 'title':
				?>
				<section>
                <p><a href="<?php echo $post->link;?>" <?php echo $lt;?> class="svc_title"><?php echo get_the_title();?></a></p>
                </section>
                <?php	
				break;
			case 'text':
                if($dexcerpt != 'yes'){?>
                <p class="svc_info"><?php echo svc_post_layout_excerpt(get_the_excerpt(),$svc_excerpt_length);?></p>
                <?php }
				break;
			case 'link':
				?>
                <a href="<?php echo $post->link;?>" class="svc_read_more" <?php echo $lt;?>>
			  <?php if($read_more == ''){ _e('Read More','svc_carousel');}else{ _e($read_more,'svc_carousel');}?>&nbsp;<i class="fa fa-angle-double-right"></i></a>
                <?php
				break;
			}//end switch
		}//end foreach?>
         </div>
         <?php		
		}
		wp_reset_query();?>
	</div>
	</div>
	</div>
	<script>
	jQuery(document).ready(function(){
	var sync1 = jQuery("#svc_carousel_container_<?php echo $svc_grid_id;?>");
		 sync1.owlCarousel({
			<?php if($car_autoplay == 'yes'){?>
			autoPlay: <?php echo $car_autoplay_time*1000;?>,
			<?php }
			if($lazyload == 'yes'){?>
			lazyLoad : true,
			<?php }?>
			items : <?php echo $car_display_item;?>,
			itemsDesktop : [1199,<?php echo $car_desktop_display_item;?>],
			itemsDesktopSmall : [979,<?php echo $car_desktopsmall_display_item;?>],
			itemsTablet : [768,<?php echo $car_tablet_display_item;?>],
			itemsMobile : [479,<?php echo $car_mobile_display_item;?>],
			pagination:<?php if($car_pagination == 'yes'){echo 'true';}else{echo 'false';}?>,
			navigation: <?php if($car_navigation == 'yes'){echo 'false';}else{echo 'true';}?>,
			<?php if($car_pagination == 'yes' && $car_pagination_num == 'yes'){?>
			paginationNumbers:true,
			<?php }
			if($car_display_item == 1 && $car_transition != ''){?>
			transitionStyle : "<?php echo $car_transition;?>",
			<?php }
			if($car_display_item == 1){?>
			autoHeight:true,
			singleItem:true,
			<?php }?>
			 navigationText: [
				"<i class='fa fa-chevron-left icon-white'></i>",
				"<i class='fa fa-chevron-right icon-white'></i>"
			],
			afterInit:function(){
				jQuery('#svc_mask_<?php echo $svc_grid_id;?>').hide();
				jQuery('#svc_post_grid_list_container_<?php echo $svc_grid_id;?>').show();
			}
		});
	});
	</script>
	<?php $message = ob_get_clean();
	return $message;
}


}

function svc_carousel_anything_shortcode($attr,$content=null){
	extract(shortcode_atts( array(
		'title' => '',
		'car_display_item' => '4',
		'car_desktop_display_item' => '4',
		'car_desktopsmall_display_item' => '3',
		'car_tablet_display_item' => '2',
		'car_mobile_display_item' => '1',
		'car_pagination' => '',
		'car_pagination_num' => '',
		'car_navigation' => '',
		'car_autoplay' => '',
		'car_autoplay_time' => '5',
		'car_margin' => '10',
		'car_transition' => '',
		'car_navigation_color' => '',
		'svc_class' => ''
	), $attr));
	
	wp_register_style( 'svc-carousel-css', plugins_url('css/css.css', __FILE__));
	wp_enqueue_style( 'svc-carousel-css');
	//wp_enqueue_style( 'svc-bootstrap-css' );
	//wp_enqueue_style( 'svc-megnific-css' );
	//wp_enqueue_style( 'svc-animate-css');
	
	wp_enqueue_script('svc-imagesloaded-js');
	//wp_enqueue_script('svc-isotop-js');
	//wp_enqueue_script('svc-script-js');
	//wp_enqueue_script('svc-megnific-js');
	//wp_enqueue_script('svc-ddslick-js');
	wp_enqueue_script('svc-carousel-js');

	$tcontent = wpb_js_remove_wpautop($content,true);
	$svc_grid_id = rand(0,5000);
	ob_start();?>
	<style type="text/css">
	<?php if($car_navigation_color != ''){?>
	.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-controls .owl-buttons div,.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-controls .owl-page span{ background:<?php echo $car_navigation_color;?> !important;}
	<?php }?>
	.svc_carousel_container_<?php echo $svc_grid_id;?>.owl-theme .owl-item div{ margin:<?php echo $car_margin;?>px;}
	</style>
	<div class="svc_post_grid_list <?php echo $svc_class;?>">
	<?php if($title != ''){?>
	<div class="svc_grid_title"><?php echo $title;?></div>
	<?php }?>

	<div class="svc_post_grid_list_container <?php echo $svc_class;?>" id="svc_post_grid_list_container_<?php echo $svc_grid_id;?>">
	<div class="svc_post_grid_<?php echo $svc_grid_id;?> svc_carousel_container_<?php echo $svc_grid_id;?> <?php echo $svc_class;?> svc_carousel_container svc_custom_carousel" id="svc_carousel_container_<?php echo $svc_grid_id;?>">
	<?php
	echo $tcontent;?>
	</div>
	</div>
	</div>
	<script>
	jQuery(document).ready(function(){
	var sync1 = jQuery("#svc_carousel_container_<?php echo $svc_grid_id;?>");
		 sync1.owlCarousel({
			<?php if($car_autoplay == 'yes'){?>
			autoPlay: <?php echo $car_autoplay_time*1000;?>,
			<?php }?>
			items : <?php echo $car_display_item;?>,
			itemsDesktop : [1199,<?php echo $car_desktop_display_item;?>],
			itemsDesktopSmall : [979,<?php echo $car_desktopsmall_display_item;?>],
			itemsTablet : [768,<?php echo $car_tablet_display_item;?>],
			itemsMobile : [479,<?php echo $car_mobile_display_item;?>],
			pagination:<?php if($car_pagination == 'yes'){echo 'true';}else{echo 'false';}?>,
			navigation: <?php if($car_navigation == 'yes'){echo 'false';}else{echo 'true';}?>,
			<?php if($car_pagination == 'yes' && $car_pagination_num == 'yes'){?>
			paginationNumbers:true,
			<?php }
			if($car_display_item == 1 && $car_transition != ''){?>
			transitionStyle : "<?php echo $car_transition;?>",
			<?php }
			if($car_display_item == 1){?>
			autoHeight:true,
			singleItem:true,
			<?php }?>
			 navigationText: [
				"<i class='fa fa-chevron-left icon-white'></i>",
				"<i class='fa fa-chevron-right icon-white'></i>"
			],
			afterInit:function(){
				jQuery('#svc_mask_<?php echo $svc_grid_id;?>').hide();
				jQuery('#svc_post_grid_list_container_<?php echo $svc_grid_id;?>').show();
			}
		});
	});
	</script>
    
    <?php $message = ob_get_clean();
	return $message;
}
?>
