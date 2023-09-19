<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransformRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $transformer)
    {
        //transform Request
        $inputAttrs = [];

        foreach ($request->request->all() as $input => $value) {

            if (is_array($value)) {

                $$input = [];

                foreach ($value as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {

                        $index = $transformer::transformRequest("$input.$key1.$key2");

                        if ($index) {
                            $data = str_replace('*', $key1, $index);
                            $data = explode('.', $data);

                            $$input[$data[1]][$data[2]] = $value2;
                        }
                        $inputAttrs[$input] = $$input;
                    }
                }
            } else {
                $inputAttrs[$transformer::transformRequest($input)] = $value;
            }

        }

        $request->replace($inputAttrs);

        //transform
        $response = $next($request);
        //catch ValidationException
        if (isset($response->exception) && $response->exception instanceof ValidationException) {
            $transformErrors = [];

            $data = $response->getData();

            //cambiando lenguaje del mensaje
            if (isset($data->message)) {
                //translate key message
                $data->message = __($data->message);
            }
            //update error message
            foreach ($data->errors as $field => $error) {

                $transformedField = $transformer::transformResponse($field);

                /**
                 * verificacion de datos para campos de validacion arrrays
                 */
                if (strpos($field, '.')) {
                    $old_key = explode('.', $field);
                    $transformedField = str_replace('*', $old_key[1], $transformedField);
                }
                $transformErrors[$transformedField] = str_replace($field, $transformedField, $error);
            }

            $data->errors = $transformErrors;

            $response->setData($data);
        }
        return $response;
    }
}
