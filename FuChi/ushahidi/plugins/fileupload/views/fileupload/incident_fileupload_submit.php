<div class="row" style="border: 2px solid gray; padding: 10px; padding-bottom:25px; width:340px; margin:auto; margin-top:10px; margin-bottom:10px;" >

	<script type="text/javascript">

			
		function addFileField(div, field, hidden_id, field_type) {
			var id = document.getElementById(hidden_id).value;
			$("#" + div).append("<div class=\"row link-row second\" id=\"" + field + "_" + id + "\"><a href=\"#\" class=\"add\" style=\"float:right;\" onClick=\"addFileField('" + div + "','" + field + "','" + hidden_id + "','" + field_type + "'); return false;\">add</a><a href=\"#\" class=\"rem\"   style=\"float:right;\" onClick='removeFileField(\"#" + field + "_" + id + "\"); return false;'>remove</a>Description: <input type=\"text\" name=\"fileUpload_description_"+id+"\" id=\"fileUpload_description_"+id+"\"/> <br/>File:<input type=\"" + field_type + "\" name=\"" + field + "_" + id + "\" class=\"" + field_type + " long\" /></div>");
			
			$("#" + field + "_" + id).effect("highlight", {}, 800);

			id = (id - 1) + 2;
			document.getElementById(hidden_id).value = id;
		}

		function removeFileField(id) {
			var answer = confirm("Are You Sure You Want To Delete This File?");
		    if (answer){
				$(id).remove();
		    }
			else{
				return false;
		    }
		}
	</script>


	
	<h4>
		Upload Files
		<br/>
		<span style="font-size:10px;">
			Use this to upload files, such as PDFs, Word documents, and other files.
		</span>
	</h4>
	<div id="divFileUpload">

		<div class="row link-row-file">
			<a href="#" class="add" style="float:right;" onClick="addFileField('divFileUpload','incident_fileUpload','fileUpload_id','file'); return false;">
				add
			</a>
			Description: <input type="text" name="fileUpload_description_1" id="fileUpload_description_1" value=""/> <br/>
			File: <input type="file" name="incident_fileUpload_1" value=""  style="width:100px; float:none;"class="text long" /> 			
			<input type="hidden" name="fileUpload_id" value="2" id="fileUpload_id">
		</div>
	</div>


</div>