NoteHub est une plateforme permetant aux étudiants de BUT R&T d’être informés des devoirs maison et futurs contrôles à venir. Les étuditants peuvent relier leur compte NoteHub à la passerelle scodoc de l’IUT de Vélizy pour leur permetre de consulter leurs notes et moyennes de BUT. Les administrateurs peuvent publier des annonces qui apparaissent sous forme de bandeau en haut de chaque page.
Fonctonnalités :
- Page de consulta􀆟on des devoirs.
- Page de consulta􀆟on des moyennes (op􀆟onnel).
- Interface d’administra􀆟on (publica􀆟on d’annonces, ges􀆟on des u􀆟lisateurs et consulta􀆟on des logs)
- Système d’annonces publiées par les administrateurs avec un bandeau en haut de la page.
- Page de ges􀆟on/modifica􀆟on du profil.
- Mise en place de systèmes de sécurisa􀆟on des données avec chiffrement.
Utlisateurs :
- Trois types d’u􀆟lisateurs (u􀆟lisateurs standard, u􀆟lisateurs cer􀆟fiés, administrateurs)
  - Les u􀆟lisateurs standard sont des u􀆟lisateurs qui ont juste créé un compte sans le relier à leur compte étudiant du CAS de l’UVSQ
  - Les u􀆟lisateurs cer􀆟fiés possèdent un badge bleu, ils ont relié leur compte à leur compte cas de l’UVSQ. Pour se faire, ils doivent entrer leurs iden􀆟fiants cas dans la page profil qui seront chiffrés avec leur mot de passe u􀆟lisateur et stockés dans la table u􀆟lisateurs.
  - Les administrateurs possèdent un badge doré et peuvent gérer les u􀆟lisateurs, publier des annonces et voir les logs.
SAE INFO
BELLON Jan
CHARBOTEL Eliot
LEVIAUX Lucas
La base de données se compose des tables suivantes :
- USERS(ID, USERNAME, PASSWORD, USERCAS, PASSCAS, PP, STATUS, #IDGROUPE)
- GROUPES(ID, NOM, #IDANNEE, TYPE)
- SEMESTRES(ID, NUMERO, #IDANNEE)
- ANNEES(ID, ANNEE)
- RESSOURCES(ID, NOM, #IDSEMESTRE)
- PUBLICATIONS(ID, TYPE, #IDPUB,#IDGROUPE)
- ANNONCES(ID, #IDEMETTEUR, COULEUR, DATE, VISIBILITE, TITRE, MESSAGE)
- DEVOIRS(ID, DATE, #IDPROF, CONTENU, #IDRESSOURCE
