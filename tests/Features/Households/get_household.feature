@skip
Feature: Getting a household's data

  Background:
    Given the database is empty
    And there is a person "person1" with email "person1@example.com" and password "password1"
    And I am authenticated as "person1"
    And I add "content-type" header equal to "application/json"
    And there is a household "Home" identified by "fbc05684-ea46-4541-965f-9eac49f77b16" belonging to "person1"

  Scenario: Getting a household
    When I send a GET request to "/api/households/fbc05684-ea46-4541-965f-9eac49f77b16"
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "id" should exist
    Then the JSON node "name" should exist

  Scenario: Trying to get a household with invalid ID
    When I send a GET request to "/api/households/XXXXXX"
    Then the response should be in JSON
    Then the response status code should be 404
