var _currentMediaPage   = 1;
var _currentInput       = 0;
var _hasMoreMedia       = true;
var _result             = [];

function _showModalHandler(e) {
    _currentInput = $(this).data('id');
    console.log(_currentInput);
    $('#media-modal').modal("show");
}

$(document).ready(function () {
    var $mediaModal = $('#media-modal');

    $('*[data-toggle="tooltip"]').tooltip();

    $('span.input-group-btn').each(function (index) {
        $(this).on('click', _showModalHandler);
    });

    $mediaModal.on('show.bs.modal', function (event) {
        var $modalTitle = $('.modal-title', $mediaModal);
        var $modalBody  = $('.modal-body.library-grid', $mediaModal);

        ( 'Select Media' === $modalTitle.html() ) ? $modalTitle.html() : $modalTitle.html('Select Media');

        if('' === $modalBody.html().trim()) {
            $.ajax({
                method: 'GET',
                url: constant.api.media.Index,
                data: {page: 1},
                success: function (response) {
                    _result = response;

                    for(var i=0; i<_result.length; i++) {
                        var media       = _result[i];
                        var mediaUrl    = siteUrl + '/media/view/' + media.id;

                        var elem = ('<div class="grid">'+
                        '<div class="gridInner">'+
                        '<img alt="'+media.filename+'" src="'+mediaUrl+'">'+
                        '<div class="titleGrid" title="'+media.filename+'" data-media-id="'+media.id+'" data-media-url="'+mediaUrl+'">'+media.filename+'</div>'+
                        '</div>'+
                        '</div>');

                        $modalBody.append(elem);
                    }

                    $('.library-grid .titleGrid').each(function (index) {
                        $(this).unbind('click').on('click', function (e) {
                            e.preventDefault();
                            $('#bird_form_photos_'+_currentInput).val($(this).data('media-url'));
                            $mediaModal.modal('hide');
                        })
                    });

                    // if(response.length > 0) {
                    //     _currentMediaPage += 1;
                    //     _hasMoreMedia = true;
                    // }
                    // else {
                    //     _hasMoreMedia = false;
                    // }
                }
            });
        }

        // $modalBody.unbind('scroll').on('scroll', function () {
        //     if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight && _hasMoreMedia) {
        //         console.log('loading more');
        //         $.ajax({
        //             method: 'GET',
        //             url: constant.api.media.Index,
        //             data: {page: _currentMediaPage},
        //             success: function (response) {
        //                 _result = response;
        //
        //                 for(var i=0; i<_result.length; i++) {
        //                     var media = _result[i];
        //
        //                     var elem = ('<div class="grid">'+
        //                     '<div class="gridInner">'+
        //                     '<img alt="'+media.filename+'" src="/media/view/'+media.id+'">'+
        //                     '<div class="titleGrid" title="'+media.filename+'" data-media-id="'+media.id+'">'+media.filename+'</div>'+
        //                     '</div>'+
        //                     '</div>');
        //
        //                     $modalBody.append(elem);
        //                 }
        //
        //                 $('.library-grid .titleGrid').each(function (index) {
        //                     $(this).unbind('click').on('click', function (e) {
        //                         e.preventDefault();
        //                         // console.log((event.relatedTarget));
        //                     })
        //                 });
        //
        //                 if(response.length > 0) {
        //                     _currentMediaPage += 1;
        //                     _hasMoreMedia = true;
        //                 }
        //                 else {
        //                     _hasMoreMedia = false;
        //                 }
        //             }
        //         });
        //     }
        // });
    });
    
    $('.load-more-media').on('click', function (e) {
        e.preventDefault();
        var $me  = $(this);
        var $cog = $('i.fa-cog', this);
        var $modal = $('.modal-body.library-grid');

        $cog.addClass('fa-spin');

        $.ajax({
            method: 'GET',
            url: constant.api.media.Index,
            data: {page: _currentMediaPage + 1},
            success: function (response) {
                $cog.removeClass('fa-spin');

                var medias = response;

                if(medias.length > 0) {
                    _currentMediaPage++;

                    for(var i=0; i<medias.length; i++) {
                        var media       = medias[i];
                        var mediaUrl    = siteUrl + '/media/view/' + media.id;

                        var elem = ('<div class="grid">' +
                        '<div class="gridInner">' +
                        '<img alt="' + media.filename + '" src="'+mediaUrl+'">' +
                        '<div class="titleGrid" title="' + media.filename + '"  data-media-id="'+media.id+'" data-media-url="'+mediaUrl+'">' + media.filename + '</div>' +
                        '</div>' +
                        '</div>');

                        $('.library-grid').append(elem);
                    }

                    $('.library-grid .titleGrid').each(function (index) {
                        $(this).unbind('click').on('click', function (e) {
                            e.preventDefault();
                            $('#bird_form_photos_'+_currentInput).val($(this).data('media-url'));
                            $mediaModal.modal('hide');
                        })
                    });

                    $modal.animate({scrollTop: $modal[0].scrollHeight}, 1000);
                }
                else if (medias.length == 0){
                    console.log(response);
                    $me.prop('disabled', true);
                }
            },
            error: function (errResponse) {
                $cog.removeClass('fa-spin');
            }
        });
    })
});