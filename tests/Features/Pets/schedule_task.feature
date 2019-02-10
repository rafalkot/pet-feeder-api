Feature: Scheduling a task

  Background:
    Given the database is empty
    And there is a person "person1" with email "person1@example.com" and password "password1"
    And there is a person "person2" with email "person2@example.com" and password "password2"
    And there is a "cat" "Pet1" identified by "60a803b5-d672-4bcc-b3a3-725f2eccfb94" belonging to "person1"
    And there is a "cat" "Pet2" identified by "d1c74784-6c41-4558-be32-07b6ad466a39" belonging to "person2"
    And there is a task with following details:
      | pet_id                               | task_id                              | task_name | task_hour | task_recurrence |
      | 60a803b5-d672-4bcc-b3a3-725f2eccfb94 | 9de30546-0b78-4671-b939-25522741d6bc | Walk      | 08:00:00  | FREQ=DAILY      |
      | d1c74784-6c41-4558-be32-07b6ad466a39 | d1c74784-6c41-4558-be32-07b6ad466a39 | Walk      | 09:00:00  | FREQ=DAILY      |
    And I am authenticated as "person1"
    And I add "content-type" header equal to "application/json"

  Scenario: Scheduling a task
    When I send a POST request to "/api/tasks" with body:
    """
      {
        "pet_id": "60a803b5-d672-4bcc-b3a3-725f2eccfb94",
        "name": "Walk",
        "hour": "08:00:00",
        "time_zone": "Europe\/Warsaw",
        "recurrence": "FREQ=DAILY"
      }
    """
    Then print response
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "id" should exist
    Then the JSON node "name" should be equal to "Walk"
    Then the JSON node "time_zone" should be equal to "Europe/Warsaw"
    Then the JSON node "recurrence" should be equal to "FREQ=DAILY"
    Then the JSON node "hour" should be equal to "08:00:00"
    Then the JSON node "pet.id" should be equal to "60a803b5-d672-4bcc-b3a3-725f2eccfb94"
    Then the JSON node "pet.name" should be equal to "Pet1"

  Scenario: Scheduling a task for pet of other person
    When I send a POST request to "/api/tasks" with body:
    """
      {
        "pet_id": "d1c74784-6c41-4558-be32-07b6ad466a39",
        "name": "Walk",
        "hour": "08:00:00",
        "time_zone": "Europe\/Warsaw",
        "recurrence": "FREQ=DAILY"
      }
    """
    Then print response
    Then the response should be in JSON
    Then the response status code should be 400
    Then the JSON node "errors" should exist
    Then the JSON node "errors[0].field" should contain "pet_id"

  Scenario: Completing a task
    When I send a PUT request to "/api/scheduledtasks/9de30546-0b78-4671-b939-25522741d6bc" with body:
    """
      {
        "completed": true
      }
    """
    Then the response status code should be 200

  Scenario: Incompleting a task
    Given I send a PUT request to "/api/scheduledtasks/9de30546-0b78-4671-b939-25522741d6bc" with body:
    """
      {
        "completed": true
      }
    """
    And I am authenticated as "person1"
    And I add "content-type" header equal to "application/json"
    When I send a PUT request to "/api/scheduledtasks/9de30546-0b78-4671-b939-25522741d6bc" with body:
    """
      {
        "completed": false
      }
    """
    Then the response status code should be 200

  Scenario: Completing a task of another person
    Given I send a PUT request to "/api/scheduledtasks/d1c74784-6c41-4558-be32-07b6ad466a39" with body:
    """
      {
        "completed": true
      }
    """
    Then the response status code should be 404
