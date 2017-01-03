@searching_products @elastic_search
Feature: Filtering list of products by options
    In order to see more specific list of the products
    As an Visitor
    I want to be able to filter the products

    Background:
        Given the store operates on a single channel in "United States"
        And the store classifies its products as "T-shirts"
        And the store has a product option "color" with a code "t_shirt_color" and "red", "blue" and "yellow" values
        And the store has a lot of "T-shirts" with different color 3 of them are red
        And the store has a lot of Hoodies with different color 5 of them are blue
        And the store has a lot of Jeans with different color 10 of them are yellow

    @domain
    Scenario: Filtering products by their color
        When I filter them by red color
        Then I should see 3 products in the list

    @domain
    Scenario: Filtering product by their color
        When I filter them by red and blue color
        Then I should see 8 products in the list

    @domain
    Scenario: Filtering product by their color
        When I filter them by red blue and yellow color
        Then I should see 18 products in the list
