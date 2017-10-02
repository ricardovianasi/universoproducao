<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FileManipulation extends AbstractHelper
{
    protected $repositoryDir;
    protected $baseURL;

    public function __construct($config)
    {
        if(isset($config['repository_dir'])) {
            $this->repositoryDir = $config['repository_dir'];
        }

        if(isset($config['base_url'])) {
            $this->baseURL = $config['base_url'];
        }
    }

    public function __invoke()
	{
	    return $this;
	}

	public function url($fileName)
    {
        return rtrim($this->baseURL, '/') . '/' .$this->generateDirectoryByFilename($fileName) .$fileName;
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