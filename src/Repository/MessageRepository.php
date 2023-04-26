<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function save(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Récupération de toutes les query pour la pagination. 
    // !!! ATTENTION !!! Remplacé par la function quertyByTopic en dessous afin de ne pas répéter les mêmes messages sous tout les topics

    // public function queryAll(): Query
    // {
    //     return $this->createQueryBuilder('m')
    //         ->getQuery();
    // }

    // Récupération de toutes les query pour la pagination
    public function queryByTopic(Topic $topic): Query
    {
        // récupérer tout les messages uniquement d'un topic
        return $this->createQueryBuilder('m')
            ->andWhere('m.topic = :topic')
            ->setParameter('topic', $topic)
            ->getQuery();
    }

    //    public function findOneBySomeField($value): ?Message
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
