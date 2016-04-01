                    var PIimportwidget = {
                        
                        jQuery : $,
                        
                        settings : {
                            columns : '.column',
                            widgetSelector: '.widget',
                            handleSelector: '.widget-head',
                            contentSelector: '.widget-content',
                            widgetDefault : {
                                movable: true,
                                removable: true,
                                collapsible: true,
                                editable: true,
                                colorClasses : ['color-yellow', 'color-red', 'color-blue', 'color-white', 'color-orange', 'color-green']
                            },
                            widgetIndividual : {
                                intro : {
                                    movable: false,
                                    removable: false,
                                    collapsible: false,
                                    editable: false
                                }
                            }
                        },
        
                        init : function () {
                            this.addWidgetControls();
                            this.makeSortable();
                        },
                        
                        getWidgetSettings : function (id) {
                            var $ = this.jQuery,
                                settings = this.settings;
                            return (id&&settings.widgetIndividual[id]) ? $.extend({},settings.widgetDefault,settings.widgetIndividual[id]) : settings.widgetDefault;
                        },
                        
                        addWidgetControls : function () {
                            var PIimportwidget = this,
                                $ = this.jQuery,
                                settings = this.settings;
                                
                            $(settings.widgetSelector, $(settings.columns)).each(function () {
                                var thisWidgetSettings = PIimportwidget.getWidgetSettings(this.id);
                                if (thisWidgetSettings.removable) {
                                    $('<a href="#" class="remove">CLOSE</a>').mousedown(function (e) {
                                        e.stopPropagation();    
                                    }).click(function () {
                                        if (confirm('This widget will be removed, ok?')) {
                                            $(this).parents(settings.widgetSelector).animate({
                                                opacity: 0    
                                            },function () {
                                                $(this).wrap('<div/>').parent().slideUp(function () {
                                                    $(this).remove();
                                                });
                                            });
                                        }
                                        return false;
                                    }).appendTo($(settings.handleSelector, this));
                                }
                                
                                if (thisWidgetSettings.editable) {
                                    $('<a href="#" class="edit">EDIT</a>').mousedown(function (e) {
                                        e.stopPropagation();    
                                    }).toggle(function () {
                                        $(this).css({backgroundPosition: '-66px 0', width: '55px'})
                                            .parents(settings.widgetSelector)
                                                .find('.edit-box').show().find('input').focus();
                                        return false;
                                    },function () {
                                        $(this).css({backgroundPosition: '', width: ''})
                                            .parents(settings.widgetSelector)
                                                .find('.edit-box').hide();
                                        return false;
                                    }).appendTo($(settings.handleSelector,this));
                                }
                                
                                if (thisWidgetSettings.collapsible) {
                                    $('<a href="#" class="collapse">COLLAPSE</a>').mousedown(function (e) {
                                        e.stopPropagation();    
                                    }).toggle(function () {
                                        $(this).css({backgroundPosition: '-38px 0'})
                                            .parents(settings.widgetSelector)
                                                .find(settings.contentSelector).hide();
                                        return false;
                                    },function () {
                                        $(this).css({backgroundPosition: ''})
                                            .parents(settings.widgetSelector)
                                                .find(settings.contentSelector).show();
                                        return false;
                                    }).prependTo($(settings.handleSelector,this));
                                }
                            });
                            
                        },
    
                        makeSortable : function () {
                            var PIimportwidget = this,
                                $ = this.jQuery,
                                settings = this.settings,
                                $sortableItems = (function () {
                                    var notSortable = '';
                                    $(settings.widgetSelector,$(settings.columns)).each(function (i) {
                                        if (!PIimportwidget.getWidgetSettings(this.id).movable) {
                                            if (!this.id) {
                                                this.id = 'widget-no-id-' + i;
                                            }
                                            //notSortable += '#' + this.id + ',';
                                        }
                                    });
                                    return $('> li:not(' + notSortable + ')', settings.columns);
                                })();
                            
                            $sortableItems.find(settings.handleSelector).css({
                                cursor: 'move'
                            }).mousedown(function (e) {
                                $sortableItems.css({width:''});
                                $(this).parent().css({
                                    width: $(this).parent().width() + 'px'
                                });
                            }).mouseup(function () {
                                if (!$(this).parent().hasClass('dragging')) {
                                    $(this).parent().css({width:''});
                                } else {
                                    $(settings.columns).sortable('disable');
                                }
                            });
        
                            $(settings.columns).sortable({
                                items: $sortableItems,
                                connectWith: $(settings.columns),
                                handle: settings.handleSelector,
                                placeholder: 'widget-placeholder',
                                forcePlaceholderSize: true,
                                revert: 300,
                                delay: 100,
                                opacity: 0.8,
                                containment: 'document',
                                start: function (e,ui) {
                                    $(ui.helper).addClass('dragging');
                                },
                                stop: function (e,ui) {
                                    $(ui.item).css({width:''}).removeClass('dragging');
                                    $(settings.columns).sortable('enable');
                                }
                            });
                        }
                      
                    };
        
                    PIimportwidget.init();
            