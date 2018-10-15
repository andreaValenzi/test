$(document).ready(function () {

    $.ajax({
        url: '/transactions/',
        type: 'GET',
        success: function(transactions) {
            var divs = transactions.map(function (transaction) {
                return '<div "id"=' + transaction.id + ' class="test-Transactions__element" >' +
                '<div class="test-Transactions__attribute">' + transaction.id + '</div>' +
                '<div class="test-Transactions__attribute">' + transaction.user_id + '</div>' +
                '<div class="test-Transactions__attribute">' + transaction.product.id  + '</div>' +
                '<div class="test-Transactions__description">' + transaction.product.description + '</div>' +
                '<div class="test-Transactions__attribute">' + transaction.amount + '</div>' +
                '<div class="test-Transactions__attribute">' + transaction.currency + '</div>' +
                '</div>';
            });

            $('.test-Transactions__container').append(divs);
        },
        error: function (error) {
            console.log(error);
        }
    });

    $('.test-Transactions__container').on('click', '.test-Transactions__element', function(){

        $.ajax({
            url: '/transactions/' + this.attributes[0].value,
            type: 'DELETE',
            success: function(result) {

            },
            error: function (error) {
                console.log(error);
            }
        });

        $( this ).slideUp();
    });
});