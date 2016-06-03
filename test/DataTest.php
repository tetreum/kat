<?php
class DataTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider searchProvider
     */
    public function testSearch($expected)
    {
        $kat = new \Kat\Kat();
        $result = $kat->search("WikiLeaks Insurance", 1, [
            "field" => "time_add"
        ]);
        $this->assertEquals($result[0], $expected[0], "Checking search result 1");
        $this->assertEquals($result[1], $expected[1], "Checking search result 2");
    }
    
    public function searchProvider () {
         return json_decode(file_get_contents(dirname(__FILE__) . "/data/search.json"));
    }
    
    /**
     * @dataProvider torrentProvider
     */
    public function testGetTorrent($expected)
    {
        $kat = new \Kat\Kat();
        $result = $kat->getTorrent("https://kat.cr/wikileaks-insurance-t4287181.html");
        $this->assertEquals(json_decode($result), json_decode($expected), "Checking getTorrent result");
    }
    
    public function torrentProvider () {
         return json_decode(file_get_contents(dirname(__FILE__) . "/data/get_torrent.json"));
    }
}