# Reports
Package per fer reports en PDF


## Requirements
    Laravel >= 6.0
    PHP >= 7.1

## Installation
```
composer require ajtarragona/reports --dev
```

Afegir classloading a  `composer.json`

```
"autoload" : {
    "psr-4": {
        "Reports\\": "storage/app/report-templates/"

```

Publicar estils del backend i dels reports PDF.
```
php artisan ajtarragona:reports:prepare
```

Publicar config dompdf
```
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```
activar `enable_php` a l'arxiu `config/dompdf.php`

## Create a new report
Disposem de la comanda:
```
php artisan make:tgn-report {report_name}
```

Això ens crearà un nou report amb el nom  `report_name` a la carpeta `storage/app/report-templates`.
Es crearan 3 arxius:
- config.php
- NomReportReport.php
- template.blade.php

## Backend
 Podem activar el backend afegint la variable `REPORTS_BACKEND = true` a l'arxiu `.env`

