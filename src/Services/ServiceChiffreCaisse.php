<?php
namespace App\Services;

use App\Repository\OperationFinanciereRepository;

class ServiceChiffreCaisse {

    private $RepoOperationFinanciere;

    public function __construct(OperationFinanciereRepository $operationFinanciereRepository)
    {
        $this->RepoOperationFinanciere = $operationFinanciereRepository;
    }

    public function afficherChiffreCaisse($datedebut, $datefin, $caisse, $typeoperation ): ?float {
      
        
        $opertionsFinancieres = $this->RepoOperationFinanciere->findCaisseByDate($datedebut, $datefin, $caisse, $typeoperation);
 
        $montant = 0;
        foreach($opertionsFinancieres as $opertionFinanciere) {
            $montant += $opertionFinanciere->getMontant(); 
        }
        return $montant;
    }
    public function ChiffreCaisseParMois($datedebut, $datefin, $caisse, $typeoperation): ?array{
        $tab =[];
        $i = 0;
        $dateEnCours = clone $datedebut;
        do {
            $i++;
            $firstday = clone $dateEnCours;
            $lastday = clone $dateEnCours;

            $firstday->modify('first day of this month');
            $lastday->modify('last day of this month');
            
            $montant = $this->afficherChiffreCaisse($firstday, $lastday, $caisse, $typeoperation);
            $dateEnCours->modify('+1 month');
            $tab[$i] = ['mois' => $firstday, 'montant' => $montant]; 
        }
        while($dateEnCours <= $datefin);
        return $tab;
    }
}