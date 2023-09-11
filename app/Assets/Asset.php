<?php

namespace App\Assets;

use App\Models\Assets\Calendar; 

/**
 *
 */
trait Asset
{
    /**
     * Set the temporal password.
     *
     * @param  paramType  $value
     * @return void
     */
    public function passwordTempGenerate($len = 15)
    {
        $passwd = $this->abc();

        //nueva cadena a generar
        $new_string = null;

        //cantidad de letras a tomar del abc y generear unnuevo string
        for ($i = 0; $i < $len; $i++) {
            $new_string .= $passwd[random_int(0, count($passwd) - 1)];
        }

        return $new_string;
    }

    /**
     * genera un codigo unico
     * @param String $id
     *
     */
    public function generateUniqueCode($id = null, $includeDate = true, $includeLetters = true, $numLetters = 5)
    {
        // Generar la parte del ID
        $code = isset($id) ? $id : rand(1, 9);
        $code .= "-";

        // Generar la parte de la fecha actual
        if ($includeDate) {
            $code .= strtotime(now());
        }

        // Generar la parte de letras aleatorias
        if ($includeLetters) {
            $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $lettersLength = strlen($letters);

            for ($i = 0; $i < $numLetters; $i++) {
                $code .= $letters[rand(0, $lettersLength - 1)];
            }
        }

        return $code;
    }

    /**
     * variable utilizada para validar actualizaciones
     */
    public $can_update = [];

    /**
     * verifica si dos valores son diferentes y recibe 3 parametros
     * el ultimo parametro es opcional se utiliza cuando quieres que
     * actualize valores nulos
     * @param mixed $value1
     * @param mixed $value2
     * @param boolean $update_is_null
     */
    public function is_diferent($value1, $value2, $update_is_null = false)
    {
        if ($update_is_null) {
            return true;
        }
        return $value2 ? strtolower($value1) != strtolower($value2) : false;
    }

    /**
     * formatea una fecha con el formato Y-m-d H:i:s
     * @param String $date
     * @return DateTime
     */
    public function format_date($date)
    {
        return isset($date) ? date('Y-m-d H:i:s', strtotime($date)) : null;
    }
 

    /**
     * verica si el tiempo actual esta entre el checkin y el check_out
     */
    public function verify_time_is_betweem($check_in, $check_out)
    {
        return strtotime(now()) >= strtotime($check_in) and strtotime(now()) < strtotime($check_out);
    }

}