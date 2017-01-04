@searching_products
Feature: Filtering list of products by options
    In order to see more specific list of the products
    As an Visitor
    I want to be able to filter the products

    Background:
        Given the store has about 10 Mugs, 20 Stickers and 25 Books

    @domain
    Scenario: Filtering products by their type
        When I filter them by double mug type
        Then I should see 10 products on the list

    @domain
    Scenario: Filtering product by their type or size
        When I filter them by double mug type or sticker size 7
        Then I should see 30 products on the list

    @domain
    Scenario: Filtering product by their size
        When I filter them by stickier size 7
        Then I should see 20 products on the list

    @domain
    Scenario: List of all products without filtering
        When I view the list of the products without filtering
        Then I should see 55 products on the list
