<?php

use Kat\Kat;
use Kat\Torrent;
use Kat\BasicTorrent;

class DataTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider searchProvider
     */
    public function testSearch($expected)
    {
        $kat = new Kat();
        $result = $kat->search("WikiLeaks Insurance", 1, [
            "field" => "time_add"
        ]);

        // temporal
        $result[0]->creator = null;
        $result[1]->creator = null;

        $this->assertEquals($result[0], new BasicTorrent($expected[0]), "Checking search result 1");
        $this->assertEquals($result[1], new BasicTorrent($expected[1]), "Checking search result 2");
    }

    public function searchProvider () {

        return [[json_decode(file_get_contents(dirname(__FILE__) . "/data/search.json"), true)]];
    }

    /**
     * @dataProvider torrentProvider
     */
    public function testGetTorrent($expected)
    {
        $kat = new Kat();
        $result = $kat->getTorrent("https://kat.cr/wikileaks-insurance-t4287181.html");

        // temporal
        $result->creator = null;

        $this->assertEquals($result, new Torrent($expected), "Checking getTorrent result");
    }

    public function torrentProvider () {
        return [[json_decode(file_get_contents(dirname(__FILE__) . "/data/get_torrent.json"), true)]];
    }
}