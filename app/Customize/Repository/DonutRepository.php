<?php

namespace Customize\Repository;

use Customize\Entity\Donut;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Eccube\Util\StringUtil;


/**
 * DonutRepository
 */
class DonutRepository extends AbstractRepository
{
    /**
     * DonutRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Donut::class);
    }


//     /**
//      * @param int $id
//      *
//      * @return Donut
//      */
//     public function get($id = 1)
//     {
//         $Donut = $this->find($id);

//         if (null === $Donut) {
//             throw new \Exception('Donut not found. id = ' . $id);
//         }
//         return $this->find($id);
//     }
}
