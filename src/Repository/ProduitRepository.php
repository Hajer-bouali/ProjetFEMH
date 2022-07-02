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

    public function updateQuantiteProduit($evenement) {
        foreach($evenement->getFicheTechniques() as $ficheTechnique) {
            if ($evenement->getEtat() == 'valide' && !$ficheTechnique->isQuantiteCalculee()) {
                $produit = $ficheTechnique->getProduit();
                $produit->setQuantite($produit->getQuantite() - ($ficheTechnique->getQuantite() * $ficheTechnique->getEvenement()->getNbpanierfinale()));
                $ficheTechnique->setQuantiteCalculee(true);
                $this->_em->persist($ficheTechnique);
                $this->_em->persist($produit);
            }
        }
        $this->_em->flush();
    }
}