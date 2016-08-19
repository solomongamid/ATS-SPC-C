<?php

/**
* The user.php file is the file which is called everytime when there is action related to a user is to be performed
* It calls the controller and renders the data which controller fetches from the model.
* 
*/

session_start();

require_once('/managers/com/sdlclabs/MenuManager.php');

$menu = new MenuManager();

$menu->handle_menu();

?>