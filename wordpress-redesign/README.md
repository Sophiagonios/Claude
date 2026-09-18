# Refonte antenniste91.fr — brouillon de travail

## Contexte

Refonte du site WordPress d'un antenniste en Essonne (91) : antenne TV/TNT, parabole/satellite,
installation Starlink, vidéosurveillance. Cible : particuliers (propriétaires) et professionnels
(hôtels, commerces, collectivités). Objectif de conversion n°1 : l'appel téléphonique.

**Contrainte de cette session** : pas d'accès réseau sortant vers `antenniste91.fr` ni vers
wp-admin depuis cet environnement (politique d'egress de l'environnement Claude Code cloud —
seul GitHub est autorisé). Ce dossier contient donc une refonte construite "à côté", sans avoir
pu auditer le site en ligne ni les images/textes envoyés dans la conversation (non reçus par
cette session). Elle repose sur le brief donné par l'utilisateur, pas sur un audit du site actuel.

## Contenu

- `prototype/index.html` — page d'accueil complète, autonome (HTML/CSS/JS vanilla, sans
  dépendance de build), pensée mobile-first, orientée clic-à-l'appel. Aperçu live publié en
  artifact : voir le lien partagé dans la conversation.

## Placeholders à remplacer avant mise en ligne

Repérables dans le HTML par la classe `ph` (soulignage pointillé orange) :

- Numéro de téléphone (actuellement `tel:0000000000` / `01 XX XX XX XX`)
- Raison sociale / nom commercial exact
- SIRET, adresse postale, email de contact
- Liste des communes réellement couvertes (celles listées sont des exemples Essonne 91,
  à confirmer)
- Avis clients (actuellement des exemples explicitement marqués comme tels)
- Note Google Business (nombre d'avis, note)
- Réponses FAQ sur le prix, le délai d'intervention et l'offre Starlink (installation seule
  ou avec fourniture du matériel)

## Direction créative retenue

- **Palette** : bleu technique profond (`#0F3D63`) + orange signal (`#FF6A2B`) pour l'appel à
  l'action, sur fond clair neutre. Mode sombre pris en charge.
- **Typographies** : Big Shoulders Display (titres, condensé/industriel — évoque tours et
  signal) + Manrope (texte courant) + IBM Plex Mono (labels techniques, effet "relevé signal").
- **Structure** : bandeau d'appel permanent (desktop + barre fixe mobile), bascule
  Particulier/Professionnel dans le hero, grille de 4 services, différenciateurs, process en
  3 étapes, zone d'intervention, avis, FAQ, CTA final, footer avec bloc NAP.

## Étapes suivantes proposées

1. Valider ou corriger cette direction (couleurs, ton, structure) sur l'aperçu live.
2. Fournir les informations placeholders ci-dessus.
3. Porter le résultat dans WordPress. Deux options selon l'existant :
   - Si un constructeur de page (Elementor, etc.) est déjà installé : recréer les sections
     une à une en copiant le texte final et en import ant la palette/police en styles globaux.
   - Sinon : envisager un thème bloc natif (Gutenberg / FSE) plus léger que l'existant.
   Cette étape nécessite soit un accès navigateur (Claude in Chrome / Computer Use) connecté à
   la session wp-admin déjà ouverte par l'utilisateur, soit une intégration manuelle par
   l'utilisateur à partir de ce code.
4. Une fois la page d'accueil validée, décliner les pages secondaires (services détaillés,
   zone d'intervention, contact) sur le même système visuel.
