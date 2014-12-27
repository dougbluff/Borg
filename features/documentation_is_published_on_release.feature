Feature: Documentation is published on release
  In order to help newcomers start using behat
  As a documentation contributor
  I want documentation to be published after we release new version of it

  Rules:
    - Documentation for Behat should be published
    - Documentation for both versions should be published

  @critical
  Scenario: Publishing behat 3.0 documentation
    Given "Behat/docs" version v3.0 was documented
    When I release "Behat/docs" version v3.0
    Then the documentation for "Behat/docs" version v3.0 should have been published

  Scenario: Publishing both behat 2.5 and 3.0 documentation
    Given "Behat/docs" version v2.5 was documented
    And "Behat/docs" version v3.0 was documented
    When I release "Behat/docs" version v2.5
    And I release "Behat/docs" version v3.0
    Then the documentation for "Behat/docs" version v2.5 should have been published
    And the documentation for "Behat/docs" version v3.0 should have been published