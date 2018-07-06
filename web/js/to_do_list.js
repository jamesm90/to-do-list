$(document).ready(function() {
    handleNoItems();

    $('#add-item').click(function() {
        $('#modal-details-title').html('New');
        $('#id-to-save').val('');

        $('#item-title-field').val('');
        $('#item-date-field').val('');
        $('#item-desc-field').val('');

        loadDetailsModal();
    });

    $('#save-item').click(function() {
        var title = $.trim($('#item-title-field').val()),
            desc = $.trim($('#item-desc-field').val()),
            date = $.trim($('#item-date-field').val()),
            id = $('#id-to-save').val();

        $('#item-errors').closest('.panel').addClass('hide');
        $('#item-generic-error').addClass('hide');
        $(this).html('Saving').attr('disabled', true);

        $.ajax({
            url: '/save',
            type: 'POST',
            data: 'title='+encodeURIComponent(title)+'&desc='+encodeURIComponent(desc)+'&date='+encodeURIComponent(date)+'&itemID='+encodeURIComponent(id),
            success: function(result) {
                if (result.errors.length == 0) {
                    refreshItems();
                }
                else {
                    var errorHtml = '';

                    var fields = Object.keys(result.errors);
                    for (var i=0; i < fields.length; i++) {
                        errorHtml += '<li>'+fields[i]+' - '+result.errors[fields[i]]+'</li>';
                    }

                    $('#item-errors').html(errorHtml);
                    $('#item-errors').closest('.panel').removeClass('hide');

                    $('#save-item').html('Save').attr('disabled', false);
                }
            },
            error: function() {
                $('#item-generic-error').removeClass('hide');
                $('#save-item').html('Save').attr('disabled', false);
            }
        });
    });

    $('#remove-item').click(function() {
        var id = $('#id-to-delete').val();

        $(this).html('Removing').attr('disabled', true);
        $('#remove-generic-error').addClass('hide');

        $.ajax({
            url: '/remove',
            type: 'POST',
            data: 'itemID='+encodeURIComponent(id),
            success: function(result) {
                if (result) {
                    refreshItems();
                }
                else {
                    $('#remove-generic-error').removeClass('hide');
                    $('#remove-item').html('Remove Item').attr('disabled', false);
                }
            },
            error: function() {
                $('#remove-generic-error').removeClass('hide');
                $('#remove-item').html('Remove Item').attr('disabled', false);
            }
        });
    });

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
        data: 'incomplete=1',
        success: function(result) {
            $('#item-holder').html(result);
            $('#modal-details').modal('hide');
            $('#modal-delete').modal('hide');

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
    $('#item-holder').find('.js-edit-link').click(function() {
        var panel = $(this).closest('.panel'),
            title = $.trim($(panel).find('.panel-heading').text());

        $('#item-title-field').val(title);
        $('#item-date-field').val($(panel).attr('data-due'));
        $('#item-desc-field').val($(panel).find('.js-item-desc').text());
        $('#id-to-save').val($(panel).attr('data-id'));

        $('#modal-details-title').html('Edit');
        loadDetailsModal();
    });

    $('#item-holder').find('.js-remove-link').click(function() {
        var panel = $(this).closest('.panel'),
            title = $.trim($(panel).find('.panel-heading').html());

        $('#delete-item-title').html(title);
        $('#id-to-delete').val($(panel).attr('data-id'));

        $('#remove-item').html('Remove Item').attr('disabled', false);
        $('#modal-delete').modal('show');
    });

    $('#item-holder').find('.js-complete').click(function() {
        var panel = $(this).closest('.panel'),
            title = $.trim($(panel).find('.panel-heading').html()),
            id = $(panel).attr('data-id');

        $(this).html('Completing').attr('disabled', true);
        $('#complete-generic-error').addClass('hide');
        $('#complete-success').addClass('hide');
        $('#complete-title').html(title);
        $(this).addClass('js-completing');

        $.ajax({
            url: '/change-status',
            type: 'POST',
            data: 'complete=1&itemID='+encodeURIComponent(id),
            success: function(result) {
                if (result) {
                    $('#complete-success').removeClass('hide');
                    refreshItems();
                }
                else {
                    $('#complete-generic-error').removeClass('hide');
                }

                $('.js-completing').html('Mark Complete').attr('disabled', false);
                $('.js-completing').removeClass('.js-completing');
            },
            error: function() {
                $('#complete-generic-error').removeClass('hide');
                $('.js-completing').html('Mark Complete').attr('disabled', false);
                $('.js-completing').removeClass('.js-completing');
            }
        });
    });
}

function loadDetailsModal() {
    $('#item-errors').closest('.panel').addClass('hide');
    $('#item-generic-error').addClass('hide');
    $('#save-item').html('Save').attr('disabled', false);
    $('#modal-details').modal({ 'show': true, 'backdrop': 'static' });
}
