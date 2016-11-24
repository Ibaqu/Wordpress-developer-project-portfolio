<?php

function dpp_project_information_get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function dpp_project_information_add_meta_box() {
	add_meta_box(
		'project_information-project-information',
		__( 'Project information', 'project_information' ),
		'dpp_project_information_html',
		'dpp_project',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'dpp_project_information_add_meta_box' );

function dpp_project_information_html( $post) {
	wp_nonce_field( '_project_information_nonce', 'project_information_nonce' ); ?>

	<p>
		<label for="project_information_project_url"><?php _e( 'Project url', 'project_information' ); ?></label><br>
		<input type="text" name="project_information_project_url" id="project_information_project_url" value="<?php echo dpp_project_information_get_meta( 'project_information_project_url' ); ?>">
	</p>	<p>
		<label for="project_information_short_description"><?php _e( 'Short description', 'project_information' ); ?></label><br>
		<input type="text" name="project_information_short_description" id="project_information_short_description" value="<?php echo dpp_project_information_get_meta( 'project_information_short_description' ); ?>">
	</p><?php
}

function dpp_project_information_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['project_information_nonce'] ) || ! wp_verify_nonce( $_POST['project_information_nonce'], '_project_information_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['project_information_project_url'] ) )
		update_post_meta( $post_id, 'project_information_project_url', esc_attr( $_POST['project_information_project_url'] ) );
	if ( isset( $_POST['project_information_short_description'] ) )
		update_post_meta( $post_id, 'project_information_short_description', esc_attr( $_POST['project_information_short_description'] ) );
}
add_action( 'save_post', 'dpp_project_information_save' );

function dpp_project_information_get_the_terms($post_id, $taxonomy_name) {
	$term_names = '';
	$terms = get_the_terms($post_id, $taxonomy_name);
	if($terms) {
		foreach ($terms as $cat) {
			$term_names .= $cat->name . ', ';
		}
	}
	return substr($term_names, 0, strlen($term_names) - 2 );
}

/*
	Usage: dpp_project_information_get_meta( 'project_information_project_url' )
	Usage: dpp_project_information_get_meta( 'project_information_short_description' )
*/