<?php 

add_action( 'elementor_pro/forms/new_record',  'maska_elementor_form_create_new_user' , 10, 2 );
function maska_elementor_form_create_new_user( $record, $ajax_handler ) // creating function 
{
    $form_name = $record->get_form_settings('form_name');
    
    //Check that the form is the "Maska Register Form" if not - stop and return;
    if ('Form Name' !== $form_name) {
        return;
    }
    
    $form_data = $record->get_formatted_data();

    //Get the value of the input with the label  
    $username = $form_data['Email'];
    $password = $form_data['Password'];
    $confirm_password = $form_data['Confirm Password'];
    $email = $form_data['Email'];

    // Validate confirm password first
    if($password !== $confirm_password) {
        $ajax_handler->add_error_message("Password confirmation does not match");
        $ajax_handler->is_success = false;
        return;
    }   
    
    // Create a new user, on success return the user_id no failure return an error object
	$user = wp_create_user( $username, $password, $email ); 

    if ( is_wp_error( $user ) ){ // if there was an error creating a new user
        $error_message = $user->get_error_message(); //add the message
        $ajax_handler->add_error_message("Failed to create new user: ".$error_message);
        $ajax_handler->is_success = false;
        return;
    }

    // Get user meta data
    $first_name = $form_data["First Name"];
    $last_name = $form_data["Last Name"];
    $phone_number = $form_data["Phone Number"];
    $country = $form_data["Country"];
    $city = $form_data["City"];

    // Update user meta data
	wp_update_user( array(
            "ID" => $user,
            "first_name" => $first_name,
            "last_name" => $last_name
        )
    );
}

?>
