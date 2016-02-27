<?php include_once "v_usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($representantes_grid)) $representantes_grid = new crepresentantes_grid();

// Page init
$representantes_grid->Page_Init();

// Page main
$representantes_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$representantes_grid->Page_Render();
?>
<?php if ($representantes->Export == "") { ?>
<script type="text/javascript">

// Page object
var representantes_grid = new ew_Page("representantes_grid");
representantes_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = representantes_grid.PageID; // For backward compatibility

// Form object
var frepresentantesgrid = new ew_Form("frepresentantesgrid");
frepresentantesgrid.FormKeyCountName = '<?php echo $representantes_grid->FormKeyCountName ?>';

// Validate form
frepresentantesgrid.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($representantes->id_afiliado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dociden_repres");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($representantes->dociden_repres->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_apell_repres");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($representantes->apell_repres->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nomb_repres");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($representantes->nomb_repres->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_email_repres");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($representantes->email_repres->FldErrMsg()) ?>");

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
frepresentantesgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_afiliado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "dociden_repres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "apell_repres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nomb_repres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "telf_resi_repres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "email_repres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "par_repres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "cel_repres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "contact_e_repres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "contact_d_repres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "st_repres", false)) return false;
	return true;
}

// Form_CustomValidate event
frepresentantesgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frepresentantesgrid.ValidateRequired = true;
<?php } else { ?>
frepresentantesgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frepresentantesgrid.Lists["x_id_afiliado"] = {"LinkField":"x_id_afiliado","Ajax":true,"AutoFill":false,"DisplayFields":["x_apell_afiliado","x_nomb_afiliado","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($representantes->getCurrentMasterTable() == "" && $representantes_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $representantes_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($representantes->CurrentAction == "gridadd") {
	if ($representantes->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$representantes_grid->TotalRecs = $representantes->SelectRecordCount();
			$representantes_grid->Recordset = $representantes_grid->LoadRecordset($representantes_grid->StartRec-1, $representantes_grid->DisplayRecs);
		} else {
			if ($representantes_grid->Recordset = $representantes_grid->LoadRecordset())
				$representantes_grid->TotalRecs = $representantes_grid->Recordset->RecordCount();
		}
		$representantes_grid->StartRec = 1;
		$representantes_grid->DisplayRecs = $representantes_grid->TotalRecs;
	} else {
		$representantes->CurrentFilter = "0=1";
		$representantes_grid->StartRec = 1;
		$representantes_grid->DisplayRecs = $representantes->GridAddRowCount;
	}
	$representantes_grid->TotalRecs = $representantes_grid->DisplayRecs;
	$representantes_grid->StopRec = $representantes_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$representantes_grid->TotalRecs = $representantes->SelectRecordCount();
	} else {
		if ($representantes_grid->Recordset = $representantes_grid->LoadRecordset())
			$representantes_grid->TotalRecs = $representantes_grid->Recordset->RecordCount();
	}
	$representantes_grid->StartRec = 1;
	$representantes_grid->DisplayRecs = $representantes_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$representantes_grid->Recordset = $representantes_grid->LoadRecordset($representantes_grid->StartRec-1, $representantes_grid->DisplayRecs);
}
$representantes_grid->RenderOtherOptions();
?>
<?php $representantes_grid->ShowPageHeader(); ?>
<?php
$representantes_grid->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div id="frepresentantesgrid" class="ewForm form-inline">
<div id="gmp_representantes" class="ewGridMiddlePanel">
<table id="tbl_representantesgrid" class="ewTable ewTableSeparate">
<?php echo $representantes->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$representantes_grid->RenderListOptions();

