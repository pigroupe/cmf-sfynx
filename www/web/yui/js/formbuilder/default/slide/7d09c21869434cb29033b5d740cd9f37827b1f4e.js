                jQuery(document).ready(function() {
                	var indexFlexsliderParams    = 0;
                	jQuery("div#piappgedmobundlemanagerformbuilderpimodelwidgetslide").find("fieldset").append('<ul id="flexsliderparams-fields-list" ></ul>');
                    jQuery('#add-another-slideparameters').click(function() {
                        var prototypeList = jQuery('#prototype_script_flexsliderparams');   
                        // parcourt le template prototype
                        var newWidget = prototypeList.html().replace('<label class="required">__name__label__</label>', '');
                        // remplace les "__name__" utilisés dans l'id et le nom du prototype
                        // par un nombre unique pour chaque email
                        // le nom de l'attribut final ressemblera à name="contact[emails][2]"
                        newWidget = newWidget.replace(/__name__/g, indexFlexsliderParams);
                        indexFlexsliderParams++;            
                        // créer une nouvelle liste d'éléments et l'ajoute à notre liste
                        var newLi = jQuery('<li class="addcollection"></li>').html(newWidget);
                        newLi.appendTo(jQuery('#flexsliderparams-fields-list'));
                        // we align the fields
                        return false;
                    });

                    var indexSQLParams    = 0;
                    jQuery("div#piappgedmobundlemanagerformbuilderpimodelwidgetslide").find("fieldset").append('<br ><ul id="sqlparams-fields-list" ></ul>');
                    jQuery('#add-another-sqlparameters').click(function() {
                        var prototypeList = jQuery('#prototype_script_flexslidersearchfields');   
                        // parcourt le template prototype
                        var newWidget2 = prototypeList.html().replace('<label class="required">__name__label__</label>', '');
                        // remplace les "__name__" utilisés dans l'id et le nom du prototype
                        // par un nombre unique pour chaque email
                        // le nom de l'attribut final ressemblera à name="contact[emails][2]"
                        newWidget2 = newWidget2.replace(/__name__/g, indexSQLParams);
                        indexSQLParams++;            
                        // créer une nouvelle liste d'éléments et l'ajoute à notre liste
                        var newLi2 = jQuery('<li class="addcollection"></li>').html(newWidget2);
                        newLi2.appendTo(jQuery('#sqlparams-fields-list'));
                        // we align the fields
                        return false;
                    });
                })            
        