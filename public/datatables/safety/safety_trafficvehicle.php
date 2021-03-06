<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/18
 * Time: 14:43
 */
//交通车辆
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
$table = 'think_safety_vehicle';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(//定义数据库中查看的字段与表格中的哪一列相对应
    array( 'db' => 'id',  'dt' => 1 ),//交通车辆表自增id
    array( 'db' => 'number_pass',  'dt' => 2 ),//通行证编号
    array( 'db' => 'subord_unit',  'dt' => 3 ),//所属单位
    array( 'db' => 'car_number',  'dt' => 4 ),//车牌号
    array( 'db' => 'vehicle_type',  'dt' => 5 ),//车辆类型
    array( 'db' => 'year_limit',  'dt' => 6 ),//年审有效期
    array( 'db' => 'insurance_limit',  'dt' => 7 ),//保险有效期
    array( 'db' => 'charage_person',  'dt' => 8 ),//负责人/驾驶员
    array( 'db' => 'entry_time',  'dt' => 9 ),//进场时间
    array( 'db' => 'car_state',  'dt' => 10 ),//车辆状态
    array( 'db' => 'remark',  'dt' => 11 )//备注
);






// $_GET = "SELECT * FROM people WHERE 'uid' = '105625886366281950'";


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( '../ssp.class.php' );

if(!empty($_GET["year"]) && !empty($_GET["selfid"]) && !empty($_GET["history_version"]))
{
    $selfid = $_GET["selfid"];
    $year = $_GET["year"];
    $history_version = $_GET["history_version"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "selfid = '$selfid' and date like '%" .$year. "%' and input_time like '%" .$history_version. "%'" )
    );
}
else if(empty($_GET["year"]) && !empty($_GET["selfid"]) && empty($_GET["history_version"]))
{
    $selfid = $_GET["selfid"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "selfid = '$selfid'" )
    );
}else if(!empty($_GET["year"]) && !empty($_GET["selfid"]) && empty($_GET["history_version"]))
{
    $selfid = $_GET["selfid"];
    $year = $_GET["year"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "selfid = '$selfid' and date like '%" .$year. "%'" )
    );
}else if(empty($_GET["year"]) && !empty($_GET["selfid"]) && !empty($_GET["history_version"]))
{
    $selfid = $_GET["selfid"];
    $history_version = $_GET["history_version"];
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "selfid = '$selfid' and input_time like '%" .$history_version. "%'" )
    );
}
else{
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns)
    );
}

