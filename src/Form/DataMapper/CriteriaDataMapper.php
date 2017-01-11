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

        $filtering = ['product_option_code' => ''];
        foreach ($forms as $form) {
            $filtering['product_option_code'] .= $form->getData().'+';
        }
        $filtering['product_option_code'] = rtrim($filtering['product_option_code'], '+');

        $data = Criteria::fromQueryParameters($data->getResourceAlias(), $filtering);
    }
}
