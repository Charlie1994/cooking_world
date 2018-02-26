$(function () {
   var $dropdowns = $('.dropdown');
   $dropdowns.each(function () {
       //TODO hover action
       // var isbtnhover,ismenuhover = false,
       //     dropdown = $(this),qid,
       //     menu = dropdown.find('.dropdown-menu');
       // dropdown.hover(function () {
       //     dropdown.addClass('open');
       //     isbtnhover = true;
       //     console.log(ismenuhover+'  '+isbtnhover);
       //
       // });
       // dropdown.mouseout(function () {
       //     // clearTimeout(qid);
       //     qid = setTimeout(function () {
       //         isbtnhover = false;
       //         if(ismenuhover === false)
       //             dropdown.removeClass('open');
       //         console.log(ismenuhover+'  '+isbtnhover);
       //     },500);
       //
       // });
       // menu.hover(function () {
       //     ismenuhover = true;
       //     console.log(ismenuhover+'  '+isbtnhover);
       //
       // });
       // menu.mouseout(function (e) {
       //     console.log('out');
       //     // clearTimeout(qid);
       //     if(isbtnhover === false)
       //         dropdown.removeClass('open');
       //     qid = setTimeout(function () {
       //         ismenuhover = false;
       //         console.log(ismenuhover+'  '+isbtnhover);
       //     },500);
       // });
       var box = $(this);
       box.click(function () {
           if(box.hasClass('open'))
               box.removeClass('open');
           else
               box.addClass('open');
       });
   });
});