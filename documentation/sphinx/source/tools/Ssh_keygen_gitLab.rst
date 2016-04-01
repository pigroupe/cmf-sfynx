.. image:: ../_static/logoAareon.gif
    :align: center

SSH keygen for GITLAB
=====================

.. toctree::
    :hidden:

    Firstly, in your terminal,  you have to configure your user informations on git

.. code-block:: bash

    $ git config --global user.name "John Doe"
    $ git config --global user.email johndoe@example.com

Then, you have to generate your ssh public key

.. code-block:: bash

    $ cd ~/.ssh
    $ ls
        authorized_keys2  id_dsa       known_hosts
        config            id_dsa.pub

    $ ssh-keygen -t rsa -b 2048 -C "$your_email"
    $ cat ~/.ssh/id_rsa.pub

To finish, put your key on gitlab (Profiles settings/SSH Keys)

You can work !


Documentation

    http://doc.gitlab.com/ce/ssh/README.html

    https://git-scm.com/book/fr/v1/Git-sur-le-serveur-G%C3%A9n%C3%A9ration-des-cl%C3%A9s-publiques-SSH