Feature: Removing a task

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

  Scenario: Removing a task
    When I send a DELETE request to "/api/tasks/9de30546-0b78-4671-b939-25522741d6bc"
    Then the response status code should be 200

  Scenario: Removing a task of other person
    When I send a DELETE request to "/api/tasks/d1c74784-6c41-4558-be32-07b6ad466a39"
    Then the response status code should be 404