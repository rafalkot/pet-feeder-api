Feature: Removing a pet

  Background:
    Given the database is empty
    And there is a person "person1" with email "person1@example.com" and password "password1"
    And there is a person "person2" with email "person2@example.com" and password "password2"
    And there is a "cat" "Pet1" identified by "60a803b5-d672-4bcc-b3a3-725f2eccfb94" belonging to "person1"
    And there is a "cat" "Pet2" identified by "d1c74784-6c41-4558-be32-07b6ad466a39" belonging to "person2"
    And I am authenticated as "person1"
    And I add "content-type" header equal to "application/json"

  Scenario: Removing a pet
    When I send a DELETE request to "/api/pets/60a803b5-d672-4bcc-b3a3-725f2eccfb94"
    Then the response status code should be 200

  Scenario: Removing profile of non owned pet
    When I send a DELETE request to "/api/pets/d1c74784-6c41-4558-be32-07b6ad466a39"
    Then the response status code should be 404

  Scenario: Removing profile of non existing pet
    When I send a DELETE request to "/api/pets/XXX"
    Then the response status code should be 404
