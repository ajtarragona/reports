# Reports
Package per fer reports en PDF

[![Latest Stable Version](http://poser.pugx.org/ajtarragona/reports/v)](https://packagist.org/packages/ajtarragona/reports) [![Total Downloads](http://poser.pugx.org/ajtarragona/reports/downloads)](https://packagist.org/packages/ajtarragona/reports) [![Latest Unstable Version](http://poser.pugx.org/ajtarragona/reports/v/unstable)](https://packagist.org/packages/ajtarragona/reports) [![License](http://poser.pugx.org/ajtarragona/reports/license)](https://packagist.org/packages/ajtarragona/reports) [![PHP Version Require](http://poser.pugx.org/ajtarragona/reports/require/php)](https://packagist.org/packages/ajtarragona/reports)

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

