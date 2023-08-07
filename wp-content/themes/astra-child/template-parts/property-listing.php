<?php
 $args = array(
               'post_type' => 'property',
               'posts_per_page' => -1,
               'order' => 'ASC'
                );
$query = new WP_Query( $args );
   if($query->have_posts()){
   while($query->have_posts()){ $query->the_post();
      $postid = get_the_id();
      $term_list = get_the_terms($postid, 'property-type');
      $termname = $term_list[0]->name;
  ?>

<section class="property container">
  <div class="row">
   <div class="col-4">
    <img src="<?php the_post_thumbnail_url(); ?>" width="100%" alt="">
     <div class="box-content">
     <h6><?php echo $termname;  ?> </h3>
    <h4><?php the_title(); ?></p> </h4>
    <p><?php the_field('address'); ?></p>
    <p><?php the_field('years_remaining_on_lease'); ?></p>
    <p><?php the_field('cap_rate'); ?></p>
    <p><?php the_field('price'); ?></p>
    <p><?php the_field('price__gross_sf'); ?></p>
    <p><?php the_field('lot_size'); ?></p>
     </div>
   </div>
  </div>
</section>

<?php } } wp_reset_postdata(); ?>
