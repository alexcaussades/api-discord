# 🎮 API Discord — GameHub & Farming Simulator 25

![Laravel](https://img.shields.io/badge/Laravel-API-FF2D20?logo=laravel&logoColor=white)
![Python](https://img.shields.io/badge/Discord_Bot-Python-5865F2?logo=discord&logoColor=white)
![HTTPS](https://img.shields.io/badge/HTTPS-Secured-success)


**API REST développée avec Laravel pour centraliser les fonctionnalités de GameHub et assurer la communication avec le bot Discord, notamment pour la gestion des parcelles et du serveur Farming Simulator 25.**

L'API permet aux applications et aux utilisateurs autorisés d'interagir avec les données du projet à travers des requêtes HTTP et des réponses JSON.

## 🔗 Liens officiels

- **Dépôt GitHub :** https://github.com/alexcaussades/api-discord/
- **Documentation des endpoints :** https://alexcaussades.freeboxos.fr/api_perso/endepoint
- **Bot Discord Farming Simulator :** https://github.com/alexcaussades/FarmingBot
- **API en ligne :** https://alexcaussades.freeboxos.fr/api_perso/

# 📋 Fonctionnalités

## 🌾Farming 25

### 🌾 Gestion des parcelles

- Consultation de la liste des parcelles disponibles.
- Vérification de la disponibilité d'une parcelle.
- Réservation d'une parcelle par un joueur identifié sur Discord.
- Annulation d'une réservation via une requête autorisée.
- Centralisation des opérations de réservation dans l'API.

### 🖥️ Serveur Farming Simulator 25

Consultation des informations exposées par l'API concernant le serveur :

- Informations générales du serveur.
- Vérification de la connectivité avec le serveur (*ping*).
- État du serveur.
- Liste des mods.
- Liste des joueurs connectés.
- Statistiques du serveur.
- Durée de fonctionnement (*uptime*).
- Point d'accès relatif aux achats.
- Gestions des taches

### 🤖 Intégration Discord

L'API sert d'intermédiaire entre le bot Discord et les données du projet.

Elle permet notamment de :
- Transmettre l'identifiant Discord d'un joueur.
- Centraliser les vérifications liées aux parcelles.
- Effectuer les opérations de réservation et d'annulation.
- Récupérer les informations du serveur Farming Simulator 25.

## 🛠️ Technologies

| Technologie | Utilisation |
|---|---|
| PHP | Langage principal de l'API |
| Laravel | Framework backend |
| API REST / JSON | Communication entre les applications |
| Python | Bot Discord client de l'API |
| HTTPS | Communication chiffrée |
| SQLite | Base de données utilisée dans certaines configurations du projet |

## 🌐 Endpoints disponibles

La liste ci-dessous correspond aux endpoints publiés par la documentation de l'API. Les routes protégées nécessitent une authentification.

**Base URL :**

```text
https://alexcaussades.freeboxos.fr/api_perso
```
## Farming Simulator 25

### 🌾 Parcelles — Farming Simulator 25

| Méthode | Endpoint | Authentification |
|---|---|---|
| `GET` | `/api/farmlands` | Non |
| `GET` | `/api/farmlands/{id}/check` | Non |
| `POST` | `/api/farmlands/{id}/reserve` | Oui |
| `DELETE` | `/api/farmlands/{id}/cancel` | Oui |

### 🖥️ Serveur Farming Simulator 25

| Méthode | Endpoint | Authentification |
|---|---|---|
| `GET` | `/api/serveur-fs25` | Non |
| `GET` | `/api/serveur-fs25/ping` | Non |
| `GET` | `/api/serveur-fs25/status` | Non |
| `GET` | `/api/serveur-fs25/mods` | Non |
| `GET` | `/api/serveur-fs25/players` | Non |
| `GET` | `/api/serveur-fs25/stats` | Non |
| `GET` | `/api/serveur-fs25/uptime` | Non |
| `GET` | `/api/serveur-fs25/buy` | Non |

### Gestion des taches

| Méthode | Endpoint | Authentification |
|---|---|---|
| `POST` | `/api/farmlands/tasks/create` | Oui |
| `GET` | `/api/farmlands/tasks/month` | Non |
| `GET` | `/api/farmlands/tasks/player` | Oui |
| `DELETE` | `/api/farmlands/tasks/delete` | Oui |

## IVAO

| Méthode | Endpoint | Authentification |
|---|---|---|
| `GET` | `/api/ivao/metar/{icao}` | Non |
| `GET` | `/api/ivao/taf/{icao}` | Non |
| `GET` | `/api/ivao/atis/{icao}` | Non |



> **Important :** les routes publiques ne nécessitent pas de jeton d'authentification selon la documentation publiée. Les routes de réservation et d'annulation sont protégées et leur accès est soumis aux permissions configurées par l'API.

Pour consulter les détails, les paramètres et les informations d'authentification, utilise la [documentation complète des endpoints](https://alexcaussades.freeboxos.fr/api_perso/endepoint).

## 🚀 Installation

### Prérequis

- PHP et les extensions requises par le projet.
- Composer.
- Une base de données compatible avec la configuration Laravel.
- Un serveur web compatible, par exemple Apache.
- Python et les dépendances nécessaires pour le bot Discord, si tu souhaites utiliser l'intégration Discord.


## 🔐 Sécurité

Les bonnes pratiques à respecter pour l'exploitation de l'API :

- Utiliser HTTPS pour toutes les communications.
- Protéger les routes de réservation et d'annulation.
- Vérifier l'identité Discord du joueur côté serveur.
- Valider les paramètres et le contenu des requêtes.
- Contrôler les permissions avant toute modification.
- Ne jamais exposer les jetons dans le code source ou les journaux.
- Ne pas exposer directement la base de données SQLite sur Internet.
- Désactiver le mode debug en production avec `APP_DEBUG=false`.

## 🐛 Gestion des erreurs

Les réponses HTTP permettent au client de distinguer les différents résultats d'une opération.

| Code HTTP | Signification |
|---|---|
| `200 OK` | Requête traitée avec succès |
| `201 Created` | Ressource créée avec succès |
| `401 Unauthorized` | Authentification manquante ou invalide |
| `403 Forbidden` | Accès interdit |
| `404 Not Found` | Ressource introuvable |
| `409 Conflict` | Conflit, par exemple une parcelle déjà réservée |
| `422 Unprocessable Entity` | Données invalides |
| `500 Internal Server Error` | Erreur interne du serveur |

Les codes réellement renvoyés dépendent de l'implémentation de chaque route.

## 🤝 Contribution

Les contributions sont les bienvenues pour améliorer l'API, ajouter des fonctionnalités ou corriger des problèmes.

1. Fork le dépôt.
2. Crée une branche dédiée à ta modification.
3. Effectue tes changements et vérifie leur fonctionnement.
4. Soumets une Pull Request en expliquant les modifications apportées.

## 📄 Licence

La licence du projet est à préciser selon les conditions définies dans le dépôt. En l'absence de licence explicite, aucune autorisation générale de réutilisation du code ne doit être présumée.

## 👨‍💻 Auteur

**AlexCaussades**

- GitHub : https://github.com/alexcaussades
- Dépôt API : https://github.com/alexcaussades/api-discord
- Documentation : https://alexcaussades.freeboxos.fr/api_perso/endepoint

---

*API privée destinée à l'écosystème GameHub et à l'intégration du bot Discord Farming Simulator 25.*
