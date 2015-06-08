La commande generate:bundle génère une nouvelle structure de bundle et l'active automatiquement dans votre application.
---------------------------------------------------------------------------------------------

Pour désactiver le mode interactif, utilisez l'option --no-interaction mais il vous faudra alors penser à passer toutes les options obligatoires :
php app/console generate:bundle --namespace=Acme/Bundle/BlogBundle --no-interaction

--namespace: L'espace de nom du bundle à créer. L'espace de nom devrait commencer avec un nom « commercial » comme le nom de votre entreprise, le nom de votre projet ou le nom de votre client, suivi par un ou plusieurs sous-espace(s) de nom facultatifs, et devrait être terminé par le nom du bundle lui-même (qui doit avoir Bundle comme suffixe) :
php app/console generate:bundle --namespace=Acme/Bundle/BlogBundle

--bundle-name: Le nom du bundle facultatif. Ce doit être une chaîne de caractères terminée par le suffixe Bundle :
php app/console generate:bundle --bundle-name=AcmeBlogBundle

--dir: Le répertoire dans lequel stocker le bundle. Par convention, la commande détecte et utilise le répertoire src/ de l'application :
php app/console generate:bundle --dir=/var/www/myproject/src

--format: (annotation) [valeurs: yml, xml, php ou annotation] Détermine le format à utiliser pour les fichiers de configuration générés comme le routage. Par défaut, la commande utilise le format annotation. Choisir le format annotation implique que le SensioFrameworkExtraBundle soit déjà installé :
php app/console generate:bundle --format=annotation

--structure: Spécifie s'il faut génerer la structure de répertoire complète, incluant les répertoires publics pour la documentation et les ressources web ainsi que les dictionnaires de traductions :
php app/console generate:bundle --structure