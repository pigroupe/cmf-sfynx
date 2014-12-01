# http://www.mediawiki.org/wiki/Manual:Varnish_caching
# set default backend if no server cluster specified

# import parsereq;

backend default {
    .host = "127.0.0.1";
    .port = "80";
    .connect_timeout = 600s;
    .first_byte_timeout = 600s;
    .between_bytes_timeout = 600s;
}
 
# access control list for "purge": open to only localhost and other local nodes
acl purge {
    "127.0.0.1";
}
 
# vcl_recv is called whenever a request is received 
sub vcl_recv {
        # parsereq.init();


        ### redirect no www to www
        if (req.http.host == "mywebsite.loc") {
            set req.http.host = "www.mywebsite.loc";
            error 750 "http://" + req.http.host + req.url;
        }
l
        ### Add a Surrogate-Capability header to announce ESI support.
            set req.http.Surrogate-Capability = "abc=ESI/1.0";


        ###  ...

            if (req.restarts == 0) {
                if (req.http.x-forwarded-for) {
                    set req.http.X-Forwarded-For = req.http.X-Forwarded-For + ", " + client.ip;
                } else {
                    set req.http.X-Forwarded-For = client.ip;
                }
            }
            set req.backend = default;

            # Normalize requests sent via curls -X mode and LWP
            if (req.url ~ "^http://") {
                set req.url = regsub(req.url, "http://[^/]*", "");
            }

            # Normalize hostname to avoid double caching
            set req.http.host = regsub(req.http.host,"^mywebsite\.loc$", "www.mywebsite.loc");    

            if (req.http.Referer) {
                set req.http.Referer = regsub(req.http.Referer,"foo","bar");
            }


        ### always cache these items:

            if (req.request == "GET" && req.url ~ "\.(js)$") {
                return(lookup);
            }

            ## various other content pages	
            if (req.request == "GET" && req.url ~ "\.(css|html|woff)$") {	
                return(lookup);
            }		

            ## multimedia 
            #if (req.request == "GET" && req.url ~ "\.(svg|swf|ico|mp3|mp4|m4a|ogg|mov|avi|wmv)$") {
            #    return(lookup);
            #}	

            ## images
            #if (req.request == "GET" && req.url ~ "\.(gif|jpg|jpeg|bmp|png|tiff|tif|ico|img|tga|wmf)$") {
            #    return(lookup);
            #}

            ## xml

            if (req.request == "GET" && req.url ~ "\.(xml)$") {
                return(lookup);
            }


        ### do not cache these rules:

            # Pass any requests that Varnish does not understand straight to the backend.
            if (req.request != "GET" && req.request != "HEAD" &&
                req.request != "PUT" && req.request != "POST" &&
                req.request != "TRACE" && req.request != "OPTIONS" &&
                req.request != "DELETE") {
                return(pipe);
            }     /* Non-RFC2616 or CONNECT which is weird. */

            # Don't cache POST, PUT, or DELETE requests
            if (req.request == "POST" || req.request == "PUT" || req.request == "DELETE") {
                set req.http.Cache-Control = req.http.Cache-Control + ", must-revalidate";
                return(pass);
            } 

            # Do not cache these paths
            if (req.url ~ "^/esi-widget-page/.*$") {
                #set req.grace = 0s;
                return(pass);
            }       

            # Force lookup if the request is a no-cache request from the client.
            if (req.http.Cache-Control ~ "(private|no-cache|no-store)") {
                ban_url(req.url);
            }  

            # Pass requests from logged-in users directly.
            if (req.http.Authenticate || req.http.Authorization) {
                return(pass);
            }

        ### don't cache authenticated sessions
            if (req.http.Cookie && req.http.Cookie ~ "authtoken=") {
                return(pipe);
            }

        ### if there is a purge make sure its coming from $localhost

            # This uses the ACL action called "purge". Basically if a request to
            # PURGE the cache comes from anywhere other than localhost, ignore it.
            if (req.request == "PURGE") {
                if (!client.ip ~ purge) {
                    error 405 "Not allowed.";
                }
                return(lookup);
            }

        ### parse accept encoding rulesets to make it look nice

            # normalize Accept-Encoding to reduce vary
            if (req.http.Accept-Encoding) {
                if (req.url ~ "\.(jpg|png|gif|gz|tgz|bz2|tbz|mp3|ogg)$") {
                    # No point in compressing these
                    unset req.http.Accept-Encoding;
                } elseif (req.http.User-Agent ~ "MSIE 6") {
                    unset req.http.Accept-Encoding;
                } elseif (req.http.Accept-Encoding ~ "gzip") {
                    set req.http.Accept-Encoding = "gzip";
                } elseif (req.http.Accept-Encoding ~ "deflate") {
                    set req.http.Accept-Encoding = "deflate";
                } else {
                    # unkown algorithm
                    unset req.http.Accept-Encoding;
                }
            } 
        

        if (req.http.host ~ "^www.mywebsite\.loc$") {
            return(lookup);
        }         

        if( req.http.Authorization || req.http.Cookie) {
            return(pass);
        }        

        ### if it passes all these tests, do a lookup anyway;
            return(lookup);
}
 
