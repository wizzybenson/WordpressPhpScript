<?php
$_SERVER = array(
  "HTTP_HOST" => "http://localhost/wordpress",
  "SERVER_NAME" => "http://localhost/wordpress",
  "REQUEST_URI" => "/",
  "REQUEST_METHOD" => "GET"
);


require_once('../../../wp-load.php');
require_once('../../../wp-admin/includes/taxonomy.php');
 /**
 *@param $csvfilepath
 *@return marque_auto an associative array that has marque as key and array of cars as value.
 **/
function parse_CSV($csvfilepath){
	//fetch the csv file and get the lines into an array
	$CSVlines = [];
        $autoCSVFile = file(get_template_directory().'/'.$csvfilepath);
        if (!$autoCSVFile) {
            exit();
        }
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

/**
 *calls parse_CSV to get the category array
 *for each marque, it makes a category and uses the id returned as the parent of the sub category
 *@param 
 *@return 
 **/
 function insert_cat_marque_auto(){
 	$marque_auto = parse_CSV("csv_marque_Modele_290318.csv");
 	foreach ($marque_auto as $marque => $arrAUto) {
 		$catID = wp_create_category($marque);
            if ($catID!=0) {
                foreach ($arrAUto as $key => $model) {
                wp_create_category($model,$catID);
            }
        }
        
 	}

 }

 insert_cat_marque_auto();







?>