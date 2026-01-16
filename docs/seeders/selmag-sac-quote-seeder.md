# Seeder: Cotización Selmag SAC (Sistema Cotizaciones + Facturación SUNAT)

Este proyecto incluye el seeder [`SelmagSacQuoteSeeder`](database/seeders/SelmagSacQuoteSeeder.php:1) que crea una cotización de ejemplo para **Selmag SAC**.

## Datos

- Empresa: Selmag SAC
- RUC: 20601633206
- Email: sotoatencioc@gmail.com
- Moneda: PEN (S/)
- IGV: 0% (sin IGV)
- Total: S/ 2,000.00

## Ejecución

En Windows (cmd) se recomienda ejecutarlo **sin namespace**:

```bash
php artisan db:seed --class=SelmagSacQuoteSeeder
```

Nota: en este entorno se observó que usar el FQCN (por ejemplo `Database\\Seeders\\SelmagSacQuoteSeeder`) puede disparar el error “Target class [...] does not exist”, mientras que la forma corta funciona correctamente.

