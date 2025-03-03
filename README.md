# Grôle - Plateforme de Gestion d'Offres

## Description
Cette application web permet aux utilisateurs de créer, consulter, modifier et supprimer des offres.

## Installation

### Prérequis
- PHP 8.1 ou supérieur
- Composer
- MySQL ou MariaDB
- Symfony CLI (recommandé pour le développement)
- Git

### Étape 1: Cloner le dépôt
```bash
git clone https://github.com/votre-utilisateur/grole.git
cd grole
```

### Étape 2: Installer les dépendances
```bash
composer install
```

### Étape 3: Configurer l'environnement
```bash
# Copier le fichier d'exemple d'environnement
cp .env.example .env

# Modifier le fichier .env avec vos paramètres
# Notamment la connexion à la base de données:
# DATABASE_URL="mysql://username:password@127.0.0.1:3306/grole_db?serverVersion=8.0"
```

### Étape 4: Créer la base de données
```bash
# Créer la base de données
php bin/console doctrine:database:create

# Exécuter les migrations
php bin/console doctrine:migrations:migrate
```

### Étape 5: Charger les données de test (optionnel)
```bash
php bin/console doctrine:fixtures:load
```

### Étape 6: Démarrer le serveur de développement
```bash
# Avec Symfony CLI
symfony server:start

# Ou avec le serveur interne de PHP
php -S localhost:8000 -t public/
```

## Configuration

### Base de données
Modifiez la variable `DATABASE_URL` dans le fichier `.env` pour configurer la connexion à votre base de données.

### Sécurité
Modifiez la variable `APP_SECRET` dans le fichier `.env` pour définir une clé secrète unique pour votre application.

### Email (si utilisé)
Configurez le service d'email en modifiant la variable `MAILER_DSN` dans le fichier `.env`.

## Fonctionnalités

### Gestion des utilisateurs
- **Inscription** : Création d'un compte avec nom d'utilisateur, email et mot de passe
- **Connexion** : Authentification sécurisée
- **Déconnexion** : Fermeture de session
- **Gestion de profil** : Modification des informations personnelles

### Gestion des offres
- **Création d'offres** : Publication avec titre, description, prix et image
- **Consultation** : Visualisation de toutes les offres disponibles
- **Modification** : Mise à jour des offres personnelles
- **Suppression** : Retrait d'offres de la plateforme

## Guide d'utilisation

### Inscription et connexion
1. **Inscription**
   - Accédez à la page d'inscription
   - Remplissez le formulaire avec votre nom d'utilisateur, email et mot de passe
   - Acceptez les conditions d'utilisation
   - Cliquez sur "S'inscrire"

2. **Connexion**
   - Accédez à la page de connexion
   - Entrez votre nom d'utilisateur/email et mot de passe
   - Cliquez sur "Se connecter"

3. **Déconnexion**
   - Cliquez sur le bouton de déconnexion dans la barre de navigation

### Gestion des offres
1. **Créer une offre**
   - Connectez-vous à votre compte
   - Accédez à "Nouvelle offre"
   - Remplissez le formulaire avec le titre, la description, le prix
   - Ajoutez une image via URL
   - Validez la création

2. **Consulter les offres**
   - Accédez à la page d'accueil ou "Toutes les offres"
   - Parcourez la liste des offres disponibles
   - Cliquez sur une offre pour voir ses détails

3. **Modifier une offre**
   - Accédez à "Mes offres"
   - Trouvez l'offre à modifier
   - Cliquez sur "Modifier"
   - Effectuez vos changements
   - Sauvegardez les modifications

4. **Supprimer une offre**
   - Accédez à "Mes offres"
   - Trouvez l'offre à supprimer
   - Cliquez sur "Supprimer"
   - Confirmez la suppression

## Conseils d'utilisation
- Utilisez un mot de passe sécurisé
- Vérifiez vos informations avant de publier une offre
- Mettez à jour régulièrement vos offres pour maintenir leur pertinence
- Déconnectez-vous après utilisation sur un appareil partagé

## Résolution des problèmes courants

### Erreur de base de données
Vérifiez que:
- Les informations de connexion dans `.env` sont correctes
- La base de données existe
- L'utilisateur a les permissions nécessaires

### Erreur de droits sur les fichiers
Sur les systèmes Unix-like:
```bash
chmod +x bin/console
```

### Problèmes d'affichage/CSS
Vérifiez que:
- Les assets ont été compilés (si vous utilisez Webpack Encore)
- Le cache du navigateur a été vidé

### Problèmes de sessions
Vérifiez que:
- Le dossier `var/cache` est accessible en écriture
- `APP_SECRET` est correctement configuré

## Informations techniques
- Développé avec Symfony
- Base de données MySQL
- Interface responsive adaptée à tous les appareils