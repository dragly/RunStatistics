<?php
require_once('../../../wp-load.php');

if( isset($_GET['project']) ) {
    global $wpdb;
    $project = $_GET['project'];
    $table_name = $wpdb->prefix . "run_statistics_runs";
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT state, COUNT(*) as occurences FROM $table_name WHERE project = '$project' GROUP BY state;" ) );
    $wpdb->print_error();
    $returnArray = array();
    foreach($results as $result) {
        //print_r($result);
        $returnArray[$result->state] = $result->occurences;
    }
}
//print_r($returnArray);
print json_encode($returnArray); 
?>