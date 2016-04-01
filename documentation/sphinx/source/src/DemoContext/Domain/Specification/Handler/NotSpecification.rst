-------------------------------------------------------------
DemoContext\\Domain\\Specification\\Handler\\NotSpecification
-------------------------------------------------------------

.. php:namespace: DemoContext\\Domain\\Specification\\Handler

.. php:class:: NotSpecification

    This file is part of the <Trigger> project.
    True if false and false if true

    .. php:method:: __construct(InterfaceSpecification $specification)

        :type $specification: InterfaceSpecification
        :param $specification:

    .. php:method:: isSatisfiedBy($object)

        :param $object:

    .. php:method:: andSpec(InterfaceSpecification $specification)

        :type $specification: InterfaceSpecification
        :param $specification:

    .. php:method:: orSpec(InterfaceSpecification $specification)

        :type $specification: InterfaceSpecification
        :param $specification:

    .. php:method:: notSpec(InterfaceSpecification $specification)

        :type $specification: InterfaceSpecification
        :param $specification:

    .. php:method:: xorSpec(InterfaceSpecification $specification)

        :type $specification: InterfaceSpecification
        :param $specification:

    .. php:method:: equalToSpec($specification1, $specification2)

        :param $specification1:
        :param $specification2:

    .. php:method:: setValues($specification1, $specification2, $object)

        :param $specification1:
        :param $specification2:
        :param $object:
