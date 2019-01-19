Feature: Registering a pet

  Background:
    Given the database is empty
    And there is a person "person1" with email "person1@example.com" and password "password1"
    And there is a "cat" "Pet1" identified by "60a803b5-d672-4bcc-b3a3-725f2eccfb94" belonging to "person1"
    And I am authenticated as "person1"
    And I add "content-type" header equal to "application/json"

  Scenario: Registering a pet
    When I send a POST request to "/api/pets" with body:
    """
      {"name": "Name", "type": "cat"}
    """
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "id" should exist
    Then the JSON node "name" should be equal to "Name"
    Then the JSON node "type" should be equal to "cat"
    Then the JSON node "gender" should be equal to "null"
    Then the JSON node "birth_year" should be equal to "null"

  Scenario: Registering a pet with empty values
    When I send a POST request to "/api/pets" with body:
    """
      {"name": "", "type": ""}
    """
    Then print response
    Then the response should be in JSON
    Then the response status code should be 400
    Then the JSON node "errors" should exist
    Then the JSON node "errors[0].field" should contain "name"
    Then the JSON node "errors[1].field" should contain "type"

  Scenario: Registering a pet with invalid name
    When I send a POST request to "/api/pets" with body:
    """
      {"name": "N", "type": "cat"}
    """
    Then the response should be in JSON
    Then print response
    Then the response status code should be 400
    Then the JSON node "errors" should exist
    Then the JSON node "errors[0].field" should contain "name"

  Scenario: Registering a pet with invalid type
    When I send a POST request to "/api/pets" with body:
    """
      {"name": "Name", "type": "XXX"}
    """
    Then the response should be in JSON
    Then print response
    Then the response status code should be 400
    Then the JSON node "errors" should exist
    Then the JSON node "errors[0].field" should contain "type"

  Scenario: Registering a pet with existing name
    When I send a POST request to "/api/pets" with body:
    """
      {"name": "Pet1", "type": "cat"}
    """
    Then the response should be in JSON
    Then print response
    Then the response status code should be 400
    Then the JSON node "errors" should exist
    Then the JSON node "errors[0].field" should contain "name"
