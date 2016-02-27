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

$empleados_list = NULL; // Initialize page object first

class cempleados_list extends cempleados {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'empleados';

	// Page object name
	var $PageObjName = 'empleados_list';

	// Grid form hidden field names
	var $FormName = 'fempleadoslist';
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

		// Table object (empleados)
		if (!isset($GLOBALS["empleados"]) || get_class($GLOBALS["empleados"]) == "cempleados") {
			$GLOBALS["empleados"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["empleados"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "empleadosadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "empleadosdelete.php";
		$this->MultiUpdateUrl = "empleadosupdate.php";

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'empleados', TRUE);

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
		$this->id_empleado->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->id_empleado->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_empleado->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id_empleado, FALSE); // id_empleado
		$this->BuildSearchSql($sWhere, $this->dociden_empleado, FALSE); // dociden_empleado
		$this->BuildSearchSql($sWhere, $this->nomb_empleado, FALSE); // nomb_empleado
		$this->BuildSearchSql($sWhere, $this->apell_empleado, FALSE); // apell_empleado
		$this->BuildSearchSql($sWhere, $this->telf_empleado, FALSE); // telf_empleado
		$this->BuildSearchSql($sWhere, $this->email_empleado, FALSE); // email_empleado
		$this->BuildSearchSql($sWhere, $this->st_empleado_p, FALSE); // st_empleado_p
		$this->BuildSearchSql($sWhere, $this->id_perfil, FALSE); // id_perfil

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->id_empleado->AdvancedSearch->Save(); // id_empleado
			$this->dociden_empleado->AdvancedSearch->Save(); // dociden_empleado
			$this->nomb_empleado->AdvancedSearch->Save(); // nomb_empleado
			$this->apell_empleado->AdvancedSearch->Save(); // apell_empleado
			$this->telf_empleado->AdvancedSearch->Save(); // telf_empleado
			$this->email_empleado->AdvancedSearch->Save(); // email_empleado
			$this->st_empleado_p->AdvancedSearch->Save(); // st_empleado_p
			$this->id_perfil->AdvancedSearch->Save(); // id_perfil
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
		if ($this->id_empleado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dociden_empleado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nomb_empleado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->apell_empleado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->telf_empleado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->email_empleado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->st_empleado_p->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_perfil->AdvancedSearch->IssetSession())
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
		$this->id_empleado->AdvancedSearch->UnsetSession();
		$this->dociden_empleado->AdvancedSearch->UnsetSession();
		$this->nomb_empleado->AdvancedSearch->UnsetSession();
		$this->apell_empleado->AdvancedSearch->UnsetSession();
		$this->telf_empleado->AdvancedSearch->UnsetSession();
		$this->email_empleado->AdvancedSearch->UnsetSession();
		$this->st_empleado_p->AdvancedSearch->UnsetSession();
		$this->id_perfil->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->id_empleado->AdvancedSearch->Load();
		$this->dociden_empleado->AdvancedSearch->Load();
		$this->nomb_empleado->AdvancedSearch->Load();
		$this->apell_empleado->AdvancedSearch->Load();
		$this->telf_empleado->AdvancedSearch->Load();
		$this->email_empleado->AdvancedSearch->Load();
		$this->st_empleado_p->AdvancedSearch->Load();
		$this->id_perfil->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_empleado); // id_empleado
			$this->UpdateSort($this->dociden_empleado); // dociden_empleado
			$this->UpdateSort($this->nomb_empleado); // nomb_empleado
			$this->UpdateSort($this->apell_empleado); // apell_empleado
			$this->UpdateSort($this->telf_empleado); // telf_empleado
			$this->UpdateSort($this->email_empleado); // email_empleado
			$this->UpdateSort($this->st_empleado_p); // st_empleado_p
			$this->UpdateSort($this->pass_empleado); // pass_empleado
			$this->UpdateSort($this->login_empleado); // login_empleado
			$this->UpdateSort($this->id_perfil); // id_perfil
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
				$this->id_empleado->setSort("");
				$this->dociden_empleado->setSort("");
				$this->nomb_empleado->setSort("");
				$this->apell_empleado->setSort("");
				$this->telf_empleado->setSort("");
				$this->email_empleado->setSort("");
				$this->st_empleado_p->setSort("");
				$this->pass_empleado->setSort("");
				$this->login_empleado->setSort("");
				$this->id_perfil->setSort("");
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

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_empleado->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fempleadoslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// id_empleado

