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
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use FOS\ElasticaBundle\Repository;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering\ProductHasOptionCodesFilter;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Query\QueryFactoryInterface;
use Lakion\SyliusElasticSearchBundle\Search\Elastic\Factory\Search\SearchFactoryInterface;
use ONGR\ElasticsearchDSL\Aggregation\FiltersAggregation;
use ONGR\ElasticsearchDSL\Search;
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
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;

    /**
     * @var QueryFactoryInterface
     */
    private $productHasOptionCodeQueryFactory;

    /**
     * @var string
     */
    private $productModelClass;

    /**
     * @var SearchFactoryInterface
     */
    private $searchFactory;

    /**
     * @param RepositoryManagerInterface $repositoryManager
     * @param QueryFactoryInterface $productHasOptionCodeQueryFactory
     * @param SearchFactoryInterface $searchFactory
     */
    public function __construct(
        RepositoryManagerInterface $repositoryManager,
        QueryFactoryInterface $productHasOptionCodeQueryFactory,
        SearchFactoryInterface $searchFactory,
        $productModelClass
    ) {
        $this->repositoryManager = $repositoryManager;
        $this->productHasOptionCodeQueryFactory = $productHasOptionCodeQueryFactory;
        $this->searchFactory = $searchFactory;
        $this->productModelClass = $productModelClass;
    }

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
            'choice_label' => function (ProductOptionValue $productOptionValue) use ($options) {

                /** @var Repository $repository */
                $repository = $this->repositoryManager->getRepository($this->productModelClass);
                $query = $this->buildAggregation($productOptionValue->getCode())->toArray();
                $result = $repository->createPaginatorAdapter($query);
                $aggregation = $result->getAggregations();
                $count = $aggregation[$productOptionValue->getCode()]['buckets'][$productOptionValue->getCode()]['doc_count'];

                return sprintf('%s (%s)', $productOptionValue->getValue(), $count);
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

    /**
     * @param string $code
     *
     * @return Search
     */
    private function buildAggregation($code)
    {
        $hasOptionValueAggregation = new FiltersAggregation($code);

        $hasOptionValueAggregation->addFilter(
            $this->productHasOptionCodeQueryFactory->create(['option_value_code' => $code]),
            $code
        );

        $aggregationSearch = $this->searchFactory->create();
        $aggregationSearch->addAggregation($hasOptionValueAggregation);

        return $aggregationSearch;
    }
}
