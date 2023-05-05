Feature: Chatroom

Scenario: Initialisation des salons et de la liste des utilisateurs
Given je suis sur la page d'accueil
Then la variable de session "chatrooms" est initialisée avec deux salons
And la variable de session "chatroom" est initialisée avec "Salon 1"
And la variable de session "users" est initialisée

Scenario: Envoi d'un message
Given je suis sur la page d'accueil
When je saisis "Un nouveau message" dans le champ "message" et que je clique sur "Envoyer"
Then le message "Un nouveau message" est ajouté au salon actuel dans la variable de session "chatrooms"
And le message "Un nouveau message" est affiché sur la page dans le salon actuel
And la variable de session "users" est mise à jour avec le temps du dernier message envoyé

Scenario: Envoi d'un message invalide
Given je suis sur la page d'accueil
When je saisis "a" dans le champ "message" et que je clique sur "Envoyer"
Then je vois le message d'erreur "Le message doit avoir entre 2 et 2048 caractères."'

Scenario: Envoi de deux messages consécutifs en moins de 24h
Given je suis sur la page d'accueil
And j'ai envoyé un message il y a moins de 24 heures dans le salon actuel
When je saisis "Un nouveau message" dans le champ "message" et que je clique sur "Envoyer"
Then je vois le message d'erreur "Vous ne pouvez pas poster deux messages consécutifs. Veuillez attendre X secondes." où
X est le temps restant avant de pouvoir envoyer un nouveau message

Scenario: Changement de salon
Given je suis sur la page d'accueil
When je clique sur le lien "Salon 2"
Then la variable de session "chatroom" est mise à jour avec "Salon 2"
And le salon "Salon 2" est affiché avec son historique de messages