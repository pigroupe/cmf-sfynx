# language: en
@mink:my_session_selenium
Feature: I would like to log in to the system

    Background: Anonymous access to login page
      Given I am logged as "anonymous"
        And I go to "/login"

   