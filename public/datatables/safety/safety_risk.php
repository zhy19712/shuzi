﻿<?php

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

// DB table to use 安全风险管理
$table = 'think_safety_risk';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

//定义数据库中查看的字段与表格中的哪一列相对应
$columns = array(
    array( 'db' => 'id',  'dt' => 1 ),
    array( 'db' => 'context',  'dt' => 2 ),
    array( 'db' => 'part',  'dt' => 3 ),
    array( 'db' => 'section',  'dt' => 4 ),
    array( 'db' => 'cat',  'dt' => 5 ),
    array( 'db' => 'source',  'dt' => 6 ),
    array( 'db' => 'founddate',  'dt' => 7 ),
    array( 'db' => 'founder',  'dt' => 8 ),
    array( 'db' => 'level',  'dt' => 9 ),
    array( 'db' => 'govern',  'dt' => 10 ),
    array( 'db' => 'governdate',  'dt' => 11 ),
    array( 'db' => 'workdutyer',  'dt' => 12 ),
    array( 'db' => 'completedate',  'dt' => 13 ),
    array( 'db' => 'acceptor',  'dt' => 14 )
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
require( '../ssp.class.php' );

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);




