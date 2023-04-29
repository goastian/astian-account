<?php

namespace App\Assets;

/**
 * 
 */
trait Asset
{
    private function abc()
    {
        return [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
            'Q', 'R', 'S', 'T', 'U', 'X', 'Y', 'W', 'Z'
        ];
    }

    /**
     * Set the code.
     *
     * @param  paramType  $value
     * @return void
     */
    public function generateCode($len=5)
    {
        //abc
        $letters = $this->abc();

        //nueva cadena a generar
        $new_string = null;

        //cantidad de letras a tomar del abc y generear unnuevo string
        for ($i = 0; $i < $len; $i++) {
            $new_string .= $letters[random_int(0, count($letters) - 1)];
        }

        //convertir a unnuevo array
        $letters = str_split($new_string . strtotime(now()));

        //new variable para alamacenar el nuevo valor
        $final_code = null;
        //recorrer el array

        for ($i = 0; $i <= count($letters) - 1; $i++) {
            $final_code .= $letters[$i];
        }

        return $final_code;
    }

    /**
     * Set the temporal password.
     *
     * @param  paramType  $value
     * @return void
     */
    public function passwordTempGenerate($len = 10)
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
}
