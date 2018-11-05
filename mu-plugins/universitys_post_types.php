<?php  
function university_post_types() {
    // Register Event Post Type
    register_post_type( 'event', array(
        // Next two creates permissions selection in User Roles and Permissions
        'capability_type'=>'event',
        'map_meta_cap'=>true,
        'has_archive'=> true,       
        'public'=> true,
        'menu_icon'=>'dashicons-calendar-alt',
        'supports'=>array('title','editor','excerpt'),
        'rewrite'=>array(
            'slug'=>'events'),
        'labels'=>array(
            'name'=>'Events',
            'add_new_item' => 'Add New Event',
            'edit_item'=> 'Edit Event',
            'all_items'=> 'All Events',
            'singlular_name'=> 'Event'
        )
    )  );
    // Register Program Post Type
    register_post_type( 'program', array(
        'has_archive'=> true,      
        'public'=> true,
        'menu_icon'=>'dashicons-awards',
        'supports'=>array('title'),
        'rewrite'=>array(
            'slug'=>'programs'),
        'labels'=>array(
            'name'=>'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item'=> 'Edit Program',
            'all_items'=> 'All Programs',
            'singlular_name'=> 'Program'
        )
    )  );
    register_post_type( 'professor', array(     
        'public'=> true,
        'menu_icon'=>'dashicons-welcome-learn-more',
        'show_in_rest'=> true,
        'supports'=>array('title','editor', 'thumbnail'),
        'labels'=>array(
            'name'=>'Professors',
            'add_new_item' => 'Add New Professor',
            'edit_item'=> 'Edit Professor',
            'all_items'=> 'All Professors',
            'singlular_name'=> 'Professor'
        )
    )  );

    register_post_type( 'campus', array(
        'capability_type' => 'campus',
        'map_meta_cap'=> true,
        'has_archive'=> true,      
        'public'=> true,
        'menu_icon'=>'dashicons-location-alt',
        'supports'=>array('title','editor'),
        'rewrite'=>array(
            'slug'=>'campuses'),
        'labels'=>array(
            'name'=>'Campuses',
            'add_new_item' => 'Add New Campus',
            'edit_item'=> 'Edit Campus',
            'all_items'=> 'All Campuses',
            'singlular_name'=> 'Campus'
        )
    )  );
    register_post_type( 'note', array(    
        'capability_type' => 'note', 
        // Enforce permissions
        'map_meta_cap' => true,
        // makes notes private
        'public'=> false,
        // shows in admin area
        'show_ui'=>true,
        'menu_icon'=>'dashicons-welcome-write-blog',
        'show_in_rest'=> true,
        'supports'=>array('title','editor', 'thumbnail'),
        'labels'=>array(
            'name'=>'Notes',
            'add_new_item' => 'Add New Note',
            'edit_item'=> 'Edit Note',
            'all_items'=> 'All Notes',
            'singlular_name'=> 'Note'
        )
    )  );
   
}
add_action('init', 'university_post_types');