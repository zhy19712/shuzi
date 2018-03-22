<?php
//设备管理
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
$table = 'think_safety_device_management';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(//定义数据库中查看的字段与表格中的哪一列相对应
    array( 'db' => 'id',  'dt' => 1 ),
    array( 'db' => 'asset_name',  'dt' => 2 ),//资产名称
    array( 'db' => 'use_department',  'dt' => 3 ),//使用部门
    array( 'db' => 'user',  'dt' => 4 ),//使用人
    array( 'db' => 'place_storage',  'dt' => 5 ),//存放地点
    array( 'db' => 'equipment_state',  'dt' => 6 ),//设备状态
    array( 'db' => 'remark',  'dt' => 7 )//备注
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

if(!empty($_GET["year"]) && !empty($_GET["history_version"]))
{
    $year = $_GET["year"];
    $history_version = $_GET["history_version"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "date like '%" .$year. "%' and input_time like '%" .$history_version. "%'" )
    );
}
else if(!empty($_GET["year"])  && empty($_GET["history_version"]))
{
    $year = $_GET["year"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "date like '%" .$year. "%'" )
    );
}else if(empty($_GET["year"]) && !empty($_GET["history_version"]))
{
    $history_version = $_GET["history_version"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "input_time like '%" .$history_version. "%'" )
    );
}
else{
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns)
    );
}





