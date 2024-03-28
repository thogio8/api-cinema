<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Film>
 *
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    public function findFilmsAffiche(){
        return $this->createQueryBuilder('f')
            ->distinct(true)
            ->join('f.seances','s')
            ->where('s.dateProjection > CURRENT_TIMESTAMP()')
            ->getQuery()
            ->getResult();
    }

    public function findFilmWithSeancesSorted(int $filmId): ?Film
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.seances', 's')
            ->addSelect('s')
            ->where('f.id = :filmId')
            ->setParameter('filmId', $filmId)
            ->orderBy('s.dateProjection', 'ASC') // Tri par date de projection, de la plus récente à la plus ancienne
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return Film[] Returns an array of Film objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Film
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
