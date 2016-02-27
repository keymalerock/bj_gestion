<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "respuestainfo.php" ?>
<?php include_once "novedadinfo.php" ?>
<?php include_once "v_usuariosinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$respuesta_delete = NULL; // Initialize page object first

class crespuesta_delete extends crespuesta {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'respuesta';

	// Page object name
	var $PageObjName = 'respuesta_delete';

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

		// Table object (respuesta)
		if (!isset($GLOBALS["respuesta"]) || get_class($GLOBALS["respuesta"]) == "crespuesta") {
			$GLOBALS["respuesta"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["respuesta"];
		}

		// Table object (novedad)
		if (!isset($GLOBALS['novedad'])) $GLOBALS['novedad'] = new cnovedad();

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'respuesta', TRUE);

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
			$this->Page_Terminate("respuestalist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id_respuesta->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("respuestalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in respuesta class, respuestainfo.php

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
		$this->id_respuesta->setDbValue($rs->fields('id_respuesta'));
		$this->id_novedad->setDbValue($rs->fields('id_novedad'));
		$this->id_empleado->setDbValue($rs->fields('id_empleado'));
		$this->obs_resp->setDbValue($rs->fields('obs_resp'));
		$this->fe_resp->setDbValue($rs->fields('fe_resp'));
		$this->estado_resp->setDbValue($rs->fields('estado_resp'));
		$this->replica_resp->setDbValue($rs->fields('replica_resp'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_respuesta->DbValue = $row['id_respuesta'];
		$this->id_novedad->DbValue = $row['id_novedad'];
		$this->id_empleado->DbValue = $row['id_empleado'];
		$this->obs_resp->DbValue = $row['obs_resp'];
		$this->fe_resp->DbValue = $row['fe_resp'];
		$this->estado_resp->DbValue = $row['estado_resp'];
		$this->replica_resp->DbValue = $row['replica_resp'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_respuesta
		// id_novedad
		// id_empleado
		// obs_resp
		// fe_resp
		// estado_resp
		// replica_resp

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_respuesta
			$this->id_respuesta->ViewValue = $this->id_respuesta->CurrentValue;
			$this->id_respuesta->ViewCustomAttributes = "";

			// id_novedad
			$this->id_novedad->ViewValue = $this->id_novedad->CurrentValue;
			$this->id_novedad->ViewCustomAttributes = "";

			// id_empleado
			if (strval($this->id_empleado->CurrentValue) <> "") {
				$sFilterWrk = "`id_empleado`" . ew_SearchString("=", $this->id_empleado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_empleado`, `dociden_empleado` AS `DispFld`, `nomb_empleado` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empleados`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_empleado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nomb_empleado`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_empleado->ViewValue = $rswrk->fields('DispFld');
					$this->id_empleado->ViewValue .= ew_ValueSeparator(1,$this->id_empleado) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_empleado->ViewValue = $this->id_empleado->CurrentValue;
				}
			} else {
				$this->id_empleado->ViewValue = NULL;
			}
			$this->id_empleado->ViewCustomAttributes = "";

			// fe_resp
			$this->fe_resp->ViewValue = $this->fe_resp->CurrentValue;
			$this->fe_resp->ViewValue = ew_FormatDateTime($this->fe_resp->ViewValue, 5);
			$this->fe_resp->ViewCustomAttributes = "";

			// estado_resp
			if (strval($this->estado_resp->CurrentValue) <> "") {
				$sFilterWrk = "`id_x_estado_respuesta`" . ew_SearchString("=", $this->estado_resp->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_x_estado_respuesta`, `estado_respuesta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `x_estado_respuesta`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->estado_resp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->estado_resp->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->estado_resp->ViewValue = $this->estado_resp->CurrentValue;
				}
			} else {
				$this->estado_resp->ViewValue = NULL;
			}
			$this->estado_resp->ViewCustomAttributes = "";

			// id_respuesta
			$this->id_respuesta->LinkCustomAttributes = "";
			$this->id_respuesta->HrefValue = "";
			$this->id_respuesta->TooltipValue = "";

			// id_novedad
			$this->id_novedad->LinkCustomAttributes = "";
			$this->id_novedad->HrefValue = "";
			$this->id_novedad->TooltipValue = "";

			// id_empleado
			$this->id_empleado->LinkCustomAttributes = "";
			$this->id_empleado->HrefValue = "";
			$this->id_empleado->TooltipValue = "";

			// fe_resp
			$this->fe_resp->LinkCustomAttributes = "";
			$this->fe_resp->HrefValue = "";
			$this->fe_resp->TooltipValue = "";

			// estado_resp
			$this->estado_resp->LinkCustomAttributes = "";
			$this->estado_resp->HrefValue = "";
			$this->estado_resp->TooltipValue = "";
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
				$sThisKey .= $row['id_respuesta'];
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
		$Breadcrumb->Add("list", $this->TableVar, "respuestalist.php", $this->TableVar, TRUE);
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
if (!isset($respuesta_delete)) $respuesta_delete = new crespuesta_delete();

// Page init
$respuesta_delete->Page_Init();

// Page main
$respuesta_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$respuesta_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var respuesta_delete = new ew_Page("respuesta_delete");
respuesta_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = respuesta_delete.PageID; // For backward compatibility

// Form object
var frespuestadelete = new ew_Form("frespuestadelete");

// Form_CustomValidate event
frespuestadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frespuestadelete.ValidateRequired = true;
<?php } else { ?>
frespuestadelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frespuestadelete.Lists["x_id_empleado"] = {"LinkField":"x_id_empleado","Ajax":null,"AutoFill":false,"DisplayFields":["x_dociden_empleado","x_nomb_empleado","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frespuestadelete.Lists["x_estado_resp"] = {"LinkField":"x_id_x_estado_respuesta","Ajax":null,"AutoFill":false,"DisplayFields":["x_estado_respuesta","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($respuesta_delete->Recordset = $respuesta_delete->LoadRecordset())
	$respuesta_deleteTotalRecs = $respuesta_delete->Recordset->RecordCount(); // Get record count
if ($respuesta_deleteTotalRecs <= 0) { // No record found, exit
	if ($respuesta_delete->Recordset)
		$respuesta_delete->Recordset->Close();
	$respuesta_delete->Page_Terminate("respuestalist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $respuesta_delete->ShowPageHeader(); ?>
<?php
$respuesta_delete->ShowMessage();
?>
<form name="frespuestadelete" id="frespuestadelete" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="respuesta">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($respuesta_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_respuestadelete" class="ewTable ewTableSeparate">
<?php echo $respuesta->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($respuesta->id_respuesta->Visible) { // id_respuesta ?>
		<td><span id="elh_respuesta_id_respuesta" class="respuesta_id_respuesta"><?php echo $respuesta->id_respuesta->FldCaption() ?></span></td>
<?php } ?>
<?php if ($respuesta->id_novedad->Visible) { // id_novedad ?>
		<td><span id="elh_respuesta_id_novedad" class="respuesta_id_novedad"><?php echo $respuesta->id_novedad->FldCaption() ?></span></td>
<?php } ?>
<?php if ($respuesta->id_empleado->Visible) { // id_empleado ?>
		<td><span id="elh_respuesta_id_empleado" class="respuesta_id_empleado"><?php echo $respuesta->id_empleado->FldCaption() ?></span></td>
<?php } ?>
<?php if ($respuesta->fe_resp->Visible) { // fe_resp ?>
		<td><span id="elh_respuesta_fe_resp" class="respuesta_fe_resp"><?php echo $respuesta->fe_resp->FldCaption() ?></span></td>
<?php } ?>
<?php if ($respuesta->estado_resp->Visible) { // estado_resp ?>
		<td><span id="elh_respuesta_estado_resp" class="respuesta_estado_resp"><?php echo $respuesta->estado_resp->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$respuesta_delete->RecCnt = 0;
$i = 0;
while (!$respuesta_delete->Recordset->EOF) {
	$respuesta_delete->RecCnt++;
	$respuesta_delete->RowCnt++;

	// Set row properties
	$respuesta->ResetAttrs();
	$respuesta->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$respuesta_delete->LoadRowValues($respuesta_delete->Recordset);

	// Render row
	$respuesta_delete->RenderRow();
?>
	<tr<?php echo $respuesta->RowAttributes() ?>>
<?php if ($respuesta->id_respuesta->Visible) { // id_respuesta ?>
		<td<?php echo $respuesta->id_respuesta->CellAttributes() ?>>
<span id="el<?php echo $respuesta_delete->RowCnt ?>_respuesta_id_respuesta" class="control-group respuesta_id_respuesta">
<span<?php echo $respuesta->id_respuesta->ViewAttributes() ?>>
<?php echo $respuesta->id_respuesta->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($respuesta->id_novedad->Visible) { // id_novedad ?>
		<td<?php echo $respuesta->id_novedad->CellAttributes() ?>>
<span id="el<?php echo $respuesta_delete->RowCnt ?>_respuesta_id_novedad" class="control-group respuesta_id_novedad">
<span<?php echo $respuesta->id_novedad->ViewAttributes() ?>>
<?php echo $respuesta->id_novedad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($respuesta->id_empleado->Visible) { // id_empleado ?>
		<td<?php echo $respuesta->id_empleado->CellAttributes() ?>>
<span id="el<?php echo $respuesta_delete->RowCnt ?>_respuesta_id_empleado" class="control-group respuesta_id_empleado">
<span<?php echo $respuesta->id_empleado->ViewAttributes() ?>>
<?php echo $respuesta->id_empleado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($respuesta->fe_resp->Visible) { // fe_resp ?>
		<td<?php echo $respuesta->fe_resp->CellAttributes() ?>>
<span id="el<?php echo $respuesta_delete->RowCnt ?>_respuesta_fe_resp" class="control-group respuesta_fe_resp">
<span<?php echo $respuesta->fe_resp->ViewAttributes() ?>>
<?php echo $respuesta->fe_resp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($respuesta->estado_resp->Visible) { // estado_resp ?>
		<td<?php echo $respuesta->estado_resp->CellAttributes() ?>>
<span id="el<?php echo $respuesta_delete->RowCnt ?>_respuesta_estado_resp" class="control-group respuesta_estado_resp">
<span<?php echo $respuesta->estado_resp->ViewAttributes() ?>>
<?php echo $respuesta->estado_resp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$respuesta_delete->Recordset->MoveNext();
}
$respuesta_delete->Recordset->Close();
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
frespuestadelete.Init();
</script>
<?php
$respuesta_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$respuesta_delete->Page_Terminate();
?>
