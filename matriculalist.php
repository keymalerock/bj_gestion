<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "matriculainfo.php" ?>
<?php include_once "afiliadoinfo.php" ?>
<?php include_once "v_usuariosinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$matricula_list = NULL; // Initialize page object first

class cmatricula_list extends cmatricula {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'matricula';

	// Page object name
	var $PageObjName = 'matricula_list';

	// Grid form hidden field names
	var $FormName = 'fmatriculalist';
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

		// Table object (matricula)
		if (!isset($GLOBALS["matricula"]) || get_class($GLOBALS["matricula"]) == "cmatricula") {
			$GLOBALS["matricula"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["matricula"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "matriculaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "matriculadelete.php";
		$this->MultiUpdateUrl = "matriculaupdate.php";

		// Table object (afiliado)
		if (!isset($GLOBALS['afiliado'])) $GLOBALS['afiliado'] = new cafiliado();

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'matricula', TRUE);

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
		$this->id_matricula->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->id_matricula->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_matricula->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id_matricula, FALSE); // id_matricula
		$this->BuildSearchSql($sWhere, $this->id_afiliado, FALSE); // id_afiliado
		$this->BuildSearchSql($sWhere, $this->tipo_matri, FALSE); // tipo_matri
		$this->BuildSearchSql($sWhere, $this->id_plan, FALSE); // id_plan
		$this->BuildSearchSql($sWhere, $this->termino3_matri, TRUE); // termino3_matri

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->id_matricula->AdvancedSearch->Save(); // id_matricula
			$this->id_afiliado->AdvancedSearch->Save(); // id_afiliado
			$this->tipo_matri->AdvancedSearch->Save(); // tipo_matri
			$this->id_plan->AdvancedSearch->Save(); // id_plan
			$this->termino3_matri->AdvancedSearch->Save(); // termino3_matri
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
		if ($this->id_matricula->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_afiliado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipo_matri->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_plan->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->termino3_matri->AdvancedSearch->IssetSession())
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
		$this->id_matricula->AdvancedSearch->UnsetSession();
		$this->id_afiliado->AdvancedSearch->UnsetSession();
		$this->tipo_matri->AdvancedSearch->UnsetSession();
		$this->id_plan->AdvancedSearch->UnsetSession();
		$this->termino3_matri->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->id_matricula->AdvancedSearch->Load();
		$this->id_afiliado->AdvancedSearch->Load();
		$this->tipo_matri->AdvancedSearch->Load();
		$this->id_plan->AdvancedSearch->Load();
		$this->termino3_matri->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_matricula); // id_matricula
			$this->UpdateSort($this->id_afiliado); // id_afiliado
			$this->UpdateSort($this->tipo_matri); // tipo_matri
			$this->UpdateSort($this->id_plan); // id_plan
			$this->UpdateSort($this->valor_matri); // valor_matri
			$this->UpdateSort($this->valor_men_matri); // valor_men_matri
			$this->UpdateSort($this->conv_matri); // conv_matri
			$this->UpdateSort($this->id_empleado); // id_empleado
			$this->UpdateSort($this->doc4_matri); // doc4_matri
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
				$this->id_matricula->setSort("");
				$this->id_afiliado->setSort("");
				$this->tipo_matri->setSort("");
				$this->id_plan->setSort("");
				$this->valor_matri->setSort("");
				$this->valor_men_matri->setSort("");
				$this->conv_matri->setSort("");
				$this->id_empleado->setSort("");
				$this->doc4_matri->setSort("");
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
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_matricula->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fmatriculalist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// id_matricula

