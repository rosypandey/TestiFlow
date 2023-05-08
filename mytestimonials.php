<?php
/*
 * Plugin Name:      TestiFlow
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:      Try it today and experience the difference!
 * Author:            Rosy Pandey
  * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/mytestimonials/
 */



function plg_testimonials_post_type() {
    $labels = array(
        'name' => 'TestiFlow',
        'singular_name' => 'TestiFlow',
        'add_new' => 'Add New testimonial',
        
    );
    $args = array(
        'labels' => $labels,
         'public' => true,
         'has_archive' => true,
         'show_in_rest'=>true,
         'menu_position' => 2,
         'show_in_rest' => true,
         'show_in_menu' => true,
         'show_admin_column' => true,
      
         'supports' => array( 'title', 'editor', 'thumbnail','excerpt','auther','comments' ),
     );
    register_post_type('testiflow', $args);
}
add_action('init', 'plg_testimonials_post_type');

//custom taxonomy

function plg_add_new_taxonomy(){
    $labels = array(
        'name' => 'Testimonial Categories',
        'search_items'      => 'Search Categories' ,
        'all_items'         => 'All Categories' ,
        'parent_item'       => 'Parent Category' ,
        'parent_item_colon' => 'Parent Categories' ,
        'edit_item'         => 'Edit Category' ,
        'update_item'       => 'Update Category' ,
        'add_new_item'      => 'Add New Categories' ,
        'new_item_name'     => 'New Category Name' ,
        'menu_name'         => 'Categories',
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_in_menu' => true,
        'show_admin_column' => true,
    );
    register_taxonomy( 'rp-categorie', ['testiflow'], $args );
}
add_action( 'init', 'plg_add_new_taxonomy' );

//metabox for custom post type

function plg_add_custom_meta_box() {
    add_meta_box(
        'contact_meta_box_id',
        'PERSONAL INFO',
        'plg_contact_meta_box_callback_func',
        'testiflow','normal','default',
       
    );
}
add_action('add_meta_boxes', 'plg_add_custom_meta_box');

//callback function of metabox 'PERSONAL INFO'
function  plg_contact_meta_box_callback_func($post){
 
   $value1= get_post_meta( $post->ID,"_name", true );
   $value2= get_post_meta( $post->ID ,"_email", true );
   $value3= get_post_meta( $post->ID ,"_address", true );

    ?>
    <form method="POST" name="formdata">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="enter name" value="<?php echo esc_attr( $value1) ?>">
        

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="enter email" 
    size="40" value="<?php echo esc_attr( $value2) ?>"/>

    <label for="address">Address:</label>
    <input type="text" id="address" name="address" placeholder="enter address"
     size="40"  value="<?php echo esc_attr( $value3) ?>" />
    </form>
  <?php
}

//to save the metadata
function plg_save_metadata( $post_id ) {
    if ( isset( $_POST['name'] ) ) {
        update_post_meta( $post_id, '_name', sanitize_text_field( $_POST['name'] ) );
    }
    if ( isset( $_POST['email'] ) ) {
        update_post_meta( $post_id, '_email', sanitize_text_field( $_POST['email'] ) );
    }
    if ( isset( $_POST['address'] ) ) {
        update_post_meta( $post_id, '_address', sanitize_text_field( $_POST['address'] ) );
    }

}
add_action( 'save_post', 'plg_save_metadata' );


//shortcode to display the form data


function plg_custom_post_type_shortcode( $atts ) {
    $args =  array(
        'post_type' => 'testiflow',
       'post_per_page'=>10,
       'publish_status'=>'Published');
   
    $query = new WP_Query($args);

    ob_start();
    ?>
        <table class="tabledata">
            <tr>
                <th>Name</th>
                <th>email</th>
                <th>address</th>
            </tr>
            <?php
            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $value1= get_post_meta( get_the_ID(),"_name", true );
                    $value2= get_post_meta( get_the_ID() ,"_email", true );
                    $value3= get_post_meta( get_the_ID() ,"_address", true );
            ?>
                <tr>
                    <td><?php echo $value1;?></td>
                    <td><?php echo $value2; ?></td>
                    <td><?php echo $value3; ?></td>
        
                </tr>
                <?php
                  }
              }
                ?>
           
        </table>
    <?php

      
    $output=ob_get_clean();
    return $output;
   
}
add_shortcode( 'custom_posts', 'plg_custom_post_type_shortcode' );





//add custom column to backend list
function plg_custom_metabox_column($columns){
    $columns['name']='name';
    $columns['email']='email';
    $columns['address']='address';
    
    return $columns;
} 
add_filter('manage_testiflow_posts_columns','plg_custom_metabox_column');

//to display the column field data in custom column

function plg_display_custom_metabox_value($column,$post_id){
    if ($column === 'name') {
             $value = get_post_meta($post_id, '_name', true);
              echo $value;
           }
           if ($column === 'email') {
            $value = get_post_meta($post_id, '_email', true);
             echo $value;
          }if ($column === 'address') {
            $value = get_post_meta($post_id, '_address', true);
             echo $value;
          }

}
add_action('manage_testiflow_posts_custom_column', 'plg_display_custom_metabox_value',10,2);


