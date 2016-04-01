PSR-2: Coding Style Guide
========================

This guide extends and expands on PSR-1_, the basic coding standard.

The intent of this guide is to reduce cognitive friction when scanning code from different authors. It does so by enumerating a shared set of rules and expectations about how to format PHP code.

The style rules herein are derived from commonalities among the various member projects. When various authors collaborate across multiple projects, it helps to have one set of guidelines to be used among all those projects. Thus, the benefit of this guide is not in the rules themselves, but in the sharing of those rules.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in RFC2119_.


1. Overview
-----------

    * Code MUST follow a "coding style guide" PSR [PSR-1_].
    * Code MUST use 4 spaces for indenting, not tabs.
    * There MUST NOT be a hard limit on line length; the soft limit MUST be 120 characters; lines SHOULD be 80 characters or less.
    * There MUST be one blank line after the namespace declaration, and there MUST be one blank line after the block of use declarations.
    * Opening braces for classes MUST go on the next line, and closing braces MUST go on the next line after the body.
    * Opening braces for methods MUST go on the next line, and closing braces MUST go on the next line after the body.
    * Visibility MUST be declared on all properties and methods; abstract and final MUST be declared before the visibility; static MUST be declared after the visibility.
    * Control structure keywords MUST have one space after them; method and function calls MUST NOT.
    * Opening braces for control structures MUST go on the same line, and closing braces MUST go on the next line after the body.
    * Opening parentheses for control structures MUST NOT have a space after them, and closing parentheses for control structures MUST NOT have a space before

.. note::
Aareon attached coding rule : Generic_WhiteSpace_DisallowTabIndent
* Example

    This example encompasses some of the rules below as a quick overview :

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
            // method body
        }

    }

2. General
----------

* Basic Coding Standard

    Code MUST follow all rules outlined in PSR-1_.

* Files

    All PHP files MUST use the Unix LF (linefeed) line ending.

    All PHP files MUST end with a single blank line.

    The closing ``?>`` tag MUST be omitted from files containing only PHP.

    .. note::
Aareon attached coding rule : Zend_Sniffs_Files_ClosingTagSniff

* Lines

    There MUST NOT be a hard limit on line length.

    The soft limit on line length MUST be 120 characters; automated style checkers MUST warn but MUST NOT error at the soft limit.

    Lines SHOULD NOT be longer than 80 characters; lines longer than that SHOULD be split into multiple subsequent lines of no more than 80 characters each.

    There MUST NOT be trailing whitespace at the end of non-blank lines.

    Blank lines MAY be added to improve readability and to indicate related blocks of code.

    There MUST NOT be more than one statement per line.

    .. note::
Aareon attached coding rule : Generic_Files_LineLength

* Indenting

    Code MUST use an indent of 4 spaces, and MUST NOT use tabs for indenting.

        N.b.: Using only spaces, and not mixing spaces with tabs, helps to avoid problems with diffs, patches, history, and annotations. The use of spaces also makes it easy to insert fine-grained sub-indentation for inter-line alignment.

* Keywords and True/False/Null

    PHP keywords_ MUST be in lower case.

    The PHP constants true, false, and null MUST be in lower case


    .. note::
Aareon attached coding rule : Generic_PHP_LowerCaseConstant

3. Namespace and Use Declarations
---------------------------------

    When present, there MUST be one blank line after the ``namespace`` declaration.

    When present, all ``use`` declarations MUST go after the ``namespace`` declaration.

    There MUST be one ``use`` keyword per declaration.

    There MUST be one blank line after the ``use`` block.

    For example:

    .. code-block:: php

        <?php
        namespace Vendor\Package;

        use FooClass;
        use BarClass as Bar;
        use OtherVendor\OtherPackage\BazClass;

        // ... additional PHP code ...

4. Classes, Properties, and Methods
-----------------------------------

    The term "class" refers to all classes, interfaces, and traits.

* Extends and Implements

    The ``extends`` and ``implements`` keywords MUST be declared on the same line as the class name.

    The opening brace for the class MUST go on its own line; the closing brace for the class MUST go on the next line after the body.

    .. code-block:: php

        <?php
        namespace Vendor\Package;

        use FooClass;
        use BarClass as Bar;
        use OtherVendor\OtherPackage\BazClass;

        class ClassName extends ParentClass implements \ArrayAccess, \Countable
        {
            // constants, properties, methods
        }

    Lists of ``implements`` MAY be split across multiple lines, where each subsequent line is indented once. When doing so, the first item in the list MUST be on the next line, and there MUST be only one interface per line.

    .. code-block:: php

        <?php
        namespace Vendor\Package;

        use FooClass;
        use BarClass as Bar;
        use OtherVendor\OtherPackage\BazClass;

        class ClassName extends ParentClass implements
            \ArrayAccess,
            \Countable,
            \Serializable
        {
            // constants, properties, methods
        }

* Properties

    Visibility MUST be declared on all properties.

    The ``var`` keyword MUST NOT be used to declare a property.

    There MUST NOT be more than one property declared per statement.

    Property names SHOULD NOT be prefixed with a single underscore to indicate protected or private visibility.

    A property declaration looks like the following.

    .. code-block:: php

        <?php
        namespace Vendor\Package;

        class ClassName
        {
            public $foo = null;
        }

    .. note::
