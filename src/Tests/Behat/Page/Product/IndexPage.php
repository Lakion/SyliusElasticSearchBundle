<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Tests\Behat\Page\Product;

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
    public function filter(Criteria $criteria)
    {
        $filterFiledName = key($criteria->getFiltering()->getFields());

        foreach ($criteria->getFiltering()->getFields()[$filterFiledName] as $type => $value) {
            $filterType = $this->getElement('filter_option', [
                '%filter_type%' => $type,
                '%filter_field_name%' => $filterFiledName,
                '%filter_value%' => sprintf('%s_%s', $type, $value)
            ]);

            $filterType->check();
        }

        $this->getDocument()->pressButton('Filter');
    }

    /**
     * {@inheritdoc}
     */
    public function getAllProducts()
    {
        $productElements = $this->getElement('products')->findAll('css', 'li');

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
            'filter_option' => '#filter_set_product_options_%filter_type%_%filter_field_name%_%filter_value%',
            'products' => '#products',
        ]);
    }
}
