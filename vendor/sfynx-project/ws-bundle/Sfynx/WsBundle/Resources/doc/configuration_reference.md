#Configuration Reference

All available configuration options are listed below with their default values.

``` yaml
#
# WsBundle configuration
#
sfynx_ws:    
    auth:
        log:
            dev: true
            test: true
            prod: false
        domains:
            domain1:
                key: or23
                url: http://www.sfynx.local/setCookies.php
            domain2:
                key: or22
                url: http://www.sfynx22.local/setCookies.php              
        handlers:
            getpermisssion:
                key: 0A1TG4GO
                method: GET
                api: http://www.sfynx22.local/ws/auth/get/permisssion
                format: json
            validatetoken:
                key: 0A1TG4GO
                method: GET
                api: http://www.sfynx22.local/ws/auth/validate/token
                format: json 
    sso:
        prefix_host: 'http://'
        uri_proxy: 'http://www.sfyn22.local'
        uri_proxy_login: 'http://www.sfyn22.local'
        application_name: sso-sfynx                     
```
