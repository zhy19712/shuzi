<?php
//特种作业人员管理
include('../conn.php');
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'think_safety_special_operate';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(//定义数据库中查看的字段与表格中的哪一列相对应
    array( 'db' => 'id',  'dt' => 1 ),//序号
    array( 'db' => 'job_name',  'dt' => 2 ),//分包单位/作业队
    array( 'db' => 'name',  'dt' => 3 ),//姓名
    array( 'db' => 'special_type_work',  'dt' => 4 ),//特殊工种
    array( 'db' => 'job_certificate',  'dt' => 5 ),//职业资格证书名称
    array( 'db' => 'date_evidence',  'dt' => 6 ),//取证日期
    array( 'db' => 'effective_date',  'dt' => 7 ),//有效日期
    array( 'db' => 'document_status',  'dt' => 8 ),//证件状态
    array( 'db' => 'remark',  'dt' => 9 )//备注
);






// $_GET = "SELECT * FROM people WHERE 'uid' = '105625886366281950'";


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( '../ssp.class.php' );

//echo json_encode(
//    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
//);

if(!empty($_GET["year"]) && !empty($_GET["selfid"]) && !empty($_GET["history_version"]))
{
    $selfid = $_GET["selfid"];
    $year = $_GET["year"];
    $history_version = $_GET["history_version"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "selfid = '$selfid' and date like '%" .$year. "%' and input_time like '%" .$history_version. "%'" )
    );
}
else if(empty($_GET["year"]) && !empty($_GET["selfid"]) && empty($_GET["history_version"]))
{
    $selfid = $_GET["selfid"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "selfid = '$selfid'" )
    );
}else if(!empty($_GET["year"]) && !empty($_GET["selfid"]) && empty($_GET["history_version"]))
{
    $selfid = $_GET["selfid"];
    $year = $_GET["year"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "selfid = '$selfid' and date like '%" .$year. "%'" )
    );
}else if(empty($_GET["year"]) && !empty($_GET["selfid"]) && !empty($_GET["history_version"]))
{
    $selfid = $_GET["selfid"];
    $history_version = $_GET["history_version"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "selfid = '$selfid' and input_time like '%" .$history_version. "%'" )
    );
}
else{
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns)
    );
}





