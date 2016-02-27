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

$respuesta_view = NULL; // Initialize page object first

class crespuesta_view extends crespuesta {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'respuesta';

	// Page object name
	var $PageObjName = 'respuesta_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["id_respuesta"] <> "") {
			$this->RecKey["id_respuesta"] = $_GET["id_respuesta"];
			$KeyUrl .= "&amp;id_respuesta=" . urlencode($this->RecKey["id_respuesta"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (novedad)
		if (!isset($GLOBALS['novedad'])) $GLOBALS['novedad'] = new cnovedad();

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'respuesta', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id_respuesta"] <> "") {
				$this->id_respuesta->setQueryStringValue($_GET["id_respuesta"]);
				$this->RecKey["id_respuesta"] = $this->id_respuesta->QueryStringValue;
			} else {
				$sReturnUrl = "respuestalist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "respuestalist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "respuestalist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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

			// obs_resp
			$this->obs_resp->ViewValue = $this->obs_resp->CurrentValue;
			$this->obs_resp->ViewCustomAttributes = "";

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

			// replica_resp
			$this->replica_resp->ViewValue = $this->replica_resp->CurrentValue;
			$this->replica_resp->ViewCustomAttributes = "";

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

			// obs_resp
			$this->obs_resp->LinkCustomAttributes = "";
			$this->obs_resp->HrefValue = "";
			$this->obs_resp->TooltipValue = "";

			// fe_resp
			$this->fe_resp->LinkCustomAttributes = "";
			$this->fe_resp->HrefValue = "";
			$this->fe_resp->TooltipValue = "";

			// estado_resp
			$this->estado_resp->LinkCustomAttributes = "";
			$this->estado_resp->HrefValue = "";
			$this->estado_resp->TooltipValue = "";

			// replica_resp
			$this->replica_resp->LinkCustomAttributes = "";
			$this->replica_resp->HrefValue = "";
			$this->replica_resp->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "respuestalist.php", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, ew_CurrentUrl());
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
if (!isset($respuesta_view)) $respuesta_view = new crespuesta_view();

// Page init
$respuesta_view->Page_Init();

// Page main
$respuesta_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$respuesta_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var respuesta_view = new ew_Page("respuesta_view");
respuesta_view.PageID = "view"; // Page ID
var EW_PAGE_ID = respuesta_view.PageID; // For backward compatibility

// Form object
var frespuestaview = new ew_Form("frespuestaview");

// Form_CustomValidate event
frespuestaview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frespuestaview.ValidateRequired = true;
<?php } else { ?>
frespuestaview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frespuestaview.Lists["x_id_empleado"] = {"LinkField":"x_id_empleado","Ajax":null,"AutoFill":false,"DisplayFields":["x_dociden_empleado","x_nomb_empleado","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frespuestaview.Lists["x_estado_resp"] = {"LinkField":"x_id_x_estado_respuesta","Ajax":null,"AutoFill":false,"DisplayFields":["x_estado_respuesta","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<div class="ewViewExportOptions">
<?php $respuesta_view->ExportOptions->Render("body") ?>
<?php if (!$respuesta_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($respuesta_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php $respuesta_view->ShowPageHeader(); ?>
<?php
$respuesta_view->ShowMessage();
?>
<form name="frespuestaview" id="frespuestaview" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="respuesta">
<table class="ewGrid"><tr><td>
<table id="tbl_respuestaview" class="table table-bordered table-striped">
<?php if ($respuesta->id_respuesta->Visible) { // id_respuesta ?>
	<tr id="r_id_respuesta">
		<td><span id="elh_respuesta_id_respuesta"><?php echo $respuesta->id_respuesta->FldCaption() ?></span></td>
		<td<?php echo $respuesta->id_respuesta->CellAttributes() ?>>
<span id="el_respuesta_id_respuesta" class="control-group">
<span<?php echo $respuesta->id_respuesta->ViewAttributes() ?>>
<?php echo $respuesta->id_respuesta->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($respuesta->id_novedad->Visible) { // id_novedad ?>
	<tr id="r_id_novedad">
		<td><span id="elh_respuesta_id_novedad"><?php echo $respuesta->id_novedad->FldCaption() ?></span></td>
		<td<?php echo $respuesta->id_novedad->CellAttributes() ?>>
<span id="el_respuesta_id_novedad" class="control-group">
<span<?php echo $respuesta->id_novedad->ViewAttributes() ?>>
<?php echo $respuesta->id_novedad->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($respuesta->id_empleado->Visible) { // id_empleado ?>
	<tr id="r_id_empleado">
		<td><span id="elh_respuesta_id_empleado"><?php echo $respuesta->id_empleado->FldCaption() ?></span></td>
		<td<?php echo $respuesta->id_empleado->CellAttributes() ?>>
<span id="el_respuesta_id_empleado" class="control-group">
<span<?php echo $respuesta->id_empleado->ViewAttributes() ?>>
<?php echo $respuesta->id_empleado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($respuesta->obs_resp->Visible) { // obs_resp ?>
	<tr id="r_obs_resp">
		<td><span id="elh_respuesta_obs_resp"><?php echo $respuesta->obs_resp->FldCaption() ?></span></td>
		<td<?php echo $respuesta->obs_resp->CellAttributes() ?>>
<span id="el_respuesta_obs_resp" class="control-group">
<span<?php echo $respuesta->obs_resp->ViewAttributes() ?>>
<?php echo $respuesta->obs_resp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($respuesta->fe_resp->Visible) { // fe_resp ?>
	<tr id="r_fe_resp">
		<td><span id="elh_respuesta_fe_resp"><?php echo $respuesta->fe_resp->FldCaption() ?></span></td>
		<td<?php echo $respuesta->fe_resp->CellAttributes() ?>>
<span id="el_respuesta_fe_resp" class="control-group">
<span<?php echo $respuesta->fe_resp->ViewAttributes() ?>>
<?php echo $respuesta->fe_resp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($respuesta->estado_resp->Visible) { // estado_resp ?>
	<tr id="r_estado_resp">
		<td><span id="elh_respuesta_estado_resp"><?php echo $respuesta->estado_resp->FldCaption() ?></span></td>
		<td<?php echo $respuesta->estado_resp->CellAttributes() ?>>
<span id="el_respuesta_estado_resp" class="control-group">
<span<?php echo $respuesta->estado_resp->ViewAttributes() ?>>
<?php echo $respuesta->estado_resp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($respuesta->replica_resp->Visible) { // replica_resp ?>
	<tr id="r_replica_resp">
		<td><span id="elh_respuesta_replica_resp"><?php echo $respuesta->replica_resp->FldCaption() ?></span></td>
		<td<?php echo $respuesta->replica_resp->CellAttributes() ?>>
<span id="el_respuesta_replica_resp" class="control-group">
<span<?php echo $respuesta->replica_resp->ViewAttributes() ?>>
<?php echo $respuesta->replica_resp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
frespuestaview.Init();
</script>
<?php
$respuesta_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$respuesta_view->Page_Terminate();
?>
