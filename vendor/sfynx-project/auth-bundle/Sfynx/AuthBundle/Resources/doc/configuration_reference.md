#Configuration Reference

All available configuration options are listed below with their default values.

``` yaml
#
# SfynxAuthBundle configuration
#  
sfynx_auth:
    loginfailure:
        authorized: true
        time_expire: 3600
        connection_attempts: 5
        cache_dir: "%kernel.root_dir%/cachesfynx/loginfailure"
    locale:
        authorized: ~ #[fr_FR, en_GB]
        cache_file: "%kernel.root_dir%/cachesfynx/languages.json"
    browser:
        switch_language_authorized: true
        switch_layout_mobile_authorized: false
    default_layout:
        init_pc:
            template: layout-pi-sfynx.html.twig
        init_mobile:
            template: Default
    default_login_redirection:
        redirection: admin_homepage
        template: layout-pi-admin-cmf.html.twig
    theme:     
        name: smoothness # {'flatlab','smoothness'} 
        login: "SfynxSmoothnessBundle::Login\\"
        layout: "SfynxSmoothnessBundle::Layout\\"
        email:
            registration:
                from_email:
                    address: contact@sfynx.fr
                template: SfynxSmoothnessBundle:Login\\Registration:email.txt.twig
            resetting:
                from_email:
                    address: contact@sfynx.fr
                template: SfynxSmoothnessBundle:Login\\Resetting:email.txt.twig 
        global:
            layout: "SfynxSmoothnessBundle::Layout\\layout-global-cmf.html.twig"
            css: "bundles/sfynxsmoothness/layout/screen-layout-global.css"
        ajax:
            layout: "SfynxSmoothnessBundle::Layout\\layout-ajax.html.twig"
            css: "bundles/sfynxsmoothness/layout/screen-layout-ajax.css"
        error:
            route_name: ~ # error_404
            html: "@SfynxSmoothnessBundle/Resources/views/Error/error.html.twig"
        admin:
            pc: "SfynxSmoothnessBundle::Layout\\Pc\\"
            mobile: "SfynxSmoothnessBundle::Layout\\Mobile\\Admin\\"
            css: "bundles/sfynxsmoothness/admin/screen.css"
            home: "SfynxSmoothnessBundle:Home:admin.html.twig"  # SfynxAuthBundle:Frontend:index.html.twig
            dashboard: "dashboard.default.html.twig"
            grid:
                img: "/bundles/sfynxsmoothness/admin/grid/img/"
                css: ""   
                type: simple
                state_save: false
                row_select: 'multi'  # multi, single
                pagination: true 
                pagination_type: "full_numbers"  # bootstrap, full_numbers, simple_numbers    
                pagination_top: false
                lengthmenu: 20
                filters_tfoot_up: true
                filters_active: false  
            form:
                builder: "SfynxSmoothnessBundle:Form"
                template: "SfynxSmoothnessBundle:Form:fields.html.twig" 
                css: ""  
            flash: "SfynxSmoothnessBundle:Flash:flash.html.twig" 
        front:
            pc: "SfynxSmoothnessBundle::Layout\\Pc\\"
            pc_path: "@SfynxSmoothnessBundle/Resources/views/Layout/Pc/"
            mobile: "SfynxSmoothnessBundle::Layout\\Mobile\\"
            mobile_path: "@SfynxSmoothnessBundle/Resources/views/Layout/Mobile/"
            css: ""
        connexion:
            login: "SfynxSmoothnessBundle::Login\\Security\\login-layout.html.twig"
            widget : "SfynxSmoothnessBundle::Login\\Security\\connexion-widget.html.twig" 
```
