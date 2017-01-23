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

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering\ProductHasOptionCodesFilter;
use Sylius\Component\Product\Model\ProductOptionValue;
use Sylius\Component\Product\Model\ProductOptionValueInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class OptionCodeFilterType extends AbstractType implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code', EntityType::class, [
            'class' => $options['class'],
            'choice_value' => function (ProductOptionValue $productOptionValue) {
                return $productOptionValue->getCode();
            },
            'query_builder' => function (EntityRepository $repository) use ($options) {
                $queryBuilder = $repository->createQueryBuilder('o');

                return $queryBuilder
                    ->leftJoin('o.option', 'option')
                    ->andWhere('option.code = :optionCode')
                    ->setParameter('optionCode', $options['option_code']);
            },
            'multiple' => true,
            'expanded' => true,
        ]);

        $builder->addModelTransformer($this);
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if (null  === $value) {
            return null;
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if ($value['code'] instanceof Collection) {
            $productOptionCodes = $value['code']->map(function (ProductOptionValueInterface $productOptionValue) {
                return $productOptionValue->getCode();
            });

            if ($productOptionCodes->isEmpty()) {
                return null;
            }

            return new ProductHasOptionCodesFilter($productOptionCodes->toArray());
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('class', ProductOptionValue::class)
            ->setRequired('option_code')
            ->setAllowedTypes('option_code', 'string')
        ;
    }
}
