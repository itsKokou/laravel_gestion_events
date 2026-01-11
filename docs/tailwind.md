Objectif
- Basculer progressivement vers l'utilisation de Tailwind CSS pour tout nouveau code,
  sans modifier les styles existants en place.

Règles générales
- Ne pas supprimer ni modifier les règles CSS déjà présentes (fichiers CSS et inline).
- Pour tout nouveau composant ou nouvelle page, utiliser les classes utilitaires Tailwind.
- Si vous voulez créer des classes réutilisables, utilisez `@apply` dans `resources/css/app.css`
  (ou un nouveau fichier importé) — mais faites-le de façon additive (n'affecte pas l'existant).

Bonnes pratiques
- Préférez des classes utilitaires simples : `px-4 py-2 rounded-lg bg-we-primary text-white`.
- Nommez les composants réutilisables de façon sémantique (ex: `btn-primary`, `card`) et définissez-les
  via `@apply` si nécessaire.
- Pour les couleurs de la marque, utilisez la variable Tailwind créée : `bg-we-primary` ou
  `hover:bg-we-primary-hover` (config définie dans `tailwind.config.js`).

Exemples
- Bouton primaire (nouveau code) :
  <button class="px-4 py-2 rounded-lg font-semibold bg-we-primary hover:bg-we-primary-hover text-white">Réserver</button>

- Carte (nouveau code) :
  <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow">...</div>

Commandes utiles
- Lancer Vite en dev :

```bash
npm run dev
```

- Build pour production :

```bash
npm run build
```

Remarques techniques
- Le projet contient déjà `tailwindcss` dans `package.json` et `@import 'tailwindcss';` dans `resources/css/app.css`.
- Le fichier `tailwind.config.js` (ajouté) configure Tailwind pour scanner les vues Blade et le JS.
- Après pull/merge, redémarrer `npm run dev` pour que Vite prenne en compte la config.

Prochaine étape proposée (optionnelle)
- Je peux convertir un composant d'exemple (ex: `.btn`) en classes Tailwind et ajouter
  un petit guide d'utilisation (PR séparée). Demandez-moi si vous voulez que j'applique
  cet exemple maintenant.
