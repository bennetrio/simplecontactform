<?php
/**
*Plugin Name: Simple Contact Form
*Description: An extremely simple plugin for contact form with email facility.
**/

function simplecontact_example_function()
{
    $information = "This is a very basic plugin";
    return $information;
}
add_shortcode('example','simplecontact_example_function');

function simplecontact_admin_menu_option()
{
    add_menu_page('Header & Footer Scripts','Site Scripts','manage_options','simpleplugin-admin-menu','simplecontact_scripts_page','','200');
}
add_action('admin_menu','simplecontact_admin_menu_option');

function simplecontact_scripts_page()
{
    if(array_key_exists('submit_scripts_update',$_POST))
    {
        update_option('simplecontact_header_scripts',$_POST['header_scripts']);
        update_option('simplecontact_footer_scripts',$_POST['footer_scripts']);
        
        ?>
        
        <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"><strong>Settings have been saved</strong></div>
        
        <?php
    }
    
    $header_scripts = get_option('simplecontact_header_scripts','none');
    $footer_scripts = get_option('simplecontact_footer_scripts','none');
    
    
    ?>
    <div class="wrap">
        <h4>Update scripts on the header and footer</h4>
        <form method="post" action="">
        <label for="header_scripts">Header Scripts</label>
        <textarea name="header_scripts" class="large-text"><?php echo $header_scripts; ?></textarea>
        <label for="header_scripts">Footer Scripts</label>
        <textarea name="footer_scripts" class="large-text"><?php echo $footer_scripts; ?></textarea>
        <input type="submit" name="submit_scripts_update" class="button button-primary" value="UPDATE SCRIPTS">
        </form>
    </div>
    <?php
}

function simplecontact_display_header_scripts()
{
    $header_scripts = get_option('simplecontact_header_scripts','none');
    echo $header_scripts;
}
add_action('wp_head','simplecontact_display_header_scripts');

function simpleplugin_display_footer_scripts()
{
    $footer_scripts = get_option('simplecontact_footer_scripts','none');
    echo $footer_scripts;
}
add_action('wp_footer','simplecontact_display_footer_scripts');

function simplecontact_form()
{
    $content = '';
    $content .= '<form method="post" action="http://localhost/wordpress_1/thank-you/">';
    
    $content .= '<input type="text" name="full_name" placeholder="Your Full Name"/>';
    $content .= '<br/>';
    
    $content .= '<input type="text" name="email_address" placeholder="Email Address"/>';
    $content .= '<br/>';
    
    $content .= '<input type="text" name="phone_number" placeholder="Phone Number"/>';
    $content .= '<br/>';
    
    $content .= '<textarea name="comments" placeholder="Comments"></textarea>';
    $content .= '<br/>';
    
    $content .= '<input type="submit" name="simplecontact_submit_form" value="Submit"/>';
    $content .= '<br/>';
    
    $content .= '</form>';
    
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
        $to = "bennetchettiar@gmail.com";
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
            'comment_approved' => 1,
        );

        wp_insert_comment($data);
        
    }
}
add_action('wp_head','simplecontact_form_capture');


?>