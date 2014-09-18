#Configuration Reference

All available configuration options are listed below with their default values.

``` yaml
#
# SfynxBrowserBundle configuration
#  
sfynx_browser:            
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
```