Aareon attached coding rule : Zend_Sniffs_NamingConventions_ValidVariableNameSniff

    This sniff checks the variables names, to respect the camelCase standard.

        False:

        .. code-block:: php

            <?php
                $this_is_bad = 0;
                $_BadName = 1;
                $WHYUPPERSCORE = 2;
                $_AndThisIsNotAllowed = 3;
                $varNameNumber4 = 4;

        Correct:

        .. code-block:: php

            <?php
                $goodCamelCase = 0;
                $goodName = 1;
                $noNumber = 2;

    Some special cases can be hard to fix at a first seen but can easily be workarounded like the following sample:

        False:

        .. code-block:: php

            <?php
                $goodVar = $xml->head;
                $badVar = $xml->Body;

        Correct:

        .. code-block:: php

            <?php
                $goodVar = $xml->head;
                $badVar = $xml->{'Body'};

* Methods

    Visibility MUST be declared on all methods.

    Method names SHOULD NOT be prefixed with a single underscore to indicate protected or private visibility.

    Method names MUST NOT be declared with a space after the method name. The opening brace MUST go on its own line, and the closing brace MUST go on the next line following the body. There MUST NOT be a space after the opening parenthesis, and there MUST NOT be a space before the closing parenthesis.

    A method declaration looks like the following. Note the placement of parentheses, commas, spaces, and braces:

    .. code-block:: php

        <?php
        namespace Vendor\Package;

        class ClassName
        {
            public function fooBarBaz($arg1, &$arg2, $arg3 = [])
            {
                // method body
            }
        }

    .. note::
Aareon attached coding rule : Generic_Functions_FunctionCallArgumentSpacing

* Method arguments

    In the argument list, there MUST NOT be a space before each comma, and there MUST be one space after each comma.

    Method arguments with default values MUST go at the end of the argument list.

    .. code-block:: php

        <?php
        namespace Vendor\Package;

        class ClassName
        {
            public function foo($arg1, &$arg2, $arg3 = [])
            {
                // method body
            }
        }

    Argument lists MAY be split across multiple lines, where each subsequent line is indented once. When doing so, the first item in the list MUST be on the next line, and there MUST be only one argument per line.

    When the argument list is split across multiple lines, the closing parenthesis and opening brace MUST be placed together on their own line with one space between them.

    .. code-block:: php

        <?php
        namespace Vendor\Package;

        class ClassName
        {
            public function aVeryLongMethodName(
                ClassTypeHint $arg1,
                &$arg2,
                array $arg3 = []
            ) {
                // method body
            }
        }

* abstract, final, and static

    When present, the ``abstract`` and ``final`` declarations MUST precede the visibility declaration.

    When present, the ``static`` declaration MUST come after the visibility declaration.

    .. code-block:: php

        <?php
        namespace Vendor\Package;

        abstract class ClassName
        {
            protected static $foo;

            abstract protected function zim();

            final public static function bar()
            {
                // method body
            }
        }

* Method and Function Calls

    When making a method or function call, there MUST NOT be a space between the method or function name and the opening parenthesis, there MUST NOT be a space after the opening parenthesis, and there MUST NOT be a space before the closing parenthesis. In the argument list, there MUST NOT be a space before each comma, and there MUST be one space after each comma.

    .. code-block:: php

        <?php
        bar();
        $foo->bar($arg1);
        Foo::bar($arg2, $arg3);

    Argument lists MAY be split across multiple lines, where each subsequent line is indented once. When doing so, the first item in the list MUST be on the next line, and there MUST be only one argument per line.

    .. code-block:: php

        <?php
        $foo->bar(
            $longArgument,
            $longerArgument,
            $muchLongerArgument
        );

5. Control Structures
---------------------

    The general style rules for control structures are as follows:

    * There MUST be one space after the control structure keyword
    * There MUST NOT be a space after the opening parenthesis
    * There MUST NOT be a space before the closing parenthesis
    * There MUST be one space between the closing parenthesis and the opening brace
    * The structure body MUST be indented once
    * The closing brace MUST be on the next line after the body

    The body of each structure MUST be enclosed by braces. This standardizes how the structures look, and reduces the likelihood of introducing errors as new lines get added to the body.

* if, elseif, else

    An ``if`` structure looks like the following. Note the placement of parentheses, spaces, and braces; and that ``else`` and ``elseif`` are on the same line as the closing brace from the earlier body.

    .. code-block:: php

        <?php
        if ($expr1) {
            // if body
        } elseif ($expr2) {
            // elseif body
        } else {
            // else body;
        }

    The keyword ``elseif`` SHOULD be used instead of ``else if`` so that all control keywords look like single words.

* switch, case

    A ``switch`` structure looks like the following. Note the placement of parentheses, spaces, and braces. The ``case`` statement MUST be indented once from ``switch``, and the ``break`` keyword (or other terminating keyword) MUST be indented at the same level as the ``case`` body. There MUST be a comment such as ``// no break`` when fall-through is intentional in a non-empty ``case`` body.

    .. code-block:: php

        <?php
        switch ($expr) {
            case 0:
                echo 'First case, with a break';
                break;
            case 1:
                echo 'Second case, which falls through';
                // no break
            case 2:
            case 3:
            case 4:
                echo 'Third case, return instead of break';
                return;
            default:
                echo 'Default case';
                break;
        }

