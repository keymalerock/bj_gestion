<?php include_once "v_usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($respuesta_grid)) $respuesta_grid = new crespuesta_grid();

// Page init
$respuesta_grid->Page_Init();

// Page main
$respuesta_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$respuesta_grid->Page_Render();
?>
<?php if ($respuesta->Export == "") { ?>
<script type="text/javascript">

// Page object
var respuesta_grid = new ew_Page("respuesta_grid");
respuesta_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = respuesta_grid.PageID; // For backward compatibility

// Form object
var frespuestagrid = new ew_Form("frespuestagrid");
frespuestagrid.FormKeyCountName = '<?php echo $respuesta_grid->FormKeyCountName ?>';

// Validate form
frespuestagrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_id_novedad");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($respuesta->id_novedad->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id_novedad");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($respuesta->id_novedad->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_empleado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($respuesta->id_empleado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_fe_resp");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($respuesta->fe_resp->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
frespuestagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_novedad", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_empleado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fe_resp", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado_resp", false)) return false;
	return true;
}

// Form_CustomValidate event
frespuestagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frespuestagrid.ValidateRequired = true;
<?php } else { ?>
frespuestagrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frespuestagrid.Lists["x_id_empleado"] = {"LinkField":"x_id_empleado","Ajax":null,"AutoFill":false,"DisplayFields":["x_dociden_empleado","x_nomb_empleado","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frespuestagrid.Lists["x_estado_resp"] = {"LinkField":"x_id_x_estado_respuesta","Ajax":null,"AutoFill":false,"DisplayFields":["x_estado_respuesta","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($respuesta->getCurrentMasterTable() == "" && $respuesta_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $respuesta_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($respuesta->CurrentAction == "gridadd") {
	if ($respuesta->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$respuesta_grid->TotalRecs = $respuesta->SelectRecordCount();
			$respuesta_grid->Recordset = $respuesta_grid->LoadRecordset($respuesta_grid->StartRec-1, $respuesta_grid->DisplayRecs);
		} else {
			if ($respuesta_grid->Recordset = $respuesta_grid->LoadRecordset())
				$respuesta_grid->TotalRecs = $respuesta_grid->Recordset->RecordCount();
		}
		$respuesta_grid->StartRec = 1;
		$respuesta_grid->DisplayRecs = $respuesta_grid->TotalRecs;
	} else {
		$respuesta->CurrentFilter = "0=1";
		$respuesta_grid->StartRec = 1;
		$respuesta_grid->DisplayRecs = $respuesta->GridAddRowCount;
	}
	$respuesta_grid->TotalRecs = $respuesta_grid->DisplayRecs;
	$respuesta_grid->StopRec = $respuesta_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$respuesta_grid->TotalRecs = $respuesta->SelectRecordCount();
	} else {
		if ($respuesta_grid->Recordset = $respuesta_grid->LoadRecordset())
			$respuesta_grid->TotalRecs = $respuesta_grid->Recordset->RecordCount();
	}
	$respuesta_grid->StartRec = 1;
	$respuesta_grid->DisplayRecs = $respuesta_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$respuesta_grid->Recordset = $respuesta_grid->LoadRecordset($respuesta_grid->StartRec-1, $respuesta_grid->DisplayRecs);
}
$respuesta_grid->RenderOtherOptions();
?>
<?php $respuesta_grid->ShowPageHeader(); ?>
<?php
$respuesta_grid->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div id="frespuestagrid" class="ewForm form-inline">
<div id="gmp_respuesta" class="ewGridMiddlePanel">
<table id="tbl_respuestagrid" class="ewTable ewTableSeparate">
<?php echo $respuesta->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$respuesta_grid->RenderListOptions();

// Render list options (header, left)
$respuesta_grid->ListOptions->Render("header", "left");
?>
<?php if ($respuesta->id_respuesta->Visible) { // id_respuesta ?>
	<?php if ($respuesta->SortUrl($respuesta->id_respuesta) == "") { ?>
		<td><div id="elh_respuesta_id_respuesta" class="respuesta_id_respuesta"><div class="ewTableHeaderCaption"><?php echo $respuesta->id_respuesta->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_respuesta_id_respuesta" class="respuesta_id_respuesta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $respuesta->id_respuesta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($respuesta->id_respuesta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($respuesta->id_respuesta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($respuesta->id_novedad->Visible) { // id_novedad ?>
	<?php if ($respuesta->SortUrl($respuesta->id_novedad) == "") { ?>
		<td><div id="elh_respuesta_id_novedad" class="respuesta_id_novedad"><div class="ewTableHeaderCaption"><?php echo $respuesta->id_novedad->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_respuesta_id_novedad" class="respuesta_id_novedad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $respuesta->id_novedad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($respuesta->id_novedad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($respuesta->id_novedad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($respuesta->id_empleado->Visible) { // id_empleado ?>
	<?php if ($respuesta->SortUrl($respuesta->id_empleado) == "") { ?>
		<td><div id="elh_respuesta_id_empleado" class="respuesta_id_empleado"><div class="ewTableHeaderCaption"><?php echo $respuesta->id_empleado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_respuesta_id_empleado" class="respuesta_id_empleado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $respuesta->id_empleado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($respuesta->id_empleado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($respuesta->id_empleado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($respuesta->fe_resp->Visible) { // fe_resp ?>
	<?php if ($respuesta->SortUrl($respuesta->fe_resp) == "") { ?>
		<td><div id="elh_respuesta_fe_resp" class="respuesta_fe_resp"><div class="ewTableHeaderCaption"><?php echo $respuesta->fe_resp->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_respuesta_fe_resp" class="respuesta_fe_resp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $respuesta->fe_resp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($respuesta->fe_resp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($respuesta->fe_resp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($respuesta->estado_resp->Visible) { // estado_resp ?>
	<?php if ($respuesta->SortUrl($respuesta->estado_resp) == "") { ?>
		<td><div id="elh_respuesta_estado_resp" class="respuesta_estado_resp"><div class="ewTableHeaderCaption"><?php echo $respuesta->estado_resp->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_respuesta_estado_resp" class="respuesta_estado_resp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $respuesta->estado_resp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($respuesta->estado_resp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($respuesta->estado_resp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$respuesta_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$respuesta_grid->StartRec = 1;
$respuesta_grid->StopRec = $respuesta_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($respuesta_grid->FormKeyCountName) && ($respuesta->CurrentAction == "gridadd" || $respuesta->CurrentAction == "gridedit" || $respuesta->CurrentAction == "F")) {
		$respuesta_grid->KeyCount = $objForm->GetValue($respuesta_grid->FormKeyCountName);
		$respuesta_grid->StopRec = $respuesta_grid->StartRec + $respuesta_grid->KeyCount - 1;
	}
}
$respuesta_grid->RecCnt = $respuesta_grid->StartRec - 1;
if ($respuesta_grid->Recordset && !$respuesta_grid->Recordset->EOF) {
	$respuesta_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $respuesta_grid->StartRec > 1)
		$respuesta_grid->Recordset->Move($respuesta_grid->StartRec - 1);
} elseif (!$respuesta->AllowAddDeleteRow && $respuesta_grid->StopRec == 0) {
	$respuesta_grid->StopRec = $respuesta->GridAddRowCount;
}

// Initialize aggregate
$respuesta->RowType = EW_ROWTYPE_AGGREGATEINIT;
$respuesta->ResetAttrs();
$respuesta_grid->RenderRow();
if ($respuesta->CurrentAction == "gridadd")
	$respuesta_grid->RowIndex = 0;
if ($respuesta->CurrentAction == "gridedit")
	$respuesta_grid->RowIndex = 0;
while ($respuesta_grid->RecCnt < $respuesta_grid->StopRec) {
	$respuesta_grid->RecCnt++;
	if (intval($respuesta_grid->RecCnt) >= intval($respuesta_grid->StartRec)) {
		$respuesta_grid->RowCnt++;
		if ($respuesta->CurrentAction == "gridadd" || $respuesta->CurrentAction == "gridedit" || $respuesta->CurrentAction == "F") {
			$respuesta_grid->RowIndex++;
			$objForm->Index = $respuesta_grid->RowIndex;
			if ($objForm->HasValue($respuesta_grid->FormActionName))
				$respuesta_grid->RowAction = strval($objForm->GetValue($respuesta_grid->FormActionName));
			elseif ($respuesta->CurrentAction == "gridadd")
				$respuesta_grid->RowAction = "insert";
			else
				$respuesta_grid->RowAction = "";
		}

		// Set up key count
		$respuesta_grid->KeyCount = $respuesta_grid->RowIndex;

		// Init row class and style
		$respuesta->ResetAttrs();
		$respuesta->CssClass = "";
		if ($respuesta->CurrentAction == "gridadd") {
			if ($respuesta->CurrentMode == "copy") {
				$respuesta_grid->LoadRowValues($respuesta_grid->Recordset); // Load row values
				$respuesta_grid->SetRecordKey($respuesta_grid->RowOldKey, $respuesta_grid->Recordset); // Set old record key
			} else {
				$respuesta_grid->LoadDefaultValues(); // Load default values
				$respuesta_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$respuesta_grid->LoadRowValues($respuesta_grid->Recordset); // Load row values
		}
		$respuesta->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($respuesta->CurrentAction == "gridadd") // Grid add
			$respuesta->RowType = EW_ROWTYPE_ADD; // Render add
		if ($respuesta->CurrentAction == "gridadd" && $respuesta->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$respuesta_grid->RestoreCurrentRowFormValues($respuesta_grid->RowIndex); // Restore form values
		if ($respuesta->CurrentAction == "gridedit") { // Grid edit
			if ($respuesta->EventCancelled) {
				$respuesta_grid->RestoreCurrentRowFormValues($respuesta_grid->RowIndex); // Restore form values
			}
			if ($respuesta_grid->RowAction == "insert")
				$respuesta->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$respuesta->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($respuesta->CurrentAction == "gridedit" && ($respuesta->RowType == EW_ROWTYPE_EDIT || $respuesta->RowType == EW_ROWTYPE_ADD) && $respuesta->EventCancelled) // Update failed
			$respuesta_grid->RestoreCurrentRowFormValues($respuesta_grid->RowIndex); // Restore form values
		if ($respuesta->RowType == EW_ROWTYPE_EDIT) // Edit row
			$respuesta_grid->EditRowCnt++;
		if ($respuesta->CurrentAction == "F") // Confirm row
			$respuesta_grid->RestoreCurrentRowFormValues($respuesta_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$respuesta->RowAttrs = array_merge($respuesta->RowAttrs, array('data-rowindex'=>$respuesta_grid->RowCnt, 'id'=>'r' . $respuesta_grid->RowCnt . '_respuesta', 'data-rowtype'=>$respuesta->RowType));

		// Render row
		$respuesta_grid->RenderRow();

		// Render list options
		$respuesta_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($respuesta_grid->RowAction <> "delete" && $respuesta_grid->RowAction <> "insertdelete" && !($respuesta_grid->RowAction == "insert" && $respuesta->CurrentAction == "F" && $respuesta_grid->EmptyRow())) {
?>
	<tr<?php echo $respuesta->RowAttributes() ?>>
<?php

// Render list options (body, left)
$respuesta_grid->ListOptions->Render("body", "left", $respuesta_grid->RowCnt);
?>
	<?php if ($respuesta->id_respuesta->Visible) { // id_respuesta ?>
		<td<?php echo $respuesta->id_respuesta->CellAttributes() ?>>
<?php if ($respuesta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id_respuesta" name="o<?php echo $respuesta_grid->RowIndex ?>_id_respuesta" id="o<?php echo $respuesta_grid->RowIndex ?>_id_respuesta" value="<?php echo ew_HtmlEncode($respuesta->id_respuesta->OldValue) ?>">
<?php } ?>
<?php if ($respuesta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $respuesta_grid->RowCnt ?>_respuesta_id_respuesta" class="control-group respuesta_id_respuesta">
<span<?php echo $respuesta->id_respuesta->ViewAttributes() ?>>
<?php echo $respuesta->id_respuesta->EditValue ?></span>
</span>
<input type="hidden" data-field="x_id_respuesta" name="x<?php echo $respuesta_grid->RowIndex ?>_id_respuesta" id="x<?php echo $respuesta_grid->RowIndex ?>_id_respuesta" value="<?php echo ew_HtmlEncode($respuesta->id_respuesta->CurrentValue) ?>">
<?php } ?>
<?php if ($respuesta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $respuesta->id_respuesta->ViewAttributes() ?>>
<?php echo $respuesta->id_respuesta->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_respuesta" name="x<?php echo $respuesta_grid->RowIndex ?>_id_respuesta" id="x<?php echo $respuesta_grid->RowIndex ?>_id_respuesta" value="<?php echo ew_HtmlEncode($respuesta->id_respuesta->FormValue) ?>">
<input type="hidden" data-field="x_id_respuesta" name="o<?php echo $respuesta_grid->RowIndex ?>_id_respuesta" id="o<?php echo $respuesta_grid->RowIndex ?>_id_respuesta" value="<?php echo ew_HtmlEncode($respuesta->id_respuesta->OldValue) ?>">
<?php } ?>
<a id="<?php echo $respuesta_grid->PageObjName . "_row_" . $respuesta_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($respuesta->id_novedad->Visible) { // id_novedad ?>
		<td<?php echo $respuesta->id_novedad->CellAttributes() ?>>
<?php if ($respuesta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($respuesta->id_novedad->getSessionValue() <> "") { ?>
<span<?php echo $respuesta->id_novedad->ViewAttributes() ?>>
<?php echo $respuesta->id_novedad->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $respuesta_grid->RowIndex ?>_id_novedad" name="x<?php echo $respuesta_grid->RowIndex ?>_id_novedad" value="<?php echo ew_HtmlEncode($respuesta->id_novedad->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_id_novedad" name="x<?php echo $respuesta_grid->RowIndex ?>_id_novedad" id="x<?php echo $respuesta_grid->RowIndex ?>_id_novedad" size="30" placeholder="<?php echo ew_HtmlEncode($respuesta->id_novedad->PlaceHolder) ?>" value="<?php echo $respuesta->id_novedad->EditValue ?>"<?php echo $respuesta->id_novedad->EditAttributes() ?>>
<?php } ?>
<input type="hidden" data-field="x_id_novedad" name="o<?php echo $respuesta_grid->RowIndex ?>_id_novedad" id="o<?php echo $respuesta_grid->RowIndex ?>_id_novedad" value="<?php echo ew_HtmlEncode($respuesta->id_novedad->OldValue) ?>">
<?php } ?>
<?php if ($respuesta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($respuesta->id_novedad->getSessionValue() <> "") { ?>
<span<?php echo $respuesta->id_novedad->ViewAttributes() ?>>
<?php echo $respuesta->id_novedad->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $respuesta_grid->RowIndex ?>_id_novedad" name="x<?php echo $respuesta_grid->RowIndex ?>_id_novedad" value="<?php echo ew_HtmlEncode($respuesta->id_novedad->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_id_novedad" name="x<?php echo $respuesta_grid->RowIndex ?>_id_novedad" id="x<?php echo $respuesta_grid->RowIndex ?>_id_novedad" size="30" placeholder="<?php echo ew_HtmlEncode($respuesta->id_novedad->PlaceHolder) ?>" value="<?php echo $respuesta->id_novedad->EditValue ?>"<?php echo $respuesta->id_novedad->EditAttributes() ?>>
<?php } ?>
<?php } ?>
<?php if ($respuesta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $respuesta->id_novedad->ViewAttributes() ?>>
<?php echo $respuesta->id_novedad->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_novedad" name="x<?php echo $respuesta_grid->RowIndex ?>_id_novedad" id="x<?php echo $respuesta_grid->RowIndex ?>_id_novedad" value="<?php echo ew_HtmlEncode($respuesta->id_novedad->FormValue) ?>">
<input type="hidden" data-field="x_id_novedad" name="o<?php echo $respuesta_grid->RowIndex ?>_id_novedad" id="o<?php echo $respuesta_grid->RowIndex ?>_id_novedad" value="<?php echo ew_HtmlEncode($respuesta->id_novedad->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($respuesta->id_empleado->Visible) { // id_empleado ?>
		<td<?php echo $respuesta->id_empleado->CellAttributes() ?>>
<?php if ($respuesta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $respuesta_grid->RowCnt ?>_respuesta_id_empleado" class="control-group respuesta_id_empleado">
<select data-field="x_id_empleado" id="x<?php echo $respuesta_grid->RowIndex ?>_id_empleado" name="x<?php echo $respuesta_grid->RowIndex ?>_id_empleado"<?php echo $respuesta->id_empleado->EditAttributes() ?>>
<?php
if (is_array($respuesta->id_empleado->EditValue)) {
	$arwrk = $respuesta->id_empleado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($respuesta->id_empleado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$respuesta->id_empleado) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $respuesta->id_empleado->OldValue = "";
?>
</select>
<script type="text/javascript">
frespuestagrid.Lists["x_id_empleado"].Options = <?php echo (is_array($respuesta->id_empleado->EditValue)) ? ew_ArrayToJson($respuesta->id_empleado->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_id_empleado" name="o<?php echo $respuesta_grid->RowIndex ?>_id_empleado" id="o<?php echo $respuesta_grid->RowIndex ?>_id_empleado" value="<?php echo ew_HtmlEncode($respuesta->id_empleado->OldValue) ?>">
<?php } ?>
<?php if ($respuesta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $respuesta_grid->RowCnt ?>_respuesta_id_empleado" class="control-group respuesta_id_empleado">
<select data-field="x_id_empleado" id="x<?php echo $respuesta_grid->RowIndex ?>_id_empleado" name="x<?php echo $respuesta_grid->RowIndex ?>_id_empleado"<?php echo $respuesta->id_empleado->EditAttributes() ?>>
<?php
if (is_array($respuesta->id_empleado->EditValue)) {
	$arwrk = $respuesta->id_empleado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($respuesta->id_empleado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$respuesta->id_empleado) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $respuesta->id_empleado->OldValue = "";
?>
</select>
<script type="text/javascript">
frespuestagrid.Lists["x_id_empleado"].Options = <?php echo (is_array($respuesta->id_empleado->EditValue)) ? ew_ArrayToJson($respuesta->id_empleado->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($respuesta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $respuesta->id_empleado->ViewAttributes() ?>>
<?php echo $respuesta->id_empleado->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_empleado" name="x<?php echo $respuesta_grid->RowIndex ?>_id_empleado" id="x<?php echo $respuesta_grid->RowIndex ?>_id_empleado" value="<?php echo ew_HtmlEncode($respuesta->id_empleado->FormValue) ?>">
<input type="hidden" data-field="x_id_empleado" name="o<?php echo $respuesta_grid->RowIndex ?>_id_empleado" id="o<?php echo $respuesta_grid->RowIndex ?>_id_empleado" value="<?php echo ew_HtmlEncode($respuesta->id_empleado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($respuesta->fe_resp->Visible) { // fe_resp ?>
		<td<?php echo $respuesta->fe_resp->CellAttributes() ?>>
<?php if ($respuesta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $respuesta_grid->RowCnt ?>_respuesta_fe_resp" class="control-group respuesta_fe_resp">
<input type="text" data-field="x_fe_resp" name="x<?php echo $respuesta_grid->RowIndex ?>_fe_resp" id="x<?php echo $respuesta_grid->RowIndex ?>_fe_resp" placeholder="<?php echo ew_HtmlEncode($respuesta->fe_resp->PlaceHolder) ?>" value="<?php echo $respuesta->fe_resp->EditValue ?>"<?php echo $respuesta->fe_resp->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_fe_resp" name="o<?php echo $respuesta_grid->RowIndex ?>_fe_resp" id="o<?php echo $respuesta_grid->RowIndex ?>_fe_resp" value="<?php echo ew_HtmlEncode($respuesta->fe_resp->OldValue) ?>">
<?php } ?>
<?php if ($respuesta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $respuesta_grid->RowCnt ?>_respuesta_fe_resp" class="control-group respuesta_fe_resp">
<input type="text" data-field="x_fe_resp" name="x<?php echo $respuesta_grid->RowIndex ?>_fe_resp" id="x<?php echo $respuesta_grid->RowIndex ?>_fe_resp" placeholder="<?php echo ew_HtmlEncode($respuesta->fe_resp->PlaceHolder) ?>" value="<?php echo $respuesta->fe_resp->EditValue ?>"<?php echo $respuesta->fe_resp->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($respuesta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $respuesta->fe_resp->ViewAttributes() ?>>
<?php echo $respuesta->fe_resp->ListViewValue() ?></span>
<input type="hidden" data-field="x_fe_resp" name="x<?php echo $respuesta_grid->RowIndex ?>_fe_resp" id="x<?php echo $respuesta_grid->RowIndex ?>_fe_resp" value="<?php echo ew_HtmlEncode($respuesta->fe_resp->FormValue) ?>">
<input type="hidden" data-field="x_fe_resp" name="o<?php echo $respuesta_grid->RowIndex ?>_fe_resp" id="o<?php echo $respuesta_grid->RowIndex ?>_fe_resp" value="<?php echo ew_HtmlEncode($respuesta->fe_resp->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($respuesta->estado_resp->Visible) { // estado_resp ?>
		<td<?php echo $respuesta->estado_resp->CellAttributes() ?>>
<?php if ($respuesta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $respuesta_grid->RowCnt ?>_respuesta_estado_resp" class="control-group respuesta_estado_resp">
<select data-field="x_estado_resp" id="x<?php echo $respuesta_grid->RowIndex ?>_estado_resp" name="x<?php echo $respuesta_grid->RowIndex ?>_estado_resp"<?php echo $respuesta->estado_resp->EditAttributes() ?>>
<?php
if (is_array($respuesta->estado_resp->EditValue)) {
	$arwrk = $respuesta->estado_resp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($respuesta->estado_resp->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $respuesta->estado_resp->OldValue = "";
?>
</select>
<script type="text/javascript">
frespuestagrid.Lists["x_estado_resp"].Options = <?php echo (is_array($respuesta->estado_resp->EditValue)) ? ew_ArrayToJson($respuesta->estado_resp->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_estado_resp" name="o<?php echo $respuesta_grid->RowIndex ?>_estado_resp" id="o<?php echo $respuesta_grid->RowIndex ?>_estado_resp" value="<?php echo ew_HtmlEncode($respuesta->estado_resp->OldValue) ?>">
<?php } ?>
<?php if ($respuesta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $respuesta_grid->RowCnt ?>_respuesta_estado_resp" class="control-group respuesta_estado_resp">
<select data-field="x_estado_resp" id="x<?php echo $respuesta_grid->RowIndex ?>_estado_resp" name="x<?php echo $respuesta_grid->RowIndex ?>_estado_resp"<?php echo $respuesta->estado_resp->EditAttributes() ?>>
<?php
if (is_array($respuesta->estado_resp->EditValue)) {
	$arwrk = $respuesta->estado_resp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($respuesta->estado_resp->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $respuesta->estado_resp->OldValue = "";
?>
</select>
<script type="text/javascript">
frespuestagrid.Lists["x_estado_resp"].Options = <?php echo (is_array($respuesta->estado_resp->EditValue)) ? ew_ArrayToJson($respuesta->estado_resp->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($respuesta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $respuesta->estado_resp->ViewAttributes() ?>>
<?php echo $respuesta->estado_resp->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado_resp" name="x<?php echo $respuesta_grid->RowIndex ?>_estado_resp" id="x<?php echo $respuesta_grid->RowIndex ?>_estado_resp" value="<?php echo ew_HtmlEncode($respuesta->estado_resp->FormValue) ?>">
<input type="hidden" data-field="x_estado_resp" name="o<?php echo $respuesta_grid->RowIndex ?>_estado_resp" id="o<?php echo $respuesta_grid->RowIndex ?>_estado_resp" value="<?php echo ew_HtmlEncode($respuesta->estado_resp->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$respuesta_grid->ListOptions->Render("body", "right", $respuesta_grid->RowCnt);
?>
	</tr>
<?php if ($respuesta->RowType == EW_ROWTYPE_ADD || $respuesta->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
frespuestagrid.UpdateOpts(<?php echo $respuesta_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($respuesta->CurrentAction <> "gridadd" || $respuesta->CurrentMode == "copy")
		if (!$respuesta_grid->Recordset->EOF) $respuesta_grid->Recordset->MoveNext();
}
?>
<?php
	if ($respuesta->CurrentMode == "add" || $respuesta->CurrentMode == "copy" || $respuesta->CurrentMode == "edit") {
		$respuesta_grid->RowIndex = '$rowindex$';
		$respuesta_grid->LoadDefaultValues();

		// Set row properties
		$respuesta->ResetAttrs();
		$respuesta->RowAttrs = array_merge($respuesta->RowAttrs, array('data-rowindex'=>$respuesta_grid->RowIndex, 'id'=>'r0_respuesta', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($respuesta->RowAttrs["class"], "ewTemplate");
		$respuesta->RowType = EW_ROWTYPE_ADD;

		// Render row
		$respuesta_grid->RenderRow();

		// Render list options
		$respuesta_grid->RenderListOptions();
		$respuesta_grid->StartRowCnt = 0;
?>
	<tr<?php echo $respuesta->RowAttributes() ?>>
<?php

// Render list options (body, left)
$respuesta_grid->ListOptions->Render("body", "left", $respuesta_grid->RowIndex);
?>
	<?php if ($respuesta->id_respuesta->Visible) { // id_respuesta ?>
		<td>
<?php if ($respuesta->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_respuesta_id_respuesta" class="control-group respuesta_id_respuesta">
<span<?php echo $respuesta->id_respuesta->ViewAttributes() ?>>
<?php echo $respuesta->id_respuesta->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_id_respuesta" name="x<?php echo $respuesta_grid->RowIndex ?>_id_respuesta" id="x<?php echo $respuesta_grid->RowIndex ?>_id_respuesta" value="<?php echo ew_HtmlEncode($respuesta->id_respuesta->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_respuesta" name="o<?php echo $respuesta_grid->RowIndex ?>_id_respuesta" id="o<?php echo $respuesta_grid->RowIndex ?>_id_respuesta" value="<?php echo ew_HtmlEncode($respuesta->id_respuesta->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($respuesta->id_novedad->Visible) { // id_novedad ?>
		<td>
<?php if ($respuesta->CurrentAction <> "F") { ?>
<?php if ($respuesta->id_novedad->getSessionValue() <> "") { ?>
<span<?php echo $respuesta->id_novedad->ViewAttributes() ?>>
<?php echo $respuesta->id_novedad->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $respuesta_grid->RowIndex ?>_id_novedad" name="x<?php echo $respuesta_grid->RowIndex ?>_id_novedad" value="<?php echo ew_HtmlEncode($respuesta->id_novedad->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_id_novedad" name="x<?php echo $respuesta_grid->RowIndex ?>_id_novedad" id="x<?php echo $respuesta_grid->RowIndex ?>_id_novedad" size="30" placeholder="<?php echo ew_HtmlEncode($respuesta->id_novedad->PlaceHolder) ?>" value="<?php echo $respuesta->id_novedad->EditValue ?>"<?php echo $respuesta->id_novedad->EditAttributes() ?>>
<?php } ?>
<?php } else { ?>
<span<?php echo $respuesta->id_novedad->ViewAttributes() ?>>
<?php echo $respuesta->id_novedad->ViewValue ?></span>
<input type="hidden" data-field="x_id_novedad" name="x<?php echo $respuesta_grid->RowIndex ?>_id_novedad" id="x<?php echo $respuesta_grid->RowIndex ?>_id_novedad" value="<?php echo ew_HtmlEncode($respuesta->id_novedad->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_novedad" name="o<?php echo $respuesta_grid->RowIndex ?>_id_novedad" id="o<?php echo $respuesta_grid->RowIndex ?>_id_novedad" value="<?php echo ew_HtmlEncode($respuesta->id_novedad->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($respuesta->id_empleado->Visible) { // id_empleado ?>
		<td>
<?php if ($respuesta->CurrentAction <> "F") { ?>
<span id="el$rowindex$_respuesta_id_empleado" class="control-group respuesta_id_empleado">
<select data-field="x_id_empleado" id="x<?php echo $respuesta_grid->RowIndex ?>_id_empleado" name="x<?php echo $respuesta_grid->RowIndex ?>_id_empleado"<?php echo $respuesta->id_empleado->EditAttributes() ?>>
<?php
if (is_array($respuesta->id_empleado->EditValue)) {
	$arwrk = $respuesta->id_empleado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($respuesta->id_empleado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$respuesta->id_empleado) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $respuesta->id_empleado->OldValue = "";
?>
</select>
<script type="text/javascript">
frespuestagrid.Lists["x_id_empleado"].Options = <?php echo (is_array($respuesta->id_empleado->EditValue)) ? ew_ArrayToJson($respuesta->id_empleado->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_respuesta_id_empleado" class="control-group respuesta_id_empleado">
<span<?php echo $respuesta->id_empleado->ViewAttributes() ?>>
<?php echo $respuesta->id_empleado->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_id_empleado" name="x<?php echo $respuesta_grid->RowIndex ?>_id_empleado" id="x<?php echo $respuesta_grid->RowIndex ?>_id_empleado" value="<?php echo ew_HtmlEncode($respuesta->id_empleado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_empleado" name="o<?php echo $respuesta_grid->RowIndex ?>_id_empleado" id="o<?php echo $respuesta_grid->RowIndex ?>_id_empleado" value="<?php echo ew_HtmlEncode($respuesta->id_empleado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($respuesta->fe_resp->Visible) { // fe_resp ?>
		<td>
<?php if ($respuesta->CurrentAction <> "F") { ?>
<span id="el$rowindex$_respuesta_fe_resp" class="control-group respuesta_fe_resp">
<input type="text" data-field="x_fe_resp" name="x<?php echo $respuesta_grid->RowIndex ?>_fe_resp" id="x<?php echo $respuesta_grid->RowIndex ?>_fe_resp" placeholder="<?php echo ew_HtmlEncode($respuesta->fe_resp->PlaceHolder) ?>" value="<?php echo $respuesta->fe_resp->EditValue ?>"<?php echo $respuesta->fe_resp->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_respuesta_fe_resp" class="control-group respuesta_fe_resp">
<span<?php echo $respuesta->fe_resp->ViewAttributes() ?>>
<?php echo $respuesta->fe_resp->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_fe_resp" name="x<?php echo $respuesta_grid->RowIndex ?>_fe_resp" id="x<?php echo $respuesta_grid->RowIndex ?>_fe_resp" value="<?php echo ew_HtmlEncode($respuesta->fe_resp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fe_resp" name="o<?php echo $respuesta_grid->RowIndex ?>_fe_resp" id="o<?php echo $respuesta_grid->RowIndex ?>_fe_resp" value="<?php echo ew_HtmlEncode($respuesta->fe_resp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($respuesta->estado_resp->Visible) { // estado_resp ?>
		<td>
<?php if ($respuesta->CurrentAction <> "F") { ?>
<span id="el$rowindex$_respuesta_estado_resp" class="control-group respuesta_estado_resp">
<select data-field="x_estado_resp" id="x<?php echo $respuesta_grid->RowIndex ?>_estado_resp" name="x<?php echo $respuesta_grid->RowIndex ?>_estado_resp"<?php echo $respuesta->estado_resp->EditAttributes() ?>>
<?php
if (is_array($respuesta->estado_resp->EditValue)) {
	$arwrk = $respuesta->estado_resp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($respuesta->estado_resp->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $respuesta->estado_resp->OldValue = "";
?>
</select>
<script type="text/javascript">
frespuestagrid.Lists["x_estado_resp"].Options = <?php echo (is_array($respuesta->estado_resp->EditValue)) ? ew_ArrayToJson($respuesta->estado_resp->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_respuesta_estado_resp" class="control-group respuesta_estado_resp">
<span<?php echo $respuesta->estado_resp->ViewAttributes() ?>>
<?php echo $respuesta->estado_resp->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_estado_resp" name="x<?php echo $respuesta_grid->RowIndex ?>_estado_resp" id="x<?php echo $respuesta_grid->RowIndex ?>_estado_resp" value="<?php echo ew_HtmlEncode($respuesta->estado_resp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado_resp" name="o<?php echo $respuesta_grid->RowIndex ?>_estado_resp" id="o<?php echo $respuesta_grid->RowIndex ?>_estado_resp" value="<?php echo ew_HtmlEncode($respuesta->estado_resp->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$respuesta_grid->ListOptions->Render("body", "right", $respuesta_grid->RowCnt);
?>
<script type="text/javascript">
frespuestagrid.UpdateOpts(<?php echo $respuesta_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($respuesta->CurrentMode == "add" || $respuesta->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $respuesta_grid->FormKeyCountName ?>" id="<?php echo $respuesta_grid->FormKeyCountName ?>" value="<?php echo $respuesta_grid->KeyCount ?>">
<?php echo $respuesta_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($respuesta->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $respuesta_grid->FormKeyCountName ?>" id="<?php echo $respuesta_grid->FormKeyCountName ?>" value="<?php echo $respuesta_grid->KeyCount ?>">
<?php echo $respuesta_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($respuesta->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="frespuestagrid">
</div>
<?php

// Close recordset
if ($respuesta_grid->Recordset)
	$respuesta_grid->Recordset->Close();
?>
<?php if ($respuesta_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($respuesta_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($respuesta->Export == "") { ?>
<script type="text/javascript">
frespuestagrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$respuesta_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$respuesta_grid->Page_Terminate();
?>
