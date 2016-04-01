                    function fnFilterGlobal ()
                    {
                        $('#grid').dataTable().fnFilter( 
                            $("#global_filter").val(),
                            null, 
                            $("#global_regex")[0].checked, 
                            $("#global_smart")[0].checked
                        );
                    }
                     
                    function fnFilterColumn ( i )
                    {
                        $('#grid').dataTable().fnFilter( 
                            $("#col"+(i+1)+"_filter").val(),
                            i, 
                            $("#col"+(i+1)+"_regex")[0].checked, 
                            $("#col"+(i+1)+"_smart")[0].checked
                        );
                    }

                    function fnCreateFooterFilter() 
                    {
                                                 
                        /* Add a select menu for each TH element in the table footer
                         *
                         *   <tfoot>
                         *      <tr>
                         *        <th data-type="input"><input type="text" name="" value="Position" style="width:100%" /></th>
                         *        <th data-type="input"><input type="text" name="" value="Id" style="width:100%" /></th>
                         *        <th data-column='2' data-title="{{ 'pi.form.label.field.topic'|trans }}"  data-ajaxsearch="false" data-values='{{ rubriques|json_encode }}'></th>
                         *        <th data-column='3' data-title="{{ 'pi.form.label.field.tag'|trans }}"></th>
                         *        <th data-column='4' data-type="input" ><input type="text" name="" value="" style="width:100%" /></th>
                         *        <th data-column='5' data-title="{{ 'pi.form.label.field.type'|trans }}" data-values='{"article":"Articles","diaporama":"Dossiers","test":"Tests","page":"Pages"}'></th>
                         *        <th data-column='6' data-title="{{ 'pi.form.label.field.author'|trans }}"></th>
                         *        <th data-column='7' data-title="{{ 'pi.page.form.status'|trans }}" data-values='{"Actif":"Actif","Archive":"Archivé","En attente dactivation":"En attente d activation"}'></th>
                         *        <th data-type="input"><input type="text" name="" value="createdat" style="width:100%" /></th>
                         *        <th data-type="input"><input type="text" name="" value="publishedat" style="width:100%" /></th> 
                         *        <th data-type="input"><input type="text" name="" value="updatedat" style="width:100%" /></th>
                         *        <th></th>
                         *      </tr>
                         *  </tfoot>
                         */
                        $("table th").each( function ( i ) {
                                var column = $(this).data('column');
                                var values = $(this).data('values');
                                var type = $(this).data('type');
                                var title = $(this).data('title');
                                var ajaxsearch = $(this).data('ajaxsearch');
                                if (column != undefined) {
                                	                                    if (values == undefined) {
                                        values = gridoTable.fnGetColumnData(column) 
                                    }
                                    if (type != "input") {
                                        var options = [];
                                        $('select', this).find(':selected').each(function(j,v){
                                            options[j] = $(v).val();
                                        });                                        
                                        $(this).html( fnCreateSelect( values, title, i) ); 
                                        $('select', this).data('title', title).data('column', column).val(options);
                                        
                                        $('select', this).change( function () {
                                            var values = $("#select_"+i).val().join('|');
                                            gridoTable.fnFilter( values, column, true );
                                        });
                                        $("#select_"+i).multiselect({
                                            multiple: true,
                                            header: true,
                                            noneSelectedText: title,
                                            create: function(){ $(this).next().width('auto');$(this).multiselect("widget").width('auto'); },
                                            open: function(){ $(this).next().width('auto');$(this).multiselect("widget").width('auto'); },
                                        }).multiselectfilter({
                                            filter: function(e, matches) {
                                                e.preventDefault();
                                                var keyword = $('.ui-multiselect-filter:visible').find('input').val();
                                                if ( (ajaxsearch === true) || (ajaxsearch === 'true') ) {
                                                	if(keyword != $(e.target).data('keyword')) {
                                                    	gridoTable.fnFilter( keyword, column, true );
                                                	}
                                            	}
                                            }
                                        });                                        
                                    } else {
                                        var search_timeout = undefined;
                                        $(this).find('input').width('91%').attr('id', 'input_'+i).data('title', title).data('column', column).keyup( function () {
                                            if(search_timeout != undefined) {
                                                clearTimeout(search_timeout);
                                            }
                                            $this = this;
                                            search_timeout = setTimeout(function() {
                                              search_timeout = undefined;
                                              gridoTable.fnFilter( $this.value, column, true );
                                            }, 1000);
                                        } );
                                    }
                                } else if (type != undefined) {
                                    this.innerHTML = '' ;
                                }
                        });
/*
                        $("[id^='select_']").change( function () {
                            var values = $(this).val().join('|');
                            gridoTable.fnFilter( values, $(this).data('column'), true );
                        });
                        $("[id^='select_']").multiselect({
                            multiple: true,
                            header: true,
                            noneSelectedText: $(this).data('title'),
                            create: function(){ $(this).next().width('auto');$(this).multiselect("widget").width('auto'); },
                            open: function(){ $(this).next().width('auto');$(this).multiselect("widget").width('auto'); },
                        }).multiselectfilter({
                            filter: function(e, matches) {
                                e.preventDefault();
                                var keyword = $('.ui-multiselect-filter:visible').find('input').val();
                                if(keyword != $(e.target).data('keyword')) {
                                    gridoTable.fnFilter( keyword, $(this).data('column'), true );
                                }
                            }
                        });
                        var foo_input = function() {
                            gridoTable.fnFilter( $(this).val(), $(this).data('column'), true );
                        };
                        $("[id^='input_']").off("keyup", foo_input);   
                        $("[id^='input_']").on("keyup", foo_input);                         
*/
                        $("[id^='ui-multiselect-']").each(function(i){
                            var string = $(this).next('span').html();
                            string = string.toString().replace(/&lt;/g, '<').replace(/&gt;/g, '>');
                            string = string.replace(/&#0*39;/g, "'");
                            string = string.replace(/&quot;/g, '"');
                            string = string.replace(/&amp;/g, '&');
                            $(this).next('span').html(string);
                            $(this).click(function() {
                                    var id = $(this).attr('id').toString().replace(/-option-(.+)/ig,'').replace('ui-multiselect-','');
                                    var string = $(this).val();
                                    string = string.toString().replace(/&amp;lt;img.*?\/&amp;gt;/ig,'');
                                    $("#"+id).next("button.ui-multiselect").html(string);
                            });
                        });
                    }

					function fnCreateSelect( aData, title, myColumnID)
					{
						var mySelectID = 'select_' + myColumnID;
    					var options = $("<select id='"+mySelectID+"' name='"+mySelectID+"' class='filtSelect' style='width:auto' multiple='multiple'  />"),
    				    addOptions = function(opts, container){
    						container.append($("<option />").val('').text('All'));
    				        $.each(opts, function(i, opt) {
    				            if(typeof(opt)=='string'){
    				            	if(typeof(i)=='string'){
    				                container.append($("<option />").val(i).text(opt));
    				            	}else{
    				            		container.append($("<option />").val(opt).text(opt));
    				            	}
    				            } else {
    				                var optgr = $("<optgroup />").attr('label',i);
    				                addOptions(opt, optgr)
    				                container.append(optgr);
    				            }
    				        });
    				    };

    				    options.css('width', '100%')
    					addOptions(aData,options);
    					return options;
					}  

                    $.extend( $.fn.dataTableExt.oSort, {
    				    "num-html-pre": function ( a ) {
    				        var x = a.replace( /<.*?>/g, "" );
    				        x = x.replace( "%", "" );
    				        if(x == " ") { x=-1; }
    				        return parseFloat( x );
    				    },    				 
    				    "num-html-asc": function ( a, b ) {
    				        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    				    },
    				 
    				    "num-html-desc": function ( a, b ) {
    				        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    				    }
    				} );

    				$.fn.dataTableExt.oSort['numeric-comma-asc']  = function(a,b) {
    					var x = (a == "-") ? 0 : a.replace( /,/, "." );
    					var y = (b == "-") ? 0 : b.replace( /,/, "." );
    					x = parseFloat( x );
    					y = parseFloat( y );
    					return ((x < y) ? -1 : ((x > y) ?  1 : 0));
    				};
    				
    				$.fn.dataTableExt.oSort['numeric-comma-desc'] = function(a,b) {
    					var x = (a == "-") ? 0 : a.replace( /,/, "." );
    					var y = (b == "-") ? 0 : b.replace( /,/, "." );
    					x = parseFloat( x );
    					y = parseFloat( y );
    					return ((x < y) ?  1 : ((x > y) ? -1 : 0));
    				}; 

    				(function($) {                        
    					/*
    					 * Function: fnGetColumnData
    					 * Purpose:  Return an array of table values from a particular column.
    					 * Returns:  array string: 1d data array
    					 * Inputs:   object:oSettings - dataTable settings object. This is always the last argument past to the function
    					 *           int:iColumn - the id of the column to extract the data from
    					 *           bool:bUnique - optional - if set to false duplicated values are not filtered out
    					 *           bool:bFiltered - optional - if set to false all the table data is used (not only the filtered)
    					 *           bool:bIgnoreEmpty - optional - if set to false empty values are not filtered from the result array
    					 * Author:   Benedikt Forchhammer <b.forchhammer /AT\ mind2.de>
    					 */
    					$.fn.dataTableExt.oApi.fnGetColumnData = function ( oSettings, iColumn, bUnique, bFiltered, bIgnoreEmpty ) {
    					    // check that we have a column id
    					    if ( typeof iColumn == "undefined" ) return new Array();
    					     
    					    // by default we only want unique data
    					    if ( typeof bUnique == "undefined" ) bUnique = true;
    					     
    					    // by default we do want to only look at filtered data
    					    if ( typeof bFiltered == "undefined" ) bFiltered = true;
    					     
    					    // by default we do not want to include empty values
    					    if ( typeof bIgnoreEmpty == "undefined" ) bIgnoreEmpty = true;
    					     
    					    // list of rows which we're going to loop through
    					    var aiRows;
    					     
    					    // use only filtered rows
    					    if (bFiltered == true) aiRows = oSettings.aiDisplay;
    					    // use all rows
    					    else aiRows = oSettings.aiDisplayMaster; // all row numbers
    					 
    					    // set up data array   
    					    var asResultData = new Array();
    					     
    					    for (var i=0,c=aiRows.length; i<c; i++) {
    					        iRow = aiRows[i];
    					        var aData = this.fnGetData(iRow);
    					        var sValue = aData[iColumn];
                                                
    					        // Error lorsque sValue = null
								if(sValue == null) continue;
                                                
                                // ignore empty values?
    					        else if (bIgnoreEmpty == true && sValue.length == 0) continue;
    					 
    					        // ignore unique values?
    					        else if (bUnique == true && jQuery.inArray(sValue, asResultData) > -1) continue;
    					         
    					        // else push the value onto the result data array
    					        else asResultData.push(sValue);
    					    }
    					     
    					    return asResultData;
    				}}(jQuery));
    					 

    				 

                    var enabled;
                    var disablerow;
                    var deleterow;
                    var archiverow;

                    var gridoTable;
                    var envelopeConf = $.fn.dataTable.Editor.display.envelope.conf;
                    envelopeConf.attach = 'head';
                    envelopeConf.windowScroll = false;       

                    $('*[class^="button-ui"]').each( function ( i ) {
                        var name_button = $(this).data('ui-icon');
                        var class_button = $(this).attr('class');                                 
                        $("a.button-"+name_button).button({icons: {primary: name_button}});
                            				});                    
                    
                    $("td.enabled").each(function(index) {
                        var value = $(this).html();
                        if (value == 1)
                            $(this).html('<img width="17px" src="/bundles/sfynxsmoothness/admin/grid/img/enabled.png?v1_0_0">');
                        if (value == 0)
                            $(this).html('<img width="17px" src="/bundles/sfynxsmoothness/admin/grid/img/disabled.png?v1_0_0">');                        
                    });                                        

                    $('#grid tbody tr').each(function(index) {
                        $(this).find("td.position").prependTo(this);
                    });
                    $('#grid thead tr').each(function(index) {
                        $(this).find("th.position").prependTo(this);
                    });

                    /* Add the events etc before DataTables hides a column  Filter on the column (the index) of this element
                    $("tfooter input").keyup( function () {
                        gridoTable.fnFilter( this.value, gridoTable.oApi._fnVisibleToColumnIndex( 
                                gridoTable.fnSettings(), $("thead input").index(this) ) );
                    } );
					*/
                    
                                                                                                    // Set up enabled row
                            enabled = new $.fn.dataTable.Editor( {
                                "domTable": "#grid",
                                //"display": "envelope",
                                "ajaxUrl": "/admin/langue/enabled?_token=7a920b54c02769dbb6c1eea04aac4027d16c9105",
                                "events": {
                                    "onPreSubmit": function (data) {
                                    },
                                    "onPostSubmit": function (json, data) {
                                    },                                    
                                    "onPreRemove": function (json) {
                                    },
                                    "onPostRemove": function (json) {
                                    }
                                }                    
                            } );
                                                                                                            // Set up disable row
                            disablerow = new $.fn.dataTable.Editor( {
                                "domTable": "#grid",
                                //"display": "envelope",
                                "ajaxUrl": "/admin/langue/disable?_token=7a920b54c02769dbb6c1eea04aac4027d16c9105",
                                "events": {
                                    "onPreSubmit": function (data) {
                                    },
                                    "onPostSubmit": function (json, data) {
                                    },                                    
                                    "onPreRemove": function (json) {
                                    },
                                    "onPostRemove": function (json) {
                                    }
                                }    
                            } );
                                                                                                                                                                                    

                    gridoTable = $('#grid').dataTable({
                        "bPaginate":true,
                        "bRetrieve":true,
                        "bFilter": true,
                                                "sPaginationType": "full_numbers",
                                                "bJQueryUI":true,
                        "bAutoWidth": false,
                        "bProcessing": true,
                        "bStateSave": false,
                        "fnInitComplete": function(oSettings, json) {
                          //
                        },                    
                                                 

                                                "aaSorting": 
                            [
                                                                    [1,'desc'],                
                                                                    
                            ],
                        
                                                
                            
                        "aLengthMenu": [[1, 5, 10, 15, 20, 25, 50, 100, 500, 1000, 5000 -1], [1, 5, 10, 15, 20, 25, 50, 100, 500, 1000, 5000, "All"]],
                                                "iDisplayLength": 25,
                                
                        
                        "oLanguage": {
                            "sLoadingRecords": "<div id='spin'></div>Please wait while loading the table",
                            "sProcessing": "<div id='spin' style='display:block;width:24px;height:24px;float:left;margin: 6px 2px;'></div>Please wait while loading the table",
                            "sLengthMenu": "Display _MENU_ records per page",
                            "sZeroRecords": "Nothing found - sorry",
                            "sInfo": "Showing _START_ to _END_ of _TOTAL_ records",
                            "sInfoEmpty": "Showing 0 to 0 of 0 records",
                            "sInfoFiltered": "(filtered from _MAX_ total records)",
                            "sInfoPostFix": "",
                            "sSearch": "Search",
                            "sUrl": "",
                            "oPaginate": {
                                "sFirst":    "First",
                                "sPrevious": "Previous",
                                "sNext":     "Next",
                                "sLast":     "Last"
                            }
                        },
                        // l - Length changing
                        // f - Filtering input
                        // t - The table!
                        // i - Information
                        // p - Pagination
                        // r - pRocessing
                        // < and > - div elements
                        // <"class" and > - div with a class
                        // Examples: <"wrapper"flipt>, <lf<t>ip>
                        //avec multi-filtre : "sDom": '<"block_filter"><"H"RTfr<"clear">>tC<"F"lpi>',
                                                                                "sDom": '<"H"RTfr<"clear"><"clear">p<"clear">>tC<"F"lpi>',
                                                    
                        "oTableTools": {
                            "sSwfPath": "/bundles/sfynxtemplate/js/datatable/extras/TableTools/media/swf/copy_csv_xls_pdf.swf?v1_0_0",
                            "sRowSelect": "multi",       //  ['single', 'multi']
                            "aButtons": [
                                                                                                                                                                                {
                                                "sExtends": "editor_remove",
                                                "sButtonText": "<img class='btn-action' src='/bundles/sfynxsmoothness/admin/grid/img/penabled.png?v1_0_0' title='Enabled' alt='Enabled'  />Enabled",
                                                "editor": enabled,
                                                "formButtons": [
                                                    {
                                                        "label": "Valider",
                                                        "className": "save",
                                                        "fn": function (e) {
                                                            this.submit(function(){
                                                                                                                            });
                                                            $("tr.DTTT_selected td.enabled").html('<img width="17px" src="/bundles/sfynxsmoothness/admin/grid/img/enabled.png?v1_0_0">');
                                                        }
                                                    }
                                                ],
                                                                                                "formTitle": "Activer données",
                                                                                                  "question": function(b) {
                                                                                                      return "Voulez-vous activer " + b + " ligne" + (b === 1 ? " ?" : "s ?")
                                                                                                        
                                                },
                                            },
                                                                            
                                                                                                                                                        {
                                                "sExtends": "editor_remove",
                                                "sButtonText": "<img class='btn-action' src='/bundles/sfynxsmoothness/admin/grid/img/pdisable.png?v1_0_0' title='Disable' alt='Disable'  />Disable",
                                                "editor": disablerow,
                                                "formButtons": [
                                                    {
                                                        "label": "Valider",
                                                        "className": "save",
                                                        "fn": function (e) {
                                                            this.submit(function(){
                                                                                                                            });
                                                            $("tr.DTTT_selected td.enabled").html('<img width="17px" src="/bundles/sfynxsmoothness/admin/grid/img/disabled.png?v1_0_0">');
                                                        }
                                                    }
                                                ],
                                                                                                "formTitle": "Désactiver données",
                                                                                                  "question": function(b) {
                                                                                                      return "Voulez-vous désactiver " + b + " ligne" + (b === 1 ? " ?" : "s ?")
                                                                                                        
                                                }
                                            },                                            
                                                                            
                                                                                                    		                                            {
                                                "sExtends": "select_all",
                                                "sButtonText": "<img class='btn-action' src='/bundles/sfynxsmoothness/admin/grid/img/select_all.png?v1_0_0' title='Select' alt='Select'  />Select",
                                                "fnComplete": function ( nButton, oConfig, oFlash, sFlash ) {
                                                    $("input[type=checkbox]").prop('checked', false);
                                                },
                                            },    
                                                                            
                                                                                                    		                                            {
                                                "sExtends": "select_none",
                                                "sButtonText": "<img class='btn-action' src='/bundles/sfynxsmoothness/admin/grid/img/select_none.png?v1_0_0' title='Deselect' alt='Deselect'  />Deselect",
                                                "fnComplete": function ( nButton, oConfig, oFlash, sFlash ) {
                                                    $("input[type=checkbox]").prop('checked', false);
                                                },
                                            },                                                                                                                                
                                                                            
                                                        
                                        ]                            
                        },
                        "oColVis": {
                            "buttonText": "&nbsp;",
                            "bRestore": true,
                            "sAlign": "right"
                        },
                        "aoColumnDefs": [
                                            { "bVisible": false, "aTargets": [ 0 ] },                                            
                                                                                                                                                                                                                                                                                                        
                                        
                    
                        ],
                        "oColumnFilterWidgets": {
                            "sSeparator": "\\s*/+\\s*",
                            "aiExclude": [ 
                                            0,1            
                                        
                            ]
                        },

                    });

                                                        
                        
                                                        
                            
                        
                                        
                        
                                                        
                            
                        
                                        
                        
                                                        
                            
                        
                                        
                        
                                                        
                            
                        
                                                            


                    var content = $("#blocksearch_content").html();
                    $("#blocksearch_content").html('');
                    $("#grid").before(content);

                    $("#global_filter").keyup( fnFilterGlobal );
                    $("#global_regex").click( fnFilterGlobal );
                    $("#global_smart").click( fnFilterGlobal );

                                            
                    $("#col1_filter").keyup( function() { fnFilterColumn( 0 ); } );
                    $("#col1_regex").click(  function() { fnFilterColumn( 0 ); } );
                    $("#col1_smart").click(  function() { fnFilterColumn( 0 ); } );
                                                
                                                

                    $('.block_filter').click(function() {
                        $("#blocksearch").slideToggle("slow");
                    });

                     

                    // http://fgnass.github.io/spin.js/
                    var opts_spinner = {
                            lines: 11, // The number of lines to draw
                            length: 2, // The length of each line
                            width: 3, // The line thickness
                            radius: 6, // The radius of the inner circle
                            corners: 1, // Corner roundness (0..1)
                            rotate: 0, // The rotation offset
                            direction: 1, // 1: clockwise, -1: counterclockwise
                            color: '#000', // #rgb or #rrggbb
                            speed: 1.3, // Rounds per second
                            trail: 54, // Afterglow percentage
                            shadow: false, // Whether to render a shadow
                            hwaccel: true, // Whether to use hardware acceleration
                            className: 'spinner', // The CSS class to assign to the spinner
                            zIndex: 1049, // The z-index (defaults to 2000000000)
                            top: 0, // Top position relative to parent in px
                            left: 0 // Left position relative to parent in px
                          };
                   var target_spinner = document.getElementById('spin');
                   var spinner = new Spinner(opts_spinner).spin(target_spinner);

                   $(function() {
                        $("a.info-tooltip").tooltip({
                              position: {
                                  track: true,
                                  my: "center bottom-20",
                                  at: "center top",
                                },
                              content: function () {
                                    return $(this).prop('title');
                                }                            
                        });
                        fnCreateFooterFilter();   
                                             
                        fnCreateFooterFilter(); 
                         
                   });            
        