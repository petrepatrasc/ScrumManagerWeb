# Scrum Manager #

[![Travis Build Status](https://travis-ci.org/petrepatrasc/ScrumManagerWeb.png?branch=master)](https://travis-ci.org/petrepatrasc/ScrumManagerWeb)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/petrepatrasc/ScrumManagerWeb/badges/quality-score.png?s=e43dcaba3f014330813fe1583a9a17ffd5cefe85)](https://scrutinizer-ci.com/g/petrepatrasc/ScrumManagerWeb/)

Scrum Manager is designed to be an open-source application for managing the Scrum meetings within a team. The project is very early on in its lifecycle, and development is still quite a way from finishing.

The technical challenge of the project is to expose an API that would allow for information to be read and manipulated through: a website, a desktop application, and a mobile application. Information would sync between the devices, and they would all be able to interact together.

## Changelog ##

Changelog information is kept here.

### 13 October, 2013 - Email API and Translations
    1. Enabled an internal notifications system that allows for messages to be sent between users and from the system.
    2. Enabled internationalisation support and translated the strings currently defined in the system.
    3. Focusing too much on delivering the API experience for the user ruins some of the overview - will focus on models for now, and return to controllers when application is more scalable.

### 22 September, 2013 - User API ###
    1. Created User entity with required fields.
    2. Implemented the following actions:

      a. Register
      b. Login
      c. RetrieveOne
      d. RetrieveAll
      e. Delete
      f. Update
      g. ResetPassword
      h. NewPassword
      i. RetrieveAll
      j. ChangePassword

    3. Created repository and service class.
    4. Created debug forms for easier functional testing.
    5. Automatic testing.
