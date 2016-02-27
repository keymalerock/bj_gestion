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

$representantes_list = NULL; // Initialize page object first

class crepresentantes_list extends crepresentantes {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'representantes';

	// Page object name
	var $PageObjName = 'representantes_list';

	// Grid form hidden field names
	var $FormName = 'frepresentanteslist';
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

		// Table object (representantes)
		if (!isset($GLOBALS["representantes"]) || get_class($GLOBALS["representantes"]) == "crepresentantes") {
			$GLOBALS["representantes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["representantes"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "representantesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "representantesdelete.php";
		$this->MultiUpdateUrl = "representantesupdate.php";

		// Table object (afiliado)
		if (!isset($GLOBALS['afiliado'])) $GLOBALS['afiliado'] = new cafiliado();

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'representantes', TRUE);

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
		$this->id_representante->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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

			// Set up master detail parameters
			$this->SetUpMasterParms();

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

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);
		if ($sFilter == "") {
			$sFilter = "0=101";
			$this->SearchWhere = $sFilter;
		}

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "afiliado") {
			global $afiliado;
			$rsmaster = $afiliado->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("afiliadolist.php"); // Return to master page
			} else {
				$afiliado->LoadListRowValues($rsmaster);
				$afiliado->RowType = EW_ROWTYPE_MASTER; // Master row
				$afiliado->RenderListRow();
				$rsmaster->Close();
			}
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
			$this->id_representante->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_representante->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id_representante, FALSE); // id_representante
		$this->BuildSearchSql($sWhere, $this->id_afiliado, FALSE); // id_afiliado
		$this->BuildSearchSql($sWhere, $this->dociden_repres, FALSE); // dociden_repres
		$this->BuildSearchSql($sWhere, $this->apell_repres, FALSE); // apell_repres
		$this->BuildSearchSql($sWhere, $this->nomb_repres, FALSE); // nomb_repres
		$this->BuildSearchSql($sWhere, $this->telf_resi_repres, FALSE); // telf_resi_repres
		$this->BuildSearchSql($sWhere, $this->email_repres, FALSE); // email_repres
		$this->BuildSearchSql($sWhere, $this->contact_e_repres, FALSE); // contact_e_repres
		$this->BuildSearchSql($sWhere, $this->st_repres, FALSE); // st_repres

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->id_representante->AdvancedSearch->Save(); // id_representante
			$this->id_afiliado->AdvancedSearch->Save(); // id_afiliado
			$this->dociden_repres->AdvancedSearch->Save(); // dociden_repres
			$this->apell_repres->AdvancedSearch->Save(); // apell_repres
			$this->nomb_repres->AdvancedSearch->Save(); // nomb_repres
			$this->telf_resi_repres->AdvancedSearch->Save(); // telf_resi_repres
			$this->email_repres->AdvancedSearch->Save(); // email_repres
			$this->contact_e_repres->AdvancedSearch->Save(); // contact_e_repres
			$this->st_repres->AdvancedSearch->Save(); // st_repres
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
		if ($this->id_representante->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dociden_repres->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->apell_repres->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nomb_repres->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->telf_resi_repres->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->email_repres->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->contact_e_repres->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->st_repres->AdvancedSearch->IssetSession())
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
		$this->id_representante->AdvancedSearch->UnsetSession();
		$this->id_afiliado->AdvancedSearch->UnsetSession();
		$this->dociden_repres->AdvancedSearch->UnsetSession();
		$this->apell_repres->AdvancedSearch->UnsetSession();
		$this->nomb_repres->AdvancedSearch->UnsetSession();
		$this->telf_resi_repres->AdvancedSearch->UnsetSession();
		$this->email_repres->AdvancedSearch->UnsetSession();
		$this->contact_e_repres->AdvancedSearch->UnsetSession();
		$this->st_repres->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->id_representante->AdvancedSearch->Load();
		$this->id_afiliado->AdvancedSearch->Load();
		$this->dociden_repres->AdvancedSearch->Load();
		$this->apell_repres->AdvancedSearch->Load();
		$this->nomb_repres->AdvancedSearch->Load();
		$this->telf_resi_repres->AdvancedSearch->Load();
		$this->email_repres->AdvancedSearch->Load();
		$this->contact_e_repres->AdvancedSearch->Load();
		$this->st_repres->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_representante); // id_representante
			$this->UpdateSort($this->id_afiliado); // id_afiliado
			$this->UpdateSort($this->dociden_repres); // dociden_repres
			$this->UpdateSort($this->apell_repres); // apell_repres
			$this->UpdateSort($this->nomb_repres); // nomb_repres
			$this->UpdateSort($this->telf_resi_repres); // telf_resi_repres
			$this->UpdateSort($this->email_repres); // email_repres
			$this->UpdateSort($this->par_repres); // par_repres
			$this->UpdateSort($this->cel_repres); // cel_repres
			$this->UpdateSort($this->contact_e_repres); // contact_e_repres
			$this->UpdateSort($this->contact_d_repres); // contact_d_repres
			$this->UpdateSort($this->st_repres); // st_repres
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->id_afiliado->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id_representante->setSort("");
				$this->id_afiliado->setSort("");
				$this->dociden_repres->setSort("");
				$this->apell_repres->setSort("");
				$this->nomb_repres->setSort("");
				$this->telf_resi_repres->setSort("");
				$this->email_repres->setSort("");
				$this->par_repres->setSort("");
				$this->cel_repres->setSort("");
				$this->contact_e_repres->setSort("");
				$this->contact_d_repres->setSort("");
				$this->st_repres->setSort("");
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
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_representante->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.frepresentanteslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// id_representante

		$this->id_representante->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_representante"]);
		if ($this->id_representante->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_representante->AdvancedSearch->SearchOperator = @$_GET["z_id_representante"];

		// id_afiliado
		$this->id_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_afiliado"]);
		if ($this->id_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_id_afiliado"];

		// dociden_repres
		$this->dociden_repres->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dociden_repres"]);
		if ($this->dociden_repres->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dociden_repres->AdvancedSearch->SearchOperator = @$_GET["z_dociden_repres"];

		// apell_repres
		$this->apell_repres->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_apell_repres"]);
		if ($this->apell_repres->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->apell_repres->AdvancedSearch->SearchOperator = @$_GET["z_apell_repres"];

		// nomb_repres
		$this->nomb_repres->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nomb_repres"]);
		if ($this->nomb_repres->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nomb_repres->AdvancedSearch->SearchOperator = @$_GET["z_nomb_repres"];

		// telf_resi_repres
		$this->telf_resi_repres->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_telf_resi_repres"]);
		if ($this->telf_resi_repres->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->telf_resi_repres->AdvancedSearch->SearchOperator = @$_GET["z_telf_resi_repres"];

		// email_repres
		$this->email_repres->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_email_repres"]);
		if ($this->email_repres->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->email_repres->AdvancedSearch->SearchOperator = @$_GET["z_email_repres"];

		// contact_e_repres
		$this->contact_e_repres->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_contact_e_repres"]);
		if ($this->contact_e_repres->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->contact_e_repres->AdvancedSearch->SearchOperator = @$_GET["z_contact_e_repres"];

		// st_repres
		$this->st_repres->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_st_repres"]);
		if ($this->st_repres->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->st_repres->AdvancedSearch->SearchOperator = @$_GET["z_st_repres"];
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_representante")) <> "")
			$this->id_representante->CurrentValue = $this->getKey("id_representante"); // id_representante
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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id_representante
			$this->id_representante->EditCustomAttributes = "";
			$this->id_representante->EditValue = ew_HtmlEncode($this->id_representante->AdvancedSearch->SearchValue);
			$this->id_representante->PlaceHolder = ew_RemoveHtml($this->id_representante->FldCaption());

			// id_afiliado
			$this->id_afiliado->EditCustomAttributes = "";
			$this->id_afiliado->EditValue = ew_HtmlEncode($this->id_afiliado->AdvancedSearch->SearchValue);
			if (strval($this->id_afiliado->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id_afiliado`" . ew_SearchString("=", $this->id_afiliado->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER);
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
					$this->id_afiliado->EditValue = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->id_afiliado->EditValue .= ew_ValueSeparator(1,$this->id_afiliado) . ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$rswrk->Close();
				} else {
					$this->id_afiliado->EditValue = ew_HtmlEncode($this->id_afiliado->AdvancedSearch->SearchValue);
				}
			} else {
				$this->id_afiliado->EditValue = NULL;
			}
			$this->id_afiliado->PlaceHolder = ew_RemoveHtml($this->id_afiliado->FldCaption());

			// dociden_repres
			$this->dociden_repres->EditCustomAttributes = "";
			$this->dociden_repres->EditValue = ew_HtmlEncode($this->dociden_repres->AdvancedSearch->SearchValue);
			$this->dociden_repres->PlaceHolder = ew_RemoveHtml($this->dociden_repres->FldCaption());

			// apell_repres
			$this->apell_repres->EditCustomAttributes = "";
			$this->apell_repres->EditValue = ew_HtmlEncode($this->apell_repres->AdvancedSearch->SearchValue);
			$this->apell_repres->PlaceHolder = ew_RemoveHtml($this->apell_repres->FldCaption());

			// nomb_repres
			$this->nomb_repres->EditCustomAttributes = "";
			$this->nomb_repres->EditValue = ew_HtmlEncode($this->nomb_repres->AdvancedSearch->SearchValue);
			$this->nomb_repres->PlaceHolder = ew_RemoveHtml($this->nomb_repres->FldCaption());

			// telf_resi_repres
			$this->telf_resi_repres->EditCustomAttributes = "";
			$this->telf_resi_repres->EditValue = ew_HtmlEncode($this->telf_resi_repres->AdvancedSearch->SearchValue);
			$this->telf_resi_repres->PlaceHolder = ew_RemoveHtml($this->telf_resi_repres->FldCaption());

			// email_repres
			$this->email_repres->EditCustomAttributes = "";
			$this->email_repres->EditValue = ew_HtmlEncode($this->email_repres->AdvancedSearch->SearchValue);
			$this->email_repres->PlaceHolder = ew_RemoveHtml($this->email_repres->FldCaption());

			// par_repres
			$this->par_repres->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->par_repres->FldTagValue(1), $this->par_repres->FldTagCaption(1) <> "" ? $this->par_repres->FldTagCaption(1) : $this->par_repres->FldTagValue(1));
			$arwrk[] = array($this->par_repres->FldTagValue(2), $this->par_repres->FldTagCaption(2) <> "" ? $this->par_repres->FldTagCaption(2) : $this->par_repres->FldTagValue(2));
			$arwrk[] = array($this->par_repres->FldTagValue(3), $this->par_repres->FldTagCaption(3) <> "" ? $this->par_repres->FldTagCaption(3) : $this->par_repres->FldTagValue(3));
			$arwrk[] = array($this->par_repres->FldTagValue(4), $this->par_repres->FldTagCaption(4) <> "" ? $this->par_repres->FldTagCaption(4) : $this->par_repres->FldTagValue(4));
			$arwrk[] = array($this->par_repres->FldTagValue(5), $this->par_repres->FldTagCaption(5) <> "" ? $this->par_repres->FldTagCaption(5) : $this->par_repres->FldTagValue(5));
			$arwrk[] = array($this->par_repres->FldTagValue(6), $this->par_repres->FldTagCaption(6) <> "" ? $this->par_repres->FldTagCaption(6) : $this->par_repres->FldTagValue(6));
			$arwrk[] = array($this->par_repres->FldTagValue(7), $this->par_repres->FldTagCaption(7) <> "" ? $this->par_repres->FldTagCaption(7) : $this->par_repres->FldTagValue(7));
			$this->par_repres->EditValue = $arwrk;

			// cel_repres
			$this->cel_repres->EditCustomAttributes = "";
			$this->cel_repres->EditValue = ew_HtmlEncode($this->cel_repres->AdvancedSearch->SearchValue);
			$this->cel_repres->PlaceHolder = ew_RemoveHtml($this->cel_repres->FldCaption());

			// contact_e_repres
			$this->contact_e_repres->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->contact_e_repres->FldTagValue(1), $this->contact_e_repres->FldTagCaption(1) <> "" ? $this->contact_e_repres->FldTagCaption(1) : $this->contact_e_repres->FldTagValue(1));
			$arwrk[] = array($this->contact_e_repres->FldTagValue(2), $this->contact_e_repres->FldTagCaption(2) <> "" ? $this->contact_e_repres->FldTagCaption(2) : $this->contact_e_repres->FldTagValue(2));
			$this->contact_e_repres->EditValue = $arwrk;

			// contact_d_repres
			$this->contact_d_repres->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->contact_d_repres->FldTagValue(1), $this->contact_d_repres->FldTagCaption(1) <> "" ? $this->contact_d_repres->FldTagCaption(1) : $this->contact_d_repres->FldTagValue(1));
			$arwrk[] = array($this->contact_d_repres->FldTagValue(2), $this->contact_d_repres->FldTagCaption(2) <> "" ? $this->contact_d_repres->FldTagCaption(2) : $this->contact_d_repres->FldTagValue(2));
			$this->contact_d_repres->EditValue = $arwrk;

			// st_repres
			$this->st_repres->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->st_repres->FldTagValue(1), $this->st_repres->FldTagCaption(1) <> "" ? $this->st_repres->FldTagCaption(1) : $this->st_repres->FldTagValue(1));
			$arwrk[] = array($this->st_repres->FldTagValue(2), $this->st_repres->FldTagCaption(2) <> "" ? $this->st_repres->FldTagCaption(2) : $this->st_repres->FldTagValue(2));
			$this->st_repres->EditValue = $arwrk;
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
		$this->id_representante->AdvancedSearch->Load();
		$this->id_afiliado->AdvancedSearch->Load();
		$this->dociden_repres->AdvancedSearch->Load();
		$this->apell_repres->AdvancedSearch->Load();
		$this->nomb_repres->AdvancedSearch->Load();
		$this->telf_resi_repres->AdvancedSearch->Load();
		$this->email_repres->AdvancedSearch->Load();
		$this->contact_e_repres->AdvancedSearch->Load();
		$this->st_repres->AdvancedSearch->Load();
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "afiliado") {
				$bValidMaster = TRUE;
				if (@$_GET["id_afiliado"] <> "") {
					$GLOBALS["afiliado"]->id_afiliado->setQueryStringValue($_GET["id_afiliado"]);
					$this->id_afiliado->setQueryStringValue($GLOBALS["afiliado"]->id_afiliado->QueryStringValue);
					$this->id_afiliado->setSessionValue($this->id_afiliado->QueryStringValue);
					if (!is_numeric($GLOBALS["afiliado"]->id_afiliado->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "afiliado") {
				if ($this->id_afiliado->QueryStringValue == "") $this->id_afiliado->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
if (!isset($representantes_list)) $representantes_list = new crepresentantes_list();

// Page init
$representantes_list->Page_Init();

// Page main
$representantes_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$representantes_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var representantes_list = new ew_Page("representantes_list");
representantes_list.PageID = "list"; // Page ID
var EW_PAGE_ID = representantes_list.PageID; // For backward compatibility

// Form object
var frepresentanteslist = new ew_Form("frepresentanteslist");
frepresentanteslist.FormKeyCountName = '<?php echo $representantes_list->FormKeyCountName ?>';

// Form_CustomValidate event
frepresentanteslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frepresentanteslist.ValidateRequired = true;
<?php } else { ?>
frepresentanteslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frepresentanteslist.Lists["x_id_afiliado"] = {"LinkField":"x_id_afiliado","Ajax":true,"AutoFill":false,"DisplayFields":["x_apell_afiliado","x_nomb_afiliado","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var frepresentanteslistsrch = new ew_Form("frepresentanteslistsrch");

// Validate function for search
frepresentanteslistsrch.Validate = function(fobj) {
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
frepresentanteslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frepresentanteslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
frepresentanteslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
frepresentanteslistsrch.Lists["x_id_afiliado"] = {"LinkField":"x_id_afiliado","Ajax":true,"AutoFill":false,"DisplayFields":["x_apell_afiliado","x_nomb_afiliado","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php if ($representantes->getCurrentMasterTable() == "" && $representantes_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $representantes_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php if (($representantes->Export == "") || (EW_EXPORT_MASTER_RECORD && $representantes->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "afiliadolist.php";
if ($representantes_list->DbMasterFilter <> "" && $representantes->getCurrentMasterTable() == "afiliado") {
	if ($representantes_list->MasterRecordExists) {
		if ($representantes->getCurrentMasterTable() == $representantes->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($representantes_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $representantes_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once "afiliadomaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$representantes_list->TotalRecs = $representantes->SelectRecordCount();
	} else {
		if ($representantes_list->Recordset = $representantes_list->LoadRecordset())
			$representantes_list->TotalRecs = $representantes_list->Recordset->RecordCount();
	}
	$representantes_list->StartRec = 1;
	if ($representantes_list->DisplayRecs <= 0 || ($representantes->Export <> "" && $representantes->ExportAll)) // Display all records
		$representantes_list->DisplayRecs = $representantes_list->TotalRecs;
	if (!($representantes->Export <> "" && $representantes->ExportAll))
		$representantes_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$representantes_list->Recordset = $representantes_list->LoadRecordset($representantes_list->StartRec-1, $representantes_list->DisplayRecs);
$representantes_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($representantes->Export == "" && $representantes->CurrentAction == "") { ?>
<form name="frepresentanteslistsrch" id="frepresentanteslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<div class="accordion ewDisplayTable ewSearchTable" id="frepresentanteslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#frepresentanteslistsrch_SearchGroup" href="#frepresentanteslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="frepresentanteslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="frepresentanteslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="representantes">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$representantes_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$representantes->RowType = EW_ROWTYPE_SEARCH;

// Render row
$representantes->ResetAttrs();
$representantes_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($representantes->id_afiliado->Visible) { // id_afiliado ?>
	<span id="xsc_id_afiliado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $representantes->id_afiliado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_afiliado" id="z_id_afiliado" value="="></span>
		<span class="control-group ewSearchField">
<?php if ($representantes->id_afiliado->getSessionValue() <> "") { ?>
<span<?php echo $representantes->id_afiliado->ViewAttributes() ?>>
<?php echo $representantes->id_afiliado->ListViewValue() ?></span>
<input type="hidden" id="x_id_afiliado" name="x_id_afiliado" value="<?php echo ew_HtmlEncode($representantes->id_afiliado->AdvancedSearch->SearchValue) ?>">
<?php } else { ?>
<?php
	$wrkonchange = trim(" " . @$representantes->id_afiliado->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$representantes->id_afiliado->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_afiliado" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_id_afiliado" id="sv_x_id_afiliado" value="<?php echo $representantes->id_afiliado->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($representantes->id_afiliado->PlaceHolder) ?>"<?php echo $representantes->id_afiliado->EditAttributes() ?>>&nbsp;<span id="em_x_id_afiliado" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_id_afiliado" style="display: inline; z-index: 8980"></div>
</span>
<input type="hidden" data-field="x_id_afiliado" name="x_id_afiliado" id="x_id_afiliado" value="<?php echo ew_HtmlEncode($representantes->id_afiliado->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id_afiliado`, `apell_afiliado` AS `DispFld`, `nomb_afiliado` AS `Disp2Fld` FROM `afiliado`";
$sWhereWrk = "`apell_afiliado` LIKE '{query_value}%' OR CONCAT(`apell_afiliado`,'" . ew_ValueSeparator(1, $Page->id_afiliado) . "',`nomb_afiliado`) LIKE '{query_value}%'";

// Call Lookup selecting
$representantes->Lookup_Selecting($representantes->id_afiliado, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_id_afiliado" id="q_x_id_afiliado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_id_afiliado", frepresentanteslistsrch, true, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_id_afiliado") + ar[i] : "";
	return dv;
}
frepresentanteslistsrch.AutoSuggests["x_id_afiliado"] = oas;
</script>
<?php } ?>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($representantes->dociden_repres->Visible) { // dociden_repres ?>
	<span id="xsc_dociden_repres" class="ewCell">
		<span class="ewSearchCaption"><?php echo $representantes->dociden_repres->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_dociden_repres" id="z_dociden_repres" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_dociden_repres" name="x_dociden_repres" id="x_dociden_repres" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($representantes->dociden_repres->PlaceHolder) ?>" value="<?php echo $representantes->dociden_repres->EditValue ?>"<?php echo $representantes->dociden_repres->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($representantes->apell_repres->Visible) { // apell_repres ?>
	<span id="xsc_apell_repres" class="ewCell">
		<span class="ewSearchCaption"><?php echo $representantes->apell_repres->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apell_repres" id="z_apell_repres" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_apell_repres" name="x_apell_repres" id="x_apell_repres" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($representantes->apell_repres->PlaceHolder) ?>" value="<?php echo $representantes->apell_repres->EditValue ?>"<?php echo $representantes->apell_repres->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($representantes->nomb_repres->Visible) { // nomb_repres ?>
	<span id="xsc_nomb_repres" class="ewCell">
		<span class="ewSearchCaption"><?php echo $representantes->nomb_repres->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nomb_repres" id="z_nomb_repres" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_nomb_repres" name="x_nomb_repres" id="x_nomb_repres" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($representantes->nomb_repres->PlaceHolder) ?>" value="<?php echo $representantes->nomb_repres->EditValue ?>"<?php echo $representantes->nomb_repres->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($representantes->contact_e_repres->Visible) { // contact_e_repres ?>
	<span id="xsc_contact_e_repres" class="ewCell">
		<span class="ewSearchCaption"><?php echo $representantes->contact_e_repres->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_contact_e_repres" id="z_contact_e_repres" value="LIKE"></span>
		<span class="control-group ewSearchField">
<div id="tp_x_contact_e_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_contact_e_repres" id="x_contact_e_repres" value="{value}"<?php echo $representantes->contact_e_repres->EditAttributes() ?>></div>
<div id="dsl_x_contact_e_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->contact_e_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->contact_e_repres->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_contact_e_repres" name="x_contact_e_repres" id="x_contact_e_repres_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $representantes->contact_e_repres->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
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
<div id="xsr_6" class="ewRow">
<?php if ($representantes->st_repres->Visible) { // st_repres ?>
	<span id="xsc_st_repres" class="ewCell">
		<span class="ewSearchCaption"><?php echo $representantes->st_repres->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_st_repres" id="z_st_repres" value="="></span>
		<span class="control-group ewSearchField">
<div id="tp_x_st_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_st_repres" id="x_st_repres" value="{value}"<?php echo $representantes->st_repres->EditAttributes() ?>></div>
<div id="dsl_x_st_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->st_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->st_repres->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_st_repres" name="x_st_repres" id="x_st_repres_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $representantes->st_repres->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
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
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $representantes_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ResetSearch") ?></a>
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
<?php $representantes_list->ShowPageHeader(); ?>
<?php
$representantes_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<form name="frepresentanteslist" id="frepresentanteslist" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="representantes">
<div id="gmp_representantes" class="ewGridMiddlePanel">
<?php if ($representantes_list->TotalRecs > 0) { ?>
<table id="tbl_representanteslist" class="ewTable ewTableSeparate">
<?php echo $representantes->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$representantes_list->RenderListOptions();

// Render list options (header, left)
$representantes_list->ListOptions->Render("header", "left");
?>
<?php if ($representantes->id_representante->Visible) { // id_representante ?>
	<?php if ($representantes->SortUrl($representantes->id_representante) == "") { ?>
		<td><div id="elh_representantes_id_representante" class="representantes_id_representante"><div class="ewTableHeaderCaption"><?php echo $representantes->id_representante->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $representantes->SortUrl($representantes->id_representante) ?>',1);"><div id="elh_representantes_id_representante" class="representantes_id_representante">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->id_representante->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->id_representante->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->id_representante->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->id_afiliado->Visible) { // id_afiliado ?>
	<?php if ($representantes->SortUrl($representantes->id_afiliado) == "") { ?>
		<td><div id="elh_representantes_id_afiliado" class="representantes_id_afiliado"><div class="ewTableHeaderCaption"><?php echo $representantes->id_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $representantes->SortUrl($representantes->id_afiliado) ?>',1);"><div id="elh_representantes_id_afiliado" class="representantes_id_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->id_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->id_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->id_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->dociden_repres->Visible) { // dociden_repres ?>
	<?php if ($representantes->SortUrl($representantes->dociden_repres) == "") { ?>
		<td><div id="elh_representantes_dociden_repres" class="representantes_dociden_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->dociden_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $representantes->SortUrl($representantes->dociden_repres) ?>',1);"><div id="elh_representantes_dociden_repres" class="representantes_dociden_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->dociden_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->dociden_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->dociden_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->apell_repres->Visible) { // apell_repres ?>
	<?php if ($representantes->SortUrl($representantes->apell_repres) == "") { ?>
		<td><div id="elh_representantes_apell_repres" class="representantes_apell_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->apell_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $representantes->SortUrl($representantes->apell_repres) ?>',1);"><div id="elh_representantes_apell_repres" class="representantes_apell_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->apell_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->apell_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->apell_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->nomb_repres->Visible) { // nomb_repres ?>
	<?php if ($representantes->SortUrl($representantes->nomb_repres) == "") { ?>
		<td><div id="elh_representantes_nomb_repres" class="representantes_nomb_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->nomb_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $representantes->SortUrl($representantes->nomb_repres) ?>',1);"><div id="elh_representantes_nomb_repres" class="representantes_nomb_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->nomb_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->nomb_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->nomb_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->telf_resi_repres->Visible) { // telf_resi_repres ?>
	<?php if ($representantes->SortUrl($representantes->telf_resi_repres) == "") { ?>
		<td><div id="elh_representantes_telf_resi_repres" class="representantes_telf_resi_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->telf_resi_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $representantes->SortUrl($representantes->telf_resi_repres) ?>',1);"><div id="elh_representantes_telf_resi_repres" class="representantes_telf_resi_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->telf_resi_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->telf_resi_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->telf_resi_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->email_repres->Visible) { // email_repres ?>
	<?php if ($representantes->SortUrl($representantes->email_repres) == "") { ?>
		<td><div id="elh_representantes_email_repres" class="representantes_email_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->email_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $representantes->SortUrl($representantes->email_repres) ?>',1);"><div id="elh_representantes_email_repres" class="representantes_email_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->email_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->email_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->email_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->par_repres->Visible) { // par_repres ?>
	<?php if ($representantes->SortUrl($representantes->par_repres) == "") { ?>
		<td><div id="elh_representantes_par_repres" class="representantes_par_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->par_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $representantes->SortUrl($representantes->par_repres) ?>',1);"><div id="elh_representantes_par_repres" class="representantes_par_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->par_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->par_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->par_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->cel_repres->Visible) { // cel_repres ?>
	<?php if ($representantes->SortUrl($representantes->cel_repres) == "") { ?>
		<td><div id="elh_representantes_cel_repres" class="representantes_cel_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->cel_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $representantes->SortUrl($representantes->cel_repres) ?>',1);"><div id="elh_representantes_cel_repres" class="representantes_cel_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->cel_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->cel_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->cel_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->contact_e_repres->Visible) { // contact_e_repres ?>
	<?php if ($representantes->SortUrl($representantes->contact_e_repres) == "") { ?>
		<td><div id="elh_representantes_contact_e_repres" class="representantes_contact_e_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->contact_e_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $representantes->SortUrl($representantes->contact_e_repres) ?>',1);"><div id="elh_representantes_contact_e_repres" class="representantes_contact_e_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->contact_e_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->contact_e_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->contact_e_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->contact_d_repres->Visible) { // contact_d_repres ?>
	<?php if ($representantes->SortUrl($representantes->contact_d_repres) == "") { ?>
		<td><div id="elh_representantes_contact_d_repres" class="representantes_contact_d_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->contact_d_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $representantes->SortUrl($representantes->contact_d_repres) ?>',1);"><div id="elh_representantes_contact_d_repres" class="representantes_contact_d_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->contact_d_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->contact_d_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->contact_d_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($representantes->st_repres->Visible) { // st_repres ?>
	<?php if ($representantes->SortUrl($representantes->st_repres) == "") { ?>
		<td><div id="elh_representantes_st_repres" class="representantes_st_repres"><div class="ewTableHeaderCaption"><?php echo $representantes->st_repres->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $representantes->SortUrl($representantes->st_repres) ?>',1);"><div id="elh_representantes_st_repres" class="representantes_st_repres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $representantes->st_repres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($representantes->st_repres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($representantes->st_repres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$representantes_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($representantes->ExportAll && $representantes->Export <> "") {
	$representantes_list->StopRec = $representantes_list->TotalRecs;
} else {

	// Set the last record to display
	if ($representantes_list->TotalRecs > $representantes_list->StartRec + $representantes_list->DisplayRecs - 1)
		$representantes_list->StopRec = $representantes_list->StartRec + $representantes_list->DisplayRecs - 1;
	else
		$representantes_list->StopRec = $representantes_list->TotalRecs;
}
$representantes_list->RecCnt = $representantes_list->StartRec - 1;
if ($representantes_list->Recordset && !$representantes_list->Recordset->EOF) {
	$representantes_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $representantes_list->StartRec > 1)
		$representantes_list->Recordset->Move($representantes_list->StartRec - 1);
} elseif (!$representantes->AllowAddDeleteRow && $representantes_list->StopRec == 0) {
	$representantes_list->StopRec = $representantes->GridAddRowCount;
}

// Initialize aggregate
$representantes->RowType = EW_ROWTYPE_AGGREGATEINIT;
$representantes->ResetAttrs();
$representantes_list->RenderRow();
while ($representantes_list->RecCnt < $representantes_list->StopRec) {
	$representantes_list->RecCnt++;
	if (intval($representantes_list->RecCnt) >= intval($representantes_list->StartRec)) {
		$representantes_list->RowCnt++;

		// Set up key count
		$representantes_list->KeyCount = $representantes_list->RowIndex;

		// Init row class and style
		$representantes->ResetAttrs();
		$representantes->CssClass = "";
		if ($representantes->CurrentAction == "gridadd") {
		} else {
			$representantes_list->LoadRowValues($representantes_list->Recordset); // Load row values
		}
		$representantes->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$representantes->RowAttrs = array_merge($representantes->RowAttrs, array('data-rowindex'=>$representantes_list->RowCnt, 'id'=>'r' . $representantes_list->RowCnt . '_representantes', 'data-rowtype'=>$representantes->RowType));

		// Render row
		$representantes_list->RenderRow();

		// Render list options
		$representantes_list->RenderListOptions();
?>
	<tr<?php echo $representantes->RowAttributes() ?>>
<?php

// Render list options (body, left)
$representantes_list->ListOptions->Render("body", "left", $representantes_list->RowCnt);
?>
	<?php if ($representantes->id_representante->Visible) { // id_representante ?>
		<td<?php echo $representantes->id_representante->CellAttributes() ?>>
<span<?php echo $representantes->id_representante->ViewAttributes() ?>>
<?php echo $representantes->id_representante->ListViewValue() ?></span>
<a id="<?php echo $representantes_list->PageObjName . "_row_" . $representantes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($representantes->id_afiliado->Visible) { // id_afiliado ?>
		<td<?php echo $representantes->id_afiliado->CellAttributes() ?>>
<span<?php echo $representantes->id_afiliado->ViewAttributes() ?>>
<?php echo $representantes->id_afiliado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($representantes->dociden_repres->Visible) { // dociden_repres ?>
		<td<?php echo $representantes->dociden_repres->CellAttributes() ?>>
<span<?php echo $representantes->dociden_repres->ViewAttributes() ?>>
<?php echo $representantes->dociden_repres->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($representantes->apell_repres->Visible) { // apell_repres ?>
		<td<?php echo $representantes->apell_repres->CellAttributes() ?>>
<span<?php echo $representantes->apell_repres->ViewAttributes() ?>>
<?php echo $representantes->apell_repres->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($representantes->nomb_repres->Visible) { // nomb_repres ?>
		<td<?php echo $representantes->nomb_repres->CellAttributes() ?>>
<span<?php echo $representantes->nomb_repres->ViewAttributes() ?>>
<?php echo $representantes->nomb_repres->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($representantes->telf_resi_repres->Visible) { // telf_resi_repres ?>
		<td<?php echo $representantes->telf_resi_repres->CellAttributes() ?>>
<span<?php echo $representantes->telf_resi_repres->ViewAttributes() ?>>
<?php echo $representantes->telf_resi_repres->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($representantes->email_repres->Visible) { // email_repres ?>
		<td<?php echo $representantes->email_repres->CellAttributes() ?>>
<span<?php echo $representantes->email_repres->ViewAttributes() ?>>
<?php echo $representantes->email_repres->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($representantes->par_repres->Visible) { // par_repres ?>
		<td<?php echo $representantes->par_repres->CellAttributes() ?>>
<span<?php echo $representantes->par_repres->ViewAttributes() ?>>
<?php echo $representantes->par_repres->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($representantes->cel_repres->Visible) { // cel_repres ?>
		<td<?php echo $representantes->cel_repres->CellAttributes() ?>>
<span<?php echo $representantes->cel_repres->ViewAttributes() ?>>
<?php echo $representantes->cel_repres->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($representantes->contact_e_repres->Visible) { // contact_e_repres ?>
		<td<?php echo $representantes->contact_e_repres->CellAttributes() ?>>
<span<?php echo $representantes->contact_e_repres->ViewAttributes() ?>>
<?php echo $representantes->contact_e_repres->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($representantes->contact_d_repres->Visible) { // contact_d_repres ?>
		<td<?php echo $representantes->contact_d_repres->CellAttributes() ?>>
<span<?php echo $representantes->contact_d_repres->ViewAttributes() ?>>
<?php echo $representantes->contact_d_repres->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($representantes->st_repres->Visible) { // st_repres ?>
		<td<?php echo $representantes->st_repres->CellAttributes() ?>>
<span<?php echo $representantes->st_repres->ViewAttributes() ?>>
<?php echo $representantes->st_repres->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$representantes_list->ListOptions->Render("body", "right", $representantes_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($representantes->CurrentAction <> "gridadd")
		$representantes_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($representantes->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($representantes_list->Recordset)
	$representantes_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($representantes->CurrentAction <> "gridadd" && $representantes->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($representantes_list->Pager)) $representantes_list->Pager = new cPrevNextPager($representantes_list->StartRec, $representantes_list->DisplayRecs, $representantes_list->TotalRecs) ?>
<?php if ($representantes_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($representantes_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $representantes_list->PageUrl() ?>start=<?php echo $representantes_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($representantes_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $representantes_list->PageUrl() ?>start=<?php echo $representantes_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $representantes_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($representantes_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $representantes_list->PageUrl() ?>start=<?php echo $representantes_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($representantes_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $representantes_list->PageUrl() ?>start=<?php echo $representantes_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $representantes_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $representantes_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $representantes_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $representantes_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($representantes_list->SearchWhere == "0=101") { ?>
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
	foreach ($representantes_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
</td></tr></table>
<script type="text/javascript">
frepresentanteslistsrch.Init();
frepresentanteslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$representantes_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$representantes_list->Page_Terminate();
?>
