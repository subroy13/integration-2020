<?php 
include_once('../config.php');
require_once( $ROOT_PATH.'/Model/eventregistration_model.php');
require_once( $ROOT_PATH.'/DAL/eventregistration_dal.php');

class eventregistration_controller {
        static function addEventRegistration() {
                $userid = 0;
                $eventid = 0;
                $paymentid = null;
                $status = "PENDING";

                if (isset($_REQUEST['reg_userid'])) {
                        $userid = $_REQUEST['reg_userid'];
                }

                if (isset($_REQUEST['reg_eventid'])) {
                        $eventid = $_REQUEST['reg_eventid'];
                }

                $eventRegDal = new eventregistration_dal;
                $eventRegObj = new eventregistration_model;

                $eventRegObj->userid = $userid;
                $eventRegObj->paymentid = $paymentid;
                $eventRegObj->eventid = $eventid;
                $eventRegObj->status = $status;

                $retval = $eventRegDal->insertDatas($eventRegObj);
                return $retval;
        }
}
?>

<?php
$mode="";
if (isset($_REQUEST['mode']))
{
    if(!empty($_REQUEST['mode']))
    {
        $mode = $_REQUEST['mode'];
    }
}


if($mode=="add"){
    $retval = eventregistration_controller::addEventRegistration();
    echo("||||". $retval);
}
else if($mode=="update"){
    $retval = eventregistration_controller::editEventRegistration(); 
    echo("||||". $retval);
}
else if($mode=="delete"){
    $retval = eventregistration_controller::deleteEventRegistration();  
    echo("||||". $retval);
}
?>