<?php

namespace App\Enum;

class EnumType
{
    /**
     * tipos de movimiento de dinero
     * @return Array
     */
    public static function payment_type()
    {
        return [
            'in',
            'out',
        ];
    }
  
    /**
     * metodos de pago
     */
    public static function payment_method()
    {
        return [
            'efectivo',
            'yape',
            'plin',
            'bcp',
            'interbank',
            'bbva',
            'paypal',
        ];
    }

    /**
     * Establece valores entre 0 y 1
     */
    public static function yes_or_not()
    {
        return [
            'si', 
            'no'
        ];
    }

    /**
     * Categorias para booking
     */
    public static function booking_type()
    {
        return [
            'booking', 
            'reservation'
        ];
    }

    public static function documento_type(){
        return [
            'dni',
            'cpp',
            'pasaporte',
            'cedula',
            'carnet de extranjeria',
        ];
    }
}