* while, do while

    A ``while`` statement looks like the following. Note the placement of parentheses, spaces, and braces.

    .. code-block:: php

        <?php
        while ($expr) {
            // structure body
        }

    Similarly, a ``do while`` statement looks like the following. Note the placement of parentheses, spaces, and braces.

    .. code-block:: php

        <?php
        do {
            // structure body;
        } while ($expr);

* for

    A ``for`` statement looks like the following. Note the placement of parentheses, spaces, and braces.

    .. code-block:: php

        <?php
        for ($i = 0; $i < 10; $i++) {
            // for body
        }

* foreach

    A ``foreach`` statement looks like the following. Note the placement of parentheses, spaces, and braces.

    .. code-block:: php

        <?php
        foreach ($iterable as $key => $value) {
            // foreach body
        }

* try, catch

    A ``try catch`` block looks like the following. Note the placement of parentheses, spaces, and braces.

    .. code-block:: php

        <?php
        try {
            // try body
        } catch (FirstExceptionType $e) {
            // catch body
        } catch (OtherExceptionType $e) {
            // catch body
        }


    .. note::
Aareon attached coding rule : Generic_ControlStructures_InlineControlStructure

6. Closures
-----------

    Closures MUST be declared with a space after the ``function`` keyword, and a space before and after the ``use`` keyword.

    The opening brace MUST go on the same line, and the closing brace MUST go on the next line following the body.

    There MUST NOT be a space after the opening parenthesis of the argument list or variable list, and there MUST NOT be a space before the closing parenthesis of the argument list or variable list.

    In the argument list and variable list, there MUST NOT be a space before each comma, and there MUST be one space after each comma.

    Closure arguments with default values MUST go at the end of the argument list.

    A closure declaration looks like the following. Note the placement of parentheses, commas, spaces, and braces:

    .. code-block:: php

        <?php
        $closureWithArgs = function ($arg1, $arg2) {
            // body
        };

        $closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
            // body
        };

    Argument lists and variable lists MAY be split across multiple lines, where each subsequent line is indented once. When doing so, the first item in the list MUST be on the next line, and there MUST be only one argument or variable per line.

    When the ending list (whether or arguments or variables) is split across multiple lines, the closing parenthesis and opening brace MUST be placed together on their own line with one space between them.

    The following are examples of closures with and without argument lists and variable lists split across multiple lines.

    .. code-block:: php

        <?php
        $longArgs_noVars = function (
            $longArgument,
            $longerArgument,
            $muchLongerArgument
        ) {
           // body
        };

        $noArgs_longVars = function () use (
            $longVar1,
            $longerVar2,
            $muchLongerVar3
        ) {
           // body
        };

        $longArgs_longVars = function (
            $longArgument,
            $longerArgument,
            $muchLongerArgument
        ) use (
            $longVar1,
            $longerVar2,
            $muchLongerVar3
        ) {
           // body
        };

        $longArgs_shortVars = function (
            $longArgument,
            $longerArgument,
            $muchLongerArgument
        ) use ($var1) {
           // body
        };

        $shortArgs_longVars = function ($arg) use (
            $longVar1,
            $longerVar2,
            $muchLongerVar3
        ) {
           // body
        };

    Note that the formatting rules also apply when the closure is used directly in a function or method call as an argument.

    .. code-block:: php

        <?php
        $foo->bar(
            $arg1,
            function ($arg2) use ($var1) {
                // body
            },
            $arg3
        );


7. Aareon : Development Coding Rules and Standards
--------------------------------------------------

    On the Flexiciel, we have severals tools to write and ensure the performance and readability of the source code. The aim is to define coding rules and standards to make every developpers read some source code easily and so, have a quick understanding of every part of the source code. By doing this, each developpers will be able to debug quickly or add fastly features because of the anknowledgement of the source code.

    Here are descriptions of tools and rules inside, used by the Flexiciel project.

    **Tool: PHP_CodeSniffer (PHPCS)**

    The PHPCS tool is used for syntax and readability performance. There's no real process-time optimization to deal with, but it clearly helps developpers to be more efficient in the debugging or featuring process. Let's explain all active rules of the PHPCS:

* Rule: Zend Standard

*Since the start of the project*

Since the start of the project

+--------------------------+--------------------------+
| Sniff                    | Description & Code       |
|                          | Sample                   |
+==========================+==========================+
| Zend\_Sniffs\_Debug\_Cod | This is an internal      |
| eAnalyzerSniff           | sniffer for              |
|                          | Zend\_Debugger.          |
+--------------------------+--------------------------+
| Zend\_Sniffs\_Files\_Clo | This sniff checks that   |
| singTagSniff             | the PHP file doesn't end |
|                          | with a closing PHP tag,  |
|                          | because it triggers some |
|                          | unexpected behaviours.   |
|                          | False:                   |
|                          |                          |
|                          |                          |
|                          |     <?php                |
|                          |     //some content code. |
|                          |      ...                 |
|                          |     //end of file        |
|                          |     ?>                   |
|                          |                          |
|                          | Correct:                 |
|                          |                          |
|                          |                          |
|                          |     <?php                |
|                          |     //some content code. |
|                          |     ...                  |
|                          |     //end of file        |
+--------------------------+--------------------------+


