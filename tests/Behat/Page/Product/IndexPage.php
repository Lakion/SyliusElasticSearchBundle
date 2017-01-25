<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Lakion\SyliusElasticSearchBundle\Behat\Page\Product;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Sylius\Behat\Page\SymfonyPage;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class IndexPage extends SymfonyPage implements IndexPageInterface
{
    /**
     * {@inheritdoc}
     */
    public function filterByProductOptions(Criteria $criteria)
    {
        foreach ($criteria->getFiltering()->getFields() as $type => $value) {
            $filterType = $this->getElement('filter_option', [
                '%filter_type%' => $type,
                '%filter_value%' => sprintf('%s_%s', $type, $value)
            ]);

            $filterType->check();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function filterByPriceRange($graterThan, $lessThan)
    {
        $this->getElement('filter_price_range_grater_than')->setValue($graterThan / 100);
        $this->getElement('filter_price_range_less_than')->setValue($lessThan / 100);
    }

    public function filter()
    {
        $this->getDocument()->pressButton('Filter');
    }

    /**
     * {@inheritdoc}
     */
    public function setPaginating($perPage)
    {
        $this->getElement('pagination')->selectOption($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function search($phrase)
    {
        $this->getElement('search')->setValue($phrase);
        $this->getElement('search_submit')->press();
    }

    /**
     * {@inheritdoc}
     */
    public function getAllProducts()
    {
        $productElements = $this->getElement('products')->findAll('css', 'div .column > div .content > a');

        return $productElements;
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'lakion_elastic_search_shop_product_index';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'filter_option' => '#filter_set_%filter_type%_code_%filter_value%',
            'search' => '#filter_set_search',
            'search_submit' => '#search_submit',
            'filter_price_range_grater_than' => '#filter_set_product_price_grater_than',
            'filter_price_range_less_than' => '#filter_set_product_price_less_than',
            'products' => '#products',
            'pagination' => '#pagination',
        ]);
    }
}
