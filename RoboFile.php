<?php

class RoboFile extends \Robo\Tasks
{

    private $source = array(
        '.htaccess', 'README.md', 'RoboFile.php', 'app', 'test', 'composer.json', 'composer.lock',
        'config-sample.php', 'index.php', 'vendor');

    private $archive = 'blogtemplate-%VER%.zip';

    function dist($version)
    {
        $this->taskFileSystemStack()->mkdir('dist')->run();
        $this->_exec('zip -r dist/' . str_replace('%VER%', $version, $this->archive) . ' ' . implode(' ', $this->source));
    }

}

