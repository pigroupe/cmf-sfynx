POC on Docker (Windows)
=======================

1. Introduction
---------------

POC consists in create Dockers containers on a Windows environment en to make a redirection for being accessible on local network.

    .. warning:: Under NICOLAS GIRAUD supervision

2. Conditions used
------------------

    * Windows 8.1 64 bits edition
    * Docker 1.10.3
    * Nginx image basic template from store

3. Tests
--------

**Easy way : make Docker containers connected by bridged mode**

    The easiest way to connect a Docker container through the local network is to connect it by bridge.
    In this way, the second adapter has to be connected on "Bridged adapter" mode in order to be recognized by local DHCP.

    .. note:: see `<https://forums.docker.com/t/how-to-access-docker-container-from-another-machine-on-local-network/4737/2>`_

    However, example was given for a MAC machine. It seems that this kind of Networking adapter won't work for virtual Machine on a Windows environment.
    Indeed, when the user tried to set it property launching Kitematic will reset automatically Docker VM properties.

    An other solution had been to force launching Docker VM with custom properties and to start containers. Nut it seems that containers cannot be launched this way.

**Windows way : make Windows redirection**

    Despite the fact that we are on Windows (desktop edition) machines, the other solution had been to redirect HOST ip to Docker VM ip through the good port.

    In this way, we used interface redirection (launched from PowerShell) in order to redirect HOST ip :

    .. code:: sh

        netsh interface portproxy add v4tov4 listenport=80 listenaddress=[HOST ip] connectport=[container port] connectaddress=[Dokcer VM ip]

    Problem : HOST isn't reachable by any other machine on local network (logique ...)

4. Conclusion
-------------

So, following the different forums and explications, it's clearly possible to redirect on a docker container through a bridge on a Linux OS like. Windows (even Server editions) are a little bit hard (maybe impossible) to be used like this.

5. Status
---------

POC closed and definitively rejected.