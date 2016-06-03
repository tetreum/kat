<?php

namespace Kat;

use DiDom\Document;

class Kat
{
    const DOMAIN = "https://kat.cr";
    
    public static $config;
    
    public static $allowedTags = '<br><b><div><img><span><code><pre><strong>';
    private $validSorts = [
        "size",
        "files_count",
        "time_add",
        "seeders",
        "leechers",
    ];

    /**
     * Parses a kat list (a search, user uploads, etc..)
     * @param Document $html
     * @param int $maxAge timestamp
     * @return array
     */
    public static function parseList ($html, $maxAge = null)
    {
        $uploads = [];
        foreach ($html->find("tr.odd, tr.even") as $i => $rawUpload)
        {
            //echo $rawUpload->html();exit;
            $elements = $rawUpload->find("td.center");
            $date = strtotime($rawUpload->find(".center[title]")[0]->attr("title"));
            
            if (!empty($maxAge) && $date < $maxAge) {
                continue;
            }
            
            $link = $rawUpload->find("a.cellMainLink")[0];
            
            // the uploader may be anonymous
            $creator = null;
            if ($rawUpload->has(".lightgrey.block a.plain")) {
                $rawUser = $rawUpload->find(".lightgrey.block a.plain")[0];
                $creator = new User([
                    "nick" => $rawUser->text(),
                    "link" => Kat::DOMAIN . $rawUser->attr("href")
                ]);
            }
            
            $uploads[] = new BasicTorrent([
                "name" => $link->text(),
                "size" => $rawUpload->find(".nobr.center")[0]->text(),
                "created" => date("Y-m-d H:i:s", $date),
                "magnet" => $rawUpload->find("a[data-nop]")[0]->attr("href"),
                "torrent" => "https:" . $rawUpload->find("a[data-download]")[0]->attr("href"),
                "creator" => $creator,
                "files" => $elements[1]->text(),
                "seeders" => $elements[3]->text(),
                "leechers" => $elements[4]->text(),
                "link" => self::DOMAIN . $link->attr("href"),
                "comments" => (int)$rawUpload->find('div.iaconbox.center.floatright a')[0]->text()
            ]);
        }
        
        return $uploads;
    }

    /**
     * Makes a search
     * @param string $query
     * @param int $page
     * @param array $sort
     * @param int $maxAge timestamp
     * @return array
     * @throws \Exception
     */
    public function search ($query, $page = 1, $sort = null, $maxAge = null)
    {
        if (strlen($query) < 2) {
            throw new \Exception("Increase search terms");
        }
        if ($page < 1) {
            throw new \Exception("Invalid page number");
        }
        
        $page = (int)$page;
        $orderBy = "";
        
        if (isset($sort))
        {
            if ( !in_array($sort["field"], $this->validSorts)) {
                throw new \Exception("Invalid sort");
            }
            
            if (!isset($sort["order"])) {
                $sort["order"] = "desc";
            }
            
            $orderBy = "?field=" . $sort["field"] . "&sorder=" . $sort["order"];
        }
        
        $results = self::query("usearch/". urlencode($query) ."/$page/$orderBy");
        
        return self::parseList($results, $maxAge);
    }

    /**
     * Gets full torrent info
     * @param string $link
     * @return Torrent
     */
    public function getTorrent ($link)
    {
        $basic = @new BasicTorrent([
            "link" => $link
        ]);
            
        return $basic->getFullInfo();
    }

    /**
     * Makes a query to KAT
     * @param string $query
     * @param array $params
     * @param string $method
     * @return Document|mixed
     */
    public static function query ($query, $params = [], $method = "get")
    {
        if (strpos($query, self::DOMAIN) === false) {
            if (substr($query, 0, 1) != "/") {
                $query = "/$query";
            }
            $query = self::DOMAIN . $query;
        }
        
        $result = Utils::curl($query, $method, $params);

        if ($method == Utils::METHOD_GET) {
            return new Document($result);
        } else {
            return $result;
        }
    }
}