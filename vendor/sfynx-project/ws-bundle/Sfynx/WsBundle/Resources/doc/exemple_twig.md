#Example Of Usage


```php
{% set resultat_default_handler = ws_auth.getPermisssion('getpermisssion', 'GET',  
    {'ws_user_id':7|encrypt('0A1TG4GO'), 'ws_application':'m1l'|encrypt('0A1TG4GO')}) %}
    &nbsp;&nbsp;  <a href="{{ resultat_default_handler.url }}">test authentication api with twig</a>
    <br />
    Url  result: 
    <br />
{{ resultat_default_handler.url }}
    <br /><br />
    Header result: 
    <br />
{{ resultat_default_handler.header|json_encode() }}
    <br /><br />
    Contenu result: 
    <br />
{{ resultat_default_handler.content }}
    <br /><br />
```
