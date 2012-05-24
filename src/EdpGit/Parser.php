<?php

namespace EdpGit;

use Exception;

class Parser
{
    /**
     * gitPath 
     *
     * Path to git binary
     * 
     * @var string
     */
    protected $gitPath = '/usr/bin/git';

    /**
     * projectPath 
     *
     * Path to git project
     * 
     * @var string
     */
    protected $projectPath;

    /**
     * construct 
     * 
     * @param string $projectPath 
     * @return void
     */
    public function construct($projectPath)
    {
        $this->setProjectPath($projectPath);
    }

    /**
     * run 
     * 
     * @param string $command 
     * @param string $projectPath 
     * @return string
     */
    public function run($command, $projectPath = false)
    {
        $projectPath = $projectPath ?: $this->getProjectPath();
        $cmd = $this->getGitPath() . ' --git-dir=' . escapeshellarg($projectPath) . ' ' . $command;
        $output = `$cmd`;
        return trim($output);
    }

    /**
     * Get gitPath.
     *
     * @return gitPath
     */
    public function getGitPath()
    {
        return $this->gitPath;
    }
 
    /**
     * Set gitPath.
     *
     * @param $gitPath the value to be set
     */
    public function setGitPath($gitPath)
    {
        $this->gitPath = $gitPath;
        return $this;
    }
 
    /**
     * Get projectPath.
     *
     * @return projectPath
     */
    public function getProjectPath()
    {
        return $this->projectPath;
    }
 
    /**
     * Set projectPath.
     *
     * @param $projectPath the value to be set
     */
    public function setProjectPath($projectPath)
    {
        $realProjectPath = realpath($projectPath . '/.git');
        if ($realProjectPath === false) {
            throw new Exception('Failed to resolve path: '.$projectPath);
        }
        if (!$this->isGitRepo($realProjectPath)) {
            throw new Exception('Given path not a valid git repository: '.$realProjectPath);
        }
        $this->projectPath = $realProjectPath;
        return $this;
    }

    /**
     * isGitRepo 
     * 
     * @param string $path 
     * @return bool
     */
    public function isGitRepo($path)
    {
        $stderr = $this->run('status --porcelain --short 2>&1 1> /dev/null', $path);
        if ($stderr) {
            return false;
        }
        return true;
    }
}
