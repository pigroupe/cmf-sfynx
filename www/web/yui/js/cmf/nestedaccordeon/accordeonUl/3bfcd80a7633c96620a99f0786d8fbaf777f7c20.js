            $.fn.accordion.defaults.container = false; 
            $(function() {
                $("#tree ul:first").attr('id', 'acc1');
                $("#tree ul:first").attr('class', 'accordion');
                $("#acc1").accordion({
                    el: ".h", 
                    head: "h4, h5", 
                    next: "div", 
                    initShow : "div.outer:eq(1)"
                });
            });
        