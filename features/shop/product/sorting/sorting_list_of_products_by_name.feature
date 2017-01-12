@searching_products
Feature: Sorting list of products by name
    In order to see more sorted list of the products
    As an Visitor
    I want to be able to sort the products

    Background:
        Given the store operates on a channel named "Europe"
        And the store also operates on another channel named "Mobile"
        And the store also operates on another channel named "Tablets"
        And the store has a product "Banana t-shirt" priced at "$100" in "Tablets" channel
        And the store also has a product "Star wars t-shirt" priced at "$150" in "Mobile" channel
        And the store also has a product "LOTR t-shirt" priced at "$300" in "Mobile" channel
        And the store also has a product "Breaking bad t-shirt" priced at "$50" in "Mobile" channel
        And the store also has a product "Westworld t-shirt" priced at "$1000" in "Europe" channel
        And the store also has a product "Orange t-shirt" priced at "$1000" in "Europe" channel

    @domain @ui
    Scenario: Sorting products by name in ascending order
        When I sort them by name in ascending order
        Then I should see products in order like "Banana t-shirt", "Breaking bad t-shirt", "LOTR t-shirt", "Orange t-shirt", "Star wars t-shirt", "Westworld t-shirt"

    @domain @ui
    Scenario: Sorting products by name in descending order
        When I sort them by name in descending order
        Then I should see products in order like "Westworld t-shirt", "Star wars t-shirt", "Orange t-shirt", "LOTR t-shirt", "Breaking bad t-shirt", "Banana t-shirt"
