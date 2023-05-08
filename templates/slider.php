
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
    <?php
    if($query->have_posts()):


        while($query->have_posts()) :
            $query->the_post() ;
           
            ?>
              
            <div class="swiper-slide">
                <div class="row">
                    <div class="col ">
                        <div class="myborder">
                            <div class="title">
                                <?php echo get_the_title(); ?><br>
                                <div class="thumbnail">
                                    <?php echo get_the_post_thumbnail();?>
                                </div>
                                <?php echo get_post_meta( get_the_ID(),"_name", true );?><br>
                                <?php  if($get_user_email['user_email'] == 'on'){
                                        echo get_post_meta( get_the_ID(),"_email", true );
                                }?>
                                <br>
                                <?php  if($get_user_address['user_address'] == 'on'){
                                        echo get_post_meta( get_the_ID(),"_address", true );
                                }?>
                                <?php echo get_the_content(); ?>
                                <?php echo get_post_meta(get_the_ID(),"company_name",true);?><br>
                                <?php echo get_post_meta(get_the_ID(),"company_website",true);?><br>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
             
            <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>

    <?php