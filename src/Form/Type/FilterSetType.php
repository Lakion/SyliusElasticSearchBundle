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

use Lakion\SyliusElasticSearchBundle\Exception\FilterSetConfigurationNotFoundException;
use Lakion\SyliusElasticSearchBundle\Form\Configuration\Provider\FilterSetProviderInterface;
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
     * @var FilterSetProviderInterface
     */
    private $filterSetConfigurationProvider;

    /**
     * @param FormTypeRegistryInterface $filterTypeRegistry
     * @param FilterSetProviderInterface $filterSetConfigurationProvider
     */
    public function __construct(
        FormTypeRegistryInterface $filterTypeRegistry,
        FilterSetProviderInterface $filterSetConfigurationProvider
    ) {
        $this->filterTypeRegistry = $filterTypeRegistry;
        $this->filterSetConfigurationProvider = $filterSetConfigurationProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        try {
            $filters = $this->filterSetConfigurationProvider->getFilterSetConfiguration($options['filter_set'])->getFilters();
        } catch (FilterSetConfigurationNotFoundException $configurationNotFoundException) {
            $filters = $this->filterSetConfigurationProvider->getFilterSetConfiguration('default')->getFilters();
        }

        foreach ($filters as $filter) {
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
