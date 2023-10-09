<?php

namespace App\Transformers\Company;
 
use League\Fractal\TransformerAbstract;

class CompanyTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($data)
    {
        return [
            'id' => $data->id,
            'empresa' => $data->company,
            'ruc' => $data->ruc
        ];
    }


    public static function transformRequest($index){

        $attribute = [
            'empresa' => 'company',
            'ruc' => 'ruc' 
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }


    public static function transformResponse($index){
        $attribute = [
            'company' => 'empresa',
            'ruc' => 'ruc'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }


    public static function getOriginalAttributes($index){
            $attribute = [
                'id' => 'id',
                'empresa' => 'company',
                'ruc' => 'ruc'
            ];

            return isset($attribute[$index]) ? $attribute[$index] : null;
    }


}
