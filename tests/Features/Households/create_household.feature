@skip
Feature: Creating a household
  In order to manage pets
  As a authenticated user
  I need to create a household first

  Background:
    Given the database is empty
    And there is a person "person1" with email "person1@example.com" and password "password1"
    And I am authenticated as "person1"
    And I add "content-type" header equal to "application/json"

  Scenario: Creating a household
    When I send a POST request to "/api/households" with body:
    """
      {"name": "Name"}
    """
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "id" should exist
    Then the JSON node "name" should exist

  Scenario: Creating a household with empty name
    When I send a POST request to "/api/households" with body:
    """
      {"name": ""}
    """
    Then the response should be in JSON
    Then the response status code should be 400
    Then the JSON node "errors" should exist

  Scenario: Creating a household with invalid name
    When I send a POST request to "/api/households" with body:
    """
      {"name": "N"}
    """
    Then the response should be in JSON
    Then the response status code should be 400
    Then the JSON node "errors" should exist