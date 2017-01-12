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

use Lakion\SyliusElasticSearchBundle\Form\DataTransformer\RecursiveMergeProductOptions;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Component\Product\Model\ProductOptionValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductOptionValuesFilterType extends AbstractType
{
    /**
     * @var FormTypeRegistryInterface
     */
    private $filterRegistry;

    /**
     * @param FormTypeRegistryInterface $filterRegistry
     */
    public function __construct(FormTypeRegistryInterface $filterRegistry)
    {
        $this->filterRegistry = $filterRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['codes'] as $code) {
            $builder->add($code, $this->filterRegistry->get('default', 'option'), ['code' => $code, 'class' => $options['class']]);
        }

        $builder->addModelTransformer(new RecursiveMergeProductOptions());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('class', ProductOptionValue::class)
            ->setAllowedTypes('class', 'string')
            ->setRequired('codes')
            ->setAllowedTypes('codes', 'array')
            ->setRequired('filtering_key')
            ->setAllowedTypes('filtering_key', 'string')
        ;
    }
}
