<?php
//特种设备管理表格展示
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
$table = 'think_safety_special_equipment_management';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(//定义数据库中查看的字段与表格中的哪一列相对应
    array( 'db' => 'id',  'dt' => 1 ),//序号
    array( 'db' => 'equip_name',  'dt' => 2 ),//设备名称
    array( 'db' => 'model',  'dt' => 3 ),//型号
    array( 'db' => 'equip_num',  'dt' => 4 ),//设备编号
    array( 'db' => 'manufactur_unit',  'dt' => 5 ),//制造单位
    array( 'db' => 'date_production',  'dt' => 6 ),//出厂日期
    array( 'db' => 'current_state',  'dt' => 7 ),//当前状态
    array( 'db' => 'safety_inspection_num',  'dt' => 8 ),//安全检验合格证书编号
    array( 'db' => 'inspection_unit',  'dt' => 9 ),//检验单位
    array( 'db' => 'entry_time',  'dt' => 10 ),//进场时间
    array( 'db' => 'equip_state',  'dt' => 11 ),//设备状态
    array( 'db' => 'remark',  'dt' => 12 ),//备注

    array( 'db' => 'safety_inspecte_certificate_time',  'dt' => 12 )//备用，安全检验合格证书有效截止日期
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





