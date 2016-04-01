                jQuery(document).ready(function() {
                    var indexSQLParams    = 0;
                    jQuery("div#piappgedmobundlemanagerformbuilderpimodelwidgetorganigram").find("fieldset").append('<br ><ul id="sqlparams-fields-list-organigram" ></ul>');
                    jQuery('#add-another-sqlparameters-organigram').click(function() {
                        var prototypeList = jQuery('#prototype_script_organigramsearchfields');   
                        // parcourt le template prototype
                        var newWidget2 = prototypeList.html().replace('<label class="required">__name__label__</label>', '');
                        // remplace les "__name__" utilisés dans l'id et le nom du prototype
                        // par un nombre unique pour chaque email
                        // le nom de l'attribut final ressemblera à name="contact[emails][2]"
                        newWidget2 = newWidget2.replace(/__name__/g, indexSQLParams);
                        indexSQLParams++;            
                        // créer une nouvelle liste d'éléments et l'ajoute à notre liste
                        var newLi2 = jQuery('<li class="addcollection"></li>').html(newWidget2);
                        newLi2.appendTo(jQuery('#sqlparams-fields-list-organigram'));
                        // we align the fields
                        return false;
                    });
                })            
        