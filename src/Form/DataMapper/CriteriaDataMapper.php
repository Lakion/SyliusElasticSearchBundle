<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Form\DataMapper;

use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Symfony\Component\Form\DataMapperInterface;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class CriteriaDataMapper implements DataMapperInterface
{
    /**
     * {@inheritdoc}
     */
    public function mapDataToForms($data, $forms)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);
        $filtering = [];
        foreach ($forms as $form) {
            $filtering[$form->getConfig()->getOption('key')] = $form->getData();
        }

        $data = Criteria::fromQueryParameters($data->getResourceAlias(), $filtering);
    }
}
