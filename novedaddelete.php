<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "novedadinfo.php" ?>
<?php include_once "v_usuariosinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$novedad_delete = NULL; // Initialize page object first

class cnovedad_delete extends cnovedad {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'novedad';

	// Page object name
	var $PageObjName = 'novedad_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-error ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<table class=\"ewStdTable\"><tr><td><div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div></td></tr></table>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (novedad)
		if (!isset($GLOBALS["novedad"]) || get_class($GLOBALS["novedad"]) == "cnovedad") {
			$GLOBALS["novedad"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["novedad"];
		}

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'novedad', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("novedadlist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("novedadlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in novedad class, novedadinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id_novedad->setDbValue($rs->fields('id_novedad'));
		$this->id_afiliado->setDbValue($rs->fields('id_afiliado'));
		$this->obs_nov->setDbValue($rs->fields('obs_nov'));
		$this->fe_nov->setDbValue($rs->fields('fe_nov'));
		$this->estado_nov->setDbValue($rs->fields('estado_nov'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_novedad->DbValue = $row['id_novedad'];
		$this->id_afiliado->DbValue = $row['id_afiliado'];
		$this->obs_nov->DbValue = $row['obs_nov'];
		$this->fe_nov->DbValue = $row['fe_nov'];
		$this->estado_nov->DbValue = $row['estado_nov'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_novedad
		// id_afiliado
		// obs_nov
		// fe_nov
		// estado_nov

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_novedad
			$this->id_novedad->ViewValue = $this->id_novedad->CurrentValue;
			$this->id_novedad->ViewCustomAttributes = "";

			// id_afiliado
			$this->id_afiliado->ViewValue = $this->id_afiliado->CurrentValue;
			if (strval($this->id_afiliado->CurrentValue) <> "") {
				$sFilterWrk = "`id_afiliado`" . ew_SearchString("=", $this->id_afiliado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_afiliado`, `dociden_afiliado` AS `DispFld`, `apell_afiliado` AS `Disp2Fld`, `nomb_afiliado` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `afiliado`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_afiliado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_afiliado->ViewValue = $rswrk->fields('DispFld');
					$this->id_afiliado->ViewValue .= ew_ValueSeparator(1,$this->id_afiliado) . $rswrk->fields('Disp2Fld');
					$this->id_afiliado->ViewValue .= ew_ValueSeparator(2,$this->id_afiliado) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_afiliado->ViewValue = $this->id_afiliado->CurrentValue;
				}
			} else {
				$this->id_afiliado->ViewValue = NULL;
			}
			$this->id_afiliado->ViewCustomAttributes = "";

			// obs_nov
			$this->obs_nov->ViewValue = $this->obs_nov->CurrentValue;
			$this->obs_nov->ViewCustomAttributes = "";

			// fe_nov
			$this->fe_nov->ViewValue = $this->fe_nov->CurrentValue;
			$this->fe_nov->ViewValue = ew_FormatDateTime($this->fe_nov->ViewValue, 5);
			$this->fe_nov->ViewCustomAttributes = "";

			// estado_nov
			if (strval($this->estado_nov->CurrentValue) <> "") {
				switch ($this->estado_nov->CurrentValue) {
					case $this->estado_nov->FldTagValue(1):
						$this->estado_nov->ViewValue = $this->estado_nov->FldTagCaption(1) <> "" ? $this->estado_nov->FldTagCaption(1) : $this->estado_nov->CurrentValue;
						break;
					case $this->estado_nov->FldTagValue(2):
						$this->estado_nov->ViewValue = $this->estado_nov->FldTagCaption(2) <> "" ? $this->estado_nov->FldTagCaption(2) : $this->estado_nov->CurrentValue;
						break;
					case $this->estado_nov->FldTagValue(3):
						$this->estado_nov->ViewValue = $this->estado_nov->FldTagCaption(3) <> "" ? $this->estado_nov->FldTagCaption(3) : $this->estado_nov->CurrentValue;
						break;
					case $this->estado_nov->FldTagValue(4):
						$this->estado_nov->ViewValue = $this->estado_nov->FldTagCaption(4) <> "" ? $this->estado_nov->FldTagCaption(4) : $this->estado_nov->CurrentValue;
						break;
					case $this->estado_nov->FldTagValue(5):
						$this->estado_nov->ViewValue = $this->estado_nov->FldTagCaption(5) <> "" ? $this->estado_nov->FldTagCaption(5) : $this->estado_nov->CurrentValue;
						break;
					default:
						$this->estado_nov->ViewValue = $this->estado_nov->CurrentValue;
				}
			} else {
				$this->estado_nov->ViewValue = NULL;
			}
			$this->estado_nov->ViewCustomAttributes = "";

			// id_afiliado
			$this->id_afiliado->LinkCustomAttributes = "";
			$this->id_afiliado->HrefValue = "";
			$this->id_afiliado->TooltipValue = "";

			// obs_nov
			$this->obs_nov->LinkCustomAttributes = "";
			$this->obs_nov->HrefValue = "";
			$this->obs_nov->TooltipValue = "";

			// fe_nov
			$this->fe_nov->LinkCustomAttributes = "";
			$this->fe_nov->HrefValue = "";
			$this->fe_nov->TooltipValue = "";

			// estado_nov
			$this->estado_nov->LinkCustomAttributes = "";
			$this->estado_nov->HrefValue = "";
			$this->estado_nov->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id_novedad'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "novedadlist.php", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, ew_CurrentUrl());
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($novedad_delete)) $novedad_delete = new cnovedad_delete();

// Page init
$novedad_delete->Page_Init();

// Page main
$novedad_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$novedad_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var novedad_delete = new ew_Page("novedad_delete");
novedad_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = novedad_delete.PageID; // For backward compatibility

// Form object
var fnovedaddelete = new ew_Form("fnovedaddelete");

// Form_CustomValidate event
fnovedaddelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnovedaddelete.ValidateRequired = true;
<?php } else { ?>
fnovedaddelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fnovedaddelete.Lists["x_id_afiliado"] = {"LinkField":"x_id_afiliado","Ajax":true,"AutoFill":false,"DisplayFields":["x_dociden_afiliado","x_apell_afiliado","x_nomb_afiliado",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($novedad_delete->Recordset = $novedad_delete->LoadRecordset())
	$novedad_deleteTotalRecs = $novedad_delete->Recordset->RecordCount(); // Get record count
if ($novedad_deleteTotalRecs <= 0) { // No record found, exit
	if ($novedad_delete->Recordset)
		$novedad_delete->Recordset->Close();
	$novedad_delete->Page_Terminate("novedadlist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $novedad_delete->ShowPageHeader(); ?>
<?php
$novedad_delete->ShowMessage();
?>
<form name="fnovedaddelete" id="fnovedaddelete" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="novedad">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($novedad_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_novedaddelete" class="ewTable ewTableSeparate">
<?php echo $novedad->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($novedad->id_afiliado->Visible) { // id_afiliado ?>
		<td><span id="elh_novedad_id_afiliado" class="novedad_id_afiliado"><?php echo $novedad->id_afiliado->FldCaption() ?></span></td>
<?php } ?>
<?php if ($novedad->obs_nov->Visible) { // obs_nov ?>
		<td><span id="elh_novedad_obs_nov" class="novedad_obs_nov"><?php echo $novedad->obs_nov->FldCaption() ?></span></td>
<?php } ?>
<?php if ($novedad->fe_nov->Visible) { // fe_nov ?>
		<td><span id="elh_novedad_fe_nov" class="novedad_fe_nov"><?php echo $novedad->fe_nov->FldCaption() ?></span></td>
<?php } ?>
<?php if ($novedad->estado_nov->Visible) { // estado_nov ?>
		<td><span id="elh_novedad_estado_nov" class="novedad_estado_nov"><?php echo $novedad->estado_nov->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$novedad_delete->RecCnt = 0;
$i = 0;
while (!$novedad_delete->Recordset->EOF) {
	$novedad_delete->RecCnt++;
	$novedad_delete->RowCnt++;

	// Set row properties
	$novedad->ResetAttrs();
	$novedad->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$novedad_delete->LoadRowValues($novedad_delete->Recordset);

	// Render row
	$novedad_delete->RenderRow();
?>
	<tr<?php echo $novedad->RowAttributes() ?>>
<?php if ($novedad->id_afiliado->Visible) { // id_afiliado ?>
		<td<?php echo $novedad->id_afiliado->CellAttributes() ?>>
<span id="el<?php echo $novedad_delete->RowCnt ?>_novedad_id_afiliado" class="control-group novedad_id_afiliado">
<span<?php echo $novedad->id_afiliado->ViewAttributes() ?>>
<?php echo $novedad->id_afiliado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($novedad->obs_nov->Visible) { // obs_nov ?>
		<td<?php echo $novedad->obs_nov->CellAttributes() ?>>
<span id="el<?php echo $novedad_delete->RowCnt ?>_novedad_obs_nov" class="control-group novedad_obs_nov">
<span<?php echo $novedad->obs_nov->ViewAttributes() ?>>
<?php echo $novedad->obs_nov->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($novedad->fe_nov->Visible) { // fe_nov ?>
		<td<?php echo $novedad->fe_nov->CellAttributes() ?>>
<span id="el<?php echo $novedad_delete->RowCnt ?>_novedad_fe_nov" class="control-group novedad_fe_nov">
<span<?php echo $novedad->fe_nov->ViewAttributes() ?>>
<?php echo $novedad->fe_nov->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($novedad->estado_nov->Visible) { // estado_nov ?>
		<td<?php echo $novedad->estado_nov->CellAttributes() ?>>
<span id="el<?php echo $novedad_delete->RowCnt ?>_novedad_estado_nov" class="control-group novedad_estado_nov">
<span<?php echo $novedad->estado_nov->ViewAttributes() ?>>
<?php echo $novedad->estado_nov->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$novedad_delete->Recordset->MoveNext();
}
$novedad_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fnovedaddelete.Init();
</script>
<?php
$novedad_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$novedad_delete->Page_Terminate();
?>
