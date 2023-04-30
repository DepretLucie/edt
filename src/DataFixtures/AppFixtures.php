<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Professeur;
use App\Entity\Matiere;
use App\Entity\Salle;
use App\Entity\Avis;
use App\Entity\Cours;
use App\Entity\AvisMatiere;
use App\Entity\Note;
use DateTime;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $matieresArray = [
            [
                "reference" => "M1011",
                "titre" => "Programmation Web Avancée",
            ],
            [
                "reference" => "M2022",
                "titre" => "Programmation Mobile Avancée",
            ],
            [
                "reference" => "M3033",
                "titre" => "Programmation Distribuée",
            ],
            [
                "reference" => "M4044",
                "titre" => "Embarqué & Temps réel",
            ],
            [
                "reference" => "M5055",
                "titre" => "SIG & Big data",
            ],
            [
                "reference" => "M6066",
                "titre" => "Conception et programmation avancées",
            ],
            [
                "reference" => "M7077",
                "titre" => "Connaissances générales",
            ],
            [
                "reference" => "M8088",
                "titre" => "Gestion de projet",
            ]
        ];

        $matieresObjects = [];

        foreach($matieresArray as $matiere) {
            $temp = new Matiere();
            $temp->setReference($matiere["reference"]);
            $temp->setTitre($matiere["titre"]);
            $matieresObjects[] = $temp;
            $manager->persist($temp);
        }

        $professeurs = [
            [
                "nom" => "Abdelhak",
                "prenom" => "Yanis",
                "email" => "yanis.abdelhak@gmail.com"
            ],
            [
                "nom" => "Dépret",
                "prenom" => "Lucie",
                "email" => "lucie.depret@gmail.com"
            ],
            [
                "nom" => "Cartier-millon",
                "prenom" => "Laura ",
                "email" => "laura.cartier@gmail.com"
            ],
            [
                "nom" => "Maybank",
                "prenom" => "JJ",
                "email" => "jj.maybank@gmail.com"
            ],
            [
                "nom" => "Cameron",
                "prenom" => "Sarah",
                "email" => "sarah.cameron@gmail.com"
            ],
            [
                "nom" => "Cameron",
                "prenom" => "Raph",
                "email" => "raph.cameron@gmail.com"
            ],
            [
                "nom" => "Routledge",
                "prenom" => "JonhB",
                "email" => "jonhB.routledge@gmail.com"
            ],
            [
                "nom" => "Carrera",
                "prenom" => "Kiara",
                "email" => "kiara.carrera@gmail.com"
            ]
        ];

        $professeursObjects = [];

        foreach($professeurs as $key => $professeur) {
            $temp = new Professeur();
            $temp->setNom($professeur["nom"]);
            $temp->setPrenom($professeur["prenom"]);
            $temp->setMail($professeur["email"]);
            $temp->addMatiere($matieresObjects[$key]);
            $professeursObjects[] = $temp;
            $manager->persist($temp);
        }

        $sallesArray = ['S.10', 'S.15', 'S.300', 'S.110', 'S.119', 'S.153','S.200','S.100','S.90'];

        $sallesObjects = [];

        foreach($sallesArray as $numero) {
            $temp = new Salle();
            $temp->setNumero($numero);
            $sallesObjects[] = $temp;
            $manager->persist($temp);
        }

        $avis = [
            [
                "note" => "2",
                "commentaire" => "Pas ouf",
                "emailEtudiant" => "yanis@gmail.com"
            ],
            [
                "note" => "5 ",
                "commentaire" => "J'ai adoré",
                "emailEtudiant" => "lucie@gmail.com"
            ],
            [
                "note" => "5",
                "commentaire" => "Top",
                "emailEtudiant" => "tim@gmail.com"
            ],  
            [
                "note" => "2",
                "commentaire" => "Peu mieux faire",
                "emailEtudiant" => "laura@gmail.com"
            ],  
            [
                "note" => "4 ",
                "commentaire" => "Bien",
                "emailEtudiant" => "loup@gmail.com"
            ],  
            [
                "note" => "1 ",
                "commentaire" => "Impossible de suivre",
                "emailEtudiant" => "mathilde@gmail.com"
            ],  
            [
                "note" => "5 ",
                "commentaire" => "Super compréhensible",
                "emailEtudiant" => "guillaume@gmail.com"
            ],  
            [
                "note" => "3 ",
                "commentaire" => "Moyen",
                "emailEtudiant" => "clement@gmail.com"
            ],
        ];

        $avisObjects = [];

        foreach($avis as $key => $avi) {
            $temp = new Avis();
            $temp->setNote($avi["note"]);
            $temp->setCommentaire($avi["commentaire"]);
            $temp->setMailEtudiant($avi["emailEtudiant"]);
            $temp->setProfesseur($professeursObjects[array_rand($professeursObjects)]);
            $manager->persist($temp);
        }

        $avisMatiere = [
            [
                "note" => "2",
                "commentaire" => "Pas ouf",
                "emailEtudiant" => "yanis@gmail.com"
            ],
            [
                "note" => "5 ",
                "commentaire" => "J'ai adoré",
                "emailEtudiant" => "lucie@gmail.com"
            ],
            [
                "note" => "5",
                "commentaire" => "Top",
                "emailEtudiant" => "tim@gmail.com"
            ],  
            [
                "note" => "2",
                "commentaire" => "Peu mieux faire",
                "emailEtudiant" => "laura@gmail.com"
            ],  
            [
                "note" => "4 ",
                "commentaire" => "Bien",
                "emailEtudiant" => "loup@gmail.com"
            ],  
            [
                "note" => "1 ",
                "commentaire" => "Impossible de suivre",
                "emailEtudiant" => "mathilde@gmail.com"
            ],  
            [
                "note" => "5 ",
                "commentaire" => "Super compréhensible",
                "emailEtudiant" => "guillaume@gmail.com"
            ],  
            [
                "note" => "3 ",
                "commentaire" => "Moyen",
                "emailEtudiant" => "clement@gmail.com"
            ],
        ];

        $avisMatiereObjects = [];

        foreach($avisMatiere as $key => $avi) {
            $temp = new AvisMatiere();
            $temp->setNote($avi["note"]);
            $temp->setCommentaire($avi["commentaire"]);
            $temp->setMailEtudiant($avi["emailEtudiant"]);
            $temp->setMatiere($matieresObjects[array_rand($matieresObjects)]);
            $manager->persist($temp);
        }
        
        date_default_timezone_set('Europe/Paris');

        $aujourdhui = new DateTime();
        $aujourdhui->setTimestamp(strtotime('today midnight'));

        $demain = new DateTime();
        $demain->setTimestamp(strtotime('tomorrow midnight'));

        $hier = new DateTime();
        $hier->setTimestamp(strtotime('yesterday midnight'));

        $avantHier = new DateTime();
        $avantHier->setTimestamp(strtotime('-2 days midnight'));

        $apresDemain = new DateTime();
        $apresDemain->setTimestamp(strtotime('+2 days midnight'));

        $coursArray = [
            // J-2
            [
                "matiere" => 1,
                "dateHeureDebut" => $this->editDate($avantHier, "+ 8 hour"),
                "dateHeureFin" => $this->editDate($avantHier, "+ 11 hour")
            ],
            [
                "matiere" => 2,
                "dateHeureDebut" => $this->editDate($avantHier, "+ 11 hour"),
                "dateHeureFin" => $this->editDate($avantHier, "+ 12 hour 30 minute")
            ],
            [
                "matiere" => 4,
                "dateHeureDebut" => $this->editDate($avantHier, "+ 14 hour"),
                "dateHeureFin" => $this->editDate($avantHier, "+ 15 hour 30 minute")
            ],
            [
                "matiere" => 0,
                "dateHeureDebut" => $this->editDate($avantHier, "+ 16 hour"),
                "dateHeureFin" => $this->editDate($avantHier, "+ 17 hour 30 minute")
            ],
            // J-1
            [
                "matiere" => 0,
                "dateHeureDebut" => $this->editDate($hier, "+ 9 hour 30 minutes"),
                "dateHeureFin" => $this->editDate($hier, "+ 11 hour")
            ],
            [
                "matiere" => 3,
                "dateHeureDebut" => $this->editDate($hier, "+ 11 hour 30 minutes"),
                "dateHeureFin" => $this->editDate($hier, "+ 13 hour")
            ],
            [
                "matiere" => 1,
                "dateHeureDebut" => $this->editDate($hier, "+ 15 hour"),
                "dateHeureFin" => $this->editDate($hier, "+ 16 hour 30 minute")
            ],
            [
                "matiere" => 4,
                "dateHeureDebut" => $this->editDate($hier, "+ 16 hour 30 minute"),
                "dateHeureFin" => $this->editDate($hier, "+ 18 hour 30 minute")
            ],
            // J
            [
                "matiere" => 0,
                "dateHeureDebut" => $this->editDate($aujourdhui, "+ 8 hour"),
                "dateHeureFin" => $this->editDate($aujourdhui, "+ 9 hour 30 minute")
            ],
            [
                "matiere" => 2,
                "dateHeureDebut" => $this->editDate($aujourdhui, "+ 10 hour"),
                "dateHeureFin" => $this->editDate($aujourdhui, "+ 11 hour 30 minute")
            ],
            [
                "matiere" => 3,
                "dateHeureDebut" => $this->editDate($aujourdhui, "+ 14 hour"),
                "dateHeureFin" => $this->editDate($aujourdhui, "+ 15 hour 30 minute")
            ],
            [
                "matiere" => 1,
                "dateHeureDebut" => $this->editDate($aujourdhui, "+ 15 hour 30 minute"),
                "dateHeureFin" => $this->editDate($aujourdhui, "+ 17 hour")
            ],
            [
                "matiere" => 4,
                "dateHeureDebut" => $this->editDate($aujourdhui, "+ 17 hour"),
                "dateHeureFin" => $this->editDate($aujourdhui, "+ 18 hour 30 minute")
            ],
            // J+1
            [
                "matiere" => 3,
                "dateHeureDebut" => $this->editDate($demain, "+ 8 hour"),
                "dateHeureFin" => $this->editDate($demain, "+ 9 hour 30 minute")
            ],
            [
                "matiere" => 1,
                "dateHeureDebut" => $this->editDate($demain, "+ 9 hour 30 minute"),
                "dateHeureFin" => $this->editDate($demain, "+ 11 hour")
            ],
            [
                "matiere" => 2,
                "dateHeureDebut" => $this->editDate($demain, "+ 14 hour"),
                "dateHeureFin" => $this->editDate($demain, "+ 15 hour 30 minute")
            ],
            [
                "matiere" => 0,
                "dateHeureDebut" => $this->editDate($demain, "+ 16 hour"),
                "dateHeureFin" => $this->editDate($demain, "+ 17 hour 30 minute")
            ],
            // J+2
            [
                "matiere" => 3,
                "dateHeureDebut" => $this->editDate($apresDemain, "+ 8 hour"),
                "dateHeureFin" => $this->editDate($apresDemain, "+ 9 hour 30 minute")
            ],
            [
                "matiere" => 1,
                "dateHeureDebut" => $this->editDate($apresDemain, "+ 10 hour"),
                "dateHeureFin" => $this->editDate($apresDemain, "+ 11 hour 30 minute")
            ],
            [
                "matiere" => 4,
                "dateHeureDebut" => $this->editDate($apresDemain, "+ 14 hour"),
                "dateHeureFin" => $this->editDate($apresDemain, "+ 15 hour")
            ],
            [
                "matiere" => 3,
                "dateHeureDebut" => $this->editDate($apresDemain, "+ 15 hour"),
                "dateHeureFin" => $this->editDate($apresDemain, "+ 16 hour 30 minute")
            ],
            [
                "matiere" => 4,
                "dateHeureDebut" => $this->editDate($apresDemain, "+ 16 hour 30 minute"),
                "dateHeureFin" => $this->editDate($apresDemain, "+ 18 hour")
            ]
        ];

        $types = [
            "TD", "TP", "Cours"
        ];

        $coursObjects = [];

        foreach($coursArray as $cours) {
            $temp = new Cours();
            $temp->setMatiere( $matieresObjects[$cours["matiere"]]);
            $temp->setProfesseur($professeursObjects[$cours["matiere"]]);
            $temp->setDateHeureDebut($cours["dateHeureDebut"]);
            $temp->setDateHeureFin($cours["dateHeureFin"]);
            $temp->setSalle($sallesObjects[array_rand($sallesObjects)]);
            $temp->setType($types[array_rand($types)]);
            $coursObjects[] = $temp;
            $manager->persist($temp);
        }
        
        $avisCours = [
            [
                "note" => "4",
                "emailEtudiant" => "yanis@gmail.com"
            ],
            [
                "note" => "3",
                "emailEtudiant" => "lucie@gmail.com"
            ],
            [
                "note" => "2",
                "emailEtudiant" => "tim@gmail.com"
            ],  
            [
                "note" => "4",
                "emailEtudiant" => "laura@gmail.com"
            ],  
            [
                "note" => "4 ",
                "emailEtudiant" => "loup@gmail.com"
            ],  
            [
                "note" => "1 ",
                "emailEtudiant" => "mathilde@gmail.com"
            ],  
            [
                "note" => "5 ",
                "emailEtudiant" => "guillaume@gmail.com"
            ],  
            [
                "note" => "3 ",
                "emailEtudiant" => "clement@gmail.com"
            ],
        ];

        $avisCoursObjects = [];

        foreach($avisCours as $key => $avisCour) {
            $temp = new Note();
            $temp->setNote($avisCour["note"]);
            $temp->setMailEtudiant($avisCour["emailEtudiant"]);
            $temp->setCours($coursObjects[array_rand($coursObjects)]);
            $manager->persist($temp);
        }

        $manager->flush();
    }
    private function editDate($date, $modifier) {
        $tmp = clone $date;
        $tmp->modify($modifier);
        return $tmp;
    }
}
