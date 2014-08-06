backend default {
    .host = "127.0.0.1";
    .port = "80";
    .connect_timeout = 600s;
    .first_byte_timeout = 600s;
    .between_bytes_timeout = 600s;
}


###################################
# TRAITEMENT DE LA REQUETE CLIENT #
###################################
import std;

sub vcl_recv {

    if (req.http.Cache-Control ~ "no-cache") {
        return (pass);
    }

    set req.http.Surrogate-Capability = "abc=ESI/1.0";
    
    # SI LA DEMANDE CONCERNE UN ASSET, ON VIRE LES COOKIES
    if (req.url ~ "\.(jpg|jpeg|gif|png|ico|css|zip|tgz|gz|rar|bz2|pdf|txt|tar|wav|bmp|rtf|js|flv|swf|html|htm|mov|avi|mp3|mpg)(\?.*|)$") {
        unset req.http.Cookie;
        return (lookup);
    }

    # ON REGARDE LES COOKIES PRESENTS DANS LA REQUETE POUR ALLER CHERCHER DANS LE BON HASH DE CACHE

    # 3 PARAMETRES SERONT UTILISES :
    # - req.http.User-Agent = mobile ou not_mobile
    # - req.http._sess_auth_user = anonymous ou authenticated
    # - req.http._sess_user_type = fv (first-visit) ou pp (prospect)
    # 
    # Ces 3 parametres seront agrégés pour constituer le hash de la clé de cache

    # CAS DE PREMIERE CONNEXION DONC PAS DE COOKIE
    # ON POURRAIT CONSIDERER QUE L'UTILISATEUR EST CONNECTE, DONC PAS DE CACHE. MAIS DU COUP ON PLOMBE TOUTES LES REQUETES DE TYPE CRAWLER
    # ON VA DONC SETER DES PARAMETRES PAR DEFAULT POUR TROUVER DU CACHE SI IL EXISTE, ET ON VA AJOUTER UN PARAMETRE POUR NE PAS METTRE EN CACHE LA REPONSE
    # (pour ne pas par exemple mettre en cache un contenu mobile identifié comme tel par le site, alors que varnish a lui identifie le client comme non mobile...)

    if (regsuball( req.http.Cookie, " ", "" ) == "") {
        set req.http._sess_user_type = "fv";
        if (req.http.User-Agent ~ "iPad" ||
            req.http.User-Agent ~ "iPhone" ||
            req.http.User-Agent ~ "Android") {
                set req.http._sess_type_device = "mobile";
        } else {
                set req.http._sess_type_device = "not_mobile";
        }
        set req.http._sess_auth_user = "anonymous";
        # PARAMETRE UTILISE POUR NE PAS CACHER LA REPONSE
        set req.http._sess_not_cache = "dont"; 
    } else {
        # TYPE DE VISITEUR
        set req.http._sess_user_type_fp = regsub( regsub( req.http.Cookie, ".*nbi_visited=", "" ), ";.*", "" );
        set req.http._sess_user_type_verif = regsub( req.http._sess_user_type_fp, "=", "" );
        if (req.http._sess_user_type_fp == req.http._sess_user_type_verif) {
            set req.http._sess_user_type = req.http._sess_user_type_fp;
        } else {
            set req.http._sess_user_type = "fv";
        }
        unset req.http._sess_user_type_fp;
        unset req.http._sess_user_type_verif;

        # TYPE DE DEVICE
        set req.http._sess_type_device_fp = regsub( regsub( req.http.Cookie, ".*device_view=", "" ), ";.*", "" );
        set req.http._sess_type_device_verif = regsub( req.http._sess_type_device_fp, "=", "" );
        if (req.http._sess_type_device_fp == req.http._sess_type_device_verif) {
            set req.http._sess_type_device = req.http._sess_type_device_fp;
        } else {
            set req.http._sess_type_device = "not_mobile";
        }
        unset req.http._sess_type_device_fp;
        unset req.http._sess_type_device_verif;

        # UTILISATEUR IDENTIFIE OU NON
        set req.http._sess_auth_user_fp = regsub( regsub( req.http.Cookie, ".*nbi_auth_user=", "" ), ";.*", "" );
        set req.http._sess_auth_user_verif = regsub( req.http._sess_auth_user_fp, "=", "" );
        if (req.http._sess_auth_user_fp == req.http._sess_auth_user_verif) {
            set req.http._sess_auth_user = "authenticated";
        } else {
            set req.http._sess_auth_user = "anonymous";
        }
        unset req.http._sess_auth_user_fp;
        unset req.http._sess_auth_user_verif;
    }

    # PAGES NBI EXLUES DU CACHE
    if (req.url ~ "/(mon-compte|connexion|verification|deconnexion)") {
        return (pass);
    }
    if (req.url ~ "(^/app.php|^/app_dev.php|^)/inscription") {
        return (pass);
    }
    if (req.url ~ "(^/app.php|^/app_dev.php|^)/mon-compte") {
        return (pass);
    }
    if (req.url ~ "(^/app.php|^/app_dev.php|^)/admin") {
        return (pass);
    }
    if (req.url ~ "(^/app.php|^/app_dev.php|^)/(login|logout|login_check)") {
        return (pass);
    }
    if (req.url ~ "(^/app.php|^/app_dev.php|^)/ws/user") {
        return (pass);
    }
    #Cette config permet de passer les bloc esi en mode connecte
    if (req.url ~ "(^/app.php|^/app_dev.php|^)/_fragment?.*login") {
        return (pass);
    }

    #if (!req.url ~ "^/_fragment") {
        #remove req.http.X-Forwarded-For;
        #set req.http.X-Forwarded-For = client.ip;
    #}


    # TRAITEMENT PAR DEFAULT DE VARNISH
    ###################################

    # On ajoute L'ip de client en X-Forwarded
    if (req.restarts == 0) {
        if (req.http.x-forwarded-for) {
          set req.http.X-Forwarded-For =
          req.http.X-Forwarded-For + ", " + client.ip;
        } else {
          set req.http.X-Forwarded-For = client.ip;
        }
    }

    # SI REQUETE HOT HTTP STANDARD, ON PASSE DANS PIPE
    if (req.request != "GET" &&
      req.request != "HEAD" &&
      req.request != "PUT" &&
      req.request != "POST" &&
      req.request != "TRACE" &&
      req.request != "OPTIONS" &&
    req.request != "DELETE") {
        /* Non-RFC2616 or CONNECT which is weird. */
        return (pipe);
    }
    # SI NI GET NI HEAD, ON PASSE LE CACHE VARNISH
    if (req.request != "GET" && req.request != "HEAD") {
        /* We only deal with GET and HEAD by default */
        return (pass);
    }

    # SI AUTHENTIFICATION HTTP OU COOKIE, ON PASSE LE CACHE VARNISH
    #if (req.http.Authorization || req.http.Cookie) {
    #    /* Not cacheable by default */
    #    return (pass);
    #}
    ## ON LAISSE "PASSER" LES COOKIES, ON GERE CELA AVEC LE HASH
    if (req.http.Authorization) {
        return (pass);
    }

    # SI L'UTILISATEUR EST LOGUE, ON PASSE !!!
    #if (req.http._sess_auth_user == "authenticated") {
        #return (pass);
    #}

    return (lookup);
}

