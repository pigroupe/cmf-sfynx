<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 *
 */

namespace Tms\Bundle\MediaBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * AbstractEntityRepository
 */
abstract class AbstractEntityRepository extends EntityRepository
{
    /**
     * Find by query builder
     * 
     * @param array $criteria
     * @param array|null $orderBy
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findByQueryBuilder(array $criteria, array $orderBy = null)
    {
        $qb = $this->createQueryBuilder('entity');

        if(!is_null($orderBy)) {
            foreach($orderBy as $field => $order) {
                $qb->addOrderBy(sprintf("entity.%s", $field), $order);
            }
        }

        self::addCriteria($qb, 'entity', $criteria);

        return $qb;
    }

    /**
     * Find by query
     *
     * @param array $criteria
     * @param array|null $orderBy
     * 
     * @return \Doctrine\ORM\Query
     */
    public function findByQuery(array $criteria = null, array $orderBy = null)
    {
        return $this->findByQueryBuilder($criteria, $orderBy)->getQuery();
    }

    /**
     * Find all query builder
     * 
     * @param array|null $orderBy
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllQueryBuilder(array $orderBy = null)
    {
        return $this->findByQueryBuilder(array(), $orderBy);
    }

    /**
     * Find all query
     * 
     * @param array|null $orderBy
     * 
     * @return \Doctrine\ORM\Query
     */
    public function findAllQuery(array $orderBy = null)
    {
        return $this->findAllQueryBuilder($orderBy)->getQuery();
    }

    /**
     * addJoin
     *
     * @param QueryBuilder $qb
     * @param string $relatedEntity
     * @param string $sourceEntity
     * @param array $relatedEntityCriteria
     */
    protected static function addJoin(QueryBuilder & $qb, $sourceEntity, $relatedEntity, array $relatedEntityCriteria = array())
    {
        $qb->join(sprintf('%s.%s', $sourceEntity, $relatedEntity), $relatedEntity);

        foreach ($relatedEntityCriteria as $field => $value) {
            self::addWhere($qb, $relatedEntity, $field, $value);
        }
    }

    /**
     * addWhere
     *
     * @param QueryBuilder $qb
     * @param string $relatedEntity
     * @param string $field
     * @param string $value
     */
    protected static function addWhere(QueryBuilder & $qb, $relatedEntity, $field, $value)
    {
        $qb->where($qb->expr()->eq(
            sprintf('%s.%s', $relatedEntity, $field),
            $value
        ));
    }

    /**
     * addCriteria
     *
     * @param QueryBuilder $qb
     * @param string $sourceEntity
     * @param array $criteria
     */
    public static function addCriteria(QueryBuilder & $qb, $sourceEntity, array $criteria)
    {
        foreach ($criteria as $field => $value) {
            if (is_array($value)) {
                self::addJoin($qb, $sourceEntity, $field);
                self::addCriteria($qb, $field, $value);
            } else {
                self::addWhere($qb, $sourceEntity, $field, $value);
            }
        }
    }
}