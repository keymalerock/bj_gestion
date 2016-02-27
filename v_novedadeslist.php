<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "v_novedadesinfo.php" ?>
<?php include_once "v_usuariosinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$v_novedades_list = NULL; // Initialize page object first

class cv_novedades_list extends cv_novedades {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'v_novedades';

	// Page object name
	var $PageObjName = 'v_novedades_list';

	// Grid form hidden field names
	var $FormName = 'fv_novedadeslist';
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

		// Table object (v_novedades)
		if (!isset($GLOBALS["v_novedades"]) || get_class($GLOBALS["v_novedades"]) == "cv_novedades") {
			$GLOBALS["v_novedades"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["v_novedades"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "v_novedadesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "v_novedadesdelete.php";
		$this->MultiUpdateUrl = "v_novedadesupdate.php";

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'v_novedades', TRUE);

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
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
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
		if (count($arrKeyFlds) >= 0) {
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		$this->BuildSearchSql($sWhere, $this->id_afiliado, FALSE); // id_afiliado
		$this->BuildSearchSql($sWhere, $this->apell_afiliado, FALSE); // apell_afiliado
		$this->BuildSearchSql($sWhere, $this->dociden_afiliado, FALSE); // dociden_afiliado
		$this->BuildSearchSql($sWhere, $this->nomb_afiliado, FALSE); // nomb_afiliado
		$this->BuildSearchSql($sWhere, $this->obs_nov, FALSE); // obs_nov
		$this->BuildSearchSql($sWhere, $this->fe_nov, FALSE); // fe_nov
		$this->BuildSearchSql($sWhere, $this->estado_nov, FALSE); // estado_nov

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->id_afiliado->AdvancedSearch->Save(); // id_afiliado
			$this->apell_afiliado->AdvancedSearch->Save(); // apell_afiliado
			$this->dociden_afiliado->AdvancedSearch->Save(); // dociden_afiliado
			$this->nomb_afiliado->AdvancedSearch->Save(); // nomb_afiliado
			$this->obs_nov->AdvancedSearch->Save(); // obs_nov
			$this->fe_nov->AdvancedSearch->Save(); // fe_nov
			$this->estado_nov->AdvancedSearch->Save(); // estado_nov
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
		if ($this->id_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->apell_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dociden_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nomb_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->obs_nov->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fe_nov->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->estado_nov->AdvancedSearch->IssetSession())
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
		$this->id_afiliado->AdvancedSearch->UnsetSession();
		$this->apell_afiliado->AdvancedSearch->UnsetSession();
		$this->dociden_afiliado->AdvancedSearch->UnsetSession();
		$this->nomb_afiliado->AdvancedSearch->UnsetSession();
		$this->obs_nov->AdvancedSearch->UnsetSession();
		$this->fe_nov->AdvancedSearch->UnsetSession();
		$this->estado_nov->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->id_afiliado->AdvancedSearch->Load();
		$this->apell_afiliado->AdvancedSearch->Load();
		$this->dociden_afiliado->AdvancedSearch->Load();
		$this->nomb_afiliado->AdvancedSearch->Load();
		$this->obs_nov->AdvancedSearch->Load();
		$this->fe_nov->AdvancedSearch->Load();
		$this->estado_nov->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_afiliado); // id_afiliado
			$this->UpdateSort($this->apell_afiliado); // apell_afiliado
			$this->UpdateSort($this->dociden_afiliado); // dociden_afiliado
			$this->UpdateSort($this->nomb_afiliado); // nomb_afiliado
			$this->UpdateSort($this->obs_nov); // obs_nov
			$this->UpdateSort($this->fe_nov); // fe_nov
			$this->UpdateSort($this->estado_nov); // estado_nov
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
				$this->id_afiliado->setSort("");
				$this->apell_afiliado->setSort("");
				$this->dociden_afiliado->setSort("");
				$this->nomb_afiliado->setSort("");
				$this->obs_nov->setSort("");
				$this->fe_nov->setSort("");
				$this->estado_nov->setSort("");
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

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fv_novedadeslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// id_afiliado

		$this->id_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_afiliado"]);
		if ($this->id_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_id_afiliado"];

		// apell_afiliado
		$this->apell_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_apell_afiliado"]);
		if ($this->apell_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->apell_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_apell_afiliado"];

		// dociden_afiliado
		$this->dociden_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dociden_afiliado"]);
		if ($this->dociden_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dociden_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_dociden_afiliado"];

		// nomb_afiliado
		$this->nomb_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nomb_afiliado"]);
		if ($this->nomb_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nomb_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_nomb_afiliado"];

		// obs_nov
		$this->obs_nov->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_obs_nov"]);
		if ($this->obs_nov->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->obs_nov->AdvancedSearch->SearchOperator = @$_GET["z_obs_nov"];

		// fe_nov
		$this->fe_nov->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fe_nov"]);
		if ($this->fe_nov->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fe_nov->AdvancedSearch->SearchOperator = @$_GET["z_fe_nov"];

		// estado_nov
		$this->estado_nov->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_estado_nov"]);
		if ($this->estado_nov->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->estado_nov->AdvancedSearch->SearchOperator = @$_GET["z_estado_nov"];
		if (is_array($this->estado_nov->AdvancedSearch->SearchValue)) $this->estado_nov->AdvancedSearch->SearchValue = implode(",", $this->estado_nov->AdvancedSearch->SearchValue);
		if (is_array($this->estado_nov->AdvancedSearch->SearchValue2)) $this->estado_nov->AdvancedSearch->SearchValue2 = implode(",", $this->estado_nov->AdvancedSearch->SearchValue2);
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
		$this->apell_afiliado->setDbValue($rs->fields('apell_afiliado'));
		$this->dociden_afiliado->setDbValue($rs->fields('dociden_afiliado'));
		$this->nomb_afiliado->setDbValue($rs->fields('nomb_afiliado'));
		$this->obs_nov->setDbValue($rs->fields('obs_nov'));
		$this->fe_nov->setDbValue($rs->fields('fe_nov'));
		$this->estado_nov->setDbValue($rs->fields('estado_nov'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_afiliado->DbValue = $row['id_afiliado'];
		$this->apell_afiliado->DbValue = $row['apell_afiliado'];
		$this->dociden_afiliado->DbValue = $row['dociden_afiliado'];
		$this->nomb_afiliado->DbValue = $row['nomb_afiliado'];
		$this->obs_nov->DbValue = $row['obs_nov'];
		$this->fe_nov->DbValue = $row['fe_nov'];
		$this->estado_nov->DbValue = $row['estado_nov'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;

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
		// apell_afiliado
		// dociden_afiliado
		// nomb_afiliado
		// obs_nov
		// fe_nov
		// estado_nov

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_afiliado
			$this->id_afiliado->ViewValue = $this->id_afiliado->CurrentValue;
			$this->id_afiliado->ViewCustomAttributes = "";

			// apell_afiliado
			$this->apell_afiliado->ViewValue = $this->apell_afiliado->CurrentValue;
			$this->apell_afiliado->ViewCustomAttributes = "";

			// dociden_afiliado
			$this->dociden_afiliado->ViewValue = $this->dociden_afiliado->CurrentValue;
			$this->dociden_afiliado->ViewCustomAttributes = "";

			// nomb_afiliado
			$this->nomb_afiliado->ViewValue = $this->nomb_afiliado->CurrentValue;
			$this->nomb_afiliado->ViewCustomAttributes = "";

			// obs_nov
			$this->obs_nov->ViewValue = $this->obs_nov->CurrentValue;
			$this->obs_nov->ViewCustomAttributes = "";

			// fe_nov
			$this->fe_nov->ViewValue = $this->fe_nov->CurrentValue;
			$this->fe_nov->ViewValue = ew_FormatDateTime($this->fe_nov->ViewValue, 5);
			$this->fe_nov->ViewCustomAttributes = "";

			// estado_nov
			if (strval($this->estado_nov->CurrentValue) <> "") {
				$this->estado_nov->ViewValue = "";
				$arwrk = explode(",", strval($this->estado_nov->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->estado_nov->FldTagValue(1):
							$this->estado_nov->ViewValue .= $this->estado_nov->FldTagCaption(1) <> "" ? $this->estado_nov->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						case $this->estado_nov->FldTagValue(2):
							$this->estado_nov->ViewValue .= $this->estado_nov->FldTagCaption(2) <> "" ? $this->estado_nov->FldTagCaption(2) : trim($arwrk[$ari]);
							break;
						case $this->estado_nov->FldTagValue(3):
							$this->estado_nov->ViewValue .= $this->estado_nov->FldTagCaption(3) <> "" ? $this->estado_nov->FldTagCaption(3) : trim($arwrk[$ari]);
							break;
						case $this->estado_nov->FldTagValue(4):
							$this->estado_nov->ViewValue .= $this->estado_nov->FldTagCaption(4) <> "" ? $this->estado_nov->FldTagCaption(4) : trim($arwrk[$ari]);
							break;
						default:
							$this->estado_nov->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->estado_nov->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->estado_nov->ViewValue = NULL;
			}
			$this->estado_nov->ViewCustomAttributes = "";

			// id_afiliado
			$this->id_afiliado->LinkCustomAttributes = "";
			$this->id_afiliado->HrefValue = "";
			$this->id_afiliado->TooltipValue = "";

			// apell_afiliado
			$this->apell_afiliado->LinkCustomAttributes = "";
			$this->apell_afiliado->HrefValue = "";
			$this->apell_afiliado->TooltipValue = "";

			// dociden_afiliado
			$this->dociden_afiliado->LinkCustomAttributes = "";
			$this->dociden_afiliado->HrefValue = "";
			$this->dociden_afiliado->TooltipValue = "";

			// nomb_afiliado
			$this->nomb_afiliado->LinkCustomAttributes = "";
			$this->nomb_afiliado->HrefValue = "";
			$this->nomb_afiliado->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id_afiliado
			$this->id_afiliado->EditCustomAttributes = "";
			$this->id_afiliado->EditValue = ew_HtmlEncode($this->id_afiliado->AdvancedSearch->SearchValue);
			$this->id_afiliado->PlaceHolder = ew_RemoveHtml($this->id_afiliado->FldCaption());

			// apell_afiliado
			$this->apell_afiliado->EditCustomAttributes = "";
			$this->apell_afiliado->EditValue = ew_HtmlEncode($this->apell_afiliado->AdvancedSearch->SearchValue);
			$this->apell_afiliado->PlaceHolder = ew_RemoveHtml($this->apell_afiliado->FldCaption());

			// dociden_afiliado
			$this->dociden_afiliado->EditCustomAttributes = "";
			$this->dociden_afiliado->EditValue = ew_HtmlEncode($this->dociden_afiliado->AdvancedSearch->SearchValue);
			$this->dociden_afiliado->PlaceHolder = ew_RemoveHtml($this->dociden_afiliado->FldCaption());

			// nomb_afiliado
			$this->nomb_afiliado->EditCustomAttributes = "";
			$this->nomb_afiliado->EditValue = ew_HtmlEncode($this->nomb_afiliado->AdvancedSearch->SearchValue);
			$this->nomb_afiliado->PlaceHolder = ew_RemoveHtml($this->nomb_afiliado->FldCaption());

			// obs_nov
			$this->obs_nov->EditCustomAttributes = "";
			$this->obs_nov->EditValue = $this->obs_nov->AdvancedSearch->SearchValue;
			$this->obs_nov->PlaceHolder = ew_RemoveHtml($this->obs_nov->FldCaption());

			// fe_nov
			$this->fe_nov->EditCustomAttributes = "";
			$this->fe_nov->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fe_nov->AdvancedSearch->SearchValue, 5), 5));
			$this->fe_nov->PlaceHolder = ew_RemoveHtml($this->fe_nov->FldCaption());

			// estado_nov
			$this->estado_nov->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->estado_nov->FldTagValue(1), $this->estado_nov->FldTagCaption(1) <> "" ? $this->estado_nov->FldTagCaption(1) : $this->estado_nov->FldTagValue(1));
			$arwrk[] = array($this->estado_nov->FldTagValue(2), $this->estado_nov->FldTagCaption(2) <> "" ? $this->estado_nov->FldTagCaption(2) : $this->estado_nov->FldTagValue(2));
			$arwrk[] = array($this->estado_nov->FldTagValue(3), $this->estado_nov->FldTagCaption(3) <> "" ? $this->estado_nov->FldTagCaption(3) : $this->estado_nov->FldTagValue(3));
			$arwrk[] = array($this->estado_nov->FldTagValue(4), $this->estado_nov->FldTagCaption(4) <> "" ? $this->estado_nov->FldTagCaption(4) : $this->estado_nov->FldTagValue(4));
			$this->estado_nov->EditValue = $arwrk;
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
		if (!ew_CheckInteger($this->id_afiliado->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id_afiliado->FldErrMsg());
		}
		if (!ew_CheckDate($this->fe_nov->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fe_nov->FldErrMsg());
		}

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
		$this->id_afiliado->AdvancedSearch->Load();
		$this->apell_afiliado->AdvancedSearch->Load();
		$this->dociden_afiliado->AdvancedSearch->Load();
		$this->nomb_afiliado->AdvancedSearch->Load();
		$this->obs_nov->AdvancedSearch->Load();
		$this->fe_nov->AdvancedSearch->Load();
		$this->estado_nov->AdvancedSearch->Load();
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
if (!isset($v_novedades_list)) $v_novedades_list = new cv_novedades_list();

// Page init
$v_novedades_list->Page_Init();

// Page main
$v_novedades_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$v_novedades_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var v_novedades_list = new ew_Page("v_novedades_list");
v_novedades_list.PageID = "list"; // Page ID
var EW_PAGE_ID = v_novedades_list.PageID; // For backward compatibility

// Form object
var fv_novedadeslist = new ew_Form("fv_novedadeslist");
fv_novedadeslist.FormKeyCountName = '<?php echo $v_novedades_list->FormKeyCountName ?>';

// Form_CustomValidate event
fv_novedadeslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fv_novedadeslist.ValidateRequired = true;
<?php } else { ?>
fv_novedadeslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fv_novedadeslistsrch = new ew_Form("fv_novedadeslistsrch");

// Validate function for search
fv_novedadeslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_id_afiliado");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($v_novedades->id_afiliado->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_fe_nov");
	if (elm && !ew_CheckDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($v_novedades->fe_nov->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fv_novedadeslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fv_novedadeslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fv_novedadeslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php if ($v_novedades_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $v_novedades_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$v_novedades_list->TotalRecs = $v_novedades->SelectRecordCount();
	} else {
		if ($v_novedades_list->Recordset = $v_novedades_list->LoadRecordset())
			$v_novedades_list->TotalRecs = $v_novedades_list->Recordset->RecordCount();
	}
	$v_novedades_list->StartRec = 1;
	if ($v_novedades_list->DisplayRecs <= 0 || ($v_novedades->Export <> "" && $v_novedades->ExportAll)) // Display all records
		$v_novedades_list->DisplayRecs = $v_novedades_list->TotalRecs;
	if (!($v_novedades->Export <> "" && $v_novedades->ExportAll))
		$v_novedades_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$v_novedades_list->Recordset = $v_novedades_list->LoadRecordset($v_novedades_list->StartRec-1, $v_novedades_list->DisplayRecs);
$v_novedades_list->RenderOtherOptions();
?>
<?php if ($v_novedades->Export == "" && $v_novedades->CurrentAction == "") { ?>
<form name="fv_novedadeslistsrch" id="fv_novedadeslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<div class="accordion ewDisplayTable ewSearchTable" id="fv_novedadeslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fv_novedadeslistsrch_SearchGroup" href="#fv_novedadeslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fv_novedadeslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fv_novedadeslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="v_novedades">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$v_novedades_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$v_novedades->RowType = EW_ROWTYPE_SEARCH;

// Render row
$v_novedades->ResetAttrs();
$v_novedades_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($v_novedades->id_afiliado->Visible) { // id_afiliado ?>
	<span id="xsc_id_afiliado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $v_novedades->id_afiliado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_afiliado" id="z_id_afiliado" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_id_afiliado" name="x_id_afiliado" id="x_id_afiliado" size="30" placeholder="<?php echo ew_HtmlEncode($v_novedades->id_afiliado->PlaceHolder) ?>" value="<?php echo $v_novedades->id_afiliado->EditValue ?>"<?php echo $v_novedades->id_afiliado->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($v_novedades->apell_afiliado->Visible) { // apell_afiliado ?>
	<span id="xsc_apell_afiliado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $v_novedades->apell_afiliado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apell_afiliado" id="z_apell_afiliado" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_apell_afiliado" name="x_apell_afiliado" id="x_apell_afiliado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($v_novedades->apell_afiliado->PlaceHolder) ?>" value="<?php echo $v_novedades->apell_afiliado->EditValue ?>"<?php echo $v_novedades->apell_afiliado->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($v_novedades->dociden_afiliado->Visible) { // dociden_afiliado ?>
	<span id="xsc_dociden_afiliado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $v_novedades->dociden_afiliado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_dociden_afiliado" id="z_dociden_afiliado" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_dociden_afiliado" name="x_dociden_afiliado" id="x_dociden_afiliado" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($v_novedades->dociden_afiliado->PlaceHolder) ?>" value="<?php echo $v_novedades->dociden_afiliado->EditValue ?>"<?php echo $v_novedades->dociden_afiliado->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($v_novedades->nomb_afiliado->Visible) { // nomb_afiliado ?>
	<span id="xsc_nomb_afiliado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $v_novedades->nomb_afiliado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nomb_afiliado" id="z_nomb_afiliado" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_nomb_afiliado" name="x_nomb_afiliado" id="x_nomb_afiliado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($v_novedades->nomb_afiliado->PlaceHolder) ?>" value="<?php echo $v_novedades->nomb_afiliado->EditValue ?>"<?php echo $v_novedades->nomb_afiliado->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($v_novedades->fe_nov->Visible) { // fe_nov ?>
	<span id="xsc_fe_nov" class="ewCell">
		<span class="ewSearchCaption"><?php echo $v_novedades->fe_nov->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_fe_nov" id="z_fe_nov" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_fe_nov" name="x_fe_nov" id="x_fe_nov" placeholder="<?php echo ew_HtmlEncode($v_novedades->fe_nov->PlaceHolder) ?>" value="<?php echo $v_novedades->fe_nov->EditValue ?>"<?php echo $v_novedades->fe_nov->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $v_novedades_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ResetSearch") ?></a>
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
<?php $v_novedades_list->ShowPageHeader(); ?>
<?php
$v_novedades_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<form name="fv_novedadeslist" id="fv_novedadeslist" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="v_novedades">
<div id="gmp_v_novedades" class="ewGridMiddlePanel">
<?php if ($v_novedades_list->TotalRecs > 0) { ?>
<table id="tbl_v_novedadeslist" class="ewTable ewTableSeparate">
<?php echo $v_novedades->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$v_novedades_list->RenderListOptions();

// Render list options (header, left)
$v_novedades_list->ListOptions->Render("header", "left");
?>
<?php if ($v_novedades->id_afiliado->Visible) { // id_afiliado ?>
	<?php if ($v_novedades->SortUrl($v_novedades->id_afiliado) == "") { ?>
		<td><div id="elh_v_novedades_id_afiliado" class="v_novedades_id_afiliado"><div class="ewTableHeaderCaption"><?php echo $v_novedades->id_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_novedades->SortUrl($v_novedades->id_afiliado) ?>',1);"><div id="elh_v_novedades_id_afiliado" class="v_novedades_id_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_novedades->id_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_novedades->id_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_novedades->id_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($v_novedades->apell_afiliado->Visible) { // apell_afiliado ?>
	<?php if ($v_novedades->SortUrl($v_novedades->apell_afiliado) == "") { ?>
		<td><div id="elh_v_novedades_apell_afiliado" class="v_novedades_apell_afiliado"><div class="ewTableHeaderCaption"><?php echo $v_novedades->apell_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_novedades->SortUrl($v_novedades->apell_afiliado) ?>',1);"><div id="elh_v_novedades_apell_afiliado" class="v_novedades_apell_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_novedades->apell_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_novedades->apell_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_novedades->apell_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($v_novedades->dociden_afiliado->Visible) { // dociden_afiliado ?>
	<?php if ($v_novedades->SortUrl($v_novedades->dociden_afiliado) == "") { ?>
		<td><div id="elh_v_novedades_dociden_afiliado" class="v_novedades_dociden_afiliado"><div class="ewTableHeaderCaption"><?php echo $v_novedades->dociden_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_novedades->SortUrl($v_novedades->dociden_afiliado) ?>',1);"><div id="elh_v_novedades_dociden_afiliado" class="v_novedades_dociden_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_novedades->dociden_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_novedades->dociden_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_novedades->dociden_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($v_novedades->nomb_afiliado->Visible) { // nomb_afiliado ?>
	<?php if ($v_novedades->SortUrl($v_novedades->nomb_afiliado) == "") { ?>
		<td><div id="elh_v_novedades_nomb_afiliado" class="v_novedades_nomb_afiliado"><div class="ewTableHeaderCaption"><?php echo $v_novedades->nomb_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_novedades->SortUrl($v_novedades->nomb_afiliado) ?>',1);"><div id="elh_v_novedades_nomb_afiliado" class="v_novedades_nomb_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_novedades->nomb_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_novedades->nomb_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_novedades->nomb_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($v_novedades->obs_nov->Visible) { // obs_nov ?>
	<?php if ($v_novedades->SortUrl($v_novedades->obs_nov) == "") { ?>
		<td><div id="elh_v_novedades_obs_nov" class="v_novedades_obs_nov"><div class="ewTableHeaderCaption"><?php echo $v_novedades->obs_nov->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_novedades->SortUrl($v_novedades->obs_nov) ?>',1);"><div id="elh_v_novedades_obs_nov" class="v_novedades_obs_nov">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_novedades->obs_nov->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_novedades->obs_nov->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_novedades->obs_nov->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($v_novedades->fe_nov->Visible) { // fe_nov ?>
	<?php if ($v_novedades->SortUrl($v_novedades->fe_nov) == "") { ?>
		<td><div id="elh_v_novedades_fe_nov" class="v_novedades_fe_nov"><div class="ewTableHeaderCaption"><?php echo $v_novedades->fe_nov->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_novedades->SortUrl($v_novedades->fe_nov) ?>',1);"><div id="elh_v_novedades_fe_nov" class="v_novedades_fe_nov">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_novedades->fe_nov->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_novedades->fe_nov->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_novedades->fe_nov->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($v_novedades->estado_nov->Visible) { // estado_nov ?>
	<?php if ($v_novedades->SortUrl($v_novedades->estado_nov) == "") { ?>
		<td><div id="elh_v_novedades_estado_nov" class="v_novedades_estado_nov"><div class="ewTableHeaderCaption"><?php echo $v_novedades->estado_nov->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_novedades->SortUrl($v_novedades->estado_nov) ?>',1);"><div id="elh_v_novedades_estado_nov" class="v_novedades_estado_nov">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_novedades->estado_nov->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_novedades->estado_nov->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_novedades->estado_nov->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$v_novedades_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($v_novedades->ExportAll && $v_novedades->Export <> "") {
	$v_novedades_list->StopRec = $v_novedades_list->TotalRecs;
} else {

	// Set the last record to display
	if ($v_novedades_list->TotalRecs > $v_novedades_list->StartRec + $v_novedades_list->DisplayRecs - 1)
		$v_novedades_list->StopRec = $v_novedades_list->StartRec + $v_novedades_list->DisplayRecs - 1;
	else
		$v_novedades_list->StopRec = $v_novedades_list->TotalRecs;
}
$v_novedades_list->RecCnt = $v_novedades_list->StartRec - 1;
if ($v_novedades_list->Recordset && !$v_novedades_list->Recordset->EOF) {
	$v_novedades_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $v_novedades_list->StartRec > 1)
		$v_novedades_list->Recordset->Move($v_novedades_list->StartRec - 1);
} elseif (!$v_novedades->AllowAddDeleteRow && $v_novedades_list->StopRec == 0) {
	$v_novedades_list->StopRec = $v_novedades->GridAddRowCount;
}

// Initialize aggregate
$v_novedades->RowType = EW_ROWTYPE_AGGREGATEINIT;
$v_novedades->ResetAttrs();
$v_novedades_list->RenderRow();
while ($v_novedades_list->RecCnt < $v_novedades_list->StopRec) {
	$v_novedades_list->RecCnt++;
	if (intval($v_novedades_list->RecCnt) >= intval($v_novedades_list->StartRec)) {
		$v_novedades_list->RowCnt++;

		// Set up key count
		$v_novedades_list->KeyCount = $v_novedades_list->RowIndex;

		// Init row class and style
		$v_novedades->ResetAttrs();
		$v_novedades->CssClass = "";
		if ($v_novedades->CurrentAction == "gridadd") {
		} else {
			$v_novedades_list->LoadRowValues($v_novedades_list->Recordset); // Load row values
		}
		$v_novedades->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$v_novedades->RowAttrs = array_merge($v_novedades->RowAttrs, array('data-rowindex'=>$v_novedades_list->RowCnt, 'id'=>'r' . $v_novedades_list->RowCnt . '_v_novedades', 'data-rowtype'=>$v_novedades->RowType));

		// Render row
		$v_novedades_list->RenderRow();

		// Render list options
		$v_novedades_list->RenderListOptions();
?>
	<tr<?php echo $v_novedades->RowAttributes() ?>>
<?php

// Render list options (body, left)
$v_novedades_list->ListOptions->Render("body", "left", $v_novedades_list->RowCnt);
?>
	<?php if ($v_novedades->id_afiliado->Visible) { // id_afiliado ?>
		<td<?php echo $v_novedades->id_afiliado->CellAttributes() ?>>
<span<?php echo $v_novedades->id_afiliado->ViewAttributes() ?>>
<?php echo $v_novedades->id_afiliado->ListViewValue() ?></span>
<a id="<?php echo $v_novedades_list->PageObjName . "_row_" . $v_novedades_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_novedades->apell_afiliado->Visible) { // apell_afiliado ?>
		<td<?php echo $v_novedades->apell_afiliado->CellAttributes() ?>>
<span<?php echo $v_novedades->apell_afiliado->ViewAttributes() ?>>
<?php echo $v_novedades->apell_afiliado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_novedades->dociden_afiliado->Visible) { // dociden_afiliado ?>
		<td<?php echo $v_novedades->dociden_afiliado->CellAttributes() ?>>
<span<?php echo $v_novedades->dociden_afiliado->ViewAttributes() ?>>
<?php echo $v_novedades->dociden_afiliado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_novedades->nomb_afiliado->Visible) { // nomb_afiliado ?>
		<td<?php echo $v_novedades->nomb_afiliado->CellAttributes() ?>>
<span<?php echo $v_novedades->nomb_afiliado->ViewAttributes() ?>>
<?php echo $v_novedades->nomb_afiliado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_novedades->obs_nov->Visible) { // obs_nov ?>
		<td<?php echo $v_novedades->obs_nov->CellAttributes() ?>>
<span<?php echo $v_novedades->obs_nov->ViewAttributes() ?>>
<?php echo $v_novedades->obs_nov->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_novedades->fe_nov->Visible) { // fe_nov ?>
		<td<?php echo $v_novedades->fe_nov->CellAttributes() ?>>
<span<?php echo $v_novedades->fe_nov->ViewAttributes() ?>>
<?php echo $v_novedades->fe_nov->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_novedades->estado_nov->Visible) { // estado_nov ?>
		<td<?php echo $v_novedades->estado_nov->CellAttributes() ?>>
<span<?php echo $v_novedades->estado_nov->ViewAttributes() ?>>
<?php echo $v_novedades->estado_nov->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$v_novedades_list->ListOptions->Render("body", "right", $v_novedades_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($v_novedades->CurrentAction <> "gridadd")
		$v_novedades_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($v_novedades->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($v_novedades_list->Recordset)
	$v_novedades_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($v_novedades->CurrentAction <> "gridadd" && $v_novedades->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($v_novedades_list->Pager)) $v_novedades_list->Pager = new cPrevNextPager($v_novedades_list->StartRec, $v_novedades_list->DisplayRecs, $v_novedades_list->TotalRecs) ?>
<?php if ($v_novedades_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($v_novedades_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $v_novedades_list->PageUrl() ?>start=<?php echo $v_novedades_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($v_novedades_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $v_novedades_list->PageUrl() ?>start=<?php echo $v_novedades_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $v_novedades_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($v_novedades_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $v_novedades_list->PageUrl() ?>start=<?php echo $v_novedades_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($v_novedades_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $v_novedades_list->PageUrl() ?>start=<?php echo $v_novedades_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $v_novedades_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $v_novedades_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $v_novedades_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $v_novedades_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($v_novedades_list->SearchWhere == "0=101") { ?>
	<p><?php echo $Language->Phrase("EnterSearchCriteria") ?></p>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
	<?php } ?>
<?php } ?>
</td>
</tr></table>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($v_novedades_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
</td></tr></table>
<script type="text/javascript">
fv_novedadeslistsrch.Init();
fv_novedadeslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$v_novedades_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$v_novedades_list->Page_Terminate();
?>
