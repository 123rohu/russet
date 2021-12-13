<?php
require 'Pdo_methods.php';
class Date_Time extends PdoMethods{


function checkSubmit($dtm){

    $pdo = new PdoMethods();

//$timestamp = strtotime($dtm['datetime']);

//$note = $dtm['note'];

//$mdt = $dtm['datetime'];
//$mdt = date("m/d/Y h:i A", $timestamp);


$sql = "INSERT INTO note_list (note_date,note_content) VALUES(:mdt,:note)";
//$sql = "INSERT INTO note_list (note_date,note_content) VALUES('$timestamp','$note');";
$bindings = [
    [':mdt',$_POST['datetime'],'str'],
    [':note',$_POST['note'],'str'],
];

$result = $pdo->otherBinded($sql, $bindings);

/* HERE I AM RETURNING EITHER AN ERROR STRING OR A SUCCESS STRING */
if($result === 'error'){
    echo "Error ".$sql;
    return 'There was an error adding the name';
}
else {
    echo "Added Successfully";
    return 'Added Successfully';
}

}


function getNotes($begin,$end){
    
    $pdo = new PdoMethods();
$sql = "SELECT * FROM note_list WHERE CAST(note_date AS DATE) BETWEEN '$begin' AND '$end' ORDER BY note_date;";
//$sql = "SELECT * FROM note_list";

$result = $pdo->selectNotBinded($sql);
if($result  == 'error'){
    return 'There has been and error processing your request';
    echo "failure!";
}
else {
    if(count($result ) != 0){
        $notes = array();

       // if($result->num_rows>0){
        
        //while($row = $result->fetch_assoc()){
        
        //$note = array("note time"=>$row['note_date'],"note"=>$row['note_content']);
        foreach ($result as $row)
        {
            $note = array("note time"=>$row['note_date'],"note"=>$row['note_content']);
            array_push($notes,$note);

        }
        
        
        return $notes;
        }

        
        
        // echo "success!";
   // }
    else {
        return 'no files found';
        echo "failure!";
    }
}
//$result = $this->conn->query($sql);
//echo "we found ".$result->num_rows;



}

}

?>