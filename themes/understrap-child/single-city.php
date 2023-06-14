<?php
/*
 * Template Name: Single City
 * Template Post Type: cities
 */

get_header();
?>
<?php
$city_id = get_the_ID();
$city_description = get_the_content();
$city_image = get_the_post_thumbnail_url($city_id);
if(!isset($_GET['estate_type'])){
    $estate_args = array(
        'post_type' => 'estate',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'city',
                'value' => $city_id,
                'compare' => '='
            )
        ),
    );
}
else{
    $estate_args = array(
        'post_type' => 'estate',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'city',
                'value' => $city_id,
                'compare' => '='
            )
        ),
        'tax_query' => array(
            array(
                'taxonomy' => 'estate_type',
                'field' => 'slug', // Изменено на 'slug'
                'terms' => isset($_GET['estate_type']) ? $_GET['estate_type'] : '',
            ),
        ),
    );
}
$estate_query = new WP_Query($estate_args);
$estate_count = $estate_query->found_posts;
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2><?php the_title(); ?></h2>
            <div class="city-description">
                <?php echo $city_description; ?>
            </div>
            <?php if ($city_image) : ?>
                <div class="city-image">
                    <img src="<?php echo esc_url($city_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                </div>
            <?php endif; ?>
            <div class="estate-count">
                <p>Найдено недвижимости: <?php echo $estate_count; ?></p>
                <label for="estate-filter">Фильтровать по типу:</label>
                <select id="estate-filter" class="form-select" onchange="filterEstateByType(this.value)">
                    <option value="all" <?php echo (!isset($_GET['estate_type']) || $_GET['estate_type'] === 'all') ? 'selected' : ''; ?>>Все</option>
                    <?php
                    $estate_types = get_terms('estate_type');
                    foreach ($estate_types as $estate_type) {
                        $selected = (isset($_GET['estate_type']) && $_GET['estate_type'] === $estate_type->slug) ? 'selected' : '';
                        echo '<option value="' . esc_attr($estate_type->slug) . '" ' . $selected . '>' . esc_html($estate_type->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
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
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>


        </div>
    </div>
</div>

<?php
get_footer();
?>
