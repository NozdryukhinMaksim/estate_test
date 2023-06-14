<?php
/*
Plugin Name: Estate Layout List
Version: 1.0
Author: Maksim
*/


function estate_listing_shortcode($atts)
{
    // Обработка параметров шорткода
    $atts = shortcode_atts(array(
        'posts_per_page' => 10,
        'show_form' => true,
    ), $atts);

    // Запрос для получения последних объектов недвижимости
    $estate_args = array(
        'post_type' => 'estate',
        'posts_per_page' => $atts['posts_per_page'],
    );
    $estate_query = new WP_Query($estate_args);

    // Вывод списка объектов недвижимости
    if ($estate_query->have_posts()) {
        $output = '<div class="estate-listing">';

        ?>
        <div class="container">
            <ul class="estate-list">
                <?php while ($estate_query->have_posts()) : $estate_query->the_post(); ?>
                    <li>
                        <div id="estate-carousel-<?php the_ID(); ?>" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
                            </div>
                        </div>
                        <div class="meta-info">
                            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <p>Стоимость: <?php echo get_post_meta(get_the_ID(), 'price', true); ?></p>
                            <p>Площадь: <?php echo get_post_meta(get_the_ID(), 'area', true); ?></p>
                            <p>Адрес: <?php echo get_post_meta(get_the_ID(), 'address', true); ?></p>
                            <p>Жилая площадь: <?php echo get_post_meta(get_the_ID(), 'living_area', true); ?></p>
                            <p>Этаж: <?php echo get_post_meta(get_the_ID(), 'floor', true); ?></p>

                            <?php
                            $city_terms = get_post_meta(get_the_ID(), 'city');
                            if ($city_terms) {
                                ?>
                                <p>Город: <a
                                            href="<?php echo get_post($city_terms[0])->guid; ?>"><?php echo get_post($city_terms[0])->post_title; ?></a>
                                </p>
                            <?php } ?>
                            <?php
                            $estate_type_terms = get_the_terms(get_the_ID(), 'estate_type');
                            if ($estate_type_terms && !is_wp_error($estate_type_terms)) {
                                $estate_types = array();
                                foreach ($estate_type_terms as $term) {
                                    $estate_types[] = $term->name;
                                }
                                $estate_types_string = implode(', ', $estate_types);
                                echo '<p>Тип недвижимости: ' . $estate_types_string . '</p>';
                            }
                            ?>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>

        </div>
        <?php

        $output .= '</div>';
        wp_reset_postdata();

        // Вывод формы добавления объекта недвижимости
        if ($atts['show_form']) {
            ob_start();
            include_once('templates/estate-form.php');
            $form = ob_get_clean();
            $output .= '<div class="estate-form">';
            $output .= $form;
            $output .= '</div>';
        }

        return $output;
    }

    return '';
}

add_shortcode('estate_listing', 'estate_listing_shortcode');

// Функция для обработки действия добавления недвижимости
function add_real_estate()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = sanitize_text_field($_POST['title']);
        $area = sanitize_text_field($_POST['area']);
        $price = sanitize_text_field($_POST['price']);
        $address = sanitize_text_field($_POST['address']);
        $living_area = sanitize_text_field($_POST['living_area']);
        $floor = sanitize_text_field($_POST['floor']);
        $description = sanitize_text_field($_POST['description']);
        $city_id = intval($_POST['city']);
        $estate_type_id = intval($_POST['estate_type']);
        $thumbnail_id = 0;
        if (!empty($_FILES['thumbnail']['tmp_name'])) {
            $uploaded_image = $_FILES['thumbnail'];
            $upload_overrides = array('test_form' => false);
            $movefile = wp_handle_upload($uploaded_image, $upload_overrides);
            if ($movefile && !isset($movefile['error'])) {
                $attachment = array(
                    'post_mime_type' => $movefile['type'],
                    'post_title' => $title,
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $thumbnail_id = wp_insert_attachment($attachment, $movefile['file']);
                if (!is_wp_error($thumbnail_id)) {
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    $attachment_data = wp_generate_attachment_metadata($thumbnail_id, $movefile['file']);
                    wp_update_attachment_metadata($thumbnail_id, $attachment_data);
                    $post_args = array(
                        'post_type' => 'estate',
                        'post_title' => $title,
                        'post_content' => $description,
                        'post_status' => 'publish'
                    );
                    $post_id = wp_insert_post($post_args);

                    if (is_wp_error($post_id)) {
                        wp_redirect(add_query_arg('error', 'post_creation_failed'));
                        exit;
                    }

                    set_post_thumbnail($post_id, $thumbnail_id);
                    update_post_meta($post_id, 'area', $area);
                    update_post_meta($post_id, 'price', $price);
                    update_post_meta($post_id, 'address', $address);
                    update_post_meta($post_id, 'living_area', $living_area);
                    update_post_meta($post_id, 'floor', $floor);
                    update_post_meta($post_id, 'city', $city_id);
                    wp_redirect(get_permalink($post_id));
                    exit;
                }
            }
        }
    }
}


// Регистрируем обработчик действия
add_action('admin_post_add_real_estate', 'add_real_estate');
add_action('admin_post_nopriv_add_real_estate', 'add_real_estate');
