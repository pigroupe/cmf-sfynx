#Configuration Reference

All available configuration options are listed below with their default values.

``` yaml
#
# SfynxCmfBundle configuration
#
sfynx_cmf:
    cache_dir:
        etag: "%kernel.root_dir%/cachesfynx/Etag/"
        indexation: "%kernel.root_dir%/cachesfynx/Indexation/"
        widget: "%kernel.root_dir%/cachesfynx/Widget/"
        seo : "%kernel.root_dir%/cachesfynx/Seo"
    seo:
        redirection_oldurl_to_new_url:
            authorized: false
            file_name : seo_links.yml 
        meta_head:
            author: Sfynx
            copyright: http://www.sfynx.fr
            title: Sfynx
            description: Based in Europe with operational offices in Switzerland, France, Russia, West and South Africa, Singapore.
            keywords: Sfynx, symfony 2, framework, CMF, CMS, PHP web applications
            og_title_add: "Sfynx : "
            og_type: website
            og_image: bundles/sfynxtemplate/images/logo/logo-sfynx-white.png
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
    page:
        homepage_deletewidget: true
        page_management_by_user_only: true
        route:
            with_prefix_locale: true
            single_slug: false
        esi:
            authorized: false
            encrypt_key: %esi_key%
            force_widget_tag_esi_for_varnish: false # true to remplace render_esi function to the esi tag used by varnish
            force_private_response_for_all: false # true if you want that all responses will have a private Cache-control without max-age
            force_private_response_only_with_authentication: false # true if you want that all responses will have a private Cache-control without max-age after authentification
            disable_after_post_request: true
        widget:
#            render_service_with_asynchrone_thread:
#                 authorized: true
#                 thread_command_start: nohup php -f
#                 thread_command_option: 2> /dev/null > /dev/null &    # 2>&1 & echo $!
#                 thread_time_limit: 20 # time limit in second
#                 thread_cache_result: memcached # memcache|file
#                 thread_cache_result_memcached_ttl: 60 # time in second
            render_service_with_ttl: false
            render_service_with_ajax: false
            ajax_disable_after_post_request: true
        refresh:
            allpage: true
            allpage_containing_snippet: true
            css_js_cache_file: true
        indexation_authorized_automatically: false
        memcache_enable_all: false
        scop:
            authorized: true    
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
    admin:
        context_menu_theme: pi2 # {'xp', 'default', 'vista', 'osx', 'human', 'gloss', 'gloss,gloss-cyan', 'gloss,gloss-semitransparent', 'pi', 'pi2'}  
```
