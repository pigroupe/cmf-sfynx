Technical rules for PHP developers
==================================

1. Description
--------------

This document is intended primarily for PHP developers. It may be helpful for leaders of technical projects, quality managers and test managers associated with PHP projects.
The purpose of this document is to provide guidelines and standards for the production of PHP projects. This document covers in particular:

    * naming conventions;
    * procedures and review tools;
    * procedures and tools for automated testing;
    * procedures and tools for code quality metrics;
    * reference technologies to use.

This document does not cover aspects of choice of language (when to use Java / PHP ...?). However, it should be noted quickly that PHP is different (in the more conventional architectures) Java by the lack of memory persistence of processed data between requests and the low use of threads. This causes PHP to be more resistant to minor breakdowns and interference month suitable for projects that require many asynchronous behavior (or real time), but more suitable for remote time in projects that do not require strong interactions with other bricks of the information system.

2. Technical rules for the implementation
-----------------------------------------

This section is for writing the PHP source code, including:

    * naming classes, variables and functions;
    * drafting comments;
    * fault management;
    * etc.

About naming conventions, the majority of the recommendations follow the Standard PHP Recommendations.

2.1. Code conventions
`````````````````````

**Project tree**

.. warning:: It is MANDATORY to respect the kind tree

Projects except specific constraints, must respect at minimum the following tree :

    .. code-block:: bash

        ├── bin/
        │    ├── install.sh
        │    └── test.sh
        ├── build/
        ├── config/
        ├── src/
        ├── testing/
        ├── vendor/
        ├── www/
        ├── Makefile
        └── README.md

    +------------------+-----------------------------------------------------------------------------------------------+
    | Folder           | Description                                                                                   |
    |                  |                                                                                               |
    +==================+===============================================================================================+
    | ./               | You can find the root of the declarative files project. For example, the project root may     |
    |                  | contain README.md files composer.json, Makefile ...                                           |
    +------------------+-----------------------------------------------------------------------------------------------+
    | ./bin            | Contains binaries used in the project. Attention binary that can be generated (using Composer,|
    |                  | for example) should be placed in a folder ./vendor/bin.                                       |
    +------------------+-----------------------------------------------------------------------------------------------+
    | ./build          | This file should NOT be versioned. It contains the results of code analysis reports automated |
    |                  | tests.                                                                                        |
    +------------------+-----------------------------------------------------------------------------------------------+
    | ./config         | Contains all the configuration files (deployment environment variables ...).                  |
    +------------------+-----------------------------------------------------------------------------------------------+
    | ./src            | The source files must be placed only in the ./src folder (and subfolders).                    |
    +------------------+-----------------------------------------------------------------------------------------------+
    | ./vendor         | This file should NOT be versioned. Contains dependencies (libraries) outside the project.     |
    |                  | This folder can be managed manually or using Composer.                                        |
    +------------------+-----------------------------------------------------------------------------------------------+
    | ./www            | Contains all the public resources (images, JavaScript, CSS, fonts ...).                       |
    |                  | This folder also contains a front controller file (index.php or app.php) that will redirect   |
    |                  | all HTTP requests to the source code in question.                                             |
    +------------------+-----------------------------------------------------------------------------------------------+

    You can find the root of the declarative files project. For example, the project root may contain README.md files composer.json, Makefile ...


**Naming : filename and classname**

.. toctree::
* :doc:`PSR4`

.. warning:: It is MANDATORY to respect the PSR0 / PSR4 (auto-loading of classes)

The term "class" refers to classes, interfaces, features and other similar structures.
To facilitate interoperability and auto-loading of classes, it is imperative to observe the following conventions :

    * classes and fully qualified namespaces must have the following structure \ <Vendor Name> \ (<Namespace> \) * <Class Name>;
    * each namespace must have a root namespace. ( "Vendor Name");
    * each namespace can have as many sub-namespaces as they wish;
    * each separator of a namespace is converted as DIRECTORY_SEPARATOR loading from the file system;
    * each "_" in the name of a CLASS is converted as DIRECTORY_SEPARATOR. The character "_" has no special meaning in a namespace;
    * classes and fully qualified namespaces are suffixed with ".php" when loading from the file system;
    * alphabetic characters in the vendors names, class names and namespaces can contain any combination of lowercase and uppercase.

For example :

.. list-table::
    :widths: 40 60
    :header-rows: 1

    * - Class
      - File
    * - \Doctrine\Common\IsolatedClassLoader
      - ./lib/vendor/Doctrine/Common/IsolatedClassLoader.php
    * - \Symfony\Core\Request
      - ./lib/vendor/Symfony/Core/Request.php
    * - \Zend\Acl
      - ./lib/vendor/Zend/Acl.php

**Naming : code standards**

.. toctree::
* :doc:`PSR2`

.. warning:: It is MANDATORY to respect PSR1 / PSR2 (style of writing code)

* Files should only use tags <? Php and <? =.
* Files should never end with a closing tag (?>)
* PHP code files must be encoded only in UTF-8 without BOM.
* Class names should be declared as StudlyCaps (or "Upper Camel Case").
* The class constants must be declared in capital letters with an underscore (_) separators.
* The names of methods must be declared as camelCase.
* The code must use 4 spaces for indentation, and no tabs.
* The lines should contain 80 characters or less perfectly.
Management braces, spaces, etc. is still a matter of debate. However, it is proposed to respect the PSR-2, to enjoy control tools and correction of compliance:
* Opening braces for classes must be on the next line, the closing braces should appear on the next line after the body of the class.
* Opening braces for methods must be on the next line, the closing braces should appear on the next line after the body of the method.
* The visibility must be declared on all properties and methods; abstract and must be declared before final visibility; static must be declared after the visibility.
* The structure of keywords control must have a space after them, methods and function calls should not be.
* Opening braces for control structures must be on the same line, and closing braces should appear on the next line after the body.
* the opening parenthesis to the control structures should not contain spaces after them, closing parentheses for control structures should not contain spaces before.

For example :

    .. code-block:: php

            	<?php
    	namespace Vendor\Package;

    	use FooInterface;
    	use BarClass as Bar;
    	use OtherVendor\OtherPackage\BazClass;

    	class Foo extends Bar implements FooInterface
    	{
    	    public function sampleFunction($a, $b = null)
    	    {
    	        if ($a === $b) {
    	            bar();
    	        } elseif ($a > $b) {
    	            $foo->bar($arg1);
    	        } else {
    	            BazClass::bar($arg2, $arg3);
    	        }
    	    }

    	    final public static function bar()
    	    {
    	    }
    	}

**Comments and documentation**

There are three types of documentation:

* Comments in the code

    Comments should be between 30 and 45 characters. Specifically, it is about finding a balance between excessive documentation and self-documenting code.
    There is no need to introduce comments for simple codes (getters, setters) and explicit. However, each algorithm must be qualified according to its complexity.

* PHPDoc (block comments)

    .. warning:: It is MANDATORY to use the documentation PHPDoc

    Block comments should respect the agreement "PHPDoc".
    A block comment starts with / ** and ends with * /. For example :

    .. code-block:: php

       	<?php
            /**
             * This is a DocBlock.
             */
            function associatedFunction()
            {
            }

    The following tags must be defined:

    In the file header:
    * @package <name>: main namespace
    * @deprecated [<text>] shows that the element is deprecated and shouldn't be used. The text can be used to indicate the alternative to use.
    * @author <author> <email>: author of the code. The tag can be duplicated.
    * @license <license>: File license

    For functions and methods:
    * @param <type> <$ var> [<text>]: indicates that the parameter $ var function is the specified type. The tag can be duplicated.
    * @return <type>: type of the return value (if several possible types, separated by a pipe "|").
    * @throws <type>: indicates that the code may throw an exception type <type>

    It is also possible to link the elements and documentation among themselves:
    * @see <element> [<text>] gives a reference to another item
    * @uses <element> [<text>] states that the function uses this element
    * @link <url> [<text>] indicates a reference to an external resource

* Technical documentation (readme files, etc.).

    .. warning:: It is MANDATORY to write technical documentation in rst

    Technical documents 'unofficial', that is to say, documents for developers only, must be:
    * accessible from the source code of the project;
    * playable on a server;
    * ideally written in .rst;

    Furthermore, each project must contain a README or README.md file at the root of its source code, stating:
    * a brief description of the project;
    * the project's license;
    * the lead developers;
    * the installation process for a newcomer;
    * any significant remarks for the understanding of the project.

**Manage mistakes and exceptions**

    * Display errors

        .. warning:: It is MANDATORY to develop by displaying all levels of error

        The developer's workstation must configure PHP so that it displays correctly errors. Php.ini file should contain:

        .. code-block:: php

            error_reporting = E_ALL | E_WARNING
            log_errors = On
            display_errors = Off
            html_errors = On

    * Intercept errors

        .. warning:: It is FORBIDDEN to use a mechanism other than the exceptions to handle errors

        It is forbidden to handle an anomaly with a die () or exit ().
        It is also forbidden to return a true or false value for a function that executes processing. The developer must assume that the behavior was performed correctly unless an exception was thrown.
        Errors management should therefore only be using exceptions.
        It should choose the best type of exception, among the native PHP exceptions or creating its own exception class.

        Here is the list of standard exceptions PHP (excluding extensions):

            * LogicException (inherited from Exception): business exceptions
            * BadFunctionCallException
            * BadMethodCallException
            * DomainException
            * InvalidArgumentException
            * LengthException
            * OutOfRangeException
            * RuntimeException (inherited from Exception) systems exceptions
            * OutOfBoundsException
            * OverflowException
            * RangeException
            * UnderflowException
            * UnexpectedValueException

        All instruction block that uses functions or methods which could throw an exception must always be wrapped in a try ... catch (and possibly a finally if the PHP version used is higher than or equal to 5.5)
        For example :

        .. code-block:: php

            try {
               $object->foo();

            } catch(\LogicException $e) {
               // (...) gestion des logs, des transactions...
               throw $e;
            }

    * Debug

        There are 2 large debugging strategies:

        * VDD, or "var_dump Driven Development"

            The first solution is to display, occasionally or permanently (for developers only) information. This technique, slow and expensive, must be avoided as much as possible, even if its use is acceptable.

        * Step-by-step

            .. warning:: It is RECOMMENDED to debug step-by-step

            The second solution is to stop the execution of the source code at any given time and to inspect / change the values of one or more variables. This is called "step by step". This solution should be preferred as possible.
            There is only one effective in PHP and Open Source tool for step-by-step: Xdebug. This tool is available on Windows (as an extension or a WampServer module), and Unix.
            Xdebug articulates directly with all modern IDE (Netbeans, PhpStom ...).

            .. note:: See `<https://xdebug.org/>`_

    * Profiler

        .. warning:: It is RECOMMENDED to use an applicative profiler

        The applicative profiler consists in analyze the performance of a code to detect bottlenecks.
        There are mainly two profiling tools in PHP:
            * xhprof
            * Xdebug

        Due to the difficulty of access to the Internet, developers are encouraged to use Xdebug.
        To enable profiling, edit the php.ini file as below :

        .. code-block:: ini

            xdebug.profiler_enable=1
            xdebug.profiler_output_dir="C:\(...)\tmp"

        Then restart Apache and PHP services. All scripts are now drawn.
        To play these tracks, you need kcachegrind. In kcachegrind, open the trace file that interests you. You have an interface similar to the following illustration :

        The left menu lists the functions performed by your script, sorted by execution time:
            * self : time spent in the function (without children functions);
            * called : number of times the function was called;
            * function: the function name.

        The right side displays (among others):
            * the call tree of the selected function;
            * details of the selected function.

        It is therefore imperative to control regularly that there is no neck control functions (called nits and a very large number of times).

    * Versioning

        .. warning:: It is MANDATORY to version source code

        * Trunk and Branches

            It is imperative to create the root of the deposit the following folders :
                * trunk : contains the latest version of the code
                * branches : contains copies of the code related to features (or bug fixes) specific
                * tags contain code marked at a given time (tag)

            The trunk is considered active branch by default.

        * External libraries

            .. warning:: It is FORBIDDEN to version External Libraries

            If Internet access is possible and shaved on the project, the dependencies should not be versioned, but must be installed by a dependency manager (Composer, Bower ...).
            In all other cases, the dependencies must be versioned in the project in a "vendor" folder.

    * Commit

        .. warning::  It is MANDATORY to provide a commit description

        Each commit must always be accompanied by a commit message:

        .. code-block:: sh

            svn commit -m "Description of the commit"

        If the commit may be associated with a bug tracker of the ticket, the description of the commit should contain a number sign (#) followed by the ticket number.

        .. code-block:: sh

            svn commit -m "# 123 Description commit"

        If the commit fixes a bug tracker of the ticket, it is possible to associate the keyword 'fixed' to ticket number:

        .. code-block:: sh

            svn commit -m "Fixed # 123 Description commit"

    * Branches and strategy

        .. warning::  It is MANDATORY to respect the versioning strategy advocated

        There are three branches strategies. You should choose the appropriate strategy for the type of project:

        * No branch

            * The developers how their work day-to-day trunk
            * Occasionally, the trunk contains invalid code

            .. list-table::
                :widths: 50 50
                :header-rows: 1

                * - Advantages
                  - Disadvantages
                * - Easy to implement
                  - The code can be unstable at any time

        * systematic branch

            * Developers create a private branch for all their tasks
            * When the task is complete, someone (the developer, the release manager ...) inspects private branch and merges into the trunk

            .. list-table::
                :widths: 50 50
                :header-rows: 1

                * - Advantages
                  - Disadvantages
                * - Trunk is extremely stable
                  - Much merge to manage / The branches may be insulated from each other / High risk of conflict

        * Needed branch

            * Developers commitment every day on trunk.
            * Three following rules must be observed:
            * Rule # 1: the trunk code must pass unit tests and be stable
            * Rule # 2: a commit must be small enough to be read easily
            * rule # 3: if the rules # 2 or # 3 can not be met (eg if it is impossible to create small commits without the trunk is stable) it is necessary to branch. This branch will be merge after control (by the developer, the release master ...)

            .. list-table::
                :widths: 50 50
                :header-rows: 1

                * - Advantages
                  - Disadvantages
                * - Trunk is extremely stable / The process of merge / Conflicts are quite rare
                  - The developer must imperatively run automated tests before each commit

    * Tags and strategy

        Tags can capture the image of a code at a specific time, and then associate a label to this image.
        Tags are usually created by the continuous integration platform (eg Jenkins).
        Although it is possible to build rich tag strategies (eg semantic versioning), the strategy is simply:
        * increment a build counter (eg "build-123" means it is the 123rd build);
        * add a suffix (dev, -rc, -stable) as the deployment level that the build has reached (eg "build-123-dev" means that the build 123 has been deployed to the development server).

    * Moreover

        .. warning::  It is MANDATORY to use a LTS version of PHP (supporting version)

        .. warning::  It is MANDATORY to use a framework (Symfony > 2.8 for Aareon)

    * Anomaly

        .. warning::  It is MANDATORY to identify anomalies

        Each anomaly (or default) must be listed and classified in GitLab, even if transmitted further via another channel (email, oral, ...).

        * Typology of anomalies

            The anomalies are classified according to the following nomenclature:

            .. list-table::
                :widths: 30 70
                :header-rows: 1

                * - Type
                  - Description
                * - Simple
                  - The story is complete, but the implementation is poor
                * - Incomplete story
                  - The implementation is good, but the story is incomplete
                * - Invalid story
                  - The implementation is good, but the story has forgotten a key element
                * - Modified story
                  - The implementation and the story is good, but the needs have changed
                * - Legacy bug
                  - No specified specifications exist

        * Prioritization

            Two tests suggest a bug :

                * Scope: how many users are affected ?
                * severity: how the problem is it critical ?

        * Scope

            .. list-table::
                :widths: 30 70
                :header-rows: 1

                * - Value
                  - Affected
                * - 5
                  - All users / features
                * - 4
                  - Much of users / features
                * - 3
                  - Moderate part of users / features
                * - 2
                  - A little part of users / features
                * - 1
                  - A tiny part of users / features

        * Severity

            .. list-table::
                :widths: 30 70
                :header-rows: 1

                * - Value
                  - Affected
                * - 5
                  - Data is lost, corrupted, or the system is unavailable
                * - 4
                  - Important features are available without workaround
                * - 3
                  - Important features are available but with reasonable workarounds
                * - 2
                  - Secondary features are available but with reasonable workarounds
                * - 1
                  - Decorative elements are available, but with reasonable workarounds

        * Priority calculation

            .. image:: ../_static/gitFlow/priority_calculation.png

            * 25: Critical
            Must be corrected immediately.
            A manager should be notified. Revival: daily

            * 15-20: Serious.
            Must be corrected in the next sprint. A senior manager should be notified.
            Stimulus: every week

            * 6-12: Moderate:
            Must be corrected in the next 2-3 sprints

            * 1-5: Low.
            Will be planned when there is time.

            The project leaders and managers must ensure that each anomaly has a severity and scope of the note to prioritize correctly.

        * Code quality

            Code quality is important, and not a subjective criterion. There are software quality standards (ISO 9126, ISO 25000 and following, ISTQB ...). Failing to comply to the letter, it is possible to be inspired. This is the subject of this document.

            .. warning:: it is MANDATORY to use the rules of the Directive PHPMD

