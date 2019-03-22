# Fake news
NewScan propose un outil performant permettant d’évaluer le niveau de fiabilité d’un article ou d’un contenu. Cette évaluation se base sur plusieurs critères de qualités tels que la présence excessive de fautes d’orthographe, etc...

Initialiser le projet
```
cp .env.dist .env.local
make install
```

Créer la base données
```
make init
```

Lancer le projet
```
make up
```

Arrêter le projet
```
make down
```

Lancer webpack pour la compilation des assets
```
make sass
```

