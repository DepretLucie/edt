# Descriptif de l'application
EDT est un projet réalisé dans le cadre du module de *Programmation Web Avancée de la Licence Professionnelle Programmation Avancée* à l'IUT de Bayonne et du Pays Basque.
<br>
C'est un projet réalisé par *Yanis Abdelhak* et *Lucie Dépret*.

<br>

# Processus d'installation
Commandes basiques
- `composer require symfony/orm-pack`
- `composer require --dev symfony/maker-bundle`
- `composer require symfony/form`
- `composer require symfony/validator`
- `composer require symfony/twig-bundle`
- `composer require sensio/framework-extra-bundle`
- `composer require easycorp/easyadmin-bundle`
- `composer require symfony/security-bundle`
<br>

Vérification pour expression sur date 
- `composer require symfony/expression-language`
<br>

Jeu de données 
- `composer require --dev orm-fixtures`
<br>

Création et envoie en BD
- `php bin/console doctrine:database:create`
- `php bin/console doctrine:schema:update --dump-sql`
- `php bin/console doctrine:schema:update --force`
- `php bin/console doctrine:fixtures:load`

<br>

# Accès au site 
Dans un terminal, aller dans le dossier public du projet avec la commande `cd public` pour effectuer la commande `php -S localhost:8000` afin de lancer l'application.<br>
L'interface du projet est accessible à l'url suivante : http://localhost:8000/agenda.html <br>
L'interface d'administration est accessible à l'url suivante : http://localhost:8000/admin avec le login et mot de passe `admin` <br>
Le jeu de données possède des cours pour le jour actuel, deux jours avant et deux jours après.

<br>

# Définition des points d'entrée de l'API
| Route                    | Verbe   | Code réponse attendu | Description de l'action                                  | 
|:-------------------------|:--------|:---------------------|:---------------------------------------------------------|
| api/cours                | GET     | 200                  | Retourne la liste des cours                              |
| api/cours/date           | GET     | 200                  | Retourne la liste des cours pour le jour passé en params |
| api/cours/{id}/note      | POST    | 201                  | Créer une note pour un cours                             |
| api/salles               | GET     | 200                  | Retourne la liste des salles                             |
| api/matiere              | GET     | 200                  | Retourne la liste des matières                           |
| api/matiere/{id}/note    | POST    | 201                  | Créer une note pour la matière                           |
| api/matiere/{id}/avis    | GET     | 200                  | Retourne les avis pour la matière                        |
| api/matiere/{id}         | PATCH   | 201                  | Modifie l'avis de la matière                             |
| api/matiere/avis/{id}    | DELETE  | 204                  | Supprime l'avis de la matière                            |

<br>

# Validateur ajouté
| Nom du validateur | Type                   | Description                                                      | 
|:------------------|:-----------------------|:-----------------------------------------------------------------|
| NotBlank          | Basic Constraints      | Valide qu'une valeur n'est pas vide                              |
| Length            | String Constraints     | Valide qu'une longueur de chaîne est comprise entre deux valeurs |
| Regex             | String Constraints     | Valide qu'une valeur correspond à une expression régulière       |
| GreaterThan       | Comparison Constraints | Valide qu'une valeur est supérieure à une autre                  |
| Expression        | Other Constraints      | Validation dynamique d'une expression d'une condition            |
| Email             | String Constraints     | Valide qu'une valeur est une adresse e-mail valide               |
| Range             | Comparison Constraints | Valide qu'un nombre ou objet se situe entre un min et un max     |

<br>

# Choix techniques
Nous avons suivi le schéma de données, donc il y a obligatoirement : 
- une salle pour un cours
- un professeur pour un cours 

<br>

## Détails > Fichier App\Entity\Salle.php
<br>

### private ?string $numero = null;

Interdiction de laisser l'attribut vide
``` php
#[ASSERT\NotBlank] 
```
Pattern du numéro de la salle
``` php
#[ASSERT\Length(min: 3, max: 5, minMessage: 'Votre salle doit faire minimum {{ limit }} caractères', maxMessage: 'Votre salle doit faire au maximum {{ limit }} caractères',)]
#[Assert\Regex(pattern: '/S\.[0-9][0-9]?[0-9]?/', message: 'Votre salle {{ value }} doit correspondre au schéma S.XXX',)]
```

<br>

## Détails > Fichier App\Entity\Cours.php
<br>

### private ?\DateTimeInterface $dateHeureDebut = null;

Obligation d'un horaire supérieur à l'horaire actuel  
``` php
#[ASSERT\GreaterThan('now +1 hours')]
```
Vérification des horaires de début (08:00 - 17:00)
``` php
#[ASSERT\Expression('this.verificationHoraire("HeureDebut","08:00", "17:00")', message:'Le début du cours doit être compris entre 08:00 et 17:00')]
```
Vérification de l'horaire de début qui doit commencer à pile ou à la demi
``` php 
#[ASSERT\Expression('this.verificationMinutes("HeureDebut")', message:'Le cours doit commencer a pile (ex: 09:00) ou à la demi (ex: 15h30)')]
```

<br>

### private ?\DateTimeInterface $dateHeureFin = null;

