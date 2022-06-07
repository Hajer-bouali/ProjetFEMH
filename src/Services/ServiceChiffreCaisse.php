<?php
namespace App\Services;

use App\Repository\OperationFinanciereRepository;

class ServiceChiffreCaisse {

    private $RepoOperationFinanciere;

    public function __construct(OperationFinanciereRepository $operationFinanciereRepository)
    {
        $this->RepoOperationFinanciere = $operationFinanciereRepository;
    }

    public function afficherChiffreCaisse($datedebut, $datefin, $caisse, $typeoperation = null): ?float {
        $opertionsFinancieres = $this->RepoOperationFinanciere->findCaisseByDate($datedebut, $datefin, $caisse, $typeoperation);
        $montant = 0;
        foreach($opertionsFinancieres as $opertionFinanciere) {
            $montant += $opertionFinanciere->getMontant(); 
        }
        return $montant;
    }

}