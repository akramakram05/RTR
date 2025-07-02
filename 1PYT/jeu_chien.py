# jeu_chien.py

import csv

def lire_chiens(fichier):
    """
    Lit le fichier chien.txt et retourne une liste de dictionnaires représentant chaque chien.
    """
    chiens = []
    try:
        with open(fichier, encoding='utf-8') as f:
            lecteur = csv.DictReader(f)
            for ligne in lecteur:
                # Conversion string "True"/"False" en booléen
                chiens.append({
                    "race": ligne["race"],
                    "enfants": ligne["enfants"].strip() == "True",
                    "sportif": ligne["sportif"].strip() == "True",
                    "appartement": ligne["appartement"].strip() == "True",
                    "taille": ligne["taille"].strip().lower()
                })
    except FileNotFoundError:
        print("Erreur : Le fichier chien.txt est introuvable !")
        exit()
    return chiens

def demander_oui_non(question):
    """
    Pose une question à l'utilisateur et attend une réponse oui/non.
    Retourne True si oui, False si non.
    """
    while True:
        reponse = input(question + " (oui/non) : ").strip().lower()
        if reponse in ['oui', 'o']:
            return True
        elif reponse in ['non', 'n']:
            return False
        else:
            print("Réponse invalide. Veuillez répondre par 'oui' ou 'non'.")

def demander_taille(question):
    """
    Demande la taille du chien souhaitée à l'utilisateur.
    Retourne la taille choisie : 'petit', 'moyen' ou 'grand'.
    """
    tailles = ['petit', 'moyen', 'grand']
    while True:
        reponse = input(question + " (petit/moyen/grand) : ").strip().lower()
        if reponse in tailles:
            return reponse
        else:
            print("Réponse invalide. Veuillez choisir entre 'petit', 'moyen' ou 'grand'.")

def filtrer_chiens(chiens, enfants, sportif, appartement, taille):
    """
    Retourne la liste des chiens correspondant exactement aux critères de l'utilisateur.
    """
    resultats = []
    for chien in chiens:
        if (chien["enfants"] == enfants and
            chien["sportif"] == sportif and
            chien["appartement"] == appartement and
            chien["taille"] == taille):
            resultats.append(chien)
    return resultats

def afficher_resultats(resultats):
    """
    Affiche la liste des races de chiens correspondant aux critères.
    """
    if resultats:
        print("\nVoici le(s) chien(s) qui vous correspond(ent) :")
        for chien in resultats:
            print(f"- {chien['race'].capitalize()} ({chien['taille']})")
    else:
        print("\nAucun chien ne correspond exactement à votre profil.\nVous pouvez essayer avec d'autres critères.")

def main():
    print("=== Quel chien est fait pour vous ? ===\n")
    chiens = lire_chiens("chien.txt")
    # Questions à l'utilisateur
    enfants = demander_oui_non("Avez-vous des enfants ?")
    sportif = demander_oui_non("Êtes-vous sportif(ve) ?")
    appartement = demander_oui_non("Habitez-vous en appartement ?")
    taille = demander_taille("Quelle taille de chien préférez-vous ?")

    # Filtrer les chiens selon les critères
    resultats = filtrer_chiens(chiens, enfants, sportif, appartement, taille)

    # Afficher le résultat
    afficher_resultats(resultats)

if __name__ == "__main__":
    main()
