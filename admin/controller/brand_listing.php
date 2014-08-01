<?php
$title = "Brand Listing";
$heading = "Brand Listing";
require_once(COMMON_CORE_MODEL . "brand.class.php");
$objBrand = new brand();
$objPagging = new pagging(RECORDS_PER_PAGE, "post");
$pageno = (isset($_REQUEST['pageno']))?$_REQUEST['pageno']:1;
$sort_by = (isset($_REQUEST['sort_by']) && $_REQUEST['sort_by'] != '') ? $_REQUEST['sort_by'] : "id";
$sort_order = (isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] != '') ? $_REQUEST['sort_order'] : "DESC";

//DELETE
if ($_REQUEST['id'] != '' && $_REQUEST['action'] == 'delete') {
    if(!is_numeric($_REQUEST['id'])){
        $_SESSION['message_type'] = 'error';
        $_SESSION['message'] = "No record found";
        $commonFunction->Redirect('./?page=brand_listing');
        exit;
    }
    else{
        $objBrand->id = $_REQUEST['id'];
        $objBrand->selectById();
        if ($objBrand->delete()) {
            if ($objBrand->deleteGreetings()) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Brand successfully deleted";
                if(isset($_REQUEST['pageno'])){
                    $etvars = '&pageno='.$_REQUEST['pageno'];
                }
                if(isset($_REQUEST['searchtext'])){
                        $etvars .= '&searchtext='.$_REQUEST['searchtext'];
                }
                if(isset($_REQUEST['search'])){
                        $etvars .= '&search='.$_REQUEST['search'];
                }
                $commonFunction->Redirect('./?page=brand_listing'.$etvars);
                //exit;
            }
        }
    }
}

if ($_REQUEST['searchtext'] != "")
{
    if ($_REQUEST['search'] == '1')
    {
      $search_name = trim($_REQUEST['searchtext']);
      $brand = $objBrand->select($objPagging, "WHERE p.name like '%" . mysql_real_escape_string($search_name) . "%' and p.deleted=0 ", $sort_by, $sort_order);
    }
    elseif ($_REQUEST['search'] == '2')
    {
        $search_user_name = trim($_REQUEST['searchtext']);
        $brand = $objBrand->select($objPagging, " WHERE p.promptmsg like '%" . mysql_real_escape_string($search_user_name) . "%' and p.deleted=0 ", $sort_by, $sort_order);
    }
    $paggingRecord = $objPagging->InfoArray();
}
else
{
        $brand = $objBrand->select($objPagging, " WHERE p.deleted=0 ", $sort_by, $sort_order);
        $paggingRecord = $objPagging->InfoArray();
}

//echo "<pre>"; print_r($brand); exit;

$extraVars = (isset($_GET) ? $_GET : array());
$pagging = $objPagging->print_pagging($extraVars, ADMIN_PAGGING_STYLE);
$starting_record = $paggingRecord['START_OFFSET'];
$ending_record = $paggingRecord['END_OFFSET'];
$total_records = $paggingRecord['TOTAL_RESULTS'];
?>