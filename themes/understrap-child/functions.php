<?php
// Регистрация типа поста "Недвижимость"
function create_estate_post_type() {
    $labels = array(
        'name' => 'Недвижимость',
        'singular_name' => 'Недвижимость',
        'menu_name' => 'Недвижимость',
        'add_new' => 'Добавить новую',
        'add_new_item' => 'Добавить новую недвижимость',
        'edit' => 'Редактировать',
        'edit_item' => 'Редактировать недвижимость',
        'new_item' => 'Новая недвижимость',
        'view' => 'Просмотр',
        'view_item' => 'Просмотреть недвижимость',
        'search_items' => 'Искать недвижимость',
        'not_found' => 'Ничего не найдено',
        'not_found_in_trash' => 'В корзине ничего не найдено',
        'parent' => 'Родительская недвижимость'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-admin-home',
        'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
    );

    register_post_type( 'estate', $args );
}
add_action( 'init', 'create_estate_post_type' );

// Регистрация таксономии "Тип недвижимости"
function create_estate_taxonomy() {
    $labels = array(
        'name' => 'Тип недвижимости',
        'singular_name' => 'Тип недвижимости',
        'menu_name' => 'Тип недвижимости',
        'all_items' => 'Все типы',
        'edit_item' => 'Редактировать тип',
        'view_item' => 'Просмотреть тип',
        'add_new_item' => 'Добавить новый тип',
        'new_item_name' => 'Новый тип недвижимости',
        'search_items' => 'Искать типы',
        'popular_items' => 'Популярные типы',
        'not_found' => 'Типы не найдены',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
    );

    register_taxonomy( 'estate_type', 'estate', $args );
}
add_action( 'init', 'create_estate_taxonomy' );

// Регистрация типа поста "Город"
function create_city_post_type() {
    $labels = array(
        'name' => 'Города',
        'singular_name' => 'Город',
        'menu_name' => 'Города',
        'add_new' => 'Добавить новый',
        'add_new_item' => 'Добавить новый город',
        'edit' => 'Редактировать',
        'edit_item' => 'Редактировать город',
        'new_item' => 'Новый город',
        'view' => 'Просмотр',
        'view_item' => 'Просмотреть город',
        'search_items' => 'Искать города',
        'not_found' => 'Города не найдены',
        'not_found_in_trash' => 'В корзине города не найдены',
        'parent' => 'Родительский город'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-location',
        'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
    );

    register_post_type( 'city', $args );
}
add_action( 'init', 'create_city_post_type' );

// Регистрация метаполей для типа поста "Недвижимость"
function add_estate_meta_boxes() {
    add_meta_box( 'estate_meta_box', 'Дополнительные поля', 'estate_meta_box_callback', 'estate', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'add_estate_meta_boxes' );

function estate_meta_box_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'estate_meta_box_nonce' );
    $city = get_post_meta( $post->ID, 'city', true );
    $city_options = get_posts( array( 'post_type' => 'city', 'posts_per_page' => -1 ) );
    $area = get_post_meta( $post->ID, 'area', true );
    $price = get_post_meta( $post->ID, 'price', true );
    $address = get_post_meta( $post->ID, 'address', true );
    $living_area = get_post_meta( $post->ID, 'living_area', true );
    $floor = get_post_meta( $post->ID, 'floor', true );
    $gallery_images = get_post_meta( $post->ID, 'gallery_images', true );
    $gallery_images_ids = explode( ',', $gallery_images );
    ?>

    <p>
        <label for="area">Площадь:</label>
        <input type="text" id="area" name="area" value="<?php echo esc_attr( $area ); ?>">
    </p>

    <p>
        <label for="price">Стоимость:</label>
        <input type="text" id="price" name="price" value="<?php echo esc_attr( $price ); ?>">
    </p>

    <p>
        <label for="address">Адрес:</label>
        <input type="text" id="address" name="address" value="<?php echo esc_attr( $address ); ?>">
    </p>

    <p>
        <label for="living_area">Жилая площадь:</label>
        <input type="text" id="living_area" name="living_area" value="<?php echo esc_attr( $living_area ); ?>">
    </p>

    <p>
        <label for="floor">Этаж:</label>
        <input type="text" id="floor" name="floor" value="<?php echo esc_attr( $floor ); ?>">
    </p>
    <p>
        <label for="city">Город:</label>
        <select id="city" name="city">
            <option value="">Выберите город</option>
            <?php foreach ( $city_options as $option ) : ?>
                <option value="<?php echo $option->ID; ?>" <?php selected( $city, $option->ID ); ?>><?php echo $option->post_title; ?></option>
            <?php endforeach; ?>
        </select>
    </p>

    <p>
        <label for="gallery_images">Изображения:</label>
        <input type="hidden" id="gallery_images" name="gallery_images" value="<?php echo esc_attr( $gallery_images ); ?>">
    <div class="image-gallery">
        <ul class="gallery-thumbnails">
            <?php
            if ( ! empty( $gallery_images_ids ) ) {
                foreach ( $gallery_images_ids as $image_id ) {
                    $image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
                    if ( $image_url ) {
                        echo '<li><img src="' . esc_url( $image_url ) . '"><a href="#" class="remove-image">Remove</a></li>';
                    }
                }
            }
            ?>
        </ul>
        <input type="button" class="button add-gallery-image" value="Добавить изображение">
    </div>
    </p>
    <?php
}

function save_estate_meta( $post_id ) {
    if ( ! isset( $_POST['estate_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['estate_meta_box_nonce'], basename( __FILE__ ) ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( isset( $_POST['area'] ) ) {
        update_post_meta( $post_id, 'area', sanitize_text_field( $_POST['area'] ) );
    }

    if ( isset( $_POST['city'] ) ) {
        update_post_meta( $post_id, 'city', sanitize_text_field( $_POST['city'] ) );
    }

    if ( isset( $_POST['price'] ) ) {
        update_post_meta( $post_id, 'price', sanitize_text_field( $_POST['price'] ) );
    }

    if ( isset( $_POST['address'] ) ) {
        update_post_meta( $post_id, 'address', sanitize_text_field( $_POST['address'] ) );
    }

    if ( isset( $_POST['living_area'] ) ) {
        update_post_meta( $post_id, 'living_area', sanitize_text_field( $_POST['living_area'] ) );
    }

    if ( isset( $_POST['floor'] ) ) {
        update_post_meta( $post_id, 'floor', sanitize_text_field( $_POST['floor'] ) );
    }
    if ( isset( $_POST['gallery_images'] ) ) {
        update_post_meta( $post_id, 'gallery_images', sanitize_text_field( $_POST['gallery_images'] ) );
    }

}
add_action( 'save_post_estate', 'save_estate_meta' );

add_action( 'admin_enqueue_scripts', function() {
    wp_enqueue_script( 'admin_js', get_stylesheet_directory_uri() .'/admin/admin-script.js', array( 'jquery' ) );
    wp_enqueue_style( 'admin_css', get_stylesheet_directory_uri() . '/admin/admin-style.css', false, '1.0.0' );
} );

function enqueue_single_city_scripts() {
    wp_enqueue_style( 'style_css', get_stylesheet_directory_uri() . '/style.css', false, '1.0.0' );
    // Регистрация скрипта
    wp_register_script('single-city-script', get_stylesheet_directory_uri() . '/js/single-city-script.js', array('jquery'), '1.0', false);

    // Подключение скрипта
    wp_enqueue_script('single-city-script');

    // Передача данных из PHP в скрипт
    wp_localize_script('single-city-script', 'singleCityData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('single-city-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_single_city_scripts');