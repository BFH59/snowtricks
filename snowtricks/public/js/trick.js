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
    const index_video = +$('#widgets-counter-videos').val();

    const tmpl_video = $('#trick_videos').data('prototype').replace(/__name__/g, index_video);

    $('#trick_videos').append(tmpl_video);

    $('#widgets-counter-videos').val(index_video + 1);
    handleVideoDeleteButton();
});

function handleVideoDeleteButton(){
    $('button[data-action="delete"]').click(function(){
        const target_video = this.dataset.target;
        $(target_video).remove();
    });
}


function updateVideoCounter(){
    const count_video = $('#trick_videos div.form-group').length;
    $('#widgets-counter-videos').val(count_video);
}
updateVideoCounter();
handleVideoDeleteButton();

/*$(document).ready(function () {
    bsCustomFileInput.init()
})*/

$(document).on('change', '.custom-file-input', function () {
    let fileName = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
    $(this).parent('.custom-file').find('.custom-file-label').text(fileName);
});