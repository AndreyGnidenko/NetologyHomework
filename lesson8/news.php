<?php

class News
{
    private $dateTime;
    private $title;
    private $tags = array();
    private $content;
    
    public function __construct ($title)
    {
        $this->dateTime = new DateTime();
        $this->title = $title;
    }

    public function addTag ($tag)
    {
       $this->tags[] = $tag;
    }
    
    public function setContent ($content)
    {
        $this->content = $content;
    }
    
    public function getFullText()
    {
        return $this->dateTime->format('Y-m-d H:i:s').'<br/><h3>'.$this->title.'</h3>'.$this->content.'<br/><i>Tags: '.implode($this->tags).'</i>';
    }
}
