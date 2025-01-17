<?php

// Function to get the profile picture extension for a given username
function getUserProfilePictureExtension($username, $directory = "assets/img/") {
    // Get all files in the directory with the given username and any extension
    $files = glob($directory . $username . ".*");
    // If files are found, return the extension of the first file
    if (! empty($files)) {
        return pathinfo($files[0], PATHINFO_EXTENSION);
    }
    // If no files are found, return null
    return null;
}
?>