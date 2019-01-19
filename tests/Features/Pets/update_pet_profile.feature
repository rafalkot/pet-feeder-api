Feature: Registering a pet

  Background:
    Given the database is empty
    And there is a person "person1" with email "person1@example.com" and password "password1"
    And there is a person "person2" with email "person2@example.com" and password "password2"
    And there is a "cat" "Pet1" identified by "60a803b5-d672-4bcc-b3a3-725f2eccfb94" belonging to "person1"
    And there is a "cat" "Pet2" identified by "d1c74784-6c41-4558-be32-07b6ad466a39" belonging to "person2"
    And I am authenticated as "person1"
    And I add "content-type" header equal to "application/json"

  Scenario: Updating profile
    When I send a PUT request to "/api/pets/60a803b5-d672-4bcc-b3a3-725f2eccfb94" with body:
    """
      {"gender": "m", "birth_year": 2018}
    """
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "id" should exist
    Then the JSON node "gender" should be equal to "m"
    Then the JSON node "birth_year" should be equal to "2018"
    Then print response

  Scenario: Updating profile with empty data
    When I send a PUT request to "/api/pets/60a803b5-d672-4bcc-b3a3-725f2eccfb94" with body:
    """
      {"gender": null, "birth_year": null}
    """
    Then print response
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "id" should exist
    Then the JSON node "gender" should be equal to "null"
    Then the JSON node "birth_year" should be equal to "null"

  Scenario: Updating profile with invalid data
    When I send a PUT request to "/api/pets/60a803b5-d672-4bcc-b3a3-725f2eccfb94" with body:
    """
      {"gender": "invalid_gender", "birth_year": 2100}
    """
    Then the response should be in JSON
    Then the response status code should be 400
    Then the JSON node "errors" should exist
    Then the JSON node "errors[0].field" should contain "gender"
    Then the JSON node "errors[1].field" should contain "birth_year"
    Then print response

  Scenario: Updating profile of non owned pet
    When I send a PUT request to "/api/pets/d1c74784-6c41-4558-be32-07b6ad466a39" with body:
    """
      {"gender": "m", "birth_year": 2018}
    """
    Then the response status code should be 404

  Scenario: Updating profile of non existing pet
    When I send a PUT request to "/api/pets/XXX" with body:
    """
      {"gender": "m", "birth_year": 2018}
    """
    Then the response status code should be 404