################################
# TRAITEMENT DU RETOUR SERVEUR #
################################

sub vcl_fetch {

    std.log( "FETCH TYPE VISITEUR: "  + req.http._sess_user_type);
    std.log( "FETCH TYPE DEVICE: " + req.http._sess_type_device );
    std.log( "FETCH AUTH USER: " + req.http._sess_auth_user );

    if (beresp.http.Surrogate-Control ~ "ESI/1.0") {
        unset beresp.http.Surrogate-Control;
        set beresp.do_esi = true;
    }

    if (beresp.ttl > 0s && beresp.do_esi) {
       set beresp.http.Cache-Control = "s-maxage="+beresp.ttl; 
       unset beresp.http.etag;
       unset beresp.http.last-modified;
       /* Optionally */
       unset beresp.http.expires;
    }

    # SI ON A UN ASSET STATIC, ON SET UN TTL POUR BYPASSER LE COMPORTEMENT PAR DEFAULT, ET ON SUPPRIME LES COOKIES 
    if (req.url ~ "\.(jpg|jpeg|gif|png|ico|css|zip|tgz|gz|rar|bz2|pdf|txt|tar|wav|bmp|rtf|js|flv|swf|html|htm|mov|avi|mp3|mpg)(\?.*|)$") {
        unset beresp.http.set-cookie;
        set beresp.http.X-Cache-Rule = "YES: static files";
        set beresp.ttl = 7d;
        return (deliver);
    }

    # TRAITEMANT DES PAGES D'ERREUR
    if (beresp.status == 404) {
        set beresp.http.X-Cache-Rule = "YES: but for 1m - beresp.status : " + beresp.status;
        set beresp.ttl = 1m;

        return (deliver);
    }
    if (beresp.status == 503 || beresp.status == 500) {
        set beresp.http.X-Cache-Rule = "NOT: beresp.status : " + beresp.status;
        set beresp.ttl = 0s;

        return (hit_for_pass);
    }

    if (req.url ~ "(^/app.php|^/app_dev.php|^)/$") {
        set beresp.ttl = 180 s; 
        set beresp.http.Cache-Control = "public, max-age=30, s_maxage: 180 , must-revalidate";
        set beresp.http.Vary = "Accept-Encoding";
    }
    if (req.url ~ "(^/app.php|^/app_dev.php|^)/") {
        set beresp.ttl = 3600 s; 
        set beresp.http.Cache-Control = "public, max-age=30, s_maxage: 3600 , must-revalidate";
        set beresp.http.Vary = "Accept-Encoding";
    }
    #if (req.url ~ "(^/app.php|^/app_dev.php|^)/_fragment$") {
        #set beresp.ttl = 0 s; 
        #set beresp.http.Cache-Control = "private, max-age=0, s_maxage: 0 , must-revalidate";
        #set beresp.http.Vary = "Accept-Encoding";
    #}

    # Gestion du ttl varnish depuis le bundle liip
    if (beresp.http.X-Reverse-Proxy-TTL) {
        C{
            char *ttl;
            ttl = VRT_GetHdr(sp, HDR_BERESP, "\024X-Reverse-Proxy-TTL:");
            VRT_l_beresp_ttl(sp, atoi(ttl));
        }C
        unset beresp.http.X-Reverse-Proxy-TTL;
    }

    # All tests passed, therefore item is cacheable
    

    # Retirer les éventuels cookies envoyés par le serveur
    # Si le site utilise les cookies (et sessions), le client recevra son cookie sur une autre page...
    # C'est la qu'on exploite a fond varnish !
    # Bon, on laisse passer les set cookies, sinon le site ne fonctionne plus !!!!!
    # unset beresp.http.set-cookie;

    # Si on est en premiere connexion, on ne met pas en cache (pas certain du type de device)
    #if (req.http._sess_not_cache && req.http._sess_not_cache == "dont") {
    #    /*
    #     * Mark as "Hit-For-Pass" for the next 30 seconds
    #     */
    #    set beresp.ttl = 30 s;
    #    set beresp.http.X-Cache-Rule-fv = "VISITE SANS COOKIES";
    #    unset req.http._sess_not_cache;
    #    return (hit_for_pass);
    #}

    # Si on est logué en passe le ttl à 0 Pour ne pas mettre en cache
    if (req.http._sess_auth_user == "authenticated") {
        #set beresp.ttl = 0 s;
        #remove beresp.http.Cache-Control;
        #set beresp.http.Cache-Control = "no-store, private, max-age=1, must-revalidate";
        set beresp.http.X-Cache-Rule-2 = "USER AUTH with ttl: " + beresp.ttl;
        #return (hit_for_pass);
    } else {
        set beresp.http.X-Cache-Rule-2 = "USER ANONYMOUS with ttl: " + beresp.ttl;
    }

    # TRAITEMENT PAR DEFAULT DE VARNISH
    ###################################

    #if (beresp.ttl <= 0s ||
    #    beresp.http.Set-Cookie ||
    #    beresp.http.Vary == "*") {
    #  /*
    #   * Mark as "Hit-For-Pass" for the next 2 minutes
    #   */
    #  set beresp.ttl = 120 s;
    #  return (hit_for_pass);
    #}
    # ON NE "HITFORPASS" QUE SI PAS DE TTL OU PRESENCE D'UN HTTP.VARY
    if (beresp.ttl <= 0s) {
        set beresp.ttl = 120 s;
        return (hit_for_pass);
    } else {
        set beresp.http.X-Cache-Rule = "YES with ttl: " + beresp.ttl;
    }

    # Stocker dans le cache, et envoyer au client
    return (deliver);
}

sub vcl_deliver {

    # ADD CACHE HIT DATA !!! FOR DEBUG !!!
    if (obj.hits > 0) {
        # if hit add hit count
        set resp.http.X-Cache = "HIT";
        set resp.http.X-Cache-Hits = obj.hits;
    } else {
        set resp.http.X-Cache = "MISS";
    }

    # TRAITEMENT PAR DEFAULT DE VARNISH
    ###################################

    return (deliver);
}

#############################3###
# TRAITEMENT DE LA CLE DE CACHE #
########################3########

sub vcl_hash {

    std.log( "HASH TYPE VISITEUR: "  + req.http._sess_user_type);
    std.log( "HASH TYPE DEVICE: " + req.http._sess_type_device );
    std.log( "HASH AUTH USER: " + req.http._sess_auth_user );

    # TRAITEMENT PAR DEFAULT DE VARNISH
    ###################################

    hash_data(req.url);
    if (req.http.host) {
        hash_data(req.http.host);
    } else {
        hash_data(server.ip);
    }
    hash_data(req.http._sess_auth_user);
    hash_data(req.http._sess_type_device);
    hash_data(req.http._sess_user_type );

    return (hash);
}