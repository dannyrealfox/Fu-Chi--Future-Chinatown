<div class="row" style="border: 2px solid gray; padding: 10px; width:340px; margin:auto; margin-top:10px; margin-bottom:10px;" >

	<script type="text/javascript">
		function deleteFile (id, div)
		{
			var answer = confirm("Are You Sure You Want To Delete This File?");
			if (answer){
				$("#" + div).effect("highlight", {}, 800);
				$.get("<?php echo url::base() . 'admin/fileupload/delete/' ?>" + id);
				$("#" + div).remove();
			}
			else{
				return false;
			}
		}
			
		function addFileField(div, field, hidden_id, field_type) {
			var id = document.getElementById(hidden_id).value;
			$("#" + div).append("<div class=\"row link-row second\" id=\"" + field + "_" + id + "\"><a style=\"float:right;\" href=\"#\" class=\"add\" onClick=\"addFileField('" + div + "','" + field + "','" + hidden_id + "','" + field_type + "'); return false;\">add</a><a href=\"#\"  style=\"float:right;\" class=\"rem\"  onClick='removeFileField(\"#" + field + "_" + id + "\"); return false;'>remove</a>Description: <input type=\"text\" name=\"fileUpload_description_"+id+"\" id=\"fileUpload_description_"+id+"\"/> <br/>File:<input type=\"" + field_type + "\" name=\"" + field + "_" + id + "\" class=\"" + field_type + " long\" /></div>");
			
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

		function addFile(fileLink, fileTitle){
			tinyMCE.execCommand("mceInsertContent",false,"<a href=\""+fileLink+"\">"+fileTitle+"</a>");
		}
</script>


	</script>


	<h4>
		Uploaded Files
		<br/>
		<span style="font-size:10px;">
			Uploaded files associated with this report
		</span>
	</h4>
	<ul>
		<?php
			foreach ($files as $file) 
			{
				print "<li id=\"file_". $file->id ."\"  >";
				$prefix = url::base().Kohana::config('upload.relative_directory');
				$file_name = $file->file_link;
				print '<a href="'.$prefix.'/'.$file_name.'">'.$file->file_title.'</a><br>';
				print "<span style=\"margin-left:10px;font-size:80%;\">";
				print "<a href=\"#\" onmousedown=\"addFile('".$prefix."/".$file_name."','".$file->file_title."')\">Insert file into description</a>";
				print "&nbsp;&nbsp;--&nbsp;&nbsp;<a style=\"color:red;\" href=\"#\" onClick=\"deleteFile('".$file->id."', 'file_".$file->id."'); return false;\" >".Kohana::lang('ui_main.delete')." file</a>";
				print "</span></li>";
			}
		?>
	</ul>
	
	
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