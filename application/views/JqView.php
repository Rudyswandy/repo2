<!DOCTYPE HTML>
<html>
<head>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/themes/cupertino/jquery-ui.css" type="text/css" media="all" />
    <link rel="stylesheet" href="http://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css" type="text/css" media="all" />
	<link type="text/css" href="<?php echo base_url()?>jqgrid/css/ui.jqgrid.css" rel="stylesheet" />
    <link type="text/css" href="<?php echo base_url()?>jqgrid/css/searchFilter.css" rel="stylesheet" />
	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js" type="text/javascript"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>jqgrid/js/jquery.jqGrid.js" type="text/javascript">
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>jqgrid/src/grid.subgrid.js" type="text/javascript">
    </script>
	<script type="text/javascript" src="<?php echo base_url(); ?>jqgrid/js/i18n/grid.locale-en.js" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>jqgrid/js/jquery.jqGrid.min.js" type="text/javascript">
	</script>
</head>

<style>
body{
    padding: 10px;
    margin: 10px;
}

.ui-widget-content .rowClass { background-color: red; background-image: none;}
</style>

<body>
	<div class="container">	
        <table id="list"></table>
        <div id="pager1"></div>
        <p id="demo"></p>
    </div>
</body>

<script type="text/javascript">

	$(document).ready(function () { reloadGrid(); });

	function reloadGrid(){
		var lastSel;

			jQuery("#list").jqGrid({
				url: "<?php echo base_url().'index.php/JqController/showData'?>",
				mtype: "POST",
				datatype: "JSON",
				colNames: ["ID", "Name", "Age", "Phone Number", "Email"],
				colModel: [
					{name: "id", index: "id", key: true, width: 10, align: "center", editable: false},
					{name: "name", index: "name", width: 15, align: "center", editable: true},
					{name: "age", index: "age", width: 10, align: "center", editable: true},
					{name: "phone_number", index: "phone_number", width: 15, align: "center", editable: true},
					{name: "email", index: "email", width: 15, align: "center", editable: true}
				],				
				rowNum: 10,
				width: 1200,
				height: "auto",
				rowList: [10, 15, 20],
				pager: "#pager1",
				sortname: "id",
				rownumbers: true,
				viewrecords: true,
				gridview: true,
				sortorder: "desc",
				subGrid: true,

				subGridRowExpanded: function(subgrid_id, row_id) {
					// we pass two parameters
					// subgrid_id is a id of the div tag created whitin a table data
					// the id of this elemenet is a combination of the "sg_" + id of the row
					// the row_id is the id of the row
					// If we wan to pass additinal parameters to the url we can use
					// a method getRowData(row_id) - which returns associative array in type name-value
					// here we can easy construct the flowing
					
					var subgrid_table_id, pager_id;

					console.log(row_id);

					subgrid_table_id = subgrid_id + "_t";
					pager_id = "p_" + subgrid_table_id;

					$("#" + subgrid_id).html("<table id='" + subgrid_table_id + "' class='scroll'></table><div id='" + pager_id + "' class='scroll'></div>");

					$("#" + subgrid_table_id).jqGrid({
						url: "<?php echo base_url(); ?>index.php/JqController/showDataSub?" + "employee_id=" + row_id,
						datatype: "JSON",
						colNames: ["Order ID", "Item Name", "Date of Order"],
						colModel: [
							{name: "order_id", index: "order_id", key: true, width: 10, align: "center", editable: false},
							{name: "item_name", index: "item_name", width: 15, align: "center", editable: true},
							{name: "order_date", index: "order_date", width: 10, align: "center", editable: true},
						],				
						rowNum: 10,
						width: 1000,
						height: "auto",
						pager: pager_id,
						sortname: "order_id",
						sortorder: "desc",
						rownumbers: true,
						viewrecords: true,
						gridview: true,
						editurl: "<?php echo base_url(); ?>index.php/JqController/crudDataSub?" + "employee_id=" + row_id
					}).jqGrid('navGrid', "#" + pager_id, {search: true, view: true, edit: true, add: true, del: true})
				},

				subGridRowColapsed: function(subgrid_id, row_id) {
					// this function is called before removing the data
					// var subgrid_table_id;
					// subgrid_table_id = subgrid_id+"_t";
					// jQuery("#"+subgrid_table_id).remove();
				},

				rowattr: function a(rd){
					//return{"class" : "rowClass"};
				},

				ondblClickRow: function(id){
					if(id && id !== lastSel){ 
				    	jQuery('#list').restoreRow(lastSel);
				    	jQuery('#list').editRow(id, true); 
				        lastSel = id;
					}
				},

				editurl: "<?php echo base_url().'index.php/JqController/crudData'?>",
				caption: "<?php echo '<center>'; ?> Test JqGrid <?php echo '</center>' ?>"
				
			}).navGrid("#pager1", {search: true, view: true, edit: true, add: true, del: true}, {}, {}, {}, {closeAfterSearch: true}
			).navButtonAdd("#pager1", {caption: "Import CSV", buttonicon: "ui-icon-del",
				onClickButton: 
					function(){
						window.location = "<?php echo base_url(); ?>index.php/JqController/import";
					}
			}).navButtonAdd('#pager1',{
		        caption:"Export Excel", 
		        buttonicon:"ui-icon-del", 
		        onClickButton: function(){
		            var data = jQuery("#list").jqGrid('getGridParam','selrow');

		            if(data > 0){
		            	window.location = "<?php echo base_url(); ?>index.php/JqController/exportExcel?" + "id=" + data;
		            }
		            else{
						alert("Please select a row");
		            }
		        }
		    });
	}

	function UploadImage(response, postdata) {

		var data = $.parseJSON(response.responseText);

		if (data.success == true) {
	  	ajaxFileUpload(data.id);
				    }  

	    return [data.success, data.message, data.id];

	}

	function ajaxFileUpload(id) 
				{
				    $.ajaxFileUpload
				    (
				        {
				            url: "<?php echo base_url().'index.php/JqController/upload'?>",
				            secureuri: false,
				            fileElementId: 'attachments',
				            dataType: 'json',
				            data: { id: id },
				            success: function (data, status) {

				                if (typeof (data.success) != 'undefined') {
				                    if (data.success == true) {
				                    	alert("asdadad");
				                        return;
				                    } else {
				                        alert(data.message);
				                    }
				                }
				                else {
				                    return alert('Failed to upload logo!');
				                }
				            },
				            error: function (data, status, e) {
				                return alert('Failed to upload logo!');
				            }
				        }
				    )}
	</script>
</html>