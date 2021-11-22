<?php

namespace App\Repository;

use App\Entity\Peak;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Peak|null find($id, $lockMode = null, $lockVersion = null)
 * @method Peak|null findOneBy(array $criteria, array $orderBy = null)
 * @method Peak[]    findAll()
 * @method Peak[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeakRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Peak::class);
    }

    /**
     * @param array $box
     * @return Peak[]
     */
    public function findInBoundingBox(array $box): array
    {
        $maxLatW = explode(' ', $box['nw'])[0];
        $maxLatE = explode(' ', $box['ne'])[0];

        $maxLat = $maxLatE > $maxLatW ? $maxLatE : $maxLatW;

        $minLatW = explode(' ', $box['sw'])[0];
        $minLatE = explode(' ', $box['se'])[0];

        $minLat = $minLatE < $minLatW ? $minLatE : $minLatW;

        $maxLonN = explode(' ', $box['ne'])[1];
        $maxLonS = explode(' ', $box['se'])[1];

        $maxLon = $maxLonN > $maxLonS ? $maxLonN : $maxLonS;

        $minLonN = explode(' ', $box['nw'])[1];
        $minLonS = explode(' ', $box['sw'])[1];

        $minLon = $minLonN < $minLonS ? $minLonN : $minLonS;

        $query = $this->createQueryBuilder('p')
            ->where('p.lat BETWEEN :minLat AND :maxLat')
            ->andWhere('p.lon BETWEEN :minLon AND :maxLon')
            ->setParameters(['maxLat' => $maxLat, 'minLat' => $minLat, 'maxLon' => $maxLon, 'minLon' => $minLon]);

        return $query->getQuery()->execute();
    }
}
