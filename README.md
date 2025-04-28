# Système de Gestion des Stagiaires - ISTA

Une application web complète pour la gestion des stagiaires, développée avec PHP, MySQL et Bootstrap, permettant aux administrateurs de gérer efficacement les dossiers des stagiaires.

![alt text](./synths/public/image/ofppt_logo.png)

## 👨‍💻 Développeurs

- **Nisrine el Atmani** - *Développeur Full Stack*
- **Abdsamad Louali** - *Développeur Full Stack*

## 🚀 Fonctionnalités

### Pour les Administrateurs
- Gestion complète des stagiaires (CRUD)
- Gestion des classes et des groupes
- Export des données en format Excel
- Gestion des documents des stagiaires
- Tableau de bord administratif
- Vérification des dossiers stagiaires

### Pour les Stagiaires
- Connexion sécurisée
- Gestion du profil personnel
- Upload des documents requis
- Consultation des informations de classe

## 🛠 Technologies Utilisées

- **Backend**: Laravel | python 3.10+
- **Base de données**: MySQL
- **Frontend**:
  - HTML5, CSS3, JavaScript
  - Bootstrap 5
  - Flask
  - Tailwind CSS
- **Packages**:
  - PHPSpreadsheet (pour l'export Excel)
  - Composer (gestion des dépendances)

## 📋 Prérequis

- Laravel
- MySQL 5.7 ou supérieur
- Serveur Apache
- Composer
- Extension PHP:
  - php_mysql
  - php_fileinfo
  - php_zip (pour PHPSpreadsheet)

## 💻 Installation

1. **Cloner le projet**
   ```bash
   git clone [URL_DU_REPO]
   cd [NOM_DU_PROJET]
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   ```

3. **Configuration de la base de données**
   - Importer le fichier `Database/ista_project.sql`
   - Configurer les accès dans `Model/conx.php`

4. **Configuration du serveur**
   - Pointer le serveur web vers le dossier du projet
   - Assurer les permissions d'écriture sur les dossiers:
     - docTRlast/
     - assets/img/

## 🗂 Structure du Projet

```
STRUCTURE DU PROJET
==================
📁 Project_S/
├── 📁 python/
│   └── 📄 app.py
└── 📁 synths/
    ├── 📁 app/
    │   └── 📁 Http/
    │       └── 📁 Controllers/
    │           ├── 📄 AdminController.php
    │           ├── 📄 ClasseController.php
    │           ├── 📄 Controller.php
    │           ├── 📄 FoldersController.php
    │           ├── 📄 LoginController.php
    │           ├── 📄 StudentController.php
    │           └── 📄 VereficationController.php
    ├── 📁 Models/
    │   ├── 📄 Admin.php
    │   ├── 📄 Classe.php
    │   ├── 📄 Student.php
    │   └── 📄 User.php
    ├── 📁 Providers/
    ├── 📁 bootstrap/
    ├── 📁 config/
    ├── 📁 database/
    ├── 📁 public/
    ├── 📁 resources/
    │   ├── 📁 css/
    │   │   └── 📄 app.css
    │   ├── 📁 js/
    │   │   ├── 📄 app.js
    │   │   └── 📄 bootstrap.js
    │   └── 📁 views/
    │       └── 📁 layout/
    │           ├── 📄 header.blade.php
    │           ├── 📄 Admin.blade.php
    │           ├── 📄 Adminprofile.blade.php
    │           ├── 📄 Ajouterclass.blade.php
    │           ├── 📄 Classdetails.blade.php
    │           ├── 📄 document-verification-results.blade.php
    │           ├── 📄 filiers.blade.php
    │           ├── 📄 Login.blade.php
    │           ├── 📄 Student.blade.php
    │           └── 📄 welcome.blade.php
    ├── 📁 routes/
    │   ├── 📄 console.php
    │   └── 📄 web.php
    ├── 📁 storage/
    ├── 📁 tests/
    ├── 📄 .editorconfig
    ├── 📄 .env.example
    ├── 📄 .gitattributes
    ├── 📄 .gitignore
    ├── 📄 artisan
    ├── 📄 composer.json
    ├── 📄 composer.lock
    ├── 📄 package-lock.json
    ├── 📄 package.json
    ├── 📄 phpunit.xml
    ├── 📄 README.md
    ├── 📄 tailwind.config.js
    └── 📄 vite.config.js
```

## 👥 Rôles Utilisateurs

### Administrateur
- Gestion complète des stagiaires
- Gestion des classes
- Export des données
- Vérification des dossiers

### Stagiaire
- Consultation du profil
- Upload des documents
- Mise à jour des informations personnelles
Access the application

Student Panel:
📱 Screenshots 🔓 Login Pages:
![alt text](./Documents/images/Screenshot%202025-04-23%20104919.png)
![alt text](./Documents/images/Screenshot%202025-04-23%20123154.png)
Admin Panel:
![alt text](./Documents/images/Screenshot%202025-04-23%20122708.png)
![alt text](./Documents/images/Screenshot%202025-04-23%20122726.png)
![alt text](./Documents/images/Screenshot%202025-04-23%20122747.png)
![alt text](./Documents/images/Screenshot%202025-04-23%20122811.png)
![alt text](./Documents/images/Screenshot%202025-04-23%20123056.png)
![alt text](./Documents/images/Screenshot%202025-04-23%20123110.png)
![alt text](./Documents/images/Screenshot%202025-04-23%20123125.png)
## 🔒 Sécurité

- Authentification sécurisée
- Protection contre les injections SQL
- Validation des données côté serveur et client
- Gestion sécurisée des uploads de fichiers

## 📝 Contribution

1. Fork le projet
2. Créer une branche pour votre fonctionnalité
   ```bash
   git checkout -b feature/AmazingFeature
   ```
3. Commit vos changements
   ```bash
   git commit -m 'Add some AmazingFeature'
   ```
4. Push vers la branche
   ```bash
   git push origin feature/AmazingFeature
   ```
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT - voir le fichier [LICENSE.md](LICENSE.md) pour plus de détails.

## 📧 Contact

### Équipe de développement
- **Nisrine el Atmani**
  - GitHub: [@Nisrine28-serine]
  - Email: nisrineelatmani74@gmail.com
- **Abdsamad Louali**
  - GitHub: [@abde-louali]
  - Email: abdsamadlouali@gmail.com

## 🙏 Remerciements

- La communauté open source
