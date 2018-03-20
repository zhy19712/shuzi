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

// DB table to use 绩效评定 type 是1 绩效评定  2评估报告 3工作总结
$table = 'think_safety_evaluation';

// Table's primary key
$primaryKey = 'major_key';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

$type = isset($_GET["types"]) ? $_GET["types"] : ''; // type 是1 绩效评定  2评估报告 3工作总结

//定义数据库中查看的字段与表格中的哪一列相对应
if($type == '1'){
    $columns = array(  // 一岗双责绩效评定
        array( 'db' => 'major_key',  'dt' => 0 ),
        array( 'db' => 'eval_name',  'dt' => 1 ),
        array( 'db' => 'years',  'dt' => 2 ),
        array( 'db' => 'quarter',  'dt' => 3 ),
        array( 'db' => 'eval_date',  'dt' => 4 ),
        array( 'db' => 'owner',  'dt' => 5 ),
        array( 'db' => 'remark',  'dt' => 6 )
    );
}else if($type == '2'){ // 安全标准化评估报告
    $columns = array(
        array( 'db' => 'major_key',  'dt' => 0 ),
        array( 'db' => 'eval_name',  'dt' => 1 ),
        array( 'db' => 'eval_date',  'dt' => 2 ),
        array( 'db' => 'owner',  'dt' => 3 ),
        array( 'db' => 'remark',  'dt' => 4 )
    );
}else{
    $columns = array( // 安全文明施工年度工作总结
        array( 'db' => 'major_key',  'dt' => 0 ),
        array( 'db' => 'eval_name',  'dt' => 1 ),
        array( 'db' => 'years',  'dt' => 2 ),
        array( 'db' => 'eval_date',  'dt' => 3 ),
        array( 'db' => 'owner',  'dt' => 4 ),
        array( 'db' => 'remark',  'dt' => 5 )
    );
}


// $_GET = "SELECT * FROM people WHERE 'uid' = '105625886366281950'";
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
require( '../ssp.class.php' );

if(!empty($type))
{
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, " type = '$type' " )
    );
}else{
    echo json_encode(
        SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
    );
}




