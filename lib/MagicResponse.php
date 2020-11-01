<?php

namespace MagicAdmin;

class MagicResponse
{
    public $content;
    public $status_code;
    public $data;

    public function __construct($content, $resp_data, $status_code)
    {
        $this->content = $content;
        $this->status_code = $status_code;
        $this->data = $resp_data;
    }
}
