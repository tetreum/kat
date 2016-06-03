<?php

namespace Kat;

class BasicTorrent 
{
    public $name;
    public $size;
    public $magnet;
    public $torrent;
    public $creator;
    public $seeders;
    public $leechers;
    public $comments;
    public $created;
    public $link;
    
    public function __construct ($info)
    {
        $this->name = trim($info["name"]);
        $this->size = trim($info["size"]);
        $this->magnet = trim($info["magnet"]);
        $this->torrent = trim($info["torrent"]);
        $this->created = $info["created"];
        $this->creator = $info["creator"];
        $this->comments = (int)$info["comments"];
        $this->seeders = (int)$info["seeders"];
        $this->leechers = (int)$info["leechers"];
        $this->link = $info["link"];
    }

    /**
     * Gets full torrent info
     * @return Torrent
     */
    public function getFullInfo ()
    {
        $rawMedia = Kat::query($this->link);
        
        $divData = $rawMedia->find("div.font11px.lightgrey.line160perc")[0];
        
        preg_match("/Downloaded ([\d+]*) times/", $divData->text(), $matches);
        $downloads = (int)$matches[1];
        
        if ($rawMedia->has(".movieCover img")) {
            $image = $rawMedia->has(".movieCover img");
        }
        $description = trim($rawMedia->find("#desc")[0]->html());
        $description = substr(str_replace('<div class="textcontent" id="desc">', "", $description), 0, -6);
        $description = trim(strip_tags($description, Kat::$allowedTags));
        
        $creator = null;
        if ($rawMedia->has(".badgeInline a")) {
            $rawCreator = $rawMedia->find(".badgeInline a")[0];
            $creator = new User([
                "nick" => $rawCreator->text(),
                "link" => Kat::DOMAIN . $rawCreator->attr("href")
            ]);
        }
        
        
        $comments = 0;
        
        if ($rawMedia->has('.darkButton i.menuValue')) {
            $comments = $rawMedia->find('.darkButton i.menuValue')[0]->text();
        }
        
        $media = [
            "name" => $rawMedia->find("[itemprop='name']")[0]->text(),
            "text" => $description,
            "size" => $rawMedia->find(".sharingWidgetBox .widgetSize")[0]->text(),
            "magnet" => $rawMedia->find("a.kaGiantButton[data-nop]")[0]->attr("href"),
            "torrent" => "https:" . $rawMedia->find("a[data-download]")[0]->attr("href"),
            "created" => $divData->find("time")[0]->attr("datetime"),
            "creator" => $creator,
            "downloads" => $downloads,
            "comments" => $comments,
            "seeders" => $rawMedia->find(".seedBlock strong")[0]->text(),
            "leechers" => $rawMedia->find(".leechBlock strong")[0]->text(),
            "link" => $this->link
        ];
        
        if (isset($image) && !empty($image)) {
            $media["image"] = $image;
        }
        
        return new Torrent($media);
    }
}