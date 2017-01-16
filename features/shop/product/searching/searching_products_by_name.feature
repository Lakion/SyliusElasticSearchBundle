@searching_products
Feature: Searching products by name
    In order to see more specific list of the products
    As an Visitor
    I want to be able to filter the products

    Background:
        Given the store operates on a channel named "Europe"
        And the store has a product "Banana T-Shirt"
        And the store has a product "Star Wars T-Shirt"
        And the store has a product "Star Wars Rogue T-Shirt"
        And the store has a product "Breaking Bad Saul T-Shirt"
        And the store has a product "Breaking Bad Mike T-Shirt"
        And the store has a product "Breaking Bad Jesse T-Shirt"
        And the store has a product "Breaking Bad Heisenberg T-Shirt"
        And the store has a product "Westworld T-Shirt"
        And the store has a product "Westworld Hosts T-Shirt"
        And the store has a product "Westworld Hopkins T-Shirt"

    @domain @ui
    Scenario: Searching products by name
        When I search for products with name "Banana"
        Then I should see 1 products on the list
        And It should be "Banana T-Shirt"

    @domain @ui
    Scenario: Searching products by name
        When I search for products with name "Star"
        Then I should see 2 products on the list
        And It should be "Star Wars T-Shirt", "Star Wars Rogue T-Shirt"

    @domain @ui
    Scenario: Searching products by name
        When I search for products with name "Bad"
        Then I should see 4 products on the list
        And It should be "Breaking Bad Saul T-Shirt", "Breaking Bad Mike T-Shirt", "Breaking Bad Jesse T-Shirt", "Breaking Bad Heisenberg T-Shirt"

    @domain @ui
    Scenario: Searching products by name
        When I search for products with name "Westworld"
        Then I should see 3 products on the list
        And It should be "Westworld T-Shirt", "Westworld Hosts T-Shirt", "Westworld Hopkins T-Shirt"

    @domain @ui
    Scenario: Searching products by name
        When I search for products with name "Hopkins"
        Then I should see 1 products on the list
        And It should be "Westworld Hopkins T-Shirt"

    @domain @ui
    Scenario: Searching products by name
        When I search for products with name "T-Shirt"
        Then I should see 10 products on the list
