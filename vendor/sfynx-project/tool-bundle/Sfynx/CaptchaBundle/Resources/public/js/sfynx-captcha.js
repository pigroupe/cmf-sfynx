$(function() {    
    var sfynxCaptchalaunch = function(el) {
        $(".item-challenge").removeClass("checked");
        $('.item-challenge').css('border', '2px solid lightgray').find('.checked').hide();
        el.addClass("checked").find(".checked").show();
        el.css('border', '2px solid red').find('.checked').show();
        _key = el.data('key');
        el.parent().parent().find("[data-key='sfynx-captcha-hidden-value']").val(_key);
    };     
    /*  for jquery < 1.9 */
    $(this).find('.item-challenge').live( "click", function() {
        sfynxCaptchalaunch( $(this) );
    });    
    /* for jquery >= 1.9 */
    $(this).find('.item-challenge').on( "click", function() {
        sfynxCaptchalaunch( $(this) );
    });     
});