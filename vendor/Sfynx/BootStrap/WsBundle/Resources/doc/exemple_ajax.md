#Example Of Usage


```php
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>

<script type="text/javascript">
    //<![CDATA[
    jQuery(document).ready(function() {

        
        /********************************
         * start block action with click
         ********************************/
        $("#block_test_url_1").click(function(e) {
            e.preventDefault();
            // start ajax 
            $.ajax({
                type: "GET",
                {# url: "{{ path('ws_auth_ajax', {'handler':'getpermisssion', 'getParams':{'ws_user_id': 7|encrypt('0A1TG4GO'), 'ws_application':'m1l'|encrypt('0A1TG4GO')}})|raw }}", #} 
                url: "{{ path('ws_auth_ajax', {'handler':'validatetoken', 'getParams':{'ws_user_id': 7|encrypt('0A1TG4GO'), 'ws_application':'m1l'|encrypt('0A1TG4GO'), 'ws_token':'g5Kyabe1nH62Y6WXinmkun-aumqJuWW_'|encrypt('0A1TG4GO')}})|raw }}",

                data: "",
                datatype: "json",
                cache: false,
                error: function(msg) {
                    alert("Error !: " + msg);ma 
               },
                success: function(response) {
                    alert(response.content);
                    alert(response.header);
                    alert(response.url);
                }
            });
            // end ajax
        });
        // end click
    });
    //]]>
</script>
```
