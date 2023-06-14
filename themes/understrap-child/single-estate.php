<?php
/*
 * Template Name: Estate Single
 * Template Post Type: estate
 */

get_header();
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <h2 class="entry-title"><?php the_title(); ?></h2>
                    <div class="entry-content">

                            <?php
                            // Получение изображений галереи
                            $gallery_images = get_post_meta( get_the_ID(), 'gallery_images', true );
                            $gallery_images_ids = explode( ',', $gallery_images );
                            if ($gallery_images) {

                                ?>
                        <div class="image-carousel">
                                <div id="gallery-carousel" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php
                                        $index = 0;
                                        ?>
                                        <div class="carousel-item">
                                            <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID())) ?>" class="d-block w-100" alt="">
                                        </div>
                                        <?php
                                        foreach ($gallery_images_ids as $image) :
                                            $active_class = ($index === 0) ? 'active' : '';
                                            $image_url = wp_get_attachment_image_url( $image, 'large' );
                                            ?>
                                            <div class="carousel-item <?php echo $active_class; ?>">
                                                <img src="<?php echo esc_url($image_url) ?>" class="d-block w-100" alt="">
                                            </div>
                                            <?php
                                            $index++;
                                        endforeach;
                                        ?>
                                    </div>
                                    <a class="carousel-control-prev" href="#gallery-carousel" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#gallery-carousel" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                        </div>
                            <?php
                            }
                            else { ?>
                                <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID())) ?>" class="" alt="">
                            <?php }
                            ?>

                        <?php the_content(); ?>

                        <p><strong>Площадь:</strong> <?php echo get_post_meta(get_the_ID(), 'area', true); ?></p>
                        <p><strong>Стоимость:</strong> <?php echo get_post_meta(get_the_ID(), 'price', true); ?></p>
                        <p><strong>Адрес:</strong> <?php echo get_post_meta(get_the_ID(), 'address', true); ?></p>
                        <p><strong>Жилая площадь:</strong> <?php echo get_post_meta(get_the_ID(), 'living_area', true); ?></p>
                        <p><strong>Этаж:</strong> <?php echo get_post_meta(get_the_ID(), 'floor', true); ?></p>


                    </div>
                </article>
            <?php endwhile; endif; ?>
        </div>
    </div>
</div>

<?php
get_footer();
?>
