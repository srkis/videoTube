<?php
require_once("includes/classes/ButtonProvider.php");


class VideoInfoControls
{
    private $userLoggedInObj;
    private  $video;

    public function __construct($video, $userLoggedInObj)
    {
        $this->userLoggedInObj = $userLoggedInObj;
        $this->video = $video;
    }

    public function create()
    {
        $likeButton = $this->createLikeButton();
        $dislikeButton = $this->createDislikeButton();

         return "<div class='controls'>
                     $likeButton
                     $dislikeButton
                 </div>";

    }

    private function createLikeButton()
    {
        $text = $this->video->getLikes();
        $videoId = $this->video->getId();
        $action = "likeVideo(this, $videoId)"; //javascript function
        $class = "likeButton";

        $imageSrc = "assets/images/icons/thumb-up.png";

        if($this->video->wasLikedBy()){

            $imageSrc = "assets/images/icons/thumb-up-active.png";
        }

        return ButtonProvider::createButton($text, $imageSrc, $action, $class);
    }

    private function createDislikeButton()
    {
        $text = $this->video->getDislikes();
        $videoId = $this->video->getId();
        $action = "dislikeVideo(this, $videoId)"; //javascript function
        $class = "dislikeButton";

        $imageSrc = "assets/images/icons/thumb-down.png";

        if($this->video->wasDislikedBy()){

            $imageSrc = "assets/images/icons/thumb-down-active.png";
        }

        return ButtonProvider::createButton($text, $imageSrc, $action, $class);
    }
}