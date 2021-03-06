<?php
//车辆管理加油记录表
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
$table = 'think_safety_refueling_record';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(//定义数据库中查看的字段与表格中的哪一列相对应
    array( 'db' => 'id',  'dt' => 0 ),
    array( 'db' => 'kilometre_number',  'dt' => 1 ),//当前公里数
    array( 'db' => 'kilometre_difference',  'dt' => 2 ),//本次与上次公里差
    array( 'db' => 'refueling_time',  'dt' => 3 ),//本次加油时间
    array( 'db' => 'last_refueling_time',  'dt' => 4 ),//上次加油时间
    array( 'db' => 'refueling_type',  'dt' => 5 ),//加油类型
    array( 'db' => 'fuel_quantity',  'dt' => 6 ),//加油量(升)
    array( 'db' => 'price',  'dt' => 7 ),//单价(元)
    array( 'db' => 'total',  'dt' => 8 ),//合计(元)
    array( 'db' => 'kilometer_oil',  'dt' => 9 )//百公里耗油
);






// $_GET = "SELECT * FROM people WHERE 'uid' = '105625886366281950'";


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( '../ssp.class.php' );

if(!empty($_GET["pid"]))
{
    $pid = $_GET["pid"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "pid = '$pid'" )
    );
}else{
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns)
    );
}







