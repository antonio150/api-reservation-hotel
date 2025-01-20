### Installation 

Pour installer tous les composantes

```bash
composer install 
```
### Utilisation

Pour voir les api dans swagger, ouvrir

```bash
http://127.0.0.1:8000/api
```
Pour voir la liste des routes qui se trouve dans cet application

```bash
symfony console debug:router
```

Cet application est sécurisé, et pour ajouter un nouvel utilisateur pour la premiere fois.
Saisie la commande ci-dessous


```bash
symfony console app:create-user anto@test.com password123
```
