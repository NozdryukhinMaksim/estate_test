
<div class="container">
<form id="real-estate-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add_real_estate">

    <div class="form-group">
        <label for="title">Название</label>
        <input type="text" name="title" id="title" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="area">Площадь</label>
        <input type="text" name="area" id="area" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="price">Цена</label>
        <input type="text" name="price" id="price" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="address">Адрес</label>
        <input type="text" name="address" id="address" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="living_area">Жилая площадь</label>
        <input type="text" name="living_area" id="living_area" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="floor">Этаж</label>
        <input type="text" name="floor" id="floor" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="thumbnail">Изображение</label>
        <input type="file" name="thumbnail" id="thumbnail" class="form-control-file" accept="image/*" required>
    </div>

    <div class="form-group">
        <label for="description">Описание</label>
        <textarea name="description" id="description" class="form-control" required></textarea>
    </div>

    <div class="form-group">
        <label for="city">Город</label>
        <select name="city" id="city" class="form-control" required>
            <option value="">Выберите город</option>
            <?php
            $cities = get_posts( array( 'post_type' => 'city'));
            foreach ($cities as $city) {
                echo '<option value=' . $city->ID . ' > ' . $city->post_title . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="estate_type">Тип недвижимости</label>
        <select name="estate_type" id="estate_type" class="form-control" required>
            <option value="">Выберите тип недвижимости</option>
            <?php
            $estate_types = get_terms(array(
                'taxonomy' => 'estate_type',
                'hide_empty' => false,
            ));
            foreach ($estate_types as $estate_type) {
                echo '<option value="' . $estate_type->term_id . '">' . $estate_type->name . '</option>';
            }
            ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Добавить недвижимость</button>
</form>
</div>
