<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTimeImmutable;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);

        $this->today = date('Y-m-d', strtotime('today'));

        $this->firstDayOfMonth = date('Y-m-d', strtotime('first day of this month'));
        $this->lastDayOfMonth = date('Y-m-d', strtotime('last day of this month'));

        $this->firstDayOfWeek = date('Y-m-d', strtotime('monday this week'));
        $this->lastDayOfWeek = date('Y-m-d', strtotime('sunday this week'));
    }

/*     public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    } */

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private $today;
    private $firstDayOfMonth;
    private $lastDayOfMonth;
    private $firstDayOfWeek;
    private $lastDayOfWeek;

    
    /**
     * Return products that have been created this month
     */
    public function findCreatedThisMonth()
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.createdAt BETWEEN :firstDayOfMonth AND :lastDayOfMonth')
            ->setParameter(':firstDayOfMonth', $this->firstDayOfMonth)
            ->setParameter(':lastDayOfMonth', $this->lastDayOfMonth)
            ->getQuery()
            ->getResult();
    }

    /**
     * Return products that have been created this week
     */
    public function findCreatedThisWeek()
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.createdAt BETWEEN :firstDayOfWeek AND :lastDayOfWeek')
            ->setParameter(':firstDayOfWeek', $this->firstDayOfWeek)
            ->setParameter(':lastDayOfWeek', $this->lastDayOfWeek)
            ->getQuery()
            ->getResult();
    }

    /**
     * Return products that have been created today
     */
    public function findCreatedToday()
    {
        $products = $this->findAll();

        $createdTodayEvents = [];

        foreach ($products as $product) {
            if ($product->getCreatedAt()->format('Y-m-d') === $this->today) {
                $createdTodayProducts[] = $product;
            }
        }

        return $createdTodayProducts;
    }

    /**
     * Return events that happened or will happen this month
     */
    public function findHappensThisMonth()
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.date BETWEEN :firstDayOfMonth AND :lastDayOfMonth')
            ->setParameter(':firstDayOfMonth', $this->firstDayOfMonth)
            ->setParameter(':lastDayOfMonth', $this->lastDayOfMonth)
            ->getQuery()
            ->getResult();
    }

    /**
     * Return events that happened or will happen this week
     */
    public function findHappensThisWeek()
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.date BETWEEN :firstDayOfWeek AND :lastDayOfWeek')
            ->setParameter(':firstDayOfWeek', $this->firstDayOfWeek)
            ->setParameter(':lastDayOfWeek', $this->lastDayOfWeek)
            ->getQuery()
            ->getResult();
    }

    /**
     * Return events that happened or will happen today
     */
    /* public function findHappensToday()
    {
        $products = $this->findAll();

        $todayProducts = [];

        foreach ($products as $product) {
            if ($product->getDate()->format('Y-m-d') === $this->today) {
                $createdTodayEvents[] = $product;
            }
        }

        return $todayProducts;
    }
 */
    /**
     * @return Event[] Returns an array of Event objects according to category
     */
    public function findByBrand(int $BrandId, ?int $limit)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.brand = :brandId')
            ->setParameter(':brandId', $BrandId)
            ->andWhere('e.date > :today')
            ->setParameter(':today', new DateTimeImmutable())
            ->andWhere('e.isArchived = 0')
            ->orderBy('e.date', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns an array of Event objects according to a keyword
     */
    public function findByKeyword(string $keyword, ?int $limit)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.title LIKE :keyword')
            ->setParameter(':keyword', "%$keyword%")
            ->andWhere('e.date > :today')
            ->setParameter(':today', new DateTimeImmutable())
            ->andWhere('e.isArchived = 0')
            ->orderBy('e.date', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns an array of Event objects that are not archived
     *
     * findByActive(?int $limit) equals to findByActive(int $limit = null)
     */
    public function findByActive(int $limit = null)
    {
        return $this->createQueryBuilder('e')
            /* ->andWhere('e.status = En attente') */
            /* ->andWhere('e.date > :today') */
           /*  ->setParameter(':today', new DateTimeImmutable())
            ->orderBy('e.date', 'ASC') */
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns an array of user past joined or created Event objects
     */
   /*  public function findPastEvents(int $userId, int $limit = null)
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.members', 'm')
            ->andWhere('m.id = :userId')
            ->setParameter(':userId', $userId)
            ->andWhere('e.date < :today')
            ->setParameter(':today', new DateTimeImmutable())
            ->orderBy('e.date', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    } */

    /**
     * @return Product[] Returns an array of user incoming joined or created Event objects
     */
  /*   public function findIncomingProducts(int $userId, int $limit = null)
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.members', 'm')
            ->andWhere('m.id = :userId')
            ->setParameter(':userId', $userId)
            ->andWhere('e.date > :today')
            ->setParameter(':today', new DateTimeImmutable())
            ->orderBy('e.date', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
 */

    /**
     * @return Product[] Returns an array of similar Event objects
     */
    public function findRecommendedProducts(Product $product, int $limit = 3)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.category = :eventCategory')
            ->setParameter(':eventCategory', $product->getBrand())
            ->andWhere('e.date > :today')
            ->setParameter(':today', new DateTimeImmutable())
            ->andWhere('e.id != :eventId')
            ->setParameter(':eventId', $product->getId())
            ->orderBy('e.date', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }



//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
