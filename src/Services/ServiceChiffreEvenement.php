<?php
namespace App\Services;

use App\Repository\OperationFinanciereRepository;

class ServiceChiffreEvenement {

    private $RepoOperationFinanciere;

    public function __construct(OperationFinanciereRepository $operationFinanciereRepository)
    {
        $this->RepoOperationFinanciere = $operationFinanciereRepository;
    }

    public function afficherChiffreEvenement($datedebut, $datefin, $evenement, $typeoperation ): ?float {
      
        
        $opertionsFinancieres = $this->RepoOperationFinanciere->findEvenementByDate($datedebut, $datefin, $evenement, $typeoperation);
 
        $montant = 0;
        foreach($opertionsFinancieres as $opertionFinanciere) {
            $montant += $opertionFinanciere->getMontant(); 
        }
        return $montant;
    }
    public function ChiffreEvenementParMois($datedebut, $datefin, $evenement, $typeoperation): ?array{
        $tab =[];
        $i = 0;
        $dateEnCours = clone $datedebut;
        do {
            $i++;
            $firstday = clone $dateEnCours;
            $lastday = clone $dateEnCours;

            $firstday->modify('first day of this month');
            $lastday->modify('last day of this month');
            
            $montant = $this->afficherChiffreEvenement($firstday, $lastday, $evenement, $typeoperation);
            $dateEnCours->modify('+1 month');
            $tab[$i] = ['mois' => $firstday, 'montant' => $montant]; 
        }
        while($dateEnCours <= $datefin);
        return $tab;
    }
}