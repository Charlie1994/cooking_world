$(function () {
    var uploadImgModal = $('#upload-image-modal'),
        uploadConfirmbtn = uploadImgModal.find('.confirm-btn'),
        dragDropBox = uploadImgModal.find('.drag-drop-box'),
        displayImgModal = $('#display-image-modal'),
        confirmBoxModal = $('#confirm-modal'),
        $triggerItem,savedFile,
        modalConfirm = function(callback){
            confirmBoxModal.find("#modal-btn-yes").on("click", function(){
                callback(true);
                confirmBoxModal.modal('hide');
            });

            confirmBoxModal.find("#modal-btn-no").on("click", function(){
                callback(false);
                confirmBoxModal.modal('hide');
            });
        };

    $('.add-photo-box').each(function () {
       initItem($(this));
    });

    $.prototype.initAddPhotoBox = function () {
        if($(this).hasClass('add-photo-box'))
            initItem($(this));
    };

    $.prototype.showModal = function ($item) {
        if($(this).attr('id') == "upload-image-modal"){
            var modal = $(this);
            modal.modal('show');
            $triggerItem = $item;
        }
    };

    uploadConfirmbtn.on('click', function () {
        dragDropBox.trigger('requestUploadFile', [$triggerItem]);
        uploadImgModal.modal('hide');
    });

    uploadImgModal.on('hide.bs.modal', function () {
        var box = uploadImgModal.find('.drag-drop-box');
        box.removeClass('has-photo').addClass('no-photo');
        uploadImgModal.find('.float-bar strong').text("Choose a photo");
    });

    displayImgModal.on('displayPhoto', function (e, url) {
        var displayimg = displayImgModal.find('img');
        displayimg.attr('src', url);
        displayImgModal.modal('show');
    });
    function initItem($photobox) {
       var $addbtn = $photobox.find('.add-photo-btn'),
           $deletebtn = $photobox.find('.delete-photo-btn'),
           $changebtn = $photobox.find('.change-photo-btn'),
           $displayimg = $photobox.find('.display-img'),
           $camera_icon = $photobox.find('.icon-camera');
       $photobox
           .on('mouseover', function () {
               if($photobox.hasClass('no-photo')){
                   $addbtn.css('display', 'block');
                   $camera_icon.css('display', 'none');
               }
           })
           .on('mouseleave', function () {
               if($photobox.hasClass('no-photo')){
                   $addbtn.removeAttr('style');
                   $camera_icon.removeAttr('style');
               }
           });
        $addbtn.on('click', function () {
            uploadImgModal.showModal($photobox);
        });
        $deletebtn.on('click', function () {
            confirmBoxModal.modal('show');
            modalConfirm(function(confirm){
                if(confirm){
                    $displayimg.attr('src', '');
                    $photobox.removeClass('has-photo').addClass('no-photo');
                    savedFile = null;
                }
            });
        });
        $displayimg.on('click', function () {
            var src = $displayimg.attr('src');
            displayImgModal.trigger('displayPhoto', [src]);
        });
        $changebtn.on('click', function () {
            uploadImgModal.showModal($photobox);
        });
        $photobox.on('responseUploadFile', function (e, response) {
            if(response.hasPhoto === true){
                var fileurl = response.file;
                savedFile = fileurl;
                showFile(fileurl);
            }
        });
        function showFile(file_url) {
            var reader = new FileReader();
            reader.onload = function (ev)
            {
                var url = ev.target.result;
                $displayimg.attr('src', url);
            };
            reader.readAsDataURL(file_url);
            $photobox.removeClass('no-photo').addClass('has-photo');
        }
    }
});