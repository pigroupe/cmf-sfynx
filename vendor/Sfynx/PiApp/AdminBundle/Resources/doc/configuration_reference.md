#Configuration Reference

All available configuration options are listed below with their default values.

``` yaml
pi_app_admin:
    layout:
        init_pc:
            template_name: layout-pi-sfynx.html.twig
            route_redirection_name: home_page
        init_mobile:
            template_name: Default
            route_redirection_name: home_page
        login_role:
            redirection_admin: admin_homepage
            template_admin: layout-pi-admin.html.twig
            redirection_user: admin_homepage
            template_user: layout-pi-admin.html.twig
            redirection_subscriber: home_page
            template_subscriber: layout-pi-sfynx.html.twig
        template:
            template_connection: layout-security.html.twig
            template_form: fields.html.twig
            template_grid: grid.theme.html.twig
            template_flash: flash.html.twig
        meta_head:
            author: Sfynx
            copyright: http://pigroupe.github.io/cmf-sfynx/
            title: Sfynx
            description: Based in Europe with operational offices in Switzerland, France, Russia, West and South Africa, Singapore.
            keywords: Sfynx, symfony 2, framework, CMF, CMS, PHP web applications
            og_title_add: "Sfynx : "
            og_type: website
            og_image: bundles/piappadmin/images/logo/logo-sfynx-white.png
            og_site_name: http://pigroupe.github.io/cmf-sfynx/
            additions:
                 ### robot management
                 1: "<meta name='robots' content='ALL'/>" # to start referencement
                 #1: "<meta name='robots' content='noodp'/>" # referencement without DMOZ description
                 #1: "<meta name='robots' content='noindex, nofollow'/>"   # to stop referencement
                 
                 ### mobile management
                 #2: "<meta name='apple-mobile-web-app-capable' content='yes'/>"
                 #3: "<meta name='apple-mobile-web-app-status-bar-style' content='black'/>"
                 #4: "<meta name='viewport' id='viewport'  content='target-densitydpi=device-dpi, user-scalable=no' />"
                 #5: "<meta name='viewport' content='initial-scale=1.0; user-scalable=0; minimum-scale=1.0; maximum-scale=1.0;' />"
                 #6: "<!-- Mobile viewport optimized: h5bp.com/viewport -->"
                 #7: "<meta name='viewport' content='width=device-width,initial-scale=1,maximum-scale=1'>"
                 
                 ### Empécher Microsoft de générer des "smart tags" sur notre page web.
                 #8: "<meta name='MSSmartTagsPreventParsing' content='TRUE'/>"            
    translation:
        defaultlocale_setting: false
    page:
        homepage_deletewidget: true
        page_management_by_user_only: true
        route:
            with_prefix_locale: true
            single_slug: false
        esi:
            force_private_response_for_all: false
            force_private_response_only_with_authentication: true
            disable_after_post_request: true
        widget:
            render_service_with_ajax: false
            ajax_disable_after_post_request: true
        refresh:
            allpage: true
            allpage_containing_snippet: true
            css_js_cache_file: true
        indexation_authorized_automatically: false
        switch_language_browser_authorized: true
        switch_layout_mobile_authorized: true
        memcache_enable_all: false
        seo_redirection:
            seo_authorized: true
            seo_repository : "%kernel.root_dir%/cache/seo"
            seo_file_name : seo_links.yml  
        scop:
            authorized: true    
            browscap:
                remote_ini_url:       http://browscap.org/stream?q=Full_PHP_BrowsCapINI
                remote_ver_url:       http://browscap.org/version
                cache_dir:            "%kernel.root_dir%/cache/browscap" # null : If null, use your application cache directory
                timeout:              5
                update_interval:      432000
                error_interval:       7200
                do_auto_update:       true
                update_method:        'cURL' # Supported methods: 'URL-wrapper','socket','cURL' and 'local'.
                local_file:           null # Only if used
                cache_filename:       'cache.php'
                ini_filename:         'browscap.ini'
                lowercase:            false # You need to rebuild the cache if this option is changed
                silent:               false            
            globals:
                navigator:
                    chrome:  25
                    safari:  4
                    ie:      7
                    firefox: 11.9
                mobile:
                    android: 2.2
                    ios: 5.9
                tablet:
                    android: 3.9
                    ios: 5.9      
    cookies:
        date_expire: true
        date_interval:  604800 # PT4H  604800
        application_id: sfynx
    permission:
        restriction_by_roles: false
        authorization:
            prepersist: true
            preupdate: true
            preremove: true
        prohibition:
            preupdate: true
            preremove: true     
    encrypters:
        encrypter_expert:
            encryptor_annotation_name: PiApp\AdminBundle\EventSubscriber\Encryptors\Annotation\Expertencrypted
            encryptor_class: PiApp\AdminBundle\EventSubscriber\Encryptors\ExpertEncryptor #  If you want, you can use your own Encryptor. Encryptor must implements EncryptorInterface interface
            encryptor_options:
                secret_key: "@kernel.secret" #The secret that is used to encrypt data. By default, it will use the kernel secret.
                algorithm: "rijndael-128" #Encryption algorithm
                mode: "cbc" #Encryption mode
                random_initialization_vector: true #If you set it to false, it will use a blank string as initialization vector.
                base64: true #Encode the encrypted data with the base64 algorithm.
                base64_url_safe: true #Replace "+" and "/" characters by "-" and "_" 
        encrypter_aes:
            encryptor_annotation_name: PiApp\AdminBundle\EventSubscriber\Encryptors\Annotation\Aesencrypted
            encryptor_class: PiApp\AdminBundle\EventSubscriber\Encryptors\AESEncryptor
            encryptor_options:      
                secret_key: "@kernel.secret"                  
    form:
        show_legend: true
        show_child_legend: false
        error_type: inline
    mail:
        overloading_mail: ~ # test@gmail.com   
    admin:
        context_menu_theme: pi2 # {'xp', 'default', 'vista', 'osx', 'human', 'gloss', 'gloss,gloss-cyan', 'gloss,gloss-semitransparent', 'pi', 'pi2'}
        grid_index_css: style-grid-7.css             
        grid_show_css: style-grid-7.css
        theme_css: smoothness # {'sfynx', 'aristo', 'rocket', 'smoothness', 'dark-hive'}
```
