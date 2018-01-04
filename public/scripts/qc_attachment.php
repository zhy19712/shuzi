<?php


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
$table = 'think_qc_attachment';

// Table's primary key
$primaryKey = 'id';

// SQL server connection information数据库连接信息
$sql_details = array(
    'user' => 'root',
    'pass' => 'admin',
    'db'   => 'shuzi',
    'host' => '127.0.0.1'
);




// $_GET = "SELECT * FROM people WHERE 'uid' = '105625886366281950'";


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( 'ssp.class.php' );

if(!empty($_GET["group_id"])&&!empty($_GET["table_name"]))
{
    $group_id = $_GET["group_id"];
    $table_name = $_GET["table_name"];
    if($table_name == "ss" || $table_name == "smyxzl"){
        $columns = array(//定义数据库中查看的字段与表格中的哪一列相对应
            array( 'db' => 'id',  'dt' => 0 ),
            array( 'db' => 'name',  'dt' => 1 ),
            array( 'db' => 'owner',  'dt' => 2 ),
            array( 'db' => 'date',  'dt' => 3 )
        );
    }else{
        $columns = array(//定义数据库中查看的字段与表格中的哪一列相对应
            array( 'db' => 'id',  'dt' => 0 ),
            array( 'db' => 'name',  'dt' => 1 ),
            array( 'db' => 'owner',  'dt' => 2 ),
            array( 'db' => 'date',  'dt' => 3 )
        );
    }


    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "type = '1' and group_id = '$group_id' and table_name = '$table_name'" )
    );
}
else{
    echo json_encode(
        SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns)
    );
}








