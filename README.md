# sms-tracer

Lumen 6 (compatible PHP 7.2). Deux APIs :

| Méthode | URL | Rôle |
|---|---|---|
| POST | `/sms/log` | Reçoit `ip`, `user_agent`, `mobile(s)`, `senderid`, `sms`, les écrit dans `storage/logs/sms/sms-YYYY-MM-DD.log`, retourne `OK` |
| GET | `/sms/log/latest` | Lit le dernier fichier de log (`?download=1` pour le télécharger) |

`ip` et `user_agent` sont déduits de la requête s'ils ne sont pas fournis.
Si `SMS_READ_KEY` est défini dans `.env`, l'API 2 exige le header `X-Api-Key`.

    composer install && php -S localhost:8000 -t public
