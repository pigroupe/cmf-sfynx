--------------------------------------------------------------------
DemoApiContext\\InfrastructureBundle\\Features\\Context\\MinkContext
--------------------------------------------------------------------

.. php:namespace: DemoApiContext\\InfrastructureBundle\\Features\\Context

.. php:class:: MinkContext

    .. php:method:: __construct()

        Initializes context.

        Every scenario gets its own context instance.
        You can also pass arbitrary arguments to the context constructor through
        behat.yml.

    .. php:method:: iAmLoggedAs($role)

        Log with a role

        :param $role:

    .. php:method:: iWaitForXSeconds($time)

        :param $time:

    .. php:method:: clickOn($element)

        Click on element CSS with index name

        :param $element:

    .. php:method:: assertElementVisible($element)

        Checks, that element with specified CSS is visible on page.

        :param $element:

    .. php:method:: assertElementNotVisible($element)

        Checks, that element with specified CSS is not visible on page.

        :param $element:

    .. php:method:: assertElementChildrenOnPage($element, $children = array())

        Checks, that element children with specified CSS are on page.

        :type $element: string
        :param $element:
        :type $children: array
        :param $children:

    .. php:method:: assertElementChildrenNotOnPage($element, $children = array())

        Checks, that element children with specified CSS are not on page.

        :type $element: string
        :param $element:
        :type $children: array
        :param $children:

    .. php:method:: assertElementChildrensVisible($element, $childrens = array())

        Checks, that element childrens with specified CSS are visible on page.

        :type $element: string
        :param $element:
        :type $childrens: array
        :param $childrens:

    .. php:method:: assertElementChildrensNotVisible($element, $childrens = array())

        Checks, that element childrens with specified CSS are not visible on page.

        :type $element: string
        :param $element:
        :type $childrens: array
        :param $childrens:

    .. php:method:: assertPropertyExists($property, $subject = null)

        Check an object parameter existance

        :param $property:
        :param $subject:

    .. php:method:: iSelectTheFirstLink($text)

        :param $text:
