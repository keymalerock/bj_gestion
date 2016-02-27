<?php include_once "v_usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($historial_grid)) $historial_grid = new chistorial_grid();

// Page init
$historial_grid->Page_Init();

// Page main
$historial_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$historial_grid->Page_Render();
?>
<?php if ($historial->Export == "") { ?>
<script type="text/javascript">

// Page object
var historial_grid = new ew_Page("historial_grid");
historial_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = historial_grid.PageID; // For backward compatibility

// Form object
var fhistorialgrid = new ew_Form("fhistorialgrid");
fhistorialgrid.FormKeyCountName = '<?php echo $historial_grid->FormKeyCountName ?>';

// Validate form
fhistorialgrid.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($historial->id_afiliado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id_afiliado");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($historial->id_afiliado->FldErrMsg()) ?>");

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
fhistorialgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_afiliado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "periodo_historial", false)) return false;
	if (ew_ValueChanged(fobj, infix, "team_historial", false)) return false;
	if (ew_ValueChanged(fobj, infix, "torneo_historial", false)) return false;
	return true;
}

// Form_CustomValidate event
fhistorialgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhistorialgrid.ValidateRequired = true;
<?php } else { ?>
fhistorialgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fhistorialgrid.Lists["x_id_afiliado"] = {"LinkField":"x_id_afiliado","Ajax":true,"AutoFill":false,"DisplayFields":["x_nomb_afiliado","x_apell_afiliado","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($historial->getCurrentMasterTable() == "" && $historial_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $historial_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($historial->CurrentAction == "gridadd") {
	if ($historial->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$historial_grid->TotalRecs = $historial->SelectRecordCount();
			$historial_grid->Recordset = $historial_grid->LoadRecordset($historial_grid->StartRec-1, $historial_grid->DisplayRecs);
		} else {
			if ($historial_grid->Recordset = $historial_grid->LoadRecordset())
				$historial_grid->TotalRecs = $historial_grid->Recordset->RecordCount();
		}
		$historial_grid->StartRec = 1;
		$historial_grid->DisplayRecs = $historial_grid->TotalRecs;
	} else {
		$historial->CurrentFilter = "0=1";
		$historial_grid->StartRec = 1;
		$historial_grid->DisplayRecs = $historial->GridAddRowCount;
	}
	$historial_grid->TotalRecs = $historial_grid->DisplayRecs;
	$historial_grid->StopRec = $historial_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$historial_grid->TotalRecs = $historial->SelectRecordCount();
	} else {
		if ($historial_grid->Recordset = $historial_grid->LoadRecordset())
			$historial_grid->TotalRecs = $historial_grid->Recordset->RecordCount();
	}
	$historial_grid->StartRec = 1;
	$historial_grid->DisplayRecs = $historial_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$historial_grid->Recordset = $historial_grid->LoadRecordset($historial_grid->StartRec-1, $historial_grid->DisplayRecs);
}
$historial_grid->RenderOtherOptions();
?>
<?php $historial_grid->ShowPageHeader(); ?>
<?php
$historial_grid->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div id="fhistorialgrid" class="ewForm form-inline">
<div id="gmp_historial" class="ewGridMiddlePanel">
<table id="tbl_historialgrid" class="ewTable ewTableSeparate">
<?php echo $historial->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$historial_grid->RenderListOptions();

