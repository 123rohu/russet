<?php
require 'Pdo_methods.php';

class Crud extends PdoMethods{

	public function getNames(){
		
		/* CREATE AN INSTANCE OF THE PDOMETHODS CLASS*/
		$pdo = new PdoMethods();

		/* CREATE THE SQL */
		$sql = "SELECT * FROM file_names";

		//PROCESS THE SQL AND GET THE RESULTS
		$records = $pdo->selectNotBinded($sql);

		/* IF THERE WAS AN ERROR DISPLAY MESSAGE */
		if($records == 'error'){
			return 'There has been and error processing your request';
		}
		else {
			if(count($records) != 0){
				 return $this->displaylinks($records);
			}
			else {
				return 'no files found';
			}
		}
	}

   private function displayLinks($records){
       $list = '<ul>';
       foreach ($records as $row){
           $list .= "<li><a target='_blank' href={$row['file_path']}>{$row['entered_file_name']}</li>";
       }
       $list .= '</ul>';
       return $list;
}

	public function addFile(){
	
		$pdo = new PdoMethods();

		/* HERE I CREATE THE SQL STATEMENT I AM BINDING THE PARAMETERS */
		$sql = "INSERT INTO file_names (file_name, file_path, entered_file_name) VALUES (:fname, :fpath, :enteredfname)";

			 
	    /* THESE BINDINGS ARE LATER INJECTED INTO THE SQL STATEMENT THIS PREVENTS AGAIN SQL INJECTIONS */
	    $bindings = [
			[':fname',$_FILES["selectedFile"]["name"],'str'],
			[':fpath',"files/".$_FILES["selectedFile"]["name"],'str'],
			[':enteredfname',$_POST['enteredFileName'],'str'],
		];

		/* I AM CALLING THE OTHERBINDED METHOD FROM MY PDO CLASS */
		$result = $pdo->otherBinded($sql, $bindings);

		/* HERE I AM RETURNING EITHER AN ERROR STRING OR A SUCCESS STRING */
		if($result === 'error'){
			return 'There was an error adding the name';
		}
		else {
			return 'Name has been added';
		}
	}

	public function updateNames($post){
		$error = false;

		if(isset($post['inputDeleteChk'])){

			foreach($post['inputDeleteChk'] as $id){
				$pdo = new PdoMethods();

				/* HERE I CREATE THE SQL STATEMENT I AM BINDING THE PARAMETERS */
				$sql = "UPDATE file_names SET file_name = :fname, file_path = :fpath, entered_file_name = :enteredfname WHERE id = :id";

				//THE ^^ WAS USED TO MAKE EACH ITEM UNIQUE BY COMBINING FNAME WITH THEY ID
				$bindings = [
					[':fname', $post["fname^^{$id}"], 'str'],
					[':fpath', $post["fpath^^{$id}"], 'str'],
					[':enteredfname', $post["color^^{$id}"], 'str'],
					[':id', $id, 'str']
				];

				$result = $pdo->otherBinded($sql, $bindings);

				if($result === 'error'){
					$error = true;
					break;
				}
				
			}

			if($error){
				return "There was an error in updating a name or names";
			}
			else {
				return "All names updated";
			}
		}
		else {
			return "No names selected to update";
		}
	}

	public function deleteNames($post){
		$error = false;
		if(isset($post['inputDeleteChk'])){
			foreach($post['inputDeleteChk'] as $id){
				$pdo = new PdoMethods();

				$sql = "DELETE FROM file_names WHERE id=:id";
				
				$bindings = [
					[':id', $id, 'int'],
				];


				$result = $pdo->otherBinded($sql, $bindings);

				if($result === 'error'){
					$error = true;
					break;
				}
			}
			if($error){
				return "There was an error in deleting a name or names";
			}
			else {
				return "All names deleted";
			}

		}
		else {
			return "No names selected to delete";
		}
	}

	/*THIS FUNCTION TAKES THE DATA FROM THE DATABASE AND RETURN AN UNORDERED LIST OF THE DATA*/
	private function createList($records){
		$list = '<ol>';
		foreach ($records as $row){
			$list .= "<li>Name: {$row['file_name']} {$row['file_path']} - Eye Color: {$row['entered_file_name']} </li>";
		}
		$list .= '</ol>';
		return $list;
	}

	/*THIS FUNCTION TAKES THE DATA AND RETURNS THE DATA IN INPUT ELEMENTS SO IT CAN BE EDITED.  */
	private function createInput($records){
		$output = "<form method='post' action='update_delete_name.php'>";
		$output .= "<input class='btn btn-success' type='submit' name='update' value='Update'>";
		$output .= "<input class='btn btn-danger' type='submit' name='delete' value='Delete'>";
		$output .= "<table class='table table-bordered table-striped'><thead><tr>";
		$output .= "<th>First Name</th><th>Last Name</th><th>Eye Color</th><th>Update/Delete</th><tbody>";
		foreach ($records as $row){
			$output .= "<tr><td><input type='text' class='form-control' name='fname^^{$row['id']}' value='{$row['file_name']}'></td>";

			$output .= "<td><input type='text' class='form-control' name='fpath^^{$row['id']}' value='{$row['file_path']}'></td>";

			$output .= "<td><input type='text' class='form-control' name='enteredfname^^{$row['id']}' value='{$row['entered_file_name']}'></td>";

			$output .= "<td><input type='checkbox' name='inputDeleteChk[]' value='{$row['id']}'></td></tr>";
		}
		
		$output .= "</tbody></table></form>";
		return $output;
	}
}