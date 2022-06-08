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
    public function ChiffreCaisseParMois($datedebut, $datefin, $caisse, $typeoperation = null): ?array{
        $tab =[];
        $i = 0;
        $dateencours=$datedebut;


            do{
                $i++;
                $tab[$i]['mois'] = $dateencours;
                $firstday=$dateencours;
                $lastday=$dateencours;
                
                $firstday->modify('first day of this month');
                $lastday->modify('last day of this month');
                $montant = $this->afficherChiffreCaisse($firstday, $lastday, $caisse, $typeoperation);
                $dateencours->modify('+1 month');
                $tab[$i]['montant']= $montant;
            }
            while($dateencours < $datefin and $i<32);
        return $tab;
    }
}