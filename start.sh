#!/bin/bash
php artisan octane:start --host=0.0.0.0 --port=$PORT &
php artisan schedule:work &
wait -n 