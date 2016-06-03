# KAT SDK [![Build Status](https://travis-ci.org/tetreum/kat.svg?branch=master)](https://travis-ci.org/tetreum/kat) [![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat)](LICENSE) [![Issues](https://img.shields.io/github/issues/tetreum/kat.svg?style=flat)](https://github.com/tetreum/kat/issues)

Unofficial Kat.cr / KickAssTorrents PHP SDK. 

(I've only made the features that i need, if you need something, code it & send a PR)

#### Install

    composer require tetreum/kat "1.*"

#### Available methods

- Kat
    - getTorrent ($link)
    - search ($query, $page = 1, $sort = null, $maxAge = null)

- User
    - getUploads ($page = 1, $maxAge = null)

- BasicTorrent ```//returned by search/getUploads```
    - getFullInfo()

#### Examples


##### Search content

```php
// Includes vendor libraries
require "vendor/autoload.php";

use Kat\Kat;
use Kat\Utils;

$kat = new Kat();
$r = $kat->search("WikiLeaks Insurance", [
    "field" => "seeders",
    "order" => "desc",
]);
Utils::p($r);
/*
Array
(
    [0] => Kat\BasicTorrent Object
        (
            [name] => WikiLeaks insurance 20130815 - C
            [size] => 325.39 GB
            [magnet] => magnet:?xt=urn:btih:091EED7793FDB48C5BD8488431E888DDE41A889F&dn=wikileaks+insurance+20130815+c&tr=udp%3A%2F%2Ftracker.publicbt.com%2Fannounce&tr=udp%3A%2F%2Fglotorrents.pw%3A6969%2Fannounce&tr=udp%3A%2F%
2Ftracker.openbittorrent.com%3A80%2Fannounce&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce
            [torrent] => https://torcache.net/torrent/091EED7793FDB48C5BD8488431E888DDE41A889F.torrent?title=[kat.cr]wikileaks.insurance.20130815.c
            [creator] => 
            [seeders] => 2
            [leechers] => 2
            [comments] => 5
            [created] => 2013-08-17 19:03:00
            [link] => https://kat.cr/wikileaks-insurance-20130815-c-t7746515.html
        )
 
    [1] => Kat\BasicTorrent Object
        (
            [name] => wikileaks insurance 20130815-B
            [size] => 46.48 GB
            [magnet] => magnet:?xt=urn:btih:95381785C3FB446DF35C5B4A8E5EF167DCB72011&dn=wikileaks+insurance+20130815+b&tr=udp%3A%2F%2Ftracker.publicbt.com%2Fannounce&tr=udp%3A%2F%2Fglotorrents.pw%3A6969%2Fannounce&tr=udp%3A%2F%
2Ftracker.openbittorrent.com%3A80%2Fannounce&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce
            [torrent] => https://torcache.net/torrent/95381785C3FB446DF35C5B4A8E5EF167DCB72011.torrent?title=[kat.cr]wikileaks.insurance.20130815.b
            [creator] => Kat\User Object
                (
                    [nick:Kat\User:private] => DWizz
                    [password:Kat\User:private] => 
                    [link:Kat\User:private] => https://kat.cr/user/DWizz/
                )
 
            [seeders] => 1
            [leechers] => 0
            [comments] => 1
            [created] => 2013-08-17 12:52:00
            [link] => https://kat.cr/wikileaks-insurance-20130815-b-t7745182.html
        )
    ...
)
*/
```

##### Get torrent info

```php
// Includes vendor libraries
require "vendor/autoload.php";

use Kat\Kat;
use Kat\Utils;

$kat = new Kat();
$r = $kat->getTorrent("https://kat.cr/wikileaks-insurance-t4287181.html");
Utils::p($r);

/*
Kat\Torrent Object
(   
    [text] => www.wikileaks.org
    [image] =>
    [downloads] => 10
    [name] => WikiLeaks insurance
    [size] => 1.39 GB
    [magnet] => magnet:?xt=urn:btih:76a36f1d11c72eb5663eeb4cf31e351321efa3a3&dn=wikileaks+insurance&tr=udp%3A%2F%2Ftracker.publicbt.com%3A80%2Fannounce&tr=udp%3A%2F%2Fglotorrents.pw%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.open
bittorrent.com%3A80%2Fannounce&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce
    [torrent] => https://torcache.net/torrent/76A36F1D11C72EB5663EEB4CF31E351321EFA3A3.torrent?title=[kat.cr]wikileaks.insurance
    [creator] => Kat\User Object
        (   
            [nick:Kat\User:private] => 23silver
            [password:Kat\User:private] =>
            [link:Kat\User:private] => https://kat.cr/user/23silver/
        )
 
    [seeders] => 0
    [leechers] => 0
    [comments] => 23
    [created] => 2010-07-29T01:32:22+00:00
    [link] => https://kat.cr/wikileaks-insurance-t4287181.html
)
*/
```

##### Get user uploads

```php
// Includes vendor libraries
require "vendor/autoload.php";

use Kat\User;
use Kat\Utils;

$user = new User(["nick" => "DWizz"]);
$r = $user->getUploads();
Utils::p($r);

Array
(
    [0] => Kat\BasicTorrent Object
        (
            [name] => wikileaks insurance 20130815-B
            [size] => 46.48 GB
            [magnet] => magnet:?xt=urn:btih:95381785C3FB446DF35C5B4A8E5EF167DCB72011&dn=wikileaks+insurance+20130815+b&tr=udp%3A%2F%2Ftracker.publicbt.com%2Fannounce&tr=udp%3A%2F%2Fglotorrents.pw%3A6969%2Fannounce&tr=udp%3A%2F%
2Ftracker.openbittorrent.com%3A80%2Fannounce&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce
            [torrent] => https://torcache.net/torrent/95381785C3FB446DF35C5B4A8E5EF167DCB72011.torrent?title=[kat.cr]wikileaks.insurance.20130815.b
            [creator] => Kat\User Object
                (
                    [nick:Kat\User:private] => DWizz
                    [password:Kat\User:private] => 
                    [link:Kat\User:private] => https://kat.cr/user/DWizz/
                )
 
            [seeders] => 1
            [leechers] => 0
            [comments] => 1
            [created] => 2013-08-17 12:52:00
            [link] => https://kat.cr/wikileaks-insurance-20130815-b-t7745182.html
        )
 
    [1] => Kat\BasicTorrent Object
        (
            [name] => Wikileaks insurance 20130815-A
            [size] => 3.32 GB
            [magnet] => magnet:?xt=urn:btih:E0A092AC0F9B56C886C41335CA36F34AAED6B80C&dn=wikileaks+insurance+20130815+a&tr=udp%3A%2F%2Ftracker.publicbt.com%2Fannounce&tr=udp%3A%2F%2Fglotorrents.pw%3A6969%2Fannounce&tr=udp%3A%2F%
2Ftracker.openbittorrent.com%3A80%2Fannounce&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce
            [torrent] => https://torcache.net/torrent/E0A092AC0F9B56C886C41335CA36F34AAED6B80C.torrent?title=[kat.cr]wikileaks.insurance.20130815.a
            [creator] => Kat\User Object
                (
                    [nick:Kat\User:private] => DWizz
                    [password:Kat\User:private] => 
                    [link:Kat\User:private] => https://kat.cr/user/DWizz/
                )
 
            [seeders] => 5
            [leechers] => 0
            [comments] => 1
            [created] => 2013-08-17 12:43:00
            [link] => https://kat.cr/wikileaks-insurance-20130815-a-t7745127.html
        )
 
)
*/
```