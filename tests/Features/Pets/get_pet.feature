Feature: Getting a pets's data

  Background:
    Given the database is empty
    And there is a person "person1" with email "person1@example.com" and password "password1"
    And there is a person "person2" with email "person2@example.com" and password "password2"
    And I am authenticated as "person1"
    And I add "content-type" header equal to "application/json"
    And there is a "cat" "Pet1" identified by "fbc05684-ea46-4541-965f-9eac49f77b16" belonging to "person1"
    And there is a "cat" "Pet2" identified by "d1c74784-6c41-4558-be32-07b6ad466a39" belonging to "person1"
    And there is a "cat" "Pet2" identified by "deb32551-d139-4c20-9114-3f2b356ff03c" belonging to "person2"

  Scenario: Getting a pet
    When I send a GET request to "/api/pets/fbc05684-ea46-4541-965f-9eac49f77b16"
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "id" should be equal to "fbc05684-ea46-4541-965f-9eac49f77b16"
    Then the JSON node "name" should be equal to "Pet1"
    Then the JSON node "type" should be equal to "cat"

  Scenario: Getting a pets list
    When I send a GET request to "/api/pets"
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "root" should have 2 elements
    Then the JSON node "[0].id" should be equal to "fbc05684-ea46-4541-965f-9eac49f77b16"
    Then the JSON node "[0].name" should be equal to "Pet1"
    Then the JSON node "[0].type" should be equal to "cat"
    Then the JSON node "[1].id" should be equal to "d1c74784-6c41-4558-be32-07b6ad466a39"
    Then the JSON node "[1].name" should be equal to "Pet2"
    Then the JSON node "[1].type" should be equal to "cat"

  Scenario: Trying to get a pet of another person
    When I send a GET request to "/api/pets/deb32551-d139-4c20-9114-3f2b356ff03c"
    Then the response should be in JSON
    Then the response status code should be 404

  Scenario: Trying to get a pet with invalid ID
    When I send a GET request to "/api/pets/XXXXXX"
    Then the response should be in JSON
    Then the response status code should be 404
