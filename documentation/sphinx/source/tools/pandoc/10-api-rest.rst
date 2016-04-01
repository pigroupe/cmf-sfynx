DemoApi context
===============

**GET request** :

::

    GET http://cosmos.alterway.dev/api/v1/actors.json

**GET request** :

::

    GET http://cosmos.alterway.dev/api/v1/actor/{actordId}

**POST request** :

::

    POST http://cosmos.alterway.dev/api/v1/actor
    {       
               "lastname":"denil"
                    ,"firstname":"laurent"
                    ,"birthday":"2015-01-01"
                    ,"sex":"M"
                    ,"email":"laurent.de-nil@alterway.fr"
                    ,"phoneNumber1":"0134342233"
                    ,"phoneNumber2":"0612131415"
                    ,"salary": 100000.0
                    ,"salaryCurrency":"EUR"
                    }

**PUT request** :

::

    PUT http://cosmos.alterway.dev/api/v1/actor
    {        "actorId": "un id ...."
               "lastname":"denil"
                    ,"firstname":"laurent"
                    ,"birthday":"2015-01-01"
                    ,"sex":"M"
                    ,"email":"laurent.de-nil@alterway.fr"
                    ,"phoneNumber1":"0134342233"
                    ,"phoneNumber2":"0612131415"
                    ,"salary": 100000.0
                    ,"salaryCurrency":"EUR"
                    }

**DELETE request** :

::

    DELETE http://cosmos.alterway.dev/api/v1/actor/{actordId}

**DELETE request** :

::

    DELETE http://cosmos.alterway.dev/api/v1/actors
    {
        "actorIds":["56ce84d5-4a50-4e90-8e26-37a71d03de4a", "958703f3-116e-4097-9af3-c2e7026e9a8d"]
    }
