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

// DB table to use 外来人员
$table = 'think_safety_eduforeignpersonnel';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(//定义数据库中查看的字段与表格中的哪一列相对应
    array( 'db' => 'id',  'dt' => 0 ),
    array( 'db' => 'content',  'dt' => 2 ),
    array( 'db' => 'edu_time',  'dt' => 3 ),
    array( 'db' => 'address',  'dt' => 4 ),
    array( 'db' => 'lecturer',  'dt' => 5 ),
    array( 'db' => 'trainee',  'dt' => 6 ),
    array( 'db' => 'num',  'dt' => 7 ),
    array( 'db' => 'remark',  'dt' => 8)
);






// $_GET = "SELECT * FROM people WHERE 'uid' = '105625886366281950'";


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( '../ssp.class.php' );

$pid = isset($_GET["pid"]) ? $_GET["pid"] : ''; // 所属一级节点编号
$zid = isset($_GET["zid"]) ? $_GET["zid"] : ''; // 所属分组当前节点编号
$years = isset($_GET["years"]) ? $_GET["years"] : ''; // 年度
$times = isset($_GET["times"]) ? $_GET["times"] : '';// 历史版本

if(!empty($pid) && !empty($zid))
{
    if(!empty($years) && !empty($times)){
        echo json_encode(
            SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "pid = '$pid' and zid = '$zid' and years = '$years' and import_time = '$times'" )
        );
    }else if(!empty($years) && empty($times)){
        echo json_encode(
            SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "pid = '$pid' and zid = '$zid' and years = '$years'" )
        );
    }else if(empty($years) && !empty($times)){
        echo json_encode(
            SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "pid = '$pid' and zid = '$zid' and import_time = '$times'" )
        );
    }else{
        echo json_encode(
            SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "pid = '$pid' and zid = '$zid'" )
        );
    }
}
else{
    echo json_encode(
        SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
    );
}





