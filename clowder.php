<?php

// ===========================================================================
//
// @name ......... : Clowder PHP Client
//
// @author ....... : Ouahib El Hanchi <ouahib.el.hanchi@gmail.com>
//
// ===========================================================================

// Clowder ===================================================================

class Clowder
{
    // Class constantes ------------------------------------------------------

    const API_URL = 'http://www.clowder.io/api';

    // Attributes ------------------------------------------------------------

    private $api_key;
    private $ch;

    // Constructor -----------------------------------------------------------

    function __construct($api_key=null)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 0);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->ch, CURLOPT_HEADER, 0);

        $this->api_key = $api_key;
    }

    // Private methods -------------------------------------------------------

    private function check($data)
    {
        if(!is_array($data)) {
            throw InvalidArgumentException("Expected an array.");
        }

        if(array_key_exists('status', $data)) {
            throw OutOfRangeException('Status should not be provided.');
        }
    }

    // -----------------------------------------------------------------------

    private function send($data)
    {
        if(!empty($this->api_key)) {
            $data['api_key'] = $this->api_key;
        }

        if(!array_key_exists('value', $data)) {
            $data['value'] = $data['status'];
        }

        if(array_key_exists('frequency', $data)) {
            $data['frequency'] = $this->clean_frequency($data['frequency']);
        }

        $url = array_key_exists('url', $data) ? $data['url'] : self::API_URL;
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);

        curl_exec($this->ch);
    }

    // -----------------------------------------------------------------------

    private function clean_frequency($frequency)
    {
        if(is_int($frequency)) {
            return $frequency;
        } else if(is_object($frequency)) {
            $date1 = new DateTime();
            $date2 = new DateTime();
            $date2->add($frequency);

            return $date2->getTimestamp() - $date1->getTimestamp();
        }

        throw InvalidArgumentException("Invalid frequency.");
    }

    // Public methodss -------------------------------------------------------

    public function ok($data)
    {
        $this->check($data);

        $data['status'] = 1;

        $this->send($data);
    }

    // -----------------------------------------------------------------------

    public function fail($data)
    {
        $this->check($data);

        $data['status'] = -1;

        $this->send($data);
    }

    // Destructor ------------------------------------------------------------

    function __destruct()
    {
        curl_close($this->ch);
    }
}

// EOF =======================================================================

?>
