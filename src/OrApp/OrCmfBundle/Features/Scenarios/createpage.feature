# language: en
@mink:my_session_selenium
Feature: I would like to log in to the system

    Background: Log in as admin
      Given I am on "/en/"
       Then the response should contain "form-connexion"
        And I fill in "_username" with "admin"
        And I fill in "_password" with "admin"
        And I press "OK"
       When I wait for 3 seconds
       Then I should see "admin"
#
#    Scenario: Create a new cmf page
#      Given I am logged as "admin"
#       When I wait for 2 seconds
#        And I click on ".menu-xp"
#       When I wait for 2 seconds
#        And I click on ".page_action_copy"
#       When I wait for 2 seconds
#       Then I should see "admin"
#       Then I register the new page

    Scenario: Edit the new cmf page
      Given I go to the new page
       When I wait for 2 seconds
        And I click on ".menu-xp"
       When I wait for 2 seconds
        And I click on ".page_action_edit"
       When I wait for 4 seconds
       Then I should see "admin"
      Given I click on the element with the following xpath '//div[@id="piapp_adminbundle_pagetype"]//select[@id="piapp_adminbundle_pagetype_layout"]//button[@class="ui-multiselect"]'
   