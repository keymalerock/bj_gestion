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

$representantes_edit = NULL; // Initialize page object first

class crepresentantes_edit extends crepresentantes {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'representantes';

	// Page object name
	var $PageObjName = 'representantes_edit';

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

		// Table object (afiliado)
		if (!isset($GLOBALS['afiliado'])) $GLOBALS['afiliado'] = new cafiliado();

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'representantes', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("representanteslist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id_representante"] <> "") {
			$this->id_representante->setQueryStringValue($_GET["id_representante"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id_representante->CurrentValue == "")
			$this->Page_Terminate("representanteslist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("representanteslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = "representanteslist.php";
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_afiliado->FldIsDetailKey) {
			$this->id_afiliado->setFormValue($objForm->GetValue("x_id_afiliado"));
		}
		if (!$this->dociden_repres->FldIsDetailKey) {
			$this->dociden_repres->setFormValue($objForm->GetValue("x_dociden_repres"));
		}
		if (!$this->apell_repres->FldIsDetailKey) {
			$this->apell_repres->setFormValue($objForm->GetValue("x_apell_repres"));
		}
		if (!$this->nomb_repres->FldIsDetailKey) {
			$this->nomb_repres->setFormValue($objForm->GetValue("x_nomb_repres"));
		}
		if (!$this->telf_resi_repres->FldIsDetailKey) {
			$this->telf_resi_repres->setFormValue($objForm->GetValue("x_telf_resi_repres"));
		}
		if (!$this->email_repres->FldIsDetailKey) {
			$this->email_repres->setFormValue($objForm->GetValue("x_email_repres"));
		}
		if (!$this->par_repres->FldIsDetailKey) {
			$this->par_repres->setFormValue($objForm->GetValue("x_par_repres"));
		}
		if (!$this->cel_repres->FldIsDetailKey) {
			$this->cel_repres->setFormValue($objForm->GetValue("x_cel_repres"));
		}
		if (!$this->contact_e_repres->FldIsDetailKey) {
			$this->contact_e_repres->setFormValue($objForm->GetValue("x_contact_e_repres"));
		}
		if (!$this->contact_d_repres->FldIsDetailKey) {
			$this->contact_d_repres->setFormValue($objForm->GetValue("x_contact_d_repres"));
		}
		if (!$this->st_repres->FldIsDetailKey) {
			$this->st_repres->setFormValue($objForm->GetValue("x_st_repres"));
		}
		if (!$this->id_representante->FldIsDetailKey)
			$this->id_representante->setFormValue($objForm->GetValue("x_id_representante"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_afiliado
			$this->id_afiliado->EditCustomAttributes = "";
			if ($this->id_afiliado->getSessionValue() <> "") {
				$this->id_afiliado->CurrentValue = $this->id_afiliado->getSessionValue();
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

		// Initialize form error message
		$gsFormError = "";

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
		$Breadcrumb->Add("list", $this->TableVar, "representanteslist.php", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, ew_CurrentUrl());
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
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($representantes_edit)) $representantes_edit = new crepresentantes_edit();

// Page init
$representantes_edit->Page_Init();

// Page main
$representantes_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$representantes_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var representantes_edit = new ew_Page("representantes_edit");
representantes_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = representantes_edit.PageID; // For backward compatibility

// Form object
var frepresentantesedit = new ew_Form("frepresentantesedit");

// Validate form
frepresentantesedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_id_afiliado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($representantes->id_afiliado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dociden_repres");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($representantes->dociden_repres->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_apell_repres");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($representantes->apell_repres->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nomb_repres");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($representantes->nomb_repres->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_email_repres");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($representantes->email_repres->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
frepresentantesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frepresentantesedit.ValidateRequired = true;
<?php } else { ?>
frepresentantesedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frepresentantesedit.Lists["x_id_afiliado"] = {"LinkField":"x_id_afiliado","Ajax":true,"AutoFill":false,"DisplayFields":["x_apell_afiliado","x_nomb_afiliado","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $representantes_edit->ShowPageHeader(); ?>
<?php
$representantes_edit->ShowMessage();
?>
<form name="frepresentantesedit" id="frepresentantesedit" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="representantes">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewGrid"><tr><td>
<table id="tbl_representantesedit" class="table table-bordered table-striped">
<?php if ($representantes->id_afiliado->Visible) { // id_afiliado ?>
	<tr id="r_id_afiliado">
		<td><span id="elh_representantes_id_afiliado"><?php echo $representantes->id_afiliado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $representantes->id_afiliado->CellAttributes() ?>>
<?php if ($representantes->id_afiliado->getSessionValue() <> "") { ?>
<span<?php echo $representantes->id_afiliado->ViewAttributes() ?>>
<?php echo $representantes->id_afiliado->ViewValue ?></span>
<input type="hidden" id="x_id_afiliado" name="x_id_afiliado" value="<?php echo ew_HtmlEncode($representantes->id_afiliado->CurrentValue) ?>">
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
<input type="hidden" data-field="x_id_afiliado" name="x_id_afiliado" id="x_id_afiliado" value="<?php echo ew_HtmlEncode($representantes->id_afiliado->CurrentValue) ?>"<?php echo $wrkonchange ?>>
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
var oas = new ew_AutoSuggest("x_id_afiliado", frepresentantesedit, true, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_id_afiliado") + ar[i] : "";
	return dv;
}
frepresentantesedit.AutoSuggests["x_id_afiliado"] = oas;
</script>
<?php } ?>
<?php echo $representantes->id_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($representantes->dociden_repres->Visible) { // dociden_repres ?>
	<tr id="r_dociden_repres">
		<td><span id="elh_representantes_dociden_repres"><?php echo $representantes->dociden_repres->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $representantes->dociden_repres->CellAttributes() ?>>
<span id="el_representantes_dociden_repres" class="control-group">
<input type="text" data-field="x_dociden_repres" name="x_dociden_repres" id="x_dociden_repres" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($representantes->dociden_repres->PlaceHolder) ?>" value="<?php echo $representantes->dociden_repres->EditValue ?>"<?php echo $representantes->dociden_repres->EditAttributes() ?>>
</span>
<?php echo $representantes->dociden_repres->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($representantes->apell_repres->Visible) { // apell_repres ?>
	<tr id="r_apell_repres">
		<td><span id="elh_representantes_apell_repres"><?php echo $representantes->apell_repres->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $representantes->apell_repres->CellAttributes() ?>>
<span id="el_representantes_apell_repres" class="control-group">
<input type="text" data-field="x_apell_repres" name="x_apell_repres" id="x_apell_repres" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($representantes->apell_repres->PlaceHolder) ?>" value="<?php echo $representantes->apell_repres->EditValue ?>"<?php echo $representantes->apell_repres->EditAttributes() ?>>
</span>
<?php echo $representantes->apell_repres->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($representantes->nomb_repres->Visible) { // nomb_repres ?>
	<tr id="r_nomb_repres">
		<td><span id="elh_representantes_nomb_repres"><?php echo $representantes->nomb_repres->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $representantes->nomb_repres->CellAttributes() ?>>
<span id="el_representantes_nomb_repres" class="control-group">
<input type="text" data-field="x_nomb_repres" name="x_nomb_repres" id="x_nomb_repres" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($representantes->nomb_repres->PlaceHolder) ?>" value="<?php echo $representantes->nomb_repres->EditValue ?>"<?php echo $representantes->nomb_repres->EditAttributes() ?>>
</span>
<?php echo $representantes->nomb_repres->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($representantes->telf_resi_repres->Visible) { // telf_resi_repres ?>
	<tr id="r_telf_resi_repres">
		<td><span id="elh_representantes_telf_resi_repres"><?php echo $representantes->telf_resi_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->telf_resi_repres->CellAttributes() ?>>
<span id="el_representantes_telf_resi_repres" class="control-group">
<input type="text" data-field="x_telf_resi_repres" name="x_telf_resi_repres" id="x_telf_resi_repres" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($representantes->telf_resi_repres->PlaceHolder) ?>" value="<?php echo $representantes->telf_resi_repres->EditValue ?>"<?php echo $representantes->telf_resi_repres->EditAttributes() ?>>
</span>
<?php echo $representantes->telf_resi_repres->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($representantes->email_repres->Visible) { // email_repres ?>
	<tr id="r_email_repres">
		<td><span id="elh_representantes_email_repres"><?php echo $representantes->email_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->email_repres->CellAttributes() ?>>
<span id="el_representantes_email_repres" class="control-group">
<input type="text" data-field="x_email_repres" name="x_email_repres" id="x_email_repres" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($representantes->email_repres->PlaceHolder) ?>" value="<?php echo $representantes->email_repres->EditValue ?>"<?php echo $representantes->email_repres->EditAttributes() ?>>
</span>
<?php echo $representantes->email_repres->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($representantes->par_repres->Visible) { // par_repres ?>
	<tr id="r_par_repres">
		<td><span id="elh_representantes_par_repres"><?php echo $representantes->par_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->par_repres->CellAttributes() ?>>
<span id="el_representantes_par_repres" class="control-group">
<div id="tp_x_par_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_par_repres" id="x_par_repres" value="{value}"<?php echo $representantes->par_repres->EditAttributes() ?>></div>
<div id="dsl_x_par_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->par_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->par_repres->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_par_repres" name="x_par_repres" id="x_par_repres_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $representantes->par_repres->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $representantes->par_repres->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($representantes->cel_repres->Visible) { // cel_repres ?>
	<tr id="r_cel_repres">
		<td><span id="elh_representantes_cel_repres"><?php echo $representantes->cel_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->cel_repres->CellAttributes() ?>>
<span id="el_representantes_cel_repres" class="control-group">
<input type="text" data-field="x_cel_repres" name="x_cel_repres" id="x_cel_repres" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($representantes->cel_repres->PlaceHolder) ?>" value="<?php echo $representantes->cel_repres->EditValue ?>"<?php echo $representantes->cel_repres->EditAttributes() ?>>
</span>
<?php echo $representantes->cel_repres->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($representantes->contact_e_repres->Visible) { // contact_e_repres ?>
	<tr id="r_contact_e_repres">
		<td><span id="elh_representantes_contact_e_repres"><?php echo $representantes->contact_e_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->contact_e_repres->CellAttributes() ?>>
<span id="el_representantes_contact_e_repres" class="control-group">
<div id="tp_x_contact_e_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_contact_e_repres" id="x_contact_e_repres" value="{value}"<?php echo $representantes->contact_e_repres->EditAttributes() ?>></div>
<div id="dsl_x_contact_e_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->contact_e_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->contact_e_repres->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
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
<?php echo $representantes->contact_e_repres->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($representantes->contact_d_repres->Visible) { // contact_d_repres ?>
	<tr id="r_contact_d_repres">
		<td><span id="elh_representantes_contact_d_repres"><?php echo $representantes->contact_d_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->contact_d_repres->CellAttributes() ?>>
<span id="el_representantes_contact_d_repres" class="control-group">
<div id="tp_x_contact_d_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_contact_d_repres" id="x_contact_d_repres" value="{value}"<?php echo $representantes->contact_d_repres->EditAttributes() ?>></div>
<div id="dsl_x_contact_d_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->contact_d_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->contact_d_repres->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_contact_d_repres" name="x_contact_d_repres" id="x_contact_d_repres_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $representantes->contact_d_repres->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $representantes->contact_d_repres->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($representantes->st_repres->Visible) { // st_repres ?>
	<tr id="r_st_repres">
		<td><span id="elh_representantes_st_repres"><?php echo $representantes->st_repres->FldCaption() ?></span></td>
		<td<?php echo $representantes->st_repres->CellAttributes() ?>>
<span id="el_representantes_st_repres" class="control-group">
<div id="tp_x_st_repres" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_st_repres" id="x_st_repres" value="{value}"<?php echo $representantes->st_repres->EditAttributes() ?>></div>
<div id="dsl_x_st_repres" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $representantes->st_repres->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($representantes->st_repres->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
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
<?php echo $representantes->st_repres->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_id_representante" name="x_id_representante" id="x_id_representante" value="<?php echo ew_HtmlEncode($representantes->id_representante->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
frepresentantesedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$representantes_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$representantes_edit->Page_Terminate();
?>
