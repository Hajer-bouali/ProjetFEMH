<?php
namespace App\Services;

use App\Entity\Historique;
use Doctrine\ORM\EntityManagerInterface;

class ServiceHistorique {

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveModifications($params) {
        $modifications = [
            'ancien' => $params['ancien'],
            'nouveau' => $params['nouveau'],
        ];
        $historique = new Historique();
        $historique->setUser($params['user']);
        $historique->setDatemodif(new \DateTime('now'));
        $historique->setTableModifiee($params['table']);
        $historique->setModifications($modifications);
        $historique->setTypeOperation($params['typeoperation']);
        $historique->setIdligne($params['idligne']);
        $this->entityManager->persist($historique);
        $this->entityManager->flush();
    }
}