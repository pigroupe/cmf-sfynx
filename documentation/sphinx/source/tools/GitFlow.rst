.. image:: ../_static/logoAareon.gif

Git Flow
========

1. Context
----------

**1.1. Issues**

    * **Capitalize** on development time throw an efficient workflow model

    * **Locate** asap defects, unconformities specifications and bug risks in order to minimise correction time which is
    proportional with the detection time

    * **Centralise** corrections and bugs in "manufacture" phase, before running qualifications tunnel tests and before
    the project into operation phase

    .. image:: ../_static/gitFlow/issues.png

**1.2. Goals**

    * **Familiarize** development teams with **gitflow workflow**

    * **Understand** issues from such a workflow process

    * **Propose** a technical look at the different challenges ahead looms

**1.3. Synthesis**

    * **Approach on quality factors** in terms of performance, robustness and accuracy

    * **Decreased risks** of expensing overrun on all projects

    * **Technical framing** for teams on more rigorous processes

    We work with 4 kinds of branches :

    * **Master** : production branch, with tags at each release. Instanciated in production

    * **Develop** : branch in charge of the integration server, used directly for small updates

    * **Features/N** : local branches from the developers to add complex functionality.
    The central branch "develop" is merged with the different branches of features

    * **Hotfixe/N** : branch of quick fixes. This branch is based on master, the changes are merged on master and dev

    .. image:: ../_static/gitFlow/branches.png

2. Workflow GitFlow - Functionality
-----------------------------------

    .. image:: ../_static/gitFlow/workflow.png

    The "Workflow Gitflow" section below is inspired by Vincent Driessen text on **nvie**.

    The Workflow Gitflow sets a strict tree model, designed around the targeted version. Although slightly more complex
    than the Workflow Feature Branch, it offers a solid structure for the management of larger projects.

    This work plan doesn't add any new concept or command. Instead, it assigns very specific roles to different branches and
    defines how and why they must interact. Additionally, it uses a system of individual branches to prepare, manage and save
    different versions.

    The Workflow Gitflow always uses a central directory as a communication node for all developers. As the other workflows,
    developers work locally and put their branches on the central directory. The only difference is located into the structure
    of the project tree.

**2.1. Historic branches (develop)**

    Instead of having a single permanent main branch, the workflow uses two branches to record a common project history. The
    main branch (master) stores the history of official versions and the development branch (develop) serves as integrator
    branch for updates. It is also easy  to assign a version number to each commit on the main branch.

    .. image:: ../_static/gitFlow/workflow2.png

    The rest of this workflow is based on the distinction between these two branches.

