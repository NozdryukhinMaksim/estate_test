(function($) {
    // Функция для фильтрации недвижимости по типу
    function filterEstateByType(value) {
        var url = window.location.href;

        // Если тип недвижимости выбран, добавляем параметр к URL
        if (value && value !== 'all') {
            url = updateQueryStringParameter(url, 'estate_type', value);
        } else {
            // Иначе, удаляем параметр из URL
            url = removeQueryStringParameter(url, 'estate_type');
        }

        // Переходим по новому URL
        window.location.href = url;
    }

    // Обновление параметра в URL
    function updateQueryStringParameter(url, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = url.indexOf('?') !== -1 ? "&" : "?";

        if (url.match(re)) {
            return url.replace(re, '$1' + key + "=" + value + '$2');
        } else {
            return url + separator + key + "=" + value;
        }
    }

    // Удаление параметра из URL
    function removeQueryStringParameter(url, key) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");

        if (url.match(re)) {
            return url.replace(re, '$1').replace(/(&|\?)$/, '');
        } else {
            return url;
        }
    }

    // Запуск скрипта после полной загрузки страницы
    $(document).ready(function() {
        // Проверяем значение параметра estate_type в URL
        var estateType = getParameterByName('estate_type');
        if (!estateType) {
            // Выбираем значение "Все" в выпадающем списке
            $('#estate-filter').val('all');
        }

        // Обработчик события изменения значения фильтра
        $('#estate-filter').on('change', function() {
            var selectedValue = $(this).val();
            filterEstateByType(selectedValue);
        });
    });

    // Получение значения параметра из URL
    function getParameterByName(name) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(window.location.href);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }
})(jQuery);
