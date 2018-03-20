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

// DB table to use 重大危险源识别与管理
$table = 'think_safety_risksources';

// Table's primary key
$primaryKey = 'major_key';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

$pid = isset($_GET["pid"]) ? $_GET["pid"] : ''; // 文件归属的父级节点编号
$zid = isset($_GET["zid"]) ? $_GET["zid"] : ''; // 文件归属的子级节点编号

//定义数据库中查看的字段与表格中的哪一列相对应
$columns = array(
    array( 'db' => 'major_key',  'dt' => 0 ),
    array( 'db' => 'risk_name',  'dt' => 1 ),
    array( 'db' => 'number',  'dt' => 2 ),
    array( 'db' => 'owner',  'dt' => 3 ),
    array( 'db' => 'risk_date',  'dt' => 4 ),
    array( 'db' => 'remark',  'dt' => 5 )
);

// $_GET = "SELECT * FROM people WHERE 'uid' = '105625886366281950'";
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
require( '../ssp.class.php' );

if(!empty($pid) && !empty($zid)){
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, " pid = '$pid' and zid = '$zid'" )
    );
}else{
    echo json_encode(
        SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
    );
}