// Render list options (header, left)
$historial_grid->ListOptions->Render("header", "left");
?>
<?php if ($historial->id_historial->Visible) { // id_historial ?>
	<?php if ($historial->SortUrl($historial->id_historial) == "") { ?>
		<td><div id="elh_historial_id_historial" class="historial_id_historial"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $historial->id_historial->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_historial_id_historial" class="historial_id_historial">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $historial->id_historial->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial->id_historial->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial->id_historial->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($historial->id_afiliado->Visible) { // id_afiliado ?>
	<?php if ($historial->SortUrl($historial->id_afiliado) == "") { ?>
		<td><div id="elh_historial_id_afiliado" class="historial_id_afiliado"><div class="ewTableHeaderCaption"><?php echo $historial->id_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_historial_id_afiliado" class="historial_id_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $historial->id_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial->id_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial->id_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($historial->periodo_historial->Visible) { // periodo_historial ?>
	<?php if ($historial->SortUrl($historial->periodo_historial) == "") { ?>
		<td><div id="elh_historial_periodo_historial" class="historial_periodo_historial"><div class="ewTableHeaderCaption"><?php echo $historial->periodo_historial->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_historial_periodo_historial" class="historial_periodo_historial">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $historial->periodo_historial->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial->periodo_historial->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial->periodo_historial->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($historial->team_historial->Visible) { // team_historial ?>
	<?php if ($historial->SortUrl($historial->team_historial) == "") { ?>
		<td><div id="elh_historial_team_historial" class="historial_team_historial"><div class="ewTableHeaderCaption"><?php echo $historial->team_historial->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_historial_team_historial" class="historial_team_historial">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $historial->team_historial->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial->team_historial->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial->team_historial->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($historial->torneo_historial->Visible) { // torneo_historial ?>
	<?php if ($historial->SortUrl($historial->torneo_historial) == "") { ?>
		<td><div id="elh_historial_torneo_historial" class="historial_torneo_historial"><div class="ewTableHeaderCaption"><?php echo $historial->torneo_historial->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_historial_torneo_historial" class="historial_torneo_historial">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $historial->torneo_historial->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($historial->torneo_historial->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($historial->torneo_historial->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$historial_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$historial_grid->StartRec = 1;
$historial_grid->StopRec = $historial_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($historial_grid->FormKeyCountName) && ($historial->CurrentAction == "gridadd" || $historial->CurrentAction == "gridedit" || $historial->CurrentAction == "F")) {
		$historial_grid->KeyCount = $objForm->GetValue($historial_grid->FormKeyCountName);
		$historial_grid->StopRec = $historial_grid->StartRec + $historial_grid->KeyCount - 1;
	}
}
$historial_grid->RecCnt = $historial_grid->StartRec - 1;
if ($historial_grid->Recordset && !$historial_grid->Recordset->EOF) {
	$historial_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $historial_grid->StartRec > 1)
		$historial_grid->Recordset->Move($historial_grid->StartRec - 1);
} elseif (!$historial->AllowAddDeleteRow && $historial_grid->StopRec == 0) {
	$historial_grid->StopRec = $historial->GridAddRowCount;
}

// Initialize aggregate
$historial->RowType = EW_ROWTYPE_AGGREGATEINIT;
$historial->ResetAttrs();
$historial_grid->RenderRow();
if ($historial->CurrentAction == "gridadd")
	$historial_grid->RowIndex = 0;
if ($historial->CurrentAction == "gridedit")
	$historial_grid->RowIndex = 0;
while ($historial_grid->RecCnt < $historial_grid->StopRec) {
	$historial_grid->RecCnt++;
	if (intval($historial_grid->RecCnt) >= intval($historial_grid->StartRec)) {
		$historial_grid->RowCnt++;
		if ($historial->CurrentAction == "gridadd" || $historial->CurrentAction == "gridedit" || $historial->CurrentAction == "F") {
			$historial_grid->RowIndex++;
			$objForm->Index = $historial_grid->RowIndex;
			if ($objForm->HasValue($historial_grid->FormActionName))
				$historial_grid->RowAction = strval($objForm->GetValue($historial_grid->FormActionName));
			elseif ($historial->CurrentAction == "gridadd")
				$historial_grid->RowAction = "insert";
			else
				$historial_grid->RowAction = "";
		}

		// Set up key count
		$historial_grid->KeyCount = $historial_grid->RowIndex;

		// Init row class and style
		$historial->ResetAttrs();
		$historial->CssClass = "";
		if ($historial->CurrentAction == "gridadd") {
			if ($historial->CurrentMode == "copy") {
				$historial_grid->LoadRowValues($historial_grid->Recordset); // Load row values
				$historial_grid->SetRecordKey($historial_grid->RowOldKey, $historial_grid->Recordset); // Set old record key
			} else {
				$historial_grid->LoadDefaultValues(); // Load default values
				$historial_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$historial_grid->LoadRowValues($historial_grid->Recordset); // Load row values
		}
		$historial->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($historial->CurrentAction == "gridadd") // Grid add
			$historial->RowType = EW_ROWTYPE_ADD; // Render add
		if ($historial->CurrentAction == "gridadd" && $historial->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$historial_grid->RestoreCurrentRowFormValues($historial_grid->RowIndex); // Restore form values
		if ($historial->CurrentAction == "gridedit") { // Grid edit
			if ($historial->EventCancelled) {
				$historial_grid->RestoreCurrentRowFormValues($historial_grid->RowIndex); // Restore form values
			}
			if ($historial_grid->RowAction == "insert")
				$historial->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$historial->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($historial->CurrentAction == "gridedit" && ($historial->RowType == EW_ROWTYPE_EDIT || $historial->RowType == EW_ROWTYPE_ADD) && $historial->EventCancelled) // Update failed
			$historial_grid->RestoreCurrentRowFormValues($historial_grid->RowIndex); // Restore form values
		if ($historial->RowType == EW_ROWTYPE_EDIT) // Edit row
			$historial_grid->EditRowCnt++;
		if ($historial->CurrentAction == "F") // Confirm row
			$historial_grid->RestoreCurrentRowFormValues($historial_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$historial->RowAttrs = array_merge($historial->RowAttrs, array('data-rowindex'=>$historial_grid->RowCnt, 'id'=>'r' . $historial_grid->RowCnt . '_historial', 'data-rowtype'=>$historial->RowType));

		// Render row
		$historial_grid->RenderRow();

		// Render list options
		$historial_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($historial_grid->RowAction <> "delete" && $historial_grid->RowAction <> "insertdelete" && !($historial_grid->RowAction == "insert" && $historial->CurrentAction == "F" && $historial_grid->EmptyRow())) {
?>
	<tr<?php echo $historial->RowAttributes() ?>>
<?php

// Render list options (body, left)
$historial_grid->ListOptions->Render("body", "left", $historial_grid->RowCnt);
?>
	<?php if ($historial->id_historial->Visible) { // id_historial ?>
		<td<?php echo $historial->id_historial->CellAttributes() ?>>
<?php if ($historial->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id_historial" name="o<?php echo $historial_grid->RowIndex ?>_id_historial" id="o<?php echo $historial_grid->RowIndex ?>_id_historial" value="<?php echo ew_HtmlEncode($historial->id_historial->OldValue) ?>">
<?php } ?>
<?php if ($historial->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $historial_grid->RowCnt ?>_historial_id_historial" class="control-group historial_id_historial">
<span<?php echo $historial->id_historial->ViewAttributes() ?>>
<?php echo $historial->id_historial->EditValue ?></span>
</span>
<input type="hidden" data-field="x_id_historial" name="x<?php echo $historial_grid->RowIndex ?>_id_historial" id="x<?php echo $historial_grid->RowIndex ?>_id_historial" value="<?php echo ew_HtmlEncode($historial->id_historial->CurrentValue) ?>">
<?php } ?>
<?php if ($historial->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $historial->id_historial->ViewAttributes() ?>>
<?php echo $historial->id_historial->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_historial" name="x<?php echo $historial_grid->RowIndex ?>_id_historial" id="x<?php echo $historial_grid->RowIndex ?>_id_historial" value="<?php echo ew_HtmlEncode($historial->id_historial->FormValue) ?>">
<input type="hidden" data-field="x_id_historial" name="o<?php echo $historial_grid->RowIndex ?>_id_historial" id="o<?php echo $historial_grid->RowIndex ?>_id_historial" value="<?php echo ew_HtmlEncode($historial->id_historial->OldValue) ?>">
<?php } ?>
<a id="<?php echo $historial_grid->PageObjName . "_row_" . $historial_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($historial->id_afiliado->Visible) { // id_afiliado ?>
		<td<?php echo $historial->id_afiliado->CellAttributes() ?>>
<?php if ($historial->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($historial->id_afiliado->getSessionValue() <> "") { ?>
<span<?php echo $historial->id_afiliado->ViewAttributes() ?>>
<?php echo $historial->id_afiliado->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $historial_grid->RowIndex ?>_id_afiliado" name="x<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($historial->id_afiliado->CurrentValue) ?>">
<?php } else { ?>
<?php
	$wrkonchange = trim(" " . @$historial->id_afiliado->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$historial->id_afiliado->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" style="white-space: nowrap; z-index: <?php echo (9000 - $historial_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" id="sv_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="<?php echo $historial->id_afiliado->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($historial->id_afiliado->PlaceHolder) ?>"<?php echo $historial->id_afiliado->EditAttributes() ?>>&nbsp;<span id="em_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" style="display: inline; z-index: <?php echo (9000 - $historial_grid->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" data-field="x_id_afiliado" name="x<?php echo $historial_grid->RowIndex ?>_id_afiliado" id="x<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($historial->id_afiliado->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
 $sSqlWrk = "SELECT `id_afiliado`, `nomb_afiliado` AS `DispFld`, `apell_afiliado` AS `Disp2Fld` FROM `afiliado`";
 $sWhereWrk = "`nomb_afiliado` LIKE '{query_value}%' OR CONCAT(`nomb_afiliado`,'" . ew_ValueSeparator(1, $Page->id_afiliado) . "',`apell_afiliado`) LIKE '{query_value}%'";

 // Call Lookup selecting
 $historial->Lookup_Selecting($historial->id_afiliado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" id="q_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $historial_grid->RowIndex ?>_id_afiliado", fhistorialgrid, true, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $historial_grid->RowIndex ?>_id_afiliado") + ar[i] : "";
	return dv;
}
fhistorialgrid.AutoSuggests["x<?php echo $historial_grid->RowIndex ?>_id_afiliado"] = oas;
</script>
<?php } ?>
<input type="hidden" data-field="x_id_afiliado" name="o<?php echo $historial_grid->RowIndex ?>_id_afiliado" id="o<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($historial->id_afiliado->OldValue) ?>">
<?php } ?>
<?php if ($historial->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($historial->id_afiliado->getSessionValue() <> "") { ?>
<span<?php echo $historial->id_afiliado->ViewAttributes() ?>>
<?php echo $historial->id_afiliado->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $historial_grid->RowIndex ?>_id_afiliado" name="x<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($historial->id_afiliado->CurrentValue) ?>">
<?php } else { ?>
<?php
	$wrkonchange = trim(" " . @$historial->id_afiliado->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$historial->id_afiliado->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" style="white-space: nowrap; z-index: <?php echo (9000 - $historial_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" id="sv_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="<?php echo $historial->id_afiliado->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($historial->id_afiliado->PlaceHolder) ?>"<?php echo $historial->id_afiliado->EditAttributes() ?>>&nbsp;<span id="em_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" style="display: inline; z-index: <?php echo (9000 - $historial_grid->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" data-field="x_id_afiliado" name="x<?php echo $historial_grid->RowIndex ?>_id_afiliado" id="x<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($historial->id_afiliado->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
 $sSqlWrk = "SELECT `id_afiliado`, `nomb_afiliado` AS `DispFld`, `apell_afiliado` AS `Disp2Fld` FROM `afiliado`";
 $sWhereWrk = "`nomb_afiliado` LIKE '{query_value}%' OR CONCAT(`nomb_afiliado`,'" . ew_ValueSeparator(1, $Page->id_afiliado) . "',`apell_afiliado`) LIKE '{query_value}%'";

 // Call Lookup selecting
 $historial->Lookup_Selecting($historial->id_afiliado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" id="q_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $historial_grid->RowIndex ?>_id_afiliado", fhistorialgrid, true, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $historial_grid->RowIndex ?>_id_afiliado") + ar[i] : "";
	return dv;
}
fhistorialgrid.AutoSuggests["x<?php echo $historial_grid->RowIndex ?>_id_afiliado"] = oas;
</script>
<?php } ?>
<?php } ?>
<?php if ($historial->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $historial->id_afiliado->ViewAttributes() ?>>
<?php echo $historial->id_afiliado->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_afiliado" name="x<?php echo $historial_grid->RowIndex ?>_id_afiliado" id="x<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($historial->id_afiliado->FormValue) ?>">
<input type="hidden" data-field="x_id_afiliado" name="o<?php echo $historial_grid->RowIndex ?>_id_afiliado" id="o<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($historial->id_afiliado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($historial->periodo_historial->Visible) { // periodo_historial ?>
		<td<?php echo $historial->periodo_historial->CellAttributes() ?>>
<?php if ($historial->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $historial_grid->RowCnt ?>_historial_periodo_historial" class="control-group historial_periodo_historial">
<input type="text" data-field="x_periodo_historial" name="x<?php echo $historial_grid->RowIndex ?>_periodo_historial" id="x<?php echo $historial_grid->RowIndex ?>_periodo_historial" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($historial->periodo_historial->PlaceHolder) ?>" value="<?php echo $historial->periodo_historial->EditValue ?>"<?php echo $historial->periodo_historial->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_periodo_historial" name="o<?php echo $historial_grid->RowIndex ?>_periodo_historial" id="o<?php echo $historial_grid->RowIndex ?>_periodo_historial" value="<?php echo ew_HtmlEncode($historial->periodo_historial->OldValue) ?>">
<?php } ?>
<?php if ($historial->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $historial_grid->RowCnt ?>_historial_periodo_historial" class="control-group historial_periodo_historial">
<input type="text" data-field="x_periodo_historial" name="x<?php echo $historial_grid->RowIndex ?>_periodo_historial" id="x<?php echo $historial_grid->RowIndex ?>_periodo_historial" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($historial->periodo_historial->PlaceHolder) ?>" value="<?php echo $historial->periodo_historial->EditValue ?>"<?php echo $historial->periodo_historial->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($historial->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $historial->periodo_historial->ViewAttributes() ?>>
<?php echo $historial->periodo_historial->ListViewValue() ?></span>
<input type="hidden" data-field="x_periodo_historial" name="x<?php echo $historial_grid->RowIndex ?>_periodo_historial" id="x<?php echo $historial_grid->RowIndex ?>_periodo_historial" value="<?php echo ew_HtmlEncode($historial->periodo_historial->FormValue) ?>">
<input type="hidden" data-field="x_periodo_historial" name="o<?php echo $historial_grid->RowIndex ?>_periodo_historial" id="o<?php echo $historial_grid->RowIndex ?>_periodo_historial" value="<?php echo ew_HtmlEncode($historial->periodo_historial->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($historial->team_historial->Visible) { // team_historial ?>
		<td<?php echo $historial->team_historial->CellAttributes() ?>>
<?php if ($historial->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $historial_grid->RowCnt ?>_historial_team_historial" class="control-group historial_team_historial">
<input type="text" data-field="x_team_historial" name="x<?php echo $historial_grid->RowIndex ?>_team_historial" id="x<?php echo $historial_grid->RowIndex ?>_team_historial" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($historial->team_historial->PlaceHolder) ?>" value="<?php echo $historial->team_historial->EditValue ?>"<?php echo $historial->team_historial->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_team_historial" name="o<?php echo $historial_grid->RowIndex ?>_team_historial" id="o<?php echo $historial_grid->RowIndex ?>_team_historial" value="<?php echo ew_HtmlEncode($historial->team_historial->OldValue) ?>">
<?php } ?>
<?php if ($historial->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $historial_grid->RowCnt ?>_historial_team_historial" class="control-group historial_team_historial">
<input type="text" data-field="x_team_historial" name="x<?php echo $historial_grid->RowIndex ?>_team_historial" id="x<?php echo $historial_grid->RowIndex ?>_team_historial" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($historial->team_historial->PlaceHolder) ?>" value="<?php echo $historial->team_historial->EditValue ?>"<?php echo $historial->team_historial->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($historial->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $historial->team_historial->ViewAttributes() ?>>
<?php echo $historial->team_historial->ListViewValue() ?></span>
<input type="hidden" data-field="x_team_historial" name="x<?php echo $historial_grid->RowIndex ?>_team_historial" id="x<?php echo $historial_grid->RowIndex ?>_team_historial" value="<?php echo ew_HtmlEncode($historial->team_historial->FormValue) ?>">
<input type="hidden" data-field="x_team_historial" name="o<?php echo $historial_grid->RowIndex ?>_team_historial" id="o<?php echo $historial_grid->RowIndex ?>_team_historial" value="<?php echo ew_HtmlEncode($historial->team_historial->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($historial->torneo_historial->Visible) { // torneo_historial ?>
		<td<?php echo $historial->torneo_historial->CellAttributes() ?>>
<?php if ($historial->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $historial_grid->RowCnt ?>_historial_torneo_historial" class="control-group historial_torneo_historial">
<input type="text" data-field="x_torneo_historial" name="x<?php echo $historial_grid->RowIndex ?>_torneo_historial" id="x<?php echo $historial_grid->RowIndex ?>_torneo_historial" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($historial->torneo_historial->PlaceHolder) ?>" value="<?php echo $historial->torneo_historial->EditValue ?>"<?php echo $historial->torneo_historial->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_torneo_historial" name="o<?php echo $historial_grid->RowIndex ?>_torneo_historial" id="o<?php echo $historial_grid->RowIndex ?>_torneo_historial" value="<?php echo ew_HtmlEncode($historial->torneo_historial->OldValue) ?>">
<?php } ?>
<?php if ($historial->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $historial_grid->RowCnt ?>_historial_torneo_historial" class="control-group historial_torneo_historial">
<input type="text" data-field="x_torneo_historial" name="x<?php echo $historial_grid->RowIndex ?>_torneo_historial" id="x<?php echo $historial_grid->RowIndex ?>_torneo_historial" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($historial->torneo_historial->PlaceHolder) ?>" value="<?php echo $historial->torneo_historial->EditValue ?>"<?php echo $historial->torneo_historial->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($historial->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $historial->torneo_historial->ViewAttributes() ?>>
<?php echo $historial->torneo_historial->ListViewValue() ?></span>
<input type="hidden" data-field="x_torneo_historial" name="x<?php echo $historial_grid->RowIndex ?>_torneo_historial" id="x<?php echo $historial_grid->RowIndex ?>_torneo_historial" value="<?php echo ew_HtmlEncode($historial->torneo_historial->FormValue) ?>">
<input type="hidden" data-field="x_torneo_historial" name="o<?php echo $historial_grid->RowIndex ?>_torneo_historial" id="o<?php echo $historial_grid->RowIndex ?>_torneo_historial" value="<?php echo ew_HtmlEncode($historial->torneo_historial->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$historial_grid->ListOptions->Render("body", "right", $historial_grid->RowCnt);
?>
	</tr>
<?php if ($historial->RowType == EW_ROWTYPE_ADD || $historial->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fhistorialgrid.UpdateOpts(<?php echo $historial_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($historial->CurrentAction <> "gridadd" || $historial->CurrentMode == "copy")
		if (!$historial_grid->Recordset->EOF) $historial_grid->Recordset->MoveNext();
}
?>
<?php
	if ($historial->CurrentMode == "add" || $historial->CurrentMode == "copy" || $historial->CurrentMode == "edit") {
		$historial_grid->RowIndex = '$rowindex$';
		$historial_grid->LoadDefaultValues();

		// Set row properties
		$historial->ResetAttrs();
		$historial->RowAttrs = array_merge($historial->RowAttrs, array('data-rowindex'=>$historial_grid->RowIndex, 'id'=>'r0_historial', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($historial->RowAttrs["class"], "ewTemplate");
		$historial->RowType = EW_ROWTYPE_ADD;

		// Render row
		$historial_grid->RenderRow();

		// Render list options
		$historial_grid->RenderListOptions();
		$historial_grid->StartRowCnt = 0;
?>
	<tr<?php echo $historial->RowAttributes() ?>>
<?php

// Render list options (body, left)
$historial_grid->ListOptions->Render("body", "left", $historial_grid->RowIndex);
?>
	<?php if ($historial->id_historial->Visible) { // id_historial ?>
		<td>
<?php if ($historial->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_historial_id_historial" class="control-group historial_id_historial">
<span<?php echo $historial->id_historial->ViewAttributes() ?>>
<?php echo $historial->id_historial->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_id_historial" name="x<?php echo $historial_grid->RowIndex ?>_id_historial" id="x<?php echo $historial_grid->RowIndex ?>_id_historial" value="<?php echo ew_HtmlEncode($historial->id_historial->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_historial" name="o<?php echo $historial_grid->RowIndex ?>_id_historial" id="o<?php echo $historial_grid->RowIndex ?>_id_historial" value="<?php echo ew_HtmlEncode($historial->id_historial->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($historial->id_afiliado->Visible) { // id_afiliado ?>
		<td>
<?php if ($historial->CurrentAction <> "F") { ?>
<?php if ($historial->id_afiliado->getSessionValue() <> "") { ?>
<span<?php echo $historial->id_afiliado->ViewAttributes() ?>>
<?php echo $historial->id_afiliado->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $historial_grid->RowIndex ?>_id_afiliado" name="x<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($historial->id_afiliado->CurrentValue) ?>">
<?php } else { ?>
<?php
	$wrkonchange = trim(" " . @$historial->id_afiliado->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$historial->id_afiliado->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" style="white-space: nowrap; z-index: <?php echo (9000 - $historial_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" id="sv_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="<?php echo $historial->id_afiliado->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($historial->id_afiliado->PlaceHolder) ?>"<?php echo $historial->id_afiliado->EditAttributes() ?>>&nbsp;<span id="em_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" style="display: inline; z-index: <?php echo (9000 - $historial_grid->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" data-field="x_id_afiliado" name="x<?php echo $historial_grid->RowIndex ?>_id_afiliado" id="x<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($historial->id_afiliado->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
 $sSqlWrk = "SELECT `id_afiliado`, `nomb_afiliado` AS `DispFld`, `apell_afiliado` AS `Disp2Fld` FROM `afiliado`";
 $sWhereWrk = "`nomb_afiliado` LIKE '{query_value}%' OR CONCAT(`nomb_afiliado`,'" . ew_ValueSeparator(1, $Page->id_afiliado) . "',`apell_afiliado`) LIKE '{query_value}%'";

 // Call Lookup selecting
 $historial->Lookup_Selecting($historial->id_afiliado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" id="q_x<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $historial_grid->RowIndex ?>_id_afiliado", fhistorialgrid, true, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $historial_grid->RowIndex ?>_id_afiliado") + ar[i] : "";
	return dv;
}
fhistorialgrid.AutoSuggests["x<?php echo $historial_grid->RowIndex ?>_id_afiliado"] = oas;
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $historial->id_afiliado->ViewAttributes() ?>>
<?php echo $historial->id_afiliado->ViewValue ?></span>
<input type="hidden" data-field="x_id_afiliado" name="x<?php echo $historial_grid->RowIndex ?>_id_afiliado" id="x<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($historial->id_afiliado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_afiliado" name="o<?php echo $historial_grid->RowIndex ?>_id_afiliado" id="o<?php echo $historial_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($historial->id_afiliado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($historial->periodo_historial->Visible) { // periodo_historial ?>
		<td>
<?php if ($historial->CurrentAction <> "F") { ?>
<span id="el$rowindex$_historial_periodo_historial" class="control-group historial_periodo_historial">
<input type="text" data-field="x_periodo_historial" name="x<?php echo $historial_grid->RowIndex ?>_periodo_historial" id="x<?php echo $historial_grid->RowIndex ?>_periodo_historial" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($historial->periodo_historial->PlaceHolder) ?>" value="<?php echo $historial->periodo_historial->EditValue ?>"<?php echo $historial->periodo_historial->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_historial_periodo_historial" class="control-group historial_periodo_historial">
<span<?php echo $historial->periodo_historial->ViewAttributes() ?>>
<?php echo $historial->periodo_historial->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_periodo_historial" name="x<?php echo $historial_grid->RowIndex ?>_periodo_historial" id="x<?php echo $historial_grid->RowIndex ?>_periodo_historial" value="<?php echo ew_HtmlEncode($historial->periodo_historial->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_periodo_historial" name="o<?php echo $historial_grid->RowIndex ?>_periodo_historial" id="o<?php echo $historial_grid->RowIndex ?>_periodo_historial" value="<?php echo ew_HtmlEncode($historial->periodo_historial->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($historial->team_historial->Visible) { // team_historial ?>
		<td>
<?php if ($historial->CurrentAction <> "F") { ?>
<span id="el$rowindex$_historial_team_historial" class="control-group historial_team_historial">
<input type="text" data-field="x_team_historial" name="x<?php echo $historial_grid->RowIndex ?>_team_historial" id="x<?php echo $historial_grid->RowIndex ?>_team_historial" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($historial->team_historial->PlaceHolder) ?>" value="<?php echo $historial->team_historial->EditValue ?>"<?php echo $historial->team_historial->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_historial_team_historial" class="control-group historial_team_historial">
<span<?php echo $historial->team_historial->ViewAttributes() ?>>
<?php echo $historial->team_historial->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_team_historial" name="x<?php echo $historial_grid->RowIndex ?>_team_historial" id="x<?php echo $historial_grid->RowIndex ?>_team_historial" value="<?php echo ew_HtmlEncode($historial->team_historial->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_team_historial" name="o<?php echo $historial_grid->RowIndex ?>_team_historial" id="o<?php echo $historial_grid->RowIndex ?>_team_historial" value="<?php echo ew_HtmlEncode($historial->team_historial->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($historial->torneo_historial->Visible) { // torneo_historial ?>
		<td>
<?php if ($historial->CurrentAction <> "F") { ?>
<span id="el$rowindex$_historial_torneo_historial" class="control-group historial_torneo_historial">
<input type="text" data-field="x_torneo_historial" name="x<?php echo $historial_grid->RowIndex ?>_torneo_historial" id="x<?php echo $historial_grid->RowIndex ?>_torneo_historial" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($historial->torneo_historial->PlaceHolder) ?>" value="<?php echo $historial->torneo_historial->EditValue ?>"<?php echo $historial->torneo_historial->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_historial_torneo_historial" class="control-group historial_torneo_historial">
<span<?php echo $historial->torneo_historial->ViewAttributes() ?>>
<?php echo $historial->torneo_historial->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_torneo_historial" name="x<?php echo $historial_grid->RowIndex ?>_torneo_historial" id="x<?php echo $historial_grid->RowIndex ?>_torneo_historial" value="<?php echo ew_HtmlEncode($historial->torneo_historial->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_torneo_historial" name="o<?php echo $historial_grid->RowIndex ?>_torneo_historial" id="o<?php echo $historial_grid->RowIndex ?>_torneo_historial" value="<?php echo ew_HtmlEncode($historial->torneo_historial->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$historial_grid->ListOptions->Render("body", "right", $historial_grid->RowCnt);
?>
<script type="text/javascript">
fhistorialgrid.UpdateOpts(<?php echo $historial_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($historial->CurrentMode == "add" || $historial->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $historial_grid->FormKeyCountName ?>" id="<?php echo $historial_grid->FormKeyCountName ?>" value="<?php echo $historial_grid->KeyCount ?>">
<?php echo $historial_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($historial->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $historial_grid->FormKeyCountName ?>" id="<?php echo $historial_grid->FormKeyCountName ?>" value="<?php echo $historial_grid->KeyCount ?>">
<?php echo $historial_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($historial->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fhistorialgrid">
</div>
<?php

// Close recordset
if ($historial_grid->Recordset)
	$historial_grid->Recordset->Close();
?>
<?php if ($historial_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($historial_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($historial->Export == "") { ?>
<script type="text/javascript">
fhistorialgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$historial_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$historial_grid->Page_Terminate();
?>
