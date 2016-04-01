                jQuery(document).ready(function() {

                    // we add the admin block Template to all widget
                    $("sfynx[id^='block__']").each(function(index) {
                        id_block = $(this).data("id");

                        var movies = [
                                      { id: id_block},
                                    ];

                        /* Render the adminblockTemplate with the "movies" data */ 
                        $("#adminblockTemplate").tmpl( movies ).prependTo(this);
                        /* Allow to draggable the block */
                        $(this).attr('data-drag', 'dragmap_block');
                    });    
                    // we add the admin widget Template to all widget
                    $("sfynx[id^='widget__']").each(function(index) {
                        id_widget = $(this).data("id");
                        var movies = [
                                      { id: id_widget},
                                    ];
                        /* Render the adminwidgetTemplate with the "movies" data */ 
                        $("#adminwidgetTemplate").tmpl( movies ).prependTo(this);
                        /* Allow to sortable the block */
                        $(this).attr('data-drag', 'dragmap_widget');
                    });
                    
                    /********************************
                     * start page action with click
                     ********************************/                     
                    $("span[class^='page_action_']").click( function() {
                        var _class    = $(this).attr('class');
                        var height = jQuery(window).height();
            
                        if (_class == "page_action_archivage"){
                            // start ajax 
                            $.ajax({
                                url: "/admin/indexation-page/archiving",
                                data: "",
                                datatype: "json",
                                cache: false,
                                "beforeSend": function ( xhr ) {
                                    //xhr.overrideMimeType("text/plain; charset=x-user-defined");
                                },
                                "statusCode": {
                                    404: function() {
                                    }
                                }                                              
                            }).done(function ( response ) {
                                //$('#page-action-dialog').html(response);
                                $('#page-action-dialog').html("You have successfully indexed a page !");
                                $('#page-action-dialog').attr('title', 'Indexing of page');
                                $('#page-action-dialog').dialog({
                                      height: 180,
                                      width: 400,
                                        open: function () {
                                       },
                                       beforeClose: function () {
                                           $('#page-action-dialog').html(' ');
                                       },
                                      buttons: {
                                          Ok: function () {
                                              $(this).dialog("close");
                                          }
                                      },
                                    show: 'scale',
                                    hide: 'scale',
                                    collapsingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                    expandingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                }).dialogExtend({
                                    "closable" : true,
                                    "maximizable" : true,
                                    "minimizable" : true,
                                    "collapsable" : true,
                                    "dblclick" : "collapse",
                                    "titlebar" : "transparent",
                                    "minimizeLocation" : "right",
                                    "icons" : {
                                      "close" : "ui-icon-circle-close",
                                      "maximize" : "ui-icon-circle-plus",
                                      "minimize" : "ui-icon-circle-minus",
                                      "collapse" : "ui-icon-triangle-1-s",
                                      "restore" : "ui-icon-bullet"
                                    },
                                });                            
                     	    });
                            // end ajax    
                        }

                        if (_class == "page_action_desarchivage"){
                            // start ajax 
                            $.ajax({
                                url: "/admin/indexation-page/delete",
                                data: "",
                                datatype: "json",
                                cache: false,
                                "beforeSend": function ( xhr ) {
                                    //xhr.overrideMimeType("text/plain; charset=x-user-defined");
                                },
                                "statusCode": {
                                    404: function() {
                                    }
                                }               
                            }).done(function ( response ) {
                                $('#page-action-dialog').html("You have successfully removed a page !");
                                $('#page-action-dialog').attr('title', 'removing indexation of page');
                                $('#page-action-dialog').dialog({
                                      height: 180,
                                      width: 400,
                                        open: function () {
                                       },
                                       beforeClose: function () {
                                           $('#page-action-dialog').html(' ');
                                       },
                                      buttons: {
                                          Ok: function () {
                                              $(this).dialog("close");
                                          }
                                      },
                                    show: 'scale',
                                    hide: 'scale',
                                    collapsingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                    expandingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                }).dialogExtend({
                                    "closable" : true,
                                    "maximizable" : true,
                                    "minimizable" : true,
                                    "collapsable" : true,
                                    "dblclick" : "collapse",
                                    "titlebar" : "transparent",
                                    "minimizeLocation" : "right",
                                    "icons" : {
                                      "close" : "ui-icon-circle-close",
                                      "maximize" : "ui-icon-circle-plus",
                                      "minimize" : "ui-icon-circle-minus",
                                      "collapse" : "ui-icon-triangle-1-s",
                                      "restore" : "ui-icon-bullet"
                                    },
                                });                         
                     	    });
                            // end ajax    
                        }                        
                        
                        if (_class == "page_action_edit"){
                            // start ajax 
                            $.ajax({
                                url: "/admin/urlmanagement-page",
                                data: "type=page&action=edit&routename=admin_keyword",
                                datatype: "json",
                                cache: false,
                                cache: false,
                                "beforeSend": function ( xhr ) {
                                    //xhr.overrideMimeType("text/plain; charset=x-user-defined");
                                },
                                "statusCode": {
                                    404: function() {
                                    }
                                }             
                            }).done(function ( response ) {
                                var url = response[0].url;
                                $("#page-action-dialog").html('<iframe id="modalIframeId" width="100%" height="99%" style="overflow-x: hidden; overflow-y: auto" marginWidth="0" marginHeight="0" frameBorder="0" src="'+url+'" />').dialog({
                                     width: 840,
                                     height: height/1.5,
                                     open: function () {
                                         $(this).attr('title', 'Updating page');
                                     },
                                     beforeClose: function () {
                                         window.location.href= "/admin/refresh-page";
                                     },
                                     show: 'scale',
                                     hide: 'scale',
                                     collapsingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                     expandingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                }).dialogExtend({
                                    "closable" : true,
                                    "maximizable" : true,
                                    "minimizable" : true,
                                    "collapsable" : true,
                                    "dblclick" : "collapse",
                                    "titlebar" : "transparent",
                                    "minimizeLocation" : "right",
                                    "icons" : {
                                      "close" : "ui-icon-circle-close",
                                      "maximize" : "ui-icon-circle-plus",
                                      "minimize" : "ui-icon-circle-minus",
                                      "collapse" : "ui-icon-triangle-1-s",
                                      "restore" : "ui-icon-bullet"
                                    },
                                });                           
                     	    });
                            // end ajax    
                        }

                        if (_class == "page_action_new"){
                            // start ajax 
                            $.ajax({
                                url: "/admin/urlmanagement-page",
                                data: "type=page&action=new",
                                datatype: "json",
                                cache: false,
                                "beforeSend": function ( xhr ) {
                                    //xhr.overrideMimeType("text/plain; charset=x-user-defined");
                                },
                                "statusCode": {
                                    404: function() {
                                    }
                                }  
                            }).done(function ( response ) {
                                var url = response[0].url;
                                $("#page-action-dialog").html('<iframe id="modalIframeId" width="100%" height="99%" style="overflow-x: hidden; overflow-y: auto" marginWidth="0" marginHeight="0" frameBorder="0" src="'+url+'" />').dialog({
                                     width: 840,
                                     height: height/1.5,
                                     open: function () {
                                         $(this).attr('title', 'Create page');
                                     },
                                     beforeClose: function () {
                                         var routename = $(this).find('iframe').contents().find("#piapp_adminbundle_pagetype_route_name").val();
                                         //console.log(routename);
                                         $.ajax({
                                          url: "/admin/urlmanagement-page",
                                          data: "type=routename" + "&routename=" + routename + "&action=url",
                                          datatype: "json",
                                          cache: false,
                                          error: function(msg){ alert( "Error !: " + msg );},            
                                          success: function(response){
                                              var url = response[0].url;
                                              window.location.href= url;
                                          }
                                      });
                                      // end ajax    
                                     },
                                     show: 'scale',
                                     hide: 'scale',
                                     collapsingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                     expandingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                }).dialogExtend({
                                    "closable" : true,
                                    "maximizable" : true,
                                    "minimizable" : true,
                                    "collapsable" : true,
                                    "dblclick" : "collapse",
                                    "titlebar" : "transparent",
                                    "minimizeLocation" : "right",
                                    "icons" : {
                                      "close" : "ui-icon-circle-close",
                                      "maximize" : "ui-icon-circle-plus",
                                      "minimize" : "ui-icon-circle-minus",
                                      "collapse" : "ui-icon-triangle-1-s",
                                      "restore" : "ui-icon-bullet"
                                    },
                                });                             
                     	    });
                            // end ajax    
                        }    
                                            
                    });
                    // end click                        
                    
                    /********************************
                     * start block action with click
                     ********************************/ 
                    $("a[class^='block_action_']").click( function() {
                        var id     = $(this).data('id');
                        var action = $(this).data('action');
                        var title  = $(this).attr('title');
                        var _class = $(this).attr('class');
                        var height = jQuery(window).height();
                        // start ajax 
                        $.ajax({
                            url: "/admin/urlmanagement-page",
                            data: "id=" + id + "&action=" + action + "&type=block",
                            datatype: "json",
                            cache: false,
                            "beforeSend": function ( xhr ) {
                                //xhr.overrideMimeType("text/plain; charset=x-user-defined");
                            },
                            "statusCode": {
                                404: function() {
                                }
                            } 
                        }).done(function ( response ) {
                            var url = response[0].url;
                            $("#block-action-dialog").html('<iframe id="modalIframeId" width="100%" height="99%" style="overflow-x: hidden; overflow-y: auto" marginWidth="0" marginHeight="0" frameBorder="0" src="'+url+'" />').dialog({
                                 width: 840,
                                 height: height/1.5,
                                 open: function () {
                                     $(this).attr('title', 'Form ' + title);
                                 },
                                 beforeClose: function () {
                                     window.location.href= "/admin/refresh-page"; 
                                 },
                                 show: 'scale',
                                 hide: 'scale',
                                 collapsingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                 expandingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                             }).dialogExtend({
                                 "closable" : true,
                                 "maximizable" : true,
                                 "minimizable" : true,
                                 "collapsable" : true,
                                 "dblclick" : "collapse",
                                 "titlebar" : "transparent",
                                 "minimizeLocation" : "right",
                                 "icons" : {
                                   "close" : "ui-icon-circle-close",
                                   "maximize" : "ui-icon-circle-plus",
                                   "minimize" : "ui-icon-circle-minus",
                                   "collapse" : "ui-icon-triangle-1-s",
                                   "restore" : "ui-icon-bullet"
                                 },
                               });                       
                 	    });
                        // end ajax        
                    });
                    // end click
                    
                    /********************************
                     * start widget action with click
                     ********************************/                     
                    $("a[class^='widget_action_']").click( function() {
                        var id         = $(this).data('id');
                        var action    = $(this).data('action');
                        var title    = $(this).attr('title');
                        var _class    = $(this).attr('class');
                        var height = jQuery(window).height();
                        // start ajax 
                        $.ajax({
                            url: "/admin/urlmanagement-page",
                            data: "id=" + id + "&action=" + action + "&type=widget",
                            datatype: "json",
                            cache: false,
                            "beforeSend": function ( xhr ) {
                                //xhr.overrideMimeType("text/plain; charset=x-user-defined");
                            },
                            "statusCode": {
                                404: function() {
                                }
                            } 
                        }).done(function ( response ) {
                            var url = response[0].url;
                            if ( (_class == "widget_action_delete") || (_class== "widget_action_move_up") || (_class== "widget_action_move_down") ){
                                $('#widget-action-dialog').dialog({
                                    height: 180,
                                    width: 400,
                                    open: function () {
                                        $(this).attr('title', 'Form ' + title);
                                        if (_class == "widget_action_delete")
                                            $(this).html('Are you sure you want to delete the widget?');
                                        if (_class == "widget_action_move_up")
                                            $(this).html('Are you sure you want to move up the Widget?');
                                        if (_class == "widget_action_move_down")
                                            $(this).html('Are you sure you want to move down the Widget?');
                                        
                                        $(this).find('iframe').attr('style', 'width: 100%;height: 100%');
                                    },
                                    buttons: {
                                        Cancel: function () {
                                            $(this).dialog("close");
                                        },
                                        Ok: function () {
                                            $.ajax({
                                                url: url,
                                                data:"",
                                                datatype: "json",
                                                cache: false,
                                                error: function(msg){ alert( "Error !: " + msg );},                 
                                                success: function(response){
                                                    window.location.href= "/admin/refresh-page";
                                                }
                                            });
                                        }
                                    },
                                    show: 'scale',
                                    hide: 'scale',
                                    collapsingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                    expandingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                }).dialogExtend({
                                    "closable" : true,
                                    "maximizable" : true,
                                    "minimizable" : true,
                                    "collapsable" : true,
                                    "dblclick" : "collapse",
                                    "titlebar" : "transparent",
                                    "minimizeLocation" : "right",
                                    "icons" : {
                                      "close" : "ui-icon-circle-close",
                                      "maximize" : "ui-icon-circle-plus",
                                      "minimize" : "ui-icon-circle-minus",
                                      "collapse" : "ui-icon-triangle-1-s",
                                      "restore" : "ui-icon-bullet"
                                    },
                                });
                            } else {
                                $('#widget-action-dialog').html('<iframe id="modalIframeId" width="100%" height="99%" style="overflow-x: hidden; overflow-y: auto" marginWidth="0" marginHeight="0" frameBorder="0" src="'+url+'" />').dialog({
                                    width: 971,
                                    height: height/1.5,
                                    overlay: {
                                        backgroundColor: '#000',
                                        opacity: 0.5
                                    },
                                    open: function () {
                                        $(this).attr('title', 'Formulaire ' + title);
                                    },
                                    beforeClose: function () {
                                        window.location.href= "/admin/refresh-page"; 
                                    },
                                    show: 'scale',
                                    hide: 'scale',
                                    collapsingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                    expandingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                }).dialogExtend({
                                    "closable" : true,
                                    "maximizable" : true,
                                    "minimizable" : true,
                                    "collapsable" : true,
                                    "dblclick" : "collapse",
                                    "titlebar" : "transparent",
                                    "minimizeLocation" : "right",
                                    "icons" : {
                                      "close" : "ui-icon-circle-close",
                                      "maximize" : "ui-icon-circle-plus",
                                      "minimize" : "ui-icon-circle-minus",
                                      "collapse" : "ui-icon-triangle-1-s",
                                      "restore" : "ui-icon-bullet"
                                    },
                                });                    
                            }                        
                 	    });
                        // end ajax                        
                    });
                    // end click    

                    $(".img_action_viewer").click( function() {
                        $("img").each(function(index) {
                            var scr = $(this).attr("src");
                            var height = $(this).height();
                            var width  = $(this).width();
                            if ($("#viewer_"+index).is(':visible')){
                                $(this).show();
                                $("#viewer_"+index).remove();
                            } else {
                                $(this).before('<div id="viewer_'+index+'" class="viewer" style="height:'+height+'px;width:'+width+'px" ></div>');
                                $(this).hide();
                                $("#viewer_"+index).iviewer({
                                   src: scr,
                                   update_on_resize: true,
                                   zoom_animation: true,
                                   onMouseMove: function(ev, coords) { },
                                   onStartDrag: function(ev, coords) { return true; }, //this image will be dragged
                                   onDrag: function(ev, coords) { }
                               });    
                           }                   
                        });
                    });
                    
                });
        