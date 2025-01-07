function copyTranslation() {
    $('.lang_key').each(function(index) {
            var _this = $(this)
            $.post(language_update_url, {
                _token: $('input[name=_token]').val(),
                id: index,
                code: document.getElementById("language_code").value,
                status: $(this).text()
            }, function(data) {
                const tsestQ = document.getElementsByClassName("value");
                _this.siblings('.lang_value').find('input').val(data);
            });
        });
    }

    function sort_keys(el) {
        $('#sort_keys').submit();
    }
