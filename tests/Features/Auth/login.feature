Feature: Login
  In order to get API token
  As a registered but not authenticated person
  I need to login

  Background:
    Given the database is empty
    And there is a person "person1" with email "person1@example.com" and password "password1"
    And I add "content-type" header equal to "application/json"

  Scenario: Successful login with correct credentials
    When I send a POST request to "/api/login" with body:
    """
      {"username": "person1", "password": "password1"}
    """
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "token" should exist

  Scenario: Trying to login with bad password
    When I send a POST request to "/api/login" with body:
    """
      {"username": "person1", "password": "bad password"}
    """
    Then the response should be in JSON
    Then the response status code should be 401

  Scenario: Trying to login with bad username and password
    When I send a POST request to "/api/login" with body:
    """
      {"username": "bad username", "password": "bad password"}
    """
    Then the response should be in JSON
    Then the response status code should be 401