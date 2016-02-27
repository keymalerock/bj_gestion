<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "representantesinfo.php" ?>
<?php include_once "afiliadoinfo.php" ?>
<?php include_once "v_usuariosinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$representantes_view = NULL; // Initialize page object first

class crepresentantes_view extends crepresentantes {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'representantes';

	// Page object name
	var $PageObjName = 'representantes_view';

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

		// Table object (representantes)
		if (!isset($GLOBALS["representantes"]) || get_class($GLOBALS["representantes"]) == "crepresentantes") {
			$GLOBALS["representantes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["representantes"];
		}
		$KeyUrl = "";
		if (@$_GET["id_representante"] <> "") {
			$this->RecKey["id_representante"] = $_GET["id_representante"];
			$KeyUrl .= "&amp;id_representante=" . urlencode($this->RecKey["id_representante"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (afiliado)
		if (!isset($GLOBALS['afiliado'])) $GLOBALS['afiliado'] = new cafiliado();

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'representantes', TRUE);

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
			$this->Page_Terminate("representanteslist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id_representante->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["id_representante"] <> "") {
				$this->id_representante->setQueryStringValue($_GET["id_representante"]);
				$this->RecKey["id_representante"] = $this->id_representante->QueryStringValue;
			} else {
				$sReturnUrl = "representanteslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "representanteslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "representanteslist.php"; // Not page request, return to list
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
		$this->id_representante->setDbValue($rs->fields('id_representante'));
		$this->id_afiliado->setDbValue($rs->fields('id_afiliado'));
		$this->dociden_repres->setDbValue($rs->fields('dociden_repres'));
		$this->apell_repres->setDbValue($rs->fields('apell_repres'));
		$this->nomb_repres->setDbValue($rs->fields('nomb_repres'));
		$this->telf_resi_repres->setDbValue($rs->fields('telf_resi_repres'));
		$this->email_repres->setDbValue($rs->fields('email_repres'));
		$this->par_repres->setDbValue($rs->fields('par_repres'));
		$this->cel_repres->setDbValue($rs->fields('cel_repres'));
		$this->contact_e_repres->setDbValue($rs->fields('contact_e_repres'));
		$this->contact_d_repres->setDbValue($rs->fields('contact_d_repres'));
		$this->st_repres->setDbValue($rs->fields('st_repres'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_representante->DbValue = $row['id_representante'];
		$this->id_afiliado->DbValue = $row['id_afiliado'];
		$this->dociden_repres->DbValue = $row['dociden_repres'];
		$this->apell_repres->DbValue = $row['apell_repres'];
		$this->nomb_repres->DbValue = $row['nomb_repres'];
		$this->telf_resi_repres->DbValue = $row['telf_resi_repres'];
		$this->email_repres->DbValue = $row['email_repres'];
		$this->par_repres->DbValue = $row['par_repres'];
		$this->cel_repres->DbValue = $row['cel_repres'];
		$this->contact_e_repres->DbValue = $row['contact_e_repres'];
		$this->contact_d_repres->DbValue = $row['contact_d_repres'];
		$this->st_repres->DbValue = $row['st_repres'];
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
		// id_representante
		// id_afiliado
		// dociden_repres
		// apell_repres
		// nomb_repres
		// telf_resi_repres
		// email_repres
		// par_repres
		// cel_repres
		// contact_e_repres
		// contact_d_repres
		// st_repres

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_representante
			$this->id_representante->ViewValue = $this->id_representante->CurrentValue;
			$this->id_representante->ViewCustomAttributes = "";

			// id_afiliado
			$this->id_afiliado->ViewValue = $this->id_afiliado->CurrentValue;
			if (strval($this->id_afiliado->CurrentValue) <> "") {
				$sFilterWrk = "`id_afiliado`" . ew_SearchString("=", $this->id_afiliado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_afiliado`, `apell_afiliado` AS `DispFld`, `nomb_afiliado` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `afiliado`";
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
					$rswrk->Close();
				} else {
					$this->id_afiliado->ViewValue = $this->id_afiliado->CurrentValue;
				}
			} else {
				$this->id_afiliado->ViewValue = NULL;
			}
			$this->id_afiliado->ViewCustomAttributes = "";

			// dociden_repres
			$this->dociden_repres->ViewValue = $this->dociden_repres->CurrentValue;
			$this->dociden_repres->ViewCustomAttributes = "";

			// apell_repres
			$this->apell_repres->ViewValue = $this->apell_repres->CurrentValue;
			$this->apell_repres->ViewCustomAttributes = "";

			// nomb_repres
			$this->nomb_repres->ViewValue = $this->nomb_repres->CurrentValue;
			$this->nomb_repres->ViewCustomAttributes = "";

			// telf_resi_repres
			$this->telf_resi_repres->ViewValue = $this->telf_resi_repres->CurrentValue;
			$this->telf_resi_repres->ViewCustomAttributes = "";

			// email_repres
			$this->email_repres->ViewValue = $this->email_repres->CurrentValue;
			$this->email_repres->ViewCustomAttributes = "";

			// par_repres
			if (strval($this->par_repres->CurrentValue) <> "") {
				switch ($this->par_repres->CurrentValue) {
					case $this->par_repres->FldTagValue(1):
						$this->par_repres->ViewValue = $this->par_repres->FldTagCaption(1) <> "" ? $this->par_repres->FldTagCaption(1) : $this->par_repres->CurrentValue;
						break;
					case $this->par_repres->FldTagValue(2):
						$this->par_repres->ViewValue = $this->par_repres->FldTagCaption(2) <> "" ? $this->par_repres->FldTagCaption(2) : $this->par_repres->CurrentValue;
						break;
					case $this->par_repres->FldTagValue(3):
						$this->par_repres->ViewValue = $this->par_repres->FldTagCaption(3) <> "" ? $this->par_repres->FldTagCaption(3) : $this->par_repres->CurrentValue;
						break;
					case $this->par_repres->FldTagValue(4):
						$this->par_repres->ViewValue = $this->par_repres->FldTagCaption(4) <> "" ? $this->par_repres->FldTagCaption(4) : $this->par_repres->CurrentValue;
						break;
					case $this->par_repres->FldTagValue(5):
						$this->par_repres->ViewValue = $this->par_repres->FldTagCaption(5) <> "" ? $this->par_repres->FldTagCaption(5) : $this->par_repres->CurrentValue;
						break;
					case $this->par_repres->FldTagValue(6):
						$this->par_repres->ViewValue = $this->par_repres->FldTagCaption(6) <> "" ? $this->par_repres->FldTagCaption(6) : $this->par_repres->CurrentValue;
						break;
					case $this->par_repres->FldTagValue(7):
						$this->par_repres->ViewValue = $this->par_repres->FldTagCaption(7) <> "" ? $this->par_repres->FldTagCaption(7) : $this->par_repres->CurrentValue;
						break;
					default:
						$this->par_repres->ViewValue = $this->par_repres->CurrentValue;
				}
			} else {
				$this->par_repres->ViewValue = NULL;
			}
			$this->par_repres->ViewCustomAttributes = "";

			// cel_repres
			$this->cel_repres->ViewValue = $this->cel_repres->CurrentValue;
			$this->cel_repres->ViewCustomAttributes = "";

			// contact_e_repres
			if (strval($this->contact_e_repres->CurrentValue) <> "") {
				switch ($this->contact_e_repres->CurrentValue) {
					case $this->contact_e_repres->FldTagValue(1):
						$this->contact_e_repres->ViewValue = $this->contact_e_repres->FldTagCaption(1) <> "" ? $this->contact_e_repres->FldTagCaption(1) : $this->contact_e_repres->CurrentValue;
						break;
					case $this->contact_e_repres->FldTagValue(2):
						$this->contact_e_repres->ViewValue = $this->contact_e_repres->FldTagCaption(2) <> "" ? $this->contact_e_repres->FldTagCaption(2) : $this->contact_e_repres->CurrentValue;
						break;
					default:
						$this->contact_e_repres->ViewValue = $this->contact_e_repres->CurrentValue;
				}
			} else {
				$this->contact_e_repres->ViewValue = NULL;
			}
			$this->contact_e_repres->ViewCustomAttributes = "";

			// contact_d_repres
			if (strval($this->contact_d_repres->CurrentValue) <> "") {
				switch ($this->contact_d_repres->CurrentValue) {
					case $this->contact_d_repres->FldTagValue(1):
						$this->contact_d_repres->ViewValue = $this->contact_d_repres->FldTagCaption(1) <> "" ? $this->contact_d_repres->FldTagCaption(1) : $this->contact_d_repres->CurrentValue;
						break;
					case $this->contact_d_repres->FldTagValue(2):
						$this->contact_d_repres->ViewValue = $this->contact_d_repres->FldTagCaption(2) <> "" ? $this->contact_d_repres->FldTagCaption(2) : $this->contact_d_repres->CurrentValue;
						break;
					default:
						$this->contact_d_repres->ViewValue = $this->contact_d_repres->CurrentValue;
				}
			} else {
				$this->contact_d_repres->ViewValue = NULL;
			}
			$this->contact_d_repres->ViewCustomAttributes = "";

			// st_repres
			if (strval($this->st_repres->CurrentValue) <> "") {
				switch ($this->st_repres->CurrentValue) {
					case $this->st_repres->FldTagValue(1):
						$this->st_repres->ViewValue = $this->st_repres->FldTagCaption(1) <> "" ? $this->st_repres->FldTagCaption(1) : $this->st_repres->CurrentValue;
						break;
					case $this->st_repres->FldTagValue(2):
						$this->st_repres->ViewValue = $this->st_repres->FldTagCaption(2) <> "" ? $this->st_repres->FldTagCaption(2) : $this->st_repres->CurrentValue;
						break;
					default:
						$this->st_repres->ViewValue = $this->st_repres->CurrentValue;
				}
			} else {
				$this->st_repres->ViewValue = NULL;
			}
			$this->st_repres->ViewCustomAttributes = "";

			// id_representante
			$this->id_representante->LinkCustomAttributes = "";
			$this->id_representante->HrefValue = "";
			$this->id_representante->TooltipValue = "";

			// id_afiliado
			$this->id_afiliado->LinkCustomAttributes = "";
			$this->id_afiliado->HrefValue = "";
			$this->id_afiliado->TooltipValue = "";

			// dociden_repres
			$this->dociden_repres->LinkCustomAttributes = "";
			$this->dociden_repres->HrefValue = "";
			$this->dociden_repres->TooltipValue = "";

			// apell_repres
			$this->apell_repres->LinkCustomAttributes = "";
			$this->apell_repres->HrefValue = "";
			$this->apell_repres->TooltipValue = "";

			// nomb_repres
			$this->nomb_repres->LinkCustomAttributes = "";
			$this->nomb_repres->HrefValue = "";
			$this->nomb_repres->TooltipValue = "";

			// telf_resi_repres
			$this->telf_resi_repres->LinkCustomAttributes = "";
			$this->telf_resi_repres->HrefValue = "";
			$this->telf_resi_repres->TooltipValue = "";

			// email_repres
			$this->email_repres->LinkCustomAttributes = "";
			$this->email_repres->HrefValue = "";
			$this->email_repres->TooltipValue = "";

			// par_repres
			$this->par_repres->LinkCustomAttributes = "";
			$this->par_repres->HrefValue = "";
			$this->par_repres->TooltipValue = "";

			// cel_repres
			$this->cel_repres->LinkCustomAttributes = "";
			$this->cel_repres->HrefValue = "";
			$this->cel_repres->TooltipValue = "";

			// contact_e_repres
			$this->contact_e_repres->LinkCustomAttributes = "";
			$this->contact_e_repres->HrefValue = "";
			$this->contact_e_repres->TooltipValue = "";

			// contact_d_repres
			$this->contact_d_repres->LinkCustomAttributes = "";
			$this->contact_d_repres->HrefValue = "";
			$this->contact_d_repres->TooltipValue = "";

			// st_repres
			$this->st_repres->LinkCustomAttributes = "";
			$this->st_repres->HrefValue = "";
			$this->st_repres->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "representanteslist.php", $this->TableVar, TRUE);
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
if (!isset($representantes_view)) $representantes_view = new crepresentantes_view();

// Page init
$representantes_view->Page_Init();

// Page main
$representantes_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$representantes_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var representantes_view = new ew_Page("representantes_view");
representantes_view.PageID = "view"; // Page ID
var EW_PAGE_ID = representantes_view.PageID; // For backward compatibility

// Form object
var frepresentantesview = new ew_Form("frepresentantesview");

// Form_CustomValidate event
frepresentantesview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frepresentantesview.ValidateRequired = true;
<?php } else { ?>
frepresentantesview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frepresentantesview.Lists["x_id_afiliado"] = {"LinkField":"x_id_afiliado","Ajax":true,"AutoFill":false,"DisplayFields":["x_apell_afiliado","x_nomb_afiliado","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<div class="ewViewExportOptions">
<?php $representantes_view->ExportOptions->Render("body") ?>
<?php if (!$representantes_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($representantes_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php $representantes_view->ShowPageHeader(); ?>
<?php
$representantes_view->ShowMessage();
?>
<form name="frepresentantesview" id="frepresentantesview" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="representantes">
<table class="ewGrid"><tr><td>
<table id="tbl_representantesview" class="table table-bordered table-striped">
<?php if ($representantes->id_representante->Visible) { // id_representante ?>
	<tr id="r_id_representante">
		<td><span id="elh_representantes_id_representante"><?php echo $representantes->id_representante->FldCaption() ?></span></td>
		<td<?php echo $representantes->id_representante->CellAttributes() ?>>
<span id="el_representantes_id_representante" class="control-group">
<span<?php echo $representantes->id_representante->ViewAttributes() ?>>
<?php echo $representantes->id_representante->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($representantes->id_afiliado->Visible) { // id_afiliado ?>
	<tr id="r_id_afiliado">
		<td><span id="elh_representantes_id_afiliado"><?php echo $representantes->id_afiliado->FldCaption() ?></span></td>
		<td<?php echo $representantes->id_afiliado->CellAttributes() ?>>
<span id="el_representantes_id_afiliado" class="control-group">
<span<?php echo $representantes->id_afiliado->ViewAttributes() ?>>
<?php echo $representantes->id_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($representantes->dociden_repres->Visible) { // dociden_repres ?>
	<tr id="r_dociden_repres">
		<td><span id="elh_representantes_dociden_repres"><?php echo $representantes->dociden_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->dociden_repres->CellAttributes() ?>>
<span id="el_representantes_dociden_repres" class="control-group">
<span<?php echo $representantes->dociden_repres->ViewAttributes() ?>>
<?php echo $representantes->dociden_repres->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($representantes->apell_repres->Visible) { // apell_repres ?>
	<tr id="r_apell_repres">
		<td><span id="elh_representantes_apell_repres"><?php echo $representantes->apell_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->apell_repres->CellAttributes() ?>>
<span id="el_representantes_apell_repres" class="control-group">
<span<?php echo $representantes->apell_repres->ViewAttributes() ?>>
<?php echo $representantes->apell_repres->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($representantes->nomb_repres->Visible) { // nomb_repres ?>
	<tr id="r_nomb_repres">
		<td><span id="elh_representantes_nomb_repres"><?php echo $representantes->nomb_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->nomb_repres->CellAttributes() ?>>
<span id="el_representantes_nomb_repres" class="control-group">
<span<?php echo $representantes->nomb_repres->ViewAttributes() ?>>
<?php echo $representantes->nomb_repres->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($representantes->telf_resi_repres->Visible) { // telf_resi_repres ?>
	<tr id="r_telf_resi_repres">
		<td><span id="elh_representantes_telf_resi_repres"><?php echo $representantes->telf_resi_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->telf_resi_repres->CellAttributes() ?>>
<span id="el_representantes_telf_resi_repres" class="control-group">
<span<?php echo $representantes->telf_resi_repres->ViewAttributes() ?>>
<?php echo $representantes->telf_resi_repres->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($representantes->email_repres->Visible) { // email_repres ?>
	<tr id="r_email_repres">
		<td><span id="elh_representantes_email_repres"><?php echo $representantes->email_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->email_repres->CellAttributes() ?>>
<span id="el_representantes_email_repres" class="control-group">
<span<?php echo $representantes->email_repres->ViewAttributes() ?>>
<?php echo $representantes->email_repres->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($representantes->par_repres->Visible) { // par_repres ?>
	<tr id="r_par_repres">
		<td><span id="elh_representantes_par_repres"><?php echo $representantes->par_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->par_repres->CellAttributes() ?>>
<span id="el_representantes_par_repres" class="control-group">
<span<?php echo $representantes->par_repres->ViewAttributes() ?>>
<?php echo $representantes->par_repres->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($representantes->cel_repres->Visible) { // cel_repres ?>
	<tr id="r_cel_repres">
		<td><span id="elh_representantes_cel_repres"><?php echo $representantes->cel_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->cel_repres->CellAttributes() ?>>
<span id="el_representantes_cel_repres" class="control-group">
<span<?php echo $representantes->cel_repres->ViewAttributes() ?>>
<?php echo $representantes->cel_repres->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($representantes->contact_e_repres->Visible) { // contact_e_repres ?>
	<tr id="r_contact_e_repres">
		<td><span id="elh_representantes_contact_e_repres"><?php echo $representantes->contact_e_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->contact_e_repres->CellAttributes() ?>>
<span id="el_representantes_contact_e_repres" class="control-group">
<span<?php echo $representantes->contact_e_repres->ViewAttributes() ?>>
<?php echo $representantes->contact_e_repres->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($representantes->contact_d_repres->Visible) { // contact_d_repres ?>
	<tr id="r_contact_d_repres">
		<td><span id="elh_representantes_contact_d_repres"><?php echo $representantes->contact_d_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->contact_d_repres->CellAttributes() ?>>
<span id="el_representantes_contact_d_repres" class="control-group">
<span<?php echo $representantes->contact_d_repres->ViewAttributes() ?>>
<?php echo $representantes->contact_d_repres->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($representantes->st_repres->Visible) { // st_repres ?>
	<tr id="r_st_repres">
		<td><span id="elh_representantes_st_repres"><?php echo $representantes->st_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->st_repres->CellAttributes() ?>>
<span id="el_representantes_st_repres" class="control-group">
<span<?php echo $representantes->st_repres->ViewAttributes() ?>>
<?php echo $representantes->st_repres->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
frepresentantesview.Init();
</script>
<?php
$representantes_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$representantes_view->Page_Terminate();
?>
