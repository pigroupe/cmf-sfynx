<?php

namespace Tms\Bundle\MediaBundle\Entity\Repository;

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

class MediaRepository extends AbstractEntityRepository
{
    /**
     * Get the number of medias for each mime type
     *
     * @param DateTime from
     * @param DateTime to
     * @return array
     */
    public function findNumberByMimeType(\DateTime $from, \DateTime $to)
    {
        return $this
            ->getEntityManager()
            ->createQuery(
                'SELECT m.mimeType, count(m.id) as number
                    FROM TmsMediaBundle:Media m
                    WHERE m.createdAt >= :from
                    AND m.createdAt <= :to
                    GROUP BY m.mimeType
                '
            )
            ->setParameter('from', $from)
            ->setParameter('to', $to->modify('+1 day'))
            ->getResult()
        ;
    }

    /**
     * Get the total size taken by medias for each mime type
     *
     * @param DateTime from
     * @param DateTime to
     * @return array
     */
    public function findSizeByMimeType(\DateTime $from, \DateTime $to)
    {
        return $this
            ->getEntityManager()
            ->createQuery(
                'SELECT m.mimeType, sum(m.size)/1048576 as size
                    FROM TmsMediaBundle:Media m
                    WHERE m.createdAt >= :from
                    AND m.createdAt <= :to
                    GROUP BY m.mimeType
                '
            )
            ->setParameter('from', $from)
            ->setParameter('to', $to->modify('+1 day'))
            ->getResult()
        ;
    }

    /**
     * Get the number of medias for each source
     *
     * @param DateTime from
     * @param DateTime to
     * @return array
     */
    public function findNumberBySource(\DateTime $from, \DateTime $to)
    {
        return $this
            ->getEntityManager()
            ->createQuery(
                'SELECT m.source as name, count(m.id) as data
                    FROM TmsMediaBundle:Media m
                    WHERE m.createdAt >= :from
                    AND m.createdAt <= :to
                    GROUP BY m.source
                '
            )
            ->setParameter('from', $from)
            ->setParameter('to', $to->modify('+1 day'))
            ->getResult()
        ;
    }
}
