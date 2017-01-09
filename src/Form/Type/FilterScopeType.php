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

use Lakion\SyliusElasticSearchBundle\Form\Configuration\FilterScope;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class FilterScopeType extends AbstractType
{
    /**
     * @var FormTypeRegistryInterface
     */
    private $filterTypeRegistry;

    /**
     * @var FilterScope[]
     */
    private $filterScopes;

    /**
     * @param FormTypeRegistryInterface $filterTypeRegistry
     * @param FilterScope[] $filterScopes
     */
    public function __construct(FormTypeRegistryInterface $filterTypeRegistry, array $filterScopes)
    {
        $this->filterTypeRegistry = $filterTypeRegistry;
        $this->filterScopes = $filterScopes;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->filterScopes[$options['filter_scope']]['filters'] as $name => $filter) {
            $builder->add(
                $name,
                get_class($this->filterTypeRegistry->get('default', $filter['type'])),
                $filter['options']
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('filter_scope')
            ->setAllowedTypes('filter_scope', 'string')
        ;
    }
}
