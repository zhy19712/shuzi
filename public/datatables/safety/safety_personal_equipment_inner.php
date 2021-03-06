<?php
//内部设施管理，个人防护装备
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
$table = 'think_safety_personal_equipment_inner';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(//定义数据库中查看的字段与表格中的哪一列相对应
    array( 'db' => 'id',  'dt' => 1 ),//序号
    array( 'db' => 'tool_name',  'dt' => 2 ),//工器具名称
    array( 'db' => 'type_model',  'dt' => 3 ),//规格型号
    array( 'db' => 'number',  'dt' => 4 ),//数量
    array( 'db' => 'batch',  'dt' => 5 ),//批次
    array( 'db' => 'manufacture',  'dt' => 6 ),//生产厂家
    array( 'db' => 'date_product',  'dt' => 7 ),//出厂日期
    array( 'db' => 'check_round',  'dt' => 8 ),//定检周期
    array( 'db' => 'first_check_date',  'dt' => 9 ),//首检日期
    array( 'db' => 'use_position',  'dt' => 10 ),//使用位置
    array( 'db' => 'remark',  'dt' => 11 )//备注
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
else if(!empty($_GET["year"]) && empty($_GET["history_version"]))
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





