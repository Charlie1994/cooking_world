/**
 * Widget SortableListView: a list view whose items can be dragged in different order
 *
 */
"use strict";
$(function () {
    $('.sortable-list').each(function () {
        var isDragging = false,
            totalHeight = 0,
            item_array = [],
            $list = $(this),
            $items = $list.find('.sortable-item'),
            myCloneitem;// the item that is used as added item
        $items.each(function () {
            if(!myCloneitem)
                myCloneitem = $(this).clone();
            init_item($(this));
        });
        // the absolute layout does not have height so the height of div should be changed manually
        $list.css('height', totalHeight+'px');

        function init_item($item) {
            var $buttons = $item.find('.sortable-btns'),
                $addButton =  $item.find('.add-btn'),
                $deleteButton =  $item.find('.delete-btn'),
                $dragButton =  $item.find('.drag-btn'),
                buttonHeight = $deleteButton.height(),
                $itemContent = $item.find('.item-content'),
                $clonedItem, $clonedItemContent,
                dragImg = new Image(),
                startOffsetX, // the x axis when drag event starts
                startOffsetY, // the y axis when drag event starts
                offsetX, // the offset of mouse while an item is being dragged
                offsetY, // the offset of mouse while an item is being dragged
                topY,
                swapAnimation = false,
                cloneAnimation = false,
                itemHeight = $item.height();
            // the cloned items have been added into the array
            if(item_array.indexOf($item)==-1){
                totalHeight += itemHeight;
                item_array.push($item);
            }
            $buttons.css('height', itemHeight+'px');
            $addButton.css({
                'top': (itemHeight-buttonHeight)/2+'px',
                'opacity': 0
            });
            $dragButton.css({
                'top': (itemHeight-buttonHeight)/2+'px',
                'opacity': 0
            });
            $deleteButton.css('top', (itemHeight-buttonHeight)/2+'px');
            dragImg.src = "../public/asset/images/transparent.png";

            // set the position of this item
            if(!$item.is(":animated")){
                topY = item_array.indexOf($item)*itemHeight;
                $item.css('top', topY+'px');
            }

            $item
                .on('dragstart drag dragend', function (e) {
                    e.preventDefault();
                })
                .on('dragstart', function (e) {
                    isDragging = true;
                    $item.css({
                        'z-index': 100
                    });
                    $itemContent.css({
                        "background-color": "var(--lightgray)"
                    });
                    $deleteButton.finish();
                    $deleteButton.css('opacity', 0);
                    $addButton.finish();
                    $addButton.css('opacity', 0);
                    // the offset of the mouse
                    topY = getCSSNumber($item, "top");
                    e.originalEvent.dataTransfer.setDragImage(dragImg,0,0);
                    // $item.css('opacity', 0);
                    startOffsetX = parseInt(e.originalEvent.clientX);
                    startOffsetY = parseInt(e.originalEvent.clientY);
                    $clonedItem = $item.clone();
                    $clonedItem.css({
                        'z-index': -1
                    });
                    $clonedItemContent = $clonedItem.find('.item-content');
                    var clonedBtns = $clonedItem.find('.sortable-btns');
                    clonedBtns.css('display', 'none');
                    var $wrap = $clonedItemContent.find('.wrap');
                    $wrap.css({
                        'opacity': 0.35
                    });
                    $clonedItemContent.css('border', '2px dashed var(--concrete)');
                    document.body.style.cursor='all-scroll';
                    $list.append($clonedItem);
                })
                .on('drag', function (e) {
                    offsetX= parseInt(e.originalEvent.clientX);
                    offsetY= parseInt(e.originalEvent.clientY);
                    if (offsetX === 0 && offsetY === 0){
                        return;
                    }
                    var calculatedOffsetY = topY + offsetY - startOffsetY,
                        calculatedOffsetX = offsetX - startOffsetX;
                    $item.css({
                        "top": calculatedOffsetY+'px',
                        'left': calculatedOffsetX+'px'
                    });
                    if(swapAnimation || cloneAnimation || item_array.length == 1){
                        return;
                    }
                    var itemIndex = item_array.indexOf($item),
                        $prevItem, $nextItem;
                    // if the item is not at the top of the list
                    if(itemIndex != 0){
                        $prevItem = item_array[itemIndex-1];
                        var prevtop = getCSSNumber($prevItem, "top"),
                            prevHeight = $prevItem.height();
                        var swapThreshold = prevtop + prevHeight/9;

                        if(calculatedOffsetY <= swapThreshold){
                            swap($prevItem, $clonedItem, $item);
                            return;
                        }
                    }
                    // if the item is not ar the bottom of the list
                    if(itemIndex != (item_array.length-1)){
                        $nextItem = item_array[itemIndex+1];
                        var nexttop = getCSSNumber($nextItem, "top"),
                            nextHeight = $nextItem.height();
                        var swapThreshold = nexttop - nextHeight/8;
                        if(calculatedOffsetY >= swapThreshold){
                            swap($nextItem, $clonedItem, $item);
                        }
                    }
                })
                .on('dragend', function (e) {
                    $item.css({
                        'z-index': 1
                    });
                    $itemContent.css({
                        "background-color": "var(--lightgray)"
                    });
                    $deleteButton.animate({'opacity': 1},'fast');
                    $addButton.animate({'opacity': 1},'fast');
                    document.body.style.cursor='auto';
                    item_array.forEach(function (item) {
                        if(item.is(':animated'))
                            item.finish();
                    });
                        var clonetop = getCSSNumber($clonedItem, "top"),
                        cloneleft = getCSSNumber($clonedItem, "left");
                    $item.animate({
                            "top":clonetop+'px',
                            "left":cloneleft+'px'},
                        function () {
                            $clonedItem.remove();
                        });
                    $item.attr('draggable', 'false');
                    isDragging = false;
                })
                .on('mouseover', function () {
                    if(isDragging) return;
                    item_array.forEach(function (item) {
                        if(item.is($item)){
                            item
                                .removeClass('not-over')
                                .addClass('over');
                        }else{
                            item
                                .addClass('not-over')
                                .removeClass('over');
                        }
                    });
                    $itemContent.css({
                        "background-color": "var(--lightgray)"
                    });
                    $deleteButton.css('background-color', 'var(--silver)');
                    $addButton.animate({
                        'top': itemHeight/2 + 0.7*buttonHeight + 'px',
                        'opacity': 1
                    });
                    $dragButton.animate({
                        'top': itemHeight/2 - 1.6*buttonHeight + 'px',
                        'opacity': 1
                    })
                })
                .on('mouseleave', function () {
                    if(isDragging) return;
                    $itemContent.css('background-color', 'initial');
                    $addButton.finish();
                    $dragButton.finish();
                    $deleteButton.css('background-color', 'var(--clouds)');
                    $addButton.animate({
                        'top': (itemHeight-buttonHeight)/2+'px',
                        'opacity': 0
                    });
                    $dragButton.animate({
                        'top': (itemHeight-buttonHeight)/2+'px',
                        'opacity': 0
                    });
                    item_array.forEach(function (item) {
                        item.removeClass('over').removeClass('not-over');
                    });
                    });
            $dragButton
                .on('mousedown', function (e) {
                    $item.attr('draggable', 'true');
                });
            $addButton.on('click', function (e) {
                $list.append(myCloneitem);
                totalHeight += myCloneitem.height();
                $list.css('height', totalHeight+'px');
                var itemIndex = item_array.indexOf($item),
                    itemTop = getCSSNumber($item, "top");
                item_array.splice(itemIndex+1, 0, myCloneitem);
                myCloneitem.css('top', itemTop+'px');
                myCloneitem.animate({'top': (itemTop+itemHeight)+'px'});
                init_item(myCloneitem);
                for(var i = itemIndex+2;i<item_array.length;i++){
                    var temp = getCSSNumber(item_array[i], "top");
                    temp += myCloneitem.height();
                    item_array[i].animate({'top': temp+'px'});
                }
                $list.trigger('itemAdded', [myCloneitem]);
                myCloneitem = myCloneitem.clone();
                $list.trigger('orderChanged', [item_array]);
            });
            
            $deleteButton.on('click', function () {
                if(item_array.length <= 1){
                    alert("You cannot remove all the items.");
                    return;
                }
                var itemIndex = item_array.indexOf($item),
                    tempTop = getCSSNumber($item, "top")-itemHeight;
                $item.animate({'top':tempTop+'px', 'opacity':'0'}, function () {
                    $item.remove();
                });
                for(var i=itemIndex+1;i<item_array.length;i++){
                    var top = getCSSNumber(item_array[i], "top")-item_array[i].height();
                    item_array[i].animate({'top':top});
                }
                totalHeight -= itemHeight;
                $list.css('height', totalHeight+'px');
                item_array.splice(itemIndex, 1);
                $list.trigger('orderChanged', [item_array]);
            });
            function swap($swapItem, $cloneItem, $originalItem) {
                var swapItemTop = getCSSNumber($swapItem, "top"),
                    cloneItemTop = getCSSNumber($cloneItem, "top");

                var swapIndex = item_array.indexOf($swapItem),
                    originIndex = item_array.indexOf($originalItem),
                    swapTemp = item_array[swapIndex],
                    originTemp = item_array[originIndex];
                item_array[swapIndex] = originTemp;
                item_array[originIndex] = swapTemp;
                swapAnimation = true;
                cloneAnimation = true;
                $cloneItem.animate({'top':swapItemTop+'px'},
                    function () {
                        cloneAnimation = false;
                    });
                $swapItem.animate({'top': cloneItemTop+'px'},                       function () {
                    swapAnimation = false;
                });
                $list.trigger('orderChanged', [item_array]);
            }

            function getCSSNumber($item, propertyName) {
                return parseInt($item.css(propertyName), 10)
            }
        }
    });
});