            jQuery(document).ready(function(){        
                var  create_content_form  = $(".block_collection");
                var  insert_content_form  = $(".block_insert_collection");

                create_content_form.parents('.clearfix').hide();
                
                $("#piappgedmobundlemanagerformbuilderpimodelwidgetblock_id_block").attr("required", "required");
                $("#piappgedmobundlemanagerformbuilderpimodelwidgetblock_title").removeAttr("required");
                $("#piappgedmobundlemanagerformbuilderpimodelwidgetblock_descriptif").removeAttr("required");

                $("input[id='piappgedmobundlemanagerformbuilderpimodelwidgetblock_choice_0']").change(function () {
                    if ($(this).is(':checked')){
                        create_content_form.parents('.clearfix').hide();
                        insert_content_form.parents('.clearfix').show();

                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetblock_id_block").attr("required", "required");
                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetblock_title").removeAttr("required");
                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetblock_descriptif").removeAttr("required");
                    } else {
                        create_content_form.parents('.clearfix').show();
                        insert_content_form.parents('.clearfix').hide();

                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetblock_id_block").removeAttr("required");
                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetblock_title").attr("required", "required");
                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetblock_descriptif").attr("required", "required");
                    }
                   });
                $("input[id='piappgedmobundlemanagerformbuilderpimodelwidgetblock_choice_1']").change(function () {
                    if ($(this).is(':checked')){
                        create_content_form.parents('.clearfix').show();
                        insert_content_form.parents('.clearfix').hide();

                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetblock_id_block").removeAttr("required");
                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetblock_title").attr("required", "required");
                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetblock_descriptif").attr("required", "required");
                    } else {
                        create_content_form.parents('.clearfix').hide();
                        insert_content_form.parents('.clearfix').show();

                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetblock_id_block").attr("required", "required");
                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetblock_title").removeAttr("required");
                        $("#piappgedmobundlemanagerformbuilderpimodelwidgetblock_descriptif").removeAttr("required");
                    }
                   });
                                      
            });
        