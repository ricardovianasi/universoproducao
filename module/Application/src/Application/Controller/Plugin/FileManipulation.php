<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 01/10/2017
 * Time: 14:35
 */

namespace Application\Controller\Plugin;

use Zend\Filter\File\RenameUpload;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class FileManipulation extends AbstractPlugin
{
    private $repositoryDir;

    public function __construct($conf)
    {
        if(isset($conf['repository_dir'])) {
            $this->repositoryDir = $conf['repository_dir'];
        }

        $this->repositoryDir = $conf['repository_dir'];
    }

    public function moveToRepository($file)
    {
        if (!isset($file['filename']))
            $file['filename'] = $file['name'];

        $file['new_name'] = $this->generateNewFileName($file);

        $fileDir = $this->getRepositoryDir().$this->generateDirectoryByFilename($file['new_name']);
        if (!is_dir($fileDir)) {
            mkdir($fileDir, 0775, true);
        }
        $fileDir .= $file['new_name'];

        $filter = new RenameUpload($fileDir);
        return $filter->filter($file);
    }

    public function generateNewFileName($file)
    {
        return strtolower(md5_file($file['tmp_name']).
            '_'.str_replace(['.', ','], '', microtime(true)).
            '_'.str_replace(['.', ','], '',mt_rand()).
            '.'.$this->getFileExtension($file['name']));
    }

    // Pegando a extensão do arquivo
    public function getFileExtension($filename)
    {
        //return substr(strrchr($file, '.'), 1);
        $info = pathinfo($filename);
        if(!empty($info['extension'])) {
            return $info['extension'];
        }

        return null;
    }

    /**
     * @param $filename
     *
     * @return string
     */
    public function generateDirectoryByFilename($filename)
    {
        $f = str_split(substr($filename, 0, 4));
        return implode('/', $f).'/';
    }

    /**
     * @return mixed
     */
    public function getRepositoryDir()
    {
        return $this->repositoryDir;
    }

    /**
     * @param mixed $repositoryDir
     */
    public function setRepositoryDir($repositoryDir)
    {
        $this->repositoryDir = $repositoryDir;
    }
}