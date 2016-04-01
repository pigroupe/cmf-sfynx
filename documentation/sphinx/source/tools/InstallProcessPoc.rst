============================
Installation process for POC
============================
.. image:: _static/logoAareon.gif
    :align: center

Installation process
====================

.. toctree::
   :caption: Table of Contents
    Install docker
    Install docker-compose
    Install Git
    Clonage

.. admonition:: Prerequisites

   Follow the default installation docker until the Install step n°3
`https://docs.docker.com/engine/installation/linux/ubuntulinux/ <https://docs.docker.com/engine/installation/linux/ubuntulinux/>`_

Install docker
--------------

.. code-block:: bash

    $ sudo wget -O /usr/bin/docker "https://get.docker.com/builds/Linux/x86_64/docker-1.9.1" && sudo chmod +x /usr/bin/docker


Add the current user to the group docker

.. code-block:: bash

    $ sudo usermod -aG docker {username}

Restart the VM

.. code-block:: bash

    $ sudo service docker restart

Check with the command docker -v

Install docker-compose
----------------------

.. code-block:: bash

    $ curl -L https://github.com/docker/compose/releases/download/1.5.2/docker-compose-`uname -s`-`uname -m` > /tmp/docker-compose

    $ sudo mv /tmp/docker-compose /usr/local/bin/docker-compose

    $ sudo chmod +x /usr/local/bin/docker-compose

Check with the command  docker-compose -v

Install Git
-----------

Installation

.. code-block:: bash

    $ sudo apt-get install git

Configuration

.. code-block:: bash

    $ git config --global user.email « vous@example.com »
    $ git config --global user.name « votre nom d'utilisateur »


Check if Apache running on the machine

.. code-block:: bash

    $ sudo service apache2 status
	$ sudo service apache2 stop
If yes
Delete the execution at the start of your machine :

.. code-block:: bash

    $ sudo update-rc.d -f apache2 remove (éviter les problèmes avec les différents dockers).

Clonage
-------

.. code-block:: bash

    Docker4Dev

@gitlabAareon : Cloner le projet Docker4Dev dans votre répertoire

Aller dans le dossier docker4Dev :
        lancer la commande : make install

