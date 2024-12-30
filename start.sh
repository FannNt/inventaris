#!/bin/bash
php artisan octane:start --server=swoole --host=0.0.0.0 &
php artisan schedule:work &
wait -n 