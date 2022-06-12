<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function updateQuantiteProduit($produit) {
        $quantite = $produit->getQuantite();
        foreach($produit->getFicheTechniques() as $ficheTechnique) {
            if ($ficheTechnique->getEvenement()->getEtat() == 'valide') {
                $quantite -= ($ficheTechnique->getQuantite() * $ficheTechnique->getEvenement()->getNbpanierfinale());               
            }
        }
        $produit->setQuantite($quantite);
        $this->_em->persist($produit);
        $this->_em->flush();
    }
}