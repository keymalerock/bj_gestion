<?php include_once "v_usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($matricula_grid)) $matricula_grid = new cmatricula_grid();

// Page init
$matricula_grid->Page_Init();

// Page main
$matricula_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$matricula_grid->Page_Render();
?>
<?php if ($matricula->Export == "") { ?>
<script type="text/javascript">

// Page object
var matricula_grid = new ew_Page("matricula_grid");
matricula_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = matricula_grid.PageID; // For backward compatibility

// Form object
var fmatriculagrid = new ew_Form("fmatriculagrid");
fmatriculagrid.FormKeyCountName = '<?php echo $matricula_grid->FormKeyCountName ?>';

// Validate form
fmatriculagrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_afiliado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($matricula->id_afiliado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id_afiliado");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($matricula->id_afiliado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_plan");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($matricula->id_plan->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_valor_matri");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($matricula->valor_matri->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_valor_men_matri");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($matricula->valor_men_matri->FldErrMsg()) ?>");

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
fmatriculagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_afiliado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tipo_matri", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_plan", false)) return false;
	if (ew_ValueChanged(fobj, infix, "valor_matri", false)) return false;
	if (ew_ValueChanged(fobj, infix, "valor_men_matri", false)) return false;
	if (ew_ValueChanged(fobj, infix, "conv_matri", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_empleado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "doc4_matri[]", false)) return false;
	return true;
}

