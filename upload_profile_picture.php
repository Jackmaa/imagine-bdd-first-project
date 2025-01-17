<?php
session_start();                                                                   // Start the session
if (isset($_POST["submit"])) {                                                     // Check if the form is submitted
    $targetDir      = "assets/img/";                                                   // Set the target directory for uploads
    $fileName       = $_SESSION["useruid"];                                            // Set the file name to the username
    $fileExtension  = pathinfo($_FILES["profilePicture"]["name"], PATHINFO_EXTENSION); // Get the file extension
    $targetFilePath = $targetDir . $fileName . "." . $fileExtension;                   // Set the target file path
    $fileType       = pathinfo($targetFilePath, PATHINFO_EXTENSION);                   // Get the file type

    // Allow certain file formats
    $allowTypes = ['jpg', 'png', 'jpeg', 'gif'];
    if (in_array($fileType, $allowTypes)) {                          // Check if the file type is allowed
                                                                         // Delete any existing profile picture files with the username
        foreach (glob($targetDir . $fileName . ".*") as $existingFile) { // '.*' to select any extension of a file
            unlink($existingFile);                                           // Delete the existing file
        }

                                                                                          // Upload file to server
        if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFilePath)) { // Move the uploaded file
                                                                                              // Resize the image to 50x50 pixels
            list($width, $height) = getimagesize($targetFilePath);                            // Get the original dimensions
            $newWidth             = 50;                                                       // Set the new width
            $newHeight            = 50;                                                       // Set the new height
            $imageResized         = imagecreatetruecolor($newWidth, $newHeight);              // Create a new true color image

            switch ($fileType) { // Create an image resource from the uploaded file
            case 'jpg':
            case 'jpeg':
                $imageTmp = imagecreatefromjpeg($targetFilePath);
                break;
            case 'png':
                $imageTmp = imagecreatefrompng($targetFilePath);
                break;
            case 'gif':
                $imageTmp = imagecreatefromgif($targetFilePath);
                break;
            default:
                $imageTmp = null;
                break;
            }

            if ($imageTmp) {                                                                                  // Check if the image resource is created
                imagecopyresampled($imageResized, $imageTmp, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height); // Resize the image
                switch ($fileType) {                                                                              // Save the resized image
                case 'jpg':
                case 'jpeg':
                    imagejpeg($imageResized, $targetFilePath);
                    break;
                case 'png':
                    imagepng($imageResized, $targetFilePath);
                    break;
                case 'gif':
                    imagegif($imageResized, $targetFilePath);
                    break;
                }
                imagedestroy($imageTmp);                                                    // Destroy the temporary image resource
                imagedestroy($imageResized);                                                // Destroy the resized image resource
                header("location: user.php?id=" . $_SESSION["userid"] . "&upload=success"); // Redirect to the user page with success message
            } else {
                header("location: user.php?id=" . $_SESSION["userid"] . "&upload=error"); // Redirect to the user page with error message
            }
        } else {
            header("location: user.php?id=" . $_SESSION["userid"] . "&upload=error"); // Redirect to the user page with error message
        }
    } else {
        header("location: user.php?id=" . $_SESSION["userid"] . "&upload=invalid"); // Redirect to the user page with invalid file type message
    }
} else {
    header("location: user.php?id=" . $_SESSION["userid"]); // Redirect to the user page if the form is not submitted
}
?>