// Render list options (header, left)
$representantes_grid->ListOptions->Render("header", "left");
?>
<?php if ($representantes->id_representante->Visible) { // id_representante ?>
	<?php if ($representantes->SortUrl($representantes->id_representante) == "") { ?>
		<td><div id="elh_representantes_id_representante" class="representantes_id_representante"><div class="ewTableHeaderCaption"><?php echo $representantes->id_representante->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_representantes_id_representante" class="representantes_id_representante">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->id_representante->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->id_representante->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->id_representante->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->id_afiliado->Visible) { // id_afiliado ?>
	<?php if ($representantes->SortUrl($representantes->id_afiliado) == "") { ?>
		<td><div id="elh_representantes_id_afiliado" class="representantes_id_afiliado"><div class="ewTableHeaderCaption"><?php echo $representantes->id_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_representantes_id_afiliado" class="representantes_id_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->id_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->id_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->id_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->dociden_repres->Visible) { // dociden_repres ?>
	<?php if ($representantes->SortUrl($representantes->dociden_repres) == "") { ?>
		<td><div id="elh_representantes_dociden_repres" class="representantes_dociden_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->dociden_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_representantes_dociden_repres" class="representantes_dociden_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->dociden_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->dociden_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->dociden_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->apell_repres->Visible) { // apell_repres ?>
	<?php if ($representantes->SortUrl($representantes->apell_repres) == "") { ?>
		<td><div id="elh_representantes_apell_repres" class="representantes_apell_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->apell_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_representantes_apell_repres" class="representantes_apell_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->apell_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->apell_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->apell_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->nomb_repres->Visible) { // nomb_repres ?>
	<?php if ($representantes->SortUrl($representantes->nomb_repres) == "") { ?>
		<td><div id="elh_representantes_nomb_repres" class="representantes_nomb_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->nomb_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_representantes_nomb_repres" class="representantes_nomb_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->nomb_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->nomb_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->nomb_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->telf_resi_repres->Visible) { // telf_resi_repres ?>
	<?php if ($representantes->SortUrl($representantes->telf_resi_repres) == "") { ?>
		<td><div id="elh_representantes_telf_resi_repres" class="representantes_telf_resi_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->telf_resi_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_representantes_telf_resi_repres" class="representantes_telf_resi_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->telf_resi_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->telf_resi_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->telf_resi_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->email_repres->Visible) { // email_repres ?>
	<?php if ($representantes->SortUrl($representantes->email_repres) == "") { ?>
		<td><div id="elh_representantes_email_repres" class="representantes_email_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->email_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_representantes_email_repres" class="representantes_email_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->email_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->email_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->email_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->par_repres->Visible) { // par_repres ?>
	<?php if ($representantes->SortUrl($representantes->par_repres) == "") { ?>
		<td><div id="elh_representantes_par_repres" class="representantes_par_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->par_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_representantes_par_repres" class="representantes_par_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->par_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->par_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->par_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->cel_repres->Visible) { // cel_repres ?>
	<?php if ($representantes->SortUrl($representantes->cel_repres) == "") { ?>
		<td><div id="elh_representantes_cel_repres" class="representantes_cel_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->cel_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_representantes_cel_repres" class="representantes_cel_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->cel_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->cel_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->cel_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->contact_e_repres->Visible) { // contact_e_repres ?>
	<?php if ($representantes->SortUrl($representantes->contact_e_repres) == "") { ?>
		<td><div id="elh_representantes_contact_e_repres" class="representantes_contact_e_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->contact_e_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_representantes_contact_e_repres" class="representantes_contact_e_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->contact_e_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->contact_e_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->contact_e_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->contact_d_repres->Visible) { // contact_d_repres ?>
	<?php if ($representantes->SortUrl($representantes->contact_d_repres) == "") { ?>
		<td><div id="elh_representantes_contact_d_repres" class="representantes_contact_d_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->contact_d_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_representantes_contact_d_repres" class="representantes_contact_d_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->contact_d_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->contact_d_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->contact_d_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->st_repres->Visible) { // st_repres ?>
	<?php if ($representantes->SortUrl($representantes->st_repres) == "") { ?>
		<td><div id="elh_representantes_st_repres" class="representantes_st_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->st_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_representantes_st_repres" class="representantes_st_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->st_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->st_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->st_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$representantes_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$representantes_grid->StartRec = 1;
$representantes_grid->StopRec = $representantes_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($representantes_grid->FormKeyCountName) && ($representantes->CurrentAction == "gridadd" || $representantes->CurrentAction == "gridedit" || $representantes->CurrentAction == "F")) {
		$representantes_grid->KeyCount = $objForm->GetValue($representantes_grid->FormKeyCountName);
		$representantes_grid->StopRec = $representantes_grid->StartRec + $representantes_grid->KeyCount - 1;
	}
}
$representantes_grid->RecCnt = $representantes_grid->StartRec - 1;
if ($representantes_grid->Recordset && !$representantes_grid->Recordset->EOF) {
	$representantes_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $representantes_grid->StartRec > 1)
		$representantes_grid->Recordset->Move($representantes_grid->StartRec - 1);
} elseif (!$representantes->AllowAddDeleteRow && $representantes_grid->StopRec == 0) {
	$representantes_grid->StopRec = $representantes->GridAddRowCount;
}

// Initialize aggregate
$representantes->RowType = EW_ROWTYPE_AGGREGATEINIT;
$representantes->ResetAttrs();
$representantes_grid->RenderRow();
if ($representantes->CurrentAction == "gridadd")
	$representantes_grid->RowIndex = 0;
if ($representantes->CurrentAction == "gridedit")
	$representantes_grid->RowIndex = 0;
while ($representantes_grid->RecCnt < $representantes_grid->StopRec) {
	$representantes_grid->RecCnt++;
	if (intval($representantes_grid->RecCnt) >= intval($representantes_grid->StartRec)) {
		$representantes_grid->RowCnt++;
		if ($representantes->CurrentAction == "gridadd" || $representantes->CurrentAction == "gridedit" || $representantes->CurrentAction == "F") {
			$representantes_grid->RowIndex++;
			$objForm->Index = $representantes_grid->RowIndex;
			if ($objForm->HasValue($representantes_grid->FormActionName))
				$representantes_grid->RowAction = strval($objForm->GetValue($representantes_grid->FormActionName));
			elseif ($representantes->CurrentAction == "gridadd")
				$representantes_grid->RowAction = "insert";
			else
				$representantes_grid->RowAction = "";
		}

		// Set up key count
		$representantes_grid->KeyCount = $representantes_grid->RowIndex;

		// Init row class and style
		$representantes->ResetAttrs();
		$representantes->CssClass = "";
		if ($representantes->CurrentAction == "gridadd") {
			if ($representantes->CurrentMode == "copy") {
				$representantes_grid->LoadRowValues($representantes_grid->Recordset); // Load row values
				$representantes_grid->SetRecordKey($representantes_grid->RowOldKey, $representantes_grid->Recordset); // Set old record key
			} else {
				$representantes_grid->LoadDefaultValues(); // Load default values
				$representantes_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$representantes_grid->LoadRowValues($representantes_grid->Recordset); // Load row values
		}
		$representantes->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($representantes->CurrentAction == "gridadd") // Grid add
			$representantes->RowType = EW_ROWTYPE_ADD; // Render add
		if ($representantes->CurrentAction == "gridadd" && $representantes->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$representantes_grid->RestoreCurrentRowFormValues($representantes_grid->RowIndex); // Restore form values
		if ($representantes->CurrentAction == "gridedit") { // Grid edit
			if ($representantes->EventCancelled) {
				$representantes_grid->RestoreCurrentRowFormValues($representantes_grid->RowIndex); // Restore form values
			}
			if ($representantes_grid->RowAction == "insert")
				$representantes->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$representantes->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($representantes->CurrentAction == "gridedit" && ($representantes->RowType == EW_ROWTYPE_EDIT || $representantes->RowType == EW_ROWTYPE_ADD) && $representantes->EventCancelled) // Update failed
			$representantes_grid->RestoreCurrentRowFormValues($representantes_grid->RowIndex); // Restore form values
		if ($representantes->RowType == EW_ROWTYPE_EDIT) // Edit row
			$representantes_grid->EditRowCnt++;
		if ($representantes->CurrentAction == "F") // Confirm row
			$representantes_grid->RestoreCurrentRowFormValues($representantes_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$representantes->RowAttrs = array_merge($representantes->RowAttrs, array('data-rowindex'=>$representantes_grid->RowCnt, 'id'=>'r' . $representantes_grid->RowCnt . '_representantes', 'data-rowtype'=>$representantes->RowType));

		// Render row
		$representantes_grid->RenderRow();

		// Render list options
		$representantes_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($representantes_grid->RowAction <> "delete" && $representantes_grid->RowAction <> "insertdelete" && !($representantes_grid->RowAction == "insert" && $representantes->CurrentAction == "F" && $representantes_grid->EmptyRow())) {
?>
	<tr<?php echo $representantes->RowAttributes() ?>>
<?php

// Render list options (body, left)
$representantes_grid->ListOptions->Render("body", "left", $representantes_grid->RowCnt);
?>
	<?php if ($representantes->id_representante->Visible) { // id_representante ?>
		<td<?php echo $representantes->id_representante->CellAttributes() ?>>
<?php if ($representantes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id_representante" name="o<?php echo $representantes_grid->RowIndex ?>_id_representante" id="o<?php echo $representantes_grid->RowIndex ?>_id_representante" value="<?php echo ew_HtmlEncode($representantes->id_representante->OldValue) ?>">
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_id_representante" class="control-group representantes_id_representante">
<span<?php echo $representantes->id_representante->ViewAttributes() ?>>
<?php echo $representantes->id_representante->EditValue ?></span>
</span>
<input type="hidden" data-field="x_id_representante" name="x<?php echo $representantes_grid->RowIndex ?>_id_representante" id="x<?php echo $representantes_grid->RowIndex ?>_id_representante" value="<?php echo ew_HtmlEncode($representantes->id_representante->CurrentValue) ?>">
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $representantes->id_representante->ViewAttributes() ?>>
<?php echo $representantes->id_representante->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_representante" name="x<?php echo $representantes_grid->RowIndex ?>_id_representante" id="x<?php echo $representantes_grid->RowIndex ?>_id_representante" value="<?php echo ew_HtmlEncode($representantes->id_representante->FormValue) ?>">
<input type="hidden" data-field="x_id_representante" name="o<?php echo $representantes_grid->RowIndex ?>_id_representante" id="o<?php echo $representantes_grid->RowIndex ?>_id_representante" value="<?php echo ew_HtmlEncode($representantes->id_representante->OldValue) ?>">
<?php } ?>
<a id="<?php echo $representantes_grid->PageObjName . "_row_" . $representantes_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($representantes->id_afiliado->Visible) { // id_afiliado ?>
		<td<?php echo $representantes->id_afiliado->CellAttributes() ?>>
<?php if ($representantes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($representantes->id_afiliado->getSessionValue() <> "") { ?>
<span<?php echo $representantes->id_afiliado->ViewAttributes() ?>>
<?php echo $representantes->id_afiliado->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" name="x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($representantes->id_afiliado->CurrentValue) ?>">
<?php } else { ?>
<?php
	$wrkonchange = trim(" " . @$representantes->id_afiliado->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$representantes->id_afiliado->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" style="white-space: nowrap; z-index: <?php echo (9000 - $representantes_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" id="sv_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="<?php echo $representantes->id_afiliado->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($representantes->id_afiliado->PlaceHolder) ?>"<?php echo $representantes->id_afiliado->EditAttributes() ?>>&nbsp;<span id="em_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" style="display: inline; z-index: <?php echo (9000 - $representantes_grid->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" data-field="x_id_afiliado" name="x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" id="x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($representantes->id_afiliado->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
 $sSqlWrk = "SELECT `id_afiliado`, `apell_afiliado` AS `DispFld`, `nomb_afiliado` AS `Disp2Fld` FROM `afiliado`";
 $sWhereWrk = "`apell_afiliado` LIKE '{query_value}%' OR CONCAT(`apell_afiliado`,'" . ew_ValueSeparator(1, $Page->id_afiliado) . "',`nomb_afiliado`) LIKE '{query_value}%'";

 // Call Lookup selecting
 $representantes->Lookup_Selecting($representantes->id_afiliado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" id="q_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $representantes_grid->RowIndex ?>_id_afiliado", frepresentantesgrid, true, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $representantes_grid->RowIndex ?>_id_afiliado") + ar[i] : "";
	return dv;
}
frepresentantesgrid.AutoSuggests["x<?php echo $representantes_grid->RowIndex ?>_id_afiliado"] = oas;
</script>
<?php } ?>
<input type="hidden" data-field="x_id_afiliado" name="o<?php echo $representantes_grid->RowIndex ?>_id_afiliado" id="o<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($representantes->id_afiliado->OldValue) ?>">
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($representantes->id_afiliado->getSessionValue() <> "") { ?>
<span<?php echo $representantes->id_afiliado->ViewAttributes() ?>>
<?php echo $representantes->id_afiliado->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" name="x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($representantes->id_afiliado->CurrentValue) ?>">
<?php } else { ?>
<?php
	$wrkonchange = trim(" " . @$representantes->id_afiliado->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$representantes->id_afiliado->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" style="white-space: nowrap; z-index: <?php echo (9000 - $representantes_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" id="sv_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="<?php echo $representantes->id_afiliado->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($representantes->id_afiliado->PlaceHolder) ?>"<?php echo $representantes->id_afiliado->EditAttributes() ?>>&nbsp;<span id="em_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" style="display: inline; z-index: <?php echo (9000 - $representantes_grid->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" data-field="x_id_afiliado" name="x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" id="x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($representantes->id_afiliado->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
 $sSqlWrk = "SELECT `id_afiliado`, `apell_afiliado` AS `DispFld`, `nomb_afiliado` AS `Disp2Fld` FROM `afiliado`";
 $sWhereWrk = "`apell_afiliado` LIKE '{query_value}%' OR CONCAT(`apell_afiliado`,'" . ew_ValueSeparator(1, $Page->id_afiliado) . "',`nomb_afiliado`) LIKE '{query_value}%'";

 // Call Lookup selecting
 $representantes->Lookup_Selecting($representantes->id_afiliado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" id="q_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $representantes_grid->RowIndex ?>_id_afiliado", frepresentantesgrid, true, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $representantes_grid->RowIndex ?>_id_afiliado") + ar[i] : "";
	return dv;
}
frepresentantesgrid.AutoSuggests["x<?php echo $representantes_grid->RowIndex ?>_id_afiliado"] = oas;
</script>
<?php } ?>
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $representantes->id_afiliado->ViewAttributes() ?>>
<?php echo $representantes->id_afiliado->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_afiliado" name="x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" id="x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($representantes->id_afiliado->FormValue) ?>">
<input type="hidden" data-field="x_id_afiliado" name="o<?php echo $representantes_grid->RowIndex ?>_id_afiliado" id="o<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($representantes->id_afiliado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($representantes->dociden_repres->Visible) { // dociden_repres ?>
		<td<?php echo $representantes->dociden_repres->CellAttributes() ?>>
<?php if ($representantes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_dociden_repres" class="control-group representantes_dociden_repres">
<input type="text" data-field="x_dociden_repres" name="x<?php echo $representantes_grid->RowIndex ?>_dociden_repres" id="x<?php echo $representantes_grid->RowIndex ?>_dociden_repres" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($representantes->dociden_repres->PlaceHolder) ?>" value="<?php echo $representantes->dociden_repres->EditValue ?>"<?php echo $representantes->dociden_repres->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_dociden_repres" name="o<?php echo $representantes_grid->RowIndex ?>_dociden_repres" id="o<?php echo $representantes_grid->RowIndex ?>_dociden_repres" value="<?php echo ew_HtmlEncode($representantes->dociden_repres->OldValue) ?>">
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_dociden_repres" class="control-group representantes_dociden_repres">
<input type="text" data-field="x_dociden_repres" name="x<?php echo $representantes_grid->RowIndex ?>_dociden_repres" id="x<?php echo $representantes_grid->RowIndex ?>_dociden_repres" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($representantes->dociden_repres->PlaceHolder) ?>" value="<?php echo $representantes->dociden_repres->EditValue ?>"<?php echo $representantes->dociden_repres->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $representantes->dociden_repres->ViewAttributes() ?>>
<?php echo $representantes->dociden_repres->ListViewValue() ?></span>
<input type="hidden" data-field="x_dociden_repres" name="x<?php echo $representantes_grid->RowIndex ?>_dociden_repres" id="x<?php echo $representantes_grid->RowIndex ?>_dociden_repres" value="<?php echo ew_HtmlEncode($representantes->dociden_repres->FormValue) ?>">
<input type="hidden" data-field="x_dociden_repres" name="o<?php echo $representantes_grid->RowIndex ?>_dociden_repres" id="o<?php echo $representantes_grid->RowIndex ?>_dociden_repres" value="<?php echo ew_HtmlEncode($representantes->dociden_repres->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($representantes->apell_repres->Visible) { // apell_repres ?>
		<td<?php echo $representantes->apell_repres->CellAttributes() ?>>
<?php if ($representantes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_apell_repres" class="control-group representantes_apell_repres">
<input type="text" data-field="x_apell_repres" name="x<?php echo $representantes_grid->RowIndex ?>_apell_repres" id="x<?php echo $representantes_grid->RowIndex ?>_apell_repres" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($representantes->apell_repres->PlaceHolder) ?>" value="<?php echo $representantes->apell_repres->EditValue ?>"<?php echo $representantes->apell_repres->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_apell_repres" name="o<?php echo $representantes_grid->RowIndex ?>_apell_repres" id="o<?php echo $representantes_grid->RowIndex ?>_apell_repres" value="<?php echo ew_HtmlEncode($representantes->apell_repres->OldValue) ?>">
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_apell_repres" class="control-group representantes_apell_repres">
<input type="text" data-field="x_apell_repres" name="x<?php echo $representantes_grid->RowIndex ?>_apell_repres" id="x<?php echo $representantes_grid->RowIndex ?>_apell_repres" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($representantes->apell_repres->PlaceHolder) ?>" value="<?php echo $representantes->apell_repres->EditValue ?>"<?php echo $representantes->apell_repres->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $representantes->apell_repres->ViewAttributes() ?>>
<?php echo $representantes->apell_repres->ListViewValue() ?></span>
<input type="hidden" data-field="x_apell_repres" name="x<?php echo $representantes_grid->RowIndex ?>_apell_repres" id="x<?php echo $representantes_grid->RowIndex ?>_apell_repres" value="<?php echo ew_HtmlEncode($representantes->apell_repres->FormValue) ?>">
<input type="hidden" data-field="x_apell_repres" name="o<?php echo $representantes_grid->RowIndex ?>_apell_repres" id="o<?php echo $representantes_grid->RowIndex ?>_apell_repres" value="<?php echo ew_HtmlEncode($representantes->apell_repres->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($representantes->nomb_repres->Visible) { // nomb_repres ?>
		<td<?php echo $representantes->nomb_repres->CellAttributes() ?>>
<?php if ($representantes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_nomb_repres" class="control-group representantes_nomb_repres">
<input type="text" data-field="x_nomb_repres" name="x<?php echo $representantes_grid->RowIndex ?>_nomb_repres" id="x<?php echo $representantes_grid->RowIndex ?>_nomb_repres" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($representantes->nomb_repres->PlaceHolder) ?>" value="<?php echo $representantes->nomb_repres->EditValue ?>"<?php echo $representantes->nomb_repres->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nomb_repres" name="o<?php echo $representantes_grid->RowIndex ?>_nomb_repres" id="o<?php echo $representantes_grid->RowIndex ?>_nomb_repres" value="<?php echo ew_HtmlEncode($representantes->nomb_repres->OldValue) ?>">
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_nomb_repres" class="control-group representantes_nomb_repres">
<input type="text" data-field="x_nomb_repres" name="x<?php echo $representantes_grid->RowIndex ?>_nomb_repres" id="x<?php echo $representantes_grid->RowIndex ?>_nomb_repres" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($representantes->nomb_repres->PlaceHolder) ?>" value="<?php echo $representantes->nomb_repres->EditValue ?>"<?php echo $representantes->nomb_repres->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $representantes->nomb_repres->ViewAttributes() ?>>
<?php echo $representantes->nomb_repres->ListViewValue() ?></span>
<input type="hidden" data-field="x_nomb_repres" name="x<?php echo $representantes_grid->RowIndex ?>_nomb_repres" id="x<?php echo $representantes_grid->RowIndex ?>_nomb_repres" value="<?php echo ew_HtmlEncode($representantes->nomb_repres->FormValue) ?>">
<input type="hidden" data-field="x_nomb_repres" name="o<?php echo $representantes_grid->RowIndex ?>_nomb_repres" id="o<?php echo $representantes_grid->RowIndex ?>_nomb_repres" value="<?php echo ew_HtmlEncode($representantes->nomb_repres->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($representantes->telf_resi_repres->Visible) { // telf_resi_repres ?>
		<td<?php echo $representantes->telf_resi_repres->CellAttributes() ?>>
<?php if ($representantes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_telf_resi_repres" class="control-group representantes_telf_resi_repres">
<input type="text" data-field="x_telf_resi_repres" name="x<?php echo $representantes_grid->RowIndex ?>_telf_resi_repres" id="x<?php echo $representantes_grid->RowIndex ?>_telf_resi_repres" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($representantes->telf_resi_repres->PlaceHolder) ?>" value="<?php echo $representantes->telf_resi_repres->EditValue ?>"<?php echo $representantes->telf_resi_repres->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_telf_resi_repres" name="o<?php echo $representantes_grid->RowIndex ?>_telf_resi_repres" id="o<?php echo $representantes_grid->RowIndex ?>_telf_resi_repres" value="<?php echo ew_HtmlEncode($representantes->telf_resi_repres->OldValue) ?>">
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_telf_resi_repres" class="control-group representantes_telf_resi_repres">
<input type="text" data-field="x_telf_resi_repres" name="x<?php echo $representantes_grid->RowIndex ?>_telf_resi_repres" id="x<?php echo $representantes_grid->RowIndex ?>_telf_resi_repres" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($representantes->telf_resi_repres->PlaceHolder) ?>" value="<?php echo $representantes->telf_resi_repres->EditValue ?>"<?php echo $representantes->telf_resi_repres->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $representantes->telf_resi_repres->ViewAttributes() ?>>
<?php echo $representantes->telf_resi_repres->ListViewValue() ?></span>
<input type="hidden" data-field="x_telf_resi_repres" name="x<?php echo $representantes_grid->RowIndex ?>_telf_resi_repres" id="x<?php echo $representantes_grid->RowIndex ?>_telf_resi_repres" value="<?php echo ew_HtmlEncode($representantes->telf_resi_repres->FormValue) ?>">
<input type="hidden" data-field="x_telf_resi_repres" name="o<?php echo $representantes_grid->RowIndex ?>_telf_resi_repres" id="o<?php echo $representantes_grid->RowIndex ?>_telf_resi_repres" value="<?php echo ew_HtmlEncode($representantes->telf_resi_repres->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($representantes->email_repres->Visible) { // email_repres ?>
		<td<?php echo $representantes->email_repres->CellAttributes() ?>>
<?php if ($representantes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_email_repres" class="control-group representantes_email_repres">
<input type="text" data-field="x_email_repres" name="x<?php echo $representantes_grid->RowIndex ?>_email_repres" id="x<?php echo $representantes_grid->RowIndex ?>_email_repres" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($representantes->email_repres->PlaceHolder) ?>" value="<?php echo $representantes->email_repres->EditValue ?>"<?php echo $representantes->email_repres->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_email_repres" name="o<?php echo $representantes_grid->RowIndex ?>_email_repres" id="o<?php echo $representantes_grid->RowIndex ?>_email_repres" value="<?php echo ew_HtmlEncode($representantes->email_repres->OldValue) ?>">
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_email_repres" class="control-group representantes_email_repres">
<input type="text" data-field="x_email_repres" name="x<?php echo $representantes_grid->RowIndex ?>_email_repres" id="x<?php echo $representantes_grid->RowIndex ?>_email_repres" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($representantes->email_repres->PlaceHolder) ?>" value="<?php echo $representantes->email_repres->EditValue ?>"<?php echo $representantes->email_repres->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $representantes->email_repres->ViewAttributes() ?>>
<?php echo $representantes->email_repres->ListViewValue() ?></span>
<input type="hidden" data-field="x_email_repres" name="x<?php echo $representantes_grid->RowIndex ?>_email_repres" id="x<?php echo $representantes_grid->RowIndex ?>_email_repres" value="<?php echo ew_HtmlEncode($representantes->email_repres->FormValue) ?>">
<input type="hidden" data-field="x_email_repres" name="o<?php echo $representantes_grid->RowIndex ?>_email_repres" id="o<?php echo $representantes_grid->RowIndex ?>_email_repres" value="<?php echo ew_HtmlEncode($representantes->email_repres->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($representantes->par_repres->Visible) { // par_repres ?>
		<td<?php echo $representantes->par_repres->CellAttributes() ?>>
<?php if ($representantes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_par_repres" class="control-group representantes_par_repres">
<div id="tp_x<?php echo $representantes_grid->RowIndex ?>_par_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $representantes_grid->RowIndex ?>_par_repres" id="x<?php echo $representantes_grid->RowIndex ?>_par_repres" value="{value}"<?php echo $representantes->par_repres->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $representantes_grid->RowIndex ?>_par_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->par_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->par_repres->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_par_repres" name="x<?php echo $representantes_grid->RowIndex ?>_par_repres" id="x<?php echo $representantes_grid->RowIndex ?>_par_repres_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $representantes->par_repres->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $representantes->par_repres->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_par_repres" name="o<?php echo $representantes_grid->RowIndex ?>_par_repres" id="o<?php echo $representantes_grid->RowIndex ?>_par_repres" value="<?php echo ew_HtmlEncode($representantes->par_repres->OldValue) ?>">
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_par_repres" class="control-group representantes_par_repres">
<div id="tp_x<?php echo $representantes_grid->RowIndex ?>_par_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $representantes_grid->RowIndex ?>_par_repres" id="x<?php echo $representantes_grid->RowIndex ?>_par_repres" value="{value}"<?php echo $representantes->par_repres->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $representantes_grid->RowIndex ?>_par_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->par_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->par_repres->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_par_repres" name="x<?php echo $representantes_grid->RowIndex ?>_par_repres" id="x<?php echo $representantes_grid->RowIndex ?>_par_repres_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $representantes->par_repres->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $representantes->par_repres->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $representantes->par_repres->ViewAttributes() ?>>
<?php echo $representantes->par_repres->ListViewValue() ?></span>
<input type="hidden" data-field="x_par_repres" name="x<?php echo $representantes_grid->RowIndex ?>_par_repres" id="x<?php echo $representantes_grid->RowIndex ?>_par_repres" value="<?php echo ew_HtmlEncode($representantes->par_repres->FormValue) ?>">
<input type="hidden" data-field="x_par_repres" name="o<?php echo $representantes_grid->RowIndex ?>_par_repres" id="o<?php echo $representantes_grid->RowIndex ?>_par_repres" value="<?php echo ew_HtmlEncode($representantes->par_repres->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($representantes->cel_repres->Visible) { // cel_repres ?>
		<td<?php echo $representantes->cel_repres->CellAttributes() ?>>
<?php if ($representantes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_cel_repres" class="control-group representantes_cel_repres">
<input type="text" data-field="x_cel_repres" name="x<?php echo $representantes_grid->RowIndex ?>_cel_repres" id="x<?php echo $representantes_grid->RowIndex ?>_cel_repres" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($representantes->cel_repres->PlaceHolder) ?>" value="<?php echo $representantes->cel_repres->EditValue ?>"<?php echo $representantes->cel_repres->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_cel_repres" name="o<?php echo $representantes_grid->RowIndex ?>_cel_repres" id="o<?php echo $representantes_grid->RowIndex ?>_cel_repres" value="<?php echo ew_HtmlEncode($representantes->cel_repres->OldValue) ?>">
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_cel_repres" class="control-group representantes_cel_repres">
<input type="text" data-field="x_cel_repres" name="x<?php echo $representantes_grid->RowIndex ?>_cel_repres" id="x<?php echo $representantes_grid->RowIndex ?>_cel_repres" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($representantes->cel_repres->PlaceHolder) ?>" value="<?php echo $representantes->cel_repres->EditValue ?>"<?php echo $representantes->cel_repres->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $representantes->cel_repres->ViewAttributes() ?>>
<?php echo $representantes->cel_repres->ListViewValue() ?></span>
<input type="hidden" data-field="x_cel_repres" name="x<?php echo $representantes_grid->RowIndex ?>_cel_repres" id="x<?php echo $representantes_grid->RowIndex ?>_cel_repres" value="<?php echo ew_HtmlEncode($representantes->cel_repres->FormValue) ?>">
<input type="hidden" data-field="x_cel_repres" name="o<?php echo $representantes_grid->RowIndex ?>_cel_repres" id="o<?php echo $representantes_grid->RowIndex ?>_cel_repres" value="<?php echo ew_HtmlEncode($representantes->cel_repres->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($representantes->contact_e_repres->Visible) { // contact_e_repres ?>
		<td<?php echo $representantes->contact_e_repres->CellAttributes() ?>>
<?php if ($representantes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_contact_e_repres" class="control-group representantes_contact_e_repres">
<div id="tp_x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" id="x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" value="{value}"<?php echo $representantes->contact_e_repres->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->contact_e_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->contact_e_repres->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_contact_e_repres" name="x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" id="x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $representantes->contact_e_repres->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $representantes->contact_e_repres->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_contact_e_repres" name="o<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" id="o<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" value="<?php echo ew_HtmlEncode($representantes->contact_e_repres->OldValue) ?>">
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_contact_e_repres" class="control-group representantes_contact_e_repres">
<div id="tp_x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" id="x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" value="{value}"<?php echo $representantes->contact_e_repres->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->contact_e_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->contact_e_repres->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_contact_e_repres" name="x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" id="x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $representantes->contact_e_repres->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $representantes->contact_e_repres->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $representantes->contact_e_repres->ViewAttributes() ?>>
<?php echo $representantes->contact_e_repres->ListViewValue() ?></span>
<input type="hidden" data-field="x_contact_e_repres" name="x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" id="x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" value="<?php echo ew_HtmlEncode($representantes->contact_e_repres->FormValue) ?>">
<input type="hidden" data-field="x_contact_e_repres" name="o<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" id="o<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" value="<?php echo ew_HtmlEncode($representantes->contact_e_repres->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($representantes->contact_d_repres->Visible) { // contact_d_repres ?>
		<td<?php echo $representantes->contact_d_repres->CellAttributes() ?>>
<?php if ($representantes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_contact_d_repres" class="control-group representantes_contact_d_repres">
<div id="tp_x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" id="x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" value="{value}"<?php echo $representantes->contact_d_repres->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->contact_d_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->contact_d_repres->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_contact_d_repres" name="x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" id="x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $representantes->contact_d_repres->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $representantes->contact_d_repres->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_contact_d_repres" name="o<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" id="o<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" value="<?php echo ew_HtmlEncode($representantes->contact_d_repres->OldValue) ?>">
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_contact_d_repres" class="control-group representantes_contact_d_repres">
<div id="tp_x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" id="x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" value="{value}"<?php echo $representantes->contact_d_repres->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->contact_d_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->contact_d_repres->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_contact_d_repres" name="x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" id="x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $representantes->contact_d_repres->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $representantes->contact_d_repres->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $representantes->contact_d_repres->ViewAttributes() ?>>
<?php echo $representantes->contact_d_repres->ListViewValue() ?></span>
<input type="hidden" data-field="x_contact_d_repres" name="x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" id="x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" value="<?php echo ew_HtmlEncode($representantes->contact_d_repres->FormValue) ?>">
<input type="hidden" data-field="x_contact_d_repres" name="o<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" id="o<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" value="<?php echo ew_HtmlEncode($representantes->contact_d_repres->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($representantes->st_repres->Visible) { // st_repres ?>
		<td<?php echo $representantes->st_repres->CellAttributes() ?>>
<?php if ($representantes->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_st_repres" class="control-group representantes_st_repres">
<div id="tp_x<?php echo $representantes_grid->RowIndex ?>_st_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $representantes_grid->RowIndex ?>_st_repres" id="x<?php echo $representantes_grid->RowIndex ?>_st_repres" value="{value}"<?php echo $representantes->st_repres->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $representantes_grid->RowIndex ?>_st_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->st_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->st_repres->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_st_repres" name="x<?php echo $representantes_grid->RowIndex ?>_st_repres" id="x<?php echo $representantes_grid->RowIndex ?>_st_repres_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $representantes->st_repres->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $representantes->st_repres->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_st_repres" name="o<?php echo $representantes_grid->RowIndex ?>_st_repres" id="o<?php echo $representantes_grid->RowIndex ?>_st_repres" value="<?php echo ew_HtmlEncode($representantes->st_repres->OldValue) ?>">
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $representantes_grid->RowCnt ?>_representantes_st_repres" class="control-group representantes_st_repres">
<div id="tp_x<?php echo $representantes_grid->RowIndex ?>_st_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $representantes_grid->RowIndex ?>_st_repres" id="x<?php echo $representantes_grid->RowIndex ?>_st_repres" value="{value}"<?php echo $representantes->st_repres->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $representantes_grid->RowIndex ?>_st_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->st_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->st_repres->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_st_repres" name="x<?php echo $representantes_grid->RowIndex ?>_st_repres" id="x<?php echo $representantes_grid->RowIndex ?>_st_repres_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $representantes->st_repres->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $representantes->st_repres->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($representantes->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $representantes->st_repres->ViewAttributes() ?>>
<?php echo $representantes->st_repres->ListViewValue() ?></span>
<input type="hidden" data-field="x_st_repres" name="x<?php echo $representantes_grid->RowIndex ?>_st_repres" id="x<?php echo $representantes_grid->RowIndex ?>_st_repres" value="<?php echo ew_HtmlEncode($representantes->st_repres->FormValue) ?>">
<input type="hidden" data-field="x_st_repres" name="o<?php echo $representantes_grid->RowIndex ?>_st_repres" id="o<?php echo $representantes_grid->RowIndex ?>_st_repres" value="<?php echo ew_HtmlEncode($representantes->st_repres->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$representantes_grid->ListOptions->Render("body", "right", $representantes_grid->RowCnt);
?>
	</tr>
<?php if ($representantes->RowType == EW_ROWTYPE_ADD || $representantes->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
frepresentantesgrid.UpdateOpts(<?php echo $representantes_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($representantes->CurrentAction <> "gridadd" || $representantes->CurrentMode == "copy")
		if (!$representantes_grid->Recordset->EOF) $representantes_grid->Recordset->MoveNext();
}
?>
<?php
	if ($representantes->CurrentMode == "add" || $representantes->CurrentMode == "copy" || $representantes->CurrentMode == "edit") {
		$representantes_grid->RowIndex = '$rowindex$';
		$representantes_grid->LoadDefaultValues();

		// Set row properties
		$representantes->ResetAttrs();
		$representantes->RowAttrs = array_merge($representantes->RowAttrs, array('data-rowindex'=>$representantes_grid->RowIndex, 'id'=>'r0_representantes', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($representantes->RowAttrs["class"], "ewTemplate");
		$representantes->RowType = EW_ROWTYPE_ADD;

		// Render row
		$representantes_grid->RenderRow();

		// Render list options
		$representantes_grid->RenderListOptions();
		$representantes_grid->StartRowCnt = 0;
?>
	<tr<?php echo $representantes->RowAttributes() ?>>
<?php

// Render list options (body, left)
$representantes_grid->ListOptions->Render("body", "left", $representantes_grid->RowIndex);
?>
	<?php if ($representantes->id_representante->Visible) { // id_representante ?>
		<td>
<?php if ($representantes->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_representantes_id_representante" class="control-group representantes_id_representante">
<span<?php echo $representantes->id_representante->ViewAttributes() ?>>
<?php echo $representantes->id_representante->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_id_representante" name="x<?php echo $representantes_grid->RowIndex ?>_id_representante" id="x<?php echo $representantes_grid->RowIndex ?>_id_representante" value="<?php echo ew_HtmlEncode($representantes->id_representante->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_representante" name="o<?php echo $representantes_grid->RowIndex ?>_id_representante" id="o<?php echo $representantes_grid->RowIndex ?>_id_representante" value="<?php echo ew_HtmlEncode($representantes->id_representante->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($representantes->id_afiliado->Visible) { // id_afiliado ?>
		<td>
<?php if ($representantes->CurrentAction <> "F") { ?>
<?php if ($representantes->id_afiliado->getSessionValue() <> "") { ?>
<span<?php echo $representantes->id_afiliado->ViewAttributes() ?>>
<?php echo $representantes->id_afiliado->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" name="x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($representantes->id_afiliado->CurrentValue) ?>">
<?php } else { ?>
<?php
	$wrkonchange = trim(" " . @$representantes->id_afiliado->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$representantes->id_afiliado->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" style="white-space: nowrap; z-index: <?php echo (9000 - $representantes_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" id="sv_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="<?php echo $representantes->id_afiliado->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($representantes->id_afiliado->PlaceHolder) ?>"<?php echo $representantes->id_afiliado->EditAttributes() ?>>&nbsp;<span id="em_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" style="display: inline; z-index: <?php echo (9000 - $representantes_grid->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" data-field="x_id_afiliado" name="x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" id="x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($representantes->id_afiliado->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
 $sSqlWrk = "SELECT `id_afiliado`, `apell_afiliado` AS `DispFld`, `nomb_afiliado` AS `Disp2Fld` FROM `afiliado`";
 $sWhereWrk = "`apell_afiliado` LIKE '{query_value}%' OR CONCAT(`apell_afiliado`,'" . ew_ValueSeparator(1, $Page->id_afiliado) . "',`nomb_afiliado`) LIKE '{query_value}%'";

 // Call Lookup selecting
 $representantes->Lookup_Selecting($representantes->id_afiliado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" id="q_x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $representantes_grid->RowIndex ?>_id_afiliado", frepresentantesgrid, true, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $representantes_grid->RowIndex ?>_id_afiliado") + ar[i] : "";
	return dv;
}
frepresentantesgrid.AutoSuggests["x<?php echo $representantes_grid->RowIndex ?>_id_afiliado"] = oas;
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $representantes->id_afiliado->ViewAttributes() ?>>
<?php echo $representantes->id_afiliado->ViewValue ?></span>
<input type="hidden" data-field="x_id_afiliado" name="x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" id="x<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($representantes->id_afiliado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_afiliado" name="o<?php echo $representantes_grid->RowIndex ?>_id_afiliado" id="o<?php echo $representantes_grid->RowIndex ?>_id_afiliado" value="<?php echo ew_HtmlEncode($representantes->id_afiliado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($representantes->dociden_repres->Visible) { // dociden_repres ?>
		<td>
<?php if ($representantes->CurrentAction <> "F") { ?>
<span id="el$rowindex$_representantes_dociden_repres" class="control-group representantes_dociden_repres">
<input type="text" data-field="x_dociden_repres" name="x<?php echo $representantes_grid->RowIndex ?>_dociden_repres" id="x<?php echo $representantes_grid->RowIndex ?>_dociden_repres" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($representantes->dociden_repres->PlaceHolder) ?>" value="<?php echo $representantes->dociden_repres->EditValue ?>"<?php echo $representantes->dociden_repres->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_representantes_dociden_repres" class="control-group representantes_dociden_repres">
<span<?php echo $representantes->dociden_repres->ViewAttributes() ?>>
<?php echo $representantes->dociden_repres->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_dociden_repres" name="x<?php echo $representantes_grid->RowIndex ?>_dociden_repres" id="x<?php echo $representantes_grid->RowIndex ?>_dociden_repres" value="<?php echo ew_HtmlEncode($representantes->dociden_repres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_dociden_repres" name="o<?php echo $representantes_grid->RowIndex ?>_dociden_repres" id="o<?php echo $representantes_grid->RowIndex ?>_dociden_repres" value="<?php echo ew_HtmlEncode($representantes->dociden_repres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($representantes->apell_repres->Visible) { // apell_repres ?>
		<td>
<?php if ($representantes->CurrentAction <> "F") { ?>
<span id="el$rowindex$_representantes_apell_repres" class="control-group representantes_apell_repres">
<input type="text" data-field="x_apell_repres" name="x<?php echo $representantes_grid->RowIndex ?>_apell_repres" id="x<?php echo $representantes_grid->RowIndex ?>_apell_repres" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($representantes->apell_repres->PlaceHolder) ?>" value="<?php echo $representantes->apell_repres->EditValue ?>"<?php echo $representantes->apell_repres->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_representantes_apell_repres" class="control-group representantes_apell_repres">
<span<?php echo $representantes->apell_repres->ViewAttributes() ?>>
<?php echo $representantes->apell_repres->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_apell_repres" name="x<?php echo $representantes_grid->RowIndex ?>_apell_repres" id="x<?php echo $representantes_grid->RowIndex ?>_apell_repres" value="<?php echo ew_HtmlEncode($representantes->apell_repres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_apell_repres" name="o<?php echo $representantes_grid->RowIndex ?>_apell_repres" id="o<?php echo $representantes_grid->RowIndex ?>_apell_repres" value="<?php echo ew_HtmlEncode($representantes->apell_repres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($representantes->nomb_repres->Visible) { // nomb_repres ?>
		<td>
<?php if ($representantes->CurrentAction <> "F") { ?>
<span id="el$rowindex$_representantes_nomb_repres" class="control-group representantes_nomb_repres">
<input type="text" data-field="x_nomb_repres" name="x<?php echo $representantes_grid->RowIndex ?>_nomb_repres" id="x<?php echo $representantes_grid->RowIndex ?>_nomb_repres" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($representantes->nomb_repres->PlaceHolder) ?>" value="<?php echo $representantes->nomb_repres->EditValue ?>"<?php echo $representantes->nomb_repres->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_representantes_nomb_repres" class="control-group representantes_nomb_repres">
<span<?php echo $representantes->nomb_repres->ViewAttributes() ?>>
<?php echo $representantes->nomb_repres->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_nomb_repres" name="x<?php echo $representantes_grid->RowIndex ?>_nomb_repres" id="x<?php echo $representantes_grid->RowIndex ?>_nomb_repres" value="<?php echo ew_HtmlEncode($representantes->nomb_repres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nomb_repres" name="o<?php echo $representantes_grid->RowIndex ?>_nomb_repres" id="o<?php echo $representantes_grid->RowIndex ?>_nomb_repres" value="<?php echo ew_HtmlEncode($representantes->nomb_repres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($representantes->telf_resi_repres->Visible) { // telf_resi_repres ?>
		<td>
<?php if ($representantes->CurrentAction <> "F") { ?>
<span id="el$rowindex$_representantes_telf_resi_repres" class="control-group representantes_telf_resi_repres">
<input type="text" data-field="x_telf_resi_repres" name="x<?php echo $representantes_grid->RowIndex ?>_telf_resi_repres" id="x<?php echo $representantes_grid->RowIndex ?>_telf_resi_repres" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($representantes->telf_resi_repres->PlaceHolder) ?>" value="<?php echo $representantes->telf_resi_repres->EditValue ?>"<?php echo $representantes->telf_resi_repres->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_representantes_telf_resi_repres" class="control-group representantes_telf_resi_repres">
<span<?php echo $representantes->telf_resi_repres->ViewAttributes() ?>>
<?php echo $representantes->telf_resi_repres->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_telf_resi_repres" name="x<?php echo $representantes_grid->RowIndex ?>_telf_resi_repres" id="x<?php echo $representantes_grid->RowIndex ?>_telf_resi_repres" value="<?php echo ew_HtmlEncode($representantes->telf_resi_repres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_telf_resi_repres" name="o<?php echo $representantes_grid->RowIndex ?>_telf_resi_repres" id="o<?php echo $representantes_grid->RowIndex ?>_telf_resi_repres" value="<?php echo ew_HtmlEncode($representantes->telf_resi_repres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($representantes->email_repres->Visible) { // email_repres ?>
		<td>
<?php if ($representantes->CurrentAction <> "F") { ?>
<span id="el$rowindex$_representantes_email_repres" class="control-group representantes_email_repres">
<input type="text" data-field="x_email_repres" name="x<?php echo $representantes_grid->RowIndex ?>_email_repres" id="x<?php echo $representantes_grid->RowIndex ?>_email_repres" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($representantes->email_repres->PlaceHolder) ?>" value="<?php echo $representantes->email_repres->EditValue ?>"<?php echo $representantes->email_repres->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_representantes_email_repres" class="control-group representantes_email_repres">
<span<?php echo $representantes->email_repres->ViewAttributes() ?>>
<?php echo $representantes->email_repres->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_email_repres" name="x<?php echo $representantes_grid->RowIndex ?>_email_repres" id="x<?php echo $representantes_grid->RowIndex ?>_email_repres" value="<?php echo ew_HtmlEncode($representantes->email_repres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_email_repres" name="o<?php echo $representantes_grid->RowIndex ?>_email_repres" id="o<?php echo $representantes_grid->RowIndex ?>_email_repres" value="<?php echo ew_HtmlEncode($representantes->email_repres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($representantes->par_repres->Visible) { // par_repres ?>
		<td>
<?php if ($representantes->CurrentAction <> "F") { ?>
<span id="el$rowindex$_representantes_par_repres" class="control-group representantes_par_repres">
<div id="tp_x<?php echo $representantes_grid->RowIndex ?>_par_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $representantes_grid->RowIndex ?>_par_repres" id="x<?php echo $representantes_grid->RowIndex ?>_par_repres" value="{value}"<?php echo $representantes->par_repres->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $representantes_grid->RowIndex ?>_par_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->par_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->par_repres->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_par_repres" name="x<?php echo $representantes_grid->RowIndex ?>_par_repres" id="x<?php echo $representantes_grid->RowIndex ?>_par_repres_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $representantes->par_repres->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $representantes->par_repres->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_representantes_par_repres" class="control-group representantes_par_repres">
<span<?php echo $representantes->par_repres->ViewAttributes() ?>>
<?php echo $representantes->par_repres->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_par_repres" name="x<?php echo $representantes_grid->RowIndex ?>_par_repres" id="x<?php echo $representantes_grid->RowIndex ?>_par_repres" value="<?php echo ew_HtmlEncode($representantes->par_repres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_par_repres" name="o<?php echo $representantes_grid->RowIndex ?>_par_repres" id="o<?php echo $representantes_grid->RowIndex ?>_par_repres" value="<?php echo ew_HtmlEncode($representantes->par_repres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($representantes->cel_repres->Visible) { // cel_repres ?>
		<td>
<?php if ($representantes->CurrentAction <> "F") { ?>
<span id="el$rowindex$_representantes_cel_repres" class="control-group representantes_cel_repres">
<input type="text" data-field="x_cel_repres" name="x<?php echo $representantes_grid->RowIndex ?>_cel_repres" id="x<?php echo $representantes_grid->RowIndex ?>_cel_repres" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($representantes->cel_repres->PlaceHolder) ?>" value="<?php echo $representantes->cel_repres->EditValue ?>"<?php echo $representantes->cel_repres->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_representantes_cel_repres" class="control-group representantes_cel_repres">
<span<?php echo $representantes->cel_repres->ViewAttributes() ?>>
<?php echo $representantes->cel_repres->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_cel_repres" name="x<?php echo $representantes_grid->RowIndex ?>_cel_repres" id="x<?php echo $representantes_grid->RowIndex ?>_cel_repres" value="<?php echo ew_HtmlEncode($representantes->cel_repres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_cel_repres" name="o<?php echo $representantes_grid->RowIndex ?>_cel_repres" id="o<?php echo $representantes_grid->RowIndex ?>_cel_repres" value="<?php echo ew_HtmlEncode($representantes->cel_repres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($representantes->contact_e_repres->Visible) { // contact_e_repres ?>
		<td>
<?php if ($representantes->CurrentAction <> "F") { ?>
<span id="el$rowindex$_representantes_contact_e_repres" class="control-group representantes_contact_e_repres">
<div id="tp_x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" id="x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" value="{value}"<?php echo $representantes->contact_e_repres->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->contact_e_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->contact_e_repres->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_contact_e_repres" name="x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" id="x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $representantes->contact_e_repres->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $representantes->contact_e_repres->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_representantes_contact_e_repres" class="control-group representantes_contact_e_repres">
<span<?php echo $representantes->contact_e_repres->ViewAttributes() ?>>
<?php echo $representantes->contact_e_repres->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_contact_e_repres" name="x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" id="x<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" value="<?php echo ew_HtmlEncode($representantes->contact_e_repres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_contact_e_repres" name="o<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" id="o<?php echo $representantes_grid->RowIndex ?>_contact_e_repres" value="<?php echo ew_HtmlEncode($representantes->contact_e_repres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($representantes->contact_d_repres->Visible) { // contact_d_repres ?>
		<td>
<?php if ($representantes->CurrentAction <> "F") { ?>
<span id="el$rowindex$_representantes_contact_d_repres" class="control-group representantes_contact_d_repres">
<div id="tp_x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" id="x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" value="{value}"<?php echo $representantes->contact_d_repres->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->contact_d_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->contact_d_repres->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_contact_d_repres" name="x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" id="x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $representantes->contact_d_repres->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $representantes->contact_d_repres->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_representantes_contact_d_repres" class="control-group representantes_contact_d_repres">
<span<?php echo $representantes->contact_d_repres->ViewAttributes() ?>>
<?php echo $representantes->contact_d_repres->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_contact_d_repres" name="x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" id="x<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" value="<?php echo ew_HtmlEncode($representantes->contact_d_repres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_contact_d_repres" name="o<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" id="o<?php echo $representantes_grid->RowIndex ?>_contact_d_repres" value="<?php echo ew_HtmlEncode($representantes->contact_d_repres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($representantes->st_repres->Visible) { // st_repres ?>
		<td>
<?php if ($representantes->CurrentAction <> "F") { ?>
<span id="el$rowindex$_representantes_st_repres" class="control-group representantes_st_repres">
<div id="tp_x<?php echo $representantes_grid->RowIndex ?>_st_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $representantes_grid->RowIndex ?>_st_repres" id="x<?php echo $representantes_grid->RowIndex ?>_st_repres" value="{value}"<?php echo $representantes->st_repres->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $representantes_grid->RowIndex ?>_st_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->st_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->st_repres->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_st_repres" name="x<?php echo $representantes_grid->RowIndex ?>_st_repres" id="x<?php echo $representantes_grid->RowIndex ?>_st_repres_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $representantes->st_repres->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $representantes->st_repres->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_representantes_st_repres" class="control-group representantes_st_repres">
<span<?php echo $representantes->st_repres->ViewAttributes() ?>>
<?php echo $representantes->st_repres->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_st_repres" name="x<?php echo $representantes_grid->RowIndex ?>_st_repres" id="x<?php echo $representantes_grid->RowIndex ?>_st_repres" value="<?php echo ew_HtmlEncode($representantes->st_repres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_st_repres" name="o<?php echo $representantes_grid->RowIndex ?>_st_repres" id="o<?php echo $representantes_grid->RowIndex ?>_st_repres" value="<?php echo ew_HtmlEncode($representantes->st_repres->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$representantes_grid->ListOptions->Render("body", "right", $representantes_grid->RowCnt);
?>
<script type="text/javascript">
frepresentantesgrid.UpdateOpts(<?php echo $representantes_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($representantes->CurrentMode == "add" || $representantes->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $representantes_grid->FormKeyCountName ?>" id="<?php echo $representantes_grid->FormKeyCountName ?>" value="<?php echo $representantes_grid->KeyCount ?>">
<?php echo $representantes_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($representantes->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $representantes_grid->FormKeyCountName ?>" id="<?php echo $representantes_grid->FormKeyCountName ?>" value="<?php echo $representantes_grid->KeyCount ?>">
<?php echo $representantes_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($representantes->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="frepresentantesgrid">
</div>
<?php

// Close recordset
if ($representantes_grid->Recordset)
	$representantes_grid->Recordset->Close();
?>
<?php if ($representantes_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($representantes_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($representantes->Export == "") { ?>
<script type="text/javascript">
frepresentantesgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$representantes_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$representantes_grid->Page_Terminate();
?>
