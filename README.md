# snowtricks

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c5eee13eb04a4cfb879e2b6adf124876)](https://app.codacy.com/manual/BFH59/snowtricks?utm_source=github.com&utm_medium=referral&utm_content=BFH59/snowtricks&utm_campaign=Badge_Grade_Dashboard)

Projet 6 openclassrooms
========================================================================================================================
Description du besoin
Vous êtes chargé de développer le site répondant aux besoins de Jimmy. Vous devez ainsi implémenter les fonctionnalités suivantes : 

un annuaire des figures de snowboard. Vous pouvez vous inspirer de la liste des figures sur Wikipédia. Contentez-vous d'intégrer 10 figures, le reste sera saisi par les internautes ;
la gestion des figures (création, modification, consultation) ;
un espace de discussion commun à toutes les figures.
Pour implémenter ces fonctionnalités, vous devez créer les pages suivantes :

la page d’accueil où figurera la liste des figures ; 
la page de création d'une nouvelle figure ;
la page de modification d'une figure ;
la page de présentation d’une figure (contenant l’espace de discussion commun autour d’une figure).

================================ Installation du projet ================================

1. Télécharger l'archive du projet ou clonez le depuis le lien : https://github.com/BFH59/snowtricks.git

2. Créez une copie du fichier .env (qui se situe à la racine du projet), renommez le fichier .env.local et mettez y vos variables d'environnements (identifiants de connexion au serveur smtp google mailer, identifiants de connexion à la base de donnée).

3. Installez les différentes dépendances à l'aide de composer (https://getcomposer.org/download/)
  -> lancer la commande " composer install "
  
4. Créez la base de données avec la commande :
  -> php bin/console doctrine:database:create
  
5. Generez les tables de la base de données avec la commande : 
   -> php bin/console doctrine:migrations:migrate
   
 6. Créez un jeu de données à l'aider des fixtures avec la commande :
 
   -> php bin/console doctrine:fixtures:load
   
 7. Profitez de votre nouveau site communautaire !!
