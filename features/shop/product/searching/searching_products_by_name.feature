@searching_products
Feature: Searching products by name
    In order to see more specific list of the products
    As an Visitor
    I want to be able to filter the products

    Background:
        Given the store operates on a channel named "Europe"
        And the store has a product "Banana t-shirt"
        And the store has a product "Star wars t-shirt"
        And the store has a product "Star wars rogue t-shirt"
        And the store has a product "Breaking bad saul t-shirt"
        And the store has a product "Breaking bad mike t-shirt"
        And the store has a product "Breaking bad jesse t-shirt"
        And the store has a product "Breaking bad heisenberg t-shirt"
        And the store has a product "Westworld t-shirt"
        And the store has a product "Westworld hosts t-shirt"
        And the store has a product "Westworld hopkins t-shirt"

    @domain
    Scenario: Searching products by name
        When I search for products with name "Banana"
        Then I should see 1 products on the list
        And It should be "Banana t-shirt"

    @domain
    Scenario: Searching products by name
        When I search for products with name "Star"
        Then I should see 2 products on the list
        And It should be "Star wars t-shirt", "Star wars rogue t-shirt"

    @domain
    Scenario: Searching products by name
        When I search for products with name "bad"
        Then I should see 4 products on the list
        And It should be "Breaking bad saul t-shirt", "Breaking bad mike t-shirt", "Breaking bad jesse t-shirt", "Breaking bad heisenberg t-shirt"

    @domain
    Scenario: Searching products by name
        When I search for products with name "Westworld"
        Then I should see 3 products on the list
        And It should be "Westworld t-shirt", "Westworld hosts t-shirt", "Westworld hopkins t-shirt"

    @domain
    Scenario: Searching products by name
        When I search for products with name "hopkins"
        Then I should see 1 products on the list
        And It should be "Westworld hopkins t-shirt"

    @domain
    Scenario: Searching products by name
        When I search for products with name "t-shirt"
        Then I should see 10 products on the list
