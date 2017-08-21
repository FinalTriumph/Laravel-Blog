/* global $ */
$(document).ready(function() {
    $('.post_div_background_image').hover(function() {
        $(this).css('height', $(this).height());
        $('.back_img', this).css('width', '110%');
        $('.back_img', this).css('margin-left', '-5%');
        $('.back_img', this).css('margin-top', '-3%');
    }, function() {
        $('.back_img', this).css('width', '100%');
        $('.back_img', this).css('margin-left', '0');
        $('.back_img', this).css('margin-top', '0');
    });
});