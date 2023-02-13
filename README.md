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
        "Reports\\": "storage/app/reports/"

```

## Create a new report
Disposem de la comanda:
```
php artisan make:censat-report {report_name}
```

## Backend
 Podem activar el backend afegint la variable `REPORTS_BACKEND = true` a l'arxiu `.env`

