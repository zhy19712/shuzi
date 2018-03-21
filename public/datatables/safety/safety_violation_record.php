<?php
//作业安全，作业行为，反违章记录
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
$table = 'think_safety_violation_record';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(//定义数据库中查看的字段与表格中的哪一列相对应
    array( 'db' => 'id',  'dt' => 1 ),//序号
    array( 'db' => 'full_name',  'dt' => 2 ),//姓名
    array( 'db' => 'violation_situation',  'dt' => 3 ),//违章情况
    array( 'db' => 'category_violation',  'dt' => 4 ),//违章类别
    array( 'db' => 'violation_time',  'dt' => 5 ),//违章时间
    array( 'db' => 'scoring_situation',  'dt' => 6 ),//记分情况
    array( 'db' => 'economic_punishment',  'dt' => 7 ),//经济处罚
    array( 'db' => 'disposal_results',  'dt' => 8 ),//处置结果
    array( 'db' => 'rectify_rectify',  'dt' => 9 )//整改情况
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






