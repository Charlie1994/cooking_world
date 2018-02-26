/**
 * Widget DragAndDropBox a component for uploading images
 */
'use strict';
$(function () {
    $('.drag-drop-box').each(function () {
        var $uploadBox = $(this),
            isDropped = false,
            $input = $uploadBox.find('input[type="file"]'),
            $ic_camera = $uploadBox.find('.icon-camera'),
            $dropBlock = $uploadBox.find('.drag-block'),
            $blockLabel = $uploadBox.find('.drag-label'),
            $bar = $uploadBox.find('.float-bar'),
            $fileButton = $bar.find('strong'),
            $displayImg = $uploadBox.find('.display-img'),
            droppedFiles,
            uploadFile,
            showFile =
                function (file_url) {
                    uploadFile = file_url;
                    var reader = new FileReader();
                    reader.onload = function (ev)
                    {
                        var url = ev.target.result;
                        $displayImg.attr('src', url);
                    };
                    reader.readAsDataURL(file_url);
                    isDropped = true;
                    $fileButton.text("Change");
                    $uploadBox
                        .removeClass('no-photo')
                        .addClass('has-photo');
                };
        var filechangeFunc = function (ev)
        {
            var files = ev.target.files;
            if(files.length !== 0)
                showFile(ev.target.files[0]);
            // $(this).remove();
            // $uploadBox.append("<input type=\"file\" class=\"file-box\">");
            // $input = $uploadBox.find('input[type="file"]');
            // $input.on("change", filechangeFunc);
        };
        $input.on('change', filechangeFunc);
        $fileButton.click(function (ev) {
            $input.trigger('click');
        });
        $uploadBox
            .on( 'drag dragstart dragend dragover dragenter dragleave drop', function( e )
                {
                    // preventing the unwanted behaviours
                    e.preventDefault();
                    e.stopPropagation();
                })
            .on( 'drop', function( e )
            {
                droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
                showFile(droppedFiles[0]);
            })
            .mouseenter( function (ev)
            {
                if(!$uploadBox.hasClass('has-photo')){
                    $dropBlock.css('visibility', 'visible');
                    $ic_camera.css('visibility', 'hidden');
                    $blockLabel.css('display', 'block');
                }
                $bar.finish();
                $bar.animate({
                    'bottom': "0"
                });
            })
            .mouseleave( function (ev)
            {
                $dropBlock.removeAttr('style');
                $ic_camera.removeAttr('style');
                $blockLabel.removeAttr('style');
                $bar.finish();
                $bar.animate({
                    'bottom': "-3.5em"
                });
            });
        $uploadBox.on('requestUploadFile', function (e, requestItem)  {
             e.stopPropagation();
             var resp = new Object();
             if($uploadBox.hasClass('has-photo')){
                 resp.hasPhoto = true;
                 resp.file = uploadFile;
             }else{
                 resp.hasPhoto = false;
             }
             requestItem.trigger('responseUploadFile', [resp]);
         });
    });
});