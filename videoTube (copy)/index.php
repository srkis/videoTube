<?php require_once("includes/header.php"); ?>

<div class="videoSection">
    <?php

//https://github.com/manoharys/VideoTube/blob/master/editVideo.php
    //https://www.freetutorialseu.com/?s=php
    //https://www.freetutorialseu.com/make-a-youtube-clone-from-scratch-javascript-php-and-mysql-udemy-course-free-download-2/
    //https://github.com/RishabhV-hash/LiveWire

//https://github.com/akinguyen/ZuyTube
    ///youtube clone php github

        $subscriptionsProvider = new SubscriptionsProvider($con, $userLoggedInObj);
        $subscriptionVideos = $subscriptionsProvider->getVideos();

        $videoGrid = new VideoGrid($con, $userLoggedInObj->getUsername());

        if( User::isLoggedIn() && sizeof($subscriptionVideos) > 0 ){

            echo $videoGrid->create($subscriptionVideos, "Subscriptions", false);
        }

          echo $videoGrid->create(null, "Recommended", false);
    ?>

</div>

<?php require_once("includes/footer.php"); ?>
