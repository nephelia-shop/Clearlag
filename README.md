<div align="center">
    <h1>Clearlag</h1>
    <p>Supprimez des entités</p>
</div>

--------------------

# Installation
1. Vous devez mettre le plugin en .phar [.phar](https://pmt.mcpe.fun/create/)/en dossier avec le plugin [devtools](https://poggit.pmmp.io/p/DevTools/) dans le dossier **plugins**

# Configuration:
| **Type**         | **Configuration**          | **Informations**                                                                                       |
|------------------|----------------------------|--------------------------------------------------------------------------------------------------------|
| **__Config__**   | `resources\config.yml`     | Ce fichier de configuration contrôle l'entierté du clearlag ainsi que ses paramètres                   |

## Config
```yaml
parameters:
  #seconds
  time: 120
  #Folder world name
  worlds:
    - "world"
    - "minage"
  broadcast:
    times: [60, 30, 15, 10, 3, 2, 1]
    message: "Clearlagg dans §6{s} §fseconde(s)"
    finish: "Clearlag §6{e} §fEntités clear"
  sound:
    enabled: true
    name: "random.levelup"
    volume: 0.5
    pitch: 1
  #animals, monsters ect
  clear_entities: true
```
- **time** → Délai du clearlag
- **worlds** → Liste des mondes ou le clearlag peut être effectué
- **broadcast** → tout ce qui concerne les messages automatiques ainsi que leur délai
- **sound** → Son lors d'un broadcast et à la fin du clearlag
- **clear_entities** → Activer pour supprimer les entités autres que les items

### Commandes : *(plugin.yml)*
| Commande    | Description          | Permission                          |
|-------------|----------------------|-------------------------------------|
| `/clearlag` | Executer le clearlag | `nepheliashop.permissions.clearlag` |

# Features :
Contactez-nous sur discord pour les ajouter
