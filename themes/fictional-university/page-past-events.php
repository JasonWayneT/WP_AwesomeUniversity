<?php get_header();
pageBanner(array(
  'title'=>'Past Events',
  'subtitle'=>'Check out our past events'
))
?>

<div class="container container--narrow page-section">
  <?php 
    // $today sets the current date so that we can compare and show only posts before or after current date
    $today = date('Ymd');
    // Create a custom Wordpress Query to access data not on the current page
    $pastEvents = new WP_Query(array(
    'post_type'=>'event',
    'meta_key'=>'event_date',
    'order_by'=>'meta_value_num',
    'order'=>'ASC',
    // Tells custom query to page numbers properly for pagination
    'paged'=>get_query_var('paged',1),
    // Ordering our Events so that past events dont show up
    'meta_query'=>array(
      array(
        'key'=>'event_date',
        'compare'=>'<=',
        'value'=>$today,
        'type'=> 'numeric'
      ),
    )
));
    // $pastEvents querys custom query above to show information on differnt page
    while( $pastEvents->have_posts() ) {
      $pastEvents->the_post();
      get_template_part('Template Parts/content-event');
         }
    // Shows Pagination of posts, array looks into custom query to activate pagination
    echo paginate_links(array(
        'total'=>$pastEvents->max_num_pages,
    ));
  ?>

</div>



<?php
get_footer();
 ?>


          
        
          
     