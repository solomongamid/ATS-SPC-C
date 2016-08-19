<?php
session_start();
require_once('/managers/com/sdlclabs/managegroup.php');


$groupsobj=new Groups();
$groupsobj->showGroups();


?>