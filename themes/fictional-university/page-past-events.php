<?php get_header();?>


<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg')?>);"></div>
  <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"> Past Events </h1>
      <div class="page-banner__intro">
        <p>Our Past Events</p>
      </div>
  </div>
</div>

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
      $pastEvents->the_post();?>
        <div class="event-summary">
          <a class="event-summary__date t-center" href=<?php the_permalink() ?>">
            <span class="event-summary__month"><?php
              // Get RAW date
              $the_event_date = get_field( 'event_date', false, false );
              // THEN create object
              $eventDate= new DateTime( $the_event_date );
              echo $eventDate->format( 'M' );
            ?></span>

            <span class="event-summary__day"><?php echo $eventDate->format('d') ?></span>  
          </a>
          <div class="event-summary__content">
            <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink() ?>"><?php the_title()?></a></h5>
             
            <p> <?php 
            if( has_excerpt() ) {
                echo  get_the_excerpt();
                }else {
                    echo wp_trim_words(get_the_content(), 18);
                    } ?> <a href="<?php the_permalink() ?>" class="nu gray">Learn more</a></p>
          </div>
        </div>
    <?php }
    // Shows Pagination of posts, array looks into custom query to activate pagination
    echo paginate_links(array(
        'total'=>$pastEvents->max_num_pages,
    ));
  ?>

</div>



<?php
get_footer();
 ?>


          
        
          
     