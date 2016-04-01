                jQuery(document).ready(function(){        
                    var  create_content_form  = $(".snippet_collection");
                    var  insert_content_form  = $(".insert_collection");
    
                    create_content_form.parents('.clearfix').hide();
                    $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_id_snippet").attr("required", "required");
                    $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_configCssClass").removeAttr("required");
                    $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_plugin").removeAttr("required");
                    $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_action").removeAttr("required");
                    $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_configXml").removeAttr("required");

                    $("input[id='sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_choice_0']").change(function () {
                        if ($(this).is(':checked')){
                            create_content_form.parents('.clearfix').hide();
                            insert_content_form.parents('.clearfix').show();
    
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_id_snippet").attr("required", "required");
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_configCssClass").removeAttr("required");
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_plugin").removeAttr("required");
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_action").removeAttr("required");
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_configXml").removeAttr("required");
                        } else {
                            create_content_form.parents('.clearfix').show();
                            insert_content_form.parents('.clearfix').hide();
    
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_id_snippet").removeAttr("required");
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_configCssClass").attr("required", "required");
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_plugin").attr("required", "required");
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_action").attr("required", "required");
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_configXml").attr("required", "required");
                        }
                       });
                    $("input[id='sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_choice_1']").change(function () {
                        if ($(this).is(':checked')){
                            create_content_form.parents('.clearfix').show();
                            insert_content_form.parents('.clearfix').hide();
    
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_id_snippet").removeAttr("required");
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_configCssClass").attr("required", "required");
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_plugin").attr("required", "required");
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_action").attr("required", "required");
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_configXml").attr("required", "required");
                        } else {
                            create_content_form.parents('.clearfix').hide();
                            insert_content_form.parents('.clearfix').show();
    
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_id_snippet").attr("required", "required");
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_configCssClass").removeAttr("required");
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_plugin").removeAttr("required");
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_action").removeAttr("required");
                            $("#sfynxtemplatebundlemanagerformbuilderpimodelwidgetsnippet_configXml").removeAttr("required");
                        }
                       });
                                          
                });
        