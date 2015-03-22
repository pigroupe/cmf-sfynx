# language: en
@mink:my_session_selenium
Feature: I would like to log in to the system

    Background: Log in as admin
      Given I am on "/en/"
       Then the response should contain "form-connexion"
        And I fill in "_username" with "admin"
        And I fill in "_password" with "admin"
        And I press "OK"
       When I wait for 4 seconds

#    Scenario: Create a new cmf page
#      Given I am logged as "admin"
#        And I click on ".menu-xp"
#       When I wait for 2 seconds
#        And I click on ".page_action_copy"
#       When I wait for 2 seconds
#       Then I register the new page

    Scenario: Create a new cmf page
      Given I am logged as "admin"
       Then I click on the main menu
        And I click to copy the page
        And I register the new page

#    Scenario: Edit the new cmf page
#      Given I go to the new page
#        And I click on ".menu-xp"
#       When I wait for 2 seconds
#        And I click on ".page_action_edit"
#       When I wait for 6 seconds
#       Then I switch to iframe "modalIframeId"
#       When I click on the element with xpath "//*[contains(@id,'tabs')]//form[@class='myform']//div[@id='piapp_adminbundle_pagetype']//fieldset//div[4]//button"
#       When I wait for 2 seconds
#       Then I switch to main window
#       Then I switch to iframe "modalIframeId"
#       When I click on the element with xpath "//body//label[contains(@for,'ui-multiselect-piapp_adminbundle_pagetype_layout-option-13')]//span"
#       When I wait for 2 seconds
#        And I press "Save"
#       When I wait for 2 seconds
#       Then I switch to main window
#       When I wait for 2 seconds   
#       When I click on the element with xpath "//body//button[contains(@class,'ui-dialog-titlebar-close')]//span"
#       When I wait for 2 seconds 
#       Then I register the new page 

    Scenario: Edit the new cmf page
      Given I go to the new page
       Then I click on the main menu
        And I click to edit the page
       Then I click to the layout select field from the edit page
        And I select the new layout "13" from the edit page
       Then I save the edit page form
        And I close the edit form
        And I register the new page  

#    Scenario: Create a new bloc
##      Given I am on "/en/copy/1426763948"
#      Given I go to the new page
#       Then I click on the main menu
#        And I click to show the structure of the page
#       When I click on the element with xpath "//body//sfynx[@data-name='content']//span[@class='ui-dialog-title']"
#       When I wait for 1 seconds
#       When I click on the element with xpath "//body//sfynx[@data-name='content']//a[@class='block_action_import']"
#       When I wait for 5 seconds
#       Then I switch to iframe "modalIframeId"
#       When I click on the element with css selector "span#behatFormBuilderWidgetBlock"
#       When I wait for 2 seconds
#       Then I switch to iframe "modalIframeId"
#        And I click on "input#piappgedmobundlemanagerformbuilderpimodelwidgetblock_choice_1"
#       When I wait for 2 seconds
#        And I press "Save"
#       When I wait for 2 seconds
#       Then I switch to main window

    Scenario: Create a new bloc
#     Given I am on "/en/copy/1427036946"
      Given I go to the new page
       Then I click on the main menu
        And I click to show the structure of the page
       Then I click to edit the widget handler from the "content" Zone
        And I click to the block widget edit form from the widget handler
       Then I create a new block with "My title block" title and "My description" descriptif and "Block description with image on the left" template
        And I close the edit form
      Given I go to the new page
