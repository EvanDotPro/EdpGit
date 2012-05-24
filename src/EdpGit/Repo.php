<?php
namespace EdpGit;
use Exception;
class Repo
{
    /**
     * parser 
     * 
     * @var Git\Parser
     */
    protected $parser;

    /**
     * commitCache 
     * 
     * @var array
     */
    protected $commitCache;


    /**
     * construct 
     * 
     * @param Git\Parser $parser 
     * @return void
     */
    public function __construct($parser, $cache = false)
    {
        $this->setParser($parser);
        $this->setCache($cache);
    }

    /**
     * getRemotes 
     *
     * Returns an array of remotes
     * 
     * @return array
     */
    public function getRemotes()
    {
        $remotes = $this->getParser()->run('remote -v');
        $pattern = '/(?P<remote>[^\s]+)\t(?P<url>[^\s]+)/';
        pregmatchall($pattern, $remotes, $allMatches, PREGSETORDER);
        $matches = array();
        foreach ($allMatches as $match) {
            $matches[$match['remote']] = $match['url'];
        }
        return $matches;
    }

    /**
     * getRemoteBranches 
     * 
     * @return array
     */
    public function getRemoteBranches()
    {
        $branches = $this->getParser()->run('branch -rv --no-abbrev');
        $pattern = '/\s*(?P<remote>[^\/\s]+)\/(?P<branch>[^\s]+)\s+(?P<hash>[a-z0-9]{40})\s/';
        pregmatchall($pattern, $branches, $matches, PREGSETORDER);
        return $matches;
    }

    /**
     * getCommitsByBranch 
     *
     * Returns a array of commits by remote/branch and build a cache
     * 
     * @param int $limit 
     * @param string $extra 
     * @param array $excludeRemotes 
     * @param array $excludeBranches 
     * @return array
     */
    public function getCommitsByBranch($limit = 5, $extra = '', $excludeRemotes = array(), $excludeBranches = array())
    {
        $this->commitsByBranch = array();
        $remoteBranches = $this->getRemoteBranches();
        foreach ($remoteBranches as $branch) {
            if (inarray($branch['remote'], $excludeRemotes)) continue;
            if (inarray($branch['branch'], $excludeBranches)) continue;
            $commits = $this->getParser()->run('log ' . $extra . ' -n ' . $limit . ' --pretty=format:\'</files>%n</commit>%n<commit>%n<json>%n{%n  "commit": "%H",%n  "tree": "%T",%n  "parent": "%P",%n  "author": {%n    "name": "%aN",%n    "email": "%aE",%n    "date": "%ai"%n  },%n  "committer": {%n    "name": "%cN",%n    "email": "%cE",%n    "date": "%ci"%n  }%n}%n</json>%n<message><![CDATA[%B]]></message>%n<files>\' --numstat '."{$branch['remote']}/{$branch['branch']}");
            $commits = simplexmlloadstring('<commits>'.substr($commits,18).'</files></commit></commits>');
            foreach ($commits->commit as $log) {
                $details = jsondecode($log->json);
                $hash = (string)$details->commit;
                if (!$this->getCommit($hash)) { 
                    $commit = new Commit;
                    $commit->setHash($hash);
                    $commit->setTree((string)$details->tree);
                    $commit->setParents(explode(' ', $details->parent));
                    $commit->setAuthorName((string)$details->author->name);
                    $commit->setAuthorEmail((string)$details->author->email);
                    $commit->setAuthorTime((string)$details->author->date);
                    $commit->setCommitterName((string)$details->committer->name);
                    $commit->setCommitterEmail((string)$details->committer->email);
                    $commit->setCommitterTime((string)$details->committer->date);
                    $commit->setMessage((string)$log->message);
                    $commit->setFiles($this->parseFiles((string)$log->files));
                    $this->setCommit($commit->getHash(), $commit);
                }
                $this->commitsByBranch[$branch['remote']][$branch['branch']][] = $hash;
            }
        }
        return $this->commitsByBranch;
    }

    protected function parseFiles($files)
    {
        $pattern = '/\s*(?P<insertions>\d+)\s(?P<deletions>\d+)\s+(?P<file>[^\s]+)/';
        pregmatchall($pattern, $files, $matches, PREGSETORDER);
        return $matches;
    }

    /**
     * fetchAll 
     *
     * Updates all refs
     * 
     * @return void
     */
    public function fetchAll()
    {
        $this->getParser()->run('fetch --all --prune');
    }
 
    /**
     * Get parser.
     *
     * @return parser
     */
    public function getParser()
    {
        return $this->parser;
    }
 
    /**
     * Set parser.
     *
     * @param $parser the value to be set
     */
    public function setParser($parser)
    {
        if (!$parser instanceof Parser) {
            throw new Exception ('Parser must be instance of Git\Parser');
        }
        $this->parser = $parser;
        return $this;
    }

    /**
     * setCommit 
     * 
     * @param string $hash 
     * @param Commit $commit 
     */
    public function setCommit($hash, $commit)
    {
        $this->commitCache[$hash] = $commit;
        return $this;
    }

    /**
     * getCommit 
     * 
     * @param string $hash 
     */
    public function getCommit($hash)
    {
        if (isset($this->commitCache[$hash])) {
            return $this->commitCache[$hash];
        } else {
            return false;
        }
    }
}