// Form_CustomValidate event
fmatriculagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmatriculagrid.ValidateRequired = true;
<?php } else { ?>
fmatriculagrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmatriculagrid.Lists["x_id_afiliado"] = {"LinkField":"x_id_afiliado","Ajax":true,"AutoFill":false,"DisplayFields":["x_dociden_afiliado","x_apell_afiliado","x_nomb_afiliado",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmatriculagrid.Lists["x_id_plan"] = {"LinkField":"x_id_plan","Ajax":null,"AutoFill":false,"DisplayFields":["x_tipo_plan","x_time_plan","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($matricula->getCurrentMasterTable() == "" && $matricula_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $matricula_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($matricula->CurrentAction == "gridadd") {
	if ($matricula->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$matricula_grid->TotalRecs = $matricula->SelectRecordCount();
			$matricula_grid->Recordset = $matricula_grid->LoadRecordset($matricula_grid->StartRec-1, $matricula_grid->DisplayRecs);
		} else {
			if ($matricula_grid->Recordset = $matricula_grid->LoadRecordset())
				$matricula_grid->TotalRecs = $matricula_grid->Recordset->RecordCount();
		}
		$matricula_grid->StartRec = 1;
		$matricula_grid->DisplayRecs = $matricula_grid->TotalRecs;
	} else {
		$matricula->CurrentFilter = "0=1";
		$matricula_grid->StartRec = 1;
		$matricula_grid->DisplayRecs = $matricula->GridAddRowCount;
	}
	$matricula_grid->TotalRecs = $matricula_grid->DisplayRecs;
	$matricula_grid->StopRec = $matricula_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$matricula_grid->TotalRecs = $matricula->SelectRecordCount();
	} else {
		if ($matricula_grid->Recordset = $matricula_grid->LoadRecordset())
			$matricula_grid->TotalRecs = $matricula_grid->Recordset->RecordCount();
	}
	$matricula_grid->StartRec = 1;
	$matricula_grid->DisplayRecs = $matricula_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$matricula_grid->Recordset = $matricula_grid->LoadRecordset($matricula_grid->StartRec-1, $matricula_grid->DisplayRecs);
}
$matricula_grid->RenderOtherOptions();
?>
<?php $matricula_grid->ShowPageHeader(); ?>
<?php
$matricula_grid->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div id="fmatriculagrid" class="ewForm form-inline">
<div id="gmp_matricula" class="ewGridMiddlePanel">
<table id="tbl_matriculagrid" class="ewTable ewTableSeparate">
<?php echo $matricula->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$matricula_grid->RenderListOptions();

// Render list options (header, left)
$matricula_grid->ListOptions->Render("header", "left");
?>
<?php if ($matricula->id_matricula->Visible) { // id_matricula ?>
	<?php if ($matricula->SortUrl($matricula->id_matricula) == "") { ?>
		<td><div id="elh_matricula_id_matricula" class="matricula_id_matricula"><div class="ewTableHeaderCaption"><?php echo $matricula->id_matricula->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_matricula_id_matricula" class="matricula_id_matricula">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->id_matricula->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->id_matricula->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->id_matricula->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($matricula->id_afiliado->Visible) { // id_afiliado ?>
	<?php if ($matricula->SortUrl($matricula->id_afiliado) == "") { ?>
		<td><div id="elh_matricula_id_afiliado" class="matricula_id_afiliado"><div class="ewTableHeaderCaption"><?php echo $matricula->id_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_matricula_id_afiliado" class="matricula_id_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->id_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->id_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->id_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($matricula->tipo_matri->Visible) { // tipo_matri ?>
	<?php if ($matricula->SortUrl($matricula->tipo_matri) == "") { ?>
		<td><div id="elh_matricula_tipo_matri" class="matricula_tipo_matri"><div class="ewTableHeaderCaption"><?php echo $matricula->tipo_matri->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_matricula_tipo_matri" class="matricula_tipo_matri">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->tipo_matri->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->tipo_matri->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->tipo_matri->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($matricula->id_plan->Visible) { // id_plan ?>
	<?php if ($matricula->SortUrl($matricula->id_plan) == "") { ?>
		<td><div id="elh_matricula_id_plan" class="matricula_id_plan"><div class="ewTableHeaderCaption"><?php echo $matricula->id_plan->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_matricula_id_plan" class="matricula_id_plan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->id_plan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->id_plan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->id_plan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($matricula->valor_matri->Visible) { // valor_matri ?>
	<?php if ($matricula->SortUrl($matricula->valor_matri) == "") { ?>
		<td><div id="elh_matricula_valor_matri" class="matricula_valor_matri"><div class="ewTableHeaderCaption"><?php echo $matricula->valor_matri->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_matricula_valor_matri" class="matricula_valor_matri">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->valor_matri->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->valor_matri->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->valor_matri->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($matricula->valor_men_matri->Visible) { // valor_men_matri ?>
	<?php if ($matricula->SortUrl($matricula->valor_men_matri) == "") { ?>
		<td><div id="elh_matricula_valor_men_matri" class="matricula_valor_men_matri"><div class="ewTableHeaderCaption"><?php echo $matricula->valor_men_matri->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_matricula_valor_men_matri" class="matricula_valor_men_matri">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->valor_men_matri->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->valor_men_matri->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->valor_men_matri->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($matricula->conv_matri->Visible) { // conv_matri ?>
	<?php if ($matricula->SortUrl($matricula->conv_matri) == "") { ?>
		<td><div id="elh_matricula_conv_matri" class="matricula_conv_matri"><div class="ewTableHeaderCaption"><?php echo $matricula->conv_matri->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_matricula_conv_matri" class="matricula_conv_matri">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->conv_matri->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->conv_matri->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->conv_matri->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($matricula->id_empleado->Visible) { // id_empleado ?>
	<?php if ($matricula->SortUrl($matricula->id_empleado) == "") { ?>
		<td><div id="elh_matricula_id_empleado" class="matricula_id_empleado"><div class="ewTableHeaderCaption"><?php echo $matricula->id_empleado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_matricula_id_empleado" class="matricula_id_empleado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->id_empleado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->id_empleado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->id_empleado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($matricula->doc4_matri->Visible) { // doc4_matri ?>
	<?php if ($matricula->SortUrl($matricula->doc4_matri) == "") { ?>
		<td><div id="elh_matricula_doc4_matri" class="matricula_doc4_matri"><div class="ewTableHeaderCaption"><?php echo $matricula->doc4_matri->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_matricula_doc4_matri" class="matricula_doc4_matri">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->doc4_matri->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->doc4_matri->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->doc4_matri->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$matricula_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$matricula_grid->StartRec = 1;
$matricula_grid->StopRec = $matricula_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($matricula_grid->FormKeyCountName) && ($matricula->CurrentAction == "gridadd" || $matricula->CurrentAction == "gridedit" || $matricula->CurrentAction == "F")) {
		$matricula_grid->KeyCount = $objForm->GetValue($matricula_grid->FormKeyCountName);
		$matricula_grid->StopRec = $matricula_grid->StartRec + $matricula_grid->KeyCount - 1;
	}
}
$matricula_grid->RecCnt = $matricula_grid->StartRec - 1;
if ($matricula_grid->Recordset && !$matricula_grid->Recordset->EOF) {
	$matricula_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $matricula_grid->StartRec > 1)
		$matricula_grid->Recordset->Move($matricula_grid->StartRec - 1);
} elseif (!$matricula->AllowAddDeleteRow && $matricula_grid->StopRec == 0) {
	$matricula_grid->StopRec = $matricula->GridAddRowCount;
}

// Initialize aggregate
$matricula->RowType = EW_ROWTYPE_AGGREGATEINIT;
$matricula->ResetAttrs();
$matricula_grid->RenderRow();
if ($matricula->CurrentAction == "gridadd")
	$matricula_grid->RowIndex = 0;
if ($matricula->CurrentAction == "gridedit")
	$matricula_grid->RowIndex = 0;
while ($matricula_grid->RecCnt < $matricula_grid->StopRec) {
	$matricula_grid->RecCnt++;
	if (intval($matricula_grid->RecCnt) >= intval($matricula_grid->StartRec)) {
		$matricula_grid->RowCnt++;
		if ($matricula->CurrentAction == "gridadd" || $matricula->CurrentAction == "gridedit" || $matricula->CurrentAction == "F") {
			$matricula_grid->RowIndex++;
			$objForm->Index = $matricula_grid->RowIndex;
			if ($objForm->HasValue($matricula_grid->FormActionName))
				$matricula_grid->RowAction = strval($objForm->GetValue($matricula_grid->FormActionName));
			elseif ($matricula->CurrentAction == "gridadd")
				$matricula_grid->RowAction = "insert";
			else
				$matricula_grid->RowAction = "";
		}

		// Set up key count
		$matricula_grid->KeyCount = $matricula_grid->RowIndex;

		// Init row class and style
		$matricula->ResetAttrs();
		$matricula->CssClass = "";
		if ($matricula->CurrentAction == "gridadd") {
			if ($matricula->CurrentMode == "copy") {
				$matricula_grid->LoadRowValues($matricula_grid->Recordset); // Load row values
				$matricula_grid->SetRecordKey($matricula_grid->RowOldKey, $matricula_grid->Recordset); // Set old record key
			} else {
				$matricula_grid->LoadDefaultValues(); // Load default values
				$matricula_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$matricula_grid->LoadRowValues($matricula_grid->Recordset); // Load row values
		}
		$matricula->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($matricula->CurrentAction == "gridadd") // Grid add
			$matricula->RowType = EW_ROWTYPE_ADD; // Render add
		if ($matricula->CurrentAction == "gridadd" && $matricula->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$matricula_grid->RestoreCurrentRowFormValues($matricula_grid->RowIndex); // Restore form values
		if ($matricula->CurrentAction == "gridedit") { // Grid edit
			if ($matricula->EventCancelled) {
				$matricula_grid->RestoreCurrentRowFormValues($matricula_grid->RowIndex); // Restore form values
			}
			if ($matricula_grid->RowAction == "insert")
				$matricula->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$matricula->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($matricula->CurrentAction == "gridedit" && ($matricula->RowType == EW_ROWTYPE_EDIT || $matricula->RowType == EW_ROWTYPE_ADD) && $matricula->EventCancelled) // Update failed
			$matricula_grid->RestoreCurrentRowFormValues($matricula_grid->RowIndex); // Restore form values
		if ($matricula->RowType == EW_ROWTYPE_EDIT) // Edit row
			$matricula_grid->EditRowCnt++;
		if ($matricula->CurrentAction == "F") // Confirm row
			$matricula_grid->RestoreCurrentRowFormValues($matricula_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$matricula->RowAttrs = array_merge($matricula->RowAttrs, array('data-rowindex'=>$matricula_grid->RowCnt, 'id'=>'r' . $matricula_grid->RowCnt . '_matricula', 'data-rowtype'=>$matricula->RowType));

		// Render row
		$matricula_grid->RenderRow();

		// Render list options
		$matricula_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($matricula_grid->RowAction <> "delete" && $matricula_grid->RowAction <> "insertdelete" && !($matricula_grid->RowAction == "insert" && $matricula->CurrentAction == "F" && $matricula_grid->EmptyRow())) {
?>
	<tr<?php echo $matricula->RowAttributes() ?>>
<?php

// Render list options (body, left)
$matricula_grid->ListOptions->Render("body", "left", $matricula_grid->RowCnt);
?>
	<?php if ($matricula->id_matricula->Visible) { // id_matricula ?>
		<td<?php echo $matricula->id_matricula->CellAttributes() ?>>
<?php if ($matricula->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id_matricula" name="o<?php echo $matricula_grid->RowIndex ?>_id_matricula" id="o<?php echo $matricula_grid->RowIndex ?>_id_matricula" value="<?php echo ew_HtmlEncode($matricula->id_matricula->OldValue) ?>">
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $matricula_grid->RowCnt ?>_matricula_id_matricula" class="control-group matricula_id_matricula">
<span<?php echo $matricula->id_matricula->ViewAttributes() ?>>
<?php echo $matricula->id_matricula->EditValue ?></span>
</span>
<input type="hidden" data-field="x_id_matricula" name="x<?php echo $matricula_grid->RowIndex ?>_id_matricula" id="x<?php echo $matricula_grid->RowIndex ?>_id_matricula" value="<?php echo ew_HtmlEncode($matricula->id_matricula->CurrentValue) ?>">
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $matricula->id_matricula->ViewAttributes() ?>>
<?php echo $matricula->id_matricula->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_matricula" name="x<?php echo $matricula_grid->RowIndex ?>_id_matricula" id="x<?php echo $matricula_grid->RowIndex ?>_id_matricula" value="<?php echo ew_HtmlEncode($matricula->id_matricula->FormValue) ?>">
<input type="hidden" data-field="x_id_matricula" name="o<?php echo $matricula_grid->RowIndex ?>_id_matricula" id="o<?php echo $matricula_grid->RowIndex ?>_id_matricula" value="<?php echo ew_HtmlEncode($matricula->id_matricula->OldValue) ?>">
<?php } ?>
<a id="<?php echo $matricula_grid->PageObjName . "_row_" . $matricula_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($matricula->id_afiliado->Visible) { // id_afiliado ?>
		<td<?php echo $matricula->id_afiliado->CellAttributes() ?>>
<?php if ($matricula->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($matricula->id_afiliado->getSessionValue() <> "") { ?>
<span<?php echo $matricula->id_afiliado->ViewAttributes() ?>>
<?php echo $matricula->id_afiliado->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" name="x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($matricula->id_afiliado->CurrentValue) ?>">
<?php } else { ?>
<?php
	$wrkonchange = trim(" " . @$matricula->id_afiliado->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$matricula->id_afiliado->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" style="white-space: nowrap; z-index: <?php echo (9000 - $matricula_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" id="sv_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="<?php echo $matricula->id_afiliado->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($matricula->id_afiliado->PlaceHolder) ?>"<?php echo $matricula->id_afiliado->EditAttributes() ?>>&nbsp;<span id="em_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" style="display: inline; z-index: <?php echo (9000 - $matricula_grid->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" data-field="x_id_afiliado" name="x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" id="x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($matricula->id_afiliado->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
 $sSqlWrk = "SELECT `id_afiliado`, `dociden_afiliado` AS `DispFld`, `apell_afiliado` AS `Disp2Fld`, `nomb_afiliado` AS `Disp3Fld` FROM `afiliado`";
 $sWhereWrk = "`dociden_afiliado` LIKE '{query_value}%' OR CONCAT(`dociden_afiliado`,'" . ew_ValueSeparator(1, $Page->id_afiliado) . "',`apell_afiliado`,'" . ew_ValueSeparator(2, $Page->id_afiliado) . "',`nomb_afiliado`) LIKE '{query_value}%'";

 // Call Lookup selecting
 $matricula->Lookup_Selecting($matricula->id_afiliado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" id="q_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $matricula_grid->RowIndex ?>_id_afiliado", fmatriculagrid, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $matricula_grid->RowIndex ?>_id_afiliado") + ar[i] : "";
	return dv;
}
fmatriculagrid.AutoSuggests["x<?php echo $matricula_grid->RowIndex ?>_id_afiliado"] = oas;
</script>
<?php } ?>
<input type="hidden" data-field="x_id_afiliado" name="o<?php echo $matricula_grid->RowIndex ?>_id_afiliado" id="o<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($matricula->id_afiliado->OldValue) ?>">
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($matricula->id_afiliado->getSessionValue() <> "") { ?>
<span<?php echo $matricula->id_afiliado->ViewAttributes() ?>>
<?php echo $matricula->id_afiliado->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" name="x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($matricula->id_afiliado->CurrentValue) ?>">
<?php } else { ?>
<?php
	$wrkonchange = trim(" " . @$matricula->id_afiliado->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$matricula->id_afiliado->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" style="white-space: nowrap; z-index: <?php echo (9000 - $matricula_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" id="sv_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="<?php echo $matricula->id_afiliado->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($matricula->id_afiliado->PlaceHolder) ?>"<?php echo $matricula->id_afiliado->EditAttributes() ?>>&nbsp;<span id="em_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" style="display: inline; z-index: <?php echo (9000 - $matricula_grid->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" data-field="x_id_afiliado" name="x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" id="x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($matricula->id_afiliado->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
 $sSqlWrk = "SELECT `id_afiliado`, `dociden_afiliado` AS `DispFld`, `apell_afiliado` AS `Disp2Fld`, `nomb_afiliado` AS `Disp3Fld` FROM `afiliado`";
 $sWhereWrk = "`dociden_afiliado` LIKE '{query_value}%' OR CONCAT(`dociden_afiliado`,'" . ew_ValueSeparator(1, $Page->id_afiliado) . "',`apell_afiliado`,'" . ew_ValueSeparator(2, $Page->id_afiliado) . "',`nomb_afiliado`) LIKE '{query_value}%'";

 // Call Lookup selecting
 $matricula->Lookup_Selecting($matricula->id_afiliado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" id="q_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $matricula_grid->RowIndex ?>_id_afiliado", fmatriculagrid, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $matricula_grid->RowIndex ?>_id_afiliado") + ar[i] : "";
	return dv;
}
fmatriculagrid.AutoSuggests["x<?php echo $matricula_grid->RowIndex ?>_id_afiliado"] = oas;
</script>
<?php } ?>
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $matricula->id_afiliado->ViewAttributes() ?>>
<?php echo $matricula->id_afiliado->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_afiliado" name="x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" id="x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($matricula->id_afiliado->FormValue) ?>">
<input type="hidden" data-field="x_id_afiliado" name="o<?php echo $matricula_grid->RowIndex ?>_id_afiliado" id="o<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($matricula->id_afiliado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($matricula->tipo_matri->Visible) { // tipo_matri ?>
		<td<?php echo $matricula->tipo_matri->CellAttributes() ?>>
<?php if ($matricula->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $matricula_grid->RowCnt ?>_matricula_tipo_matri" class="control-group matricula_tipo_matri">
<div id="tp_x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" id="x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" value="{value}"<?php echo $matricula->tipo_matri->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->tipo_matri->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($matricula->tipo_matri->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_tipo_matri" name="x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" id="x<?php echo $matricula_grid->RowIndex ?>_tipo_matri_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $matricula->tipo_matri->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $matricula->tipo_matri->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_tipo_matri" name="o<?php echo $matricula_grid->RowIndex ?>_tipo_matri" id="o<?php echo $matricula_grid->RowIndex ?>_tipo_matri" value="<?php echo ew_HtmlEncode($matricula->tipo_matri->OldValue) ?>">
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $matricula_grid->RowCnt ?>_matricula_tipo_matri" class="control-group matricula_tipo_matri">
<div id="tp_x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" id="x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" value="{value}"<?php echo $matricula->tipo_matri->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->tipo_matri->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($matricula->tipo_matri->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_tipo_matri" name="x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" id="x<?php echo $matricula_grid->RowIndex ?>_tipo_matri_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $matricula->tipo_matri->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $matricula->tipo_matri->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $matricula->tipo_matri->ViewAttributes() ?>>
<?php echo $matricula->tipo_matri->ListViewValue() ?></span>
<input type="hidden" data-field="x_tipo_matri" name="x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" id="x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" value="<?php echo ew_HtmlEncode($matricula->tipo_matri->FormValue) ?>">
<input type="hidden" data-field="x_tipo_matri" name="o<?php echo $matricula_grid->RowIndex ?>_tipo_matri" id="o<?php echo $matricula_grid->RowIndex ?>_tipo_matri" value="<?php echo ew_HtmlEncode($matricula->tipo_matri->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($matricula->id_plan->Visible) { // id_plan ?>
		<td<?php echo $matricula->id_plan->CellAttributes() ?>>
<?php if ($matricula->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $matricula_grid->RowCnt ?>_matricula_id_plan" class="control-group matricula_id_plan">
<select data-field="x_id_plan" id="x<?php echo $matricula_grid->RowIndex ?>_id_plan" name="x<?php echo $matricula_grid->RowIndex ?>_id_plan"<?php echo $matricula->id_plan->EditAttributes() ?>>
<?php
if (is_array($matricula->id_plan->EditValue)) {
	$arwrk = $matricula->id_plan->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($matricula->id_plan->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$matricula->id_plan) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $matricula->id_plan->OldValue = "";
?>
</select>
<script type="text/javascript">
fmatriculagrid.Lists["x_id_plan"].Options = <?php echo (is_array($matricula->id_plan->EditValue)) ? ew_ArrayToJson($matricula->id_plan->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_id_plan" name="o<?php echo $matricula_grid->RowIndex ?>_id_plan" id="o<?php echo $matricula_grid->RowIndex ?>_id_plan" value="<?php echo ew_HtmlEncode($matricula->id_plan->OldValue) ?>">
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $matricula_grid->RowCnt ?>_matricula_id_plan" class="control-group matricula_id_plan">
<select data-field="x_id_plan" id="x<?php echo $matricula_grid->RowIndex ?>_id_plan" name="x<?php echo $matricula_grid->RowIndex ?>_id_plan"<?php echo $matricula->id_plan->EditAttributes() ?>>
<?php
if (is_array($matricula->id_plan->EditValue)) {
	$arwrk = $matricula->id_plan->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($matricula->id_plan->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$matricula->id_plan) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $matricula->id_plan->OldValue = "";
?>
</select>
<script type="text/javascript">
fmatriculagrid.Lists["x_id_plan"].Options = <?php echo (is_array($matricula->id_plan->EditValue)) ? ew_ArrayToJson($matricula->id_plan->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $matricula->id_plan->ViewAttributes() ?>>
<?php echo $matricula->id_plan->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_plan" name="x<?php echo $matricula_grid->RowIndex ?>_id_plan" id="x<?php echo $matricula_grid->RowIndex ?>_id_plan" value="<?php echo ew_HtmlEncode($matricula->id_plan->FormValue) ?>">
<input type="hidden" data-field="x_id_plan" name="o<?php echo $matricula_grid->RowIndex ?>_id_plan" id="o<?php echo $matricula_grid->RowIndex ?>_id_plan" value="<?php echo ew_HtmlEncode($matricula->id_plan->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($matricula->valor_matri->Visible) { // valor_matri ?>
		<td<?php echo $matricula->valor_matri->CellAttributes() ?>>
<?php if ($matricula->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $matricula_grid->RowCnt ?>_matricula_valor_matri" class="control-group matricula_valor_matri">
<input type="text" data-field="x_valor_matri" name="x<?php echo $matricula_grid->RowIndex ?>_valor_matri" id="x<?php echo $matricula_grid->RowIndex ?>_valor_matri" size="30" placeholder="<?php echo ew_HtmlEncode($matricula->valor_matri->PlaceHolder) ?>" value="<?php echo $matricula->valor_matri->EditValue ?>"<?php echo $matricula->valor_matri->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_valor_matri" name="o<?php echo $matricula_grid->RowIndex ?>_valor_matri" id="o<?php echo $matricula_grid->RowIndex ?>_valor_matri" value="<?php echo ew_HtmlEncode($matricula->valor_matri->OldValue) ?>">
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $matricula_grid->RowCnt ?>_matricula_valor_matri" class="control-group matricula_valor_matri">
<input type="text" data-field="x_valor_matri" name="x<?php echo $matricula_grid->RowIndex ?>_valor_matri" id="x<?php echo $matricula_grid->RowIndex ?>_valor_matri" size="30" placeholder="<?php echo ew_HtmlEncode($matricula->valor_matri->PlaceHolder) ?>" value="<?php echo $matricula->valor_matri->EditValue ?>"<?php echo $matricula->valor_matri->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $matricula->valor_matri->ViewAttributes() ?>>
<?php echo $matricula->valor_matri->ListViewValue() ?></span>
<input type="hidden" data-field="x_valor_matri" name="x<?php echo $matricula_grid->RowIndex ?>_valor_matri" id="x<?php echo $matricula_grid->RowIndex ?>_valor_matri" value="<?php echo ew_HtmlEncode($matricula->valor_matri->FormValue) ?>">
<input type="hidden" data-field="x_valor_matri" name="o<?php echo $matricula_grid->RowIndex ?>_valor_matri" id="o<?php echo $matricula_grid->RowIndex ?>_valor_matri" value="<?php echo ew_HtmlEncode($matricula->valor_matri->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($matricula->valor_men_matri->Visible) { // valor_men_matri ?>
		<td<?php echo $matricula->valor_men_matri->CellAttributes() ?>>
<?php if ($matricula->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $matricula_grid->RowCnt ?>_matricula_valor_men_matri" class="control-group matricula_valor_men_matri">
<input type="text" data-field="x_valor_men_matri" name="x<?php echo $matricula_grid->RowIndex ?>_valor_men_matri" id="x<?php echo $matricula_grid->RowIndex ?>_valor_men_matri" size="30" placeholder="<?php echo ew_HtmlEncode($matricula->valor_men_matri->PlaceHolder) ?>" value="<?php echo $matricula->valor_men_matri->EditValue ?>"<?php echo $matricula->valor_men_matri->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_valor_men_matri" name="o<?php echo $matricula_grid->RowIndex ?>_valor_men_matri" id="o<?php echo $matricula_grid->RowIndex ?>_valor_men_matri" value="<?php echo ew_HtmlEncode($matricula->valor_men_matri->OldValue) ?>">
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $matricula_grid->RowCnt ?>_matricula_valor_men_matri" class="control-group matricula_valor_men_matri">
<input type="text" data-field="x_valor_men_matri" name="x<?php echo $matricula_grid->RowIndex ?>_valor_men_matri" id="x<?php echo $matricula_grid->RowIndex ?>_valor_men_matri" size="30" placeholder="<?php echo ew_HtmlEncode($matricula->valor_men_matri->PlaceHolder) ?>" value="<?php echo $matricula->valor_men_matri->EditValue ?>"<?php echo $matricula->valor_men_matri->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $matricula->valor_men_matri->ViewAttributes() ?>>
<?php echo $matricula->valor_men_matri->ListViewValue() ?></span>
<input type="hidden" data-field="x_valor_men_matri" name="x<?php echo $matricula_grid->RowIndex ?>_valor_men_matri" id="x<?php echo $matricula_grid->RowIndex ?>_valor_men_matri" value="<?php echo ew_HtmlEncode($matricula->valor_men_matri->FormValue) ?>">
<input type="hidden" data-field="x_valor_men_matri" name="o<?php echo $matricula_grid->RowIndex ?>_valor_men_matri" id="o<?php echo $matricula_grid->RowIndex ?>_valor_men_matri" value="<?php echo ew_HtmlEncode($matricula->valor_men_matri->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($matricula->conv_matri->Visible) { // conv_matri ?>
		<td<?php echo $matricula->conv_matri->CellAttributes() ?>>
<?php if ($matricula->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $matricula_grid->RowCnt ?>_matricula_conv_matri" class="control-group matricula_conv_matri">
<input type="text" data-field="x_conv_matri" name="x<?php echo $matricula_grid->RowIndex ?>_conv_matri" id="x<?php echo $matricula_grid->RowIndex ?>_conv_matri" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($matricula->conv_matri->PlaceHolder) ?>" value="<?php echo $matricula->conv_matri->EditValue ?>"<?php echo $matricula->conv_matri->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_conv_matri" name="o<?php echo $matricula_grid->RowIndex ?>_conv_matri" id="o<?php echo $matricula_grid->RowIndex ?>_conv_matri" value="<?php echo ew_HtmlEncode($matricula->conv_matri->OldValue) ?>">
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $matricula_grid->RowCnt ?>_matricula_conv_matri" class="control-group matricula_conv_matri">
<input type="text" data-field="x_conv_matri" name="x<?php echo $matricula_grid->RowIndex ?>_conv_matri" id="x<?php echo $matricula_grid->RowIndex ?>_conv_matri" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($matricula->conv_matri->PlaceHolder) ?>" value="<?php echo $matricula->conv_matri->EditValue ?>"<?php echo $matricula->conv_matri->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $matricula->conv_matri->ViewAttributes() ?>>
<?php echo $matricula->conv_matri->ListViewValue() ?></span>
<input type="hidden" data-field="x_conv_matri" name="x<?php echo $matricula_grid->RowIndex ?>_conv_matri" id="x<?php echo $matricula_grid->RowIndex ?>_conv_matri" value="<?php echo ew_HtmlEncode($matricula->conv_matri->FormValue) ?>">
<input type="hidden" data-field="x_conv_matri" name="o<?php echo $matricula_grid->RowIndex ?>_conv_matri" id="o<?php echo $matricula_grid->RowIndex ?>_conv_matri" value="<?php echo ew_HtmlEncode($matricula->conv_matri->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($matricula->id_empleado->Visible) { // id_empleado ?>
		<td<?php echo $matricula->id_empleado->CellAttributes() ?>>
<?php if ($matricula->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $matricula_grid->RowCnt ?>_matricula_id_empleado" class="control-group matricula_id_empleado">
<input type="hidden" data-field="x_id_empleado" name="x<?php echo $matricula_grid->RowIndex ?>_id_empleado" id="x<?php echo $matricula_grid->RowIndex ?>_id_empleado" value="<?php echo ew_HtmlEncode($matricula->id_empleado->CurrentValue) ?>">
</span>
<input type="hidden" data-field="x_id_empleado" name="o<?php echo $matricula_grid->RowIndex ?>_id_empleado" id="o<?php echo $matricula_grid->RowIndex ?>_id_empleado" value="<?php echo ew_HtmlEncode($matricula->id_empleado->OldValue) ?>">
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $matricula_grid->RowCnt ?>_matricula_id_empleado" class="control-group matricula_id_empleado">
<input type="hidden" data-field="x_id_empleado" name="x<?php echo $matricula_grid->RowIndex ?>_id_empleado" id="x<?php echo $matricula_grid->RowIndex ?>_id_empleado" value="<?php echo ew_HtmlEncode($matricula->id_empleado->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $matricula->id_empleado->ViewAttributes() ?>>
<?php echo $matricula->id_empleado->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_empleado" name="x<?php echo $matricula_grid->RowIndex ?>_id_empleado" id="x<?php echo $matricula_grid->RowIndex ?>_id_empleado" value="<?php echo ew_HtmlEncode($matricula->id_empleado->FormValue) ?>">
<input type="hidden" data-field="x_id_empleado" name="o<?php echo $matricula_grid->RowIndex ?>_id_empleado" id="o<?php echo $matricula_grid->RowIndex ?>_id_empleado" value="<?php echo ew_HtmlEncode($matricula->id_empleado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($matricula->doc4_matri->Visible) { // doc4_matri ?>
		<td<?php echo $matricula->doc4_matri->CellAttributes() ?>>
<?php if ($matricula->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $matricula_grid->RowCnt ?>_matricula_doc4_matri" class="control-group matricula_doc4_matri">
<div id="tp_x<?php echo $matricula_grid->RowIndex ?>_doc4_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x<?php echo $matricula_grid->RowIndex ?>_doc4_matri[]" id="x<?php echo $matricula_grid->RowIndex ?>_doc4_matri[]" value="{value}"<?php echo $matricula->doc4_matri->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $matricula_grid->RowIndex ?>_doc4_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->doc4_matri->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($matricula->doc4_matri->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox"><input type="checkbox" data-field="x_doc4_matri" name="x<?php echo $matricula_grid->RowIndex ?>_doc4_matri[]" id="x<?php echo $matricula_grid->RowIndex ?>_doc4_matri_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $matricula->doc4_matri->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $matricula->doc4_matri->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_doc4_matri" name="o<?php echo $matricula_grid->RowIndex ?>_doc4_matri[]" id="o<?php echo $matricula_grid->RowIndex ?>_doc4_matri[]" value="<?php echo ew_HtmlEncode($matricula->doc4_matri->OldValue) ?>">
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $matricula_grid->RowCnt ?>_matricula_doc4_matri" class="control-group matricula_doc4_matri">
<div id="tp_x<?php echo $matricula_grid->RowIndex ?>_doc4_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x<?php echo $matricula_grid->RowIndex ?>_doc4_matri[]" id="x<?php echo $matricula_grid->RowIndex ?>_doc4_matri[]" value="{value}"<?php echo $matricula->doc4_matri->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $matricula_grid->RowIndex ?>_doc4_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->doc4_matri->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($matricula->doc4_matri->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox"><input type="checkbox" data-field="x_doc4_matri" name="x<?php echo $matricula_grid->RowIndex ?>_doc4_matri[]" id="x<?php echo $matricula_grid->RowIndex ?>_doc4_matri_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $matricula->doc4_matri->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $matricula->doc4_matri->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($matricula->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $matricula->doc4_matri->ViewAttributes() ?>>
<?php echo $matricula->doc4_matri->ListViewValue() ?></span>
<input type="hidden" data-field="x_doc4_matri" name="x<?php echo $matricula_grid->RowIndex ?>_doc4_matri" id="x<?php echo $matricula_grid->RowIndex ?>_doc4_matri" value="<?php echo ew_HtmlEncode($matricula->doc4_matri->FormValue) ?>">
<input type="hidden" data-field="x_doc4_matri" name="o<?php echo $matricula_grid->RowIndex ?>_doc4_matri[]" id="o<?php echo $matricula_grid->RowIndex ?>_doc4_matri[]" value="<?php echo ew_HtmlEncode($matricula->doc4_matri->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$matricula_grid->ListOptions->Render("body", "right", $matricula_grid->RowCnt);
?>
	</tr>
<?php if ($matricula->RowType == EW_ROWTYPE_ADD || $matricula->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fmatriculagrid.UpdateOpts(<?php echo $matricula_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($matricula->CurrentAction <> "gridadd" || $matricula->CurrentMode == "copy")
		if (!$matricula_grid->Recordset->EOF) $matricula_grid->Recordset->MoveNext();
}
?>
<?php
	if ($matricula->CurrentMode == "add" || $matricula->CurrentMode == "copy" || $matricula->CurrentMode == "edit") {
		$matricula_grid->RowIndex = '$rowindex$';
		$matricula_grid->LoadDefaultValues();

		// Set row properties
		$matricula->ResetAttrs();
		$matricula->RowAttrs = array_merge($matricula->RowAttrs, array('data-rowindex'=>$matricula_grid->RowIndex, 'id'=>'r0_matricula', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($matricula->RowAttrs["class"], "ewTemplate");
		$matricula->RowType = EW_ROWTYPE_ADD;

		// Render row
		$matricula_grid->RenderRow();

		// Render list options
		$matricula_grid->RenderListOptions();
		$matricula_grid->StartRowCnt = 0;
?>
	<tr<?php echo $matricula->RowAttributes() ?>>
<?php

// Render list options (body, left)
$matricula_grid->ListOptions->Render("body", "left", $matricula_grid->RowIndex);
?>
	<?php if ($matricula->id_matricula->Visible) { // id_matricula ?>
		<td>
<?php if ($matricula->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_matricula_id_matricula" class="control-group matricula_id_matricula">
<span<?php echo $matricula->id_matricula->ViewAttributes() ?>>
<?php echo $matricula->id_matricula->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_id_matricula" name="x<?php echo $matricula_grid->RowIndex ?>_id_matricula" id="x<?php echo $matricula_grid->RowIndex ?>_id_matricula" value="<?php echo ew_HtmlEncode($matricula->id_matricula->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_matricula" name="o<?php echo $matricula_grid->RowIndex ?>_id_matricula" id="o<?php echo $matricula_grid->RowIndex ?>_id_matricula" value="<?php echo ew_HtmlEncode($matricula->id_matricula->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($matricula->id_afiliado->Visible) { // id_afiliado ?>
		<td>
<?php if ($matricula->CurrentAction <> "F") { ?>
<?php if ($matricula->id_afiliado->getSessionValue() <> "") { ?>
<span<?php echo $matricula->id_afiliado->ViewAttributes() ?>>
<?php echo $matricula->id_afiliado->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" name="x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($matricula->id_afiliado->CurrentValue) ?>">
<?php } else { ?>
<?php
	$wrkonchange = trim(" " . @$matricula->id_afiliado->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$matricula->id_afiliado->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" style="white-space: nowrap; z-index: <?php echo (9000 - $matricula_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" id="sv_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="<?php echo $matricula->id_afiliado->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($matricula->id_afiliado->PlaceHolder) ?>"<?php echo $matricula->id_afiliado->EditAttributes() ?>>&nbsp;<span id="em_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" style="display: inline; z-index: <?php echo (9000 - $matricula_grid->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" data-field="x_id_afiliado" name="x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" id="x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($matricula->id_afiliado->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
 $sSqlWrk = "SELECT `id_afiliado`, `dociden_afiliado` AS `DispFld`, `apell_afiliado` AS `Disp2Fld`, `nomb_afiliado` AS `Disp3Fld` FROM `afiliado`";
 $sWhereWrk = "`dociden_afiliado` LIKE '{query_value}%' OR CONCAT(`dociden_afiliado`,'" . ew_ValueSeparator(1, $Page->id_afiliado) . "',`apell_afiliado`,'" . ew_ValueSeparator(2, $Page->id_afiliado) . "',`nomb_afiliado`) LIKE '{query_value}%'";

 // Call Lookup selecting
 $matricula->Lookup_Selecting($matricula->id_afiliado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" id="q_x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $matricula_grid->RowIndex ?>_id_afiliado", fmatriculagrid, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $matricula_grid->RowIndex ?>_id_afiliado") + ar[i] : "";
	return dv;
}
fmatriculagrid.AutoSuggests["x<?php echo $matricula_grid->RowIndex ?>_id_afiliado"] = oas;
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $matricula->id_afiliado->ViewAttributes() ?>>
<?php echo $matricula->id_afiliado->ViewValue ?></span>
<input type="hidden" data-field="x_id_afiliado" name="x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" id="x<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($matricula->id_afiliado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_afiliado" name="o<?php echo $matricula_grid->RowIndex ?>_id_afiliado" id="o<?php echo $matricula_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($matricula->id_afiliado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($matricula->tipo_matri->Visible) { // tipo_matri ?>
		<td>
<?php if ($matricula->CurrentAction <> "F") { ?>
<span id="el$rowindex$_matricula_tipo_matri" class="control-group matricula_tipo_matri">
<div id="tp_x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" id="x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" value="{value}"<?php echo $matricula->tipo_matri->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->tipo_matri->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($matricula->tipo_matri->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_tipo_matri" name="x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" id="x<?php echo $matricula_grid->RowIndex ?>_tipo_matri_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $matricula->tipo_matri->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $matricula->tipo_matri->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_matricula_tipo_matri" class="control-group matricula_tipo_matri">
<span<?php echo $matricula->tipo_matri->ViewAttributes() ?>>
<?php echo $matricula->tipo_matri->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_tipo_matri" name="x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" id="x<?php echo $matricula_grid->RowIndex ?>_tipo_matri" value="<?php echo ew_HtmlEncode($matricula->tipo_matri->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_tipo_matri" name="o<?php echo $matricula_grid->RowIndex ?>_tipo_matri" id="o<?php echo $matricula_grid->RowIndex ?>_tipo_matri" value="<?php echo ew_HtmlEncode($matricula->tipo_matri->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($matricula->id_plan->Visible) { // id_plan ?>
		<td>
<?php if ($matricula->CurrentAction <> "F") { ?>
<span id="el$rowindex$_matricula_id_plan" class="control-group matricula_id_plan">
<select data-field="x_id_plan" id="x<?php echo $matricula_grid->RowIndex ?>_id_plan" name="x<?php echo $matricula_grid->RowIndex ?>_id_plan"<?php echo $matricula->id_plan->EditAttributes() ?>>
<?php
if (is_array($matricula->id_plan->EditValue)) {
	$arwrk = $matricula->id_plan->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($matricula->id_plan->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$matricula->id_plan) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $matricula->id_plan->OldValue = "";
?>
</select>
<script type="text/javascript">
fmatriculagrid.Lists["x_id_plan"].Options = <?php echo (is_array($matricula->id_plan->EditValue)) ? ew_ArrayToJson($matricula->id_plan->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_matricula_id_plan" class="control-group matricula_id_plan">
<span<?php echo $matricula->id_plan->ViewAttributes() ?>>
<?php echo $matricula->id_plan->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_id_plan" name="x<?php echo $matricula_grid->RowIndex ?>_id_plan" id="x<?php echo $matricula_grid->RowIndex ?>_id_plan" value="<?php echo ew_HtmlEncode($matricula->id_plan->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_plan" name="o<?php echo $matricula_grid->RowIndex ?>_id_plan" id="o<?php echo $matricula_grid->RowIndex ?>_id_plan" value="<?php echo ew_HtmlEncode($matricula->id_plan->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($matricula->valor_matri->Visible) { // valor_matri ?>
		<td>
<?php if ($matricula->CurrentAction <> "F") { ?>
<span id="el$rowindex$_matricula_valor_matri" class="control-group matricula_valor_matri">
<input type="text" data-field="x_valor_matri" name="x<?php echo $matricula_grid->RowIndex ?>_valor_matri" id="x<?php echo $matricula_grid->RowIndex ?>_valor_matri" size="30" placeholder="<?php echo ew_HtmlEncode($matricula->valor_matri->PlaceHolder) ?>" value="<?php echo $matricula->valor_matri->EditValue ?>"<?php echo $matricula->valor_matri->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_matricula_valor_matri" class="control-group matricula_valor_matri">
<span<?php echo $matricula->valor_matri->ViewAttributes() ?>>
<?php echo $matricula->valor_matri->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_valor_matri" name="x<?php echo $matricula_grid->RowIndex ?>_valor_matri" id="x<?php echo $matricula_grid->RowIndex ?>_valor_matri" value="<?php echo ew_HtmlEncode($matricula->valor_matri->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_valor_matri" name="o<?php echo $matricula_grid->RowIndex ?>_valor_matri" id="o<?php echo $matricula_grid->RowIndex ?>_valor_matri" value="<?php echo ew_HtmlEncode($matricula->valor_matri->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($matricula->valor_men_matri->Visible) { // valor_men_matri ?>
		<td>
<?php if ($matricula->CurrentAction <> "F") { ?>
<span id="el$rowindex$_matricula_valor_men_matri" class="control-group matricula_valor_men_matri">
<input type="text" data-field="x_valor_men_matri" name="x<?php echo $matricula_grid->RowIndex ?>_valor_men_matri" id="x<?php echo $matricula_grid->RowIndex ?>_valor_men_matri" size="30" placeholder="<?php echo ew_HtmlEncode($matricula->valor_men_matri->PlaceHolder) ?>" value="<?php echo $matricula->valor_men_matri->EditValue ?>"<?php echo $matricula->valor_men_matri->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_matricula_valor_men_matri" class="control-group matricula_valor_men_matri">
<span<?php echo $matricula->valor_men_matri->ViewAttributes() ?>>
<?php echo $matricula->valor_men_matri->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_valor_men_matri" name="x<?php echo $matricula_grid->RowIndex ?>_valor_men_matri" id="x<?php echo $matricula_grid->RowIndex ?>_valor_men_matri" value="<?php echo ew_HtmlEncode($matricula->valor_men_matri->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_valor_men_matri" name="o<?php echo $matricula_grid->RowIndex ?>_valor_men_matri" id="o<?php echo $matricula_grid->RowIndex ?>_valor_men_matri" value="<?php echo ew_HtmlEncode($matricula->valor_men_matri->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($matricula->conv_matri->Visible) { // conv_matri ?>
		<td>
<?php if ($matricula->CurrentAction <> "F") { ?>
<span id="el$rowindex$_matricula_conv_matri" class="control-group matricula_conv_matri">
<input type="text" data-field="x_conv_matri" name="x<?php echo $matricula_grid->RowIndex ?>_conv_matri" id="x<?php echo $matricula_grid->RowIndex ?>_conv_matri" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($matricula->conv_matri->PlaceHolder) ?>" value="<?php echo $matricula->conv_matri->EditValue ?>"<?php echo $matricula->conv_matri->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_matricula_conv_matri" class="control-group matricula_conv_matri">
<span<?php echo $matricula->conv_matri->ViewAttributes() ?>>
<?php echo $matricula->conv_matri->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_conv_matri" name="x<?php echo $matricula_grid->RowIndex ?>_conv_matri" id="x<?php echo $matricula_grid->RowIndex ?>_conv_matri" value="<?php echo ew_HtmlEncode($matricula->conv_matri->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_conv_matri" name="o<?php echo $matricula_grid->RowIndex ?>_conv_matri" id="o<?php echo $matricula_grid->RowIndex ?>_conv_matri" value="<?php echo ew_HtmlEncode($matricula->conv_matri->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($matricula->id_empleado->Visible) { // id_empleado ?>
		<td>
<?php if ($matricula->CurrentAction <> "F") { ?>
<span id="el$rowindex$_matricula_id_empleado" class="control-group matricula_id_empleado">
<input type="hidden" data-field="x_id_empleado" name="x<?php echo $matricula_grid->RowIndex ?>_id_empleado" id="x<?php echo $matricula_grid->RowIndex ?>_id_empleado" value="<?php echo ew_HtmlEncode($matricula->id_empleado->CurrentValue) ?>">
</span>
<?php } else { ?>
<input type="hidden" data-field="x_id_empleado" name="x<?php echo $matricula_grid->RowIndex ?>_id_empleado" id="x<?php echo $matricula_grid->RowIndex ?>_id_empleado" value="<?php echo ew_HtmlEncode($matricula->id_empleado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_empleado" name="o<?php echo $matricula_grid->RowIndex ?>_id_empleado" id="o<?php echo $matricula_grid->RowIndex ?>_id_empleado" value="<?php echo ew_HtmlEncode($matricula->id_empleado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($matricula->doc4_matri->Visible) { // doc4_matri ?>
		<td>
<?php if ($matricula->CurrentAction <> "F") { ?>
<span id="el$rowindex$_matricula_doc4_matri" class="control-group matricula_doc4_matri">
<div id="tp_x<?php echo $matricula_grid->RowIndex ?>_doc4_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x<?php echo $matricula_grid->RowIndex ?>_doc4_matri[]" id="x<?php echo $matricula_grid->RowIndex ?>_doc4_matri[]" value="{value}"<?php echo $matricula->doc4_matri->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $matricula_grid->RowIndex ?>_doc4_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->doc4_matri->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($matricula->doc4_matri->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox"><input type="checkbox" data-field="x_doc4_matri" name="x<?php echo $matricula_grid->RowIndex ?>_doc4_matri[]" id="x<?php echo $matricula_grid->RowIndex ?>_doc4_matri_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $matricula->doc4_matri->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $matricula->doc4_matri->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_matricula_doc4_matri" class="control-group matricula_doc4_matri">
<span<?php echo $matricula->doc4_matri->ViewAttributes() ?>>
<?php echo $matricula->doc4_matri->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_doc4_matri" name="x<?php echo $matricula_grid->RowIndex ?>_doc4_matri" id="x<?php echo $matricula_grid->RowIndex ?>_doc4_matri" value="<?php echo ew_HtmlEncode($matricula->doc4_matri->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_doc4_matri" name="o<?php echo $matricula_grid->RowIndex ?>_doc4_matri[]" id="o<?php echo $matricula_grid->RowIndex ?>_doc4_matri[]" value="<?php echo ew_HtmlEncode($matricula->doc4_matri->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$matricula_grid->ListOptions->Render("body", "right", $matricula_grid->RowCnt);
?>
<script type="text/javascript">
fmatriculagrid.UpdateOpts(<?php echo $matricula_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($matricula->CurrentMode == "add" || $matricula->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $matricula_grid->FormKeyCountName ?>" id="<?php echo $matricula_grid->FormKeyCountName ?>" value="<?php echo $matricula_grid->KeyCount ?>">
<?php echo $matricula_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($matricula->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $matricula_grid->FormKeyCountName ?>" id="<?php echo $matricula_grid->FormKeyCountName ?>" value="<?php echo $matricula_grid->KeyCount ?>">
<?php echo $matricula_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($matricula->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fmatriculagrid">
</div>
<?php

// Close recordset
if ($matricula_grid->Recordset)
	$matricula_grid->Recordset->Close();
?>
<?php if ($matricula_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($matricula_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($matricula->Export == "") { ?>
<script type="text/javascript">
fmatriculagrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$matricula_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$matricula_grid->Page_Terminate();
?>
