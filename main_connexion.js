$(document).ready(function(){

    function validateAlph(field)
    {
        var f_value = field.val();
        regexp = new RegExp(/^[^0-9-\/:<>=+&_?!\[\]\{\},;%@*°\'"~²^§]*$/);
        var test = regexp.test(f_value);

        if(test && f_value.length > 1)
        {
            field.removeClass('error_b');
            return 1;
        }
        else
        {
            field.addClass('error_b');
            return 0;
        }
    }

    function validateForm(field) {
        var x = field.val();
        var atpos = x.indexOf("@");
        var dotpos = x.lastIndexOf(".");
        if (atpos< 1 || dotpos<atpos+2 || dotpos+2>=x.length) {
            field.addClass('error_b');
            return 0;
        }
        else
        {
            field.removeClass('error_b');
            return 1;
        }
    }


    $('#register_form').on('submit', function(e) 
    {
        e.preventDefault();

        var $form = $(this);
        var firstname = $('#user_registration_first_name');
        var lastname = $('#user_registration_last_name');
        var email = $('#user_registration_email');
        var fb = $('#user_registration_flying_blue_id');
        var reglement = $('#registration_regle');

        var valide_firstname = validateAlph(firstname);
        var valide_lastname = validateAlph(lastname);
        var valide_email = validateForm(email);

        if(valide_firstname && valide_lastname && valide_email)
        {
            $('.errorMsg').html('');
            
            var _type		= $form.attr('method');
            var _url 		= $form.data('action');
            var _url_redirection = $form.data('redirection');
            var _data		= $(this).serialize()  
            // start ajax 
            $.ajax({
                url: _url,
                data: _data,
                type: _type,
                cache : false
            }).done(function ( response ) {  
                var _status_ = response.status;
                var _errors_ = response.errors;
                if ( _status_ == 'ko' ) {
                    $form.find('.errorMsg').show().text(JSON.stringify(_errors_));
                }
                
                if(_errors_[0].id.length === 0)
                {
                    //console.log(_errors_['flying_blue_id'].id);  && !_errors_['flying_blue_id'].id
                    $('.errorMsg').html('');
                    
                    var fb_value = fb.val();

                    if(fb_value.length > 0 && fb_value.length < 10 )
                    {
                        fb.addClass('error_b');
                        $('.errorMsg').html('Ce numéro Flying Blue est  incorrect');
                    }
                    else
                    {
                        fb.removeClass('error_b');
                        $('.errorMsg').html('');
                        if(!reglement.is(':checked'))
                        {
                            $('.errorMsg').html('Vous devez accepter le règlement afin de pouvoir participer au jeu');
                        }
                        else
                        {
                            $('.errorMsg').html('');
                            //document.forms["register_form"].submit();
                            if (_url_redirection != "") {
                                window.location.href = _url_redirection;
                            } else {
                                window.location.reload();
                            }                    
                        }
                    }
                }
                else
                {
                    $('.errorMsg').html('email utilisé');
                }
                
                
            });  
            
            

            
        }
        else
        {
            $('.errorMsg').html('Le/Les champ(s) en rouge sont vides ou incorrects');
        }
        
    });
    
    $('#login_form').on('submit', function(e) 
    {
        e.preventDefault();

        var $form = $(this);
        var firstname = $('#_password');
        var email = $('#_username');

        var valide_firstname = validateAlph(firstname);
        var valide_email = validateForm(email);

        if(valide_firstname && valide_email)
        {
            $('.errorMsg').html('');
            
            var _url 		= $form.data('action');
            var _type		= $form.attr('method');
            var _data		= $(this).serialize()            
            $.ajax({
                url: _url,
                data: _data,
                type: _type
            }).done(function ( response ) {   
                if ( response == 'error' ) {
                    $('.errorMsg').html('Le/Les champ(s) en rouge sont vides ou incorrects');
                } else {
                    var _status_ = response.status;
                    var _url_redirection = response.redirection;
                    if (_url_redirection == "") {
                        window.location.reload();
                    } else {
                        window.location.href = _url_redirection;
                    }
                }
            });
            //document.forms["login_form"].submit();
        }
        else
        {
            $('.errorMsg').html('Le/Les champ(s) en rouge sont vides ou incorrects');
        }
        
    });    
    
});