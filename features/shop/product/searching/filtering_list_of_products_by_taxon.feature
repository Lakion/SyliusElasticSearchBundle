@searching_products
Feature: Filtering list of products by taxon
    In order to see more specific list of the products
    As an Visitor
    I want to be able to filter the products

    Background:
        Given the store classifies its products as Mugs, Stickers and Books
        And the store has about 40 Mugs, 15 Stickers and 50 Books

    @domain
    Scenario: Filtering products by book
        When I filter them by "Books" taxon
        Then I should see 10 products on the list

    @domain
    Scenario: Filtering product by stickers
        When I filter them by "Stickers" taxon
        Then I should see 30 products on the list

    @domain
    Scenario: Filtering product by mugs
        When I filter them by "Mugs" taxon
        Then I should see 20 products on the list

    @domain
    Scenario: List of all products without filtering
        When I view the list of the products without filtering
        Then I should see 105 products on the list