# # Called after vcl_recv
sub vcl_pipe {
        # Note that only the first request to the backend will have
        # X-Forwarded-For set.  If you use X-Forwarded-For and want to
        # have it set for all requests, make sure to have:
        set req.http.connection = "close";
 
        # This is otherwise not necessary if you do not do any request rewriting.
}

# Called after vcl_recv Determine the cache key when storing/retrieving a cached page
sub vcl_hash {
    hash_data(req.url);
    if (req.http.host) {
        hash_data(req.http.host);
        set req.http.X-TEST = req.http.X-TEST + " + "  + req.http.host; //add
    } else {
        hash_data(server.ip);
        set req.http.X-TEST = req.http.X-TEST + " + " + server.ip; //add
    }

    # Don't include cookie in hash
    # if (req.http.Cookie) {
    # hash_data(req.http.Cookie);
    # }

    if( req.http.Cookie ~ "locale" ) {
        hash_data(regsub( req.http.Cookie, "^.*?locale=([^;]*);*.*$", "\1" ));
    }
    return (hash);
}
 
# Called after vcl_hash if the cache has a copy of the page.
sub vcl_hit {
    if (req.request == "PURGE") {
        purge;
        error 200 "Purged.";
    }
    if (obj.ttl <= 0s) {
        return (pass);
    }
    return (deliver);
}

# Called after vcl_hash if the cache does not have a copy of the page.
sub vcl_miss {
    if (req.request == "PURGE") {
        error 404 "Not in cache.";
    }
    return (fetch);
}

sub vcl_pass {
  #ensure connections are closed and not reused
  set bereq.http.connection = "close";
}
 
