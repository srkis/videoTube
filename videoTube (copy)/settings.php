<?php
require_once("includes/header.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/SettingsFormProvider.php");

if( ! User::isLoggedIn() ) {
        header("Location: signIn.php");
}

$detailsMessage = "";
$passwordMessage = "";

$fromProvider = new SettingsFormProvider();

if(isset($_POST["saveDetailsButton"])){

        $account = new Account($con);

        $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
        $lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);
        $email = FormSanitizer::sanitizeFormString($_POST["email"]);

        if($account->updateDetails($firstName, $lastName, $userLoggedInObj->getUsername(), $email)){
                $detailsMessage = "<div class='alert alert-success' >
                    <strong>SUCCESS!</strong> Details updated successfully!
                </div>";
        }else{

                $errMessage = $account->getFirstError();

                if($errMessage == "" ) $errMessage = "Something went wrong";

                $detailsMessage = "<div class='alert alert-danger' >
                    <strong>ERROR!</strong> $errMessage
                </div>";
        }


}

if(isset($_POST["savePasswordButton"])){

        $account = new Account($con);

        $oldPassword = FormSanitizer::sanitizeFormPassword($_POST["oldPassword"]);
        $newPassword = FormSanitizer::sanitizeFormPassword($_POST["newPassword"]);
        $newPassword2 = FormSanitizer::sanitizeFormPassword($_POST["newPassword2"]);

        if($account->updatePassword($oldPassword, $newPassword, $userLoggedInObj->getUsername(), $newPassword2)){
                $passwordMessage = "<div class='alert alert-success' >
                    <strong>SUCCESS!</strong> Password updated successfully!
                </div>";
        }else{

                $errMessage = $account->getFirstError();

                if($errMessage == "" ) $errMessage = "Something went wrong";

                $passwordMessage = "<div class='alert alert-danger' >
                    <strong>ERROR!</strong> $errMessage
                </div>";
        }


}



?>

<div class="settingsContainer column">

        <div class="formSection">
                <div class="message">
                        <?php echo $detailsMessage; ?>
                </div>
                <?php
                    echo $fromProvider->createUserDetailsForm(
                        isset($_POST["firstName"]) ? $_POST["firstName"] : $userLoggedInObj->getFirstName(),
                        isset($_POST["lastName"]) ? $_POST["lastName"] : $userLoggedInObj->getLastName(),
                        isset($_POST["email"]) ? $_POST["email"] : $userLoggedInObj->getEmail()
                    );
                ?>
        </div>

        <div class="formSection">
                <div class="message">
                        <?php echo $passwordMessage; ?>
                </div>
                <?php
                echo $fromProvider->createPasswordForm();
                ?>
        </div>
</div>
