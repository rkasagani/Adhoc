<?php

$username_id = logged_in_user::id();

$action = isset($_GET['action']) ? $_GET['action'] : '';

if($_GET['action']=='edit' && $_GET['id']!=''){
    $heading = "Brand Management";
    $title = "Edit Brand";
}
else{
    $heading = "Brand Management";
    $title = "Add Brand";
}
 
require_once(COMMON_CORE_MODEL . "users.class.php");
require_once(COMMON_CORE_MODEL . "brand.class.php");
$objBrand = new brand();
$objBrand->id = (isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])) ? htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8') : $objBrand->id;
$brand = array();

//$objUser = new users();
//$user = $objUser->select("",'where deleted="0" and status="Active"',"id","ASC");

///******* Edit Case *********//
if ($objBrand->id != '' && $action == 'edit')
{


    $objBrand->selectById();  
    if ($objBrand->id == "")
    {
    	$_SESSION['message_type'] = 'error';
        $_SESSION['message'] = "Record does not exist";
        $objBrand->commonFunction->Redirect('./?page=brand_listing');
    }
    else
    {

        $brand = (array) $objBrand;

    }
} 

///******* Insert / Update Case *********//
if (isset($_POST['addEditPhotoSubmit']))
{
    foreach ($objBrand as $k => $d)
    {
        if($k!='password'){
            $objBrand->$k = (isset($_POST[$k])) ? htmlspecialchars($_POST[$k], ENT_QUOTES, 'UTF-8') : $objBrand->$k;
        }
    }
    $objBrand->user_id = $username_id;

    if($_GET['action']=='edit' && $_GET['id']!='')
    {
         $validateData['required'] = array('name' => 'Please provide Brand Name',
                                           'promptmsg' => 'Please provide promptmsg'
                                           );
    }
    else
    {
         $validateData['required'] = array('name' => 'Please provide Brand Name',
                                           'promptmsg' => 'Please provide promptmsg'
                                           );
    }

    $errorMsg = $commonFunction->validate_form($_POST, $validateData);



    $file = ($_FILES['image']['name']);
    if($file=='' && $action =='add')
    {
        $errorMsg[] = 'Please provide Image';
    }
	$brand = (array) $objBrand;
	 $file_photo = ($_FILES['photo']['name']);
    if($file=='' && $action =='add')
    {
        $errorMsg[] = 'Please provide photo';
    }

    $brand = (array) $objBrand;
    //echo "<pre>"; print_R($_FILES);
    //echo "<pre>"; print_r($photo); exit;

    if (count($errorMsg) == 0) {
        if($objBrand->insertUpdate($objBrand->id) == false)
        {

            $errorMsg[] = $objBrand->error_message;
        }
        else
        {
            if (!isset($objBrand->id))
            {
               $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = "Photo successfully added";
               $commonFunction->Redirect('./?page=brand_listing');
               exit;
            } 
            else 
            {
               $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = "Photo successfully updated";
               $commonFunction->Redirect('./?page=brand_listing');
               exit;
            }
        }
    }
}

if ($objBrand->id != '' && $action == 'statusupdate')
{
                $currentStatus = $_REQUEST['status'];
                if($currentStatus == 'Active'){
                $currentStatus = 'Inactive';
                } else {
                $currentStatus = 'Active';

                }
                $commentId = $_REQUEST['id'];
                $brand = $objBrand->updateStatus($currentStatus,$commentId);
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Brand successfully updated";
                $commonFunction->Redirect('./?page=brand_listing');
               exit;

}
///******* Insert / Update Case *********//
?>