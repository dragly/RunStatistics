<?php
require_once('../../../wp-load.php');

if( isset($_GET['project']) ) {
    //Reads the posted values
    $success = $_GET["success"];
    $project = $_GET["project"];

    global $wpdb;
    $runid = uniqid("", true);
    $wpdb->show_errors();
    $table_name = $wpdb->prefix . "run_statistics_runs";
    $rows_affected = $wpdb->insert( $table_name, 
                                    array( 'project' => $project,
                                            'runid' => $runid,
                                            'state' => "running") 
                                   );
    if($rows_affected) {
        print json_encode(array("runid" => $runid));
    } else {
        $wpdb->print_error();
    }
}
?> 
