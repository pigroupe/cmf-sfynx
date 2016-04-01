                    function fnFilterGlobal ()
                    {
                        $('#grid_media').dataTable().fnFilter( 
                            $("#global_filter").val(),
                            null, 
                            $("#global_regex")[0].checked, 
                            $("#global_smart")[0].checked
                        );
                    }
                     
                    function fnFilterColumn ( i )
                    {
                        $('#grid_media').dataTable().fnFilter( 
                            $("#col"+(i+1)+"_filter").val(),
                            i, 
                            $("#col"+(i+1)+"_regex")[0].checked, 
                            $("#col"+(i+1)+"_smart")[0].checked
                        );
                    }

                    function fnCreateFooterFilter() 
                    {
                                                  $('tfoot tr').addClass("tfoot-up");
                         $('tfoot').replaceWith(function(){
                                return $("<thead />", {html: $(this).html()});
                         }); 
                                                 
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
                                	                    				                            				    var mincDateFilter;
                        				    var maxcDateFilter;
                            				$("table th").each( function ( i ) {
                                                var column = $(this).data('search');
                                                if (column == 6) {
                                                    $(this).html('<div id="filter-grid-date-0" ><input class="form-control form-control-inline input-medium default-date-picker" type="text" id="minc" name="minc"><input type="text" class="form-control form-control-inline input-medium default-date-picker" id="maxc" name="maxc"></div>');
                                                }
                            				}); 
                            				$("#maxc").datepicker({
                                                changeMonth: true,
                                                changeYear: true,
                                                yearRange: "-71:+11",
                                                reverseYearRange: true,
                                                showOtherMonths: true,
                                                showButtonPanel: true,
                                                showAnim: "fade",  // blind fade explode puff fold
                                                showWeek: true,
                                                format: "yy-mm-dd",
                                                dateFormat: "yy-mm-dd",
                                                showOptions: { 
                                                    direction: "up" 
                                                },
                                                numberOfMonths: [ 1, 2 ],
                                                buttonText: "Choisissez une date",
                                                showOn: "focus",
                                                buttonImage: "/bundles/sfynxtemplate/images/icons/form/picto-calendar.png",
                                                onSelect: function(date) {
                                                	maxcDateFilter = new Date(date).getTime();
                                                    grid_mediaoTable.fnDraw();
                                                    $(this).datepicker('hide');
                                                }
                                            }).keyup( function () {
                                            	maxcDateFilter = new Date(this.value).getTime();
                                                grid_mediaoTable.fnDraw();
                                                $(this).datepicker('hide');
                                            }).on('changeDate', function(ev){
                                            	maxcDateFilter = new Date(ev.date).getTime();
                                                grid_mediaoTable.fnDraw();
                                                $(this).datepicker('hide');
                                            });
                            				$("#minc").datepicker({
                                                changeMonth: true,
                                                changeYear: true,
                                                yearRange: "-71:+11",
                                                reverseYearRange: true,
                                                showOtherMonths: true,
                                                showButtonPanel: true,
                                                showAnim: "fade",  // blind fade explode puff fold
                                                showWeek: true,
                                                format: "yy-mm-dd",
                                                dateFormat: "yy-mm-dd",
                                                showOptions: { 
                                                    direction: "up" 
                                                },
                                                numberOfMonths: [ 1, 2 ],
                                                buttonText: "Choisissez une date",
                                                showOn: "focus",
                                                buttonImage: "/bundles/sfynxtemplate/images/icons/form/picto-calendar.png",
                                                onSelect: function(date) {
                                                	mincDateFilter = new Date(date).getTime();
                                                    grid_mediaoTable.fnDraw();
                                                    $(this).datepicker('hide');
                                                  }
                                            }).keyup( function () {
                                            	mincDateFilter = new Date(this.value).getTime();
                                                grid_mediaoTable.fnDraw();
                                                $(this).datepicker('hide');
                                            }).on('changeDate', function(ev){
                                                <!-- http://bootstrap-datepicker.readthedocs.org/en/release/methods.html#setdate -->                                                
                                            	maxcDateFilter = new Date(ev.date).getTime();
                                                grid_mediaoTable.fnDraw();
                                                $(this).datepicker('hide');
                                            });
                            				$.datepicker.setDefaults( $.datepicker.regional[ "en" ] );
                            			                        				    var minuDateFilter;
                        				    var maxuDateFilter;
                            				$("table th").each( function ( i ) {
                                                var column = $(this).data('search');
                                                if (column == 7) {
                                                    $(this).html('<div id="filter-grid-date-1" ><input class="form-control form-control-inline input-medium default-date-picker" type="text" id="minu" name="minu"><input type="text" class="form-control form-control-inline input-medium default-date-picker" id="maxu" name="maxu"></div>');
                                                }
                            				}); 
                            				$("#maxu").datepicker({
                                                changeMonth: true,
                                                changeYear: true,
                                                yearRange: "-71:+11",
                                                reverseYearRange: true,
                                                showOtherMonths: true,
                                                showButtonPanel: true,
                                                showAnim: "fade",  // blind fade explode puff fold
                                                showWeek: true,
                                                format: "yy-mm-dd",
                                                dateFormat: "yy-mm-dd",
                                                showOptions: { 
                                                    direction: "up" 
                                                },
                                                numberOfMonths: [ 1, 2 ],
                                                buttonText: "Choisissez une date",
                                                showOn: "focus",
                                                buttonImage: "/bundles/sfynxtemplate/images/icons/form/picto-calendar.png",
                                                onSelect: function(date) {
                                                	maxuDateFilter = new Date(date).getTime();
                                                    grid_mediaoTable.fnDraw();
                                                    $(this).datepicker('hide');
                                                }
                                            }).keyup( function () {
                                            	maxuDateFilter = new Date(this.value).getTime();
                                                grid_mediaoTable.fnDraw();
                                                $(this).datepicker('hide');
                                            }).on('changeDate', function(ev){
                                            	maxuDateFilter = new Date(ev.date).getTime();
                                                grid_mediaoTable.fnDraw();
                                                $(this).datepicker('hide');
                                            });
                            				$("#minu").datepicker({
                                                changeMonth: true,
                                                changeYear: true,
                                                yearRange: "-71:+11",
                                                reverseYearRange: true,
                                                showOtherMonths: true,
                                                showButtonPanel: true,
                                                showAnim: "fade",  // blind fade explode puff fold
                                                showWeek: true,
                                                format: "yy-mm-dd",
                                                dateFormat: "yy-mm-dd",
                                                showOptions: { 
                                                    direction: "up" 
                                                },
                                                numberOfMonths: [ 1, 2 ],
                                                buttonText: "Choisissez une date",
                                                showOn: "focus",
                                                buttonImage: "/bundles/sfynxtemplate/images/icons/form/picto-calendar.png",
                                                onSelect: function(date) {
                                                	minuDateFilter = new Date(date).getTime();
                                                    grid_mediaoTable.fnDraw();
                                                    $(this).datepicker('hide');
                                                  }
                                            }).keyup( function () {
                                            	minuDateFilter = new Date(this.value).getTime();
                                                grid_mediaoTable.fnDraw();
                                                $(this).datepicker('hide');
                                            }).on('changeDate', function(ev){
                                                <!-- http://bootstrap-datepicker.readthedocs.org/en/release/methods.html#setdate -->                                                
                                            	maxuDateFilter = new Date(ev.date).getTime();
                                                grid_mediaoTable.fnDraw();
                                                $(this).datepicker('hide');
                                            });
                            				$.datepicker.setDefaults( $.datepicker.regional[ "en" ] );
                            			                            		                                    if (values == undefined) {
                                        values = grid_mediaoTable.fnGetColumnData(column) 
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
                                            grid_mediaoTable.fnFilter( values, column, true );
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
                                                    	grid_mediaoTable.fnFilter( keyword, column, true );
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
                                              grid_mediaoTable.fnFilter( $this.value, column, true );
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
                            grid_mediaoTable.fnFilter( values, $(this).data('column'), true );
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
                                    grid_mediaoTable.fnFilter( keyword, $(this).data('column'), true );
                                }
                            }
                        });
                        var foo_input = function() {
                            grid_mediaoTable.fnFilter( $(this).val(), $(this).data('column'), true );
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
    						container.append($("<option />").val('').text('Tous'));
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
    					 

    				    				            				    // http://live.datatables.net/etewoq/4/edit#javascript,html,live
        				    var mincDateFilter;
        				    var maxcDateFilter;
            				            				  
        				        				    // http://live.datatables.net/etewoq/4/edit#javascript,html,live
        				    var minuDateFilter;
        				    var maxuDateFilter;
            				            				  
        				 
    				 

                    var enabled;
                    var disablerow;
                    var deleterow;
                    var archiverow;

                    var grid_mediaoTable;
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

                    $('#grid_media tbody tr').each(function(index) {
                        $(this).find("td.position").prependTo(this);
                    });
                    $('#grid_media thead tr').each(function(index) {
                        $(this).find("th.position").prependTo(this);
                    });

                    /* Add the events etc before DataTables hides a column  Filter on the column (the index) of this element
                    $("tfooter input").keyup( function () {
                        grid_mediaoTable.fnFilter( this.value, grid_mediaoTable.oApi._fnVisibleToColumnIndex( 
                                grid_mediaoTable.fnSettings(), $("thead input").index(this) ) );
                    } );
					*/
                    
                                                                                                                                                        // Set up enabled row
                            enabled = new $.fn.dataTable.Editor( {
                                "domTable": "#grid_media",
                                //"display": "envelope",
                                "ajaxUrl": "/content/gedmo/media/enabled?_token=2a3a6fbed92aace05dcf5f4d31bbf7ec7a76d709",
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
                                "domTable": "#grid_media",
                                //"display": "envelope",
                                "ajaxUrl": "/content/gedmo/media/disable?_token=2a3a6fbed92aace05dcf5f4d31bbf7ec7a76d709",
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
                                                                                                                                                                                    

                    grid_mediaoTable = $('#grid_media').dataTable({
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
                                                "bServerSide": true,
                        "sAjaxSource": "/admin/gedmo/media/",
                        'fnServerData' : function ( sSource, aoData, fnCallback ) {
                        	                            	    						    aoData.push( { 'name' : 'date-minc', 'value' : $("#minc").val() } );
    						    aoData.push( { 'name' : 'date-maxc', 'value' : $("#maxc").val() } );
    						        						    aoData.push( { 'name' : 'date-minu', 'value' : $("#minu").val() } );
    						    aoData.push( { 'name' : 'date-maxu', 'value' : $("#maxu").val() } );
    						    						    					        
					        //$.getJSON( sSource, aoData, function (json) {
					            /* Do whatever additional processing you want on the callback, then tell DataTables */
					        //    fnCallback(json)
					        //} );						    
					        $.ajax({
							    'dataType' : 'json',
							    'data' : aoData,
							    'type' : 'POST',
							    'url' : sSource,
							    'success' : fnCallback
						    });
					    },
					    "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
							/* Append the grade to the default row class name */ 
						    var id = aData[0];
						    $(nRow).attr("id",id);
							return nRow;
						},
                        "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {  
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

                            $('*[class^="button-ui"]').each( function ( i ) {
                                var name_button = $(this).data('ui-icon');
                                var class_button = $(this).attr('class');                                 
                                $("a.button-"+name_button).button({icons: {primary: name_button}});
                                            				});                             

                            /* Add a select menu for each TH element in the table footer */
                            /* http://datatables.net/forums/discussion/comment/33095 */
                            
                            fnCreateFooterFilter()
                        },                                               
                                                 

                                                "aaSorting": 
                            [
                                                                    [6,'desc'],                
                                                                    
                            ],
                        
                                                "aoColumns": 
                            [
                                                                   {"bSortable":true},                
                                                                    {"bSortable":true},                
                                                                    {"bSortable":true},                
                                                                    {"bSortable":true},                
                                                                    {"bSortable":true},                
                                                                    {"bSortable":false},                
                                                                    {"bSortable":false},                
                                                                    {"bSortable":false},                
                                                                    {"bSortable":true},                
                                                                    {"bSortable":false},                
                                             
                            ],
                                                
                            
                        "aLengthMenu": [[1, 5, 10, 15, 20, 25, 50, 100, 500, 1000, 5000 -1], [1, 5, 10, 15, 20, 25, 50, 100, 500, 1000, 5000, "All"]],
                                                "iDisplayLength": 20,
                                
                        
                        "oLanguage": {
                            "sLoadingRecords": "<div id='spin'></div>Veuillez patienter pendant le chargement du tableau !",
                            "sProcessing": "<div id='spin' style='display:block;width:24px;height:24px;float:left;margin: 6px 2px;'></div>Veuillez patienter pendant le chargement du tableau !",
                            "sLengthMenu": "Afficher _MENU_ enregistrements par page",
                            "sZeroRecords": "Nothing found - sorry",
                            "sInfo": "Affichage de _START_ à _END_ de _TOTAL_ enregistrements",
                            "sInfoEmpty": "Affichage de 0 à 0 de 0 enregistrement",
                            "sInfoFiltered": "(filtré à partir de _MAX_ enregistrements au total)",
                            "sInfoPostFix": "",
                            "sSearch": "Rechercher",
                            "sUrl": "",
                            "oPaginate": {
                                "sFirst":    "Premier",
                                "sPrevious": "Précédent",
                                "sNext":     "Suivant",
                                "sLast":     "Dernier"
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
                        //avec multi-filtre : "sDom": '<"block_filter"><"H"RTfr<"clear">W>tC<"F"lpi>',
                                                                                "sDom": '<"H"RTfr<"clear">W>tC<"F"lpi>',
                                                    
                        "oTableTools": {
                            "sSwfPath": "/bundles/sfynxtemplate/js/datatable/extras/TableTools/media/swf/copy_csv_xls_pdf.swf?v1_0_0",
                            "sRowSelect": "multi",       //  ['single', 'multi']
                            "aButtons": [
                                                                                                                                
                                                                                                                                                        {
                                                "sExtends": "editor_remove",
                                                "sButtonText": "<img class='btn-action' src='/bundles/sfynxsmoothness/admin/grid/img/penabled.png?v1_0_0' title='Activer' alt='Activer'  />Activer",
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
                                                "sButtonText": "<img class='btn-action' src='/bundles/sfynxsmoothness/admin/grid/img/pdisable.png?v1_0_0' title='Désactiver' alt='Désactiver'  />Désactiver",
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
                                                "sButtonText": "<img class='btn-action' src='/bundles/sfynxsmoothness/admin/grid/img/select_all.png?v1_0_0' title='Sélectionner' alt='Sélectionner'  />Sélectionner",
                                                "fnComplete": function ( nButton, oConfig, oFlash, sFlash ) {
                                                    $("input[type=checkbox]").prop('checked', false);
                                                },
                                            },    
                                                                            
                                                                                                    		                                            {
                                                "sExtends": "select_none",
                                                "sButtonText": "<img class='btn-action' src='/bundles/sfynxsmoothness/admin/grid/img/select_none.png?v1_0_0' title='Désélectionner' alt='Désélectionner'  />Désélectionner",
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
                                                { "bVisible": false, "aTargets": [ 1 ] },                
                                                            
                    
                        ],
                        "oColumnFilterWidgets": {
                            "sSeparator": "\\s*/+\\s*",
                            "aiExclude": [ 
                                                                0,                
                                                1,                
                                                2,                
                                                3,                
                                                4,                
                                                5,                
                                                6,                
                                                7,                
                                                8,                
                                                9,                
                                                            
                            ]
                        },

                    });

                                                        
                        
                                                    grid_mediaoTable.rowGrouping({ 
                                                                iGroupingColumnIndex: 2,
                                        
                                                                sGroupingColumnSortDirection: "desc",
                                                                                                                                                                
                                                                bHideGroupingColumn: true,
                                                                                                                                sGroupBy: "name",
                                                                
                                
                                                                                                
                                                                                                                                
                                                                                                                                
                                
                                                                sDateFormat: "yyyy-MM-dd",    
                                                                                                                                
                                bExpandableGrouping: true, 
                                bExpandableGrouping2: true,
                                
                                oHideEffect: { method: "hide", duration: "fast", easing: "linear" },
                                oShowEffect: { method: "show", duration: "slow", easing: "linear" },
                                
                                                            });
                                                    
                            
                        
                                        
                        
                                                        
                            
                        
                                        
                        
                                                        
                            
                        
                                        
                        
                                                        
                            
                        
                                        
                        
                                                        
                            
                        
                                                            


                    var content = $("#blocksearch_content").html();
                    $("#blocksearch_content").html('');
                    $("#grid_media").before(content);

                    $("#global_filter").keyup( fnFilterGlobal );
                    $("#global_regex").click( fnFilterGlobal );
                    $("#global_smart").click( fnFilterGlobal );

                                            
                    $("#col4_filter").keyup( function() { fnFilterColumn( 3 ); } );
                    $("#col4_regex").click(  function() { fnFilterColumn( 3 ); } );
                    $("#col4_smart").click(  function() { fnFilterColumn( 3 ); } );
                                                
                        
                    $("#col5_filter").keyup( function() { fnFilterColumn( 4 ); } );
                    $("#col5_regex").click(  function() { fnFilterColumn( 4 ); } );
                    $("#col5_smart").click(  function() { fnFilterColumn( 4 ); } );
                                                
                                                

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
                         
                   });            
        