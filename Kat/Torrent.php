<?php

namespace Kat;

class Torrent extends BasicTorrent
{
    public $text;
    public $image;
    public $downloads;
    
    public function __construct ($info)
    {
        parent::__construct($info);
        
        $this->text = $info["text"];
        $this->downloads = (int)$info["downloads"];
        
        if (isset($info["image"])) {
            $this->image = $info["image"];
        }
        
    }
}