**2.2. Development branches (features)**

    Each update should have its own branch, which can be placed in the central repository for backup and collaboration purposes.
    But, instead of coming off of the main branch, the feature branches use the development branches as the origin. When a
    change is completed, it is attached to the development branch. Changes should never interact directly with the main branch.

    .. image:: ../_static/gitFlow/workflow3.png

    Please note that the functionality of branches (features) combined with the development branch (develop) match the Workflow
    Feature branch. But the Workflow Gitflow doesn't stop at this point.

    Current agreements :
    * detach a branch : develop
    * merge with : stable branch
    * naming convention feature /*

**2.3. Delivery branches (releases)**

    When the development branch has acquired sufficient functionality for its release / delivery (or the date predetermined
    output approach), you can detach a branch version from the development branch.

    Create this branch launches new versioning cycle, in order to not add any new feature after this point: only reviews,
    documentation and other activities aiming at the release of this version should be placed on this branch.

    Once ready to be launched, it performs a merge in the stable branch and given a version number (feature). Moreover, it
    should also join the development branch, which may has evolved since the creation of the branch delivery.

    .. image:: ../_static/gitFlow/delivery_branches.png

    Using a specific branch to prepare the delivery of a version allows a team to work on improving the current version,
    while another people continue to evolve the next version. This also helps create well-defined phases of development
    (it is easy example to say "this week we will work on version 4.0" and see it in the directory structure).

    Current agreements :
    * detach a branch : develop
    * merge with : stable branch
    * naming convention release /*

**2.4. Maintenance branches (hotfix)**

    .. image:: ../_static/gitFlow/hotfix.png

    Maintenance branches, or "hotfix" are used for minor changes in versions. This is the only branch that should come off
    directly from the stable branch. As soon as a fix is ready, the branch should be merged into the stable branch and
    the development branch (or current version), and the version of the stable branch should be incremented with an
    updated version number.

    Dedicating a development line for corrections allows your team to solve problems without needing to interrupt the
    left of the operations or wait until the release of the next version. Maintenance branches can be considered as
    ad hoc version of branches directly related to the stable branch.

    Current agreements:
    * detach a branch: master
    * merge with: stable branch
    * naming convention: hotfix / *

3. Example
----------

    The below example shows how workflow can be used for a single release cycle. We will assume that you have already created
    a central repository.

**3.1. Create a development branch**

    .. image:: ../_static/gitFlow/ex_create_dev_branch.png

    The first step is to complement the default stable branch with a development branch.
    A simple way to go is to ask a developer to *create locally an empty branch* and place it on the server:

    .. code-block:: bash

        git branch develop
        git push -u origin develop

    This branch will contain the complete history of the project while the stable branch will contain an abbreviated
    version. Other developers should then *reproduce the central directory* and create a tracking branch for development :

    .. code-block:: bash

        git clone ssh://user@host/path/to/repo.git
        git checkout -b develop origin/develop

    So, each developer has a local copy of the implementation of the historic branches.

**3.2. Mary and John begin two new features**

    .. image:: ../_static/gitFlow/ex_2_new_features.png

    Our example begins with Mary and John deciding to work on two new features.
    To begin development, they both need to branch to their respective functionality. Instead of relying on master,
    they use the Develop branch:

    1 - Creation and connexion to the feature branch

    .. code-block:: bash

        git checkout -b feature/<NUM-TICKET-OF-TASK>

    2 - Adding commits to the "feature" branch, in the same way

    .. code-block:: bash

        git add -i

        * 2 : update files
            1,3-5 : files 1,3,4,5
            '*' : all files

        * 4 : add new files
            same thing ...

    3 - Commiting updates

    .. code-block:: bash

        git commit -m " REF : #<NUM-TICKET-OF-TASK> : <message>"

    4 - Purge commits before proposing

    .. code-block:: bash

        git rebase -i HEAD~1

    Then push ":x" for saving.

    5 - Update local feature branch taking in charge updates from develop branch

    .. code-block:: bash

        git pull origin develop
        git rebase develop

    6 - Update locale feature branch, then feature remote branch

    .. code-block:: bash

        git fetch
        git rebase origin feature/<NUM-TICKET-OF-TASK>
        git push –u origin feature/<NUM-TICKET-OF-TASK>

**3.3. Mary finishes her feature**

    .. image:: ../_static/gitFlow/ex_mary_finish_feature.png

    After adding a few commits, Mary decides that its functionality is ready. If his team uses the pull requests,
    she should open one asking the merge of its functionality in the Develop branch.
    Otherwise, she can do its merge in its branch local develop, and make a push for it in the deposit center, as follow :

    1 - Update locale Develop branch

    .. code-block:: bash

        git pull origin develop
        git checkout develop

    2 - Update remote Develop branch with her feature

    .. code-block:: bash

        git merge feature/<NUM-TICKET-OF-TASK> --no-off
        git push –u origin develop
        git branch –d feature/<NUM-TICKET-OF-TASK>

    --no-ff to appear in the connection history (since the rebase to Develop on feature is a continuous history).

    The first command verifies that the branch Develop is updated before trying to merge the functionality.
    Note that you should always avoid making a merge feature directly into master.
    We can resolve conflicts as in the centralized workflow.

    **CAUTION : this part is performed only by Mary if she is Lead developer with merge requests rights on the branch Develop. (See Part Code Review)**

    .. image:: ../_static/gitFlow/ex_end_feature.png

**3.4. Mary begins to prepare her delivery**

    .. image:: ../_static/gitFlow/ex_begin_delivery.png

    While John continues to work on its functionality, Mary begins to prepare the first release / official delivery of
    the project. As for the development of a feature, it uses a new branch to encapsulate the preparation of delivery.
    This step is also the one where the version number is established:

    .. code-block:: bash

        git checkout -b release/vX.Y.Z develop

    This branch is the place where we will clean delivery, test everything, will update the documentation, and will all
    other preparations for future deliveries. It's like a feature branch that serves only to tweak a delivery.

    As soon as Mary creates this branch and makes a push to the central repository, the delivery is frozen.
    Any absent functionality Develop branch is delayed until the next delivery cycle.

**3.5. Mary finishes her release**

    .. image:: ../_static/gitFlow/ex_finish_release.png

    Once the delivery is ready to be published, Mary does a merge into Master and Develop, and then removes the delivery
    branch. We have to do a merge in Develop, cause they may be some critical updates added to the delivery branch, and
    that they must be accessible to the new features. Again, if the organization focuses on the revision of the code,
    it would be the perfect place for a pull request :

    .. code-block:: bash

        git checkout master
        git merge release/vX.Y.Z
        git push
        git checkout develop
        git merge release/vX.Y.Z
        git push
        git branch -d release/vX.Y.Z

    Delivery branches serve as a buffer between the development features (Develop) and deliveries available to the
    public (master). When you did a merge into master, you have to put a tag on the commit, to find it easily in the future :

    .. code-block:: bash

        git tag -a X.Y.Z -m "Première release publique" master
        git push --tags

    Git comes with more hooks, which are scripts that run when a particular event occurs in a repository. You can set a
    hook so that it automatically makes the build of a public release each time we made a push to the master branch in
    the central repository, or during the push of a tag.

**3.6. A user discovers a bug**

    .. image:: ../_static/gitFlow/discover_bug.png

    After delivery, Mary returns to the development of features for the next delivery with John ... until a user opens
    a ticket to report a bug in the current version.
    To fix this bug, Mary (or John) creates a maintenance branch from master, correct the problem in as many commits as
    needed and then made the merge directly into master :

    .. code-block:: bash

        git checkout -b hotfix/<NUM-TICKET-OF-TASK> master
        # On corrige le bug
        git checkout master
        git merge hotfix/<NUM-TICKET-OF-TASK>
        git push

    As the Delivery branches, Maintenance branches contain important updates to include in the branch develop, it is
    necessary that Mary also makes this merge. Then she can totally remove the branch :

    .. code-block:: bash

        git checkout develop
        git merge hotfix/<NUM-TICKET-OF-TASK>
        git push
        git branch -d hotfix/<NUM-TICKET-OF-TASK>

    .. image:: ../_static/gitFlow/remove_maitnenance_branch.png

4. Merge request policy
-----------------------

**4.1. Environment**

    * SBE (Site Building Environment)     => linked to branch Develop

    * ET (Environment of Testing)         => linked to branch Release

    * UAT (User Acceptance Testing)       => linked to branch Master

    * Pre-production                      => linked to branch Master

    * ….

    * Production                          => linked to branch Master

**4.2. Actors**

    * Developer       => right to create a new branch, no possibility of committer on Develop / Release / Master

    * Release Manager => Right to emerge in Dev / Release / Master

    * Project Manager => right visualization (guest)

**4.3. Read rights**

        * For each feature a new branch is created

        * This copy is writable. It is this that the developer uses

        * Once the work is finished, ask on a Aareon GitLab a "Merge Request"

        * The release manager receives a "Merge request"

5. Code review
--------------

**5.1. Merge request workflow**

    The strategy for each new "merge request" in order to updating the remote Develop branch with a complete feature:

    1. The developer requested a "merge request" that he assigns to himself

    2. In the "discussion" tab, add a "1" in the "Write" sub-tab and confirm on "Add Comment"

    3. Then assign the "merge Request" to another developer who will be responsible for making a "+1" to validate its code review, or "comment" to stop it. This one will assign the "merge request" to another developer, etc ....

    4. And so on, until all the developers have given his opinion on code review. A storyteller appears at the top right of the "discussion" tab giving the number of positive opinion on code review

    5. The developer will be the last to validate or not the code review (He can use tools like Gerrit to power will develop a workflow with custom rules)

    .. image:: ../_static/gitFlow/merge_workflow.png

**5.2. Tools**

    * Snowshoe : « merge requests » dashboard (display merge resuest state only)

    * Gerrit : code review on « merge requests » with possibility of making workflow rules

    * Gitk et Gitg : manage commits history

    .. code-block:: bash

        Apt-get install gitk
        Apt-get install gitg

    * GitKraken

6. Useful links
---------------

    * `<http://nvie.com/posts/a-successful-git-branching-model/>`_

    * `<https://fr.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow>`_

    * `<http://danielkummer.github.io/git-flow-cheatsheet/>`_

7. Conclusion
-------------

**7.1. General observation**

    * The used git workflow, more commonly known as "git-flow" has been optimized and simplified via the git-flow tool that can be installed easily (apt-get install git-flow)

    * We recommend not to use this tool to provide developers with an essential and primordial GIT expertise to ensure the sustainability of the progress of a project

    * An awareness-raising work on the importance of "code reviews" is needed very clear if we want to bring value-added to such a process GITflow

**7.2. Alert**

    * It is important that the Lead developers impose strictly applying this approach to developers

    * It is important for the Lead developers to be organized in order to best optimize the management of "merge requests",
    mainly using controls tools.