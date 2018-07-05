$(document).ready(function() {
    handleNoItems();

    $('.js-dismiss').click(function() {
        $(this).closest('.alert').addClass('hide');
    });

    addItemListeners();
});

function refreshItems() {
    $('#item-holder').html('<p style="text-align:center; width:100%"><i class="fa fa-spinner fa-4x fa-spin"></i></p>');

    $.ajax({
        url: '/refresh-items',
        type: 'POST',
        data: 'incomplete=0',
        success: function(result) {
            $('#item-holder').html(result);

            handleNoItems();
            addItemListeners();
        }
    });
}

function handleNoItems() {
    if ($('#item-holder').children().length == 0) {
        $('#no-items-msg').removeClass('hide');
    }
    else {
        $('#no-items-msg').addClass('hide');
    }
}

function addItemListeners() {
    $('#item-holder').find('.js-incomplete').click(function() {
        var panel = $(this).closest('.panel'),
            title = $.trim($(panel).find('.panel-heading').html()),
            id = $(panel).attr('data-id');

        $(this).html('Marking Incomplete').attr('disabled', true);
        $('#incomplete-generic-error').addClass('hide');
        $('#incomplete-success').addClass('hide');
        $('#incomplete-title').html(title);
        $(this).addClass('js-incompleting');

        $.ajax({
            url: '/change-status',
            type: 'POST',
            data: 'complete=0&itemID='+encodeURIComponent(id),
            success: function(result) {
                if (result) {
                    $('#incomplete-success').removeClass('hide');
                    refreshItems();
                }
                else {
                    $('#incomplete-generic-error').removeClass('hide');
                }

                $('.js-incompleting').html('Mark Incomplete').attr('disabled', false);
                $('.js-incompleting').removeClass('.js-incompleting');
            },
            error: function() {
                $('#incomplete-generic-error').removeClass('hide');
                $('.js-incompleting').html('Mark Incomplete').attr('disabled', false);
                $('.js-incompleting').removeClass('.js-incompleting');
            }
        });
    });
}