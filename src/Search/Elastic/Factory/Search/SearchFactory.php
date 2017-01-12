<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Search;

use ONGR\ElasticsearchDSL\Search;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class SearchFactory implements SearchFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return new Search();
    }
}
