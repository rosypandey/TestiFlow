
<ul>
    <li>
        <h2><?php echo get_the_title(); ?></h2>
        <div class="thumbnail">
            <?php echo get_the_post_thumbnail();?>
        </div>
        <?php echo get_post_meta( get_the_ID(),"info_name", true );?><br>
        <?php  if($get_user_email['user_email'] == 'on'){
                        echo get_post_meta( get_the_ID(),"info_email", true );
                }else{
                    echo '';
                } ?>
                <br>
                <?php  if($get_user_address['user_address'] == 'on'){
                        echo get_post_meta( get_the_ID(),"info_address", true );
                }else{
                    echo '';
                } ?>
        <?php echo get_the_content(); ?><br>
        <?php echo get_post_meta(get_the_ID(),"company_name",true);?><br>
        <?php echo get_post_meta(get_the_ID(),"company_website",true);?><br>
        
    </li>
</ul>