<?php include_once "representantesinfo.php" ?>
<?php include_once "v_usuariosinfo.php" ?>
<?php

//
// Page class
//

$representantes_grid = NULL; // Initialize page object first

class crepresentantes_grid extends crepresentantes {

	// Page ID
	var $PageID = 'grid';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'representantes';

	// Page object name
	var $PageObjName = 'representantes_grid';

	// Grid form hidden field names
	var $FormName = 'frepresentantesgrid';
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
		$this->FormActionName .= '_' . $this->FormName;
		$this->FormKeyName .= '_' . $this->FormName;
		$this->FormOldKeyName .= '_' . $this->FormName;
		$this->FormBlankRowName .= '_' . $this->FormName;
		$this->FormKeyCountName .= '_' . $this->FormName;
		$GLOBALS["Grid"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (representantes)
		if (!isset($GLOBALS["representantes"]) || get_class($GLOBALS["representantes"]) == "crepresentantes") {
			$GLOBALS["representantes"] = &$this;

//			$GLOBALS["MasterTable"] = &$GLOBALS["Table"];
//			if (!isset($GLOBALS["Table"])) $GLOBALS["Table"] = &$GLOBALS["representantes"];

		}

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'grid', TRUE);

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

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
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
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

//		$GLOBALS["Table"] = &$GLOBALS["MasterTable"];
		unset($GLOBALS["Grid"]);
		if ($url == "")
			return;

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

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
	var $ShowOtherOptions = FALSE;
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

			// Handle reset command
			$this->ResetCmd();

			// Set up master detail parameters
			$this->SetUpMasterParms();

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

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 35; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

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

