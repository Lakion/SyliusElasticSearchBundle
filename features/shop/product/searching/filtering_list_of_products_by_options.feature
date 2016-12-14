@searching_products @elastic_search
Feature: Filtering list of products by options
    In order to see more specific list of the products
    As an Visitor
    I want to be able to filter the products

    Background:
        Given the store operates on a single channel in "United States"
        And the store classifies its products as "T-shirts"
        And the store has a lot of "T-shirts" with different color 3 of them are red
        And the store has a lot of "Hoodies" with black, gray, red color 4 of them are blue

    @ui
    Scenario: Filtering products by their color
        When I view newest products from taxon "T-shirts"
        And I filter them by red color
        Then I should see 3 products in the list

    @ui
    Scenario: Filtering product by their color
        When I view newest products from taxon "Hoodies"
        And I filter them by blue color
        Then I should see 4 products in the list

    @ui
    Scenario: Being able to filter only by existing options
        When I view newest products from taxon "Hoodies"
        Then I should be able to filter by green, red, black and blue color
