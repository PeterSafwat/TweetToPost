<?php

/* 
Copyright 2019  Peter Safwat  (email : peter.safwat444@gmail.com)

Plugin Name: Tweet To Post
Plugin URI: http://www.example.com
Description: Posts Twitter Tweets as posts
Author: Peter Safwat
Version: 1
*/

// Action to run function after all wordpress and plugins are loaded
add_action( 'wp_loaded', 'main_function');

// Main Function that runs all the functionality
function main_function() {
    if ( !is_admin() ) { 

		// Main Request to Api
		$request = wp_remote_get('https://jsonplaceholder.typicode.com/posts');

		// Check if the request succeded
		if( is_wp_error( $request ) ) {
			return false; 
		}

		// Get Data Body
		$data = wp_remote_retrieve_body($request);

		// Parse Json
		$data_array = json_decode( $data, true );

		// Check if there is data
		if( ! empty( $data_array ) ) {

			$counter = 0;
			
			// Loop Data
			foreach( $data_array as $item ) {

				// Limit to 10 Records of the original 500 Results
				if($counter <= 9){
				
					// Call Create Post Function and send post title
					create_post($item['title']);
				 }

				 $counter++;
				
			}

		}

    }
}

// Function that gets post title and checks if there is a post with same title if not it calls wordpress insert post function and echos to the frontend the title of the posts created
function create_post($post_title) {

	// Check if post with same title already exists , MAKE SURE YOU DELETE POSTS FROM TRASH IF YOU ARE TESTING
	if (!get_page_by_title($post_title, 'OBJECT', 'post') ){

		// Prepare post array
		$my_post = array(
			'post_title' => $post_title,
			'post_content' => 'Test post content',
			'post_status' => 'publish'
		);
		
		// Create Post
		 $result = wp_insert_post( $my_post );
		 
		 // Echo post titles created
		 echo 'Post with title : '. $post_title .' Created <br>';
	
	}else{
		echo 'Sorry! Post with title : '. $post_title .' <b>Already Exists</b> <br>';
	}

}