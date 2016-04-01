                jQuery(document).ready(function() {                    
                    $(".block_action_menu").css("display", 'none');
                    $(".widget_action_menu").css("display", 'none');
                    $(".veneer_blocks_widgets").click( function() {       
                        if ($(':ui-veneer').is(':visible')){
                            $("sfynx[id^='block__'] h6").css("display", 'none');
                            $(":ui-veneer").veneer( "destroy" );
                            $("sfynx[id^='block__']").off();
                            $("sfynx[id^='widget__']").off();
                        } else {
                            $(".captcha").remove();
                            // we set up the venner on all blocks
                            //$(".block_action_menu").css("display", 'inline-block');
                            //$("sfynx[id^='block__'] h6").css("display", 'block');
                            $("sfynx[id^='block__']").veneer( {disabled: true, title: "<span></span>"} );
                            $("sfynx[id^='block__'] h6").veneer( {collapsible: true, uiBorder: true, title: "WIDGET" } ).parent().css("display", 'both');
                            // we add the admin block Template to all widget
                            $("sfynx[id^='block__']").each(function(index) {
                                var id_block      = $(this).data('id');
                                var id_name_block = $(this).data("name");
                                /* Allow to draggable the block */
                                $("#ui-dialog-title-block__"+id_block+" span").html("ZONE " + id_name_block);
                            });
                            $( "sfynx[id^='block__']" )
                            .mouseenter(function() {
                                $( this ).find("h5.block_action_menu").attr("style", 'display:inline-block !important');
                            })
                            .mouseleave(function() {
                                $( this ).find("h5.block_action_menu").attr("style", 'display:none !important;');
                            });
                            $( "sfynx[id^='widget__']" )
                            .mouseenter(function() {
                                //console.log('enter')
                                $( this ).find("h6.widget_action_menu").attr("style", 'display:block !important');
                            })
                            .mouseleave(function() {
                                //console.log('out')
                                $( this ).find("h6.widget_action_menu").attr("style", 'display:none !important;');
                            });


                            /********************************
                             * copy widget action with draggable/droppable
                             ********************************/     
                            // we draggable all block
                            $("[data-drag^='dragmap_block']").draggable({ zIndex: 99999999999999999 });
                            // we set the tooltip on all titles of block actions.
                            $(".block_action_menu  [title]").tooltip({
                                  position: {
                                      track: true,
                                      my: "center bottom-20",
                                      at: "center top",
                                    }
                            });    
                            $(".widget_action_menu [title]").tooltip({
                                  position: {
                                      track: true,
                                      my: "center bottom-20",
                                      at: "center top",
                                    }
                            });
                                                 
                            var id_start_block = 0;
                            var id_end_block;
                            var id_widget;
                            $("[data-drag^='dragmap_widget']").draggable({
                                // axis: "y", // Le sortable ne s'applique que sur l'axe vertical
                                //containment: ".shoppingList", // Le drag ne peut sortir de l'élément qui contient la liste
                                //handle: ".item", // Le drag ne peut se faire que sur l'élément .item (le texte)
                                distance: 10, // Le drag ne commence qu'à partir de 10px de distance de l'élément
                                // This event is triggered when dragging starts.
                                start: function(event, ui){
                                    id_start_block = $(this).parent().parent().data('id');
                                    id_widget      = $(this).data('id');

                                    $(this).width('227px');
                                    $("[data-drag^='dragmap_block']").css('min-height', '100px');
                                    $("[data-overflow^='visible']").css("overflow","visible") ;
                                    $(".mcontentwrapper, .flexcroll").css("overflow","visible") ;

                                },
                                // This event is triggered when dragging stop.
                                stop: function(event, ui){
                                },
                                zIndex: 99999999999999999
                            });

                            $("[data-drag^='dragmap_block']").droppable({
                                // Lorsque l'on relache un élément sur un block
                                drop: function(event, ui){

                                    if (id_start_block != 0) {
                                        id_end_block = $(this).data("id");    
                                        // On supprimer l'élément de la page, le setTimeout est un fix pour IE (http://dev.jqueryui.com/ticket/4088)
                                        setTimeout(function() { ui.draggable.remove(); }, 1);                                
                                        $("#hProBar").progressbar({ 
                                            value: 100, 
                                            animationOptions: {
                                                duration: 10000
                                            }
                                        });
                                        $.ajax({
                                            url: "/admin/widget/movewidget-page",
                                            data: "id_start_block=" + id_start_block + "&id_end_block=" + id_end_block + "&id_widget=" + id_widget,
                                            datatype: "json",
                                            //type: "POST",
                                            cache: false,
                                            "beforeSend": function ( xhr ) {
                                                //xhr.overrideMimeType("text/plain; charset=x-user-defined");
                                            },
                                            "statusCode": {
                                                404: function() {
                                                }
                                            }            
                                        }).done(function ( response ) {
                                            //var request = response[0].request;
                                            //alert(response);
                                            $("#hProBar").progressbar( "destroy" );
                                            window.location.href= "/admin/refresh-page";
                                 	    });
                                    }
                                },
                                // Lorsque l'on passe un élément au dessus d'un block
                                over: function(event, ui){
                                },
                                // Lorsque l'on quitte un block
                                out: function(event, ui){
                                }
                            });    
                        }                        
                    });
                });
        