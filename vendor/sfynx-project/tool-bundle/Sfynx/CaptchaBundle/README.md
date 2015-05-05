CAPTCHA SFYNX Bundles
=====================


## Parameters configuration
**parameter.yml** : 

``` bash
parameters:

    sfynx_captcha_form_options:
        root_pictures: '@@SfynxCaptchaBundle/Resources/public'
        captcha_pictures:
            - ['Sfynx', '/img/captcha/logo-sfynx.png']
            - ['Pi-groupe', '/img/captcha/pigroupe.jpg']
```

**routing.yml** : 

``` bash
    SfynxCaptchaBundle:
        resource: "@SfynxCaptchaBundle/Resources/config/routing.yml"
        prefix:   /
```

## Twig

``` bash
    
     {% form_theme form with ['SfynxCaptchaBundle:Form:captcha.html.twig'] %}

```

## Css/js

``` bash
    
    {% stylesheets filter='css_url_rewrite, ?yui_css'
    '@SfynxCaptchaBundle/Resources/public/css/sfynx-captcha.css' %}
    <link rel="stylesheet" href="{{ asset_url }}" />
    {% endstylesheets %}


    {% javascripts filter='?yui_js'
        '@SfynxCaptchaBundle/Resources/public/js/sfynx-captcha.js'
    %}
    <script type="text/javascript" src="{{ asset_url }}"></script>
    {% endjavascripts %}

```
