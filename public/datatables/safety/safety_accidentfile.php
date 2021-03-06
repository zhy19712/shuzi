<?php
//事故档案
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
$table = 'think_safety_accident_file';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(//定义数据库中查看的字段与表格中的哪一列相对应
    array( 'db' => 'id',  'dt' => 0 ),
    array( 'db' => 'accident_name',  'dt' => 1 ),
    array( 'db' => 'accident_time',  'dt' => 2 ),
    array( 'db' => 'accident_place',  'dt' => 3 ),
    array( 'db' => 'accident_type',  'dt' => 4 ),
    array( 'db' => 'accident_level',  'dt' => 5 ),
    array( 'db' => 'death_toll',  'dt' => 6 ),
    array( 'db' => 'injure',  'dt' => 7 ),
    array( 'db' => 'light_injure',  'dt' => 8 ),
    array( 'db' => 'economic_loss',  'dt' => 9 ),
    array( 'db' => 'accident_unit',  'dt' => 10 ),
    array( 'db' => 'accident_result',  'dt' => 11 ),
    array( 'db' => 'remark',  'dt' => 12 )
);






// $_GET = "SELECT * FROM people WHERE 'uid' = '105625886366281950'";


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( '../ssp.class.php' );

if(!empty($_GET["year"]))
{
    $year = $_GET["year"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "accident_time like '%" .$year. "%'" )
    );
}else{
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns)
    );
}







