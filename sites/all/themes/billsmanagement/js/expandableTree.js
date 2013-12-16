/**************************************************************/
/* Prepares the cv to be dynamically expandable/collapsible   */
/**************************************************************/
function prepareList() {
    $('.menu').find('li:has(ul)')
    .click(function(event) {
        if (this == event.target &&!$(this).children('ul').is(":animated")) {
            if( $(this).hasClass("collapsed")){
                $(this).removeClass('collapsed');
                $(this).addClass('expanded');
            } else {
                $(this).addClass('collapsed');
                $(this).removeClass('expanded');
            }
            $(this).children('ul').toggle('medium');
        }
        return false;
    })
    .removeClass('expanded')
    .addClass('collapsed')
    .children('ul').hide();

    $('.menu').find('a').click(function(event) {
        if("Review History" == $(event.target).text()){
             $(event.target).parent('li').trigger("click");
        }
        var href = $(event.target).attr('href');
        $.post(href, {}, function(data){
            $("#pr_right_content").html($(data).find("#pr_right_content").html())
        }, "html");
    });
}

//    //Create the button funtionality
//    $('#expandList')
//    .unbind('click')
//    .click( function() {
//        $('.collapsed').addClass('expanded');
//        $('.collapsed').children().show('medium');
//    })
//    $('#collapseList')
//    .unbind('click')
//    .click( function() {
//        $('.collapsed').removeClass('expanded');
//        $('.collapsed').children().hide('medium');
//    })

/**************************************************************/
/* Functions to execute on loading the document               */
/**************************************************************/
$(document).ready( function() {
    prepareList()
});

