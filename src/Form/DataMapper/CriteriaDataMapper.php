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
        $filtering = $data instanceof Criteria ? ['per_page' => $data->getPaginating()->getItemsPerPage()] : [];

        foreach ($forms as $form) {
            if (null !== $form->getData()) {
                $filtering[] = $form->getData();
            }
        }

        $data = Criteria::fromQueryParameters(
            $data->getResourceAlias(),
            array_merge(
                $filtering,
                $data instanceof Criteria ? $data->getFiltering()->getFields() : []
            )
        );
    }
}
