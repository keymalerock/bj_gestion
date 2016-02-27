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

$novedad_add = NULL; // Initialize page object first

class cnovedad_add extends cnovedad {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'novedad';

	// Page object name
	var $PageObjName = 'novedad_add';

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

		// Table object (novedad)
		if (!isset($GLOBALS["novedad"]) || get_class($GLOBALS["novedad"]) == "cnovedad") {
			$GLOBALS["novedad"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["novedad"];
		}

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'novedad', TRUE);

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
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id_novedad"] != "") {
				$this->id_novedad->setQueryStringValue($_GET["id_novedad"]);
				$this->setKey("id_novedad", $this->id_novedad->CurrentValue); // Set up key
			} else {
				$this->setKey("id_novedad", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Set up detail parameters
		$this->SetUpDetailParms();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("novedadlist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->GetAddUrl();
					if (ew_GetPageName($sReturnUrl) == "novedadview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id_afiliado->CurrentValue = NULL;
		$this->id_afiliado->OldValue = $this->id_afiliado->CurrentValue;
		$this->obs_nov->CurrentValue = NULL;
		$this->obs_nov->OldValue = $this->obs_nov->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_afiliado->FldIsDetailKey) {
			$this->id_afiliado->setFormValue($objForm->GetValue("x_id_afiliado"));
		}
		if (!$this->obs_nov->FldIsDetailKey) {
			$this->obs_nov->setFormValue($objForm->GetValue("x_obs_nov"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id_afiliado->CurrentValue = $this->id_afiliado->FormValue;
		$this->obs_nov->CurrentValue = $this->obs_nov->FormValue;
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

			// id_afiliado
			$this->id_afiliado->LinkCustomAttributes = "";
			$this->id_afiliado->HrefValue = "";
			$this->id_afiliado->TooltipValue = "";

			// obs_nov
			$this->obs_nov->LinkCustomAttributes = "";
			$this->obs_nov->HrefValue = "";
			$this->obs_nov->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_afiliado
			$this->id_afiliado->EditCustomAttributes = "";
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

			// obs_nov
			$this->obs_nov->EditCustomAttributes = "";
			$this->obs_nov->EditValue = $this->obs_nov->CurrentValue;
			$this->obs_nov->PlaceHolder = ew_RemoveHtml($this->obs_nov->FldCaption());

			// Edit refer script
			// id_afiliado

			$this->id_afiliado->HrefValue = "";

			// obs_nov
			$this->obs_nov->HrefValue = "";
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
		if (!ew_CheckInteger($this->id_afiliado->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_afiliado->FldErrMsg());
		}
		if (!$this->obs_nov->FldIsDetailKey && !is_null($this->obs_nov->FormValue) && $this->obs_nov->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->obs_nov->FldCaption());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("respuesta", $DetailTblVar) && $GLOBALS["respuesta"]->DetailAdd) {
			if (!isset($GLOBALS["respuesta_grid"])) $GLOBALS["respuesta_grid"] = new crespuesta_grid(); // get detail page object
			$GLOBALS["respuesta_grid"]->ValidateGridForm();
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// id_afiliado
		$this->id_afiliado->SetDbValueDef($rsnew, $this->id_afiliado->CurrentValue, NULL, FALSE);

		// obs_nov
		$this->obs_nov->SetDbValueDef($rsnew, $this->obs_nov->CurrentValue, NULL, FALSE);

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
			$this->id_novedad->setDbValue($conn->Insert_ID());
			$rsnew['id_novedad'] = $this->id_novedad->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("respuesta", $DetailTblVar) && $GLOBALS["respuesta"]->DetailAdd) {
				$GLOBALS["respuesta"]->id_novedad->setSessionValue($this->id_novedad->CurrentValue); // Set master key
				if (!isset($GLOBALS["respuesta_grid"])) $GLOBALS["respuesta_grid"] = new crespuesta_grid(); // Get detail page object
				$AddRow = $GLOBALS["respuesta_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["respuesta"]->id_novedad->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("respuesta", $DetailTblVar)) {
				if (!isset($GLOBALS["respuesta_grid"]))
					$GLOBALS["respuesta_grid"] = new crespuesta_grid;
				if ($GLOBALS["respuesta_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["respuesta_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["respuesta_grid"]->CurrentMode = "add";
					$GLOBALS["respuesta_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["respuesta_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["respuesta_grid"]->setStartRecordNumber(1);
					$GLOBALS["respuesta_grid"]->id_novedad->FldIsDetailKey = TRUE;
					$GLOBALS["respuesta_grid"]->id_novedad->CurrentValue = $this->id_novedad->CurrentValue;
					$GLOBALS["respuesta_grid"]->id_novedad->setSessionValue($GLOBALS["respuesta_grid"]->id_novedad->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "novedadlist.php", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, ew_CurrentUrl());
	}

	// Page Load event
	function Page_Load() {
		if (CurrentUserLevel() ==2) // si es  tipo empleado no se muestra el campo solo de lectura   
		{                   
			   $this->obs_nov->Visible = FALSE; 
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($novedad_add)) $novedad_add = new cnovedad_add();

// Page init
$novedad_add->Page_Init();

// Page main
$novedad_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$novedad_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var novedad_add = new ew_Page("novedad_add");
novedad_add.PageID = "add"; // Page ID
var EW_PAGE_ID = novedad_add.PageID; // For backward compatibility

// Form object
var fnovedadadd = new ew_Form("fnovedadadd");

// Validate form
fnovedadadd.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($novedad->id_afiliado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id_afiliado");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($novedad->id_afiliado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_obs_nov");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($novedad->obs_nov->FldCaption()) ?>");

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
fnovedadadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnovedadadd.ValidateRequired = true;
<?php } else { ?>
fnovedadadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fnovedadadd.Lists["x_id_afiliado"] = {"LinkField":"x_id_afiliado","Ajax":true,"AutoFill":false,"DisplayFields":["x_dociden_afiliado","x_apell_afiliado","x_nomb_afiliado",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $novedad_add->ShowPageHeader(); ?>
<?php
$novedad_add->ShowMessage();
?>
<form name="fnovedadadd" id="fnovedadadd" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="novedad">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewGrid"><tr><td>
<table id="tbl_novedadadd" class="table table-bordered table-striped">
<?php if ($novedad->id_afiliado->Visible) { // id_afiliado ?>
	<tr id="r_id_afiliado">
		<td><span id="elh_novedad_id_afiliado"><?php echo $novedad->id_afiliado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $novedad->id_afiliado->CellAttributes() ?>>
<span id="el_novedad_id_afiliado" class="control-group">
<?php
	$wrkonchange = trim(" " . @$novedad->id_afiliado->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$novedad->id_afiliado->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_afiliado" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_id_afiliado" id="sv_x_id_afiliado" value="<?php echo $novedad->id_afiliado->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($novedad->id_afiliado->PlaceHolder) ?>"<?php echo $novedad->id_afiliado->EditAttributes() ?>>&nbsp;<span id="em_x_id_afiliado" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_id_afiliado" style="display: inline; z-index: 8980"></div>
</span>
<input type="hidden" data-field="x_id_afiliado" name="x_id_afiliado" id="x_id_afiliado" value="<?php echo ew_HtmlEncode($novedad->id_afiliado->CurrentValue) ?>"<?php echo $wrkonchange ?>>
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
var oas = new ew_AutoSuggest("x_id_afiliado", fnovedadadd, true, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_id_afiliado") + ar[i] : "";
	return dv;
}
fnovedadadd.AutoSuggests["x_id_afiliado"] = oas;
</script>
</span>
<?php echo $novedad->id_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($novedad->obs_nov->Visible) { // obs_nov ?>
	<tr id="r_obs_nov">
		<td><span id="elh_novedad_obs_nov"><?php echo $novedad->obs_nov->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $novedad->obs_nov->CellAttributes() ?>>
<span id="el_novedad_obs_nov" class="control-group">
<textarea data-field="x_obs_nov" name="x_obs_nov" id="x_obs_nov" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($novedad->obs_nov->PlaceHolder) ?>"<?php echo $novedad->obs_nov->EditAttributes() ?>><?php echo $novedad->obs_nov->EditValue ?></textarea>
</span>
<?php echo $novedad->obs_nov->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("respuesta", explode(",", $novedad->getCurrentDetailTable())) && $respuesta->DetailAdd) {
?>
<?php include_once "respuestagrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fnovedadadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$novedad_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$novedad_add->Page_Terminate();
?>
