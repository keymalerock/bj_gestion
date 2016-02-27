<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "novedadinfo.php" ?>
<?php include_once "v_usuariosinfo.php" ?>
<?php include_once "respuestagridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$novedad_list = NULL; // Initialize page object first

class cnovedad_list extends cnovedad {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'novedad';

	// Page object name
	var $PageObjName = 'novedad_list';

	// Grid form hidden field names
	var $FormName = 'fnovedadlist';
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

		// Table object (novedad)
		if (!isset($GLOBALS["novedad"]) || get_class($GLOBALS["novedad"]) == "cnovedad") {
			$GLOBALS["novedad"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["novedad"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "novedadadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "novedaddelete.php";
		$this->MultiUpdateUrl = "novedadupdate.php";

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'novedad', TRUE);

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
		$this->id_novedad->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->id_novedad->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_novedad->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id_novedad, FALSE); // id_novedad
		$this->BuildSearchSql($sWhere, $this->id_afiliado, FALSE); // id_afiliado
		$this->BuildSearchSql($sWhere, $this->fe_nov, FALSE); // fe_nov
		$this->BuildSearchSql($sWhere, $this->estado_nov, FALSE); // estado_nov

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->id_novedad->AdvancedSearch->Save(); // id_novedad
			$this->id_afiliado->AdvancedSearch->Save(); // id_afiliado
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
		if ($this->id_novedad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_afiliado->AdvancedSearch->IssetSession())
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
		$this->id_novedad->AdvancedSearch->UnsetSession();
		$this->id_afiliado->AdvancedSearch->UnsetSession();
		$this->fe_nov->AdvancedSearch->UnsetSession();
		$this->estado_nov->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->id_novedad->AdvancedSearch->Load();
		$this->id_afiliado->AdvancedSearch->Load();
		$this->fe_nov->AdvancedSearch->Load();
		$this->estado_nov->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_novedad); // id_novedad
			$this->UpdateSort($this->id_afiliado); // id_afiliado
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
				$this->id_novedad->setSort("");
				$this->id_afiliado->setSort("");
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

