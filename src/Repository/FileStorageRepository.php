<?php

namespace App\Repository;

use App\Entity\FileStorage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FileStorage>
 *
 * @method FileStorage|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileStorage|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileStorage[]    findAll()
 * @method FileStorage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileStorageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileStorage::class);
    }

    public function add(FileStorage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FileStorage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return FileStorage[]
     */
    public function fetchAll(): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->orderBy('a.uuid', 'ASC');
        return $qb->getQuery()->getArrayResult();
    }

//    /**
//     * @return FileStorage[] Returns an array of FileStorage objects
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

//    public function findOneBySomeField($value): ?FileStorage
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
