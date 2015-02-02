<?php

class RoboFile extends \Robo\Tasks
{

    private $source = array(
        'README.md', 'RoboFile.php', 'app', 'test', 'composer.json', 'composer.lock',
        'config-sample.php', 'index.php', 'vendor');

    private $archive = 'blogtemplate-latest.zip';

    function dist()
    {
        $this->_exec('zip -R ' . $this->archive . ' ' . implode(' ', $this->source));
    }

}

