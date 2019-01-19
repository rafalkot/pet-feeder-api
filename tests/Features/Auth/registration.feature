Feature: Registration
  In order to use API
  As a guest user
  I need to register

  Background:
    Given the database is empty
    And there is a person "person1" with email "person1@example.com" and password "password1"
    And I add "content-type" header equal to "application/json"

  Scenario: Successful registration
    When I send a POST request to "/api/register" with body:
    """
      {"username": "person2", "password": "password2", "email": "person2@example.com"}
    """
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "token" should exist
    Then the JSON node "person_id" should exist

  Scenario: Trying to register an account with existing email
    When I send a POST request to "/api/register" with body:
    """
      {"username": "person2", "password": "password2", "email": "person1@example.com"}
    """
    Then the response should be in JSON
    Then the response status code should be 400
    Then the JSON node "errors" should exist
    Then the JSON node "errors[0].field" should contain "email"

  Scenario: Trying to register an account with existing username
    When I send a POST request to "/api/register" with body:
    """
      {"username": "person1", "password": "password2", "email": "person2@example.com"}
    """
    Then the response should be in JSON
    Then the response status code should be 400
    Then the JSON node "errors" should exist
    Then the JSON node "errors[0].field" should contain "username"

  Scenario: Trying to register an account as logged user
    Given I am authenticated as "person1"
    When I send a POST request to "/api/register" with body:
    """
      {"username": "person2", "password": "password2", "email": "person2@example.com"}
    """
    Then the response should be in JSON
    Then the response status code should be 403
