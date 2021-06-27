<?php
require_once("includes/header.php");
require_once("includes/classes/VideoPlayer.php");
require_once("includes/classes/VideoInfoSection.php");
require_once("includes/classes/CommentSection.php");
require_once("includes/classes/Comment.php");

if(!isset($_GET["id"])){

    echo "No url passed into page";  //show 404 page
    exit();
}

$video = new Video($con, $_GET["id"], $userLoggedInObj);
$video->incrementViews();

?>

<script src="assets/js/videoPlayerActions.js"></script>
<script src="assets/js/commentActions.js"></script>

<div class="watchLeftColumn">

    <?php

        $videoPlayer = new VideoPlayer($video);
        echo $videoPlayer->create(true);

        $videoInfo = new VideoInfoSection($con, $video, $userLoggedInObj);
        echo $videoInfo->create();

    $commentSection = new CommentSection($con, $video, $userLoggedInObj);
    echo $commentSection->create();

    ?>
</div>

<div class="suggestions">
    <?php
            $videoGrid = new VideoGrid($con, $userLoggedInObj);
            echo $videoGrid->create(null, null, false);
    ?>
</div>


<!-- modal Warning -->
<div class="modal fade left" id="ModalWarning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-notify modal-warning modal-side modal-bottom-left" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->

            <div style="background-color: #4285F4 !important;" class="modal-header">
                <p style="font-size: 20px;" class="heading">Huh, You are secret ninja?! <i style="font-size: 25px;" class="waves-effect mdb-icon-copy fas fa-user-ninja ml-1 white-text" aria-hidden="true"></i></p>

            </div>

            <!--Body-->
            <div class="modal-body">

                <div class="row">
                    <div class="col-3 text-center">
                        <img src="https://mdbootstrap.com/img/Photos/Avatars/img%20(1).jpg" alt="IMG of Avatars"
                             class="img-fluid z-depth-1-half rounded-circle">
                        <div style="height: 10px"></div>
                        <p class="title mb-0">Jane</p>
                        <p class="text-muted " style="font-size: 13px">Assitent</p>
                    </div>

                    <div class="col-9">
                        <p>Please login to your account if you want to like this video.</p>
                        <p class="card-text"><strong>As a logged in user you have unlimited possibilities</strong></p>
                    </div>
                </div>


            </div>

            <!--Footer-->
            <div class="modal-footer justify-content-center">
                <a style="background-color: #4285F4 !important;" href="signIn.php"  class="btn btn-info">Login here</a>

            </div>
        </div>
        <!--/.Content-->
    </div>
</div>
<!-- Central Modal Warning Demo-->



<?php require_once("includes/footer.php"); ?>