Rule: Generic Standard\ `¶ <#Rule-Generic-Standard>`__
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Since the start of the project

+--------------------------+--------------------------+
| Sniff                    | Description & Code       |
|                          | Sample                   |
+==========================+==========================+
| Generic\_CodeAnalysis\_E | Checks there's no empty  |
| mptyStatement            | statements.              |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     if ($empty == true)  |
|                          | {                        |
|                          |         //Empty statemen |
|                          | t                        |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     if ($empty == true)  |
|                          | {                        |
|                          |         $empty = false;  |
|                          |         //Non empty stat |
|                          | ement                    |
|                          |     }                    |
+--------------------------+--------------------------+
| Generic\_CodeAnalysis\_F | Avoid calling a function |
| orLoopWithTestFunctionCa | into a for each time     |
| ll                       | when it's not necessary. |
|                          | False:                   |
|                          |                          |
|                          |     <?php                |
|                          |     for ($i = 0; $i<coun |
|                          | t($array); $i++) {       |
|                          |         //Counting each  |
|                          | time you end the loop    |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     for ($i = 0, $nb = c |
|                          | ount($array); $i<$nb; ++ |
|                          | $i) {                    |
|                          |         //Count only onc |
|                          | e                        |
|                          |     }                    |
+--------------------------+--------------------------+
| Generic\_CodeAnalysis\_U | This sniff detects all   |
| nusedFunctionParameter   | unused parameters        |
|                          | declared in functions    |
|                          | and methods.             |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     function stupidFunct |
|                          | ion($usefulParam, $stupi |
|                          | dParam)                  |
|                          |     {                    |
|                          |         return $usefulPa |
|                          | ram;                     |
|                          |         //So why using $ |
|                          | stupidParam??            |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     function goodFunctio |
|                          | n($usefulParam)          |
|                          |     {                    |
|                          |         return $usefulPa |
|                          | ram;                     |
|                          |     }                    |
+--------------------------+--------------------------+
| Generic\_CodeAnalysis\_U | It sends warnings if you |
| selessOverridingMethod   | try to override a method |
|                          | by only calling the      |
|                          | parent's one.            |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A              |
|                          |     {                    |
|                          |         public function  |
|                          | doThis()                 |
|                          |         {                |
|                          |             return 'OK!' |
|                          | ;                        |
|                          |         }                |
|                          |     }                    |
|                          |     class B extends A    |
|                          |     {                    |
|                          |         public function  |
|                          | doThis()                 |
|                          |         {                |
|                          |             //This is us |
|                          | eless, unless you really |
|                          |  want                    |
|                          |             //to do some |
|                          | thing different from par |
|                          | ent                      |
|                          |             return paren |
|                          | t::doThis();             |
|                          |         }                |
|                          |     }                    |
|                          |     $b = new B();        |
|                          |     $result = $b->doThis |
|                          | ();                      |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A              |
|                          |     {                    |
|                          |         public function  |
|                          | doThis()                 |
|                          |         {                |
|                          |             return 'OK!' |
|                          | ;                        |
|                          |         }                |
|                          |     }                    |
|                          |     class B extends A    |
|                          |     {                    |
|                          |         //Don't need to  |
|                          | overwrite A::doThis()    |
|                          |     }                    |
|                          |     $b = new B();        |
|                          |     $result = $b->doThis |
|                          | ();                      |
|                          |     //Works perfectly!   |
+--------------------------+--------------------------+
| Generic\_ControlStructur | Oblige developpers to    |
| es\_InlineControlStructu | use brackets in their    |
| re                       | statements for a better  |
|                          | readability.             |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     if ($makeIncorrectSt |
|                          | uff == true)             |
|                          |         doSomething();   |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     if ($makeCorrectStuf |
|                          | f == true) {             |
|                          |         doSomething();   |
|                          |     }                    |
+--------------------------+--------------------------+
| Generic\_Files\_ByteOrde | Detects the Byte Order   |
| rMark                    | Mark (BOM) on each PHP   |
|                          | file encoded in UTF-8 if |
|                          | present. A BOM can       |
|                          | display the content of a |
|                          | PHP file instead of      |
|                          | compiling it or, in most |
|                          | often cases, displays    |
|                          | unwanted chars like      |
|                          | "î»¿".                   |
|                          | Just open an editor      |
|                          | showing you if BOM is    |
|                          | used and configure your  |
|                          | file to remove it.       |
+--------------------------+--------------------------+
| Generic\_Files\_LineEndi | Lines must end with a    |
| ngs                      | Unix carridge return     |
|                          | "\\n" and not a Windows  |
|                          | ("\\r\\n") or a Mac      |
|                          | ("\\r") one.             |
|                          | Just open an editor      |
|                          | showing you the line     |
|                          | endings and configure    |
|                          | your file to use Unix's  |
|                          | carridge return.         |
+--------------------------+--------------------------+
| Generic\_Formatting\_NoS | This sniff warns you if  |
| paceAfterCast            | you let a space between  |
|                          | a casting operation and  |
|                          | the variable you want to |
|                          | cast.                    |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     $a = (int) $b;       |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     $a = (int)$b;        |
+--------------------------+--------------------------+
| Generic\_Functions\_Call | Prevent you from call    |
| TimePassByReference      | time passed by           |
|                          | reference. This is       |
|                          | deprecated and can throw |
|                          | errors on PHP > 5.2.     |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     function myCount($ar |
|                          | ray, &$nb)               |
|                          |     {                    |
|                          |         $nb = count($arr |
|                          | ay);                     |
|                          |         return $array;   |
|                          |     }                    |
|                          |     $nb = 0;             |
|                          |     $array = myCount($ar |
|                          | ray, &$nb);              |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     function myCount($ar |
|                          | ray, &$nb)               |
|                          |     {                    |
|                          |         $nb = count($arr |
|                          | ay);                     |
|                          |         return $array;   |
|                          |     }                    |
|                          |     $nb = 0;             |
|                          |     $array = myCount($ar |
|                          | ray, $nb);               |
|                          |     //Note there's no "& |
|                          | " sign before the $nb va |
|                          | riable.                  |
+--------------------------+--------------------------+
| Generic\_Functions\_Open | Checks the opening of a  |
| ingFunctionBraceBsdAllma | method or function is    |
| n                        | done with a braket       |
|                          | placed in the BSD Allman |
|                          | convention.              |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     function braceOnSame |
|                          | Line() {                 |
|                          |                          |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     function braceOnNewL |
|                          | ine()                    |
|                          |     {                    |
|                          |                          |
|                          |     }                    |
|                          |     //Only for classes a |
|                          | nd functions             |
+--------------------------+--------------------------+
| Generic\_Metrics\_Cyclom | Calculates the           |
| aticComplexity           | complexity of a function |
|                          | or method. This must not |
|                          | be over 15.              |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     //"function" keyword |
|                          |  (1)                     |
|                          |     function complexFunc |
|                          | tion($param)             |
|                          |     {                    |
|                          |         switch($param) { |
|                          |  //"switch" keyword (2)  |
|                          |             case 'method |
|                          | One': //"case" keyword ( |
|                          | 3)                       |
|                          |                 $value = |
|                          |  MyClass::methodOne();   |
|                          |                 break;   |
|                          |             case 'method |
|                          | Two': //current is (4)   |
|                          |                 //...    |
|                          |                 break;   |
|                          |             default:     |
|                          |         }                |
|                          |         $i = 0;          |
|                          |         while ($i++ < 10 |
|                          | ) { //"while" keyword (5 |
|                          | )                        |
|                          |             if ($i < 3)  |
|                          | { //"if" keyword (6)     |
|                          |                 $value[$ |
|                          | i] = MyClass::methodTwo( |
|                          | );                       |
|                          |             } elseif ($i |
|                          |  == 4) { //"elseif" keyw |
|                          | ord (7)                  |
|                          |                 $value[$ |
|                          | i] = MyClass::methodTwo( |
|                          | $i);                     |
|                          |             } else {     |
|                          |                 $value[$ |
|                          | i] = MyClass::methodTwo( |
|                          | null);                   |
|                          |             }            |
|                          |         }                |
|                          |         //... and others |
|                          |  source code             |
|                          |     }                    |
|                          |     // Keywords increasi |
|                          | ng cyclomatic complexity |
|                          |  are :                   |
|                          |     // - function        |
|                          |     // - if / elseif     |
|                          |     // - for / while / d |
|                          | o...while                |
|                          |     // - foreach         |
|                          |     // - switch / case   |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     function complexFunc |
|                          | tion($param)             |
|                          |     {                    |
|                          |         $tmpA = firstSim |
|                          | pleFunction($param);     |
|                          |         $tmpB = secondSi |
|                          | mpleFunction($param);    |
|                          |         //...            |
|                          |     }                    |
+--------------------------+--------------------------+
| Generic\_Metrics\_Nestin | Evaluates the level of   |
| gLevel                   | nesting of each          |
|                          | functions or methods.    |
|                          | This must not be over 4. |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     foreach ($array as $ |
|                          | value) {                 |
|                          |         //Nesting Level  |
|                          | : 1                      |
|                          |         if ($value < 500 |
|                          | ) {                      |
|                          |             //Nesting Le |
|                          | vel : 2                  |
|                          |             if (($value  |
|                          | + 100) < 200) {          |
|                          |                 //Nestin |
|                          | g Level : 3              |
|                          |                 if (true |
|                          | ) {                      |
|                          |                     //Ne |
|                          | sting Level : 4          |
|                          |                     if ( |
|                          | $value < 10) {           |
|                          |                          |
|                          | //Nesting Level : 5      |
|                          |                          |
|                          | return $value;           |
|                          |                     } el |
|                          | se {                     |
|                          |                          |
|                          | //Nesting Level : 5      |
|                          |                          |
|                          | return ($value + 10);    |
|                          |                     }    |
|                          |                 }        |
|                          |             }            |
|                          |         }                |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     foreach ($array as $ |
|                          | value) {                 |
|                          |         //Nesting Level  |
|                          | : 1                      |
|                          |         if ($value >= 50 |
|                          | 0) {                     |
|                          |             //Nesting Le |
|                          | vel : 2                  |
|                          |             continue;    |
|                          |         }                |
|                          |         if (($value + 10 |
|                          | 0) >= 200) {             |
|                          |             //Nesting Le |
|                          | vel : 2                  |
|                          |             continue;    |
|                          |         }                |
|                          |         $return = ($valu |
|                          | e < 10)                  |
|                          |             ? $value     |
|                          |             : ($value +  |
|                          | 10);                     |
|                          |         return $return;  |
|                          |     }                    |
+--------------------------+--------------------------+
| Generic\_NamingConventio | Obliges you to uppercase |
| ns\_UpperCaseConstantNam | your own constants.      |
| e                        | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     define('my_const', 8 |
|                          | );                       |
|                          |     define('myOtherConst |
|                          | ', 16);                  |
|                          |     class A              |
|                          |     {                    |
|                          |         const toto = 'tu |
|                          | tu';                     |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     define('MY_CONST', 8 |
|                          | );                       |
|                          |     define('MY_OTHER_CON |
|                          | ST', 16);                |
|                          |     class A              |
|                          |     {                    |
|                          |         const TOTO = 'tu |
|                          | tu';                     |
|                          |     }                    |
+--------------------------+--------------------------+
| Generic\_PHP\_Deprecated | Shows all uses of        |
| Functions                | deprecated functions or  |
|                          | methods.                 |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     ereg($args);         |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     preg_match($args);   |
+--------------------------+--------------------------+
| Generic\_PHP\_DisallowSh | Makes it mandatory to    |
| ortOpenTag               | use full tags PHP, which |
|                          | is "<?php".              |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?                   |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
+--------------------------+--------------------------+
| Generic\_PHP\_NoSilenced | Detects all utilisations |
| Errors                   | of the silenced errors   |
|                          | practice using the "@"   |
|                          | char before the name of  |
|                          | the function to call.    |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     @fopen($file, 'w+b') |
|                          | ;                        |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     fopen($file, 'w+b'); |
+--------------------------+--------------------------+
| Generic\_WhiteSpace\_Sco | Checks the indent based  |
| peIndent                 | on the current scope the |
|                          | line of your code is.    |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     if (false) {         |
|                          |     $iAmSoBadIndent = tr |
|                          | ue;                      |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     if (true) {          |
|                          |         $iAmSoGoodIndent |
|                          |  = true;                 |
|                          |     }                    |
+--------------------------+--------------------------+

Since the release 1.3 (Sprint 1)

+--------------------------+--------------------------+
| Sniff                    | Description & Code       |
|                          | Sample                   |
+==========================+==========================+
| Generic\_CodeAnalysis\_U | This sniff can tell you  |
| nconditionalIfStatement  | if you write an "if"     |
|                          | statement without any    |
|                          | condition.               |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     if () {              |
|                          |         //So ?           |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     if (true) {          |
|                          |         //Looks like bet |
|                          | ter...                   |
|                          |     }                    |
+--------------------------+--------------------------+
| Generic\_CodeAnalysis\_U | Prevents you from using  |
| nnecessaryFinalModifier  | the "final" keyword on   |
|                          | functions or methods     |
|                          | while it's not           |
|                          | necessary.               |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A              |
|                          |     {                    |
|                          |         public final fun |
|                          | ction iAmLast()          |
|                          |         {                |
|                          |                          |
|                          |         }                |
|                          |     }                    |
|                          |     class B extends A    |
|                          |     {                    |
|                          |         public function  |
|                          | iAmLast()                |
|                          |         {                |
|                          |             //This throw |
|                          | s fatal errors... Brrr.. |
|                          | . So bad...              |
|                          |         }                |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A              |
|                          |     {                    |
|                          |         public function  |
|                          | iAmLast()                |
|                          |         {                |
|                          |                          |
|                          |         }                |
|                          |     }                    |
|                          |     class B extends A    |
|                          |     {                    |
|                          |         public function  |
|                          | iAmLast()                |
|                          |         {                |
|                          |             //This is ok |
|                          |  :)                      |
|                          |         }                |
|                          |     }                    |
+--------------------------+--------------------------+
| Generic\_Formatting\_Dis | Enforces developpers to  |
| allowMultipleStatements  | declare one statement by |
|                          | line.                    |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     $a = 'a'; $b = 'b';  |
|                          | $c = 'c'; $d = 'd';      |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     $a = 'a';            |
|                          |     $b = 'b';            |
|                          |     $c = 'c';            |
|                          |     $d = 'd';            |
+--------------------------+--------------------------+



Rule: PEAR Standard\ `¶ <#Rule-PEAR-Standard>`__
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Since the start of the project

+--------------------------+--------------------------+
| Sniff                    | Description & Code       |
|                          | Sample                   |
+==========================+==========================+
| PEAR\_Classes\_ClassDecl | Checks the declaration   |
| aration                  | of the class is correct. |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A {            |
|                          |                          |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A              |
|                          |     {                    |
|                          |         //Look the brake |
|                          | t position               |
|                          |     }                    |
+--------------------------+--------------------------+
| PEAR\_Functions\_Functio | Checks the whitespaces   |
| nCallSignature           | before and after a       |
|                          | function call and a      |
|                          | function signature.      |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     $a = thisIsNotAllowe |
|                          | d ( $params ) ;          |
|                          |     function neitherThis |
|                          |  ( $args )               |
|                          |     {                    |
|                          |                          |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     $a = thisIsAllowed($ |
|                          | params);                 |
|                          |     function eitherThis( |
|                          | $args)                   |
|                          |     {                    |
|                          |                          |
|                          |     }                    |
+--------------------------+--------------------------+
| PEAR\_Functions\_ValidDe | Obliges you to write     |
| faultValue               | arguments with default   |
|                          | values at the end of     |
|                          | function or method       |
|                          | definitions.             |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     function myCount($nb |
|                          |  = 0, $array)            |
|                          |     {                    |
|                          |                          |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     function myCount($ar |
|                          | ray, $nb = 0)            |
|                          |     {                    |
|                          |                          |
|                          |     }                    |
+--------------------------+--------------------------+
| PEAR\_WhiteSpace\_ScopeC | Be sure the closing      |
| losingBrace              | brace for functions,     |
|                          | methods, classes or      |
|                          | statements are well      |
|                          | indents.                 |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     if (true) {          |
|                          |         if (false) {     |
|                          |             //Where's th |
|                          | e closing bracket?       |
|                          |     }                    |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     if (true) {          |
|                          |         if (false) {     |
|                          |             //Oh good, i |
|                          | t's just behind my "if"  |
|                          | statement                |
|                          |         }                |
|                          |     }                    |
+--------------------------+--------------------------+

Since the release 1.3 (Sprint 1)

+--------------------------+--------------------------+
| Sniff                    | Description & Code       |
|                          | Sample                   |
+==========================+==========================+
| PEAR\_Commenting\_Inline | Errors you when using a  |
| Comment                  | perl-style comment.      |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     #This is a comment   |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     //This is a comment  |
+--------------------------+--------------------------+



Rule: Squiz Standard\ `¶ <#Rule-Squiz-Standard>`__
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Since the start of the project

+--------------------------+--------------------------+
| Sniff                    | Description & Code       |
|                          | Sample                   |
+==========================+==========================+
| Squiz\_Functions\_Global | Avoid using and declare  |
| Function                 | global functions         |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     function doThis()    |
|                          |     {                    |
|                          |         //...            |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A              |
|                          |     {                    |
|                          |         public static fu |
|                          | nction doThis()          |
|                          |         {                |
|                          |             //...        |
|                          |         }                |
|                          |     }                    |
+--------------------------+--------------------------+

Since the release 1.3 (Sprint 1)

+--------------------------+--------------------------+
| Sniff                    | Description & Code       |
|                          | Sample                   |
+==========================+==========================+
| Squiz\_PHP\_GlobalKeywor | Stops the usage of the   |
| d                        | "global" keyword.        |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     global $bar;         |
|                          |     $foo = $_GLOBAL['bar |
|                          | '];                      |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     //Use variables corr |
|                          | ectly                    |
+--------------------------+--------------------------+
| Squiz\_Scope\_MemberVarS | Obliges the "public",    |
| cope                     | "protected" and          |
|                          | "private" keyword for    |
|                          | class attributes.        |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A              |
|                          |     {                    |
|                          |         $public = 'publi |
|                          | c';                      |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A              |
|                          |     {                    |
|                          |         public $public = |
|                          |  'public';               |
|                          |         protected $_prot |
|                          | ected = 'protected';     |
|                          |     }                    |
+--------------------------+--------------------------+
| Squiz\_Scope\_MethodScop | Obliges the "public",    |
| e                        | "protected" and          |
|                          | "private" keyword for    |
|                          | class methods.           |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A              |
|                          |     {                    |
|                          |         function public( |
|                          | )                        |
|                          |         {                |
|                          |                          |
|                          |         }                |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A              |
|                          |     {                    |
|                          |         public function  |
|                          | public()                 |
|                          |         {                |
|                          |                          |
|                          |         }                |
|                          |     }                    |
+--------------------------+--------------------------+
| Squiz\_WhiteSpace\_Super | Sends errors if PHP      |
| fluousWhitespace         | files contains useless   |
|                          | whitespaces or empty     |
|                          | lines (twice in a row is |
|                          | not allowed).            |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     //There're spaces af |
|                          | ter this line            |
|                          |                          |
|                          |     //And large hole abo |
|                          | ve                       |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     //There's no more sp |
|                          | aces after this line     |
|                          |                          |
|                          |     //And the empty line |
|                          | s are reduce to only one |
+--------------------------+--------------------------+

Must be active in the release 1.3 (Sprint 2)

+--------------------------+--------------------------+
| Sniff                    | Description & Code       |
|                          | Sample                   |
+==========================+==========================+
| Squiz\_Arrays\_ArrayBrac | False:                   |
| ketSpacing               | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     $array[ 'Hello'];    |
|                          |     $array ['Hello'];    |
|                          |     $array               |
|                          |     ['Hello'];           |
|                          |     $array ['Hello'      |
|                          |     ];                   |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     $array['Hello'];     |
+--------------------------+--------------------------+
| Squiz\_WhiteSpace\_Logic | False:                   |
| alOperatorSpacing        | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     if ($foo||$bar && $b |
|                          | az) {}                   |
|                          |     if ($foo|| $bar&amp; |
|                          | &amp;$baz) {}            |
|                          |     if ($foo  ||   $bar  |
|                          |   && $baz) {}            |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     if ($foo || $bar &&  |
|                          | $baz) {}                 |
+--------------------------+--------------------------+
| Squiz\_WhiteSpace\_Semic | False:                   |
| olonSpacing              | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     doThis() ;           |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     doThis();            |
+--------------------------+--------------------------+

Must be active in the release 1.3 (Sprint 3)

+--------------------------+--------------------------+
| Sniff                    | Description & Code       |
|                          | Sample                   |
+==========================+==========================+
| Squiz\_PHP\_NonExecutabl | False:                   |
| eCode                    | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     return $value;       |
|                          |     $value += 1;         |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     $value += 1;         |
|                          |     return $value;       |
+--------------------------+--------------------------+

Must be active in the release 1.4

+--------------------------+--------------------------+
| Sniff                    | Description & Code       |
|                          | Sample                   |
+==========================+==========================+
| Squiz\_Classes\_SelfMemb | False:                   |
| erReference              | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A              |
|                          |     {                    |
|                          |         public static fu |
|                          | nction dontMove()        |
|                          |         {                |
|                          |                          |
|                          |         }                |
|                          |                          |
|                          |         public static fu |
|                          | nction freeze()          |
|                          |         {                |
|                          |             Self::dontMo |
|                          | ve();                    |
|                          |             self ::dontM |
|                          | ove();                   |
|                          |         }                |
|                          |     }                    |
|                          |     $a = new A();        |
|                          |     $a->freeze();        |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A              |
|                          |     {                    |
|                          |         public static fu |
|                          | nction dontMove()        |
|                          |         {                |
|                          |                          |
|                          |         }                |
|                          |                          |
|                          |         public static fu |
|                          | nction freeze()          |
|                          |         {                |
|                          |             self::dontMo |
|                          | ve();                    |
|                          |         }                |
|                          |     }                    |
|                          |     A::freeze();         |
+--------------------------+--------------------------+
| Squiz\_Strings\_DoubleQu | False:                   |
| oteUsage                 | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     "Why double quoting  |
|                          | this?";                  |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     'You do not need';   |
|                          |     "But this is allowed |
|                          |  'cause of the tick";    |
+--------------------------+--------------------------+


Rule: Aareon Standard\ `¶ <#Rule-Aareon-Standard>`__
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

This ruling contains all sniffs created by Aareon. They're into
Jenkins/phpcs/Standards/Aareon/Sniffs/

Since the start of the project

+--------------------------+--------------------------+
| Sniff                    | Description & Code       |
|                          | Sample                   |
+==========================+==========================+
| Aareon\_Functions\_Leadi | Checks that all          |
| ngUnderscore             | protected and private    |
|                          | methods have a leading   |
|                          | underscore char.         |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A              |
|                          |     {                    |
|                          |         public function  |
|                          | _public()                |
|                          |         {                |
|                          |                          |
|                          |         }                |
|                          |         protected functi |
|                          | on protected()           |
|                          |         {                |
|                          |                          |
|                          |         }                |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A              |
|                          |     {                    |
|                          |         public function  |
|                          | public()                 |
|                          |         {                |
|                          |                          |
|                          |         }                |
|                          |         protected functi |
|                          | on _protected()          |
|                          |         {                |
|                          |                          |
|                          |         }                |
|                          |     }                    |
+--------------------------+--------------------------+
| Aareon\_Functions\_NoVar | Throws errors for each   |
| Dump                     | "var\_dump()" function   |
|                          | calls.                   |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     $test = 'test';      |
|                          |     var_dump($test);     |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     $test = 'test';      |
+--------------------------+--------------------------+
| Aareon\_Functions\_Visib | This sniff triggers      |
| ility                    | error if "public",       |
|                          | "protected" or "private" |
|                          | keyword is missing.      |
|                          | False:                   |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A              |
|                          |     {                    |
|                          |         $public = 'publi |
|                          | c';                      |
|                          |         function public( |
|                          | )                        |
|                          |         {                |
|                          |                          |
|                          |         }                |
|                          |     }                    |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     class A              |
|                          |     {                    |
|                          |         public $public = |
|                          |  'public';               |
|                          |         public function  |
|                          | public()                 |
|                          |         {                |
|                          |                          |
|                          |         }                |
|                          |     }                    |
+--------------------------+--------------------------+

Disabled

+--------------------------+--------------------------+
| Sniff                    | Description & Code       |
|                          | Sample                   |
+==========================+==========================+
| Aareon\_Comments\_FindTo | False:                   |
| doSniff                  | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     /*                   |
|                          |      * @todo: TODO       |
|                          |      */                  |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     // Stuff is done.    |
+--------------------------+--------------------------+
| Aareon\_Formatting\_Spac | False:                   |
| esAroundConcatSniff      | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     'Line'.' without '.' |
|                          | space'.' concatenation'; |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     'Line' . ' without ' |
|                          |  . 'space' . ' concatena |
|                          | tion';                   |
+--------------------------+--------------------------+
| Aareon\_Strings\_Unneces | False:                   |
| saryStringConcatSniff    | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     'Text' . ' that ' .  |
|                          | "don't" . ' need concate |
|                          | nation';                 |
|                          |                          |
|                          | Correct:                 |
|                          | ::                       |
|                          |                          |
|                          |     <?php                |
|                          |     'Text that ' . "don' |
|                          | t" . ' need concatenatio |
|                          | n';                      |
|                          |                          |
|                          | *Note that because of    |
|                          | the usage of double      |
|                          | quote, concat is allowed |
|                          | here.*                   |
+--------------------------+--------------------------+