		$this->id_matricula->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_matricula"]);
		if ($this->id_matricula->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_matricula->AdvancedSearch->SearchOperator = @$_GET["z_id_matricula"];

		// id_afiliado
		$this->id_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_afiliado"]);
		if ($this->id_afiliado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_afiliado->AdvancedSearch->SearchOperator = @$_GET["z_id_afiliado"];

		// tipo_matri
		$this->tipo_matri->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tipo_matri"]);
		if ($this->tipo_matri->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tipo_matri->AdvancedSearch->SearchOperator = @$_GET["z_tipo_matri"];

		// id_plan
		$this->id_plan->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_plan"]);
		if ($this->id_plan->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_plan->AdvancedSearch->SearchOperator = @$_GET["z_id_plan"];

		// termino3_matri
		$this->termino3_matri->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_termino3_matri"]);
		if ($this->termino3_matri->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->termino3_matri->AdvancedSearch->SearchOperator = @$_GET["z_termino3_matri"];
		if (is_array($this->termino3_matri->AdvancedSearch->SearchValue)) $this->termino3_matri->AdvancedSearch->SearchValue = implode(",", $this->termino3_matri->AdvancedSearch->SearchValue);
		if (is_array($this->termino3_matri->AdvancedSearch->SearchValue2)) $this->termino3_matri->AdvancedSearch->SearchValue2 = implode(",", $this->termino3_matri->AdvancedSearch->SearchValue2);
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
		$this->id_matricula->setDbValue($rs->fields('id_matricula'));
		$this->id_afiliado->setDbValue($rs->fields('id_afiliado'));
		$this->tipo_matri->setDbValue($rs->fields('tipo_matri'));
		$this->id_plan->setDbValue($rs->fields('id_plan'));
		$this->valor_matri->setDbValue($rs->fields('valor_matri'));
		$this->valor_men_matri->setDbValue($rs->fields('valor_men_matri'));
		$this->conv_matri->setDbValue($rs->fields('conv_matri'));
		$this->id_empleado->setDbValue($rs->fields('id_empleado'));
		$this->bol_matri->setDbValue($rs->fields('bol_matri'));
		$this->cuenta_matri->setDbValue($rs->fields('cuenta_matri'));
		$this->termino1_matri->setDbValue($rs->fields('termino1_matri'));
		$this->termino2_matri->setDbValue($rs->fields('termino2_matri'));
		$this->termino3_matri->setDbValue($rs->fields('termino3_matri'));
		$this->pag_card_matri->setDbValue($rs->fields('pag_card_matri'));
		$this->tipo_card_matri->setDbValue($rs->fields('tipo_card_matri'));
		$this->num_card_matri->setDbValue($rs->fields('num_card_matri'));
		$this->venc_card_matri->setDbValue($rs->fields('venc_card_matri'));
		$this->doc1_matri->setDbValue($rs->fields('doc1_matri'));
		$this->doc2_matri->setDbValue($rs->fields('doc2_matri'));
		$this->doc3_matri->setDbValue($rs->fields('doc3_matri'));
		$this->doc4_matri->setDbValue($rs->fields('doc4_matri'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_matricula->DbValue = $row['id_matricula'];
		$this->id_afiliado->DbValue = $row['id_afiliado'];
		$this->tipo_matri->DbValue = $row['tipo_matri'];
		$this->id_plan->DbValue = $row['id_plan'];
		$this->valor_matri->DbValue = $row['valor_matri'];
		$this->valor_men_matri->DbValue = $row['valor_men_matri'];
		$this->conv_matri->DbValue = $row['conv_matri'];
		$this->id_empleado->DbValue = $row['id_empleado'];
		$this->bol_matri->DbValue = $row['bol_matri'];
		$this->cuenta_matri->DbValue = $row['cuenta_matri'];
		$this->termino1_matri->DbValue = $row['termino1_matri'];
		$this->termino2_matri->DbValue = $row['termino2_matri'];
		$this->termino3_matri->DbValue = $row['termino3_matri'];
		$this->pag_card_matri->DbValue = $row['pag_card_matri'];
		$this->tipo_card_matri->DbValue = $row['tipo_card_matri'];
		$this->num_card_matri->DbValue = $row['num_card_matri'];
		$this->venc_card_matri->DbValue = $row['venc_card_matri'];
		$this->doc1_matri->DbValue = $row['doc1_matri'];
		$this->doc2_matri->DbValue = $row['doc2_matri'];
		$this->doc3_matri->DbValue = $row['doc3_matri'];
		$this->doc4_matri->DbValue = $row['doc4_matri'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_matricula")) <> "")
			$this->id_matricula->CurrentValue = $this->getKey("id_matricula"); // id_matricula
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

		// Convert decimal values if posted back
		if ($this->valor_matri->FormValue == $this->valor_matri->CurrentValue && is_numeric(ew_StrToFloat($this->valor_matri->CurrentValue)))
			$this->valor_matri->CurrentValue = ew_StrToFloat($this->valor_matri->CurrentValue);

		// Convert decimal values if posted back
		if ($this->valor_men_matri->FormValue == $this->valor_men_matri->CurrentValue && is_numeric(ew_StrToFloat($this->valor_men_matri->CurrentValue)))
			$this->valor_men_matri->CurrentValue = ew_StrToFloat($this->valor_men_matri->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_matricula
		// id_afiliado
		// tipo_matri
		// id_plan
		// valor_matri
		// valor_men_matri
		// conv_matri
		// id_empleado
		// bol_matri
		// cuenta_matri
		// termino1_matri
		// termino2_matri
		// termino3_matri
		// pag_card_matri
		// tipo_card_matri
		// num_card_matri
		// venc_card_matri
		// doc1_matri
		// doc2_matri
		// doc3_matri
		// doc4_matri

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_matricula
			$this->id_matricula->ViewValue = $this->id_matricula->CurrentValue;
			$this->id_matricula->ViewCustomAttributes = "";

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

			// tipo_matri
			if (strval($this->tipo_matri->CurrentValue) <> "") {
				switch ($this->tipo_matri->CurrentValue) {
					case $this->tipo_matri->FldTagValue(1):
						$this->tipo_matri->ViewValue = $this->tipo_matri->FldTagCaption(1) <> "" ? $this->tipo_matri->FldTagCaption(1) : $this->tipo_matri->CurrentValue;
						break;
					case $this->tipo_matri->FldTagValue(2):
						$this->tipo_matri->ViewValue = $this->tipo_matri->FldTagCaption(2) <> "" ? $this->tipo_matri->FldTagCaption(2) : $this->tipo_matri->CurrentValue;
						break;
					case $this->tipo_matri->FldTagValue(3):
						$this->tipo_matri->ViewValue = $this->tipo_matri->FldTagCaption(3) <> "" ? $this->tipo_matri->FldTagCaption(3) : $this->tipo_matri->CurrentValue;
						break;
					default:
						$this->tipo_matri->ViewValue = $this->tipo_matri->CurrentValue;
				}
			} else {
				$this->tipo_matri->ViewValue = NULL;
			}
			$this->tipo_matri->ViewCustomAttributes = "";

			// id_plan
			if (strval($this->id_plan->CurrentValue) <> "") {
				$sFilterWrk = "`id_plan`" . ew_SearchString("=", $this->id_plan->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_plan`, `tipo_plan` AS `DispFld`, `time_plan` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `plan`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_plan, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_plan->ViewValue = $rswrk->fields('DispFld');
					$this->id_plan->ViewValue .= ew_ValueSeparator(1,$this->id_plan) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_plan->ViewValue = $this->id_plan->CurrentValue;
				}
			} else {
				$this->id_plan->ViewValue = NULL;
			}
			$this->id_plan->ViewCustomAttributes = "";

			// valor_matri
			$this->valor_matri->ViewValue = $this->valor_matri->CurrentValue;
			$this->valor_matri->ViewValue = ew_FormatCurrency($this->valor_matri->ViewValue, 2, 0, 0, -2);
			$this->valor_matri->ViewCustomAttributes = "";

			// valor_men_matri
			$this->valor_men_matri->ViewValue = $this->valor_men_matri->CurrentValue;
			$this->valor_men_matri->ViewValue = ew_FormatCurrency($this->valor_men_matri->ViewValue, 2, -2, -2, -2);
			$this->valor_men_matri->ViewCustomAttributes = "";

			// conv_matri
			$this->conv_matri->ViewValue = $this->conv_matri->CurrentValue;
			$this->conv_matri->ViewCustomAttributes = "";

			// id_empleado
			$this->id_empleado->ViewValue = $this->id_empleado->CurrentValue;
			$this->id_empleado->ViewCustomAttributes = "";

			// bol_matri
			$this->bol_matri->ViewValue = $this->bol_matri->CurrentValue;
			$this->bol_matri->ViewCustomAttributes = "";

			// cuenta_matri
			$this->cuenta_matri->ViewValue = $this->cuenta_matri->CurrentValue;
			$this->cuenta_matri->ViewCustomAttributes = "";

			// termino1_matri
			if (strval($this->termino1_matri->CurrentValue) <> "") {
				$this->termino1_matri->ViewValue = "";
				$arwrk = explode(",", strval($this->termino1_matri->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->termino1_matri->FldTagValue(1):
							$this->termino1_matri->ViewValue .= $this->termino1_matri->FldTagCaption(1) <> "" ? $this->termino1_matri->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						default:
							$this->termino1_matri->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->termino1_matri->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->termino1_matri->ViewValue = NULL;
			}
			$this->termino1_matri->ViewCustomAttributes = "";

			// termino2_matri
			if (strval($this->termino2_matri->CurrentValue) <> "") {
				$this->termino2_matri->ViewValue = "";
				$arwrk = explode(",", strval($this->termino2_matri->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->termino2_matri->FldTagValue(1):
							$this->termino2_matri->ViewValue .= $this->termino2_matri->FldTagCaption(1) <> "" ? $this->termino2_matri->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						default:
							$this->termino2_matri->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->termino2_matri->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->termino2_matri->ViewValue = NULL;
			}
			$this->termino2_matri->ViewCustomAttributes = "";

			// termino3_matri
			if (strval($this->termino3_matri->CurrentValue) <> "") {
				$this->termino3_matri->ViewValue = "";
				$arwrk = explode(",", strval($this->termino3_matri->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->termino3_matri->FldTagValue(1):
							$this->termino3_matri->ViewValue .= $this->termino3_matri->FldTagCaption(1) <> "" ? $this->termino3_matri->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						default:
							$this->termino3_matri->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->termino3_matri->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->termino3_matri->ViewValue = NULL;
			}
			$this->termino3_matri->ViewCustomAttributes = "";

			// pag_card_matri
			if (strval($this->pag_card_matri->CurrentValue) <> "") {
				switch ($this->pag_card_matri->CurrentValue) {
					case $this->pag_card_matri->FldTagValue(1):
						$this->pag_card_matri->ViewValue = $this->pag_card_matri->FldTagCaption(1) <> "" ? $this->pag_card_matri->FldTagCaption(1) : $this->pag_card_matri->CurrentValue;
						break;
					case $this->pag_card_matri->FldTagValue(2):
						$this->pag_card_matri->ViewValue = $this->pag_card_matri->FldTagCaption(2) <> "" ? $this->pag_card_matri->FldTagCaption(2) : $this->pag_card_matri->CurrentValue;
						break;
					default:
						$this->pag_card_matri->ViewValue = $this->pag_card_matri->CurrentValue;
				}
			} else {
				$this->pag_card_matri->ViewValue = NULL;
			}
			$this->pag_card_matri->ViewCustomAttributes = "";

			// tipo_card_matri
			if (strval($this->tipo_card_matri->CurrentValue) <> "") {
				switch ($this->tipo_card_matri->CurrentValue) {
					case $this->tipo_card_matri->FldTagValue(1):
						$this->tipo_card_matri->ViewValue = $this->tipo_card_matri->FldTagCaption(1) <> "" ? $this->tipo_card_matri->FldTagCaption(1) : $this->tipo_card_matri->CurrentValue;
						break;
					case $this->tipo_card_matri->FldTagValue(2):
						$this->tipo_card_matri->ViewValue = $this->tipo_card_matri->FldTagCaption(2) <> "" ? $this->tipo_card_matri->FldTagCaption(2) : $this->tipo_card_matri->CurrentValue;
						break;
					case $this->tipo_card_matri->FldTagValue(3):
						$this->tipo_card_matri->ViewValue = $this->tipo_card_matri->FldTagCaption(3) <> "" ? $this->tipo_card_matri->FldTagCaption(3) : $this->tipo_card_matri->CurrentValue;
						break;
					case $this->tipo_card_matri->FldTagValue(4):
						$this->tipo_card_matri->ViewValue = $this->tipo_card_matri->FldTagCaption(4) <> "" ? $this->tipo_card_matri->FldTagCaption(4) : $this->tipo_card_matri->CurrentValue;
						break;
					default:
						$this->tipo_card_matri->ViewValue = $this->tipo_card_matri->CurrentValue;
				}
			} else {
				$this->tipo_card_matri->ViewValue = NULL;
			}
			$this->tipo_card_matri->ViewCustomAttributes = "";

			// num_card_matri
			$this->num_card_matri->ViewValue = $this->num_card_matri->CurrentValue;
			$this->num_card_matri->ViewCustomAttributes = "";

			// venc_card_matri
			$this->venc_card_matri->ViewValue = $this->venc_card_matri->CurrentValue;
			$this->venc_card_matri->ViewValue = ew_FormatDateTime($this->venc_card_matri->ViewValue, 2);
			$this->venc_card_matri->ViewCustomAttributes = "";

			// doc1_matri
			if (strval($this->doc1_matri->CurrentValue) <> "") {
				$this->doc1_matri->ViewValue = "";
				$arwrk = explode(",", strval($this->doc1_matri->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->doc1_matri->FldTagValue(1):
							$this->doc1_matri->ViewValue .= $this->doc1_matri->FldTagCaption(1) <> "" ? $this->doc1_matri->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						default:
							$this->doc1_matri->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->doc1_matri->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->doc1_matri->ViewValue = NULL;
			}
			$this->doc1_matri->ViewCustomAttributes = "";

			// doc2_matri
			if (strval($this->doc2_matri->CurrentValue) <> "") {
				$this->doc2_matri->ViewValue = "";
				$arwrk = explode(",", strval($this->doc2_matri->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->doc2_matri->FldTagValue(1):
							$this->doc2_matri->ViewValue .= $this->doc2_matri->FldTagCaption(1) <> "" ? $this->doc2_matri->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						default:
							$this->doc2_matri->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->doc2_matri->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->doc2_matri->ViewValue = NULL;
			}
			$this->doc2_matri->ViewCustomAttributes = "";

			// doc3_matri
			if (strval($this->doc3_matri->CurrentValue) <> "") {
				$this->doc3_matri->ViewValue = "";
				$arwrk = explode(",", strval($this->doc3_matri->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->doc3_matri->FldTagValue(1):
							$this->doc3_matri->ViewValue .= $this->doc3_matri->FldTagCaption(1) <> "" ? $this->doc3_matri->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						default:
							$this->doc3_matri->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->doc3_matri->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->doc3_matri->ViewValue = NULL;
			}
			$this->doc3_matri->ViewCustomAttributes = "";

			// doc4_matri
			if (strval($this->doc4_matri->CurrentValue) <> "") {
				$this->doc4_matri->ViewValue = "";
				$arwrk = explode(",", strval($this->doc4_matri->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->doc4_matri->FldTagValue(1):
							$this->doc4_matri->ViewValue .= $this->doc4_matri->FldTagCaption(1) <> "" ? $this->doc4_matri->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						default:
							$this->doc4_matri->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->doc4_matri->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->doc4_matri->ViewValue = NULL;
			}
			$this->doc4_matri->ViewCustomAttributes = "";

			// id_matricula
			$this->id_matricula->LinkCustomAttributes = "";
			$this->id_matricula->HrefValue = "";
			$this->id_matricula->TooltipValue = "";

			// id_afiliado
			$this->id_afiliado->LinkCustomAttributes = "";
			$this->id_afiliado->HrefValue = "";
			$this->id_afiliado->TooltipValue = "";

			// tipo_matri
			$this->tipo_matri->LinkCustomAttributes = "";
			$this->tipo_matri->HrefValue = "";
			$this->tipo_matri->TooltipValue = "";

			// id_plan
			$this->id_plan->LinkCustomAttributes = "";
			$this->id_plan->HrefValue = "";
			$this->id_plan->TooltipValue = "";

			// valor_matri
			$this->valor_matri->LinkCustomAttributes = "";
			$this->valor_matri->HrefValue = "";
			$this->valor_matri->TooltipValue = "";

			// valor_men_matri
			$this->valor_men_matri->LinkCustomAttributes = "";
			$this->valor_men_matri->HrefValue = "";
			$this->valor_men_matri->TooltipValue = "";

			// conv_matri
			$this->conv_matri->LinkCustomAttributes = "";
			$this->conv_matri->HrefValue = "";
			$this->conv_matri->TooltipValue = "";

			// id_empleado
			$this->id_empleado->LinkCustomAttributes = "";
			$this->id_empleado->HrefValue = "";
			$this->id_empleado->TooltipValue = "";

			// doc4_matri
			$this->doc4_matri->LinkCustomAttributes = "";
			$this->doc4_matri->HrefValue = "";
			$this->doc4_matri->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id_matricula
			$this->id_matricula->EditCustomAttributes = "";
			$this->id_matricula->EditValue = ew_HtmlEncode($this->id_matricula->AdvancedSearch->SearchValue);
			$this->id_matricula->PlaceHolder = ew_RemoveHtml($this->id_matricula->FldCaption());

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

			// tipo_matri
			$this->tipo_matri->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->tipo_matri->FldTagValue(1), $this->tipo_matri->FldTagCaption(1) <> "" ? $this->tipo_matri->FldTagCaption(1) : $this->tipo_matri->FldTagValue(1));
			$arwrk[] = array($this->tipo_matri->FldTagValue(2), $this->tipo_matri->FldTagCaption(2) <> "" ? $this->tipo_matri->FldTagCaption(2) : $this->tipo_matri->FldTagValue(2));
			$arwrk[] = array($this->tipo_matri->FldTagValue(3), $this->tipo_matri->FldTagCaption(3) <> "" ? $this->tipo_matri->FldTagCaption(3) : $this->tipo_matri->FldTagValue(3));
			$this->tipo_matri->EditValue = $arwrk;

			// id_plan
			$this->id_plan->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id_plan`, `tipo_plan` AS `DispFld`, `time_plan` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `plan`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_plan, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_plan->EditValue = $arwrk;

			// valor_matri
			$this->valor_matri->EditCustomAttributes = "onblur='si_sirve();'";
			$this->valor_matri->EditValue = ew_HtmlEncode($this->valor_matri->AdvancedSearch->SearchValue);
			$this->valor_matri->PlaceHolder = ew_RemoveHtml($this->valor_matri->FldCaption());

			// valor_men_matri
			$this->valor_men_matri->EditCustomAttributes = "";
			$this->valor_men_matri->EditValue = ew_HtmlEncode($this->valor_men_matri->AdvancedSearch->SearchValue);
			$this->valor_men_matri->PlaceHolder = ew_RemoveHtml($this->valor_men_matri->FldCaption());

			// conv_matri
			$this->conv_matri->EditCustomAttributes = "";
			$this->conv_matri->EditValue = ew_HtmlEncode($this->conv_matri->AdvancedSearch->SearchValue);
			$this->conv_matri->PlaceHolder = ew_RemoveHtml($this->conv_matri->FldCaption());

			// id_empleado
			$this->id_empleado->EditCustomAttributes = "";
			$this->id_empleado->EditValue = ew_HtmlEncode($this->id_empleado->AdvancedSearch->SearchValue);
			$this->id_empleado->PlaceHolder = ew_RemoveHtml($this->id_empleado->FldCaption());

			// doc4_matri
			$this->doc4_matri->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->doc4_matri->FldTagValue(1), $this->doc4_matri->FldTagCaption(1) <> "" ? $this->doc4_matri->FldTagCaption(1) : $this->doc4_matri->FldTagValue(1));
			$this->doc4_matri->EditValue = $arwrk;
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
		if (!ew_CheckInteger($this->id_matricula->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id_matricula->FldErrMsg());
		}
		if (!ew_CheckInteger($this->id_afiliado->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id_afiliado->FldErrMsg());
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
		$this->id_matricula->AdvancedSearch->Load();
		$this->id_afiliado->AdvancedSearch->Load();
		$this->tipo_matri->AdvancedSearch->Load();
		$this->id_plan->AdvancedSearch->Load();
		$this->termino3_matri->AdvancedSearch->Load();
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
if (!isset($matricula_list)) $matricula_list = new cmatricula_list();

// Page init
$matricula_list->Page_Init();

// Page main
$matricula_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$matricula_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var matricula_list = new ew_Page("matricula_list");
matricula_list.PageID = "list"; // Page ID
var EW_PAGE_ID = matricula_list.PageID; // For backward compatibility

// Form object
var fmatriculalist = new ew_Form("fmatriculalist");
fmatriculalist.FormKeyCountName = '<?php echo $matricula_list->FormKeyCountName ?>';

// Form_CustomValidate event
fmatriculalist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmatriculalist.ValidateRequired = true;
<?php } else { ?>
fmatriculalist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmatriculalist.Lists["x_id_afiliado"] = {"LinkField":"x_id_afiliado","Ajax":true,"AutoFill":false,"DisplayFields":["x_dociden_afiliado","x_apell_afiliado","x_nomb_afiliado",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmatriculalist.Lists["x_id_plan"] = {"LinkField":"x_id_plan","Ajax":null,"AutoFill":false,"DisplayFields":["x_tipo_plan","x_time_plan","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fmatriculalistsrch = new ew_Form("fmatriculalistsrch");

// Validate function for search
fmatriculalistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_id_matricula");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($matricula->id_matricula->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_id_afiliado");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($matricula->id_afiliado->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fmatriculalistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmatriculalistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fmatriculalistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fmatriculalistsrch.Lists["x_id_afiliado"] = {"LinkField":"x_id_afiliado","Ajax":true,"AutoFill":false,"DisplayFields":["x_dociden_afiliado","x_apell_afiliado","x_nomb_afiliado",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmatriculalistsrch.Lists["x_id_plan"] = {"LinkField":"x_id_plan","Ajax":null,"AutoFill":false,"DisplayFields":["x_tipo_plan","x_time_plan","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php if ($matricula->getCurrentMasterTable() == "" && $matricula_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $matricula_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php if (($matricula->Export == "") || (EW_EXPORT_MASTER_RECORD && $matricula->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "afiliadolist.php";
if ($matricula_list->DbMasterFilter <> "" && $matricula->getCurrentMasterTable() == "afiliado") {
	if ($matricula_list->MasterRecordExists) {
		if ($matricula->getCurrentMasterTable() == $matricula->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($matricula_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $matricula_list->ExportOptions->Render("body") ?></div>
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
		$matricula_list->TotalRecs = $matricula->SelectRecordCount();
	} else {
		if ($matricula_list->Recordset = $matricula_list->LoadRecordset())
			$matricula_list->TotalRecs = $matricula_list->Recordset->RecordCount();
	}
	$matricula_list->StartRec = 1;
	if ($matricula_list->DisplayRecs <= 0 || ($matricula->Export <> "" && $matricula->ExportAll)) // Display all records
		$matricula_list->DisplayRecs = $matricula_list->TotalRecs;
	if (!($matricula->Export <> "" && $matricula->ExportAll))
		$matricula_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$matricula_list->Recordset = $matricula_list->LoadRecordset($matricula_list->StartRec-1, $matricula_list->DisplayRecs);
$matricula_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($matricula->Export == "" && $matricula->CurrentAction == "") { ?>
<form name="fmatriculalistsrch" id="fmatriculalistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<div class="accordion ewDisplayTable ewSearchTable" id="fmatriculalistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fmatriculalistsrch_SearchGroup" href="#fmatriculalistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fmatriculalistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fmatriculalistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="matricula">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$matricula_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$matricula->RowType = EW_ROWTYPE_SEARCH;

// Render row
$matricula->ResetAttrs();
$matricula_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($matricula->id_matricula->Visible) { // id_matricula ?>
	<span id="xsc_id_matricula" class="ewCell">
		<span class="ewSearchCaption"><?php echo $matricula->id_matricula->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_matricula" id="z_id_matricula" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_id_matricula" name="x_id_matricula" id="x_id_matricula" placeholder="<?php echo ew_HtmlEncode($matricula->id_matricula->PlaceHolder) ?>" value="<?php echo $matricula->id_matricula->EditValue ?>"<?php echo $matricula->id_matricula->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($matricula->id_afiliado->Visible) { // id_afiliado ?>
	<span id="xsc_id_afiliado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $matricula->id_afiliado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_afiliado" id="z_id_afiliado" value="="></span>
		<span class="control-group ewSearchField">
<?php if ($matricula->id_afiliado->getSessionValue() <> "") { ?>
<span<?php echo $matricula->id_afiliado->ViewAttributes() ?>>
<?php echo $matricula->id_afiliado->ListViewValue() ?></span>
<input type="hidden" id="x_id_afiliado" name="x_id_afiliado" value="<?php echo ew_HtmlEncode($matricula->id_afiliado->AdvancedSearch->SearchValue) ?>">
<?php } else { ?>
<?php
	$wrkonchange = trim(" " . @$matricula->id_afiliado->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$matricula->id_afiliado->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_afiliado" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_id_afiliado" id="sv_x_id_afiliado" value="<?php echo $matricula->id_afiliado->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($matricula->id_afiliado->PlaceHolder) ?>"<?php echo $matricula->id_afiliado->EditAttributes() ?>>&nbsp;<span id="em_x_id_afiliado" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_id_afiliado" style="display: inline; z-index: 8980"></div>
</span>
<input type="hidden" data-field="x_id_afiliado" name="x_id_afiliado" id="x_id_afiliado" value="<?php echo ew_HtmlEncode($matricula->id_afiliado->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id_afiliado`, `dociden_afiliado` AS `DispFld`, `apell_afiliado` AS `Disp2Fld`, `nomb_afiliado` AS `Disp3Fld` FROM `afiliado`";
$sWhereWrk = "`dociden_afiliado` LIKE '{query_value}%' OR CONCAT(`dociden_afiliado`,'" . ew_ValueSeparator(1, $Page->id_afiliado) . "',`apell_afiliado`,'" . ew_ValueSeparator(2, $Page->id_afiliado) . "',`nomb_afiliado`) LIKE '{query_value}%'";

// Call Lookup selecting
$matricula->Lookup_Selecting($matricula->id_afiliado, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_id_afiliado" id="q_x_id_afiliado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_id_afiliado", fmatriculalistsrch, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_id_afiliado") + ar[i] : "";
	return dv;
}
fmatriculalistsrch.AutoSuggests["x_id_afiliado"] = oas;
</script>
<?php } ?>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($matricula->tipo_matri->Visible) { // tipo_matri ?>
	<span id="xsc_tipo_matri" class="ewCell">
		<span class="ewSearchCaption"><?php echo $matricula->tipo_matri->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_tipo_matri" id="z_tipo_matri" value="="></span>
		<span class="control-group ewSearchField">
<div id="tp_x_tipo_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_tipo_matri" id="x_tipo_matri" value="{value}"<?php echo $matricula->tipo_matri->EditAttributes() ?>></div>
<div id="dsl_x_tipo_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->tipo_matri->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($matricula->tipo_matri->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_tipo_matri" name="x_tipo_matri" id="x_tipo_matri_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $matricula->tipo_matri->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
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
<div id="xsr_4" class="ewRow">
<?php if ($matricula->id_plan->Visible) { // id_plan ?>
	<span id="xsc_id_plan" class="ewCell">
		<span class="ewSearchCaption"><?php echo $matricula->id_plan->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_plan" id="z_id_plan" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_id_plan" id="x_id_plan" name="x_id_plan"<?php echo $matricula->id_plan->EditAttributes() ?>>
<?php
if (is_array($matricula->id_plan->EditValue)) {
	$arwrk = $matricula->id_plan->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($matricula->id_plan->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$matricula->id_plan) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fmatriculalistsrch.Lists["x_id_plan"].Options = <?php echo (is_array($matricula->id_plan->EditValue)) ? ew_ArrayToJson($matricula->id_plan->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $matricula_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $matricula_list->ShowPageHeader(); ?>
<?php
$matricula_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<form name="fmatriculalist" id="fmatriculalist" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="matricula">
<div id="gmp_matricula" class="ewGridMiddlePanel">
<?php if ($matricula_list->TotalRecs > 0) { ?>
<table id="tbl_matriculalist" class="ewTable ewTableSeparate">
<?php echo $matricula->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$matricula_list->RenderListOptions();

// Render list options (header, left)
$matricula_list->ListOptions->Render("header", "left");
?>
<?php if ($matricula->id_matricula->Visible) { // id_matricula ?>
	<?php if ($matricula->SortUrl($matricula->id_matricula) == "") { ?>
		<td><div id="elh_matricula_id_matricula" class="matricula_id_matricula"><div class="ewTableHeaderCaption"><?php echo $matricula->id_matricula->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $matricula->SortUrl($matricula->id_matricula) ?>',1);"><div id="elh_matricula_id_matricula" class="matricula_id_matricula">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->id_matricula->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->id_matricula->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->id_matricula->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($matricula->id_afiliado->Visible) { // id_afiliado ?>
	<?php if ($matricula->SortUrl($matricula->id_afiliado) == "") { ?>
		<td><div id="elh_matricula_id_afiliado" class="matricula_id_afiliado"><div class="ewTableHeaderCaption"><?php echo $matricula->id_afiliado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $matricula->SortUrl($matricula->id_afiliado) ?>',1);"><div id="elh_matricula_id_afiliado" class="matricula_id_afiliado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->id_afiliado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->id_afiliado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->id_afiliado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($matricula->tipo_matri->Visible) { // tipo_matri ?>
	<?php if ($matricula->SortUrl($matricula->tipo_matri) == "") { ?>
		<td><div id="elh_matricula_tipo_matri" class="matricula_tipo_matri"><div class="ewTableHeaderCaption"><?php echo $matricula->tipo_matri->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $matricula->SortUrl($matricula->tipo_matri) ?>',1);"><div id="elh_matricula_tipo_matri" class="matricula_tipo_matri">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->tipo_matri->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->tipo_matri->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->tipo_matri->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($matricula->id_plan->Visible) { // id_plan ?>
	<?php if ($matricula->SortUrl($matricula->id_plan) == "") { ?>
		<td><div id="elh_matricula_id_plan" class="matricula_id_plan"><div class="ewTableHeaderCaption"><?php echo $matricula->id_plan->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $matricula->SortUrl($matricula->id_plan) ?>',1);"><div id="elh_matricula_id_plan" class="matricula_id_plan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->id_plan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->id_plan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->id_plan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($matricula->valor_matri->Visible) { // valor_matri ?>
	<?php if ($matricula->SortUrl($matricula->valor_matri) == "") { ?>
		<td><div id="elh_matricula_valor_matri" class="matricula_valor_matri"><div class="ewTableHeaderCaption"><?php echo $matricula->valor_matri->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $matricula->SortUrl($matricula->valor_matri) ?>',1);"><div id="elh_matricula_valor_matri" class="matricula_valor_matri">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->valor_matri->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->valor_matri->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->valor_matri->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($matricula->valor_men_matri->Visible) { // valor_men_matri ?>
	<?php if ($matricula->SortUrl($matricula->valor_men_matri) == "") { ?>
		<td><div id="elh_matricula_valor_men_matri" class="matricula_valor_men_matri"><div class="ewTableHeaderCaption"><?php echo $matricula->valor_men_matri->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $matricula->SortUrl($matricula->valor_men_matri) ?>',1);"><div id="elh_matricula_valor_men_matri" class="matricula_valor_men_matri">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->valor_men_matri->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->valor_men_matri->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->valor_men_matri->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($matricula->conv_matri->Visible) { // conv_matri ?>
	<?php if ($matricula->SortUrl($matricula->conv_matri) == "") { ?>
		<td><div id="elh_matricula_conv_matri" class="matricula_conv_matri"><div class="ewTableHeaderCaption"><?php echo $matricula->conv_matri->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $matricula->SortUrl($matricula->conv_matri) ?>',1);"><div id="elh_matricula_conv_matri" class="matricula_conv_matri">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->conv_matri->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->conv_matri->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->conv_matri->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($matricula->id_empleado->Visible) { // id_empleado ?>
	<?php if ($matricula->SortUrl($matricula->id_empleado) == "") { ?>
		<td><div id="elh_matricula_id_empleado" class="matricula_id_empleado"><div class="ewTableHeaderCaption"><?php echo $matricula->id_empleado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $matricula->SortUrl($matricula->id_empleado) ?>',1);"><div id="elh_matricula_id_empleado" class="matricula_id_empleado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->id_empleado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->id_empleado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->id_empleado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($matricula->doc4_matri->Visible) { // doc4_matri ?>
	<?php if ($matricula->SortUrl($matricula->doc4_matri) == "") { ?>
		<td><div id="elh_matricula_doc4_matri" class="matricula_doc4_matri"><div class="ewTableHeaderCaption"><?php echo $matricula->doc4_matri->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $matricula->SortUrl($matricula->doc4_matri) ?>',1);"><div id="elh_matricula_doc4_matri" class="matricula_doc4_matri">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $matricula->doc4_matri->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($matricula->doc4_matri->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($matricula->doc4_matri->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$matricula_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($matricula->ExportAll && $matricula->Export <> "") {
	$matricula_list->StopRec = $matricula_list->TotalRecs;
} else {

	// Set the last record to display
	if ($matricula_list->TotalRecs > $matricula_list->StartRec + $matricula_list->DisplayRecs - 1)
		$matricula_list->StopRec = $matricula_list->StartRec + $matricula_list->DisplayRecs - 1;
	else
		$matricula_list->StopRec = $matricula_list->TotalRecs;
}
$matricula_list->RecCnt = $matricula_list->StartRec - 1;
if ($matricula_list->Recordset && !$matricula_list->Recordset->EOF) {
	$matricula_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $matricula_list->StartRec > 1)
		$matricula_list->Recordset->Move($matricula_list->StartRec - 1);
} elseif (!$matricula->AllowAddDeleteRow && $matricula_list->StopRec == 0) {
	$matricula_list->StopRec = $matricula->GridAddRowCount;
}

// Initialize aggregate
$matricula->RowType = EW_ROWTYPE_AGGREGATEINIT;
$matricula->ResetAttrs();
$matricula_list->RenderRow();
while ($matricula_list->RecCnt < $matricula_list->StopRec) {
	$matricula_list->RecCnt++;
	if (intval($matricula_list->RecCnt) >= intval($matricula_list->StartRec)) {
		$matricula_list->RowCnt++;

		// Set up key count
		$matricula_list->KeyCount = $matricula_list->RowIndex;

		// Init row class and style
		$matricula->ResetAttrs();
		$matricula->CssClass = "";
		if ($matricula->CurrentAction == "gridadd") {
		} else {
			$matricula_list->LoadRowValues($matricula_list->Recordset); // Load row values
		}
		$matricula->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$matricula->RowAttrs = array_merge($matricula->RowAttrs, array('data-rowindex'=>$matricula_list->RowCnt, 'id'=>'r' . $matricula_list->RowCnt . '_matricula', 'data-rowtype'=>$matricula->RowType));

		// Render row
		$matricula_list->RenderRow();

		// Render list options
		$matricula_list->RenderListOptions();
?>
	<tr<?php echo $matricula->RowAttributes() ?>>
<?php

// Render list options (body, left)
$matricula_list->ListOptions->Render("body", "left", $matricula_list->RowCnt);
?>
	<?php if ($matricula->id_matricula->Visible) { // id_matricula ?>
		<td<?php echo $matricula->id_matricula->CellAttributes() ?>>
<span<?php echo $matricula->id_matricula->ViewAttributes() ?>>
<?php echo $matricula->id_matricula->ListViewValue() ?></span>
<a id="<?php echo $matricula_list->PageObjName . "_row_" . $matricula_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($matricula->id_afiliado->Visible) { // id_afiliado ?>
		<td<?php echo $matricula->id_afiliado->CellAttributes() ?>>
<span<?php echo $matricula->id_afiliado->ViewAttributes() ?>>
<?php echo $matricula->id_afiliado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($matricula->tipo_matri->Visible) { // tipo_matri ?>
		<td<?php echo $matricula->tipo_matri->CellAttributes() ?>>
<span<?php echo $matricula->tipo_matri->ViewAttributes() ?>>
<?php echo $matricula->tipo_matri->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($matricula->id_plan->Visible) { // id_plan ?>
		<td<?php echo $matricula->id_plan->CellAttributes() ?>>
<span<?php echo $matricula->id_plan->ViewAttributes() ?>>
<?php echo $matricula->id_plan->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($matricula->valor_matri->Visible) { // valor_matri ?>
		<td<?php echo $matricula->valor_matri->CellAttributes() ?>>
<span<?php echo $matricula->valor_matri->ViewAttributes() ?>>
<?php echo $matricula->valor_matri->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($matricula->valor_men_matri->Visible) { // valor_men_matri ?>
		<td<?php echo $matricula->valor_men_matri->CellAttributes() ?>>
<span<?php echo $matricula->valor_men_matri->ViewAttributes() ?>>
<?php echo $matricula->valor_men_matri->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($matricula->conv_matri->Visible) { // conv_matri ?>
		<td<?php echo $matricula->conv_matri->CellAttributes() ?>>
<span<?php echo $matricula->conv_matri->ViewAttributes() ?>>
<?php echo $matricula->conv_matri->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($matricula->id_empleado->Visible) { // id_empleado ?>
		<td<?php echo $matricula->id_empleado->CellAttributes() ?>>
<span<?php echo $matricula->id_empleado->ViewAttributes() ?>>
<?php echo $matricula->id_empleado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($matricula->doc4_matri->Visible) { // doc4_matri ?>
		<td<?php echo $matricula->doc4_matri->CellAttributes() ?>>
<span<?php echo $matricula->doc4_matri->ViewAttributes() ?>>
<?php echo $matricula->doc4_matri->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$matricula_list->ListOptions->Render("body", "right", $matricula_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($matricula->CurrentAction <> "gridadd")
		$matricula_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($matricula->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($matricula_list->Recordset)
	$matricula_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($matricula->CurrentAction <> "gridadd" && $matricula->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($matricula_list->Pager)) $matricula_list->Pager = new cPrevNextPager($matricula_list->StartRec, $matricula_list->DisplayRecs, $matricula_list->TotalRecs) ?>
<?php if ($matricula_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($matricula_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $matricula_list->PageUrl() ?>start=<?php echo $matricula_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($matricula_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $matricula_list->PageUrl() ?>start=<?php echo $matricula_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $matricula_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($matricula_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $matricula_list->PageUrl() ?>start=<?php echo $matricula_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($matricula_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $matricula_list->PageUrl() ?>start=<?php echo $matricula_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $matricula_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $matricula_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $matricula_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $matricula_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($matricula_list->SearchWhere == "0=101") { ?>
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
	foreach ($matricula_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
</td></tr></table>
<script type="text/javascript">
fmatriculalistsrch.Init();
fmatriculalist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$matricula_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$matricula_list->Page_Terminate();
?>
