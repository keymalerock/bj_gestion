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

$afiliado_list = NULL; // Initialize page object first

class cafiliado_list extends cafiliado {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'afiliado';

	// Page object name
	var $PageObjName = 'afiliado_list';

	// Grid form hidden field names
	var $FormName = 'fafiliadolist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "afiliadoadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "afiliadodelete.php";
		$this->MultiUpdateUrl = "afiliadoupdate.php";

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'afiliado', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("login.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Setup other options
		$this->SetupOtherOptions();

		// Set "checkbox" visible
		if (count($this->CustomActions) > 0)
			$this->ListOptions->Items["checkbox"]->Visible = TRUE;
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 35;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset
			if ($this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 35; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);
		if ($sFilter == "") {
			$sFilter = "0=101";
			$this->SearchWhere = $sFilter;
		}

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id_afiliado->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_afiliado->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->dociden_afiliado, FALSE); // dociden_afiliado
		$this->BuildSearchSql($sWhere, $this->apell_afiliado, FALSE); // apell_afiliado
		$this->BuildSearchSql($sWhere, $this->nomb_afiliado, FALSE); // nomb_afiliado
		$this->BuildSearchSql($sWhere, $this->email_afiliado, FALSE); // email_afiliado
		$this->BuildSearchSql($sWhere, $this->cel_afiliado, FALSE); // cel_afiliado
		$this->BuildSearchSql($sWhere, $this->genero_afiliado, FALSE); // genero_afiliado
		$this->BuildSearchSql($sWhere, $this->fe_afiliado, FALSE); // fe_afiliado
		$this->BuildSearchSql($sWhere, $this->talla_afiliado, FALSE); // talla_afiliado
		$this->BuildSearchSql($sWhere, $this->peso_afiliado, FALSE); // peso_afiliado
		$this->BuildSearchSql($sWhere, $this->altu_afiliado, FALSE); // altu_afiliado
		$this->BuildSearchSql($sWhere, $this->localresdi_afiliado, FALSE); // localresdi_afiliado
		$this->BuildSearchSql($sWhere, $this->telf_fijo_afiliado, FALSE); // telf_fijo_afiliado
		$this->BuildSearchSql($sWhere, $this->st_afiliado, FALSE); // st_afiliado
		$this->BuildSearchSql($sWhere, $this->st_notificado, FALSE); // st_notificado

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->dociden_afiliado->AdvancedSearch->Save(); // dociden_afiliado
			$this->apell_afiliado->AdvancedSearch->Save(); // apell_afiliado
			$this->nomb_afiliado->AdvancedSearch->Save(); // nomb_afiliado
			$this->email_afiliado->AdvancedSearch->Save(); // email_afiliado
			$this->cel_afiliado->AdvancedSearch->Save(); // cel_afiliado
			$this->genero_afiliado->AdvancedSearch->Save(); // genero_afiliado
			$this->fe_afiliado->AdvancedSearch->Save(); // fe_afiliado
			$this->talla_afiliado->AdvancedSearch->Save(); // talla_afiliado
			$this->peso_afiliado->AdvancedSearch->Save(); // peso_afiliado
			$this->altu_afiliado->AdvancedSearch->Save(); // altu_afiliado
			$this->localresdi_afiliado->AdvancedSearch->Save(); // localresdi_afiliado
			$this->telf_fijo_afiliado->AdvancedSearch->Save(); // telf_fijo_afiliado
			$this->st_afiliado->AdvancedSearch->Save(); // st_afiliado
			$this->st_notificado->AdvancedSearch->Save(); // st_notificado
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Check if search parm exists
	function CheckSearchParms() {
		if ($this->dociden_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->apell_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nomb_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->email_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->cel_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->genero_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fe_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->talla_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->peso_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->altu_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->localresdi_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->telf_fijo_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->st_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->st_notificado->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->dociden_afiliado->AdvancedSearch->UnsetSession();
		$this->apell_afiliado->AdvancedSearch->UnsetSession();
		$this->nomb_afiliado->AdvancedSearch->UnsetSession();
		$this->email_afiliado->AdvancedSearch->UnsetSession();
		$this->cel_afiliado->AdvancedSearch->UnsetSession();
		$this->genero_afiliado->AdvancedSearch->UnsetSession();
		$this->fe_afiliado->AdvancedSearch->UnsetSession();
		$this->talla_afiliado->AdvancedSearch->UnsetSession();
		$this->peso_afiliado->AdvancedSearch->UnsetSession();
		$this->altu_afiliado->AdvancedSearch->UnsetSession();
		$this->localresdi_afiliado->AdvancedSearch->UnsetSession();
		$this->telf_fijo_afiliado->AdvancedSearch->UnsetSession();
		$this->st_afiliado->AdvancedSearch->UnsetSession();
		$this->st_notificado->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->dociden_afiliado->AdvancedSearch->Load();
		$this->apell_afiliado->AdvancedSearch->Load();
		$this->nomb_afiliado->AdvancedSearch->Load();
		$this->email_afiliado->AdvancedSearch->Load();
		$this->cel_afiliado->AdvancedSearch->Load();
		$this->genero_afiliado->AdvancedSearch->Load();
		$this->fe_afiliado->AdvancedSearch->Load();
		$this->talla_afiliado->AdvancedSearch->Load();
		$this->peso_afiliado->AdvancedSearch->Load();
		$this->altu_afiliado->AdvancedSearch->Load();
		$this->localresdi_afiliado->AdvancedSearch->Load();
		$this->telf_fijo_afiliado->AdvancedSearch->Load();
		$this->st_afiliado->AdvancedSearch->Load();
		$this->st_notificado->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->dociden_afiliado); // dociden_afiliado
			$this->UpdateSort($this->apell_afiliado); // apell_afiliado
			$this->UpdateSort($this->nomb_afiliado); // nomb_afiliado
			$this->UpdateSort($this->email_afiliado); // email_afiliado
			$this->UpdateSort($this->cel_afiliado); // cel_afiliado
			$this->UpdateSort($this->genero_afiliado); // genero_afiliado
			$this->UpdateSort($this->fe_afiliado); // fe_afiliado
			$this->UpdateSort($this->telf_fijo_afiliado); // telf_fijo_afiliado
			$this->UpdateSort($this->st_afiliado); // st_afiliado
			$this->UpdateSort($this->st_notificado); // st_notificado
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->dociden_afiliado->setSort("");
				$this->apell_afiliado->setSort("");
				$this->nomb_afiliado->setSort("");
				$this->email_afiliado->setSort("");
				$this->cel_afiliado->setSort("");
				$this->genero_afiliado->setSort("");
				$this->fe_afiliado->setSort("");
				$this->telf_fijo_afiliado->setSort("");
				$this->st_afiliado->setSort("");
				$this->st_notificado->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "detail_historial"
		$item = &$this->ListOptions->Add("detail_historial");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'historial') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["historial_grid"])) $GLOBALS["historial_grid"] = new chistorial_grid;

		// "detail_matricula"
		$item = &$this->ListOptions->Add("detail_matricula");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'matricula') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["matricula_grid"])) $GLOBALS["matricula_grid"] = new cmatricula_grid;

		// "detail_representantes"
		$item = &$this->ListOptions->Add("detail_representantes");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'representantes') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["representantes_grid"])) $GLOBALS["representantes_grid"] = new crepresentantes_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = TRUE;
			$item->ShowInButtonGroup = FALSE;
		}

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = TRUE;
		$item->Header = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\"></label>";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		$this->ListOptions->ButtonClass = "btn-small"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_historial"
		$oListOpt = &$this->ListOptions->Items["detail_historial"];
		if ($Security->AllowList(CurrentProjectID() . 'historial')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("historial", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("historiallist.php?" . EW_TABLE_SHOW_MASTER . "=afiliado&id_afiliado=" . strval($this->id_afiliado->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["historial_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'historial')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=historial")) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "historial";
			}
			if ($GLOBALS["historial_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'historial')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=historial")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "historial";
			}
			if ($links <> "") {
				$body .= "<button class=\"btn btn-small dropdown-toggle\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_matricula"
		$oListOpt = &$this->ListOptions->Items["detail_matricula"];
		if ($Security->AllowList(CurrentProjectID() . 'matricula')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("matricula", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("matriculalist.php?" . EW_TABLE_SHOW_MASTER . "=afiliado&id_afiliado=" . strval($this->id_afiliado->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["matricula_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'matricula')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=matricula")) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "matricula";
			}
			if ($GLOBALS["matricula_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'matricula')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=matricula")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "matricula";
			}
			if ($links <> "") {
				$body .= "<button class=\"btn btn-small dropdown-toggle\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_representantes"
		$oListOpt = &$this->ListOptions->Items["detail_representantes"];
		if ($Security->AllowList(CurrentProjectID() . 'representantes')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("representantes", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("representanteslist.php?" . EW_TABLE_SHOW_MASTER . "=afiliado&id_afiliado=" . strval($this->id_afiliado->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["representantes_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'representantes')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=representantes")) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "representantes";
			}
			if ($GLOBALS["representantes_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'representantes')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=representantes")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "representantes";
			}
			if ($links <> "") {
				$body .= "<button class=\"btn btn-small dropdown-toggle\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">" .
				"<a class=\"btn btn-small ewRowLink ewDetailView\" data-action=\"list\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . $body . "</a>";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . $Language->Phrase("MasterDetailCopyLink") . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"btn btn-small dropdown-toggle\" data-toggle=\"dropdown\">&nbsp;<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_afiliado->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_historial");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=historial") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["historial"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["historial"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'historial') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "historial";
		}
		$item = &$option->Add("detailadd_matricula");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=matricula") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["matricula"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["matricula"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'matricula') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "matricula";
		}
		$item = &$option->Add("detailadd_representantes");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=representantes") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["representantes"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["representantes"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'representantes') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "representantes";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink) . "\">" . $Language->Phrase("AddMasterDetailLink") . "</a>";
			$item->Visible = ($DetailTableLink <> "" && $Security->CanAdd());

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-small"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fafiliadolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// dociden_afiliado

		$this->dociden_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dociden_afiliado"]);
		if ($this->dociden_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dociden_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_dociden_afiliado"];

		// apell_afiliado
		$this->apell_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_apell_afiliado"]);
		if ($this->apell_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->apell_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_apell_afiliado"];

		// nomb_afiliado
		$this->nomb_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nomb_afiliado"]);
		if ($this->nomb_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nomb_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_nomb_afiliado"];

		// email_afiliado
		$this->email_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_email_afiliado"]);
		if ($this->email_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->email_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_email_afiliado"];

		// cel_afiliado
		$this->cel_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_cel_afiliado"]);
		if ($this->cel_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->cel_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_cel_afiliado"];

		// genero_afiliado
		$this->genero_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_genero_afiliado"]);
		if ($this->genero_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->genero_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_genero_afiliado"];

		// fe_afiliado
		$this->fe_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fe_afiliado"]);
		if ($this->fe_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fe_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_fe_afiliado"];

		// talla_afiliado
		$this->talla_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_talla_afiliado"]);
		if ($this->talla_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->talla_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_talla_afiliado"];

		// peso_afiliado
		$this->peso_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_peso_afiliado"]);
		if ($this->peso_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->peso_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_peso_afiliado"];

		// altu_afiliado
		$this->altu_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_altu_afiliado"]);
		if ($this->altu_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->altu_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_altu_afiliado"];

		// localresdi_afiliado
		$this->localresdi_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_localresdi_afiliado"]);
		if ($this->localresdi_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->localresdi_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_localresdi_afiliado"];

		// telf_fijo_afiliado
		$this->telf_fijo_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_telf_fijo_afiliado"]);
		if ($this->telf_fijo_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->telf_fijo_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_telf_fijo_afiliado"];

		// st_afiliado
		$this->st_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_st_afiliado"]);
		if ($this->st_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->st_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_st_afiliado"];

		// st_notificado
		$this->st_notificado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_st_notificado"]);
		if ($this->st_notificado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->st_notificado->AdvancedSearch->SearchOperator = @$_GET["z_st_notificado"];
	}

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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_afiliado")) <> "")
			$this->id_afiliado->CurrentValue = $this->getKey("id_afiliado"); // id_afiliado
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_afiliado

		$this->id_afiliado->CellCssStyle = "white-space: nowrap;";

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

			// telf_fijo_afiliado
			$this->telf_fijo_afiliado->LinkCustomAttributes = "";
			$this->telf_fijo_afiliado->HrefValue = "";
			$this->telf_fijo_afiliado->TooltipValue = "";

			// st_afiliado
			$this->st_afiliado->LinkCustomAttributes = "";
			$this->st_afiliado->HrefValue = "";
			$this->st_afiliado->TooltipValue = "";

			// st_notificado
			$this->st_notificado->LinkCustomAttributes = "";
			$this->st_notificado->HrefValue = "";
			$this->st_notificado->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// dociden_afiliado
			$this->dociden_afiliado->EditCustomAttributes = "";
			$this->dociden_afiliado->EditValue = ew_HtmlEncode($this->dociden_afiliado->AdvancedSearch->SearchValue);
			$this->dociden_afiliado->PlaceHolder = ew_RemoveHtml($this->dociden_afiliado->FldCaption());

			// apell_afiliado
			$this->apell_afiliado->EditCustomAttributes = "";
			$this->apell_afiliado->EditValue = ew_HtmlEncode($this->apell_afiliado->AdvancedSearch->SearchValue);
			$this->apell_afiliado->PlaceHolder = ew_RemoveHtml($this->apell_afiliado->FldCaption());

			// nomb_afiliado
			$this->nomb_afiliado->EditCustomAttributes = "";
			$this->nomb_afiliado->EditValue = ew_HtmlEncode($this->nomb_afiliado->AdvancedSearch->SearchValue);
			$this->nomb_afiliado->PlaceHolder = ew_RemoveHtml($this->nomb_afiliado->FldCaption());

			// email_afiliado
			$this->email_afiliado->EditCustomAttributes = "";
			$this->email_afiliado->EditValue = ew_HtmlEncode($this->email_afiliado->AdvancedSearch->SearchValue);
			$this->email_afiliado->PlaceHolder = ew_RemoveHtml($this->email_afiliado->FldCaption());

			// cel_afiliado
			$this->cel_afiliado->EditCustomAttributes = "";
			$this->cel_afiliado->EditValue = ew_HtmlEncode($this->cel_afiliado->AdvancedSearch->SearchValue);
			$this->cel_afiliado->PlaceHolder = ew_RemoveHtml($this->cel_afiliado->FldCaption());

			// genero_afiliado
			$this->genero_afiliado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->genero_afiliado->FldTagValue(1), $this->genero_afiliado->FldTagCaption(1) <> "" ? $this->genero_afiliado->FldTagCaption(1) : $this->genero_afiliado->FldTagValue(1));
			$arwrk[] = array($this->genero_afiliado->FldTagValue(2), $this->genero_afiliado->FldTagCaption(2) <> "" ? $this->genero_afiliado->FldTagCaption(2) : $this->genero_afiliado->FldTagValue(2));
			$this->genero_afiliado->EditValue = $arwrk;

			// fe_afiliado
			$this->fe_afiliado->EditCustomAttributes = "";
			$this->fe_afiliado->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fe_afiliado->AdvancedSearch->SearchValue, 5), 5));
			$this->fe_afiliado->PlaceHolder = ew_RemoveHtml($this->fe_afiliado->FldCaption());

			// telf_fijo_afiliado
			$this->telf_fijo_afiliado->EditCustomAttributes = "";
			$this->telf_fijo_afiliado->EditValue = ew_HtmlEncode($this->telf_fijo_afiliado->AdvancedSearch->SearchValue);
			$this->telf_fijo_afiliado->PlaceHolder = ew_RemoveHtml($this->telf_fijo_afiliado->FldCaption());

			// st_afiliado
			$this->st_afiliado->EditCustomAttributes = "";
			$this->st_afiliado->EditValue = ew_HtmlEncode($this->st_afiliado->AdvancedSearch->SearchValue);
			$this->st_afiliado->PlaceHolder = ew_RemoveHtml($this->st_afiliado->FldCaption());

			// st_notificado
			$this->st_notificado->EditCustomAttributes = "";
			$this->st_notificado->EditValue = ew_HtmlEncode($this->st_notificado->AdvancedSearch->SearchValue);
			$this->st_notificado->PlaceHolder = ew_RemoveHtml($this->st_notificado->FldCaption());
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->dociden_afiliado->AdvancedSearch->Load();
		$this->apell_afiliado->AdvancedSearch->Load();
		$this->nomb_afiliado->AdvancedSearch->Load();
		$this->email_afiliado->AdvancedSearch->Load();
		$this->cel_afiliado->AdvancedSearch->Load();
		$this->genero_afiliado->AdvancedSearch->Load();
		$this->fe_afiliado->AdvancedSearch->Load();
		$this->talla_afiliado->AdvancedSearch->Load();
		$this->peso_afiliado->AdvancedSearch->Load();
		$this->altu_afiliado->AdvancedSearch->Load();
		$this->localresdi_afiliado->AdvancedSearch->Load();
		$this->telf_fijo_afiliado->AdvancedSearch->Load();
		$this->st_afiliado->AdvancedSearch->Load();
		$this->st_notificado->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, $this->TableVar, TRUE);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($afiliado_list)) $afiliado_list = new cafiliado_list();

// Page init
$afiliado_list->Page_Init();

// Page main
$afiliado_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$afiliado_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var afiliado_list = new ew_Page("afiliado_list");
afiliado_list.PageID = "list"; // Page ID
var EW_PAGE_ID = afiliado_list.PageID; // For backward compatibility

// Form object
var fafiliadolist = new ew_Form("fafiliadolist");
fafiliadolist.FormKeyCountName = '<?php echo $afiliado_list->FormKeyCountName ?>';

// Form_CustomValidate event
fafiliadolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fafiliadolist.ValidateRequired = true;
<?php } else { ?>
fafiliadolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fafiliadolistsrch = new ew_Form("fafiliadolistsrch");

// Validate function for search
fafiliadolistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fafiliadolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fafiliadolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fafiliadolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php if ($afiliado_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $afiliado_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$afiliado_list->TotalRecs = $afiliado->SelectRecordCount();
	} else {
		if ($afiliado_list->Recordset = $afiliado_list->LoadRecordset())
			$afiliado_list->TotalRecs = $afiliado_list->Recordset->RecordCount();
	}
	$afiliado_list->StartRec = 1;
	if ($afiliado_list->DisplayRecs <= 0 || ($afiliado->Export <> "" && $afiliado->ExportAll)) // Display all records
		$afiliado_list->DisplayRecs = $afiliado_list->TotalRecs;
	if (!($afiliado->Export <> "" && $afiliado->ExportAll))
		$afiliado_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$afiliado_list->Recordset = $afiliado_list->LoadRecordset($afiliado_list->StartRec-1, $afiliado_list->DisplayRecs);
$afiliado_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($afiliado->Export == "" && $afiliado->CurrentAction == "") { ?>
<form name="fafiliadolistsrch" id="fafiliadolistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<div class="accordion ewDisplayTable ewSearchTable" id="fafiliadolistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fafiliadolistsrch_SearchGroup" href="#fafiliadolistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fafiliadolistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fafiliadolistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="afiliado">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$afiliado_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$afiliado->RowType = EW_ROWTYPE_SEARCH;

// Render row
$afiliado->ResetAttrs();
$afiliado_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($afiliado->dociden_afiliado->Visible) { // dociden_afiliado ?>
	<span id="xsc_dociden_afiliado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $afiliado->dociden_afiliado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_dociden_afiliado" id="z_dociden_afiliado" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_dociden_afiliado" name="x_dociden_afiliado" id="x_dociden_afiliado" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($afiliado->dociden_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->dociden_afiliado->EditValue ?>"<?php echo $afiliado->dociden_afiliado->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($afiliado->apell_afiliado->Visible) { // apell_afiliado ?>
	<span id="xsc_apell_afiliado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $afiliado->apell_afiliado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apell_afiliado" id="z_apell_afiliado" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_apell_afiliado" name="x_apell_afiliado" id="x_apell_afiliado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($afiliado->apell_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->apell_afiliado->EditValue ?>"<?php echo $afiliado->apell_afiliado->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($afiliado->nomb_afiliado->Visible) { // nomb_afiliado ?>
	<span id="xsc_nomb_afiliado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $afiliado->nomb_afiliado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nomb_afiliado" id="z_nomb_afiliado" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_nomb_afiliado" name="x_nomb_afiliado" id="x_nomb_afiliado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($afiliado->nomb_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->nomb_afiliado->EditValue ?>"<?php echo $afiliado->nomb_afiliado->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($afiliado->email_afiliado->Visible) { // email_afiliado ?>
	<span id="xsc_email_afiliado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $afiliado->email_afiliado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_email_afiliado" id="z_email_afiliado" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_email_afiliado" name="x_email_afiliado" id="x_email_afiliado" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($afiliado->email_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->email_afiliado->EditValue ?>"<?php echo $afiliado->email_afiliado->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($afiliado->cel_afiliado->Visible) { // cel_afiliado ?>
	<span id="xsc_cel_afiliado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $afiliado->cel_afiliado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_cel_afiliado" id="z_cel_afiliado" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_cel_afiliado" name="x_cel_afiliado" id="x_cel_afiliado" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($afiliado->cel_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->cel_afiliado->EditValue ?>"<?php echo $afiliado->cel_afiliado->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($afiliado->st_afiliado->Visible) { // st_afiliado ?>
	<span id="xsc_st_afiliado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $afiliado->st_afiliado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_st_afiliado" id="z_st_afiliado" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_st_afiliado" name="x_st_afiliado" id="x_st_afiliado" size="30" placeholder="<?php echo ew_HtmlEncode($afiliado->st_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->st_afiliado->EditValue ?>"<?php echo $afiliado->st_afiliado->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $afiliado_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ResetSearch") ?></a>
	</div>
</div>
</div>
</div>
			</div>
		</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $afiliado_list->ShowPageHeader(); ?>
<?php
$afiliado_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<form name="fafiliadolist" id="fafiliadolist" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="afiliado">
<div id="gmp_afiliado" class="ewGridMiddlePanel">
<?php if ($afiliado_list->TotalRecs > 0) { ?>
<table id="tbl_afiliadolist" class="ewTable ewTableSeparate">
<?php echo $afiliado->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$afiliado_list->RenderListOptions();

// Render list options (header, left)
$afiliado_list->ListOptions->Render("header", "left");
?>
<?php if ($afiliado->dociden_afiliado->Visible) { // dociden_afiliado ?>
	<?php if ($afiliado->SortUrl($afiliado->dociden_afiliado) == "") { ?>
		<td><div id="elh_afiliado_dociden_afiliado" class="afiliado_dociden_afiliado"><div class="ewTableHeaderCaption"><?php echo $afiliado->dociden_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $afiliado->SortUrl($afiliado->dociden_afiliado) ?>',1);"><div id="elh_afiliado_dociden_afiliado" class="afiliado_dociden_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $afiliado->dociden_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($afiliado->dociden_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($afiliado->dociden_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($afiliado->apell_afiliado->Visible) { // apell_afiliado ?>
	<?php if ($afiliado->SortUrl($afiliado->apell_afiliado) == "") { ?>
		<td><div id="elh_afiliado_apell_afiliado" class="afiliado_apell_afiliado"><div class="ewTableHeaderCaption"><?php echo $afiliado->apell_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $afiliado->SortUrl($afiliado->apell_afiliado) ?>',1);"><div id="elh_afiliado_apell_afiliado" class="afiliado_apell_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $afiliado->apell_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($afiliado->apell_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($afiliado->apell_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($afiliado->nomb_afiliado->Visible) { // nomb_afiliado ?>
	<?php if ($afiliado->SortUrl($afiliado->nomb_afiliado) == "") { ?>
		<td><div id="elh_afiliado_nomb_afiliado" class="afiliado_nomb_afiliado"><div class="ewTableHeaderCaption"><?php echo $afiliado->nomb_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $afiliado->SortUrl($afiliado->nomb_afiliado) ?>',1);"><div id="elh_afiliado_nomb_afiliado" class="afiliado_nomb_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $afiliado->nomb_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($afiliado->nomb_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($afiliado->nomb_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($afiliado->email_afiliado->Visible) { // email_afiliado ?>
	<?php if ($afiliado->SortUrl($afiliado->email_afiliado) == "") { ?>
		<td><div id="elh_afiliado_email_afiliado" class="afiliado_email_afiliado"><div class="ewTableHeaderCaption"><?php echo $afiliado->email_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $afiliado->SortUrl($afiliado->email_afiliado) ?>',1);"><div id="elh_afiliado_email_afiliado" class="afiliado_email_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $afiliado->email_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($afiliado->email_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($afiliado->email_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($afiliado->cel_afiliado->Visible) { // cel_afiliado ?>
	<?php if ($afiliado->SortUrl($afiliado->cel_afiliado) == "") { ?>
		<td><div id="elh_afiliado_cel_afiliado" class="afiliado_cel_afiliado"><div class="ewTableHeaderCaption"><?php echo $afiliado->cel_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $afiliado->SortUrl($afiliado->cel_afiliado) ?>',1);"><div id="elh_afiliado_cel_afiliado" class="afiliado_cel_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $afiliado->cel_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($afiliado->cel_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($afiliado->cel_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($afiliado->genero_afiliado->Visible) { // genero_afiliado ?>
	<?php if ($afiliado->SortUrl($afiliado->genero_afiliado) == "") { ?>
		<td><div id="elh_afiliado_genero_afiliado" class="afiliado_genero_afiliado"><div class="ewTableHeaderCaption"><?php echo $afiliado->genero_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $afiliado->SortUrl($afiliado->genero_afiliado) ?>',1);"><div id="elh_afiliado_genero_afiliado" class="afiliado_genero_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $afiliado->genero_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($afiliado->genero_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($afiliado->genero_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($afiliado->fe_afiliado->Visible) { // fe_afiliado ?>
	<?php if ($afiliado->SortUrl($afiliado->fe_afiliado) == "") { ?>
		<td><div id="elh_afiliado_fe_afiliado" class="afiliado_fe_afiliado"><div class="ewTableHeaderCaption"><?php echo $afiliado->fe_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $afiliado->SortUrl($afiliado->fe_afiliado) ?>',1);"><div id="elh_afiliado_fe_afiliado" class="afiliado_fe_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $afiliado->fe_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($afiliado->fe_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($afiliado->fe_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($afiliado->telf_fijo_afiliado->Visible) { // telf_fijo_afiliado ?>
	<?php if ($afiliado->SortUrl($afiliado->telf_fijo_afiliado) == "") { ?>
		<td><div id="elh_afiliado_telf_fijo_afiliado" class="afiliado_telf_fijo_afiliado"><div class="ewTableHeaderCaption"><?php echo $afiliado->telf_fijo_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $afiliado->SortUrl($afiliado->telf_fijo_afiliado) ?>',1);"><div id="elh_afiliado_telf_fijo_afiliado" class="afiliado_telf_fijo_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $afiliado->telf_fijo_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($afiliado->telf_fijo_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($afiliado->telf_fijo_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($afiliado->st_afiliado->Visible) { // st_afiliado ?>
	<?php if ($afiliado->SortUrl($afiliado->st_afiliado) == "") { ?>
		<td><div id="elh_afiliado_st_afiliado" class="afiliado_st_afiliado"><div class="ewTableHeaderCaption"><?php echo $afiliado->st_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $afiliado->SortUrl($afiliado->st_afiliado) ?>',1);"><div id="elh_afiliado_st_afiliado" class="afiliado_st_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $afiliado->st_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($afiliado->st_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($afiliado->st_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($afiliado->st_notificado->Visible) { // st_notificado ?>
	<?php if ($afiliado->SortUrl($afiliado->st_notificado) == "") { ?>
		<td><div id="elh_afiliado_st_notificado" class="afiliado_st_notificado"><div class="ewTableHeaderCaption"><?php echo $afiliado->st_notificado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $afiliado->SortUrl($afiliado->st_notificado) ?>',1);"><div id="elh_afiliado_st_notificado" class="afiliado_st_notificado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $afiliado->st_notificado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($afiliado->st_notificado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($afiliado->st_notificado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$afiliado_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($afiliado->ExportAll && $afiliado->Export <> "") {
	$afiliado_list->StopRec = $afiliado_list->TotalRecs;
} else {

	// Set the last record to display
	if ($afiliado_list->TotalRecs > $afiliado_list->StartRec + $afiliado_list->DisplayRecs - 1)
		$afiliado_list->StopRec = $afiliado_list->StartRec + $afiliado_list->DisplayRecs - 1;
	else
		$afiliado_list->StopRec = $afiliado_list->TotalRecs;
}
$afiliado_list->RecCnt = $afiliado_list->StartRec - 1;
if ($afiliado_list->Recordset && !$afiliado_list->Recordset->EOF) {
	$afiliado_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $afiliado_list->StartRec > 1)
		$afiliado_list->Recordset->Move($afiliado_list->StartRec - 1);
} elseif (!$afiliado->AllowAddDeleteRow && $afiliado_list->StopRec == 0) {
	$afiliado_list->StopRec = $afiliado->GridAddRowCount;
}

// Initialize aggregate
$afiliado->RowType = EW_ROWTYPE_AGGREGATEINIT;
$afiliado->ResetAttrs();
$afiliado_list->RenderRow();
while ($afiliado_list->RecCnt < $afiliado_list->StopRec) {
	$afiliado_list->RecCnt++;
	if (intval($afiliado_list->RecCnt) >= intval($afiliado_list->StartRec)) {
		$afiliado_list->RowCnt++;

		// Set up key count
		$afiliado_list->KeyCount = $afiliado_list->RowIndex;

		// Init row class and style
		$afiliado->ResetAttrs();
		$afiliado->CssClass = "";
		if ($afiliado->CurrentAction == "gridadd") {
		} else {
			$afiliado_list->LoadRowValues($afiliado_list->Recordset); // Load row values
		}
		$afiliado->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$afiliado->RowAttrs = array_merge($afiliado->RowAttrs, array('data-rowindex'=>$afiliado_list->RowCnt, 'id'=>'r' . $afiliado_list->RowCnt . '_afiliado', 'data-rowtype'=>$afiliado->RowType));

		// Render row
		$afiliado_list->RenderRow();

		// Render list options
		$afiliado_list->RenderListOptions();
?>
	<tr<?php echo $afiliado->RowAttributes() ?>>
<?php

// Render list options (body, left)
$afiliado_list->ListOptions->Render("body", "left", $afiliado_list->RowCnt);
?>
	<?php if ($afiliado->dociden_afiliado->Visible) { // dociden_afiliado ?>
		<td<?php echo $afiliado->dociden_afiliado->CellAttributes() ?>>
<span<?php echo $afiliado->dociden_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->dociden_afiliado->ListViewValue() ?></span>
<a id="<?php echo $afiliado_list->PageObjName . "_row_" . $afiliado_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($afiliado->apell_afiliado->Visible) { // apell_afiliado ?>
		<td<?php echo $afiliado->apell_afiliado->CellAttributes() ?>>
<span<?php echo $afiliado->apell_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->apell_afiliado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($afiliado->nomb_afiliado->Visible) { // nomb_afiliado ?>
		<td<?php echo $afiliado->nomb_afiliado->CellAttributes() ?>>
<span<?php echo $afiliado->nomb_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->nomb_afiliado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($afiliado->email_afiliado->Visible) { // email_afiliado ?>
		<td<?php echo $afiliado->email_afiliado->CellAttributes() ?>>
<span<?php echo $afiliado->email_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->email_afiliado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($afiliado->cel_afiliado->Visible) { // cel_afiliado ?>
		<td<?php echo $afiliado->cel_afiliado->CellAttributes() ?>>
<span<?php echo $afiliado->cel_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->cel_afiliado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($afiliado->genero_afiliado->Visible) { // genero_afiliado ?>
		<td<?php echo $afiliado->genero_afiliado->CellAttributes() ?>>
<span<?php echo $afiliado->genero_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->genero_afiliado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($afiliado->fe_afiliado->Visible) { // fe_afiliado ?>
		<td<?php echo $afiliado->fe_afiliado->CellAttributes() ?>>
<span<?php echo $afiliado->fe_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->fe_afiliado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($afiliado->telf_fijo_afiliado->Visible) { // telf_fijo_afiliado ?>
		<td<?php echo $afiliado->telf_fijo_afiliado->CellAttributes() ?>>
<span<?php echo $afiliado->telf_fijo_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->telf_fijo_afiliado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($afiliado->st_afiliado->Visible) { // st_afiliado ?>
		<td<?php echo $afiliado->st_afiliado->CellAttributes() ?>>
<span<?php echo $afiliado->st_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->st_afiliado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($afiliado->st_notificado->Visible) { // st_notificado ?>
		<td<?php echo $afiliado->st_notificado->CellAttributes() ?>>
<span<?php echo $afiliado->st_notificado->ViewAttributes() ?>>
<?php echo $afiliado->st_notificado->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$afiliado_list->ListOptions->Render("body", "right", $afiliado_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($afiliado->CurrentAction <> "gridadd")
		$afiliado_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($afiliado->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($afiliado_list->Recordset)
	$afiliado_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($afiliado->CurrentAction <> "gridadd" && $afiliado->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($afiliado_list->Pager)) $afiliado_list->Pager = new cPrevNextPager($afiliado_list->StartRec, $afiliado_list->DisplayRecs, $afiliado_list->TotalRecs) ?>
<?php if ($afiliado_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($afiliado_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $afiliado_list->PageUrl() ?>start=<?php echo $afiliado_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($afiliado_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $afiliado_list->PageUrl() ?>start=<?php echo $afiliado_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $afiliado_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($afiliado_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $afiliado_list->PageUrl() ?>start=<?php echo $afiliado_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($afiliado_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $afiliado_list->PageUrl() ?>start=<?php echo $afiliado_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $afiliado_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $afiliado_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $afiliado_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $afiliado_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($afiliado_list->SearchWhere == "0=101") { ?>
	<p><?php echo $Language->Phrase("EnterSearchCriteria") ?></p>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
	<?php } ?>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoPermission") ?></p>
	<?php } ?>
<?php } ?>
</td>
</tr></table>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($afiliado_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
</td></tr></table>
<script type="text/javascript">
fafiliadolistsrch.Init();
fafiliadolist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$afiliado_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$afiliado_list->Page_Terminate();
?>