		// "detail_respuesta"
		$item = &$this->ListOptions->Add("detail_respuesta");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'respuesta') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["respuesta_grid"])) $GLOBALS["respuesta_grid"] = new crespuesta_grid;

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

		// "detail_respuesta"
		$oListOpt = &$this->ListOptions->Items["detail_respuesta"];
		if ($Security->AllowList(CurrentProjectID() . 'respuesta')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("respuesta", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("respuestalist.php?" . EW_TABLE_SHOW_MASTER . "=novedad&id_novedad=" . strval($this->id_novedad->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["respuesta_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'respuesta')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=respuesta")) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "respuesta";
			}
			if ($GLOBALS["respuesta_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'respuesta')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=respuesta")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "respuesta";
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
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_novedad->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
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
		$item->Visible = ($this->AddUrl <> "");
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_respuesta");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=respuesta") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["respuesta"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["respuesta"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'respuesta'));
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "respuesta";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink) . "\">" . $Language->Phrase("AddMasterDetailLink") . "</a>";
			$item->Visible = ($DetailTableLink <> "");

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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fnovedadlist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// id_novedad

		$this->id_novedad->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_novedad"]);
		if ($this->id_novedad->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_novedad->AdvancedSearch->SearchOperator = @$_GET["z_id_novedad"];

		// id_afiliado
		$this->id_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_afiliado"]);
		if ($this->id_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_id_afiliado"];

		// fe_nov
		$this->fe_nov->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fe_nov"]);
		if ($this->fe_nov->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fe_nov->AdvancedSearch->SearchOperator = @$_GET["z_fe_nov"];
		$this->fe_nov->AdvancedSearch->SearchCondition = @$_GET["v_fe_nov"];
		$this->fe_nov->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_fe_nov"]);
		if ($this->fe_nov->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->fe_nov->AdvancedSearch->SearchOperator2 = @$_GET["w_fe_nov"];

		// estado_nov
		$this->estado_nov->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_estado_nov"]);
		if ($this->estado_nov->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->estado_nov->AdvancedSearch->SearchOperator = @$_GET["z_estado_nov"];
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_novedad")) <> "")
			$this->id_novedad->CurrentValue = $this->getKey("id_novedad"); // id_novedad
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

			// id_novedad
			$this->id_novedad->LinkCustomAttributes = "";
			$this->id_novedad->HrefValue = "";
			$this->id_novedad->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id_novedad
			$this->id_novedad->EditCustomAttributes = "";
			$this->id_novedad->EditValue = ew_HtmlEncode($this->id_novedad->AdvancedSearch->SearchValue);
			$this->id_novedad->PlaceHolder = ew_RemoveHtml($this->id_novedad->FldCaption());

			// id_afiliado
			$this->id_afiliado->EditCustomAttributes = "";
			$this->id_afiliado->EditValue = ew_HtmlEncode($this->id_afiliado->AdvancedSearch->SearchValue);
			if (strval($this->id_afiliado->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id_afiliado`" . ew_SearchString("=", $this->id_afiliado->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER);
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
					$this->id_afiliado->EditValue = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->id_afiliado->EditValue .= ew_ValueSeparator(1,$this->id_afiliado) . ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->id_afiliado->EditValue .= ew_ValueSeparator(2,$this->id_afiliado) . ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$rswrk->Close();
				} else {
					$this->id_afiliado->EditValue = ew_HtmlEncode($this->id_afiliado->AdvancedSearch->SearchValue);
				}
			} else {
				$this->id_afiliado->EditValue = NULL;
			}
			$this->id_afiliado->PlaceHolder = ew_RemoveHtml($this->id_afiliado->FldCaption());

			// obs_nov
			$this->obs_nov->EditCustomAttributes = "";
			$this->obs_nov->EditValue = $this->obs_nov->AdvancedSearch->SearchValue;
			$this->obs_nov->PlaceHolder = ew_RemoveHtml($this->obs_nov->FldCaption());

			// fe_nov
			$this->fe_nov->EditCustomAttributes = "";
			$this->fe_nov->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fe_nov->AdvancedSearch->SearchValue, 5), 5));
			$this->fe_nov->PlaceHolder = ew_RemoveHtml($this->fe_nov->FldCaption());
			$this->fe_nov->EditCustomAttributes = "";
			$this->fe_nov->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fe_nov->AdvancedSearch->SearchValue2, 5), 5));
			$this->fe_nov->PlaceHolder = ew_RemoveHtml($this->fe_nov->FldCaption());

			// estado_nov
			$this->estado_nov->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->estado_nov->FldTagValue(1), $this->estado_nov->FldTagCaption(1) <> "" ? $this->estado_nov->FldTagCaption(1) : $this->estado_nov->FldTagValue(1));
			$arwrk[] = array($this->estado_nov->FldTagValue(2), $this->estado_nov->FldTagCaption(2) <> "" ? $this->estado_nov->FldTagCaption(2) : $this->estado_nov->FldTagValue(2));
			$arwrk[] = array($this->estado_nov->FldTagValue(3), $this->estado_nov->FldTagCaption(3) <> "" ? $this->estado_nov->FldTagCaption(3) : $this->estado_nov->FldTagValue(3));
			$arwrk[] = array($this->estado_nov->FldTagValue(4), $this->estado_nov->FldTagCaption(4) <> "" ? $this->estado_nov->FldTagCaption(4) : $this->estado_nov->FldTagValue(4));
			$arwrk[] = array($this->estado_nov->FldTagValue(5), $this->estado_nov->FldTagCaption(5) <> "" ? $this->estado_nov->FldTagCaption(5) : $this->estado_nov->FldTagValue(5));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
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
		if (!ew_CheckInteger($this->id_novedad->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id_novedad->FldErrMsg());
		}
		if (!ew_CheckInteger($this->id_afiliado->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id_afiliado->FldErrMsg());
		}
		if (!ew_CheckDate($this->fe_nov->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fe_nov->FldErrMsg());
		}
		if (!ew_CheckDate($this->fe_nov->AdvancedSearch->SearchValue2)) {
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
		$this->id_novedad->AdvancedSearch->Load();
		$this->id_afiliado->AdvancedSearch->Load();
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
	   if (empty(CurrentUserID())) {
			$this->obs_nov->Visible = FALSE; 
			$this->id_novedad->Visible = FALSE; 
			$this->id_afiliado->Visible = FALSE;              
			 $this->fe_nov->Visible = FALSE;              
			 $this->estado_nov->Visible = FALSE; 

			// $this->st_resp_nov->Visible = FALSE; 
	   }                                                            
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
if (!isset($novedad_list)) $novedad_list = new cnovedad_list();

// Page init
$novedad_list->Page_Init();

// Page main
$novedad_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$novedad_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var novedad_list = new ew_Page("novedad_list");
novedad_list.PageID = "list"; // Page ID
var EW_PAGE_ID = novedad_list.PageID; // For backward compatibility

// Form object
var fnovedadlist = new ew_Form("fnovedadlist");
fnovedadlist.FormKeyCountName = '<?php echo $novedad_list->FormKeyCountName ?>';

// Form_CustomValidate event
fnovedadlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnovedadlist.ValidateRequired = true;
<?php } else { ?>
fnovedadlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fnovedadlist.Lists["x_id_afiliado"] = {"LinkField":"x_id_afiliado","Ajax":true,"AutoFill":false,"DisplayFields":["x_dociden_afiliado","x_apell_afiliado","x_nomb_afiliado",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fnovedadlistsrch = new ew_Form("fnovedadlistsrch");

// Validate function for search
fnovedadlistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_id_novedad");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($novedad->id_novedad->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_id_afiliado");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($novedad->id_afiliado->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_fe_nov");
	if (elm && !ew_CheckDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($novedad->fe_nov->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fnovedadlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnovedadlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fnovedadlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fnovedadlistsrch.Lists["x_id_afiliado"] = {"LinkField":"x_id_afiliado","Ajax":true,"AutoFill":false,"DisplayFields":["x_dociden_afiliado","x_apell_afiliado","x_nomb_afiliado",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php if ($novedad_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $novedad_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$novedad_list->TotalRecs = $novedad->SelectRecordCount();
	} else {
		if ($novedad_list->Recordset = $novedad_list->LoadRecordset())
			$novedad_list->TotalRecs = $novedad_list->Recordset->RecordCount();
	}
	$novedad_list->StartRec = 1;
	if ($novedad_list->DisplayRecs <= 0 || ($novedad->Export <> "" && $novedad->ExportAll)) // Display all records
		$novedad_list->DisplayRecs = $novedad_list->TotalRecs;
	if (!($novedad->Export <> "" && $novedad->ExportAll))
		$novedad_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$novedad_list->Recordset = $novedad_list->LoadRecordset($novedad_list->StartRec-1, $novedad_list->DisplayRecs);
$novedad_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($novedad->Export == "" && $novedad->CurrentAction == "") { ?>
<form name="fnovedadlistsrch" id="fnovedadlistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<div class="accordion ewDisplayTable ewSearchTable" id="fnovedadlistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fnovedadlistsrch_SearchGroup" href="#fnovedadlistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fnovedadlistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fnovedadlistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="novedad">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$novedad_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$novedad->RowType = EW_ROWTYPE_SEARCH;

// Render row
$novedad->ResetAttrs();
$novedad_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($novedad->id_novedad->Visible) { // id_novedad ?>
	<span id="xsc_id_novedad" class="ewCell">
		<span class="ewSearchCaption"><?php echo $novedad->id_novedad->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_novedad" id="z_id_novedad" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_id_novedad" name="x_id_novedad" id="x_id_novedad" placeholder="<?php echo ew_HtmlEncode($novedad->id_novedad->PlaceHolder) ?>" value="<?php echo $novedad->id_novedad->EditValue ?>"<?php echo $novedad->id_novedad->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($novedad->id_afiliado->Visible) { // id_afiliado ?>
	<span id="xsc_id_afiliado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $novedad->id_afiliado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_afiliado" id="z_id_afiliado" value="="></span>
		<span class="control-group ewSearchField">
<?php
	$wrkonchange = trim(" " . @$novedad->id_afiliado->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$novedad->id_afiliado->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_afiliado" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_id_afiliado" id="sv_x_id_afiliado" value="<?php echo $novedad->id_afiliado->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($novedad->id_afiliado->PlaceHolder) ?>"<?php echo $novedad->id_afiliado->EditAttributes() ?>>&nbsp;<span id="em_x_id_afiliado" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_id_afiliado" style="display: inline; z-index: 8980"></div>
</span>
<input type="hidden" data-field="x_id_afiliado" name="x_id_afiliado" id="x_id_afiliado" value="<?php echo ew_HtmlEncode($novedad->id_afiliado->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id_afiliado`, `dociden_afiliado` AS `DispFld`, `apell_afiliado` AS `Disp2Fld`, `nomb_afiliado` AS `Disp3Fld` FROM `afiliado`";
$sWhereWrk = "`dociden_afiliado` LIKE '{query_value}%' OR CONCAT(`dociden_afiliado`,'" . ew_ValueSeparator(1, $Page->id_afiliado) . "',`apell_afiliado`,'" . ew_ValueSeparator(2, $Page->id_afiliado) . "',`nomb_afiliado`) LIKE '{query_value}%'";

// Call Lookup selecting
$novedad->Lookup_Selecting($novedad->id_afiliado, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_id_afiliado" id="q_x_id_afiliado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_id_afiliado", fnovedadlistsrch, true, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_id_afiliado") + ar[i] : "";
	return dv;
}
fnovedadlistsrch.AutoSuggests["x_id_afiliado"] = oas;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($novedad->fe_nov->Visible) { // fe_nov ?>
	<span id="xsc_fe_nov" class="ewCell">
		<span class="ewSearchCaption"><?php echo $novedad->fe_nov->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_fe_nov" id="z_fe_nov" value="BETWEEN"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_fe_nov" name="x_fe_nov" id="x_fe_nov" placeholder="<?php echo ew_HtmlEncode($novedad->fe_nov->PlaceHolder) ?>" value="<?php echo $novedad->fe_nov->EditValue ?>"<?php echo $novedad->fe_nov->EditAttributes() ?>>
<?php if (!$novedad->fe_nov->ReadOnly && !$novedad->fe_nov->Disabled && @$novedad->fe_nov->EditAttrs["readonly"] == "" && @$novedad->fe_nov->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_fe_nov" name="cal_x_fe_nov" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fnovedadlistsrch", "x_fe_nov", "%Y/%m/%d");
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_fe_nov">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_fe_nov">
<input type="text" data-field="x_fe_nov" name="y_fe_nov" id="y_fe_nov" placeholder="<?php echo ew_HtmlEncode($novedad->fe_nov->PlaceHolder) ?>" value="<?php echo $novedad->fe_nov->EditValue2 ?>"<?php echo $novedad->fe_nov->EditAttributes() ?>>
<?php if (!$novedad->fe_nov->ReadOnly && !$novedad->fe_nov->Disabled && @$novedad->fe_nov->EditAttrs["readonly"] == "" && @$novedad->fe_nov->EditAttrs["disabled"] == "") { ?>
<button id="cal_y_fe_nov" name="cal_y_fe_nov" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fnovedadlistsrch", "y_fe_nov", "%Y/%m/%d");
</script>
<?php } ?>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $novedad_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $novedad_list->ShowPageHeader(); ?>
<?php
$novedad_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<form name="fnovedadlist" id="fnovedadlist" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="novedad">
<div id="gmp_novedad" class="ewGridMiddlePanel">
<?php if ($novedad_list->TotalRecs > 0) { ?>
<table id="tbl_novedadlist" class="ewTable ewTableSeparate">
<?php echo $novedad->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$novedad_list->RenderListOptions();

// Render list options (header, left)
$novedad_list->ListOptions->Render("header", "left");
?>
<?php if ($novedad->id_novedad->Visible) { // id_novedad ?>
	<?php if ($novedad->SortUrl($novedad->id_novedad) == "") { ?>
		<td><div id="elh_novedad_id_novedad" class="novedad_id_novedad"><div class="ewTableHeaderCaption"><?php echo $novedad->id_novedad->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $novedad->SortUrl($novedad->id_novedad) ?>',1);"><div id="elh_novedad_id_novedad" class="novedad_id_novedad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $novedad->id_novedad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($novedad->id_novedad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($novedad->id_novedad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($novedad->id_afiliado->Visible) { // id_afiliado ?>
	<?php if ($novedad->SortUrl($novedad->id_afiliado) == "") { ?>
		<td><div id="elh_novedad_id_afiliado" class="novedad_id_afiliado"><div class="ewTableHeaderCaption"><?php echo $novedad->id_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $novedad->SortUrl($novedad->id_afiliado) ?>',1);"><div id="elh_novedad_id_afiliado" class="novedad_id_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $novedad->id_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($novedad->id_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($novedad->id_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($novedad->obs_nov->Visible) { // obs_nov ?>
	<?php if ($novedad->SortUrl($novedad->obs_nov) == "") { ?>
		<td><div id="elh_novedad_obs_nov" class="novedad_obs_nov"><div class="ewTableHeaderCaption"><?php echo $novedad->obs_nov->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $novedad->SortUrl($novedad->obs_nov) ?>',1);"><div id="elh_novedad_obs_nov" class="novedad_obs_nov">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $novedad->obs_nov->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($novedad->obs_nov->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($novedad->obs_nov->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($novedad->fe_nov->Visible) { // fe_nov ?>
	<?php if ($novedad->SortUrl($novedad->fe_nov) == "") { ?>
		<td><div id="elh_novedad_fe_nov" class="novedad_fe_nov"><div class="ewTableHeaderCaption"><?php echo $novedad->fe_nov->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $novedad->SortUrl($novedad->fe_nov) ?>',1);"><div id="elh_novedad_fe_nov" class="novedad_fe_nov">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $novedad->fe_nov->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($novedad->fe_nov->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($novedad->fe_nov->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($novedad->estado_nov->Visible) { // estado_nov ?>
	<?php if ($novedad->SortUrl($novedad->estado_nov) == "") { ?>
		<td><div id="elh_novedad_estado_nov" class="novedad_estado_nov"><div class="ewTableHeaderCaption"><?php echo $novedad->estado_nov->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $novedad->SortUrl($novedad->estado_nov) ?>',1);"><div id="elh_novedad_estado_nov" class="novedad_estado_nov">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $novedad->estado_nov->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($novedad->estado_nov->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($novedad->estado_nov->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$novedad_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($novedad->ExportAll && $novedad->Export <> "") {
	$novedad_list->StopRec = $novedad_list->TotalRecs;
} else {

	// Set the last record to display
	if ($novedad_list->TotalRecs > $novedad_list->StartRec + $novedad_list->DisplayRecs - 1)
		$novedad_list->StopRec = $novedad_list->StartRec + $novedad_list->DisplayRecs - 1;
	else
		$novedad_list->StopRec = $novedad_list->TotalRecs;
}
$novedad_list->RecCnt = $novedad_list->StartRec - 1;
if ($novedad_list->Recordset && !$novedad_list->Recordset->EOF) {
	$novedad_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $novedad_list->StartRec > 1)
		$novedad_list->Recordset->Move($novedad_list->StartRec - 1);
} elseif (!$novedad->AllowAddDeleteRow && $novedad_list->StopRec == 0) {
	$novedad_list->StopRec = $novedad->GridAddRowCount;
}

// Initialize aggregate
$novedad->RowType = EW_ROWTYPE_AGGREGATEINIT;
$novedad->ResetAttrs();
$novedad_list->RenderRow();
while ($novedad_list->RecCnt < $novedad_list->StopRec) {
	$novedad_list->RecCnt++;
	if (intval($novedad_list->RecCnt) >= intval($novedad_list->StartRec)) {
		$novedad_list->RowCnt++;

		// Set up key count
		$novedad_list->KeyCount = $novedad_list->RowIndex;

		// Init row class and style
		$novedad->ResetAttrs();
		$novedad->CssClass = "";
		if ($novedad->CurrentAction == "gridadd") {
		} else {
			$novedad_list->LoadRowValues($novedad_list->Recordset); // Load row values
		}
		$novedad->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$novedad->RowAttrs = array_merge($novedad->RowAttrs, array('data-rowindex'=>$novedad_list->RowCnt, 'id'=>'r' . $novedad_list->RowCnt . '_novedad', 'data-rowtype'=>$novedad->RowType));

		// Render row
		$novedad_list->RenderRow();

		// Render list options
		$novedad_list->RenderListOptions();
?>
	<tr<?php echo $novedad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$novedad_list->ListOptions->Render("body", "left", $novedad_list->RowCnt);
?>
	<?php if ($novedad->id_novedad->Visible) { // id_novedad ?>
		<td<?php echo $novedad->id_novedad->CellAttributes() ?>>
<span<?php echo $novedad->id_novedad->ViewAttributes() ?>>
<?php echo $novedad->id_novedad->ListViewValue() ?></span>
<a id="<?php echo $novedad_list->PageObjName . "_row_" . $novedad_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($novedad->id_afiliado->Visible) { // id_afiliado ?>
		<td<?php echo $novedad->id_afiliado->CellAttributes() ?>>
<span<?php echo $novedad->id_afiliado->ViewAttributes() ?>>
<?php echo $novedad->id_afiliado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($novedad->obs_nov->Visible) { // obs_nov ?>
		<td<?php echo $novedad->obs_nov->CellAttributes() ?>>
<span<?php echo $novedad->obs_nov->ViewAttributes() ?>>
<?php echo $novedad->obs_nov->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($novedad->fe_nov->Visible) { // fe_nov ?>
		<td<?php echo $novedad->fe_nov->CellAttributes() ?>>
<span<?php echo $novedad->fe_nov->ViewAttributes() ?>>
<?php echo $novedad->fe_nov->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($novedad->estado_nov->Visible) { // estado_nov ?>
		<td<?php echo $novedad->estado_nov->CellAttributes() ?>>
<span<?php echo $novedad->estado_nov->ViewAttributes() ?>>
<?php echo $novedad->estado_nov->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$novedad_list->ListOptions->Render("body", "right", $novedad_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($novedad->CurrentAction <> "gridadd")
		$novedad_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($novedad->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($novedad_list->Recordset)
	$novedad_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($novedad->CurrentAction <> "gridadd" && $novedad->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($novedad_list->Pager)) $novedad_list->Pager = new cPrevNextPager($novedad_list->StartRec, $novedad_list->DisplayRecs, $novedad_list->TotalRecs) ?>
<?php if ($novedad_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($novedad_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $novedad_list->PageUrl() ?>start=<?php echo $novedad_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($novedad_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $novedad_list->PageUrl() ?>start=<?php echo $novedad_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $novedad_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($novedad_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $novedad_list->PageUrl() ?>start=<?php echo $novedad_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($novedad_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $novedad_list->PageUrl() ?>start=<?php echo $novedad_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $novedad_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $novedad_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $novedad_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $novedad_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($novedad_list->SearchWhere == "0=101") { ?>
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
	foreach ($novedad_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
</td></tr></table>
<script type="text/javascript">
fnovedadlistsrch.Init();
fnovedadlist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$novedad_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$novedad_list->Page_Terminate();
?>