# Called after a document has been successfully retrieved from the backend.
sub vcl_fetch {
        ### Check for ESI acknowledgement and remove Surrogate-Control header
            if (beresp.http.Surrogate-Control ~ "ESI/1.0") {
                unset beresp.http.Surrogate-Control;

                // For Varnish >= 3.0
                set beresp.do_esi = true;
            }
            /* By default Varnish ignores Cache-Control: nocache
            (https://www.varnish-cache.org/docs/3.0/tutorial/increasing_your_hitrate.html#cache-control),
            so in order avoid caching it has to be done explicitly */
            if (beresp.http.Pragma ~ "no-cache" ||
                 beresp.http.Cache-Control ~ "no-cache" ||
                 beresp.http.Cache-Control ~ "private") {
                return (hit_for_pass);
            }

        ### if i cant connect to the backend, ill set the grace period to be 600 seconds to hold onto content
            set beresp.ttl = 600s; 
            set beresp.grace = 600s;

            if (beresp.status == 404) { 
              set beresp.ttl = 0s; 
            }

            if (beresp.status >= 500) { 
              set beresp.ttl = 0s; 
            }

            if (req.request == "GET" && req.url ~ "\.(gif|jpg|jpeg|bmp|png|tiff|tif|ico|img|tga|wmf)$") {
              set beresp.ttl = 600s;     
            }

            # various other content pages
            if (req.request == "GET" && req.url ~ "\.(css|html)$") {
              set beresp.ttl = 600s;
            }

            if (req.request == "GET" && req.url ~ "\.(js)$") {
              set beresp.ttl = 600s;
            }

	### xml
            if (req.request == "GET" && req.url ~ "\.(xml)$") {
              set beresp.ttl = 600s;
            }

        ### multimedia
            if (req.request == "GET" && req.url ~ "\.(svg|swf|ico|mp3|mp4|m4a|ogg|mov|avi|wmv)$") {
              set beresp.ttl = 600s;
            }

        ### Don't allow static files to set cookies
            if (beresp.ttl > 0s) {
                # Don't allow static files to set cookies
                unset beresp.http.Set-cookie;
                unset req.http.Cookie;
                
                # Remove Expires from backend, it's not long enough
                unset beresp.http.expires;

                # Set the clients TTL on this object
                if(beresp.ttl == 600s) {
                    set beresp.http.cache-control = "public,max-age=600";
                } else {
                    set beresp.http.cache-control = "public";
                }

                # marker for vcl_deliver to reset Age
                set beresp.http.magicmarker = "1";
            }

        ### Don't allow static files to set cookies
            if (req.url ~ "(?i)\.(gif|jpg|jpeg|bmp|png|tiff|tif|ico|img|tga|wmf|pdf)(\?[a-z0-9]+)?$") {
                # Don't allow static files to set cookies
                unset beresp.http.Set-cookie;
                set beresp.ttl = 0s;  
                # return with no-cache to vcl_deliver
                return (hit_for_pass);
            } elseif (req.url ~ "(?i)\.(svg|swf|ico|mp3|mp4|m4a|ogg|mov|avi|wmv)(\?[a-z0-9]+)?$") {
                # Don't allow static files to set cookies
                unset beresp.http.Set-cookie;
                set beresp.ttl = 0s;  
                # return with no-cache to vcl_deliver
                return (hit_for_pass);
            } 

        ### Pour que tous les utilisateurs reçoivent la page commune cachée et qu’elle reste dynamique en fonction des utilisateurs il faut enlever la session des cookies pour les pages/parties #communes et la laisser pour les pages/parties individuelles.
            if ( ! req.url ~ "^/esi-widget-page" ) {
                set req.http.Cookie = regsuball(req.http.Cookie, "SFYNXSESSID=[^;]+(; )?", "");
                if (req.http.Cookie ~ "^$") {
                    unset req.http.Cookie;
                }
            } 

        ### Allow items to be stale if needed
            if (req.http.Cookie ~"(UserID|_session)") {
                # Don't cache content for logged in users
                set beresp.http.X-Cacheable = "NO:Got Session";
                # return with no-cache to vcl_deliver
                return (hit_for_pass);
            } elseif (beresp.http.Cache-Control ~ "(private|no-cache|no-store)") {
                # Respect the Cache-Control=private header from the backend
                set beresp.http.X-Cacheable = "NO:Cache-Control=private";
                # return with no-cache to vcl_deliver
                return (hit_for_pass);
            } elseif (beresp.ttl <= 0s) {
                set beresp.http.X-Cacheable = "NO:Not Cacheable";
                # return with no-cache to vcl_deliver
                return (hit_for_pass);
            } else {
                set beresp.http.X-Cacheable = "YES";
            }        

        return(deliver);           
}

# Called after vcl_it or vcl_fetch
sub vcl_deliver {
    # set resp.http.hoge = parsereq.body(post);
    # set resp.http.Age = "0";

    # Uncomment to add hostname to headers
    # set resp.http.X-Served-By = server.hostname;
    # Identify which Varnish handled the request
    if (obj.hits > 0) {
        set resp.http.X-Cache = "HIT";
        set resp.http.X-Cache-Hits = obj.hits;
    } else {
        set resp.http.X-Cache = "MISS";
    }
    # Remove version number sometimes set by CMS
    if (resp.http.X-Content-Encoded-By) {
        unset resp.http.X-Content-Encoded-By;
    }
    if (resp.http.magicmarker) {
        # Remove the magic marker, see vcl_fetch
        unset resp.http.magicmarker;
        # By definition we have a fresh object
        set resp.http.Age = "0";
    }

}

sub vcl_error { 
    ### redirect no www to www
        if (obj.status == 750) {
            set obj.http.Location = obj.response;
            set obj.status = 301;
            return(deliver);
        }

    ### 401 status
        if (obj.status == 401) {
            set obj.http.Content-Type = "text/html; charset=utf-8";
            set obj.http.WWW-Authenticate = "Basic realm=Secured";
            synthetic {" 

                <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
                "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">

                <HTML>
                <HEAD>
                    <TITLE>Error</TITLE>
                    <META HTTP-EQUIV='Content-Type' CONTENT='text/html;'>
                </HEAD>
                <BODY>
                    <H1>401 Unauthorized (varnish)</H1>
                </BODY>
                </HTML>
            "};
            return (deliver);
        }
}