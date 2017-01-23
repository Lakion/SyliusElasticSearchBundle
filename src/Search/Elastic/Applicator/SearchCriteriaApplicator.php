<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Search\Elastic\Applicator;

use ONGR\ElasticsearchDSL\Search;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
abstract class SearchCriteriaApplicator implements SearchCriteriaApplicatorInterface
{
    /**
     * {@inheritDoc}
     */
    public function apply($criteria, Search $search)
    {
        $method = $this->getApplyMethod($criteria);

        if (!method_exists($this, $method)) {
            throw new \RuntimeException();
        }

        $this->$method($criteria, $search);
    }

    /**
     * @param mixed $criteria
     *
     * @return string
     */
    private function getApplyMethod($criteria)
    {
        if (!is_object($criteria)) {
            throw new \RuntimeException();
        }

        $classParts = explode('\\', get_class($criteria));

        return 'apply' . end($classParts);
    }
}
