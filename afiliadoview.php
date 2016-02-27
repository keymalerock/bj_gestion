<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "afiliadoinfo.php" ?>
<?php include_once "v_usuariosinfo.php" ?>
<?php include_once "historialgridcls.php" ?>
<?php include_once "matriculagridcls.php" ?>
<?php include_once "representantesgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$afiliado_view = NULL; // Initialize page object first

class cafiliado_view extends cafiliado {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'afiliado';

	// Page object name
	var $PageObjName = 'afiliado_view';

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

		// Table object (afiliado)
		if (!isset($GLOBALS["afiliado"]) || get_class($GLOBALS["afiliado"]) == "cafiliado") {
			$GLOBALS["afiliado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["afiliado"];
		}
		$KeyUrl = "";
		if (@$_GET["id_afiliado"] <> "") {
			$this->RecKey["id_afiliado"] = $_GET["id_afiliado"];
			$KeyUrl .= "&amp;id_afiliado=" . urlencode($this->RecKey["id_afiliado"]);
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
			define("EW_TABLE_NAME", 'afiliado', TRUE);

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
			$this->Page_Terminate("afiliadolist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id_afiliado->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["id_afiliado"] <> "") {
				$this->id_afiliado->setQueryStringValue($_GET["id_afiliado"]);
				$this->RecKey["id_afiliado"] = $this->id_afiliado->QueryStringValue;
			} else {
				$sReturnUrl = "afiliadolist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "afiliadolist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "afiliadolist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();

		// Set up detail parameters
		$this->SetUpDetailParms();
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
		$DetailTableLink = "";
		$option = &$options["detail"];

		// Detail table 'historial'
		$body = $Language->TablePhrase("historial", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("historiallist.php?" . EW_TABLE_SHOW_MASTER . "=afiliado&id_afiliado=" . strval($this->id_afiliado->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_historial");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'historial');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "historial";
		}

		// Detail table 'matricula'
		$body = $Language->TablePhrase("matricula", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("matriculalist.php?" . EW_TABLE_SHOW_MASTER . "=afiliado&id_afiliado=" . strval($this->id_afiliado->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_matricula");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'matricula');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "matricula";
		}

		// Detail table 'representantes'
		$body = $Language->TablePhrase("representantes", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("representanteslist.php?" . EW_TABLE_SHOW_MASTER . "=afiliado&id_afiliado=" . strval($this->id_afiliado->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_representantes");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'representantes');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "representantes";
		}

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<a class=\"ewAction ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink)) . "\">" . $body . "</a>";
			$item = &$option->Add("details");
			$item->Body = $body;
			$item->Visible = ($DetailTableLink <> "");

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detail_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}

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
		$this->id_afiliado->setDbValue($rs->fields('id_afiliado'));
		$this->dociden_afiliado->setDbValue($rs->fields('dociden_afiliado'));
		$this->apell_afiliado->setDbValue($rs->fields('apell_afiliado'));
		$this->nomb_afiliado->setDbValue($rs->fields('nomb_afiliado'));
		$this->direcc_afiliado->setDbValue($rs->fields('direcc_afiliado'));
		$this->email_afiliado->setDbValue($rs->fields('email_afiliado'));
		$this->cel_afiliado->setDbValue($rs->fields('cel_afiliado'));
		$this->genero_afiliado->setDbValue($rs->fields('genero_afiliado'));
		$this->fe_afiliado->setDbValue($rs->fields('fe_afiliado'));
		$this->telemerg_afiliado->setDbValue($rs->fields('telemerg_afiliado'));
		$this->talla_afiliado->setDbValue($rs->fields('talla_afiliado'));
		$this->peso_afiliado->setDbValue($rs->fields('peso_afiliado'));
		$this->altu_afiliado->setDbValue($rs->fields('altu_afiliado'));
		$this->localresdi_afiliado->setDbValue($rs->fields('localresdi_afiliado'));
		$this->telf_fijo_afiliado->setDbValue($rs->fields('telf_fijo_afiliado'));
		$this->coleg_afiliado->setDbValue($rs->fields('coleg_afiliado'));
		$this->seguro_afiliado->setDbValue($rs->fields('seguro_afiliado'));
		$this->tiposangre_afiliado->setDbValue($rs->fields('tiposangre_afiliado'));
		$this->contacto_afiliado->setDbValue($rs->fields('contacto_afiliado'));
		$this->st_afiliado->setDbValue($rs->fields('st_afiliado'));
		$this->foto_afiliado->Upload->DbValue = $rs->fields('foto_afiliado');
		$this->foto_afiliado->CurrentValue = $this->foto_afiliado->Upload->DbValue;
		$this->st_notificado->setDbValue($rs->fields('st_notificado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_afiliado->DbValue = $row['id_afiliado'];
		$this->dociden_afiliado->DbValue = $row['dociden_afiliado'];
		$this->apell_afiliado->DbValue = $row['apell_afiliado'];
		$this->nomb_afiliado->DbValue = $row['nomb_afiliado'];
		$this->direcc_afiliado->DbValue = $row['direcc_afiliado'];
		$this->email_afiliado->DbValue = $row['email_afiliado'];
		$this->cel_afiliado->DbValue = $row['cel_afiliado'];
		$this->genero_afiliado->DbValue = $row['genero_afiliado'];
		$this->fe_afiliado->DbValue = $row['fe_afiliado'];
		$this->telemerg_afiliado->DbValue = $row['telemerg_afiliado'];
		$this->talla_afiliado->DbValue = $row['talla_afiliado'];
		$this->peso_afiliado->DbValue = $row['peso_afiliado'];
		$this->altu_afiliado->DbValue = $row['altu_afiliado'];
		$this->localresdi_afiliado->DbValue = $row['localresdi_afiliado'];
		$this->telf_fijo_afiliado->DbValue = $row['telf_fijo_afiliado'];
		$this->coleg_afiliado->DbValue = $row['coleg_afiliado'];
		$this->seguro_afiliado->DbValue = $row['seguro_afiliado'];
		$this->tiposangre_afiliado->DbValue = $row['tiposangre_afiliado'];
		$this->contacto_afiliado->DbValue = $row['contacto_afiliado'];
		$this->st_afiliado->DbValue = $row['st_afiliado'];
		$this->foto_afiliado->Upload->DbValue = $row['foto_afiliado'];
		$this->st_notificado->DbValue = $row['st_notificado'];
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

		// Convert decimal values if posted back
		if ($this->altu_afiliado->FormValue == $this->altu_afiliado->CurrentValue && is_numeric(ew_StrToFloat($this->altu_afiliado->CurrentValue)))
			$this->altu_afiliado->CurrentValue = ew_StrToFloat($this->altu_afiliado->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_afiliado
		// dociden_afiliado
		// apell_afiliado
		// nomb_afiliado
		// direcc_afiliado
		// email_afiliado
		// cel_afiliado
		// genero_afiliado
		// fe_afiliado
		// telemerg_afiliado
		// talla_afiliado
		// peso_afiliado
		// altu_afiliado
		// localresdi_afiliado
		// telf_fijo_afiliado
		// coleg_afiliado
		// seguro_afiliado
		// tiposangre_afiliado
		// contacto_afiliado
		// st_afiliado
		// foto_afiliado
		// st_notificado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_afiliado
			$this->id_afiliado->ViewValue = $this->id_afiliado->CurrentValue;
			$this->id_afiliado->ViewCustomAttributes = "";

			// dociden_afiliado
			$this->dociden_afiliado->ViewValue = $this->dociden_afiliado->CurrentValue;
			$this->dociden_afiliado->ViewCustomAttributes = "";

			// apell_afiliado
			$this->apell_afiliado->ViewValue = $this->apell_afiliado->CurrentValue;
			$this->apell_afiliado->ViewCustomAttributes = "";

			// nomb_afiliado
			$this->nomb_afiliado->ViewValue = $this->nomb_afiliado->CurrentValue;
			$this->nomb_afiliado->ViewCustomAttributes = "";

			// direcc_afiliado
			$this->direcc_afiliado->ViewValue = $this->direcc_afiliado->CurrentValue;
			$this->direcc_afiliado->ViewCustomAttributes = "";

			// email_afiliado
			$this->email_afiliado->ViewValue = $this->email_afiliado->CurrentValue;
			$this->email_afiliado->ViewCustomAttributes = "";

			// cel_afiliado
			$this->cel_afiliado->ViewValue = $this->cel_afiliado->CurrentValue;
			$this->cel_afiliado->ViewCustomAttributes = "";

			// genero_afiliado
			if (strval($this->genero_afiliado->CurrentValue) <> "") {
				switch ($this->genero_afiliado->CurrentValue) {
					case $this->genero_afiliado->FldTagValue(1):
						$this->genero_afiliado->ViewValue = $this->genero_afiliado->FldTagCaption(1) <> "" ? $this->genero_afiliado->FldTagCaption(1) : $this->genero_afiliado->CurrentValue;
						break;
					case $this->genero_afiliado->FldTagValue(2):
						$this->genero_afiliado->ViewValue = $this->genero_afiliado->FldTagCaption(2) <> "" ? $this->genero_afiliado->FldTagCaption(2) : $this->genero_afiliado->CurrentValue;
						break;
					default:
						$this->genero_afiliado->ViewValue = $this->genero_afiliado->CurrentValue;
				}
			} else {
				$this->genero_afiliado->ViewValue = NULL;
			}
			$this->genero_afiliado->ViewCustomAttributes = "";

			// fe_afiliado
			$this->fe_afiliado->ViewValue = $this->fe_afiliado->CurrentValue;
			$this->fe_afiliado->ViewValue = ew_FormatDateTime($this->fe_afiliado->ViewValue, 5);
			$this->fe_afiliado->ViewCustomAttributes = "";

			// telemerg_afiliado
			$this->telemerg_afiliado->ViewValue = $this->telemerg_afiliado->CurrentValue;
			$this->telemerg_afiliado->ViewCustomAttributes = "";

			// talla_afiliado
			$this->talla_afiliado->ViewValue = $this->talla_afiliado->CurrentValue;
			$this->talla_afiliado->ViewCustomAttributes = "";

			// peso_afiliado
			$this->peso_afiliado->ViewValue = $this->peso_afiliado->CurrentValue;
			$this->peso_afiliado->ViewCustomAttributes = "";

			// altu_afiliado
			$this->altu_afiliado->ViewValue = $this->altu_afiliado->CurrentValue;
			$this->altu_afiliado->ViewCustomAttributes = "";

			// localresdi_afiliado
			$this->localresdi_afiliado->ViewValue = $this->localresdi_afiliado->CurrentValue;
			$this->localresdi_afiliado->ViewCustomAttributes = "";

			// telf_fijo_afiliado
			$this->telf_fijo_afiliado->ViewValue = $this->telf_fijo_afiliado->CurrentValue;
			$this->telf_fijo_afiliado->ViewCustomAttributes = "";

			// coleg_afiliado
			$this->coleg_afiliado->ViewValue = $this->coleg_afiliado->CurrentValue;
			$this->coleg_afiliado->ViewCustomAttributes = "";

			// seguro_afiliado
			$this->seguro_afiliado->ViewValue = $this->seguro_afiliado->CurrentValue;
			$this->seguro_afiliado->ViewCustomAttributes = "";

			// tiposangre_afiliado
			$this->tiposangre_afiliado->ViewValue = $this->tiposangre_afiliado->CurrentValue;
			$this->tiposangre_afiliado->ViewCustomAttributes = "";

			// contacto_afiliado
			$this->contacto_afiliado->ViewValue = $this->contacto_afiliado->CurrentValue;
			$this->contacto_afiliado->ViewCustomAttributes = "";

			// st_afiliado
			$this->st_afiliado->ViewValue = $this->st_afiliado->CurrentValue;
			$this->st_afiliado->ViewCustomAttributes = "";

			// foto_afiliado
			if (!ew_Empty($this->foto_afiliado->Upload->DbValue)) {
				$this->foto_afiliado->ImageWidth = 200;
				$this->foto_afiliado->ImageHeight = 200;
				$this->foto_afiliado->ImageAlt = $this->foto_afiliado->FldAlt();
				$this->foto_afiliado->ViewValue = ew_UploadPathEx(FALSE, $this->foto_afiliado->UploadPath) . $this->foto_afiliado->Upload->DbValue;
			} else {
				$this->foto_afiliado->ViewValue = "";
			}
			$this->foto_afiliado->ViewCustomAttributes = "";

			// st_notificado
			$this->st_notificado->ViewValue = $this->st_notificado->CurrentValue;
			$this->st_notificado->ViewCustomAttributes = "";

			// id_afiliado
			$this->id_afiliado->LinkCustomAttributes = "";
			$this->id_afiliado->HrefValue = "";
			$this->id_afiliado->TooltipValue = "";

			// dociden_afiliado
			$this->dociden_afiliado->LinkCustomAttributes = "";
			$this->dociden_afiliado->HrefValue = "";
			$this->dociden_afiliado->TooltipValue = "";

			// apell_afiliado
			$this->apell_afiliado->LinkCustomAttributes = "";
			$this->apell_afiliado->HrefValue = "";
			$this->apell_afiliado->TooltipValue = "";

			// nomb_afiliado
			$this->nomb_afiliado->LinkCustomAttributes = "";
			$this->nomb_afiliado->HrefValue = "";
			$this->nomb_afiliado->TooltipValue = "";

			// direcc_afiliado
			$this->direcc_afiliado->LinkCustomAttributes = "";
			$this->direcc_afiliado->HrefValue = "";
			$this->direcc_afiliado->TooltipValue = "";

			// email_afiliado
			$this->email_afiliado->LinkCustomAttributes = "";
			$this->email_afiliado->HrefValue = "";
			$this->email_afiliado->TooltipValue = "";

			// cel_afiliado
			$this->cel_afiliado->LinkCustomAttributes = "";
			$this->cel_afiliado->HrefValue = "";
			$this->cel_afiliado->TooltipValue = "";

			// genero_afiliado
			$this->genero_afiliado->LinkCustomAttributes = "";
			$this->genero_afiliado->HrefValue = "";
			$this->genero_afiliado->TooltipValue = "";

			// fe_afiliado
			$this->fe_afiliado->LinkCustomAttributes = "";
			$this->fe_afiliado->HrefValue = "";
			$this->fe_afiliado->TooltipValue = "";

			// telemerg_afiliado
			$this->telemerg_afiliado->LinkCustomAttributes = "";
			$this->telemerg_afiliado->HrefValue = "";
			$this->telemerg_afiliado->TooltipValue = "";

			// talla_afiliado
			$this->talla_afiliado->LinkCustomAttributes = "";
			$this->talla_afiliado->HrefValue = "";
			$this->talla_afiliado->TooltipValue = "";

			// peso_afiliado
			$this->peso_afiliado->LinkCustomAttributes = "";
			$this->peso_afiliado->HrefValue = "";
			$this->peso_afiliado->TooltipValue = "";

			// altu_afiliado
			$this->altu_afiliado->LinkCustomAttributes = "";
			$this->altu_afiliado->HrefValue = "";
			$this->altu_afiliado->TooltipValue = "";

			// localresdi_afiliado
			$this->localresdi_afiliado->LinkCustomAttributes = "";
			$this->localresdi_afiliado->HrefValue = "";
			$this->localresdi_afiliado->TooltipValue = "";

			// telf_fijo_afiliado
			$this->telf_fijo_afiliado->LinkCustomAttributes = "";
			$this->telf_fijo_afiliado->HrefValue = "";
			$this->telf_fijo_afiliado->TooltipValue = "";

			// coleg_afiliado
			$this->coleg_afiliado->LinkCustomAttributes = "";
			$this->coleg_afiliado->HrefValue = "";
			$this->coleg_afiliado->TooltipValue = "";

			// seguro_afiliado
			$this->seguro_afiliado->LinkCustomAttributes = "";
			$this->seguro_afiliado->HrefValue = "";
			$this->seguro_afiliado->TooltipValue = "";

			// tiposangre_afiliado
			$this->tiposangre_afiliado->LinkCustomAttributes = "";
			$this->tiposangre_afiliado->HrefValue = "";
			$this->tiposangre_afiliado->TooltipValue = "";

			// contacto_afiliado
			$this->contacto_afiliado->LinkCustomAttributes = "";
			$this->contacto_afiliado->HrefValue = "";
			$this->contacto_afiliado->TooltipValue = "";

			// st_afiliado
			$this->st_afiliado->LinkCustomAttributes = "";
			$this->st_afiliado->HrefValue = "";
			$this->st_afiliado->TooltipValue = "";

			// foto_afiliado
			$this->foto_afiliado->LinkCustomAttributes = "";
			if (!ew_Empty($this->foto_afiliado->Upload->DbValue)) {
				$this->foto_afiliado->HrefValue = ew_UploadPathEx(FALSE, $this->foto_afiliado->UploadPath) . $this->foto_afiliado->Upload->DbValue; // Add prefix/suffix
				$this->foto_afiliado->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->foto_afiliado->HrefValue = ew_ConvertFullUrl($this->foto_afiliado->HrefValue);
			} else {
				$this->foto_afiliado->HrefValue = "";
			}
			$this->foto_afiliado->HrefValue2 = $this->foto_afiliado->UploadPath . $this->foto_afiliado->Upload->DbValue;
			$this->foto_afiliado->TooltipValue = $this->foto_afiliado->ViewValue;
			if ($this->foto_afiliado->HrefValue == "") $this->foto_afiliado->HrefValue = "javascript:void(0);";
			$this->foto_afiliado->LinkAttrs["class"] = "ewTooltipLink";
			$this->foto_afiliado->LinkAttrs["data-tooltip-id"] = "tt_afiliado_x_foto_afiliado";
			$this->foto_afiliado->LinkAttrs["data-tooltip-width"] = $this->foto_afiliado->TooltipWidth;
			$this->foto_afiliado->LinkAttrs["data-placement"] = "right";

			// st_notificado
			$this->st_notificado->LinkCustomAttributes = "";
			$this->st_notificado->HrefValue = "";
			$this->st_notificado->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("historial", $DetailTblVar)) {
				if (!isset($GLOBALS["historial_grid"]))
					$GLOBALS["historial_grid"] = new chistorial_grid;
				if ($GLOBALS["historial_grid"]->DetailView) {
					$GLOBALS["historial_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["historial_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["historial_grid"]->setStartRecordNumber(1);
					$GLOBALS["historial_grid"]->id_afiliado->FldIsDetailKey = TRUE;
					$GLOBALS["historial_grid"]->id_afiliado->CurrentValue = $this->id_afiliado->CurrentValue;
					$GLOBALS["historial_grid"]->id_afiliado->setSessionValue($GLOBALS["historial_grid"]->id_afiliado->CurrentValue);
				}
			}
			if (in_array("matricula", $DetailTblVar)) {
				if (!isset($GLOBALS["matricula_grid"]))
					$GLOBALS["matricula_grid"] = new cmatricula_grid;
				if ($GLOBALS["matricula_grid"]->DetailView) {
					$GLOBALS["matricula_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["matricula_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["matricula_grid"]->setStartRecordNumber(1);
					$GLOBALS["matricula_grid"]->id_afiliado->FldIsDetailKey = TRUE;
					$GLOBALS["matricula_grid"]->id_afiliado->CurrentValue = $this->id_afiliado->CurrentValue;
					$GLOBALS["matricula_grid"]->id_afiliado->setSessionValue($GLOBALS["matricula_grid"]->id_afiliado->CurrentValue);
				}
			}
			if (in_array("representantes", $DetailTblVar)) {
				if (!isset($GLOBALS["representantes_grid"]))
					$GLOBALS["representantes_grid"] = new crepresentantes_grid;
				if ($GLOBALS["representantes_grid"]->DetailView) {
					$GLOBALS["representantes_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["representantes_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["representantes_grid"]->setStartRecordNumber(1);
					$GLOBALS["representantes_grid"]->id_afiliado->FldIsDetailKey = TRUE;
					$GLOBALS["representantes_grid"]->id_afiliado->CurrentValue = $this->id_afiliado->CurrentValue;
					$GLOBALS["representantes_grid"]->id_afiliado->setSessionValue($GLOBALS["representantes_grid"]->id_afiliado->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "afiliadolist.php", $this->TableVar, TRUE);
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
if (!isset($afiliado_view)) $afiliado_view = new cafiliado_view();

// Page init
$afiliado_view->Page_Init();

// Page main
$afiliado_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$afiliado_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var afiliado_view = new ew_Page("afiliado_view");
afiliado_view.PageID = "view"; // Page ID
var EW_PAGE_ID = afiliado_view.PageID; // For backward compatibility

// Form object
var fafiliadoview = new ew_Form("fafiliadoview");

// Form_CustomValidate event
fafiliadoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fafiliadoview.ValidateRequired = true;
<?php } else { ?>
fafiliadoview.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fafiliadoview.MultiPage = new ew_MultiPage("fafiliadoview",
	[["x_id_afiliado",1],["x_dociden_afiliado",1],["x_apell_afiliado",1],["x_nomb_afiliado",1],["x_direcc_afiliado",1],["x_email_afiliado",1],["x_cel_afiliado",1],["x_genero_afiliado",1],["x_fe_afiliado",1],["x_telemerg_afiliado",2],["x_talla_afiliado",2],["x_peso_afiliado",2],["x_altu_afiliado",2],["x_localresdi_afiliado",2],["x_telf_fijo_afiliado",1],["x_coleg_afiliado",2],["x_seguro_afiliado",2],["x_tiposangre_afiliado",2],["x_contacto_afiliado",2],["x_foto_afiliado",1]]
);

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<div class="ewViewExportOptions">
<?php $afiliado_view->ExportOptions->Render("body") ?>
<?php if (!$afiliado_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($afiliado_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php $afiliado_view->ShowPageHeader(); ?>
<?php
$afiliado_view->ShowMessage();
?>
<form name="fafiliadoview" id="fafiliadoview" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="afiliado">
<?php if ($afiliado->Export == "") { ?>
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="afiliado_view">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_afiliado1" data-toggle="tab"><?php echo $afiliado->PageCaption(1) ?></a></li>
		<li><a href="#tab_afiliado2" data-toggle="tab"><?php echo $afiliado->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
<?php if ($afiliado->Export == "") { ?>
		<div class="tab-pane active" id="tab_afiliado1">
<?php } ?>
<table class="ewGrid"<?php if ($afiliado->Export == "") echo " style=\"width: 100%\""; ?>><tr><td>
<table id="tbl_afiliadoview1" class="table table-bordered table-striped">
<?php if ($afiliado->id_afiliado->Visible) { // id_afiliado ?>
	<tr id="r_id_afiliado">
		<td><span id="elh_afiliado_id_afiliado"><?php echo $afiliado->id_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->id_afiliado->CellAttributes() ?>>
<span id="el_afiliado_id_afiliado" class="control-group">
<span<?php echo $afiliado->id_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->id_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->dociden_afiliado->Visible) { // dociden_afiliado ?>
	<tr id="r_dociden_afiliado">
		<td><span id="elh_afiliado_dociden_afiliado"><?php echo $afiliado->dociden_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->dociden_afiliado->CellAttributes() ?>>
<span id="el_afiliado_dociden_afiliado" class="control-group">
<span<?php echo $afiliado->dociden_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->dociden_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->apell_afiliado->Visible) { // apell_afiliado ?>
	<tr id="r_apell_afiliado">
		<td><span id="elh_afiliado_apell_afiliado"><?php echo $afiliado->apell_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->apell_afiliado->CellAttributes() ?>>
<span id="el_afiliado_apell_afiliado" class="control-group">
<span<?php echo $afiliado->apell_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->apell_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->nomb_afiliado->Visible) { // nomb_afiliado ?>
	<tr id="r_nomb_afiliado">
		<td><span id="elh_afiliado_nomb_afiliado"><?php echo $afiliado->nomb_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->nomb_afiliado->CellAttributes() ?>>
<span id="el_afiliado_nomb_afiliado" class="control-group">
<span<?php echo $afiliado->nomb_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->nomb_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->direcc_afiliado->Visible) { // direcc_afiliado ?>
	<tr id="r_direcc_afiliado">
		<td><span id="elh_afiliado_direcc_afiliado"><?php echo $afiliado->direcc_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->direcc_afiliado->CellAttributes() ?>>
<span id="el_afiliado_direcc_afiliado" class="control-group">
<span<?php echo $afiliado->direcc_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->direcc_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->email_afiliado->Visible) { // email_afiliado ?>
	<tr id="r_email_afiliado">
		<td><span id="elh_afiliado_email_afiliado"><?php echo $afiliado->email_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->email_afiliado->CellAttributes() ?>>
<span id="el_afiliado_email_afiliado" class="control-group">
<span<?php echo $afiliado->email_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->email_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->cel_afiliado->Visible) { // cel_afiliado ?>
	<tr id="r_cel_afiliado">
		<td><span id="elh_afiliado_cel_afiliado"><?php echo $afiliado->cel_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->cel_afiliado->CellAttributes() ?>>
<span id="el_afiliado_cel_afiliado" class="control-group">
<span<?php echo $afiliado->cel_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->cel_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->genero_afiliado->Visible) { // genero_afiliado ?>
	<tr id="r_genero_afiliado">
		<td><span id="elh_afiliado_genero_afiliado"><?php echo $afiliado->genero_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->genero_afiliado->CellAttributes() ?>>
<span id="el_afiliado_genero_afiliado" class="control-group">
<span<?php echo $afiliado->genero_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->genero_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->fe_afiliado->Visible) { // fe_afiliado ?>
	<tr id="r_fe_afiliado">
		<td><span id="elh_afiliado_fe_afiliado"><?php echo $afiliado->fe_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->fe_afiliado->CellAttributes() ?>>
<span id="el_afiliado_fe_afiliado" class="control-group">
<span<?php echo $afiliado->fe_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->fe_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->telf_fijo_afiliado->Visible) { // telf_fijo_afiliado ?>
	<tr id="r_telf_fijo_afiliado">
		<td><span id="elh_afiliado_telf_fijo_afiliado"><?php echo $afiliado->telf_fijo_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->telf_fijo_afiliado->CellAttributes() ?>>
<span id="el_afiliado_telf_fijo_afiliado" class="control-group">
<span<?php echo $afiliado->telf_fijo_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->telf_fijo_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->st_afiliado->Visible) { // st_afiliado ?>
	<tr id="r_st_afiliado">
		<td><span id="elh_afiliado_st_afiliado"><?php echo $afiliado->st_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->st_afiliado->CellAttributes() ?>>
<span id="el_afiliado_st_afiliado" class="control-group">
<span<?php echo $afiliado->st_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->st_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->foto_afiliado->Visible) { // foto_afiliado ?>
	<tr id="r_foto_afiliado">
		<td><span id="elh_afiliado_foto_afiliado"><?php echo $afiliado->foto_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->foto_afiliado->CellAttributes() ?>>
<span id="el_afiliado_foto_afiliado" class="control-group">
<span>
<?php if ($afiliado->foto_afiliado->LinkAttributes() <> "") { ?>
<?php if (!empty($afiliado->foto_afiliado->Upload->DbValue)) { ?>
<a<?php echo $afiliado->foto_afiliado->LinkAttributes() ?>><?php echo ew_GetFileViewTag($afiliado->foto_afiliado, $afiliado->foto_afiliado->ViewValue) ?></a>
<?php } elseif (!in_array($afiliado->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($afiliado->foto_afiliado->Upload->DbValue)) { ?>
<?php echo ew_GetFileViewTag($afiliado->foto_afiliado, $afiliado->foto_afiliado->ViewValue) ?>
<?php } elseif (!in_array($afiliado->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
<div id="tt_afiliado_x_foto_afiliado" style="display: none">
<?php echo ew_GetFileViewTag($afiliado->foto_afiliado, $afiliado->foto_afiliado->TooltipValue) ?>
</div>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->st_notificado->Visible) { // st_notificado ?>
	<tr id="r_st_notificado">
		<td><span id="elh_afiliado_st_notificado"><?php echo $afiliado->st_notificado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->st_notificado->CellAttributes() ?>>
<span id="el_afiliado_st_notificado" class="control-group">
<span<?php echo $afiliado->st_notificado->ViewAttributes() ?>>
<?php echo $afiliado->st_notificado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php if ($afiliado->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($afiliado->Export == "") { ?>
		<div class="tab-pane" id="tab_afiliado2">
<?php } ?>
<table class="ewGrid"<?php if ($afiliado->Export == "") echo " style=\"width: 100%\""; ?>><tr><td>
<table id="tbl_afiliadoview2" class="table table-bordered table-striped">
<?php if ($afiliado->telemerg_afiliado->Visible) { // telemerg_afiliado ?>
	<tr id="r_telemerg_afiliado">
		<td><span id="elh_afiliado_telemerg_afiliado"><?php echo $afiliado->telemerg_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->telemerg_afiliado->CellAttributes() ?>>
<span id="el_afiliado_telemerg_afiliado" class="control-group">
<span<?php echo $afiliado->telemerg_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->telemerg_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->talla_afiliado->Visible) { // talla_afiliado ?>
	<tr id="r_talla_afiliado">
		<td><span id="elh_afiliado_talla_afiliado"><?php echo $afiliado->talla_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->talla_afiliado->CellAttributes() ?>>
<span id="el_afiliado_talla_afiliado" class="control-group">
<span<?php echo $afiliado->talla_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->talla_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->peso_afiliado->Visible) { // peso_afiliado ?>
	<tr id="r_peso_afiliado">
		<td><span id="elh_afiliado_peso_afiliado"><?php echo $afiliado->peso_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->peso_afiliado->CellAttributes() ?>>
<span id="el_afiliado_peso_afiliado" class="control-group">
<span<?php echo $afiliado->peso_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->peso_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->altu_afiliado->Visible) { // altu_afiliado ?>
	<tr id="r_altu_afiliado">
		<td><span id="elh_afiliado_altu_afiliado"><?php echo $afiliado->altu_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->altu_afiliado->CellAttributes() ?>>
<span id="el_afiliado_altu_afiliado" class="control-group">
<span<?php echo $afiliado->altu_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->altu_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->localresdi_afiliado->Visible) { // localresdi_afiliado ?>
	<tr id="r_localresdi_afiliado">
		<td><span id="elh_afiliado_localresdi_afiliado"><?php echo $afiliado->localresdi_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->localresdi_afiliado->CellAttributes() ?>>
<span id="el_afiliado_localresdi_afiliado" class="control-group">
<span<?php echo $afiliado->localresdi_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->localresdi_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->coleg_afiliado->Visible) { // coleg_afiliado ?>
	<tr id="r_coleg_afiliado">
		<td><span id="elh_afiliado_coleg_afiliado"><?php echo $afiliado->coleg_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->coleg_afiliado->CellAttributes() ?>>
<span id="el_afiliado_coleg_afiliado" class="control-group">
<span<?php echo $afiliado->coleg_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->coleg_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->seguro_afiliado->Visible) { // seguro_afiliado ?>
	<tr id="r_seguro_afiliado">
		<td><span id="elh_afiliado_seguro_afiliado"><?php echo $afiliado->seguro_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->seguro_afiliado->CellAttributes() ?>>
<span id="el_afiliado_seguro_afiliado" class="control-group">
<span<?php echo $afiliado->seguro_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->seguro_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->tiposangre_afiliado->Visible) { // tiposangre_afiliado ?>
	<tr id="r_tiposangre_afiliado">
		<td><span id="elh_afiliado_tiposangre_afiliado"><?php echo $afiliado->tiposangre_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->tiposangre_afiliado->CellAttributes() ?>>
<span id="el_afiliado_tiposangre_afiliado" class="control-group">
<span<?php echo $afiliado->tiposangre_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->tiposangre_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($afiliado->contacto_afiliado->Visible) { // contacto_afiliado ?>
	<tr id="r_contacto_afiliado">
		<td><span id="elh_afiliado_contacto_afiliado"><?php echo $afiliado->contacto_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->contacto_afiliado->CellAttributes() ?>>
<span id="el_afiliado_contacto_afiliado" class="control-group">
<span<?php echo $afiliado->contacto_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->contacto_afiliado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php if ($afiliado->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($afiliado->Export == "") { ?>
	</div>
</div>
</td></tr></tbody></table>
<?php } ?>
<?php
	if (in_array("historial", explode(",", $afiliado->getCurrentDetailTable())) && $historial->DetailView) {
?>
<?php include_once "historialgrid.php" ?>
<?php } ?>
<?php
	if (in_array("matricula", explode(",", $afiliado->getCurrentDetailTable())) && $matricula->DetailView) {
?>
<?php include_once "matriculagrid.php" ?>
<?php } ?>
<?php
	if (in_array("representantes", explode(",", $afiliado->getCurrentDetailTable())) && $representantes->DetailView) {
?>
<?php include_once "representantesgrid.php" ?>
<?php } ?>
</form>
<script type="text/javascript">
fafiliadoview.Init();
</script>
<?php
$afiliado_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$afiliado_view->Page_Terminate();
?>
