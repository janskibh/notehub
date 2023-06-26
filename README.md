/!\ Avant d'installer notehub n'oubliez pas de modifier la valeur de $dbpass dans include/config.php par le mot de passe de votre base de données

# Notehub

NoteHub est une plateforme permetant aux étudiants de BUT R&T d’être informés des devoirs maison et futurs contrôles à venir. Les étuditants peuvent relier leur compte NoteHub à la passerelle scodoc de l’IUT de Vélizy afin de consulter leurs notes et moyennes de BUT. Des annonces peuvent être publiées par les administrateurs, elles apparaissent en haut de page.
## Fonctonnalités :
- Page de consultation des devoirs.
- Page de consultation des moyennes (optionnel).
- Interface d’administration (publication d’annonces, gestion des utilisateurs et consultation des logs)
- Système d’annonces publiées par les administrateurs avec un bandeau en haut de la page.
- Page de modification du profil.
- Mise en place de systèmes de sécurisation des données avec chiffrement.
- Peut être un forum et un choix de thèmes
## Utlisateurs :
- Trois types d’utlisateurs (utilisateurs standard, utilisateurs certifiés, administrateurs)
  - Les utilisateurs standard sont des ultiisateurs qui ont juste créé un compte sans le relier à leur compte étudiant du CAS de l’UVSQ, ils n'ont pas accès aux notes
  - Les utilisateurs certfiés possèdent un badge bleu, ils ont relié leur compte à leur compte cas de l’UVSQ. Pour se faire, ils doivent entrer leurs identifiants CAS dans la page profil qui seront chiffrés avec leur mot de passe utilisateur et stockés dans la table utilisateurs.
  - Les administrateurs possèdent un badge doré et peuvent gérer les utilisateurs, publier des annonces et voir les logs.
## La base de données se compose des tables suivantes :
- USERS(ID, USERNAME, PASSWORD, USERCAS, PASSCAS, PP, STATUS, #IDGROUPE)
- GROUPES(ID, NOM, #IDANNEE, TYPE)
- SEMESTRES(ID, NUMERO, #IDANNEE)
- ANNEES(ID, ANNEE)
- RESSOURCES(ID, NOM, #IDSEMESTRE)
- PUBLICATIONS(ID, TYPE, #IDPUB,#IDGROUPE)
- ANNONCES(ID, #IDEMETTEUR, COULEUR, DATE, VISIBILITE, TITRE, MESSAGE)
- DEVOIRS(ID, DATE, #IDPROF, CONTENU, #IDRESSOURCE)
