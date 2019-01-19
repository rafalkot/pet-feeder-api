@skip
Feature: Getting a household's data

  Background:
    Given the database is empty
    And there is a person "person1" with email "person1@example.com" and password "password1"
    And there is a person "person2" with email "person2@example.com" and password "password2" identified by "fbc05684-ea46-4541-965f-9eac49f77b16"
    And I am authenticated as "person1"
    And I add "content-type" header equal to "application/json"
    And there is a household "Home" identified by "fbc05684-ea46-4541-965f-9eac49f77b16" belonging to "person1"

  Scenario: Inviting a person to household
    When I send a POST request to "/api/households/fbc05684-ea46-4541-965f-9eac49f77b16/invitations" with body:
    """
      {"person_id": "fbc05684-ea46-4541-965f-9eac49f77b16"}
    """
    Then the response status code should be 201

  Scenario: Trying to invite a person to household twice
    When I send a POST request to "/api/households/fbc05684-ea46-4541-965f-9eac49f77b16/invitations" with body:
    """
      {"person_id": "fbc05684-ea46-4541-965f-9eac49f77b16"}
    """
    And I am authenticated as "person1"
    And I send a POST request to "/api/households/fbc05684-ea46-4541-965f-9eac49f77b16/invitations" with body:
    """
      {"person_id": "fbc05684-ea46-4541-965f-9eac49f77b16"}
    """
    Then the response should be in JSON
    Then the response status code should be 400

  Scenario: Trying invite to a household with invalid ID
    When I send a POST request to "/api/households/XXXXXX/invitations" with body:
    """
      {"person_id": "fbc05684-ea46-4541-965f-9eac49f77b16"}
    """
    Then the response should be in JSON
    Then the response status code should be 404

  Scenario: Trying invite to a household a person with invalid ID
    When I send a POST request to "/api/households/fbc05684-ea46-4541-965f-9eac49f77b16/invitations" with body:
    """
      {"person_id": "XXXXXX"}
    """
    Then the response should be in JSON
    Then the response status code should be 400
