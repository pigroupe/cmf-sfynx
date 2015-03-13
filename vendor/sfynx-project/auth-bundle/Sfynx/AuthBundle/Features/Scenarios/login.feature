# language: en
@mink:my_session_selenium
Feature: I would like to log in to the system

    Background: Anonymous access to login page
      Given I am logged as "anonymous"
        And I go to "/login"

    Scenario: Log in as admin
      Given I am on "/login"
       Then the response should contain "behatFormLogin"
       Then I should not see "Logged in as admin"
        And I fill in "username" with "admin"
        And I fill in "password" with "admin"
        And I press "Connexion"
       Then I should see "Logged in as admin"
       Then the response should not contain "behatFormLogin"
       When I wait for 2 seconds
        And I click on ".connexion-my-account"
       When I follow "behatLinkProfile"
       Then the response should contain "user_from"
       When I wait for 2 seconds
       And I click on ".connexion-my-account"
       When I follow "behatLinkUsers"
       Then the response should contain "grid_customer_wrapper"
       When I follow "logout"
       Then I should not see "Logged in as admin"
       Then the response should contain "form-connexion"

    Scenario: Unsuccessful login
      Given I go to "/login"
       Then the response should contain "behatFormLogin"
       Then I should not see "Logged in as admin"
        And I fill in "username" with "wrong username"
        And I fill in "password" with "wrong password"
        And I press "Connexion"
       Then I should see "Bad credentials"
       Then the response should contain "behatFormLogin"

    Scenario: Log in as user
      Given I am logged as "anonymous"
        And I go to "/en/"
       Then the response should contain "form-connexion"
       Then I should not see "Logged in as user"
        And I fill in "_username" with "user"
        And I fill in "_password" with "user"
        And I press "OK"
       When I wait for 2 seconds
       Then the response should contain "/logout"
       When I wait for 2 seconds
       When I follow "behatLinkLogout"
       Then the response should not contain "/logout"
       Then the response should contain "form-connexion"
