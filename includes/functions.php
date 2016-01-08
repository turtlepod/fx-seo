<?php
/**
 * f(x) SEO Functions
 * @since 0.1.0
**/

/* do not access this file directly */
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Get Option helper function
 * @since 0.1.0
 */
function fx_seo_get_option( $option, $default = '', $option_name = 'fx-seo' ) {

	/* Bail early if no option defined */
	if ( !$option ){
		return false;
	}

	/* Get database and sanitize it */
	$get_option = get_option( $option_name );

	/* if the data is not array, return false */
	if( !is_array( $get_option ) ){
		return $default;
	}

	/* Get data if it's set */
	if( isset( $get_option[ $option ] ) ){
		return $get_option[ $option ];
	}
	/* Data is not set */
	else{
		return $default;
	}
}


/**
 * Render Title
 * parse the title string and replace template tags.
 * @since 0.1.0
 */
function fx_seo_render_title( $title ){
	$template = fx_seo_get_option( 'template_title', '%current_title% &mdash; ' . get_bloginfo( 'name' ) );
	$title = str_replace( '%current_title%', $title, $template );
	return apply_filters( 'fx_seo_render_title', $title );
}

/**
 * Render Meta Description
 * parse the meta description text string and replace template tags.
 * @since 0.1.0
 */
function fx_seo_render_meta_description( $desc ){
	$template = fx_seo_get_option( 'template_meta_desc', '%current_description%' );
	$desc = str_replace( '%current_description%', $desc, $template );
	return apply_filters( 'fx_seo_render_meta_desc', $desc );
}


/* Title & Meta Description
------------------------------------------ */

/* <title> tag filter */
add_filter( 'wp_title', 'fx_seo_wp_title', 99 );
add_filter( 'pre_get_document_title', 'fx_seo_wp_title' ); // wp 4.4

/* meta description */
add_action( 'wp_head', 'fx_seo_meta_description', 5 );


/**
 * Modify output of wp_title.
 * @since 0.1.0
 * @return string
 */
function fx_seo_wp_title( $title ){
	global $wp_query;

	/* Default variable */
	$current = '';

	/* in front page */
	if ( is_front_page() ){
		$title = fx_seo_get_option( 'front_title', get_bloginfo( 'name' ) );
	}

	/* In other pages. */
	else{

		/* In singular pages. */
		if ( is_home() || is_singular() ) {
			$current = get_post_field( 'post_title', get_queried_object_id() );
		}

		/* If viewing any type of archive page. */
		elseif ( is_archive() ) {

			/* If viewing a taxonomy term archive. */
			if ( is_category() || is_tag() || is_tax() ) {
				if ( is_search() )
					$current = esc_attr( get_search_query() ).' &ndash; '.single_term_title( '', false );
				else
					$current = single_term_title( '', false );
			}

			/* If viewing a post type archive. */
			elseif ( is_post_type_archive() ) {
				$post_type = get_post_type_object( get_query_var( 'post_type' ) );
				$current = $post_type->labels->name;
			}

			/* If viewing an author/user archive. */
			elseif ( is_author() ) {
				$current = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
			}

			/* If viewing a date-/time-based archive. */
			elseif ( is_date () ) {
				if ( is_month() ){
					$current = sprintf( _x( 'Archive for %1$s', 'month', 'fx-seo' ), single_month_title( ' ', false) );
				}
				elseif ( is_year() ){
					$current = sprintf( _x( 'Archive for %1$s', 'year', 'fx-seo' ), get_the_time( 'Y' ) );
				}
			}

			/* For any other archives. */
			else {
				$current = __( 'Archives', 'fx-seo' );
			}
		}

		/* If viewing a search results page. */
		elseif ( is_search() )
			$current = strip_tags( str_replace( '+', ' ', get_search_query( false ) ) );

		/* If viewing a 404 not found page. */
		elseif ( is_404() )
			$current = __( '404 Not Found', 'fx-seo' );

		/* Other pages */
		else
			$current = $title;

		/* If the current page is a paged page. */
		if ( ( ( $page = $wp_query->get( 'paged' ) ) || ( $page = $wp_query->get( 'page' ) ) ) && $page > 1 )
			$current = sprintf( _x( '%1$s Page %2$s', 'paged', 'fx-seo' ), $current . $separator, number_format_i18n( $page ) );

		/* Add template */
		$title = fx_seo_render_title( $current );
	}

	return esc_attr( strip_tags( trim( $title ) ) );
}


/**
 * Add Meta Description
 * @since 0.1.0
 * @return string
 */
function fx_seo_meta_description(){

	/* Set an empty $description variable. */
	$description = '';

	/* If viewing the front page. */
	if ( is_front_page() ){
		$description = fx_seo_get_option( 'front_page_meta_desc', get_bloginfo( 'description' ) );
	}

	/* If viewing the home/posts page or is singular pages */
	elseif ( is_home() || is_singular() ) {
		$description = get_post_field( 'post_excerpt', get_queried_object_id() );
	}

	/* If viewing an archive page. */
	elseif ( is_archive() ) {

		/* If viewing a user/author archive. */
		if ( is_author() ) {
			$description = get_the_author_meta( 'description', get_query_var( 'author' ) );
		}

		/* If viewing a taxonomy term archive, get the term's description. */
		elseif ( is_category() || is_tag() || is_tax() ){
			$description = term_description( '', get_query_var( 'taxonomy' ) );
		}

		/* If viewing a custom post type archive. */
		elseif ( is_post_type_archive() ) {

			/* Get the post type object. */
			$post_type = get_post_type_object( get_query_var( 'post_type' ) );

			/* If a description was set for the post type, use it. */
			if ( isset( $post_type->description ) ){
				$description = $post_type->description;
			}
		}
	}

	/* Allow developer to modify meta description output */
	$description = fx_seo_render_meta_description( $description );

	/* Format output of meta description. */
	if ( $description && !empty( $description ) ){
		$description = '<meta name="description" content="' . str_replace( array( "\r", "\n", "\t" ), '', esc_attr( strip_tags( $description ) ) ) . '" />' . "\n";
	}

	echo $description;
}