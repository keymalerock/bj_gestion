<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "respuestainfo.php" ?>
<?php include_once "novedadinfo.php" ?>
<?php include_once "v_usuariosinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$respuesta_list = NULL; // Initialize page object first

class crespuesta_list extends crespuesta {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'respuesta';

	// Page object name
	var $PageObjName = 'respuesta_list';

	// Grid form hidden field names
	var $FormName = 'frespuestalist';
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

		// Table object (respuesta)
		if (!isset($GLOBALS["respuesta"]) || get_class($GLOBALS["respuesta"]) == "crespuesta") {
			$GLOBALS["respuesta"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["respuesta"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "respuestaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "respuestadelete.php";
		$this->MultiUpdateUrl = "respuestaupdate.php";

		// Table object (novedad)
		if (!isset($GLOBALS['novedad'])) $GLOBALS['novedad'] = new cnovedad();

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'respuesta', TRUE);

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
		$this->id_respuesta->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "novedad") {
			global $novedad;
			$rsmaster = $novedad->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("novedadlist.php"); // Return to master page
			} else {
				$novedad->LoadListRowValues($rsmaster);
				$novedad->RowType = EW_ROWTYPE_MASTER; // Master row
				$novedad->RenderListRow();
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
			$this->id_respuesta->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_respuesta->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_respuesta); // id_respuesta
			$this->UpdateSort($this->id_novedad); // id_novedad
			$this->UpdateSort($this->id_empleado); // id_empleado
			$this->UpdateSort($this->fe_resp); // fe_resp
			$this->UpdateSort($this->estado_resp); // estado_resp
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
				$this->id_respuesta->setSort("DESC");
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
				$this->id_novedad->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id_respuesta->setSort("");
				$this->id_novedad->setSort("");
				$this->id_empleado->setSort("");
				$this->fe_resp->setSort("");
				$this->estado_resp->setSort("");
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

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
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

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_respuesta->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.frespuestalist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->id_respuesta->setDbValue($rs->fields('id_respuesta'));
		$this->id_novedad->setDbValue($rs->fields('id_novedad'));
		$this->id_empleado->setDbValue($rs->fields('id_empleado'));
		$this->obs_resp->setDbValue($rs->fields('obs_resp'));
		$this->fe_resp->setDbValue($rs->fields('fe_resp'));
		$this->estado_resp->setDbValue($rs->fields('estado_resp'));
		$this->replica_resp->setDbValue($rs->fields('replica_resp'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_respuesta->DbValue = $row['id_respuesta'];
		$this->id_novedad->DbValue = $row['id_novedad'];
		$this->id_empleado->DbValue = $row['id_empleado'];
		$this->obs_resp->DbValue = $row['obs_resp'];
		$this->fe_resp->DbValue = $row['fe_resp'];
		$this->estado_resp->DbValue = $row['estado_resp'];
		$this->replica_resp->DbValue = $row['replica_resp'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_respuesta")) <> "")
			$this->id_respuesta->CurrentValue = $this->getKey("id_respuesta"); // id_respuesta
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
		// id_respuesta
		// id_novedad
		// id_empleado
		// obs_resp
		// fe_resp
		// estado_resp
		// replica_resp

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_respuesta
			$this->id_respuesta->ViewValue = $this->id_respuesta->CurrentValue;
			$this->id_respuesta->ViewCustomAttributes = "";

			// id_novedad
			$this->id_novedad->ViewValue = $this->id_novedad->CurrentValue;
			$this->id_novedad->ViewCustomAttributes = "";

			// id_empleado
			if (strval($this->id_empleado->CurrentValue) <> "") {
				$sFilterWrk = "`id_empleado`" . ew_SearchString("=", $this->id_empleado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_empleado`, `dociden_empleado` AS `DispFld`, `nomb_empleado` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empleados`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_empleado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nomb_empleado`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_empleado->ViewValue = $rswrk->fields('DispFld');
					$this->id_empleado->ViewValue .= ew_ValueSeparator(1,$this->id_empleado) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_empleado->ViewValue = $this->id_empleado->CurrentValue;
				}
			} else {
				$this->id_empleado->ViewValue = NULL;
			}
			$this->id_empleado->ViewCustomAttributes = "";

			// fe_resp
			$this->fe_resp->ViewValue = $this->fe_resp->CurrentValue;
			$this->fe_resp->ViewValue = ew_FormatDateTime($this->fe_resp->ViewValue, 5);
			$this->fe_resp->ViewCustomAttributes = "";

			// estado_resp
			if (strval($this->estado_resp->CurrentValue) <> "") {
				$sFilterWrk = "`id_x_estado_respuesta`" . ew_SearchString("=", $this->estado_resp->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_x_estado_respuesta`, `estado_respuesta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `x_estado_respuesta`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->estado_resp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->estado_resp->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->estado_resp->ViewValue = $this->estado_resp->CurrentValue;
				}
			} else {
				$this->estado_resp->ViewValue = NULL;
			}
			$this->estado_resp->ViewCustomAttributes = "";

			// id_respuesta
			$this->id_respuesta->LinkCustomAttributes = "";
			$this->id_respuesta->HrefValue = "";
			$this->id_respuesta->TooltipValue = "";

			// id_novedad
			$this->id_novedad->LinkCustomAttributes = "";
			$this->id_novedad->HrefValue = "";
			$this->id_novedad->TooltipValue = "";

			// id_empleado
			$this->id_empleado->LinkCustomAttributes = "";
			$this->id_empleado->HrefValue = "";
			$this->id_empleado->TooltipValue = "";

			// fe_resp
			$this->fe_resp->LinkCustomAttributes = "";
			$this->fe_resp->HrefValue = "";
			$this->fe_resp->TooltipValue = "";

			// estado_resp
			$this->estado_resp->LinkCustomAttributes = "";
			$this->estado_resp->HrefValue = "";
			$this->estado_resp->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
			if ($sMasterTblVar == "novedad") {
				$bValidMaster = TRUE;
				if (@$_GET["id_novedad"] <> "") {
					$GLOBALS["novedad"]->id_novedad->setQueryStringValue($_GET["id_novedad"]);
					$this->id_novedad->setQueryStringValue($GLOBALS["novedad"]->id_novedad->QueryStringValue);
					$this->id_novedad->setSessionValue($this->id_novedad->QueryStringValue);
					if (!is_numeric($GLOBALS["novedad"]->id_novedad->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "novedad") {
				if ($this->id_novedad->QueryStringValue == "") $this->id_novedad->setSessionValue("");
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
if (!isset($respuesta_list)) $respuesta_list = new crespuesta_list();

// Page init
$respuesta_list->Page_Init();

// Page main
$respuesta_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$respuesta_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var respuesta_list = new ew_Page("respuesta_list");
respuesta_list.PageID = "list"; // Page ID
var EW_PAGE_ID = respuesta_list.PageID; // For backward compatibility

// Form object
var frespuestalist = new ew_Form("frespuestalist");
frespuestalist.FormKeyCountName = '<?php echo $respuesta_list->FormKeyCountName ?>';

// Form_CustomValidate event
frespuestalist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frespuestalist.ValidateRequired = true;
<?php } else { ?>
frespuestalist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frespuestalist.Lists["x_id_empleado"] = {"LinkField":"x_id_empleado","Ajax":null,"AutoFill":false,"DisplayFields":["x_dociden_empleado","x_nomb_empleado","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frespuestalist.Lists["x_estado_resp"] = {"LinkField":"x_id_x_estado_respuesta","Ajax":null,"AutoFill":false,"DisplayFields":["x_estado_respuesta","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php if ($respuesta->getCurrentMasterTable() == "" && $respuesta_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $respuesta_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php if (($respuesta->Export == "") || (EW_EXPORT_MASTER_RECORD && $respuesta->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "novedadlist.php";
if ($respuesta_list->DbMasterFilter <> "" && $respuesta->getCurrentMasterTable() == "novedad") {
	if ($respuesta_list->MasterRecordExists) {
		if ($respuesta->getCurrentMasterTable() == $respuesta->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($respuesta_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $respuesta_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once "novedadmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$respuesta_list->TotalRecs = $respuesta->SelectRecordCount();
	} else {
		if ($respuesta_list->Recordset = $respuesta_list->LoadRecordset())
			$respuesta_list->TotalRecs = $respuesta_list->Recordset->RecordCount();
	}
	$respuesta_list->StartRec = 1;
	if ($respuesta_list->DisplayRecs <= 0 || ($respuesta->Export <> "" && $respuesta->ExportAll)) // Display all records
		$respuesta_list->DisplayRecs = $respuesta_list->TotalRecs;
	if (!($respuesta->Export <> "" && $respuesta->ExportAll))
		$respuesta_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$respuesta_list->Recordset = $respuesta_list->LoadRecordset($respuesta_list->StartRec-1, $respuesta_list->DisplayRecs);
$respuesta_list->RenderOtherOptions();
?>
<?php $respuesta_list->ShowPageHeader(); ?>
<?php
$respuesta_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<form name="frespuestalist" id="frespuestalist" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="respuesta">
<div id="gmp_respuesta" class="ewGridMiddlePanel">
<?php if ($respuesta_list->TotalRecs > 0) { ?>
<table id="tbl_respuestalist" class="ewTable ewTableSeparate">
<?php echo $respuesta->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$respuesta_list->RenderListOptions();

// Render list options (header, left)
$respuesta_list->ListOptions->Render("header", "left");
?>
<?php if ($respuesta->id_respuesta->Visible) { // id_respuesta ?>
	<?php if ($respuesta->SortUrl($respuesta->id_respuesta) == "") { ?>
		<td><div id="elh_respuesta_id_respuesta" class="respuesta_id_respuesta"><div class="ewTableHeaderCaption"><?php echo $respuesta->id_respuesta->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $respuesta->SortUrl($respuesta->id_respuesta) ?>',1);"><div id="elh_respuesta_id_respuesta" class="respuesta_id_respuesta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $respuesta->id_respuesta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($respuesta->id_respuesta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($respuesta->id_respuesta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($respuesta->id_novedad->Visible) { // id_novedad ?>
	<?php if ($respuesta->SortUrl($respuesta->id_novedad) == "") { ?>
		<td><div id="elh_respuesta_id_novedad" class="respuesta_id_novedad"><div class="ewTableHeaderCaption"><?php echo $respuesta->id_novedad->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $respuesta->SortUrl($respuesta->id_novedad) ?>',1);"><div id="elh_respuesta_id_novedad" class="respuesta_id_novedad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $respuesta->id_novedad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($respuesta->id_novedad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($respuesta->id_novedad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($respuesta->id_empleado->Visible) { // id_empleado ?>
	<?php if ($respuesta->SortUrl($respuesta->id_empleado) == "") { ?>
		<td><div id="elh_respuesta_id_empleado" class="respuesta_id_empleado"><div class="ewTableHeaderCaption"><?php echo $respuesta->id_empleado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $respuesta->SortUrl($respuesta->id_empleado) ?>',1);"><div id="elh_respuesta_id_empleado" class="respuesta_id_empleado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $respuesta->id_empleado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($respuesta->id_empleado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($respuesta->id_empleado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($respuesta->fe_resp->Visible) { // fe_resp ?>
	<?php if ($respuesta->SortUrl($respuesta->fe_resp) == "") { ?>
		<td><div id="elh_respuesta_fe_resp" class="respuesta_fe_resp"><div class="ewTableHeaderCaption"><?php echo $respuesta->fe_resp->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $respuesta->SortUrl($respuesta->fe_resp) ?>',1);"><div id="elh_respuesta_fe_resp" class="respuesta_fe_resp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $respuesta->fe_resp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($respuesta->fe_resp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($respuesta->fe_resp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($respuesta->estado_resp->Visible) { // estado_resp ?>
	<?php if ($respuesta->SortUrl($respuesta->estado_resp) == "") { ?>
		<td><div id="elh_respuesta_estado_resp" class="respuesta_estado_resp"><div class="ewTableHeaderCaption"><?php echo $respuesta->estado_resp->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $respuesta->SortUrl($respuesta->estado_resp) ?>',1);"><div id="elh_respuesta_estado_resp" class="respuesta_estado_resp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $respuesta->estado_resp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($respuesta->estado_resp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($respuesta->estado_resp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$respuesta_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($respuesta->ExportAll && $respuesta->Export <> "") {
	$respuesta_list->StopRec = $respuesta_list->TotalRecs;
} else {

	// Set the last record to display
	if ($respuesta_list->TotalRecs > $respuesta_list->StartRec + $respuesta_list->DisplayRecs - 1)
		$respuesta_list->StopRec = $respuesta_list->StartRec + $respuesta_list->DisplayRecs - 1;
	else
		$respuesta_list->StopRec = $respuesta_list->TotalRecs;
}
$respuesta_list->RecCnt = $respuesta_list->StartRec - 1;
if ($respuesta_list->Recordset && !$respuesta_list->Recordset->EOF) {
	$respuesta_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $respuesta_list->StartRec > 1)
		$respuesta_list->Recordset->Move($respuesta_list->StartRec - 1);
} elseif (!$respuesta->AllowAddDeleteRow && $respuesta_list->StopRec == 0) {
	$respuesta_list->StopRec = $respuesta->GridAddRowCount;
}

// Initialize aggregate
$respuesta->RowType = EW_ROWTYPE_AGGREGATEINIT;
$respuesta->ResetAttrs();
$respuesta_list->RenderRow();
while ($respuesta_list->RecCnt < $respuesta_list->StopRec) {
	$respuesta_list->RecCnt++;
	if (intval($respuesta_list->RecCnt) >= intval($respuesta_list->StartRec)) {
		$respuesta_list->RowCnt++;

		// Set up key count
		$respuesta_list->KeyCount = $respuesta_list->RowIndex;

		// Init row class and style
		$respuesta->ResetAttrs();
		$respuesta->CssClass = "";
		if ($respuesta->CurrentAction == "gridadd") {
		} else {
			$respuesta_list->LoadRowValues($respuesta_list->Recordset); // Load row values
		}
		$respuesta->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$respuesta->RowAttrs = array_merge($respuesta->RowAttrs, array('data-rowindex'=>$respuesta_list->RowCnt, 'id'=>'r' . $respuesta_list->RowCnt . '_respuesta', 'data-rowtype'=>$respuesta->RowType));

		// Render row
		$respuesta_list->RenderRow();

		// Render list options
		$respuesta_list->RenderListOptions();
?>
	<tr<?php echo $respuesta->RowAttributes() ?>>
<?php

// Render list options (body, left)
$respuesta_list->ListOptions->Render("body", "left", $respuesta_list->RowCnt);
?>
	<?php if ($respuesta->id_respuesta->Visible) { // id_respuesta ?>
		<td<?php echo $respuesta->id_respuesta->CellAttributes() ?>>
<span<?php echo $respuesta->id_respuesta->ViewAttributes() ?>>
<?php echo $respuesta->id_respuesta->ListViewValue() ?></span>
<a id="<?php echo $respuesta_list->PageObjName . "_row_" . $respuesta_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($respuesta->id_novedad->Visible) { // id_novedad ?>
		<td<?php echo $respuesta->id_novedad->CellAttributes() ?>>
<span<?php echo $respuesta->id_novedad->ViewAttributes() ?>>
<?php echo $respuesta->id_novedad->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($respuesta->id_empleado->Visible) { // id_empleado ?>
		<td<?php echo $respuesta->id_empleado->CellAttributes() ?>>
<span<?php echo $respuesta->id_empleado->ViewAttributes() ?>>
<?php echo $respuesta->id_empleado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($respuesta->fe_resp->Visible) { // fe_resp ?>
		<td<?php echo $respuesta->fe_resp->CellAttributes() ?>>
<span<?php echo $respuesta->fe_resp->ViewAttributes() ?>>
<?php echo $respuesta->fe_resp->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($respuesta->estado_resp->Visible) { // estado_resp ?>
		<td<?php echo $respuesta->estado_resp->CellAttributes() ?>>
<span<?php echo $respuesta->estado_resp->ViewAttributes() ?>>
<?php echo $respuesta->estado_resp->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$respuesta_list->ListOptions->Render("body", "right", $respuesta_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($respuesta->CurrentAction <> "gridadd")
		$respuesta_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($respuesta->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($respuesta_list->Recordset)
	$respuesta_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($respuesta->CurrentAction <> "gridadd" && $respuesta->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($respuesta_list->Pager)) $respuesta_list->Pager = new cPrevNextPager($respuesta_list->StartRec, $respuesta_list->DisplayRecs, $respuesta_list->TotalRecs) ?>
<?php if ($respuesta_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($respuesta_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $respuesta_list->PageUrl() ?>start=<?php echo $respuesta_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($respuesta_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $respuesta_list->PageUrl() ?>start=<?php echo $respuesta_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $respuesta_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($respuesta_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $respuesta_list->PageUrl() ?>start=<?php echo $respuesta_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($respuesta_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $respuesta_list->PageUrl() ?>start=<?php echo $respuesta_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $respuesta_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $respuesta_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $respuesta_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $respuesta_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($respuesta_list->SearchWhere == "0=101") { ?>
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
	foreach ($respuesta_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
</td></tr></table>
<script type="text/javascript">
frespuestalist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$respuesta_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$respuesta_list->Page_Terminate();
?>
