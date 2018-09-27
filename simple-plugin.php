<?php
/**
*Plugin Name: Simple Contact Form
*Description: An extremely simple plugin for contact form with email facility.
*Author: Bennet Rio Chettiar
*Author URI: https://www.linkedin.com/in/bennet-rio-chettiar-a9126b76/
*Version: 1.0.0
**/


function simplecontactform_admin_menu_option()
{
    add_menu_page('SIMPLE CONTACT FORM','Simple Contact Form','manage_options','simplecontact-admin-menu','simplecontactform_page','','200');
}
add_action('admin_menu','simplecontactform_admin_menu_option');


function simplecontactform_page()
{
    if(array_key_exists('submit_update',$_POST))
    {
        update_option('simplecontactform_scripts',$_POST['email_address']);
        
        ?>
        
        <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"><strong>Email ID has been updated with <?php echo $_POST['email_address'] ?></strong></div>
        
        <?php
    }
    
    
    ?>
    <div class="wrap">
        <h3>Please update the email address where you want the form details to be sent to.</h3>
        <form method="post" action="">
        <label for="send_email">Send Email To</label>
        <input type="text" name="email_address" value="" placeholder="bennetrio.chettiar@my.jcu.edu.au" style="min-width:300px;"/>
        <input type="submit" name="submit_update" class="button button-primary" value="UPDATE EMAIL">
        </form>
    </div>
    <?php
}



function simplecontact_form()
{


    $content = '';
    $content .= '<form method="post" action="">';
    
    $content .= '<input type="text" name="full_name" placeholder="Your Full Name" style="max-width:300px;" required/>';
    $content .= '<br/><br/>';
    
    $content .= '<input type="text" name="email_address" placeholder="Email Address" style="max-width:300px;" required/>';
    $content .= '<br/><br/>';
    
    $content .= '<input type="text" name="phone_number" placeholder="Phone Number" style="max-width:300px;"/>';
    $content .= '<br/><br/>';
    
    $content .= '<textarea name="comments" placeholder="Comments" style="max-width:300px;"></textarea>';
    $content .= '<br/><br/>';
    
    $content .= '<input type="submit" name="simplecontact_submit_form" value="Submit"/>';
    $content .= '<br/><br/>';
    
    $content .= '</form>';
    
    

   if(array_key_exists('simplecontact_submit_form',$_POST))
    { ?>
     <div><strong>Message Received.</strong></div> 
    <?php
    }

    return $content;

}
add_shortcode('simplecontactform','simplecontact_form');






function set_html_content_type()
{
    return 'text/html';
}




function simplecontact_form_capture()
{
    global $post;
    if(array_key_exists('simplecontact_submit_form',$_POST))
    {
        
        $to = get_option('simplecontactform_scripts','none');
        $subject = "Form Submission";
        $body = '';
        
        $body .= 'Name: ' .$_POST['full_name']. '<br/>';
        $body .= 'Email: ' .$_POST['email_address']. '<br/>';
        $body .= 'Phone: ' .$_POST['phone_number']. '<br/>';
        $body .= 'Comments: ' .$_POST['comments']. '<br/>';
        
        add_filter('wp_mail_content_type','set_html_content_type');
        wp_mail($to, $subject, $body);
        remove_filter('wp_mail_content_type','set_html_content_type');
        
        /*Inserting the message into a comment*/
        $time = current_time('mysql');

        $data = array(
            'comment_post_ID' => $post->ID,
            'comment_content' => $body,
            'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
            'comment_date' => $time,
            'comment_approved' => 0,
        );

        wp_insert_comment($data);
        
    }
}
add_action('wp_head','simplecontact_form_capture');


?>