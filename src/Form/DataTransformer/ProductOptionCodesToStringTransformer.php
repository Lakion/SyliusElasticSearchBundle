<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Form\DataTransformer;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Product\Model\ProductOptionValueInterface;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductOptionCodesToStringTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($values)
    {
        if ($values['product_option_code'] instanceof Collection) {
            $values = $values['product_option_code']->toArray();
        }

        return implode('+', array_map(function (ProductOptionValueInterface $productOptionValue) {
            return $productOptionValue->getCode();
        }, $values));
    }
}
