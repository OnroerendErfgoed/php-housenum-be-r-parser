Housenum-be-r-parser-php
========================

A small library for merging and splitting sequences of Belgian housenumbers.
    
.. image:: https://travis-ci.org/OnroerendErfgoed/php-housenum-be-r-parser.png?branch=master
        :target: https://travis-ci.org/OnroerendErfgoed/php-housenum-be-r-parser
.. image:: https://coveralls.io/repos/OnroerendErfgoed/php-housenum-be-r-parser/badge.png?branch=master 
        :target: https://coveralls.io/r/OnroerendErfgoed/php-housenum-be-r-parser?branch=master
.. image:: https://poser.pugx.org/oe/housenum-be-r-parser/v/stable.svg
        :target: https://packagist.org/packages/oe/housenum-be-r-parser

Description
-----------

Splits ranges of Belgian house numbers into individual ones and vice versa.

Installation
------------

The HousenumBErParser can be installed through composer:

.. code-block:: js

    //composer.json
    {
        "require": {
            "oe/housenum-be-r-parser": "dev-master"
        }
    }


Usage
-----

Splitting a range of housenumbers:

.. code-block:: php

    <?php
    use OE\HousenumBErParser\Facade;

    $facade = new Facade();

    echo "Splitting 15-21\n";

    $split = $facade->split('15-21');

    foreach($split as $hnr) {
        echo $hnr . "\n";
    }

Merging housenumbers:

.. code-block:: php

    <?php
    use OE\HousenumBErParser\Facade;

    $facade = new Facade();

    echo "Merging 17/1, 15, 20 bus C, 20 bus B, 17/3, 17/2, 20 bus A\n";

    $merge = $facade->merge('17/1, 15, 20 bus C, 20 bus B, 17/3, 17/2, 20 bus A');

    echo $facade->numbersToString($merge) . "\n";

The Facade can handle errors in three different ways. It can ignores errors 
(the default way of handling errors). In this case invalid input will be left
as it is as much as possible. So, when trying to split the string 
*25, something*, the resulting output will be *25, something*.

The second option is to remove invalid input. This can be set by passing 
*Reader::ERR_REMOVE_INVALID_INPUT* to the Facade constructor. In this case, invalid
input will be stripped as much as possible. So, when trying to split the string
*25, something*, the resulting output will be *25*.

The third and final option is to throw *Exceptions*. This can be set by passing
*Reader::ERR_EXCEPTIONS* to the Facade constructor. In this case, invalid
input will trigger an *Exception*. So, when trying to split the string 
*25, something*, an exception will be thrown.

Other languages
---------------

There is a similar library that does the same, but written in python.
