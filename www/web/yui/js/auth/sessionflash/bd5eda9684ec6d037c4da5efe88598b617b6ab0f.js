                $(document).ready(function() {
                    // Messages are injected into the overlay fancybox
                    var layout_flash_message = $("#confirm-popup-flash").html();
                    if (layout_flash_message != null && layout_flash_message.length != 0) {
                        $.fancybox({
                        	'wrapCSS': 'fancybox-sfynx',
                            'type': 'inline',
                            'autoDimensions':true,
                            'height': 'auto',
                            'padding':0,
                            'content': layout_flash_message
                        });
                    }        
                });
        