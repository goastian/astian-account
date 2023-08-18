<?php

namespace App\Exceptions;

use Exception;
use App\Assets\JsonResponser;

class ReportMessage extends Exception
{
    use JsonResponser;

    protected $message;
    protected $code;

    public function __construct($message, $code)
    {
        $this->message = $message;
        $this->code = $code;
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return  $this->message($this->message, $this->code);
    }
}
