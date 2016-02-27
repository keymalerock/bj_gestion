<?php include_once "matriculainfo.php" ?>
<?php include_once "v_usuariosinfo.php" ?>
<?php

//
// Page class
//

$matricula_grid = NULL; // Initialize page object first

class cmatricula_grid extends cmatricula {

	// Page ID
	var $PageID = 'grid';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'matricula';

	// Page object name
	var $PageObjName = 'matricula_grid';

	// Grid form hidden field names
	var $FormName = 'fmatriculagrid';
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

		// Table object (matricula)
		if (!isset($GLOBALS["matricula"]) || get_class($GLOBALS["matricula"]) == "cmatricula") {
			$GLOBALS["matricula"] = &$this;

//			$GLOBALS["MasterTable"] = &$GLOBALS["Table"];
//			if (!isset($GLOBALS["Table"])) $GLOBALS["Table"] = &$GLOBALS["matricula"];

		}

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'grid', TRUE);

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
		$this->id_matricula->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		$this->valor_matri->FormValue = ""; // Clear form value
		$this->valor_men_matri->FormValue = ""; // Clear form value
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
			$this->id_matricula->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_matricula->FormValue))
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
					$sKey .= $this->id_matricula->CurrentValue;

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
		if ($objForm->HasValue("x_tipo_matri") && $objForm->HasValue("o_tipo_matri") && $this->tipo_matri->CurrentValue <> $this->tipo_matri->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_id_plan") && $objForm->HasValue("o_id_plan") && $this->id_plan->CurrentValue <> $this->id_plan->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_valor_matri") && $objForm->HasValue("o_valor_matri") && $this->valor_matri->CurrentValue <> $this->valor_matri->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_valor_men_matri") && $objForm->HasValue("o_valor_men_matri") && $this->valor_men_matri->CurrentValue <> $this->valor_men_matri->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_conv_matri") && $objForm->HasValue("o_conv_matri") && $this->conv_matri->CurrentValue <> $this->conv_matri->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_id_empleado") && $objForm->HasValue("o_id_empleado") && $this->id_empleado->CurrentValue <> $this->id_empleado->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_doc4_matri") && $objForm->HasValue("o_doc4_matri") && $this->doc4_matri->CurrentValue <> $this->doc4_matri->OldValue)
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
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->id_matricula->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();
	}

	// Set record key
	function SetRecordKey(&$key, $rs) {
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('id_matricula');
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
		$this->id_matricula->CurrentValue = NULL;
		$this->id_matricula->OldValue = $this->id_matricula->CurrentValue;
		$this->id_afiliado->CurrentValue = NULL;
		$this->id_afiliado->OldValue = $this->id_afiliado->CurrentValue;
		$this->tipo_matri->CurrentValue = NULL;
		$this->tipo_matri->OldValue = $this->tipo_matri->CurrentValue;
		$this->id_plan->CurrentValue = NULL;
		$this->id_plan->OldValue = $this->id_plan->CurrentValue;
		$this->valor_matri->CurrentValue = NULL;
		$this->valor_matri->OldValue = $this->valor_matri->CurrentValue;
		$this->valor_men_matri->CurrentValue = NULL;
		$this->valor_men_matri->OldValue = $this->valor_men_matri->CurrentValue;
		$this->conv_matri->CurrentValue = NULL;
		$this->conv_matri->OldValue = $this->conv_matri->CurrentValue;
		$this->id_empleado->CurrentValue = CurrentUserID();
		$this->id_empleado->OldValue = $this->id_empleado->CurrentValue;
		$this->doc4_matri->CurrentValue = NULL;
		$this->doc4_matri->OldValue = $this->doc4_matri->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$objForm->FormName = $this->FormName;
		if (!$this->id_matricula->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id_matricula->setFormValue($objForm->GetValue("x_id_matricula"));
		if (!$this->id_afiliado->FldIsDetailKey) {
			$this->id_afiliado->setFormValue($objForm->GetValue("x_id_afiliado"));
		}
		$this->id_afiliado->setOldValue($objForm->GetValue("o_id_afiliado"));
		if (!$this->tipo_matri->FldIsDetailKey) {
			$this->tipo_matri->setFormValue($objForm->GetValue("x_tipo_matri"));
		}
		$this->tipo_matri->setOldValue($objForm->GetValue("o_tipo_matri"));
		if (!$this->id_plan->FldIsDetailKey) {
			$this->id_plan->setFormValue($objForm->GetValue("x_id_plan"));
		}
		$this->id_plan->setOldValue($objForm->GetValue("o_id_plan"));
		if (!$this->valor_matri->FldIsDetailKey) {
			$this->valor_matri->setFormValue($objForm->GetValue("x_valor_matri"));
		}
		$this->valor_matri->setOldValue($objForm->GetValue("o_valor_matri"));
		if (!$this->valor_men_matri->FldIsDetailKey) {
			$this->valor_men_matri->setFormValue($objForm->GetValue("x_valor_men_matri"));
		}
		$this->valor_men_matri->setOldValue($objForm->GetValue("o_valor_men_matri"));
		if (!$this->conv_matri->FldIsDetailKey) {
			$this->conv_matri->setFormValue($objForm->GetValue("x_conv_matri"));
		}
		$this->conv_matri->setOldValue($objForm->GetValue("o_conv_matri"));
		if (!$this->id_empleado->FldIsDetailKey) {
			$this->id_empleado->setFormValue($objForm->GetValue("x_id_empleado"));
		}
		$this->id_empleado->setOldValue($objForm->GetValue("o_id_empleado"));
		if (!$this->doc4_matri->FldIsDetailKey) {
			$this->doc4_matri->setFormValue($objForm->GetValue("x_doc4_matri"));
		}
		$this->doc4_matri->setOldValue($objForm->GetValue("o_doc4_matri"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id_matricula->CurrentValue = $this->id_matricula->FormValue;
		$this->id_afiliado->CurrentValue = $this->id_afiliado->FormValue;
		$this->tipo_matri->CurrentValue = $this->tipo_matri->FormValue;
		$this->id_plan->CurrentValue = $this->id_plan->FormValue;
		$this->valor_matri->CurrentValue = $this->valor_matri->FormValue;
		$this->valor_men_matri->CurrentValue = $this->valor_men_matri->FormValue;
		$this->conv_matri->CurrentValue = $this->conv_matri->FormValue;
		$this->id_empleado->CurrentValue = $this->id_empleado->FormValue;
		$this->doc4_matri->CurrentValue = $this->doc4_matri->FormValue;
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
		$arKeys[] = $this->RowOldKey;
		$cnt = count($arKeys);
		if ($cnt >= 1) {
			if (strval($arKeys[0]) <> "")
				$this->id_matricula->CurrentValue = strval($arKeys[0]); // id_matricula
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_matricula
			// id_afiliado

			$this->id_afiliado->EditCustomAttributes = "";
			if ($this->id_afiliado->getSessionValue() <> "") {
				$this->id_afiliado->CurrentValue = $this->id_afiliado->getSessionValue();
				$this->id_afiliado->OldValue = $this->id_afiliado->CurrentValue;
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
			} else {
			$this->id_afiliado->EditValue = ew_HtmlEncode($this->id_afiliado->CurrentValue);
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
					$this->id_afiliado->EditValue = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->id_afiliado->EditValue .= ew_ValueSeparator(1,$this->id_afiliado) . ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->id_afiliado->EditValue .= ew_ValueSeparator(2,$this->id_afiliado) . ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$rswrk->Close();
				} else {
					$this->id_afiliado->EditValue = ew_HtmlEncode($this->id_afiliado->CurrentValue);
				}
			} else {
				$this->id_afiliado->EditValue = NULL;
			}
			$this->id_afiliado->PlaceHolder = ew_RemoveHtml($this->id_afiliado->FldCaption());
			}

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
			$this->valor_matri->EditValue = ew_HtmlEncode($this->valor_matri->CurrentValue);
			$this->valor_matri->PlaceHolder = ew_RemoveHtml($this->valor_matri->FldCaption());
			if (strval($this->valor_matri->EditValue) <> "" && is_numeric($this->valor_matri->EditValue)) {
			$this->valor_matri->EditValue = ew_FormatNumber($this->valor_matri->EditValue, -2, 0, 0, -2);
			$this->valor_matri->OldValue = $this->valor_matri->EditValue;
			}

			// valor_men_matri
			$this->valor_men_matri->EditCustomAttributes = "";
			$this->valor_men_matri->EditValue = ew_HtmlEncode($this->valor_men_matri->CurrentValue);
			$this->valor_men_matri->PlaceHolder = ew_RemoveHtml($this->valor_men_matri->FldCaption());
			if (strval($this->valor_men_matri->EditValue) <> "" && is_numeric($this->valor_men_matri->EditValue)) {
			$this->valor_men_matri->EditValue = ew_FormatNumber($this->valor_men_matri->EditValue, -2, -2, -2, -2);
			$this->valor_men_matri->OldValue = $this->valor_men_matri->EditValue;
			}

			// conv_matri
			$this->conv_matri->EditCustomAttributes = "";
			$this->conv_matri->EditValue = ew_HtmlEncode($this->conv_matri->CurrentValue);
			$this->conv_matri->PlaceHolder = ew_RemoveHtml($this->conv_matri->FldCaption());

			// id_empleado
			$this->id_empleado->EditCustomAttributes = "";
			$this->id_empleado->CurrentValue = CurrentUserID();

			// doc4_matri
			$this->doc4_matri->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->doc4_matri->FldTagValue(1), $this->doc4_matri->FldTagCaption(1) <> "" ? $this->doc4_matri->FldTagCaption(1) : $this->doc4_matri->FldTagValue(1));
			$this->doc4_matri->EditValue = $arwrk;

			// Edit refer script
			// id_matricula

			$this->id_matricula->HrefValue = "";

			// id_afiliado
			$this->id_afiliado->HrefValue = "";

			// tipo_matri
			$this->tipo_matri->HrefValue = "";

			// id_plan
			$this->id_plan->HrefValue = "";

			// valor_matri
			$this->valor_matri->HrefValue = "";

			// valor_men_matri
			$this->valor_men_matri->HrefValue = "";

			// conv_matri
			$this->conv_matri->HrefValue = "";

			// id_empleado
			$this->id_empleado->HrefValue = "";

			// doc4_matri
			$this->doc4_matri->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_matricula
			$this->id_matricula->EditCustomAttributes = "";
			$this->id_matricula->EditValue = $this->id_matricula->CurrentValue;
			$this->id_matricula->ViewCustomAttributes = "";

			// id_afiliado
			$this->id_afiliado->EditCustomAttributes = "";
			if ($this->id_afiliado->getSessionValue() <> "") {
				$this->id_afiliado->CurrentValue = $this->id_afiliado->getSessionValue();
				$this->id_afiliado->OldValue = $this->id_afiliado->CurrentValue;
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
			} else {
			$this->id_afiliado->EditValue = ew_HtmlEncode($this->id_afiliado->CurrentValue);
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
					$this->id_afiliado->EditValue = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->id_afiliado->EditValue .= ew_ValueSeparator(1,$this->id_afiliado) . ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->id_afiliado->EditValue .= ew_ValueSeparator(2,$this->id_afiliado) . ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$rswrk->Close();
				} else {
					$this->id_afiliado->EditValue = ew_HtmlEncode($this->id_afiliado->CurrentValue);
				}
			} else {
				$this->id_afiliado->EditValue = NULL;
			}
			$this->id_afiliado->PlaceHolder = ew_RemoveHtml($this->id_afiliado->FldCaption());
			}

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
			$this->valor_matri->EditValue = ew_HtmlEncode($this->valor_matri->CurrentValue);
			$this->valor_matri->PlaceHolder = ew_RemoveHtml($this->valor_matri->FldCaption());
			if (strval($this->valor_matri->EditValue) <> "" && is_numeric($this->valor_matri->EditValue)) {
			$this->valor_matri->EditValue = ew_FormatNumber($this->valor_matri->EditValue, -2, 0, 0, -2);
			$this->valor_matri->OldValue = $this->valor_matri->EditValue;
			}

			// valor_men_matri
			$this->valor_men_matri->EditCustomAttributes = "";
			$this->valor_men_matri->EditValue = ew_HtmlEncode($this->valor_men_matri->CurrentValue);
			$this->valor_men_matri->PlaceHolder = ew_RemoveHtml($this->valor_men_matri->FldCaption());
			if (strval($this->valor_men_matri->EditValue) <> "" && is_numeric($this->valor_men_matri->EditValue)) {
			$this->valor_men_matri->EditValue = ew_FormatNumber($this->valor_men_matri->EditValue, -2, -2, -2, -2);
			$this->valor_men_matri->OldValue = $this->valor_men_matri->EditValue;
			}

			// conv_matri
			$this->conv_matri->EditCustomAttributes = "";
			$this->conv_matri->EditValue = ew_HtmlEncode($this->conv_matri->CurrentValue);
			$this->conv_matri->PlaceHolder = ew_RemoveHtml($this->conv_matri->FldCaption());

			// id_empleado
			$this->id_empleado->EditCustomAttributes = "";
			$this->id_empleado->CurrentValue = CurrentUserID();

			// doc4_matri
			$this->doc4_matri->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->doc4_matri->FldTagValue(1), $this->doc4_matri->FldTagCaption(1) <> "" ? $this->doc4_matri->FldTagCaption(1) : $this->doc4_matri->FldTagValue(1));
			$this->doc4_matri->EditValue = $arwrk;

			// Edit refer script
			// id_matricula

			$this->id_matricula->HrefValue = "";

			// id_afiliado
			$this->id_afiliado->HrefValue = "";

			// tipo_matri
			$this->tipo_matri->HrefValue = "";

			// id_plan
			$this->id_plan->HrefValue = "";

			// valor_matri
			$this->valor_matri->HrefValue = "";

			// valor_men_matri
			$this->valor_men_matri->HrefValue = "";

			// conv_matri
			$this->conv_matri->HrefValue = "";

			// id_empleado
			$this->id_empleado->HrefValue = "";

			// doc4_matri
			$this->doc4_matri->HrefValue = "";
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
		if (!ew_CheckInteger($this->id_afiliado->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_afiliado->FldErrMsg());
		}
		if (!$this->id_plan->FldIsDetailKey && !is_null($this->id_plan->FormValue) && $this->id_plan->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id_plan->FldCaption());
		}
		if (!ew_CheckNumber($this->valor_matri->FormValue)) {
			ew_AddMessage($gsFormError, $this->valor_matri->FldErrMsg());
		}
		if (!ew_CheckNumber($this->valor_men_matri->FormValue)) {
			ew_AddMessage($gsFormError, $this->valor_men_matri->FldErrMsg());
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
				$sThisKey .= $row['id_matricula'];
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

			// tipo_matri
			$this->tipo_matri->SetDbValueDef($rsnew, $this->tipo_matri->CurrentValue, NULL, $this->tipo_matri->ReadOnly);

			// id_plan
			$this->id_plan->SetDbValueDef($rsnew, $this->id_plan->CurrentValue, NULL, $this->id_plan->ReadOnly);

			// valor_matri
			$this->valor_matri->SetDbValueDef($rsnew, $this->valor_matri->CurrentValue, NULL, $this->valor_matri->ReadOnly);

			// valor_men_matri
			$this->valor_men_matri->SetDbValueDef($rsnew, $this->valor_men_matri->CurrentValue, NULL, $this->valor_men_matri->ReadOnly);

			// conv_matri
			$this->conv_matri->SetDbValueDef($rsnew, $this->conv_matri->CurrentValue, NULL, $this->conv_matri->ReadOnly);

			// id_empleado
			$this->id_empleado->SetDbValueDef($rsnew, $this->id_empleado->CurrentValue, NULL, $this->id_empleado->ReadOnly);

			// doc4_matri
			$this->doc4_matri->SetDbValueDef($rsnew, $this->doc4_matri->CurrentValue, NULL, $this->doc4_matri->ReadOnly);

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

		// tipo_matri
		$this->tipo_matri->SetDbValueDef($rsnew, $this->tipo_matri->CurrentValue, NULL, FALSE);

		// id_plan
		$this->id_plan->SetDbValueDef($rsnew, $this->id_plan->CurrentValue, NULL, FALSE);

		// valor_matri
		$this->valor_matri->SetDbValueDef($rsnew, $this->valor_matri->CurrentValue, NULL, FALSE);

		// valor_men_matri
		$this->valor_men_matri->SetDbValueDef($rsnew, $this->valor_men_matri->CurrentValue, NULL, FALSE);

		// conv_matri
		$this->conv_matri->SetDbValueDef($rsnew, $this->conv_matri->CurrentValue, NULL, FALSE);

		// id_empleado
		$this->id_empleado->SetDbValueDef($rsnew, $this->id_empleado->CurrentValue, NULL, FALSE);

		// doc4_matri
		$this->doc4_matri->SetDbValueDef($rsnew, $this->doc4_matri->CurrentValue, NULL, FALSE);

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
			$this->id_matricula->setDbValue($conn->Insert_ID());
			$rsnew['id_matricula'] = $this->id_matricula->DbValue;
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
