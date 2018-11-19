<?php

return [

    // Infos
    "apiVersion" => "1.0",
    "author" => "Ikon-K",
    "authorUrl" => "http://www.ikon-k.fr",

    // Administration
    'adminUrl' => 'admin',
    'adminRoles' => [
        'admin' => 'Administrateur',
        'superAdmin' => 'SuperAdmin'
    ],

    // Languages
    "defaultLanguage" => "fr",
    "languages" => [
        "fr" => "Français",
        "en" => "English",
    ],
    "translateKey" => "translate",

    // Medias
    'fileUploadFolder' => 'uploads',
    'imageQuality' => 100,
    'imageCacheFolder' => 'caches',
    'imageMaxWidth' => 1400,
    'imageNotFound' => 'images/no-found.jpg',

];