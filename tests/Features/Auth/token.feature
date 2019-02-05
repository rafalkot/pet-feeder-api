Feature: Token validation

  Background:
    Given the database is empty
    And there is a person "person1" with email "person1@example.com" and password "password1"
    And I add "content-type" header equal to "application/json"

  Scenario: Logged user
    When I am authenticated as "person1"
    And I send a GET request to "/api/auth/validateToken"
    Then the response status code should be 200

  Scenario: Guest user
    And I send a GET request to "/api/auth/validateToken"
    Then the response should be in JSON
    Then the response status code should be 401