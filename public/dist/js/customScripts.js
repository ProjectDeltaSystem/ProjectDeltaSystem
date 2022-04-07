function formatCurrency(number) {

    return new Intl.NumberFormat(
        'pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }
    ).format(number);
}

function formatNumber(number) {
    return number.toFixed(2).replace('.', ',');
}


function formatToNumber(numberFormat) {
    number = numberFormat.replace('R$', '');
    number = number.replace('.', '');
    number = number.replace(',', '.');
    number = number.replace(/&nbsp;/g, '');
    return parseFloat(number);
}

function formatDate(date) {
    forMouth = ((date.getMonth()) + 1);
    if (forMouth < 10) {
        forMouth = '0' + forMouth;
    }

    forDate = date.getDate() + "/" + forMouth + "/" + date.getFullYear();

    if (date.getSeconds() < 10) {
        forHour = date.getHours() + ':' + date.getMinutes() + ':0' + date.getSeconds();
    } else {
        forHour = date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
    }

    return forDate + ' ' + forHour;
}