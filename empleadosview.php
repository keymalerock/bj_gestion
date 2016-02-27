<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "empleadosinfo.php" ?>
<?php include_once "v_usuariosinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$empleados_view = NULL; // Initialize page object first

class cempleados_view extends cempleados {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'empleados';

	// Page object name
	var $PageObjName = 'empleados_view';

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

		// Table object (empleados)
		if (!isset($GLOBALS["empleados"]) || get_class($GLOBALS["empleados"]) == "cempleados") {
			$GLOBALS["empleados"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["empleados"];
		}
		$KeyUrl = "";
		if (@$_GET["id_empleado"] <> "") {
			$this->RecKey["id_empleado"] = $_GET["id_empleado"];
			$KeyUrl .= "&amp;id_empleado=" . urlencode($this->RecKey["id_empleado"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'empleados', TRUE);

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
			$this->Page_Terminate("empleadoslist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id_empleado->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["id_empleado"] <> "") {
				$this->id_empleado->setQueryStringValue($_GET["id_empleado"]);
				$this->RecKey["id_empleado"] = $this->id_empleado->QueryStringValue;
			} else {
				$sReturnUrl = "empleadoslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "empleadoslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "empleadoslist.php"; // Not page request, return to list
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
		$this->id_empleado->setDbValue($rs->fields('id_empleado'));
		$this->dociden_empleado->setDbValue($rs->fields('dociden_empleado'));
		$this->nomb_empleado->setDbValue($rs->fields('nomb_empleado'));
		$this->apell_empleado->setDbValue($rs->fields('apell_empleado'));
		$this->telf_empleado->setDbValue($rs->fields('telf_empleado'));
		$this->email_empleado->setDbValue($rs->fields('email_empleado'));
		$this->st_empleado_p->setDbValue($rs->fields('st_empleado_p'));
		$this->pass_empleado->setDbValue($rs->fields('pass_empleado'));
		$this->login_empleado->setDbValue($rs->fields('login_empleado'));
		$this->id_perfil->setDbValue($rs->fields('id_perfil'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_empleado->DbValue = $row['id_empleado'];
		$this->dociden_empleado->DbValue = $row['dociden_empleado'];
		$this->nomb_empleado->DbValue = $row['nomb_empleado'];
		$this->apell_empleado->DbValue = $row['apell_empleado'];
		$this->telf_empleado->DbValue = $row['telf_empleado'];
		$this->email_empleado->DbValue = $row['email_empleado'];
		$this->st_empleado_p->DbValue = $row['st_empleado_p'];
		$this->pass_empleado->DbValue = $row['pass_empleado'];
		$this->login_empleado->DbValue = $row['login_empleado'];
		$this->id_perfil->DbValue = $row['id_perfil'];
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
		// id_empleado
		// dociden_empleado
		// nomb_empleado
		// apell_empleado
		// telf_empleado
		// email_empleado
		// st_empleado_p
		// pass_empleado
		// login_empleado
		// id_perfil

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_empleado
			$this->id_empleado->ViewValue = $this->id_empleado->CurrentValue;
			$this->id_empleado->ViewCustomAttributes = "";

			// dociden_empleado
			$this->dociden_empleado->ViewValue = $this->dociden_empleado->CurrentValue;
			$this->dociden_empleado->ViewCustomAttributes = "";

			// nomb_empleado
			$this->nomb_empleado->ViewValue = $this->nomb_empleado->CurrentValue;
			$this->nomb_empleado->ViewCustomAttributes = "";

			// apell_empleado
			$this->apell_empleado->ViewValue = $this->apell_empleado->CurrentValue;
			$this->apell_empleado->ViewCustomAttributes = "";

			// telf_empleado
			$this->telf_empleado->ViewValue = $this->telf_empleado->CurrentValue;
			$this->telf_empleado->ViewCustomAttributes = "";

			// email_empleado
			$this->email_empleado->ViewValue = $this->email_empleado->CurrentValue;
			$this->email_empleado->ViewCustomAttributes = "";

			// st_empleado_p
			if (ew_ConvertToBool($this->st_empleado_p->CurrentValue)) {
				$this->st_empleado_p->ViewValue = $this->st_empleado_p->FldTagCaption(2) <> "" ? $this->st_empleado_p->FldTagCaption(2) : "1";
			} else {
				$this->st_empleado_p->ViewValue = $this->st_empleado_p->FldTagCaption(1) <> "" ? $this->st_empleado_p->FldTagCaption(1) : "0";
			}
			$this->st_empleado_p->ViewCustomAttributes = "";

			// pass_empleado
			$this->pass_empleado->ViewValue = "********";
			$this->pass_empleado->ViewCustomAttributes = "";

			// login_empleado
			$this->login_empleado->ViewValue = $this->login_empleado->CurrentValue;
			$this->login_empleado->ViewCustomAttributes = "";

			// id_perfil
			if (strval($this->id_perfil->CurrentValue) <> "") {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->id_perfil->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_perfil, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_perfil->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_perfil->ViewValue = $this->id_perfil->CurrentValue;
				}
			} else {
				$this->id_perfil->ViewValue = NULL;
			}
			$this->id_perfil->ViewCustomAttributes = "";

			// id_empleado
			$this->id_empleado->LinkCustomAttributes = "";
			$this->id_empleado->HrefValue = "";
			$this->id_empleado->TooltipValue = "";

			// dociden_empleado
			$this->dociden_empleado->LinkCustomAttributes = "";
			$this->dociden_empleado->HrefValue = "";
			$this->dociden_empleado->TooltipValue = "";

			// nomb_empleado
			$this->nomb_empleado->LinkCustomAttributes = "";
			$this->nomb_empleado->HrefValue = "";
			$this->nomb_empleado->TooltipValue = "";

			// apell_empleado
			$this->apell_empleado->LinkCustomAttributes = "";
			$this->apell_empleado->HrefValue = "";
			$this->apell_empleado->TooltipValue = "";

			// telf_empleado
			$this->telf_empleado->LinkCustomAttributes = "";
			$this->telf_empleado->HrefValue = "";
			$this->telf_empleado->TooltipValue = "";

			// email_empleado
			$this->email_empleado->LinkCustomAttributes = "";
			$this->email_empleado->HrefValue = "";
			$this->email_empleado->TooltipValue = "";

			// st_empleado_p
			$this->st_empleado_p->LinkCustomAttributes = "";
			$this->st_empleado_p->HrefValue = "";
			$this->st_empleado_p->TooltipValue = "";

			// pass_empleado
			$this->pass_empleado->LinkCustomAttributes = "";
			$this->pass_empleado->HrefValue = "";
			$this->pass_empleado->TooltipValue = "";

			// login_empleado
			$this->login_empleado->LinkCustomAttributes = "";
			$this->login_empleado->HrefValue = "";
			$this->login_empleado->TooltipValue = "";

			// id_perfil
			$this->id_perfil->LinkCustomAttributes = "";
			$this->id_perfil->HrefValue = "";
			$this->id_perfil->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "empleadoslist.php", $this->TableVar, TRUE);
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
if (!isset($empleados_view)) $empleados_view = new cempleados_view();

// Page init
$empleados_view->Page_Init();

// Page main
$empleados_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empleados_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var empleados_view = new ew_Page("empleados_view");
empleados_view.PageID = "view"; // Page ID
var EW_PAGE_ID = empleados_view.PageID; // For backward compatibility

// Form object
var fempleadosview = new ew_Form("fempleadosview");

// Form_CustomValidate event
fempleadosview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempleadosview.ValidateRequired = true;
<?php } else { ?>
fempleadosview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fempleadosview.Lists["x_id_perfil"] = {"LinkField":"x_userlevelid","Ajax":null,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<div class="ewViewExportOptions">
<?php $empleados_view->ExportOptions->Render("body") ?>
<?php if (!$empleados_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($empleados_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php $empleados_view->ShowPageHeader(); ?>
<?php
$empleados_view->ShowMessage();
?>
<form name="fempleadosview" id="fempleadosview" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="empleados">
<table class="ewGrid"><tr><td>
<table id="tbl_empleadosview" class="table table-bordered table-striped">
<?php if ($empleados->id_empleado->Visible) { // id_empleado ?>
	<tr id="r_id_empleado">
		<td><span id="elh_empleados_id_empleado"><?php echo $empleados->id_empleado->FldCaption() ?></span></td>
		<td<?php echo $empleados->id_empleado->CellAttributes() ?>>
<span id="el_empleados_id_empleado" class="control-group">
<span<?php echo $empleados->id_empleado->ViewAttributes() ?>>
<?php echo $empleados->id_empleado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleados->dociden_empleado->Visible) { // dociden_empleado ?>
	<tr id="r_dociden_empleado">
		<td><span id="elh_empleados_dociden_empleado"><?php echo $empleados->dociden_empleado->FldCaption() ?></span></td>
		<td<?php echo $empleados->dociden_empleado->CellAttributes() ?>>
<span id="el_empleados_dociden_empleado" class="control-group">
<span<?php echo $empleados->dociden_empleado->ViewAttributes() ?>>
<?php echo $empleados->dociden_empleado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleados->nomb_empleado->Visible) { // nomb_empleado ?>
	<tr id="r_nomb_empleado">
		<td><span id="elh_empleados_nomb_empleado"><?php echo $empleados->nomb_empleado->FldCaption() ?></span></td>
		<td<?php echo $empleados->nomb_empleado->CellAttributes() ?>>
<span id="el_empleados_nomb_empleado" class="control-group">
<span<?php echo $empleados->nomb_empleado->ViewAttributes() ?>>
<?php echo $empleados->nomb_empleado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleados->apell_empleado->Visible) { // apell_empleado ?>
	<tr id="r_apell_empleado">
		<td><span id="elh_empleados_apell_empleado"><?php echo $empleados->apell_empleado->FldCaption() ?></span></td>
		<td<?php echo $empleados->apell_empleado->CellAttributes() ?>>
<span id="el_empleados_apell_empleado" class="control-group">
<span<?php echo $empleados->apell_empleado->ViewAttributes() ?>>
<?php echo $empleados->apell_empleado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleados->telf_empleado->Visible) { // telf_empleado ?>
	<tr id="r_telf_empleado">
		<td><span id="elh_empleados_telf_empleado"><?php echo $empleados->telf_empleado->FldCaption() ?></span></td>
		<td<?php echo $empleados->telf_empleado->CellAttributes() ?>>
<span id="el_empleados_telf_empleado" class="control-group">
<span<?php echo $empleados->telf_empleado->ViewAttributes() ?>>
<?php echo $empleados->telf_empleado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleados->email_empleado->Visible) { // email_empleado ?>
	<tr id="r_email_empleado">
		<td><span id="elh_empleados_email_empleado"><?php echo $empleados->email_empleado->FldCaption() ?></span></td>
		<td<?php echo $empleados->email_empleado->CellAttributes() ?>>
<span id="el_empleados_email_empleado" class="control-group">
<span<?php echo $empleados->email_empleado->ViewAttributes() ?>>
<?php echo $empleados->email_empleado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleados->st_empleado_p->Visible) { // st_empleado_p ?>
	<tr id="r_st_empleado_p">
		<td><span id="elh_empleados_st_empleado_p"><?php echo $empleados->st_empleado_p->FldCaption() ?></span></td>
		<td<?php echo $empleados->st_empleado_p->CellAttributes() ?>>
<span id="el_empleados_st_empleado_p" class="control-group">
<span<?php echo $empleados->st_empleado_p->ViewAttributes() ?>>
<?php echo $empleados->st_empleado_p->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleados->pass_empleado->Visible) { // pass_empleado ?>
	<tr id="r_pass_empleado">
		<td><span id="elh_empleados_pass_empleado"><?php echo $empleados->pass_empleado->FldCaption() ?></span></td>
		<td<?php echo $empleados->pass_empleado->CellAttributes() ?>>
<span id="el_empleados_pass_empleado" class="control-group">
<span<?php echo $empleados->pass_empleado->ViewAttributes() ?>>
<?php echo $empleados->pass_empleado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleados->login_empleado->Visible) { // login_empleado ?>
	<tr id="r_login_empleado">
		<td><span id="elh_empleados_login_empleado"><?php echo $empleados->login_empleado->FldCaption() ?></span></td>
		<td<?php echo $empleados->login_empleado->CellAttributes() ?>>
<span id="el_empleados_login_empleado" class="control-group">
<span<?php echo $empleados->login_empleado->ViewAttributes() ?>>
<?php echo $empleados->login_empleado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empleados->id_perfil->Visible) { // id_perfil ?>
	<tr id="r_id_perfil">
		<td><span id="elh_empleados_id_perfil"><?php echo $empleados->id_perfil->FldCaption() ?></span></td>
		<td<?php echo $empleados->id_perfil->CellAttributes() ?>>
<span id="el_empleados_id_perfil" class="control-group">
<span<?php echo $empleados->id_perfil->ViewAttributes() ?>>
<?php echo $empleados->id_perfil->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
fempleadosview.Init();
</script>
<?php
$empleados_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empleados_view->Page_Terminate();
?>
