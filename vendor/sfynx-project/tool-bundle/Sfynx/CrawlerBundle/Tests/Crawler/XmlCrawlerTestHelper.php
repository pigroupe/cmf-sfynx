<?php

namespace Sfynx\CrawlerBundle\Tests\Crawler;

class XmlCrawlerTestHelper
{
    public static function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            if ($objects) {
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (filetype($dir."/".$object) == "dir") {
                            rrmdir($dir."/".$object);
                        } else {
                            unlink($dir."/".$object);
                        }
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}
