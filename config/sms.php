<?php

return [
    // Dossier contenant les fichiers de log SMS (un fichier par jour)
    'log_path' => env('SMS_LOG_PATH') ?: storage_path('logs/sms'),
    // Jeton optionnel protégeant l'API de lecture (header X-Api-Key). Vide = pas de protection.
    'read_key' => env('SMS_READ_KEY', ''),
];
