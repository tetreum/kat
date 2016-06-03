<?php

namespace Kat;

class User
{
    private $nick;
    private $password;
    private $link;
    
    public function __construct ($info)
    {
        $this->nick = $info["nick"];
        
        if (isset($info["link"])) {
            $this->link = $info["link"];
        }
        
        if (isset($info["password"]) && !empty($info["password"])) {
            $this->password = $info["password"];
        }
    }
    
    /**
     * Gets user uploads
     * @param int $page
     * @param int $maxAge
     * 
     * @return array
    */
    public function getUploads ($page = 1, $maxAge = null)
    {
        $page = (int)$page;
        $html = Kat::query("user/{$this->nick}/uploads/?page=" . $page);
        
        return Kat::parseList($html, $maxAge);
    }
    
    /**
    * Not finished
    **/
    public function login ()
    {
        if (empty($this->password)) {
            throw new \Exception("Missing password");
        }
        $html = Kat::query("auth/login/", [
            "email" => $this->nick,
            "password" => $this->password,
            ], Utils::METHOD_POST);
    }
}