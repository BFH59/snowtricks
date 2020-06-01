$('#add_image').click(function(){
    const index = +$('#widgets-counter').val();

    const tmpl = $('#trick_images').data('prototype').replace(/__name__/g, index);

    $('#trick_images').append(tmpl);

    $('#widgets-counter').val(index + 1);
    handleDeleteButton();

});

function handleDeleteButton(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        $(target).remove();
    });
}


function updateCounter(){
    const count = $('#trick_images div.form-group').length;
    $('#widgets-counter').val(count);
}
updateCounter();
handleDeleteButton();

$('#add_video').click(function(){
    const index = +$('#widgets-counter').val();

    const tmpl = $('#trick_videos').data('prototype').replace(/__name__/g, index);

    $('#trick_videos').append(tmpl);

    $('#widgets-counter').val(index + 1);
    handleDeleteButton();
});

function handleDeleteButton(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        $(target).remove();
    });
}


function updateCounter(){
    const count = $('#trick_videos div.form-group').length;
    $('#widgets-counter').val(count);
}
updateCounter();
handleDeleteButton();

$('.custom-file-input').on('change', function(event) {
    var inputFile = event.currentTarget;
    $(inputFile).parent()
        .find('.custom-file-label')
        .html(inputFile.files[0].name);
});