# Projet d'architecture logiciel

## Calculatrice

### Instructions d'installation
Pour installer ce projet, il suffit de:
cloner le repo
```shell
git clone https://github.com/AxelSevenS/SoftwareArchitecture-Projet [chemin
 du dossier]
```

s'assurer d'être sur la bonne branche
```shell
git checkout calculator
```

et installer les dépendances
```shell
docker compose up php-composer
```

### Instructions d'utilisation
Pour utiliser le projet, il suffit d'exécuter le script PHP "calc.php" en lui fournissant en arguments les calculs à effectuer;
vous pouvez passer au script autant d'arguments que vous voulez, avec n'importe quel format, tant que votre terminal est capable de lire les arguments:
```shell
php ./src/calc.php 1 + 1
php ./src/calc.php "1 + 1"
php ./src/calc.php 1+1
php ./src/calc.php "1 + (2 - 1)" 
```

### Auteur
Ce programme a été développé, conçu et programmé par Axel Sevenet, [@AxelSevenS](https://github.com/AxelSevenS) 
