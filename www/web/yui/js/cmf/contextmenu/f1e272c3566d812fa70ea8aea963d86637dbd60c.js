                jQuery(document).ready(function() {

                    var id      = ".menu-xp";
                    var menu  =             [
                { "Go to site": 
                    {
                        onclick:function() {
                                window.location.href= "/en/"; 
                        },
                        icon:'/bundles/sfynxtemplate/images/icons/contextmenu/rotation-16.png?v1_0_0'
                    }
                },
                { "Go to Admin":
                    {
                        onclick:function() {
                                window.location.href= "/redirectionuser"; 
                        },
                        icon:'/bundles/sfynxtemplate/images/icons/contextmenu/rotation-16-inverse.png?v1_0_0'
                    }
                },
                $.contextMenu.separator,
                { '<span class="org-chart-page">Flowchart pages</span>':  
                    {
                        onclick:function() {
                        },
                        icon:'/bundles/sfynxtemplate/images/icons/contextmenu/organigramme-16.png?v1_0_0'
                    }
                },
                { '<span class="org-tree-page" >Tree of pages</span>':  
                    {
                        onclick:function() {
                        },
                        icon:'/bundles/sfynxtemplate/images/icons/contextmenu/tree-16.png?v1_0_0'
                    }
                },
                $.contextMenu.separator,
                { '<span class="page_action_refresh" >Refresh page</span>': 
                    {
                        onclick:function() {
                               window.location.href= "/admin/refresh-page"; 
                        },
                        icon:'/bundles/sfynxtemplate/images/icons/contextmenu/maj-16.png?v1_0_0'
                    }
                },                
                { '<span class="veneer_blocks_widgets" >Show the structure of the page</span>': 
                    {
                        onclick:function() {
                        },
                        icon:'/bundles/sfynxtemplate/images/icons/contextmenu/block-16.png?v1_0_0'
                    }
                },
                { '<span class="page_action_new" >Add a page</span>': 
                    {
                        onclick:function() {
                        },
                        icon:'/bundles/sfynxtemplate/images/icons/contextmenu/add-page-16.png?v1_0_0'
                    }
                },
                { '<span class="page_action_edit" >Edit the Page</span>': 
                    {
                        onclick:function() {
                        },
                        icon:'/bundles/sfynxtemplate/images/icons/contextmenu/update-page-16.png?v1_0_0'
                    }
                },   
                { '<span class="page_action_copy" >Copy the page</span>': 
                    {
                        onclick:function() {
                               window.location.href= "/admin/copy-page"; 
                        },
                        icon:'/bundles/sfynxtemplate/images/icons/contextmenu/copy-page-16.png?v1_0_0'
                    }
                },                  
                $.contextMenu.separator,
                { '<span class="page_action_archivage" >Indexing of page</span>': 
                    {
                        onclick:function() {
                        },
                        icon:'/bundles/sfynxtemplate/images/icons/contextmenu/archivage-16.png?v1_0_0'
                    }
                },    
                { '<span class="page_action_desarchivage" >removing indexation of page</span>': 
                    {
                        onclick:function() {
                        },
                        icon:'/bundles/sfynxtemplate/images/icons/contextmenu/desarchivage-16.png?v1_0_0'
                    }
                },    
                $.contextMenu.separator,
                { '<span class="img_action_viewer" >Viewer</span>': 
                    {
                        onclick:function() {
                        },
                        icon:'/bundles/sfynxtemplate/images/icons/contextmenu/viewer-16.gif?v1_0_0'
                    }
                },                
                $.contextMenu.separator,
                { 'Logout': 
                    {
                        onclick:function() {
                                window.location.href= "/logout"; 
                        },
                        icon:'/bundles/sfynxtemplate/images/icons/contextmenu/quitter-16.png?v1_0_0'
                    }
                }
            ]
            
        ;

                    $(id).contextMenu(menu,{

                                                theme: 'pi2',
                                                    
                        
                                                shadow:true,
                                                                        shadowOpacity:.4,
                                                                        shadowColor:'#000',
                                                                        shadowOffset: 13,
                                                                        shadowWidthAdjust: -3,
                                                                        shadowHeightAdjust: -3,
                                                                        
                                                                                                
                        
                            
                        showSpeed:1000,
                            
                            
                        hideSpeed:1000,
                            

                            
                        showTransition:'fadeIn',
                                
                            
                        hideTransition:'fadeOut',
                                                                                        

                        showCallback:function() {
                                                    },
                        hideCallback:function() {
                                                    },
                        beforeShow:function() {
                                                    },                
                    });

                    // add the left click mouse for tablette.
                    $(id).click(function(e){
                        var element = e.target;
                        var evt     = element.ownerDocument.createEvent('MouseEvents');
                        var RIGHT_CLICK_BUTTON_CODE = 2; // the same for FF and IE

                        evt.initMouseEvent('contextmenu', true, true,
                             element.ownerDocument.defaultView, 1, e.screenX, e.screenY, e.clientX, e.clientY, false,
                             false, false, false, RIGHT_CLICK_BUTTON_CODE, null);

                        if (document.createEventObject){
                            // dispatch for IE
                           return element.fireEvent('onclick', evt)
                         }
                        else{
                           // dispatch for firefox + others
                          return element.dispatchEvent(evt);
                        }
                    });                    
                                                            
                });
        