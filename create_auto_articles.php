<?php
$_SERVER = array(
  "HTTP_HOST" => "http://localhost/wordpress",
  "SERVER_NAME" => "http://localhost/wordpress",
  "REQUEST_URI" => "/",
  "REQUEST_METHOD" => "GET"
);


require_once('../../../wp-load.php');
require_once('../../../wp-admin/includes/taxonomy.php');
require_once('../../../wp-includes/category.php');
require_once('../../../wp-includes/post.php');

 /*
 *@param 
 *@return 
 */

function insert_auto_post(){

  //get the marque category that has no parent as array of objects.
  $marques = get_terms( 
     'category',
     array('hide_empty' =>0,'parent'=>0)
  );

  foreach ($marques as $key => $marque) {
    //skip the wordpress default uncategorized top level category.
    if ($marque->name==="Uncategorized") {
      continue;
    }

    //get the models sub category of the marque.
    $models = get_terms( 
     'category',
     array('hide_empty' =>0,'parent'=>$marque->term_id)
    );
    
    //for every model insert a post.
    foreach ($models as $key => $model) {
     
      $id_post=wp_insert_post(array(
        'post_title'=>$marque->name."-".$model->name,
        'post_content'=>"Fun isn't something one considers when balancing the universe, but this puts a smile on my face",
        'post_category'=>array($model->term_id)
      ));

      echo $id_post."\n";
    }
  
  
  }
}

insert_auto_post();






?>