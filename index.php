    <?php get_header(); ?>
    <main class="main container">
      <div class="main-wrapper">
        <div class="main-content">
          <section id="intro">
            <?php 
                $id=6;
                $post = get_post($id);
                $title = apply_filters('the_title', $post->post_title);
                $link = apply_filters('the_permalink', $post->guid);
                $content = apply_filters('wp_trim_excerpt', $post->post_content);

            ?>
            

            <div class="intro-post" itemprop="description">
              <p><?php echo $content; ?></p>
              <a class="btn intro-bt-more" href="./apresentacao<?php //echo $link; ?>">Saiba maissss</a>
            </div> 

          </section>
          <?php  wp_reset_postdata(); ?>
           
          <section class="main-news" itemprop="subEvents">
            <header>
              <h1>Notíciass</h1>
            </header>
           
            <?php

            //$args = array( 'numberposts' => 5, 'category_name' => 'noticias' );

            //$myposts = get_posts( $args );
            //foreach ( $myposts as $post ) : setup_postdata( $post );

            //$my_date = get_the_date('d/m/Y - G:i', $post>ID);
            //$my_date = explode('.', $my_date);
            //$my_date[1] = ucfirst($my_date[1]);
            //$my_date = implode('', $my_date);

            //$link = '';
            //if(get_post_meta($post->ID,'link',true)){
             //$link = get_post_meta($post->ID,'link',true);
             //$post_link = get_post($link);
            //}
              
            //else
              //$link = get_permalink();
            //?>
              
             <!--  <article class="main-news-post" intemprop="subEvent">
                <date class="main-news-post-date" datetime="<?php //the_time('d-m-Y') ?>"><?php //the_time('d/m/Y') ?></date>
                <a class="main-news-post-link" href="<?php //echo $link; ?>"><?php //the_title(); ?></a>
                <a href="<?php //echo $link; ?>"><p><?php //echo excerpt(30) ?></p></a>
              </article> -->
            <?php //wp_reset_postdata(); endforeach; ?>
            <?php //$post = $tmp_post; ?>
            
            <?php ufmg_news(); ?>

            <a class="btn main-news-link-more" href="<?php echo get_site_url(); ?>/noticias/">Ver todas as notícias</a>
          </section>
        </div>
      </div>
    </main>

    <?php get_footer(); ?>
