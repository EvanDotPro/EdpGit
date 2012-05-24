<?php
namespace Git;
use DateTime;
class Commit
{
    /**
     * hash 
     * 
     * @var string
     */
    protected $hash;

    /**
     * tree 
     * 
     * @var string
     */
    protected $tree;

    /**
     * parents 
     * 
     * @var array
     */
    protected $parents;

    /**
     * authorName 
     * 
     * @var string
     */
    protected $authorName;

    /**
     * authorEmail 
     * 
     * @var string
     */
    protected $authorEmail;

    /**
     * authorTime 
     * 
     * @var DateTime
     */
    protected $authorTime;

    /**
     * committerName 
     * 
     * @var string
     */
    protected $committerName;

    /**
     * committerEmail 
     * 
     * @var string
     */
    protected $committerEmail;

    /**
     * committerTime 
     * 
     * @var DateTime
     */
    protected $committerTime;

    /**
     * subject 
     * 
     * @var string
     */
    protected $subject;

    /**
     * message 
     * 
     * @var string
     */
    protected $message;

    /**
     * files 
     * 
     * @var array
     */
    protected $files;
 
    /**
     * Get hash.
     *
     * @return hash
     */
    public function getHash()
    {
        return $this->hash;
    }
 
    /**
     * Set hash.
     *
     * @param $hash the value to be set
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }
 
    /**
     * Get tree.
     *
     * @return tree
     */
    public function getTree()
    {
        return $this->tree;
    }
 
    /**
     * Set tree.
     *
     * @param $tree the value to be set
     */
    public function setTree($tree)
    {
        $this->tree = $tree;
        return $this;
    }
 
    /**
     * Get parents.
     *
     * @return parents
     */
    public function getParents()
    {
        return $this->parents;
    }
 
    /**
     * Set parents.
     *
     * @param $parents the value to be set
     */
    public function setParents($parents)
    {
        $this->parents = $parents;
        return $this;
    }
 
    /**
     * Get authorName.
     *
     * @return authorName
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }
 
    /**
     * Set authorName.
     *
     * @param $authorName the value to be set
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
        return $this;
    }
 
    /**
     * Get authorEmail.
     *
     * @return authorEmail
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * getAuthorGravatar 
     * 
     * @return string
     */
    public function getAuthorGravatar()
    {
        return md5(strtolower(trim($this->getAuthorEmail())));
    }
 
    /**
     * Set authorEmail.
     *
     * @param $authorEmail the value to be set
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;
        return $this;
    }
 
    /**
     * Get authorTime.
     *
     * @return authorTime
     */
    public function getAuthorTime()
    {
        return $this->authorTime;
    }
 
    /**
     * Set authorTime.
     *
     * @param $authorTime the value to be set
     */
    public function setAuthorTime($authorTime)
    {
        $this->authorTime = new DateTime($authorTime);
        return $this;
    }
 
    /**
     * Get committerName.
     *
     * @return committerName
     */
    public function getCommitterName()
    {
        return $this->committerName;
    }
 
    /**
     * Set committerName.
     *
     * @param $committerName the value to be set
     */
    public function setCommitterName($committerName)
    {
        $this->committerName = $committerName;
        return $this;
    }
 
    /**
     * Get committerEmail.
     *
     * @return committerEmail
     */
    public function getCommitterEmail()
    {
        return $this->committerEmail;
    }

    /**
     * getCommitterGravatar 
     * 
     * @return string
     */
    public function getCommitterGravatar()
    {
        return md5(strtolower(trim($this->getCommitterEmail())));
    }
 
    /**
     * Set committerEmail.
     *
     * @param $committerEmail the value to be set
     */
    public function setCommitterEmail($committerEmail)
    {
        $this->committerEmail = $committerEmail;
        return $this;
    }
 
    /**
     * Get committerTime.
     *
     * @return committerTime
     */
    public function getCommitterTime()
    {
        return $this->committerTime;
    }
 
    /**
     * Set committerTime.
     *
     * @param $committerTime the value to be set
     */
    public function setCommitterTime($committerTime)
    {
        $this->committerTime = new DateTime($committerTime);
        return $this;
    }

    /**
     * Get subject.
     *
     * @return subject
     */
    public function getSubject()
    {
        return $this->subject;
    }
 
    /**
     * Set subject.
     *
     * @param $subject the value to be set
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }
 
    /**
     * Get message.
     *
     * @return message
     */
    public function getMessage()
    {
        return $this->message;
    }
 
    /**
     * Set message.
     *
     * @param $message the value to be set
     */
    public function setMessage($message)
    {
        $this->message = trim($message);
        $lines = explode("\n", $this->message);
        $this->setSubject($lines[0]);
        return $this;
    }
 
    /**
     * Get files.
     *
     * @return files
     */
    public function getFiles()
    {
        return $this->files;
    }
 
    /**
     * Set files.
     *
     * @param $files the value to be set
     */
    public function setFiles($files)
    {
        $this->files = array();
        foreach ($files as $file) {
            if (count($file) == 7) {
                unset($file[0], $file[1], $file[2], $file[3]);
                // <OCD>
                $file['insertions'] = (int) $file['insertions'];
                $file['deletions'] = (int) $file['deletions'];
                // </OCD>
                $this->files[] = $file;
            }
        }
        return $this;
    }
}
