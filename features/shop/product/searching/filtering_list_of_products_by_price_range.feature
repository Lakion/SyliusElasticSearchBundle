@searching_products
Feature: Filtering list of products by price range
    In order to see more specific list of the products
    As an Visitor
    I want to be able to filter the products

    Background:
        Given the store operates on a single channel in "United States"
        And the store has 10 Mugs, 20 Stickers and 25 Books

    @todo
    Scenario: Filtering products by price range
        When I filter them by price between 100 and 200
        Then I should see 5 products on the list
