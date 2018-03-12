<?php

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

// DB table to use 法规标准识别
$table = 'think_safety_record';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(//定义数据库中查看的字段与表格中的哪一列相对应
    array( 'db' => 'id',  'dt' => 0 ),
    array( 'db' => 'record_name',  'dt' => 1 ),
    array( 'db' => 'original_number',  'dt' => 2 ),
    array( 'db' => 'replace_number',  'dt' => 3 ),
    array( 'db' => 'replace_time',  'dt' => 4 ),
    array( 'db' => 'owner',  'dt' => 5 ),
    array( 'db' => 'record_type',  'dt' => 6 )
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

if(!empty($_GET["years"]))
{
    $years = $_GET["years"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "years = '$years'" )
    );
}
else{
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "years = 'empty'" )
    );
}






