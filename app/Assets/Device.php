<?php

namespace App\Assets;

/**
 * 
 */
trait Device
{
    public function Agent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }


    public function remoteIP()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    public function setTokenName($request)
    {
        if ($request->email) {
            return $request->email . "|" . $this->Agent() . "|" . $this->remoteIP();
        } else {
            return $request->user()->email . "|" . $this->Agent() . "|" . $this->remoteIP();
        }
    }
}
