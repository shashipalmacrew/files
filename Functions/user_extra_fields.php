<?php
/*
Custom user Fields
*/

add_action( 'show_user_profile', 'crf_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'crf_show_extra_profile_fields' );
add_action( "user_new_form", "crf_show_extra_profile_fields" );

function crf_show_extra_profile_fields( $user ) { ?>

	<h3>Extra profile information</h3>
    <table class="form-table">
        <tr>
            <th><label for="access_code">Manager Access Code</label></th>
            <td>
                <input type="text" class="regular-text" name="access_code" value="<?php echo esc_attr( get_the_author_meta( 'access_code', $user->ID ) ); ?>" id="access_code" /><br />
                <span class="description">Add access code of manager so that agent can use access code on registration.</span> 
            </td>
        </tr>
    </table>
	<?php
}


add_action( 'personal_options_update', 'crf_update_profile_fields' );
add_action( 'edit_user_profile_update', 'crf_update_profile_fields' );
add_action('user_register', 'crf_update_profile_fields');

function crf_update_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	# again do this only if you can
    if(!current_user_can('manage_options'))
        return false;

    # save my custom field
    update_usermeta($user_id, 'access_code', $_POST['access_code']);
}