		$this->id_empleado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_empleado"]);
		if ($this->id_empleado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_empleado->AdvancedSearch->SearchOperator = @$_GET["z_id_empleado"];

		// dociden_empleado
		$this->dociden_empleado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dociden_empleado"]);
		if ($this->dociden_empleado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dociden_empleado->AdvancedSearch->SearchOperator = @$_GET["z_dociden_empleado"];

		// nomb_empleado
		$this->nomb_empleado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nomb_empleado"]);
		if ($this->nomb_empleado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nomb_empleado->AdvancedSearch->SearchOperator = @$_GET["z_nomb_empleado"];

		// apell_empleado
		$this->apell_empleado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_apell_empleado"]);
		if ($this->apell_empleado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->apell_empleado->AdvancedSearch->SearchOperator = @$_GET["z_apell_empleado"];

		// telf_empleado
		$this->telf_empleado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_telf_empleado"]);
		if ($this->telf_empleado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->telf_empleado->AdvancedSearch->SearchOperator = @$_GET["z_telf_empleado"];

		// email_empleado
		$this->email_empleado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_email_empleado"]);
		if ($this->email_empleado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->email_empleado->AdvancedSearch->SearchOperator = @$_GET["z_email_empleado"];

		// st_empleado_p
		$this->st_empleado_p->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_st_empleado_p"]);
		if ($this->st_empleado_p->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->st_empleado_p->AdvancedSearch->SearchOperator = @$_GET["z_st_empleado_p"];

		// id_perfil
		$this->id_perfil->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_perfil"]);
		if ($this->id_perfil->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_perfil->AdvancedSearch->SearchOperator = @$_GET["z_id_perfil"];
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_empleado")) <> "")
			$this->id_empleado->CurrentValue = $this->getKey("id_empleado"); // id_empleado
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
		// id_empleado

		$this->id_empleado->CellCssStyle = "white-space: nowrap;";

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id_empleado
			$this->id_empleado->EditCustomAttributes = "";
			$this->id_empleado->EditValue = ew_HtmlEncode($this->id_empleado->AdvancedSearch->SearchValue);
			$this->id_empleado->PlaceHolder = ew_RemoveHtml($this->id_empleado->FldCaption());

			// dociden_empleado
			$this->dociden_empleado->EditCustomAttributes = "";
			$this->dociden_empleado->EditValue = ew_HtmlEncode($this->dociden_empleado->AdvancedSearch->SearchValue);
			$this->dociden_empleado->PlaceHolder = ew_RemoveHtml($this->dociden_empleado->FldCaption());

			// nomb_empleado
			$this->nomb_empleado->EditCustomAttributes = "";
			$this->nomb_empleado->EditValue = ew_HtmlEncode($this->nomb_empleado->AdvancedSearch->SearchValue);
			$this->nomb_empleado->PlaceHolder = ew_RemoveHtml($this->nomb_empleado->FldCaption());

			// apell_empleado
			$this->apell_empleado->EditCustomAttributes = "";
			$this->apell_empleado->EditValue = ew_HtmlEncode($this->apell_empleado->AdvancedSearch->SearchValue);
			$this->apell_empleado->PlaceHolder = ew_RemoveHtml($this->apell_empleado->FldCaption());

			// telf_empleado
			$this->telf_empleado->EditCustomAttributes = "";
			$this->telf_empleado->EditValue = ew_HtmlEncode($this->telf_empleado->AdvancedSearch->SearchValue);
			$this->telf_empleado->PlaceHolder = ew_RemoveHtml($this->telf_empleado->FldCaption());

			// email_empleado
			$this->email_empleado->EditCustomAttributes = "";
			$this->email_empleado->EditValue = ew_HtmlEncode($this->email_empleado->AdvancedSearch->SearchValue);
			$this->email_empleado->PlaceHolder = ew_RemoveHtml($this->email_empleado->FldCaption());

			// st_empleado_p
			$this->st_empleado_p->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->st_empleado_p->FldTagValue(1), $this->st_empleado_p->FldTagCaption(1) <> "" ? $this->st_empleado_p->FldTagCaption(1) : $this->st_empleado_p->FldTagValue(1));
			$arwrk[] = array($this->st_empleado_p->FldTagValue(2), $this->st_empleado_p->FldTagCaption(2) <> "" ? $this->st_empleado_p->FldTagCaption(2) : $this->st_empleado_p->FldTagValue(2));
			$this->st_empleado_p->EditValue = $arwrk;

			// pass_empleado
			$this->pass_empleado->EditCustomAttributes = "";
			$this->pass_empleado->EditValue = ew_HtmlEncode($this->pass_empleado->AdvancedSearch->SearchValue);

			// login_empleado
			$this->login_empleado->EditCustomAttributes = "";
			$this->login_empleado->EditValue = ew_HtmlEncode($this->login_empleado->AdvancedSearch->SearchValue);
			$this->login_empleado->PlaceHolder = ew_RemoveHtml($this->login_empleado->FldCaption());

			// id_perfil
			$this->id_perfil->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_perfil, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_perfil->EditValue = $arwrk;
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
		$this->id_empleado->AdvancedSearch->Load();
		$this->dociden_empleado->AdvancedSearch->Load();
		$this->nomb_empleado->AdvancedSearch->Load();
		$this->apell_empleado->AdvancedSearch->Load();
		$this->telf_empleado->AdvancedSearch->Load();
		$this->email_empleado->AdvancedSearch->Load();
		$this->st_empleado_p->AdvancedSearch->Load();
		$this->id_perfil->AdvancedSearch->Load();
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
if (!isset($empleados_list)) $empleados_list = new cempleados_list();

// Page init
$empleados_list->Page_Init();

// Page main
$empleados_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empleados_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var empleados_list = new ew_Page("empleados_list");
empleados_list.PageID = "list"; // Page ID
var EW_PAGE_ID = empleados_list.PageID; // For backward compatibility

// Form object
var fempleadoslist = new ew_Form("fempleadoslist");
fempleadoslist.FormKeyCountName = '<?php echo $empleados_list->FormKeyCountName ?>';

// Form_CustomValidate event
fempleadoslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempleadoslist.ValidateRequired = true;
<?php } else { ?>
fempleadoslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fempleadoslist.Lists["x_id_perfil"] = {"LinkField":"x_userlevelid","Ajax":null,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fempleadoslistsrch = new ew_Form("fempleadoslistsrch");

// Validate function for search
fempleadoslistsrch.Validate = function(fobj) {
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
fempleadoslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempleadoslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fempleadoslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fempleadoslistsrch.Lists["x_id_perfil"] = {"LinkField":"x_userlevelid","Ajax":null,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php if ($empleados_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $empleados_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$empleados_list->TotalRecs = $empleados->SelectRecordCount();
	} else {
		if ($empleados_list->Recordset = $empleados_list->LoadRecordset())
			$empleados_list->TotalRecs = $empleados_list->Recordset->RecordCount();
	}
	$empleados_list->StartRec = 1;
	if ($empleados_list->DisplayRecs <= 0 || ($empleados->Export <> "" && $empleados->ExportAll)) // Display all records
		$empleados_list->DisplayRecs = $empleados_list->TotalRecs;
	if (!($empleados->Export <> "" && $empleados->ExportAll))
		$empleados_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$empleados_list->Recordset = $empleados_list->LoadRecordset($empleados_list->StartRec-1, $empleados_list->DisplayRecs);
$empleados_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($empleados->Export == "" && $empleados->CurrentAction == "") { ?>
<form name="fempleadoslistsrch" id="fempleadoslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<div class="accordion ewDisplayTable ewSearchTable" id="fempleadoslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fempleadoslistsrch_SearchGroup" href="#fempleadoslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fempleadoslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fempleadoslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="empleados">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$empleados_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$empleados->RowType = EW_ROWTYPE_SEARCH;

// Render row
$empleados->ResetAttrs();
$empleados_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($empleados->dociden_empleado->Visible) { // dociden_empleado ?>
	<span id="xsc_dociden_empleado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $empleados->dociden_empleado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_dociden_empleado" id="z_dociden_empleado" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_dociden_empleado" name="x_dociden_empleado" id="x_dociden_empleado" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($empleados->dociden_empleado->PlaceHolder) ?>" value="<?php echo $empleados->dociden_empleado->EditValue ?>"<?php echo $empleados->dociden_empleado->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($empleados->nomb_empleado->Visible) { // nomb_empleado ?>
	<span id="xsc_nomb_empleado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $empleados->nomb_empleado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nomb_empleado" id="z_nomb_empleado" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_nomb_empleado" name="x_nomb_empleado" id="x_nomb_empleado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($empleados->nomb_empleado->PlaceHolder) ?>" value="<?php echo $empleados->nomb_empleado->EditValue ?>"<?php echo $empleados->nomb_empleado->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($empleados->apell_empleado->Visible) { // apell_empleado ?>
	<span id="xsc_apell_empleado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $empleados->apell_empleado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apell_empleado" id="z_apell_empleado" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_apell_empleado" name="x_apell_empleado" id="x_apell_empleado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($empleados->apell_empleado->PlaceHolder) ?>" value="<?php echo $empleados->apell_empleado->EditValue ?>"<?php echo $empleados->apell_empleado->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($empleados->telf_empleado->Visible) { // telf_empleado ?>
	<span id="xsc_telf_empleado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $empleados->telf_empleado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_telf_empleado" id="z_telf_empleado" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_telf_empleado" name="x_telf_empleado" id="x_telf_empleado" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($empleados->telf_empleado->PlaceHolder) ?>" value="<?php echo $empleados->telf_empleado->EditValue ?>"<?php echo $empleados->telf_empleado->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($empleados->email_empleado->Visible) { // email_empleado ?>
	<span id="xsc_email_empleado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $empleados->email_empleado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_email_empleado" id="z_email_empleado" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_email_empleado" name="x_email_empleado" id="x_email_empleado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($empleados->email_empleado->PlaceHolder) ?>" value="<?php echo $empleados->email_empleado->EditValue ?>"<?php echo $empleados->email_empleado->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($empleados->st_empleado_p->Visible) { // st_empleado_p ?>
	<span id="xsc_st_empleado_p" class="ewCell">
		<span class="ewSearchCaption"><?php echo $empleados->st_empleado_p->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_st_empleado_p" id="z_st_empleado_p" value="="></span>
		<span class="control-group ewSearchField">
<div id="tp_x_st_empleado_p" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_st_empleado_p" id="x_st_empleado_p" value="{value}"<?php echo $empleados->st_empleado_p->EditAttributes() ?>></div>
<div id="dsl_x_st_empleado_p" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $empleados->st_empleado_p->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($empleados->st_empleado_p->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_st_empleado_p" name="x_st_empleado_p" id="x_st_empleado_p_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $empleados->st_empleado_p->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
<?php if ($empleados->id_perfil->Visible) { // id_perfil ?>
	<span id="xsc_id_perfil" class="ewCell">
		<span class="ewSearchCaption"><?php echo $empleados->id_perfil->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_perfil" id="z_id_perfil" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_id_perfil" id="x_id_perfil" name="x_id_perfil"<?php echo $empleados->id_perfil->EditAttributes() ?>>
<?php
if (is_array($empleados->id_perfil->EditValue)) {
	$arwrk = $empleados->id_perfil->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($empleados->id_perfil->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fempleadoslistsrch.Lists["x_id_perfil"].Options = <?php echo (is_array($empleados->id_perfil->EditValue)) ? ew_ArrayToJson($empleados->id_perfil->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_8" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $empleados_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ResetSearch") ?></a>
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
<?php $empleados_list->ShowPageHeader(); ?>
<?php
$empleados_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<form name="fempleadoslist" id="fempleadoslist" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="empleados">
<div id="gmp_empleados" class="ewGridMiddlePanel">
<?php if ($empleados_list->TotalRecs > 0) { ?>
<table id="tbl_empleadoslist" class="ewTable ewTableSeparate">
<?php echo $empleados->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$empleados_list->RenderListOptions();

// Render list options (header, left)
$empleados_list->ListOptions->Render("header", "left");
?>
<?php if ($empleados->id_empleado->Visible) { // id_empleado ?>
	<?php if ($empleados->SortUrl($empleados->id_empleado) == "") { ?>
		<td><div id="elh_empleados_id_empleado" class="empleados_id_empleado"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $empleados->id_empleado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleados->SortUrl($empleados->id_empleado) ?>',1);"><div id="elh_empleados_id_empleado" class="empleados_id_empleado">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $empleados->id_empleado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleados->id_empleado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleados->id_empleado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($empleados->dociden_empleado->Visible) { // dociden_empleado ?>
	<?php if ($empleados->SortUrl($empleados->dociden_empleado) == "") { ?>
		<td><div id="elh_empleados_dociden_empleado" class="empleados_dociden_empleado"><div class="ewTableHeaderCaption"><?php echo $empleados->dociden_empleado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleados->SortUrl($empleados->dociden_empleado) ?>',1);"><div id="elh_empleados_dociden_empleado" class="empleados_dociden_empleado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleados->dociden_empleado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleados->dociden_empleado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleados->dociden_empleado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($empleados->nomb_empleado->Visible) { // nomb_empleado ?>
	<?php if ($empleados->SortUrl($empleados->nomb_empleado) == "") { ?>
		<td><div id="elh_empleados_nomb_empleado" class="empleados_nomb_empleado"><div class="ewTableHeaderCaption"><?php echo $empleados->nomb_empleado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleados->SortUrl($empleados->nomb_empleado) ?>',1);"><div id="elh_empleados_nomb_empleado" class="empleados_nomb_empleado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleados->nomb_empleado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleados->nomb_empleado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleados->nomb_empleado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($empleados->apell_empleado->Visible) { // apell_empleado ?>
	<?php if ($empleados->SortUrl($empleados->apell_empleado) == "") { ?>
		<td><div id="elh_empleados_apell_empleado" class="empleados_apell_empleado"><div class="ewTableHeaderCaption"><?php echo $empleados->apell_empleado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleados->SortUrl($empleados->apell_empleado) ?>',1);"><div id="elh_empleados_apell_empleado" class="empleados_apell_empleado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleados->apell_empleado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleados->apell_empleado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleados->apell_empleado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($empleados->telf_empleado->Visible) { // telf_empleado ?>
	<?php if ($empleados->SortUrl($empleados->telf_empleado) == "") { ?>
		<td><div id="elh_empleados_telf_empleado" class="empleados_telf_empleado"><div class="ewTableHeaderCaption"><?php echo $empleados->telf_empleado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleados->SortUrl($empleados->telf_empleado) ?>',1);"><div id="elh_empleados_telf_empleado" class="empleados_telf_empleado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleados->telf_empleado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleados->telf_empleado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleados->telf_empleado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($empleados->email_empleado->Visible) { // email_empleado ?>
	<?php if ($empleados->SortUrl($empleados->email_empleado) == "") { ?>
		<td><div id="elh_empleados_email_empleado" class="empleados_email_empleado"><div class="ewTableHeaderCaption"><?php echo $empleados->email_empleado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleados->SortUrl($empleados->email_empleado) ?>',1);"><div id="elh_empleados_email_empleado" class="empleados_email_empleado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleados->email_empleado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleados->email_empleado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleados->email_empleado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($empleados->st_empleado_p->Visible) { // st_empleado_p ?>
	<?php if ($empleados->SortUrl($empleados->st_empleado_p) == "") { ?>
		<td><div id="elh_empleados_st_empleado_p" class="empleados_st_empleado_p"><div class="ewTableHeaderCaption"><?php echo $empleados->st_empleado_p->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleados->SortUrl($empleados->st_empleado_p) ?>',1);"><div id="elh_empleados_st_empleado_p" class="empleados_st_empleado_p">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleados->st_empleado_p->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleados->st_empleado_p->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleados->st_empleado_p->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($empleados->pass_empleado->Visible) { // pass_empleado ?>
	<?php if ($empleados->SortUrl($empleados->pass_empleado) == "") { ?>
		<td><div id="elh_empleados_pass_empleado" class="empleados_pass_empleado"><div class="ewTableHeaderCaption"><?php echo $empleados->pass_empleado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleados->SortUrl($empleados->pass_empleado) ?>',1);"><div id="elh_empleados_pass_empleado" class="empleados_pass_empleado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleados->pass_empleado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleados->pass_empleado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleados->pass_empleado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($empleados->login_empleado->Visible) { // login_empleado ?>
	<?php if ($empleados->SortUrl($empleados->login_empleado) == "") { ?>
		<td><div id="elh_empleados_login_empleado" class="empleados_login_empleado"><div class="ewTableHeaderCaption"><?php echo $empleados->login_empleado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleados->SortUrl($empleados->login_empleado) ?>',1);"><div id="elh_empleados_login_empleado" class="empleados_login_empleado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleados->login_empleado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleados->login_empleado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleados->login_empleado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($empleados->id_perfil->Visible) { // id_perfil ?>
	<?php if ($empleados->SortUrl($empleados->id_perfil) == "") { ?>
		<td><div id="elh_empleados_id_perfil" class="empleados_id_perfil"><div class="ewTableHeaderCaption"><?php echo $empleados->id_perfil->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empleados->SortUrl($empleados->id_perfil) ?>',1);"><div id="elh_empleados_id_perfil" class="empleados_id_perfil">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleados->id_perfil->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleados->id_perfil->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleados->id_perfil->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$empleados_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($empleados->ExportAll && $empleados->Export <> "") {
	$empleados_list->StopRec = $empleados_list->TotalRecs;
} else {

	// Set the last record to display
	if ($empleados_list->TotalRecs > $empleados_list->StartRec + $empleados_list->DisplayRecs - 1)
		$empleados_list->StopRec = $empleados_list->StartRec + $empleados_list->DisplayRecs - 1;
	else
		$empleados_list->StopRec = $empleados_list->TotalRecs;
}
$empleados_list->RecCnt = $empleados_list->StartRec - 1;
if ($empleados_list->Recordset && !$empleados_list->Recordset->EOF) {
	$empleados_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $empleados_list->StartRec > 1)
		$empleados_list->Recordset->Move($empleados_list->StartRec - 1);
} elseif (!$empleados->AllowAddDeleteRow && $empleados_list->StopRec == 0) {
	$empleados_list->StopRec = $empleados->GridAddRowCount;
}

// Initialize aggregate
$empleados->RowType = EW_ROWTYPE_AGGREGATEINIT;
$empleados->ResetAttrs();
$empleados_list->RenderRow();
while ($empleados_list->RecCnt < $empleados_list->StopRec) {
	$empleados_list->RecCnt++;
	if (intval($empleados_list->RecCnt) >= intval($empleados_list->StartRec)) {
		$empleados_list->RowCnt++;

		// Set up key count
		$empleados_list->KeyCount = $empleados_list->RowIndex;

		// Init row class and style
		$empleados->ResetAttrs();
		$empleados->CssClass = "";
		if ($empleados->CurrentAction == "gridadd") {
		} else {
			$empleados_list->LoadRowValues($empleados_list->Recordset); // Load row values
		}
		$empleados->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$empleados->RowAttrs = array_merge($empleados->RowAttrs, array('data-rowindex'=>$empleados_list->RowCnt, 'id'=>'r' . $empleados_list->RowCnt . '_empleados', 'data-rowtype'=>$empleados->RowType));

		// Render row
		$empleados_list->RenderRow();

		// Render list options
		$empleados_list->RenderListOptions();
?>
	<tr<?php echo $empleados->RowAttributes() ?>>
<?php

// Render list options (body, left)
$empleados_list->ListOptions->Render("body", "left", $empleados_list->RowCnt);
?>
	<?php if ($empleados->id_empleado->Visible) { // id_empleado ?>
		<td<?php echo $empleados->id_empleado->CellAttributes() ?>>
<span<?php echo $empleados->id_empleado->ViewAttributes() ?>>
<?php echo $empleados->id_empleado->ListViewValue() ?></span>
<a id="<?php echo $empleados_list->PageObjName . "_row_" . $empleados_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($empleados->dociden_empleado->Visible) { // dociden_empleado ?>
		<td<?php echo $empleados->dociden_empleado->CellAttributes() ?>>
<span<?php echo $empleados->dociden_empleado->ViewAttributes() ?>>
<?php echo $empleados->dociden_empleado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($empleados->nomb_empleado->Visible) { // nomb_empleado ?>
		<td<?php echo $empleados->nomb_empleado->CellAttributes() ?>>
<span<?php echo $empleados->nomb_empleado->ViewAttributes() ?>>
<?php echo $empleados->nomb_empleado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($empleados->apell_empleado->Visible) { // apell_empleado ?>
		<td<?php echo $empleados->apell_empleado->CellAttributes() ?>>
<span<?php echo $empleados->apell_empleado->ViewAttributes() ?>>
<?php echo $empleados->apell_empleado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($empleados->telf_empleado->Visible) { // telf_empleado ?>
		<td<?php echo $empleados->telf_empleado->CellAttributes() ?>>
<span<?php echo $empleados->telf_empleado->ViewAttributes() ?>>
<?php echo $empleados->telf_empleado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($empleados->email_empleado->Visible) { // email_empleado ?>
		<td<?php echo $empleados->email_empleado->CellAttributes() ?>>
<span<?php echo $empleados->email_empleado->ViewAttributes() ?>>
<?php echo $empleados->email_empleado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($empleados->st_empleado_p->Visible) { // st_empleado_p ?>
		<td<?php echo $empleados->st_empleado_p->CellAttributes() ?>>
<span<?php echo $empleados->st_empleado_p->ViewAttributes() ?>>
<?php echo $empleados->st_empleado_p->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($empleados->pass_empleado->Visible) { // pass_empleado ?>
		<td<?php echo $empleados->pass_empleado->CellAttributes() ?>>
<span<?php echo $empleados->pass_empleado->ViewAttributes() ?>>
<?php echo $empleados->pass_empleado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($empleados->login_empleado->Visible) { // login_empleado ?>
		<td<?php echo $empleados->login_empleado->CellAttributes() ?>>
<span<?php echo $empleados->login_empleado->ViewAttributes() ?>>
<?php echo $empleados->login_empleado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($empleados->id_perfil->Visible) { // id_perfil ?>
		<td<?php echo $empleados->id_perfil->CellAttributes() ?>>
<span<?php echo $empleados->id_perfil->ViewAttributes() ?>>
<?php echo $empleados->id_perfil->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$empleados_list->ListOptions->Render("body", "right", $empleados_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($empleados->CurrentAction <> "gridadd")
		$empleados_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($empleados->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($empleados_list->Recordset)
	$empleados_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($empleados->CurrentAction <> "gridadd" && $empleados->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($empleados_list->Pager)) $empleados_list->Pager = new cPrevNextPager($empleados_list->StartRec, $empleados_list->DisplayRecs, $empleados_list->TotalRecs) ?>
<?php if ($empleados_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($empleados_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $empleados_list->PageUrl() ?>start=<?php echo $empleados_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($empleados_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $empleados_list->PageUrl() ?>start=<?php echo $empleados_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $empleados_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($empleados_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $empleados_list->PageUrl() ?>start=<?php echo $empleados_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($empleados_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $empleados_list->PageUrl() ?>start=<?php echo $empleados_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $empleados_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $empleados_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $empleados_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $empleados_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($empleados_list->SearchWhere == "0=101") { ?>
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
	foreach ($empleados_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
</td></tr></table>
<script type="text/javascript">
fempleadoslistsrch.Init();
fempleadoslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$empleados_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empleados_list->Page_Terminate();
?>
