.. image:: ../_static/logoAareon.gif
    :align: center

====
GIT
====

.. toctree::

    Retrieve the current project :

.. code-block:: bash

    $ git clone current_project

By default, there are two branchs master and develop. So go to develop :

.. code-block:: bash

    $ git checkout develop

Each new development must have his own branch for only one user story.
Each new branch must have a particular name beginning with **features/NumberOfTask-NameOfTask**

.. code-block:: bash

    $ git checkout -b features/randomNumber-create-new-branch

On each branch : **One functionality = One commit**

.. attention:: Be Careful :
        The golden rule of rebase
            ``No one shall rebase a shared branch``


        **Never, NEVER, NEVER** rebase a shared branch
        By shared branch I mean a branch that exists on the distant

        For each commit, first you have to fetch [...] rebase your branch :

.. code-block:: bash

    $ git add files
$ git commit -m "#NumberTask msg" [#n5]_


then

.. code-block:: bash

    $ git fetch

**if nothing**

.. code-block:: bash

    $ git push origin your-branch

else

.. code-block:: bash

    $ git rebase origin branchByDefault (usually develop)
(fix all conflicts)
and

.. code-block:: bash

    $ git push origin your-branch


* Go on Gitlab, create a MergeRequest

* Affect it to an other developer

.. note:: * To validate a MR :
        * it's must have at least 3 '+1'
            * Read back the code
            * Inform about developments
            * Learn from others news developments skills
            * Globally vision of the project

**Same way for Develop to Master**
    MR develop -> master

    no need +1 but with a looooong message with all new functionnalities added into master
    Be careful don't delete develop !!!

**Same way for Master to Prod**
    Create a TAG of the production version for each MR Master to Prod

.. [#n5] msg = just few words about the functionnality
.. .. [#n5] msg = just few words about the functionnality