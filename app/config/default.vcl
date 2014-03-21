# http://www.mediawiki.org/wiki/Manual:Varnish_caching
# set default backend if no server cluster specified
backend default {
    .host = "127.0.0.1";
    .port = "80";
}
 
# access control list for "purge": open to only localhost and other local nodes
acl purge {
    "127.0.0.1";
}
 
# vcl_recv is called whenever a request is received 
sub vcl_recv {
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
        set req.http.host = regsub(req.http.host,"^monsite\.loc$", "www.monsite.loc");    

        if (req.http.Referer) {
            set req.http.Referer = regsub(req.http.Referer,"foo","bar");
        }
        
        # normalize Accept-Encoding to reduce vary
        if (req.http.Accept-Encoding) {
          if (req.http.User-Agent ~ "MSIE 6") {
            unset req.http.Accept-Encoding;
          } elsif (req.http.Accept-Encoding ~ "gzip") {
            set req.http.Accept-Encoding = "gzip";
          } elsif (req.http.Accept-Encoding ~ "deflate") {
            set req.http.Accept-Encoding = "deflate";
          } else {
            unset req.http.Accept-Encoding;
          }
        }  

        set req.http.Surrogate-Capability = "abc=ESI/1.0";   

        # Do not cache these paths
        if (req.url ~ "^/esi-widget-page/.*$") {
            #set req.grace = 0s;
            return(pass);
        }       

        # Force lookup if the request is a no-cache request from the client.
        if (req.http.Cache-Control ~ "(private|no-cache|no-store)") {
            ban_url(req.url);
        }          
 
        # This uses the ACL action called "purge". Basically if a request to
        # PURGE the cache comes from anywhere other than localhost, ignore it.
        if (req.request == "PURGE") {
            if (!client.ip ~ purge) {
                error 405 "Not allowed.";
            }
            return(lookup);
        }

        # Pass any requests that Varnish does not understand straight to the backend.
        if (req.request != "GET" && req.request != "HEAD" &&
            req.request != "PUT" && req.request != "POST" &&
            req.request != "TRACE" && req.request != "OPTIONS" &&
            req.request != "DELETE") {
            return(pipe);
        }     /* Non-RFC2616 or CONNECT which is weird. */

        if( req.http.Authorization || req.http.Cookie) {
            return (pass);
        }        

        return (lookup);
}
 
sub vcl_pipe {
        # Note that only the first request to the backend will have
        # X-Forwarded-For set.  If you use X-Forwarded-For and want to
        # have it set for all requests, make sure to have:
        # set req.http.connection = "close";
 
        # This is otherwise not necessary if you do not do any request rewriting.
 
        # set req.http.connection = "close";
}

# Determine the cache key when storing/retrieving a cached page
sub vcl_hash {
    hash_data(req.url);
    if (req.http.host) {
        hash_data(req.http.host);
    } else {
        hash_data(server.ip);
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
 
# Called if the cache has a copy of the page.
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

sub vcl_pipe {
  set bereq.http.connection = "close";
}
 
# Called after a document has been successfully retrieved from the backend.
sub vcl_fetch {
        # Don't allow static files to set cookies
        if (req.url ~ "(?i)\.(ico|swf|css|js|html|htm|gz|xml)(\?[a-z0-9]+)?$") {
            unset beresp.http.Set-cookie;
            set beresp.ttl = 4h;  
            return (hit_for_pass);
        } else  if (req.url ~ "(?i)\.(png|gif|jpeg|jpg|pdf)(\?[a-z0-9]+)?$") {
            # Don't allow static files to set cookies
            unset beresp.http.Set-cookie;
            set beresp.ttl = 0s;  
            return (hit_for_pass);
        } 

        set beresp.do_esi = true;     

        #Pour que tous les utilisateurs reçoivent la page commune cachée et qu’elle reste dynamique en fonction des utilisateurs il faut enlever la session des cookies pour les pages/parties #communes et la laisser pour les pages/parties individuelles.
        if ( ! req.url ~ "^/esi-widget-page" ) {
            set req.http.Cookie = regsuball(req.http.Cookie, "PHPSESSID=[^;]+(; )?", "");
            if (req.http.Cookie ~ "^$") {
                unset req.http.Cookie;
            }
        } 

        # Allow items to be stale if needed
        if (req.http.Cookie ~"(UserID|_session)") {
            # Don't cache content for logged in users
            set beresp.http.X-Cacheable = "NO:Got Session";
            return (hit_for_pass);
        } else if (beresp.http.Cache-Control ~ "private") {
            # Respect the Cache-Control=private header from the backend
            set beresp.http.X-Cacheable = "NO:Cache-Control=private";
            return (hit_for_pass);
        } else if (beresp.http.Cache-Control ~ "no-cache") {
            # Respect the Cache-Control=private header from the backend
            set beresp.http.X-Cacheable = "NO:Cache-Control=private";
            return (hit_for_pass);
        } else if (beresp.ttl <= 0s) {
            set beresp.http.X-Cacheable = "NO:Not Cacheable";
            return (hit_for_pass);
        } else if (beresp.ttl < 1s) {
            # Extend the lifetime of the object artificially
            set beresp.ttl = 300s;
            set beresp.grace = 300s;
            set beresp.http.X-Cacheable = "YES:Forced";
        } else {
            set beresp.http.X-Cacheable = "YES";
        }        

        return(deliver);           
}


sub vcl_deliver {
    set resp.http.Age = "0";
    return (deliver);

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