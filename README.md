# SnowTricks

## Sommaire documentation sur le projet

[Documentation projet](https://openclassrooms.com/fr/paths/59/projects/42/assignment)

## Environnements
**Local** : Snowtricks

**prod**  : https://ocprojects.fr/

## Local

#### Cloner le projet

```bash
  git clone https://github.com/cyrilglanum/SnowTricks.git
```

#### Aller sur le répertoire du projet

```bash
  cd SnowTricks
```

#### Installer les dépendances

```bash
  composer install
```

#### Configurer le fichier .env

#### Optimizing Configuration Loading

```bash
  php artisan config:cache
```

*If you execute the `config:cache` command during your deployment process, you should be sure that you are only calling the `env` function from within your configuration files. Once the configuration has been cached, the `.env` file will not be loaded and all calls to the `env` function for `.env` variables will return `null`.*
  
#### Optimiser le chargement des routes

```bash
  php artisan route:cache
```

#### Optimiser le chargement des vues

```bash
  php artisan view:cache
```

## Vhosts
Exemple Wamp
```
#
<VirtualHost *:80>
	ServerName local.snowtricks.com
	DocumentRoot "c:/wamp64/www/git/SnowTricks/public"
	<Directory  "c:/wamp64/www/git/SnowTricks/public/">
		Options +Indexes +Includes +FollowSymLinks +MultiViews
		AllowOverride All
		Require local
	</Directory>
</VirtualHost>
```