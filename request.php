<?php

interface IRequest
{
    public function getBody();
}

class Request implements IRequest
{
    function __construct()
    {
        $this->bootstrapSelf();
    }

    private function bootstrapSelf()
    {
        foreach ($_SERVER as $key => $value) {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    private function toCamelCase($string)
    {
        $result = strtolower($string);

        preg_match_all('/_[a-z]/', $result, $matches);

        foreach ($matches[0] as $match) {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }

        return $result;
    }

    public function getBody()
    {
        if ($this->requestMethod === "GET") {
            return;
        }


        if ($this->requestMethod == "POST") {

            $body = array();
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }

            return $body;
        }
    }

    public function getQueryParam()
    {
        $mapQuery = [];
        if(isset($_SERVER['QUERY_STRING'])){
            $query = explode("&", $_SERVER['QUERY_STRING']);
            foreach ($query as $v) {
                $v = explode("=", $v);
                if (count($v) > 1) {
                    $mapQuery[$v[0]] = $v[1];
                } else if (count($v) == 1) {
                    $mapQuery[$v[0]] = null;
                }
            }
        }
        return $mapQuery;
    }
}