Obligation d'un horaire supérieur à l'horaire actuel 
``` php
#[ASSERT\GreaterThan('now +1 hours')]
```
Vérification que la date de fin soit supérieur à la date de début
``` php
#[ASSERT\Expression('this.getDateHeureDebut() < this.getDateHeureFin()', message:'La date de fin ne doit pas être antérieur à la date de début')]
```
Vérification des horaires de fin (09:00 - 18:00)
``` php
#[ASSERT\Expression('this.verificationHoraire("HeureFin","09:00", "18:00")', message:'La fin du cours doit être comprise entre 09:00 et 18:00')]
```
Vérification que le cours soit situé le même jour 
``` php
#[ASSERT\Expression('this.getDateHeureDebut().format("!Y-m-d") == this.getDateHeureFin().format("!Y-m-d")', message:'Le cours doit se situer dans la même journée')]
```
Vérification de l'horaire de début qui doit terminer à pile ou à la demi
``` php 
#[ASSERT\Expression('this.verificationMinutes("HeureFin")', message:'Le cours doit terminer a pile (ex: 09:00) ou à la demi (ex: 15h30)')]
```

<br>

### private ?string $type = null;

Nous avons limité les choix en une liste de 3 éléments : TD, TP, Cours. Nous n'avons donc pas besoin d'assert spécifiques.

<br>

### private ?Professeur $professeur = null;

Vérification qu'il n'y a pas deux cours avec ce professeur aux horaires souhaitées
``` php
#[ASSERT\Expression('this.verificationProfesseurCours()', message:'Ce professeur a déjà un cours prévu à ces horaires')]
```

<br>

### private ?Matiere $matiere = null;

Vérification que la matière est enseignée par le professeur choisi
``` php
#[ASSERT\Expression('this.verificationMatieresProfesseur()', message:'Cette matière n\'est pas enseignée par ce professeur')]
```

<br>

### private ?Salle $salle = null;

Vérification qu'il n'y a pas deux cours dans une même salle aux horaires souhaitées
``` php
#[ASSERT\Expression('this.verificationCoursExistant()', message:'Un cours a déjà lieu dans cette salle aux horaires indiquées')]
```

<br>

## Détails > Fichier App\Repository\CoursRepository.php

<br>

### Fonction findEdtOneDay($date)

Retourne la liste des cours du jour correspondant au paramètre de `$date`.

<br>

# Fonctionnalités additionnelles ajoutées
## Ajout de fonctions pour les validateurs > fichier Cours.php

`verificationCourExistant()` pour que deux cours ne se superposent pas dans la même salle <br>
`verificationProfesseurCours()` pour que le professeur ne puisse pas enseigner deux cours simultanément <br>
`verificationMatieresProfesseur()` pour que le professeur ne puisse enseigner que ses matières attribuées <br>
`verificationMinutes()` pour que le choix des horaires(minutes) soit limité à 00 ou 30 <br>
Dans le fichier Salle.php, ajout d'un UniqueEntity pour ne pas renseigner deux fois le même numéro de salle
``` php
#[UniqueEntity(fields: ['numero'], errorPath: "numero", message: "Ce numéro de salle a déjà été renseigné.")]
```

<br>

## Pop up informatif
En cliquant sur un cours, vous obtenez le détail de ce cours et vous pourrez donc noter le professeur et le cours enseigné. <br><br>


Fonctionnalité `Noter le professeur` <br>
Afin de regarder les avis des professeurs, il faut se rendre dans l'onglet `Professeurs` où nous avons calculé la `moyenne des avis` par professeur. <br><br>

Fonctionnalité `Noter le cours` <br>
Afin de regarder si la note du cours a bien été enregistré, vous pouvez vous rendre sur l'admin dans la catégorie `Note`. Nous avons décidé de ne pas afficher les notes sur l'interface car nous nous plaçons en tant qu'étudiant qui remplit un formulaire, à la demande du professeur, pour connaître son ressenti. <br> Pour cela nous avons créer une nouvelle classe `Note.php` <br><br>


## Détails > Fichier App\Entity\Note.php
<br>

Ajout d'un UniqueEntity pour ne pas renseigner deux fois une note avec un même mail
``` php
#[UniqueEntity(fields: ['cours', 'mail_etudiant'], errorPath: "mail_etudiant", message: "Cet étudiant a déjà noté ce cours.")]
```

<br>

### private ?int $note = null;
Interdiction de laisser l'attribut vide
``` php
#[ASSERT\NotBlank] 
```
Obligation d'une note de 0 à 5
``` php
#[ASSERT\Range(min: 0, max: 5)]
```

<br>

### private ?string $mail_etudiant = null;
Interdiction de laisser l'attribut vide
``` php
#[ASSERT\NotBlank] 
```
Pattern d'un mail
``` php
#[ASSERT\Email]
```

<br>

## Onglet Matières
Fonctionnalité `Noter une matière` <br>
Création d'une nouvelle classe `AvisMatiere.php` qui est identique à `Avis.php` avec Api, AdminCRUD.<br>
Comme pour l'onglet `Professeur`, nous pouvons noter et voir les avis d'une matière. 

<br>

## Calendrier
Fonctionnalité `Calendrier` <br>
L'utilisateur a accès à un calendrier en cliquant sur la date située en haut à droite du header. 

<br>

# Implémentation de fonctionnalités qui ne fonctionnent pas/qui ne sont pas encore codés
- Actuellement l'affichage permet que deux cours soit disponibles en même temps pour un étudiant
- Nous n'avons pas gérer le fait d'avoir des cours uniquement en semaine