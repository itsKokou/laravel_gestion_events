<?php

return [
    'pdf' => [
        /*
         * Chemin vers wkhtmltopdf.
         * Exemple macOS: /usr/local/bin/wkhtmltopdf ou /opt/homebrew/bin/wkhtmltopdf
         */
        'binary' => env('WKHTMLTOPDF_BINARY', 'wkhtmltopdf'),
    ],
];

