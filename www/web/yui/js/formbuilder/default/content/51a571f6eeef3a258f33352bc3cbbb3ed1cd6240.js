                        
            jQuery(document).ready(function(){        
                var  create_content_form  = $(".content_collection");
                var  insert_content_form  = $(".insert_collection");

                create_content_form.parents('.clearfix').hide();
                $("#piappgedmobundlemanagerformbuilderpimodelwidgetcontent_id_content").attr("required", "required");
                $("#piappgedmobundlemanagerformbuilderpimodelwidgetcontent_descriptif").removeAttr("required");

                $("input[id='piappgedmobundlemanagerformbuilderpimodelwidgetcontent_choice_0']").change(function () {
                    if ($(this).is(':checked')){
                        create_content_form.parents('.clearfix').hide();
                        insert_content_form.parents('.clearfix').show();

                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetcontent_id_content").attr("required", "required");
                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetcontent_descriptif").removeAttr("required");
                    } else {
                        create_content_form.parents('.clearfix').show();
                        insert_content_form.parents('.clearfix').hide();

                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetcontent_id_content").removeAttr("required");
                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetcontent_descriptif").attr("required", "required");
                    }
                   });
                $("input[id='piappgedmobundlemanagerformbuilderpimodelwidgetcontent_choice_1']").change(function () {
                    if ($(this).is(':checked')){
                        create_content_form.parents('.clearfix').show();
                        insert_content_form.parents('.clearfix').hide();

                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetcontent_id_content").removeAttr("required");
                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetcontent_descriptif").attr("required", "required");
                    } else {
                        create_content_form.parents('.clearfix').hide();
                        insert_content_form.parents('.clearfix').show();

                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetcontent_id_content").attr("required", "required");
                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetcontent_descriptif").removeAttr("required");
                    }
                   });
                                      
            });
        