	//  Exit inline mode
	function ClearInlineMode() {
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Perform update to grid
	function GridUpdate() {
		global $conn, $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		$sSql = $this->SQL();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
		}
		return $bGridUpdate;
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

	// Perform Grid Add
	function GridInsert() {
		global $conn, $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			if ($rowaction == "insert") {
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
				$this->LoadOldRecord(); // Load old recordset
			}
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->id_representante->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->ClearInlineMode(); // Clear grid add mode and return
			return TRUE;
		}
		if ($bGridInsert) {

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "gridadd"; // Stay in gridadd mode
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_id_afiliado") && $objForm->HasValue("o_id_afiliado") && $this->id_afiliado->CurrentValue <> $this->id_afiliado->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_dociden_repres") && $objForm->HasValue("o_dociden_repres") && $this->dociden_repres->CurrentValue <> $this->dociden_repres->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_apell_repres") && $objForm->HasValue("o_apell_repres") && $this->apell_repres->CurrentValue <> $this->apell_repres->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nomb_repres") && $objForm->HasValue("o_nomb_repres") && $this->nomb_repres->CurrentValue <> $this->nomb_repres->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_telf_resi_repres") && $objForm->HasValue("o_telf_resi_repres") && $this->telf_resi_repres->CurrentValue <> $this->telf_resi_repres->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_email_repres") && $objForm->HasValue("o_email_repres") && $this->email_repres->CurrentValue <> $this->email_repres->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_par_repres") && $objForm->HasValue("o_par_repres") && $this->par_repres->CurrentValue <> $this->par_repres->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_cel_repres") && $objForm->HasValue("o_cel_repres") && $this->cel_repres->CurrentValue <> $this->cel_repres->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_contact_e_repres") && $objForm->HasValue("o_contact_e_repres") && $this->contact_e_repres->CurrentValue <> $this->contact_e_repres->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_contact_d_repres") && $objForm->HasValue("o_contact_d_repres") && $this->contact_d_repres->CurrentValue <> $this->contact_d_repres->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_st_repres") && $objForm->HasValue("o_st_repres") && $this->st_repres->CurrentValue <> $this->st_repres->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
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
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = TRUE;
			$item->Visible = FALSE; // Default hidden
		}

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		$this->ListOptions->ButtonClass = "btn-small"; // Class for button group
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($objForm->HasValue($this->FormOldKeyName))
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
			if ($this->RowOldKey <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $OldKeyName . "\" id=\"" . $OldKeyName . "\" value=\"" . ew_HtmlEncode($this->RowOldKey) . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				if (is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"javascript:void(0);\" onclick=\"ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
		}
		if ($this->CurrentMode == "edit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->id_representante->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();
	}

	// Set record key
	function SetRecordKey(&$key, $rs) {
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('id_representante');
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$option = &$this->OtherOptions["addedit"];
		$option->UseDropDownButton = FALSE;
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$option->UseButtonGroup = TRUE;
		$option->ButtonClass = "btn-small"; // Class for button group
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		if (($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") && $this->CurrentAction != "F") { // Check add/copy/edit mode
			if ($this->AllowAddDeleteRow) {
				$option = &$options["addedit"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
				$item = &$option->Add("addblankrow");
				$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
				$item->Visible = $Security->CanAdd();
				$this->ShowOtherOptions = $item->Visible;
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id_representante->CurrentValue = NULL;
		$this->id_representante->OldValue = $this->id_representante->CurrentValue;
		$this->id_afiliado->CurrentValue = NULL;
		$this->id_afiliado->OldValue = $this->id_afiliado->CurrentValue;
		$this->dociden_repres->CurrentValue = NULL;
		$this->dociden_repres->OldValue = $this->dociden_repres->CurrentValue;
		$this->apell_repres->CurrentValue = NULL;
		$this->apell_repres->OldValue = $this->apell_repres->CurrentValue;
		$this->nomb_repres->CurrentValue = NULL;
		$this->nomb_repres->OldValue = $this->nomb_repres->CurrentValue;
		$this->telf_resi_repres->CurrentValue = NULL;
		$this->telf_resi_repres->OldValue = $this->telf_resi_repres->CurrentValue;
		$this->email_repres->CurrentValue = NULL;
		$this->email_repres->OldValue = $this->email_repres->CurrentValue;
		$this->par_repres->CurrentValue = NULL;
		$this->par_repres->OldValue = $this->par_repres->CurrentValue;
		$this->cel_repres->CurrentValue = NULL;
		$this->cel_repres->OldValue = $this->cel_repres->CurrentValue;
		$this->contact_e_repres->CurrentValue = NULL;
		$this->contact_e_repres->OldValue = $this->contact_e_repres->CurrentValue;
		$this->contact_d_repres->CurrentValue = NULL;
		$this->contact_d_repres->OldValue = $this->contact_d_repres->CurrentValue;
		$this->st_repres->CurrentValue = "Activo";
		$this->st_repres->OldValue = $this->st_repres->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$objForm->FormName = $this->FormName;
		if (!$this->id_representante->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id_representante->setFormValue($objForm->GetValue("x_id_representante"));
		if (!$this->id_afiliado->FldIsDetailKey) {
			$this->id_afiliado->setFormValue($objForm->GetValue("x_id_afiliado"));
		}
		$this->id_afiliado->setOldValue($objForm->GetValue("o_id_afiliado"));
		if (!$this->dociden_repres->FldIsDetailKey) {
			$this->dociden_repres->setFormValue($objForm->GetValue("x_dociden_repres"));
		}
		$this->dociden_repres->setOldValue($objForm->GetValue("o_dociden_repres"));
		if (!$this->apell_repres->FldIsDetailKey) {
			$this->apell_repres->setFormValue($objForm->GetValue("x_apell_repres"));
		}
		$this->apell_repres->setOldValue($objForm->GetValue("o_apell_repres"));
		if (!$this->nomb_repres->FldIsDetailKey) {
			$this->nomb_repres->setFormValue($objForm->GetValue("x_nomb_repres"));
		}
		$this->nomb_repres->setOldValue($objForm->GetValue("o_nomb_repres"));
		if (!$this->telf_resi_repres->FldIsDetailKey) {
			$this->telf_resi_repres->setFormValue($objForm->GetValue("x_telf_resi_repres"));
		}
		$this->telf_resi_repres->setOldValue($objForm->GetValue("o_telf_resi_repres"));
		if (!$this->email_repres->FldIsDetailKey) {
			$this->email_repres->setFormValue($objForm->GetValue("x_email_repres"));
		}
		$this->email_repres->setOldValue($objForm->GetValue("o_email_repres"));
		if (!$this->par_repres->FldIsDetailKey) {
			$this->par_repres->setFormValue($objForm->GetValue("x_par_repres"));
		}
		$this->par_repres->setOldValue($objForm->GetValue("o_par_repres"));
		if (!$this->cel_repres->FldIsDetailKey) {
			$this->cel_repres->setFormValue($objForm->GetValue("x_cel_repres"));
		}
		$this->cel_repres->setOldValue($objForm->GetValue("o_cel_repres"));
		if (!$this->contact_e_repres->FldIsDetailKey) {
			$this->contact_e_repres->setFormValue($objForm->GetValue("x_contact_e_repres"));
		}
		$this->contact_e_repres->setOldValue($objForm->GetValue("o_contact_e_repres"));
		if (!$this->contact_d_repres->FldIsDetailKey) {
			$this->contact_d_repres->setFormValue($objForm->GetValue("x_contact_d_repres"));
		}
		$this->contact_d_repres->setOldValue($objForm->GetValue("o_contact_d_repres"));
		if (!$this->st_repres->FldIsDetailKey) {
			$this->st_repres->setFormValue($objForm->GetValue("x_st_repres"));
		}
		$this->st_repres->setOldValue($objForm->GetValue("o_st_repres"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id_representante->CurrentValue = $this->id_representante->FormValue;
		$this->id_afiliado->CurrentValue = $this->id_afiliado->FormValue;
		$this->dociden_repres->CurrentValue = $this->dociden_repres->FormValue;
		$this->apell_repres->CurrentValue = $this->apell_repres->FormValue;
		$this->nomb_repres->CurrentValue = $this->nomb_repres->FormValue;
		$this->telf_resi_repres->CurrentValue = $this->telf_resi_repres->FormValue;
		$this->email_repres->CurrentValue = $this->email_repres->FormValue;
		$this->par_repres->CurrentValue = $this->par_repres->FormValue;
		$this->cel_repres->CurrentValue = $this->cel_repres->FormValue;
		$this->contact_e_repres->CurrentValue = $this->contact_e_repres->FormValue;
		$this->contact_d_repres->CurrentValue = $this->contact_d_repres->FormValue;
		$this->st_repres->CurrentValue = $this->st_repres->FormValue;
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
		$arKeys[] = $this->RowOldKey;
		$cnt = count($arKeys);
		if ($cnt >= 1) {
			if (strval($arKeys[0]) <> "")
				$this->id_representante->CurrentValue = strval($arKeys[0]); // id_representante
			else
				$bValidKey = FALSE;
		} else {
			$bValidKey = FALSE;
		}

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_representante
			// id_afiliado

			$this->id_afiliado->EditCustomAttributes = "";
			if ($this->id_afiliado->getSessionValue() <> "") {
				$this->id_afiliado->CurrentValue = $this->id_afiliado->getSessionValue();
				$this->id_afiliado->OldValue = $this->id_afiliado->CurrentValue;
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
			} else {
			$this->id_afiliado->EditValue = ew_HtmlEncode($this->id_afiliado->CurrentValue);
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
					$this->id_afiliado->EditValue = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->id_afiliado->EditValue .= ew_ValueSeparator(1,$this->id_afiliado) . ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$rswrk->Close();
				} else {
					$this->id_afiliado->EditValue = ew_HtmlEncode($this->id_afiliado->CurrentValue);
				}
			} else {
				$this->id_afiliado->EditValue = NULL;
			}
			$this->id_afiliado->PlaceHolder = ew_RemoveHtml($this->id_afiliado->FldCaption());
			}

			// dociden_repres
			$this->dociden_repres->EditCustomAttributes = "";
			$this->dociden_repres->EditValue = ew_HtmlEncode($this->dociden_repres->CurrentValue);
			$this->dociden_repres->PlaceHolder = ew_RemoveHtml($this->dociden_repres->FldCaption());

			// apell_repres
			$this->apell_repres->EditCustomAttributes = "";
			$this->apell_repres->EditValue = ew_HtmlEncode($this->apell_repres->CurrentValue);
			$this->apell_repres->PlaceHolder = ew_RemoveHtml($this->apell_repres->FldCaption());

			// nomb_repres
			$this->nomb_repres->EditCustomAttributes = "";
			$this->nomb_repres->EditValue = ew_HtmlEncode($this->nomb_repres->CurrentValue);
			$this->nomb_repres->PlaceHolder = ew_RemoveHtml($this->nomb_repres->FldCaption());

			// telf_resi_repres
			$this->telf_resi_repres->EditCustomAttributes = "";
			$this->telf_resi_repres->EditValue = ew_HtmlEncode($this->telf_resi_repres->CurrentValue);
			$this->telf_resi_repres->PlaceHolder = ew_RemoveHtml($this->telf_resi_repres->FldCaption());

			// email_repres
			$this->email_repres->EditCustomAttributes = "";
			$this->email_repres->EditValue = ew_HtmlEncode($this->email_repres->CurrentValue);
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
			$this->cel_repres->EditValue = ew_HtmlEncode($this->cel_repres->CurrentValue);
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

			// Edit refer script
			// id_representante

			$this->id_representante->HrefValue = "";

			// id_afiliado
			$this->id_afiliado->HrefValue = "";

			// dociden_repres
			$this->dociden_repres->HrefValue = "";

			// apell_repres
			$this->apell_repres->HrefValue = "";

			// nomb_repres
			$this->nomb_repres->HrefValue = "";

			// telf_resi_repres
			$this->telf_resi_repres->HrefValue = "";

			// email_repres
			$this->email_repres->HrefValue = "";

			// par_repres
			$this->par_repres->HrefValue = "";

			// cel_repres
			$this->cel_repres->HrefValue = "";

			// contact_e_repres
			$this->contact_e_repres->HrefValue = "";

			// contact_d_repres
			$this->contact_d_repres->HrefValue = "";

			// st_repres
			$this->st_repres->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_representante
			$this->id_representante->EditCustomAttributes = "";
			$this->id_representante->EditValue = $this->id_representante->CurrentValue;
			$this->id_representante->ViewCustomAttributes = "";

			// id_afiliado
			$this->id_afiliado->EditCustomAttributes = "";
			if ($this->id_afiliado->getSessionValue() <> "") {
				$this->id_afiliado->CurrentValue = $this->id_afiliado->getSessionValue();
				$this->id_afiliado->OldValue = $this->id_afiliado->CurrentValue;
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
			} else {
			$this->id_afiliado->EditValue = ew_HtmlEncode($this->id_afiliado->CurrentValue);
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
					$this->id_afiliado->EditValue = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->id_afiliado->EditValue .= ew_ValueSeparator(1,$this->id_afiliado) . ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$rswrk->Close();
				} else {
					$this->id_afiliado->EditValue = ew_HtmlEncode($this->id_afiliado->CurrentValue);
				}
			} else {
				$this->id_afiliado->EditValue = NULL;
			}
			$this->id_afiliado->PlaceHolder = ew_RemoveHtml($this->id_afiliado->FldCaption());
			}

			// dociden_repres
			$this->dociden_repres->EditCustomAttributes = "";
			$this->dociden_repres->EditValue = ew_HtmlEncode($this->dociden_repres->CurrentValue);
			$this->dociden_repres->PlaceHolder = ew_RemoveHtml($this->dociden_repres->FldCaption());

			// apell_repres
			$this->apell_repres->EditCustomAttributes = "";
			$this->apell_repres->EditValue = ew_HtmlEncode($this->apell_repres->CurrentValue);
			$this->apell_repres->PlaceHolder = ew_RemoveHtml($this->apell_repres->FldCaption());

			// nomb_repres
			$this->nomb_repres->EditCustomAttributes = "";
			$this->nomb_repres->EditValue = ew_HtmlEncode($this->nomb_repres->CurrentValue);
			$this->nomb_repres->PlaceHolder = ew_RemoveHtml($this->nomb_repres->FldCaption());

			// telf_resi_repres
			$this->telf_resi_repres->EditCustomAttributes = "";
			$this->telf_resi_repres->EditValue = ew_HtmlEncode($this->telf_resi_repres->CurrentValue);
			$this->telf_resi_repres->PlaceHolder = ew_RemoveHtml($this->telf_resi_repres->FldCaption());

			// email_repres
			$this->email_repres->EditCustomAttributes = "";
			$this->email_repres->EditValue = ew_HtmlEncode($this->email_repres->CurrentValue);
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
			$this->cel_repres->EditValue = ew_HtmlEncode($this->cel_repres->CurrentValue);
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

			// Edit refer script
			// id_representante

			$this->id_representante->HrefValue = "";

			// id_afiliado
			$this->id_afiliado->HrefValue = "";

			// dociden_repres
			$this->dociden_repres->HrefValue = "";

			// apell_repres
			$this->apell_repres->HrefValue = "";

			// nomb_repres
			$this->nomb_repres->HrefValue = "";

			// telf_resi_repres
			$this->telf_resi_repres->HrefValue = "";

			// email_repres
			$this->email_repres->HrefValue = "";

			// par_repres
			$this->par_repres->HrefValue = "";

			// cel_repres
			$this->cel_repres->HrefValue = "";

			// contact_e_repres
			$this->contact_e_repres->HrefValue = "";

			// contact_d_repres
			$this->contact_d_repres->HrefValue = "";

			// st_repres
			$this->st_repres->HrefValue = "";
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

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->id_afiliado->FldIsDetailKey && !is_null($this->id_afiliado->FormValue) && $this->id_afiliado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id_afiliado->FldCaption());
		}
		if (!$this->dociden_repres->FldIsDetailKey && !is_null($this->dociden_repres->FormValue) && $this->dociden_repres->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dociden_repres->FldCaption());
		}
		if (!$this->apell_repres->FldIsDetailKey && !is_null($this->apell_repres->FormValue) && $this->apell_repres->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->apell_repres->FldCaption());
		}
		if (!$this->nomb_repres->FldIsDetailKey && !is_null($this->nomb_repres->FormValue) && $this->nomb_repres->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nomb_repres->FldCaption());
		}
		if (!ew_CheckEmail($this->email_repres->FormValue)) {
			ew_AddMessage($gsFormError, $this->email_repres->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id_representante'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		if ($this->id_afiliado->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`id_afiliado` = " . ew_AdjustSql($this->id_afiliado->CurrentValue) . ")";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->id_afiliado->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->id_afiliado->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
		if ($this->dociden_repres->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`dociden_repres` = '" . ew_AdjustSql($this->dociden_repres->CurrentValue) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->dociden_repres->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->dociden_repres->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// id_afiliado
			$this->id_afiliado->SetDbValueDef($rsnew, $this->id_afiliado->CurrentValue, 0, $this->id_afiliado->ReadOnly);

			// dociden_repres
			$this->dociden_repres->SetDbValueDef($rsnew, $this->dociden_repres->CurrentValue, "", $this->dociden_repres->ReadOnly);

			// apell_repres
			$this->apell_repres->SetDbValueDef($rsnew, $this->apell_repres->CurrentValue, "", $this->apell_repres->ReadOnly);

			// nomb_repres
			$this->nomb_repres->SetDbValueDef($rsnew, $this->nomb_repres->CurrentValue, "", $this->nomb_repres->ReadOnly);

			// telf_resi_repres
			$this->telf_resi_repres->SetDbValueDef($rsnew, $this->telf_resi_repres->CurrentValue, NULL, $this->telf_resi_repres->ReadOnly);

			// email_repres
			$this->email_repres->SetDbValueDef($rsnew, $this->email_repres->CurrentValue, NULL, $this->email_repres->ReadOnly);

			// par_repres
			$this->par_repres->SetDbValueDef($rsnew, $this->par_repres->CurrentValue, NULL, $this->par_repres->ReadOnly);

			// cel_repres
			$this->cel_repres->SetDbValueDef($rsnew, $this->cel_repres->CurrentValue, NULL, $this->cel_repres->ReadOnly);

			// contact_e_repres
			$this->contact_e_repres->SetDbValueDef($rsnew, $this->contact_e_repres->CurrentValue, NULL, $this->contact_e_repres->ReadOnly);

			// contact_d_repres
			$this->contact_d_repres->SetDbValueDef($rsnew, $this->contact_d_repres->CurrentValue, NULL, $this->contact_d_repres->ReadOnly);

			// st_repres
			$this->st_repres->SetDbValueDef($rsnew, $this->st_repres->CurrentValue, NULL, $this->st_repres->ReadOnly);

			// Check referential integrity for master table 'afiliado'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_afiliado();
			$KeyValue = isset($rsnew['id_afiliado']) ? $rsnew['id_afiliado'] : $rsold['id_afiliado'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@id_afiliado@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				$rsmaster = $GLOBALS["afiliado"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "afiliado", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Set up foreign key field value from Session
			if ($this->getCurrentMasterTable() == "afiliado") {
				$this->id_afiliado->CurrentValue = $this->id_afiliado->getSessionValue();
			}
		if ($this->id_afiliado->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(id_afiliado = " . ew_AdjustSql($this->id_afiliado->CurrentValue) . ")";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->id_afiliado->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->id_afiliado->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		if ($this->dociden_repres->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(dociden_repres = '" . ew_AdjustSql($this->dociden_repres->CurrentValue) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->dociden_repres->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->dociden_repres->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}

		// Check referential integrity for master table 'afiliado'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_afiliado();
		if (strval($this->id_afiliado->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@id_afiliado@", ew_AdjustSql($this->id_afiliado->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["afiliado"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "afiliado", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// id_afiliado
		$this->id_afiliado->SetDbValueDef($rsnew, $this->id_afiliado->CurrentValue, 0, FALSE);

		// dociden_repres
		$this->dociden_repres->SetDbValueDef($rsnew, $this->dociden_repres->CurrentValue, "", FALSE);

		// apell_repres
		$this->apell_repres->SetDbValueDef($rsnew, $this->apell_repres->CurrentValue, "", FALSE);

		// nomb_repres
		$this->nomb_repres->SetDbValueDef($rsnew, $this->nomb_repres->CurrentValue, "", FALSE);

		// telf_resi_repres
		$this->telf_resi_repres->SetDbValueDef($rsnew, $this->telf_resi_repres->CurrentValue, NULL, FALSE);

		// email_repres
		$this->email_repres->SetDbValueDef($rsnew, $this->email_repres->CurrentValue, NULL, FALSE);

		// par_repres
		$this->par_repres->SetDbValueDef($rsnew, $this->par_repres->CurrentValue, NULL, FALSE);

		// cel_repres
		$this->cel_repres->SetDbValueDef($rsnew, $this->cel_repres->CurrentValue, NULL, FALSE);

		// contact_e_repres
		$this->contact_e_repres->SetDbValueDef($rsnew, $this->contact_e_repres->CurrentValue, NULL, FALSE);

		// contact_d_repres
		$this->contact_d_repres->SetDbValueDef($rsnew, $this->contact_d_repres->CurrentValue, NULL, FALSE);

		// st_repres
		$this->st_repres->SetDbValueDef($rsnew, $this->st_repres->CurrentValue, NULL, strval($this->st_repres->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->id_representante->setDbValue($conn->Insert_ID());
			$rsnew['id_representante'] = $this->id_representante->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {

		// Hide foreign keys
		$sMasterTblVar = $this->getCurrentMasterTable();
		if ($sMasterTblVar == "afiliado") {
			$this->id_afiliado->Visible = FALSE;
			if ($GLOBALS["afiliado"]->EventCancelled) $this->EventCancelled = TRUE;
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
}
?>
