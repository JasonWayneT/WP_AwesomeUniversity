<?php

function universityRegisterSearch() {
    register_rest_route('university/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'universitySearchResults'
    ));
}

add_action('rest_api_init', universityRegisterSearch());


function universitySearchResults($data) {
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'page', 'professor', 'campus', 'event', 'program'),
        'posts_per_page'=> -1,
        's' => sanitize_text_field($data['term'])
    ));
 
    $results = array(
        'generalInfo' => array(),
        'professors' => array(),
        'programs' => array(),
        'campuses' => array(),
        'events' => array(),
        'relationships'=>array()
    );

    while($mainQuery->have_posts()) {
        $mainQuery->the_post();
           
        if (get_post_type() == 'page' || get_post_type() == 'post') {
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'type' => get_post_type(),
                'author' => get_the_author_meta('nickname')
            ));
        }

        if (get_post_type() == 'professor') {
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'type' => get_post_type(),
                'thumbnail'=> get_the_post_thumbnail_url(0, 'professorLandscape')
                // 0 means current
            ));
        }
        if (get_post_type() == 'program') {
            $relatedCampuses = get_field('related_campus');
            if($relatedCampuses){
                foreach($relatedCampuses as $campus){
                    array_push($results['campuses'], array(
                        'title'=>get_the_title($campus),
                        'permalink'=>get_the_permalink($campus)
                    ));
                }
            }
            array_push($results['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'type' => get_post_type(),
                'id' => get_the_id()

            ));
        }
        if (get_post_type() == 'event') {
            $the_event_date = get_field( 'event_date', false, false );
            $eventDate= new DateTime( $the_event_date );
            $excerpt=Null;
            if( has_excerpt() ) {
                $excerpt = get_the_excerpt();
                }else {
                    $excerpt= wp_trim_words(get_the_content(), 18);
                    }
            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'type' => get_post_type(),
                'month'=> $eventDate->format( 'M' ),
                'day'=>  $eventDate->format( 'd' ),
                'excerpt'=> $excerpt

            ));
        }
        if (get_post_type() == 'campus') {
            array_push($results['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'type' => get_post_type()
            ));
        }
        
    } // End While Loop

    if($results['programs']){
        $programsMetaQuery =array('relation' => 'OR');

    foreach($results['programs'] as $item){
        array_push($programsMetaQuery, 
        array(
            'key' => 'related_programs',
            'compare'=> 'LIKE',
            'value' => '"' . $item['id'] . '"')
    );
    }
    $relationshipQuery = new WP_Query(array(
        'post_type'=>array('professor','event'),
        'meta_query'=> $programsMetaQuery
   
    ));
    while($relationshipQuery->have_posts()){
        $relationshipQuery->the_post();
        if (get_post_type() == 'event') {
            $the_event_date = get_field( 'event_date', false, false );
            $eventDate= new DateTime( $the_event_date );
            $excerpt=Null;
            if( has_excerpt() ) {
                $excerpt = get_the_excerpt();
                }else {
                    $excerpt= wp_trim_words(get_the_content(), 18);
                    }
            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'type' => get_post_type(),
                'month'=> $eventDate->format( 'M' ),
                'day'=>  $eventDate->format( 'd' ),
                'excerpt'=> $excerpt

            ));
        }
        if (get_post_type() == 'professor') {
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'type' => get_post_type(),
                'thumbnail'=> get_the_post_thumbnail_url(0, 'professorLandscape')

            ));
          
        }
    
    
    }
    // Gets rid of possible duplicates from two queries
    $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
    $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
    }
    
    return $results;
} // End UniversitySearchResults