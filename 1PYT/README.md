# Projet Bloc 2 – Jeu “Quel chien est fait pour vous ?”

## Présentation

Ce projet est un petit jeu Python en console pour l’association “AdopteUnChien”.  
Le but est d’aider les utilisateurs à découvrir la race de chien la plus adaptée à leur mode de vie, grâce à quelques questions simples.

## Fonctionnement

Le programme pose 4 questions à l’utilisateur :

1. Avez-vous des enfants ? (oui/non)
2. Êtes-vous sportif(ve) ? (oui/non)
3. Habitez-vous en appartement ? (oui/non)
4. Quelle taille de chien préférez-vous ? (petit/moyen/grand)

Selon les réponses, le programme lit le fichier `chien.txt` (fourni), filtre les races de chien qui correspondent, puis affiche le résultat.

## Fichiers fournis

- **jeu_chien.py** : Le script principal Python.
- **chien.txt** : Liste des races de chiens et leurs caractéristiques (format CSV, modifiable).

## Comment utiliser

1. Ouvrir un terminal dans le dossier du projet contenant `jeu_chien.py` et `chien.txt`.
2. Taper la commande suivante pour lancer le jeu :
   python3 jeu_chien.py
3. Répondre simplement aux questions dans le terminal.
4. Le résultat s’affiche à la fin.

## Ajouter ou modifier des chiens

Pour modifier la liste des chiens proposés, il suffit d’éditer le fichier `chien.txt` (format CSV).

Exemple de ligne :
race,enfants,sportif,appartement,taille
Papillon,True,True,True,petit

## Organisation & Contributions

Projet réalisé dans le cadre du Bloc 2 – Python, rattrapage 1ère année.  
Merci de préciser les noms et prénoms de chaque membre du groupe dans le fichier CONTRIBUTORS.md du dépôt GitHub.

## Auteurs

(membres du projet :Akhrouf Akram)
