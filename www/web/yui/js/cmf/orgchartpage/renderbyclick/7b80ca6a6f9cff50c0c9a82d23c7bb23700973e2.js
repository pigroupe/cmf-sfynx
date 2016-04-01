                jQuery(document).ready(function() {
                    jQuery.fx.interval = 0.1;
                    $(".org-chart-page").click(function(e){
                            if ($('#pi_block-boxes').is(':hidden')){
                                $("div[data-block^='pi_menuleft']").toggle(300);
                                setTimeout(function(){
                                    $('.jOrgChart').addClass("animated");
                                },300);
                            } else {
                                setTimeout(function(){
                                    $('.jOrgChart').removeClass("animated");
                                },300);
                                setTimeout(function(){
                                    $("div[data-block^='pi_menuleft']").toggle(500);
                                },2500);
                            }
                    });

                    $("[data-drag^='dragmap_']").draggable();

                    $("#org").jOrgChart({
                        chartElement : '#pi_treeChart',
                        chartClass : 'jOrgChart',
                        dragAndDrop  : true
                    });

                });
        