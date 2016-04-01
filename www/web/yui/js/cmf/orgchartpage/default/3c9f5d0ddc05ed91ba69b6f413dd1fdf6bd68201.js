            jQuery(document).ready(function() {
                $("[data-drag^='dragmap_']").draggable();
                $("#orga").jOrgChart({
                    chartElement : '#chart_orga',
                    chartClass : 'jOrgChart',
                    dragAndDrop  : true
                });
            });
        