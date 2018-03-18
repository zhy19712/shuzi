<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/18
 * Time: 15:14
 */
//应急处置
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
$table = 'think_safety_emergency_disposal';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(//定义数据库中查看的字段与表格中的哪一列相对应
    array( 'db' => 'id',  'dt' => 0 ),//应急处置表自增id
    array( 'db' => 'preplan_file_name',  'dt' => 1 ),//文件名称
    array( 'db' => 'preplan_number',  'dt' => 2 ),//编号
    array( 'db' => 'version_number',  'dt' => 3 ),//版本号
    array( 'db' => 'alternative_version',  'dt' => 4 ),//替代版本号
    array( 'db' => 'applicability',  'dt' => 5 ),//适用性评价
    array( 'db' => 'date',  'dt' => 6 ),//上传时间
    array( 'db' => 'owner',  'dt' => 7 ),//上传人
    array( 'db' => 'remark',  'dt' => 8 ),//备注
    array( 'db' => 'preplan_state',  'dt' => 9 )//状态
);





// $_GET = "SELECT * FROM people WHERE 'uid' = '105625886366281950'";


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( '../ssp.class.php' );

if(!empty($_GET["year"]) && !empty($_GET["nameid"]))
{
    $year = $_GET["year"];
    $nameid = $_GET["nameid"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "id = '$nameid' and date like '%" .$year. "%'" )
    );
}
else if(empty($_GET["year"]) && !empty($_GET["nameid"]))
{
    $nameid = $_GET["nameid"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "id = '$nameid'" )
    );
}else if(!empty($_GET["year"]) && empty($_GET["nameid"]))
{
    $year = $_GET["year"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "date like '%" .$year. "%'" )
    );
}else{
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns)
    );
}
