<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Form\Type;

use Lakion\SyliusElasticSearchBundle\Form\Configuration\FilterSet;
use Lakion\SyliusElasticSearchBundle\Form\DataMapper\CriteriaDataMapper;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class FilterSetType extends AbstractType
{
    /**
     * @var FormTypeRegistryInterface
     */
    private $filterTypeRegistry;

    /**
     * @var FilterSet[]
     */
    private $filterSets;

    /**
     * @param FormTypeRegistryInterface $filterTypeRegistry
     * @param FilterSet[] $filterSets
     */
    public function __construct(FormTypeRegistryInterface $filterTypeRegistry, array $filterSets)
    {
        $this->filterTypeRegistry = $filterTypeRegistry;

        foreach ($filterSets as $filterSetName => $filterSetConfiguration) {
            $this->filterSets[$filterSetName] = FilterSet::createFromConfiguration($filterSetName, $filterSetConfiguration);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->filterSets[$options['filter_set']]->getFilters() as $name => $filter) {
            $builder->add(
                $filter->getName(),
                $this->filterTypeRegistry->get('default', $filter->getType()),
                $filter->getOptions()
            );
        }

        $builder->setDataMapper(new CriteriaDataMapper());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('csrf_protection', false)
            ->setRequired('filter_set')
            ->setAllowedTypes('filter_set', 'string')
        ;
    }
}
