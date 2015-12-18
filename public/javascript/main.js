/**
 * Конфиг сообщений об ошибке
 */
var responseMessage = {
    exists: 'Данная валюта уже добавлена в базу',
    notFound: 'Валюта не найдена'
};

/**
 * Обновить данные котировок
 */
function updateQuotation() {
    $('input.js-updateQuotation').val('Обновляется...');
    $.ajax({
        url: '/index/update',
        data: {},
        success: function(result) {
            var response = $.parseJSON(result);
            if (response === 'success') {
                $.ajax({
                    url: '/index/index',
                    data: {},
                    success: function(result) {
                        $('div.js-tableContainer').html($(result).find('table.js-table'));
                    }
                });
            } else {
                alert('Ошибка!');
            }
            $('input.js-updateQuotation').val('Обновить данные курса валют');
        }
    });
}

/**
 * Добавить валюту
 */
function addCurrency() {
    var letterCode = $('input.js-currencyLetterCode').val();
    if (!letterCode) {
        return;
    }
    $('input.js-addCurrency').val('Обновляется...');
    $.ajax({
        url: '/index/add',
        data: {currencyLetterCode: letterCode},
        success: function(result) {
            var response = $.parseJSON(result);
            var resultMessage = responseMessage[response];
            if (resultMessage) {
               alert(resultMessage);
               $('input.js-addCurrency').val('Добавить валюту');
            } else if (response === 'success') {
                $.ajax({
                    url: '/index/index',
                    data: {},
                    success: function(result) {
                        $('div.js-tableContainer').html($(result).find('table.js-table'));
                    }
                });
            } else {
                alert('Ошибка!');
            }
            $('input.js-addCurrency').val('Добавить валюту');
        }
    });
}

/**
 * Удалить валюту
 * 
 * @param {int}     currencyId  id валюты
 * @param {Object}  button      Объект кнопки удаления
 */
function deleteCurrency(currencyId, button) {
    if (!currencyId) {
        return;
    }
    $(button).val('Удаление...');
    $.ajax({
        url: '/index/delete',
        data: {currencyId: currencyId},
        success: function(result) {
            var response = $.parseJSON(result);
            var resultMessage = responseMessage[response];
            if (resultMessage) {
               alert(resultMessage);
               $(button).val('Добавить валюту');
            } else if (response === 'success') {
                $.ajax({
                    url: '/index/index',
                    data: {},
                    success: function(result) {
                        $('div.js-tableContainer').html($(result).find('table.js-table'));
                    }
                });
            } else {
                alert('Ошибка!');
            }
            $(button).val('Удалить валюту');
        }
    });
}