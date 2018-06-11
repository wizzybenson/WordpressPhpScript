<?php
 function create_db(){
	global $wpdb;
    // creates marque_auto table in database if not exists
    $marque_auto_table = $wpdb->prefix . "marque_auto"; 
    $charset_collate = $wpdb->get_charset_collate();
    $sql1 = "CREATE TABLE IF NOT EXISTS $marque_auto_table (
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `marque` VARCHAR(45) NOT NULL,
    	UNIQUE (`id`)
    ) $charset_collate;";

    // creates sous_marque_auto table in database if not exists
    $sous_marque_auto_table = $wpdb->prefix . "sous_marque_auto"; 
    $charset_collate = $wpdb->get_charset_collate();
    $sql2 = "CREATE TABLE IF NOT EXISTS $sous_marque_auto_table (
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `id_marque_auto` mediumint(9) NOT NULL,
        `model` VARCHAR(45) NOT NULL,
    	UNIQUE (`id`),
    	FOREIGN KEY(id_marque_auto) REFERENCES $marque_auto_table(id)
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    dbDelta( $sql1 );
    dbDelta($sql2);
}
//returns an associative array that has marque as key and array of cars as value.
function parse_CSV($csvfilepath){
	//fetch the csv file and get the lines into an array
	$CSVlines = [];
        $autoCSVFile = file(get_template_directory().'/'.$csvfilepath);
        foreach ($autoCSVFile as $line) {
          $CSVlines[]=str_getcsv($line);

        }

        //explode the CSVlines array on ; and store the returned arrays in explodedCSVlines array
        $explodedCSVLines=[];
        foreach ($CSVlines as $key => $CSVline) {
         $explodedCSVLines[] = preg_split('@;@', $CSVline[0], NULL, PREG_SPLIT_NO_EMPTY);
           
        }

       //make the associative array that has the marques as keys and an array of cars as values
        $marque_auto = [];
        foreach ($explodedCSVLines as $key => $value) {
          if (!empty($value)) {
            if (array_key_exists($value[0], $marque_auto)) {
              $marque_auto[$value[0]][] = $value[1];
            }
            else{
              $marque_auto[$value[0]] = [];
              $marque_auto[$value[0]][] = $value[1];
            }
            
            
          }
          
        }
        array_shift($marque_auto);
        return $marque_auto;


}

 function insert_marque_auto_into_db(){
 	global $wpdb;
 	create_db();
 	$marque_auto = parse_CSV("csv_marque_Modele_290318.csv");
 	foreach ($marque_auto as $marque => $arrAUto) {
 		$wpdb->insert($wpdb->prefix . "marque_auto",array(
 			"marque" => $marque
 		));
 		$lastID = $wpdb->insert_id;
 		foreach ($arrAUto as $key => $model) {
	 			$wpdb->insert($wpdb->prefix . "sous_marque_auto",array(
	 			"id_marque_auto" => $lastID,
	 			"model" => $model
	 		));
 		}
 		
 	}

 }

add_action("insert_auto","insert_marque_auto_into_db");






?>