//add Excerpt column to backend list

function plg_custom_excerpt_column( $columns ) {
    $columns['excerpt'] ='Excerpt';
    return $columns;
}
add_filter( 'manage_testiflow_posts_columns', 'plg_custom_excerpt_column' );

//to display the excerpt column data in backend

function plg_custom_excerpt_column_content( $column_name, $post_id ) {

    if ( $column_name == 'excerpt' ) {
        echo get_the_excerpt($post_id);
    }
}
add_action( 'manage_testiflow_posts_custom_column', 'plg_custom_excerpt_column_content',10,2);


//to enqueue the css file
function plg_my_enqueue_styles() {
    wp_enqueue_style( 'my-style', plugins_url( '/css/mystyle.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'plg_my_enqueue_styles' );


//shortcode to display the all the custom post in frontend
function plg_cat_listing_func($atts)
{$atts = shortcode_atts(
    array(
        'category_name' => '',
        
    ), $atts, 'lists' );


    $args = array(
        'post_type' => 'testiflow',
        'posts_per_page' => 5,
        'publish_status' => 'Publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'rp-categorie', 
                'field' => 'slug',
                'terms' => $atts['category_name'],
            ),
        ),
        
    );
    ob_start();
    $query = new WP_Query($args);
    ?>
     <div class="container">
                <div class="row">
    <?php
    if($query->have_posts()):


        while($query->have_posts()) :
            $query->the_post() ;
           
            ?>
              
             <div class="col-sm-6 myborder">
                <div class="title"><?php echo get_the_title(); ?><br>
                <div class="thumbnail">
                <?php echo get_the_post_thumbnail();?>
                </div>
                <?php echo get_post_meta( get_the_ID(),"_name", true );?>
                <?php echo get_the_content(); ?></div>
            </div>
             
            <?php
        endwhile;
    endif;
    ?>
    </div>
            </div>

    <?php
    $display=ob_get_clean();
    return $display;


}
add_shortcode('plg_lists','plg_cat_listing_func');





// Add a custom column to the Testimonials list table for the feature image
add_filter( 'manage_testiflow_posts_columns', 'plg_add_testimonial_image_column' );
function plg_add_testimonial_image_column( $columns ) {
    $columns['testimonial_image'] = 'Image';
    return $columns;
}

// Display the feature image in the custom column on the Testimonials list table
add_action( 'manage_testiflow_posts_custom_column', 'plg_display_testimonial_image_column', 10, 2 );
function plg_display_testimonial_image_column( $column, $post_id ) {
    if ( 'testimonial_image' === $column ) {
        $thumbnail = get_the_post_thumbnail( $post_id, array( 60, 60 ) );
        echo $thumbnail;
    }
}

// to enqueue  cdn links of bootstrap
function plg_mytestimonial_stylesheet()
{
    wp_enqueue_style( 'myCSS', plugins_url( 'mystyle.css', __FILE__ ) );
    wp_enqueue_style( 'myplugin-style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' );
    wp_enqueue_script( 'myplugin-script', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js', null, null, true );
}
add_action('wp_enqueue_scripts', 'plg_mytestimonial_stylesheet');



//creating sub menu called setting


function plg_setting_menu_page() //menu page which have settings options for this plugin
{
    add_submenu_page(
        'edit.php?post_type=testiflow', // the slug of your custom post type
        'settings', // the title of the menu page
        'settings', // the label of the menu item
        'manage_options', // the required user capability to view the menu item
        'settings', // the slug of the menu page
        'plg_setting_callback_func' // the callback function to display the content of the menu page
    );
    
}
add_action( 'admin_menu', 'plg_setting_menu_page' );

function plg_setting_callback_func(){
  
   ?>
   <form method='post'>
    <input type="checkbox" id="checkbox" name="enable_email">
    <label for="checkbox">Enable user email</label><br>
    <input type="checkbox" id="checkbox2" name="enable_address">
    <label for="checkbox2">Enable user address</label><br>
   


    <input type='submit'  id='submit' value='save changes' name='save'/>
</form>
    <?php
    //to save datas in database
    if( isset($_POST['save']) ) {
        $Postdata=[];
        $Postdata['user_email']=isset($_POST['enable_email']);
        $Postdata['user_address']=isset($_POST['enable_address']);
        
    }
    

}








//link the javacript file and slider cdn link
function plg_testimonial_stylesheet()
{
  
    //swiper slider cdn
    wp_enqueue_style( 'swipercss-cdn', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css' );
    wp_enqueue_script( 'swiperjs_cdn', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js', null, null, true );
    //custom js
    wp_enqueue_script( 'myscript', plugins_url( 'myscript.js', __FILE__ ), array('jquery'), null, true );
}
add_action('wp_enqueue_scripts', 'plg_testimonial_stylesheet');

//shortcode to display the slider

function plg_slider_func()
{

    $args = array(
        'post_type' => 'testiflow',
        'posts_per_page' => 5,
        'publish_status' => 'Publish',
   
    );
    $get_user_email=get_option('post_data');
    $get_user_address=get_option('post_data');
    ob_start();
    $query = new WP_Query($args);
   include('slider.php');
    $display=ob_get_clean();
    return $display;


}
add_shortcode('slider_lists','plg_slider_func');

   
//to add testimonials through form
function plg_add_testimonial_form_shortcode() {
    ob_start();
    ?>
    <form method="post">
        <div id="fields-container">
        <label for="company_name">Company name:</label><br>
        <input type="text" name="company_name" id="company_name" ><br><br>
        <label for="company_website">Company website:</label><br>
        <input type="text" name="company_website" id="company_website"><br><br>

        <label for="testimonial_author">full name:</label><br>
        <input type="text" name="testimonial_author" id="testimonial_author" ><br><br>
        
        <label for="testimonial_content">Testimonial:</label><br>
        <textarea name="testimonial_content" id="testimonial_content" rows="5"></textarea><br><br>
        
            <div class="stars">
        <input class="star" type="radio" id="star-1" name="rating" value="1"/>
        <label class="star" for="star-1"></label>
        <input class="star" type="radio" id="star-2" name="rating" value="2"/>
        <label class="star" for="star-2"></label>
        <input class="star" type="radio" id="star-3" name="rating" value="3"/>
        <label class="star" for="star-3"></label>
        <input class="star" type="radio" id="star-4" name="rating" value="4"/>
        <label class="star" for="star-4"></label>
        <input class="star" type="radio" id="star-5" name="rating" value="5"/>
        <label class="star" for="star-5"></label>
    </div>
         
             <?php wp_nonce_field( 'testimonial_form', 'mytestimonial_nonce' ); ?>

             <input type="submit" name="submit_testimonial" value="Submit Testimonial">
                </form>
                <?php
  
    $form = ob_get_clean();
    return $form;
}
add_shortcode('add_testimonial_shortcode', 'plg_add_testimonial_form_shortcode');




// Handle the form submission
function plg_add_testimonial_form_submit(){
   
    if (isset($_POST['submit_testimonial'])) {
        if( ! wp_verify_nonce( $_POST['mytestimonial_nonce'],'testimonial_form' ) ) {
            return;
        };
        $testimonial_author = sanitize_text_field($_POST['testimonial_author']);
        $testimonial_content = sanitize_textarea_field($_POST['testimonial_content']);
        $company_name = sanitize_text_field($_POST['company_name']);
        $company_website = sanitize_text_field($_POST['company_website']);
        $rawRating = $_POST['rating'];
        
        $args = array(
            'post_title' => $testimonial_author,
            'post_content' => $testimonial_content,
            'post_status' => 'publish',
            'post_type' => 'testiflow',
            'meta_input' => array(
                'company_name' => $company_name,
                'company_website' => $company_website,
                'testimonial_rating' => $rawRating,
            )
           
        );
        $post_id = wp_insert_post($args);
        if ($post_id) {
            wp_redirect( home_url( $_POST['_wp_http_referer'] ) );
            die;
        }
    }
  
}
add_action('init','plg_add_testimonial_form_submit'); 

// Add custom columns to testimonial list
function plg_add_testimonial_columns($columns) {
    $columns['company_name'] = 'Company Name';
    $columns['company_website'] = 'Company Website';
    $columns['testimonial_rating'] = 'Rating';
    return $columns;
}  
add_filter('manage_testiflow_posts_columns', 'plg_add_testimonial_columns');

//  custom columns with meta data
function plg_add_data_testimonial_columns($column_name, $post_id) {
    if ($column_name == 'company_name') {
        $company_name = get_post_meta($post_id, 'company_name', true);
        echo $company_name;
    }
    if ($column_name == 'company_website') {
        $company_website = get_post_meta($post_id, 'company_website', true);
        echo $company_website;
    }
    if ($column_name == 'testimonial_rating') {
        $rawRating = get_post_meta($post_id, 'testimonial_rating', true);
        echo $rawRating;
    }
   

}
add_action('manage_testiflow_posts_custom_column', 'plg_add_data_testimonial_columns', 10, 2);






//shortcode to display the testimonials in list and grid view

function plg_display_func( $atts ) {
    $atts = shortcode_atts( array(
        'view' => 'list', // default view is list
        'per_row' => 1, // default number of items per row is 1

    ), $atts );

    $args = array(
        'post_type' => 'testiflow',
        'post_status' => 'publish',
        'per_row' => $atts['per_row'],
     
    );

    $get_user_email=get_option('post_data');
    $get_user_address=get_option('post_data');
    ob_start();
    $query = new WP_Query( $args );
    

    if ( $query->have_posts() ) {
        if ( $atts['view'] === 'grid' ) {
          include( 'grid.php' );
        } elseif( $atts['view'] === 'list' ) {
          include( 'list.php' ); 
        }elseif( $atts['view'] === 'slider') {
            include('slider.php');
        }
    }

    $display = ob_get_clean();
    return $display;
}
add_shortcode( 'display_lists', 'plg_display_func' );



