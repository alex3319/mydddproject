<?php

namespace App\Repository;

use App\Entity\FileStorageDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FileStorageDocument>
 *
 * @method FileStorageDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileStorageDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileStorageDocument[]    findAll()
 * @method FileStorageDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileStorageDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileStorageDocument::class);
    }

    public function add(FileStorageDocument $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FileStorageDocument $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FileStorageDocument[] Returns an array of FileStorageDocument objects
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

//    public function findOneBySomeField($value): ?FileStorageDocument
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
