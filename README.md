# Notehub

NoteHub est une plateforme permetant aux étudiants de BUT R&T d’être informés des devoirs maison et futurs contrôles à venir. Les étuditants peuvent relier leur compte NoteHub à la passerelle scodoc de l’IUT de Vélizy afin de consulter leurs notes et moyennes de BUT. Des annonces peuvent être publiées par les administrateurs, elles apparaissent en heut de page.
## Fonctonnalités :
- Page de consultation des devoirs.
- Page de consultation des moyennes (op􀆟onnel).
- Interface d’administration (publica􀆟on d’annonces, gestion des u􀆟lisateurs et consultation des logs)
- Système d’annonces publiées par les administrateurs avec un bandeau en haut de la page.
- Page de ges􀆟on/modifica􀆟on du profil.
- Mise en place de systèmes de sécurisa􀆟on des données avec chiffrement.
## Utlisateurs :
- Trois types d’utlisateurs (utilisateurs standard, utilisateurs cer􀆟fiés, administrateurs)
  - Les utlisateurs standard sont des u􀆟lisateurs qui ont juste créé un compte sans le relier à leur compte étudiant du CAS de l’UVSQ
  - Les utlisateurs certfiés possèdent un badge bleu, ils ont relié leur compte à leur compte cas de l’UVSQ. Pour se faire, ils doivent entrer leurs identifiants cas dans la page profil qui seront chiffrés avec leur mot de passe utilisateur et stockés dans la table utilisateurs.
  - Les administrateurs possèdent un badge doré et peuvent gérer les utilisateurs, publier des annonces et voir les logs.
## La base de données se compose des tables suivantes :
- USERS(ID, USERNAME, PASSWORD, USERCAS, PASSCAS, PP, STATUS, #IDGROUPE)
- GROUPES(ID, NOM, #IDANNEE, TYPE)
- SEMESTRES(ID, NUMERO, #IDANNEE)
- ANNEES(ID, ANNEE)
- RESSOURCES(ID, NOM, #IDSEMESTRE)
- PUBLICATIONS(ID, TYPE, #IDPUB,#IDGROUPE)
- ANNONCES(ID, #IDEMETTEUR, COULEUR, DATE, VISIBILITE, TITRE, MESSAGE)
- DEVOIRS(ID, DATE, #IDPROF, CONTENU, #IDRESSOURCE
