.. image:: ../_static/logoAareon.gif

Using Sphinx
============

**Link :**

`<http://www.sitepoint.com/using­sphinx­for­php­project­documentation/>`_

`<http://symfony.com/doc/current/contributing/documentation/format.html>`_

`<https://pythonhosted.org/sphinxcontrib­phpdomain/>`_


**ReStructuredText Reference**

`<http://docutils.sourceforge.net/docs/user/rst/quickref.html>`_

`<http://python.physique.free.fr/aide/index.html>`_


**Extensions to PHP**

`<https://github.com/varspool/sphpdox>`_

`<https://github.com/fabpot/sphinx­php>`_


Sphinx
------

Sphinx is a build system that provides tools to create documentation from reStructuredText
documents. As such, it adds new directives and interpreted text roles to the standard reST
markup.


Install Sphinx
--------------

sudo apt­get install python­sphinx python­setuptools
sudo easy_install pip
sudo pip install sphinxcontrib­phpdomain
cd /path/where/documentation/project/lives
sphinx­quickstart

Here is a list of the default used in this project

+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| Prompt                                                                      | Choice                                                       |
+=============================================================================+==============================================================+
| > Root path for the documentation [.]:                                      | <ENTER>                                                      |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > Separate source and build directories (y/n) [n]:                          | y                                                            |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > Name prefix for templates and static dir [_]:                             | <ENTER>                                                      |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > Project name:                                                             | symfony_dem                                                  |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > Author name(s):                                                           | Aareon                                                       |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > Project version:                                                          | 0.0.1                                                        |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > Project release [0.0.1]:                                                  | <ENTER>                                                      |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > Source file suffix [.rst]:                                                | <ENTER>                                                      |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > Name of your master document (without suffix) [index]:                    | <ENTER>                                                      |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > Do you want to use the epub builder (y/n) [n]:                            | <ENTER>                                                      |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > autodoc: automatically insert docstrings from modules (y/n) [n]: y        | y                                                            |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > doctest: automatically test code snippets in doctest blocks (y/n) [n]:    | n                                                            |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > intersphinx: link between Sphinx documentation of different projects      | y                                                            |
| (y/n) [n]: y                                                                |                                                              |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > todo: write "todo" entries that can be shown or hidden on build (y/n)     | <ENTER>                                                      |
| [n]:                                                                        |                                                              |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > coverage: checks for documentation coverage (y/n) [n]:                    | <ENTER>                                                      |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > pngmath: include math, rendered as PNG images (y/n) [n]:                  | <ENTER>                                                      |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > mathjax: include math, rendered in the browser by MathJax (y/n) [n]:      | <ENTER>                                                      |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > ifconfig: conditional inclusion of content based on config values (y/n)   | <ENTER>                                                      |
| [n]: y                                                                      |                                                              |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > viewcode: include links to the source code of documented Python           | <ENTER>                                                      |
| objects (y/n) [n]:                                                          |                                                              |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > Create Makefile? (y/n) [y]:                                               | <ENTER>                                                      |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+
| > Create Windows command file? (y/n) [y]: n                                 | n                                                            |
+-----------------------------------------------------------------------------+--------------------------------------------------------------+

EN : After answering some questions about your project  you’ll find a directory structure with
an automatically generated conf.py. You’ll need to make a couple of changes to it as follows:

FR : Après avoir répondu à quelques questions sur votre projet, vous trouverez une
structure de répertoire avec un conf.py. généré automatiquement Vous aurez besoin de faire
quelques changements comme suit:

# Add any Sphinx extension module names here [...]
extensions = ['sphinxcontrib.phpdomain']

# The name of the default domain.
primary_domain = 'php'

# The default language to highlight source code in.
highlight_language = 'php'

EN : Assuming you allowed the quickstart to create the makefile, you can now test
everything works :

FR : En supposant que vous avez autorisé “quickstart “ à créer le makefile, vous pouvez
maintenant tester que tout fonctionne :

make html

Sphinx allows to comment on the following items:
‘function’ cross­referenced with ‘func’
‘global’ cross­referenced with ‘global’
‘const’ cross­referenced with ‘const’
‘method’ cross­referenced with ‘meth’
‘class’ cross­referenced with ‘class’
‘attr’ cross­referenced with ‘attr’
‘exception’ cross­referenced with ‘exc’
‘namespace’ cross­referenced with ‘ns’
‘interface’ cross­referenced with ‘interface’

**Use with a PHP project**

About sphinx
------------

`<http://www.sphinx­doc.org/en/stable/>`_

`<https://github.com/sphinx­doc/sphinx>`_

`<http://deusyss.developpez.com/tutoriels/Python/SphinxDoc/>`_

`<http://www.sitepoint.com/using­sphinx­for­php­project­documentation/>`_

`<http://symfony.com/doc/current/contributing/documentation/format.html>`_
