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

$matricula_edit = NULL; // Initialize page object first

class cmatricula_edit extends cmatricula {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'matricula';

	// Page object name
	var $PageObjName = 'matricula_edit';

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

		// Table object (matricula)
		if (!isset($GLOBALS["matricula"]) || get_class($GLOBALS["matricula"]) == "cmatricula") {
			$GLOBALS["matricula"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["matricula"];
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
			define("EW_TABLE_NAME", 'matricula', TRUE);

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
			$this->Page_Terminate("matriculalist.php");
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
		if (@$_GET["id_matricula"] <> "") {
			$this->id_matricula->setQueryStringValue($_GET["id_matricula"]);
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
		if ($this->id_matricula->CurrentValue == "")
			$this->Page_Terminate("matriculalist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("matriculalist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
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
		if (!$this->tipo_matri->FldIsDetailKey) {
			$this->tipo_matri->setFormValue($objForm->GetValue("x_tipo_matri"));
		}
		if (!$this->id_plan->FldIsDetailKey) {
			$this->id_plan->setFormValue($objForm->GetValue("x_id_plan"));
		}
		if (!$this->valor_matri->FldIsDetailKey) {
			$this->valor_matri->setFormValue($objForm->GetValue("x_valor_matri"));
		}
		if (!$this->valor_men_matri->FldIsDetailKey) {
			$this->valor_men_matri->setFormValue($objForm->GetValue("x_valor_men_matri"));
		}
		if (!$this->conv_matri->FldIsDetailKey) {
			$this->conv_matri->setFormValue($objForm->GetValue("x_conv_matri"));
		}
		if (!$this->id_empleado->FldIsDetailKey) {
			$this->id_empleado->setFormValue($objForm->GetValue("x_id_empleado"));
		}
		if (!$this->bol_matri->FldIsDetailKey) {
			$this->bol_matri->setFormValue($objForm->GetValue("x_bol_matri"));
		}
		if (!$this->cuenta_matri->FldIsDetailKey) {
			$this->cuenta_matri->setFormValue($objForm->GetValue("x_cuenta_matri"));
		}
		if (!$this->termino1_matri->FldIsDetailKey) {
			$this->termino1_matri->setFormValue($objForm->GetValue("x_termino1_matri"));
		}
		if (!$this->termino2_matri->FldIsDetailKey) {
			$this->termino2_matri->setFormValue($objForm->GetValue("x_termino2_matri"));
		}
		if (!$this->termino3_matri->FldIsDetailKey) {
			$this->termino3_matri->setFormValue($objForm->GetValue("x_termino3_matri"));
		}
		if (!$this->pag_card_matri->FldIsDetailKey) {
			$this->pag_card_matri->setFormValue($objForm->GetValue("x_pag_card_matri"));
		}
		if (!$this->tipo_card_matri->FldIsDetailKey) {
			$this->tipo_card_matri->setFormValue($objForm->GetValue("x_tipo_card_matri"));
		}
		if (!$this->num_card_matri->FldIsDetailKey) {
			$this->num_card_matri->setFormValue($objForm->GetValue("x_num_card_matri"));
		}
		if (!$this->venc_card_matri->FldIsDetailKey) {
			$this->venc_card_matri->setFormValue($objForm->GetValue("x_venc_card_matri"));
		}
		if (!$this->doc1_matri->FldIsDetailKey) {
			$this->doc1_matri->setFormValue($objForm->GetValue("x_doc1_matri"));
		}
		if (!$this->doc2_matri->FldIsDetailKey) {
			$this->doc2_matri->setFormValue($objForm->GetValue("x_doc2_matri"));
		}
		if (!$this->doc3_matri->FldIsDetailKey) {
			$this->doc3_matri->setFormValue($objForm->GetValue("x_doc3_matri"));
		}
		if (!$this->doc4_matri->FldIsDetailKey) {
			$this->doc4_matri->setFormValue($objForm->GetValue("x_doc4_matri"));
		}
		if (!$this->id_matricula->FldIsDetailKey)
			$this->id_matricula->setFormValue($objForm->GetValue("x_id_matricula"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_matricula->CurrentValue = $this->id_matricula->FormValue;
		$this->id_afiliado->CurrentValue = $this->id_afiliado->FormValue;
		$this->tipo_matri->CurrentValue = $this->tipo_matri->FormValue;
		$this->id_plan->CurrentValue = $this->id_plan->FormValue;
		$this->valor_matri->CurrentValue = $this->valor_matri->FormValue;
		$this->valor_men_matri->CurrentValue = $this->valor_men_matri->FormValue;
		$this->conv_matri->CurrentValue = $this->conv_matri->FormValue;
		$this->id_empleado->CurrentValue = $this->id_empleado->FormValue;
		$this->bol_matri->CurrentValue = $this->bol_matri->FormValue;
		$this->cuenta_matri->CurrentValue = $this->cuenta_matri->FormValue;
		$this->termino1_matri->CurrentValue = $this->termino1_matri->FormValue;
		$this->termino2_matri->CurrentValue = $this->termino2_matri->FormValue;
		$this->termino3_matri->CurrentValue = $this->termino3_matri->FormValue;
		$this->pag_card_matri->CurrentValue = $this->pag_card_matri->FormValue;
		$this->tipo_card_matri->CurrentValue = $this->tipo_card_matri->FormValue;
		$this->num_card_matri->CurrentValue = $this->num_card_matri->FormValue;
		$this->venc_card_matri->CurrentValue = $this->venc_card_matri->FormValue;
		$this->doc1_matri->CurrentValue = $this->doc1_matri->FormValue;
		$this->doc2_matri->CurrentValue = $this->doc2_matri->FormValue;
		$this->doc3_matri->CurrentValue = $this->doc3_matri->FormValue;
		$this->doc4_matri->CurrentValue = $this->doc4_matri->FormValue;
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

			// bol_matri
			$this->bol_matri->LinkCustomAttributes = "";
			$this->bol_matri->HrefValue = "";
			$this->bol_matri->TooltipValue = "";

			// cuenta_matri
			$this->cuenta_matri->LinkCustomAttributes = "";
			$this->cuenta_matri->HrefValue = "";
			$this->cuenta_matri->TooltipValue = "";

			// termino1_matri
			$this->termino1_matri->LinkCustomAttributes = "";
			$this->termino1_matri->HrefValue = "";
			$this->termino1_matri->TooltipValue = "";

			// termino2_matri
			$this->termino2_matri->LinkCustomAttributes = "";
			$this->termino2_matri->HrefValue = "";
			$this->termino2_matri->TooltipValue = "";

			// termino3_matri
			$this->termino3_matri->LinkCustomAttributes = "";
			$this->termino3_matri->HrefValue = "";
			$this->termino3_matri->TooltipValue = "";

			// pag_card_matri
			$this->pag_card_matri->LinkCustomAttributes = "";
			$this->pag_card_matri->HrefValue = "";
			$this->pag_card_matri->TooltipValue = "";

			// tipo_card_matri
			$this->tipo_card_matri->LinkCustomAttributes = "";
			$this->tipo_card_matri->HrefValue = "";
			$this->tipo_card_matri->TooltipValue = "";

			// num_card_matri
			$this->num_card_matri->LinkCustomAttributes = "";
			$this->num_card_matri->HrefValue = "";
			$this->num_card_matri->TooltipValue = "";

			// venc_card_matri
			$this->venc_card_matri->LinkCustomAttributes = "";
			$this->venc_card_matri->HrefValue = "";
			$this->venc_card_matri->TooltipValue = "";

			// doc1_matri
			$this->doc1_matri->LinkCustomAttributes = "";
			$this->doc1_matri->HrefValue = "";
			$this->doc1_matri->TooltipValue = "";

			// doc2_matri
			$this->doc2_matri->LinkCustomAttributes = "";
			$this->doc2_matri->HrefValue = "";
			$this->doc2_matri->TooltipValue = "";

			// doc3_matri
			$this->doc3_matri->LinkCustomAttributes = "";
			$this->doc3_matri->HrefValue = "";
			$this->doc3_matri->TooltipValue = "";

			// doc4_matri
			$this->doc4_matri->LinkCustomAttributes = "";
			$this->doc4_matri->HrefValue = "";
			$this->doc4_matri->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_afiliado
			$this->id_afiliado->EditCustomAttributes = "";
			if ($this->id_afiliado->getSessionValue() <> "") {
				$this->id_afiliado->CurrentValue = $this->id_afiliado->getSessionValue();
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
			if (strval($this->valor_matri->EditValue) <> "" && is_numeric($this->valor_matri->EditValue)) $this->valor_matri->EditValue = ew_FormatNumber($this->valor_matri->EditValue, -2, 0, 0, -2);

			// valor_men_matri
			$this->valor_men_matri->EditCustomAttributes = "";
			$this->valor_men_matri->EditValue = ew_HtmlEncode($this->valor_men_matri->CurrentValue);
			$this->valor_men_matri->PlaceHolder = ew_RemoveHtml($this->valor_men_matri->FldCaption());
			if (strval($this->valor_men_matri->EditValue) <> "" && is_numeric($this->valor_men_matri->EditValue)) $this->valor_men_matri->EditValue = ew_FormatNumber($this->valor_men_matri->EditValue, -2, -2, -2, -2);

			// conv_matri
			$this->conv_matri->EditCustomAttributes = "";
			$this->conv_matri->EditValue = ew_HtmlEncode($this->conv_matri->CurrentValue);
			$this->conv_matri->PlaceHolder = ew_RemoveHtml($this->conv_matri->FldCaption());

			// id_empleado
			$this->id_empleado->EditCustomAttributes = "";
			$this->id_empleado->CurrentValue = CurrentUserID();

			// bol_matri
			$this->bol_matri->EditCustomAttributes = "";
			$this->bol_matri->EditValue = ew_HtmlEncode($this->bol_matri->CurrentValue);
			$this->bol_matri->PlaceHolder = ew_RemoveHtml($this->bol_matri->FldCaption());

			// cuenta_matri
			$this->cuenta_matri->EditCustomAttributes = "";
			$this->cuenta_matri->EditValue = ew_HtmlEncode($this->cuenta_matri->CurrentValue);
			$this->cuenta_matri->PlaceHolder = ew_RemoveHtml($this->cuenta_matri->FldCaption());

			// termino1_matri
			$this->termino1_matri->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->termino1_matri->FldTagValue(1), $this->termino1_matri->FldTagCaption(1) <> "" ? $this->termino1_matri->FldTagCaption(1) : $this->termino1_matri->FldTagValue(1));
			$this->termino1_matri->EditValue = $arwrk;

			// termino2_matri
			$this->termino2_matri->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->termino2_matri->FldTagValue(1), $this->termino2_matri->FldTagCaption(1) <> "" ? $this->termino2_matri->FldTagCaption(1) : $this->termino2_matri->FldTagValue(1));
			$this->termino2_matri->EditValue = $arwrk;

			// termino3_matri
			$this->termino3_matri->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->termino3_matri->FldTagValue(1), $this->termino3_matri->FldTagCaption(1) <> "" ? $this->termino3_matri->FldTagCaption(1) : $this->termino3_matri->FldTagValue(1));
			$this->termino3_matri->EditValue = $arwrk;

			// pag_card_matri
			$this->pag_card_matri->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->pag_card_matri->FldTagValue(1), $this->pag_card_matri->FldTagCaption(1) <> "" ? $this->pag_card_matri->FldTagCaption(1) : $this->pag_card_matri->FldTagValue(1));
			$arwrk[] = array($this->pag_card_matri->FldTagValue(2), $this->pag_card_matri->FldTagCaption(2) <> "" ? $this->pag_card_matri->FldTagCaption(2) : $this->pag_card_matri->FldTagValue(2));
			$this->pag_card_matri->EditValue = $arwrk;

			// tipo_card_matri
			$this->tipo_card_matri->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->tipo_card_matri->FldTagValue(1), $this->tipo_card_matri->FldTagCaption(1) <> "" ? $this->tipo_card_matri->FldTagCaption(1) : $this->tipo_card_matri->FldTagValue(1));
			$arwrk[] = array($this->tipo_card_matri->FldTagValue(2), $this->tipo_card_matri->FldTagCaption(2) <> "" ? $this->tipo_card_matri->FldTagCaption(2) : $this->tipo_card_matri->FldTagValue(2));
			$arwrk[] = array($this->tipo_card_matri->FldTagValue(3), $this->tipo_card_matri->FldTagCaption(3) <> "" ? $this->tipo_card_matri->FldTagCaption(3) : $this->tipo_card_matri->FldTagValue(3));
			$arwrk[] = array($this->tipo_card_matri->FldTagValue(4), $this->tipo_card_matri->FldTagCaption(4) <> "" ? $this->tipo_card_matri->FldTagCaption(4) : $this->tipo_card_matri->FldTagValue(4));
			$this->tipo_card_matri->EditValue = $arwrk;

			// num_card_matri
			$this->num_card_matri->EditCustomAttributes = "";
			$this->num_card_matri->EditValue = ew_HtmlEncode($this->num_card_matri->CurrentValue);
			$this->num_card_matri->PlaceHolder = ew_RemoveHtml($this->num_card_matri->FldCaption());

			// venc_card_matri
			$this->venc_card_matri->EditCustomAttributes = "placeholder='mm/aaaa'";
			$this->venc_card_matri->EditValue = ew_HtmlEncode($this->venc_card_matri->CurrentValue);
			$this->venc_card_matri->PlaceHolder = ew_RemoveHtml($this->venc_card_matri->FldCaption());

			// doc1_matri
			$this->doc1_matri->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->doc1_matri->FldTagValue(1), $this->doc1_matri->FldTagCaption(1) <> "" ? $this->doc1_matri->FldTagCaption(1) : $this->doc1_matri->FldTagValue(1));
			$this->doc1_matri->EditValue = $arwrk;

			// doc2_matri
			$this->doc2_matri->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->doc2_matri->FldTagValue(1), $this->doc2_matri->FldTagCaption(1) <> "" ? $this->doc2_matri->FldTagCaption(1) : $this->doc2_matri->FldTagValue(1));
			$this->doc2_matri->EditValue = $arwrk;

			// doc3_matri
			$this->doc3_matri->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->doc3_matri->FldTagValue(1), $this->doc3_matri->FldTagCaption(1) <> "" ? $this->doc3_matri->FldTagCaption(1) : $this->doc3_matri->FldTagValue(1));
			$this->doc3_matri->EditValue = $arwrk;

			// doc4_matri
			$this->doc4_matri->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->doc4_matri->FldTagValue(1), $this->doc4_matri->FldTagCaption(1) <> "" ? $this->doc4_matri->FldTagCaption(1) : $this->doc4_matri->FldTagValue(1));
			$this->doc4_matri->EditValue = $arwrk;

			// Edit refer script
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

			// bol_matri
			$this->bol_matri->HrefValue = "";

			// cuenta_matri
			$this->cuenta_matri->HrefValue = "";

			// termino1_matri
			$this->termino1_matri->HrefValue = "";

			// termino2_matri
			$this->termino2_matri->HrefValue = "";

			// termino3_matri
			$this->termino3_matri->HrefValue = "";

			// pag_card_matri
			$this->pag_card_matri->HrefValue = "";

			// tipo_card_matri
			$this->tipo_card_matri->HrefValue = "";

			// num_card_matri
			$this->num_card_matri->HrefValue = "";

			// venc_card_matri
			$this->venc_card_matri->HrefValue = "";

			// doc1_matri
			$this->doc1_matri->HrefValue = "";

			// doc2_matri
			$this->doc2_matri->HrefValue = "";

			// doc3_matri
			$this->doc3_matri->HrefValue = "";

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
		if (!$this->id_plan->FldIsDetailKey && !is_null($this->id_plan->FormValue) && $this->id_plan->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id_plan->FldCaption());
		}
		if (!ew_CheckNumber($this->valor_matri->FormValue)) {
			ew_AddMessage($gsFormError, $this->valor_matri->FldErrMsg());
		}
		if (!ew_CheckNumber($this->valor_men_matri->FormValue)) {
			ew_AddMessage($gsFormError, $this->valor_men_matri->FldErrMsg());
		}
		if (!ew_CheckEmail($this->bol_matri->FormValue)) {
			ew_AddMessage($gsFormError, $this->bol_matri->FldErrMsg());
		}
		if (!ew_CheckCreditCard($this->num_card_matri->FormValue)) {
			ew_AddMessage($gsFormError, $this->num_card_matri->FldErrMsg());
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

			// bol_matri
			$this->bol_matri->SetDbValueDef($rsnew, $this->bol_matri->CurrentValue, NULL, $this->bol_matri->ReadOnly);

			// cuenta_matri
			$this->cuenta_matri->SetDbValueDef($rsnew, $this->cuenta_matri->CurrentValue, NULL, $this->cuenta_matri->ReadOnly);

			// termino1_matri
			$this->termino1_matri->SetDbValueDef($rsnew, $this->termino1_matri->CurrentValue, NULL, $this->termino1_matri->ReadOnly);

			// termino2_matri
			$this->termino2_matri->SetDbValueDef($rsnew, $this->termino2_matri->CurrentValue, NULL, $this->termino2_matri->ReadOnly);

			// termino3_matri
			$this->termino3_matri->SetDbValueDef($rsnew, $this->termino3_matri->CurrentValue, NULL, $this->termino3_matri->ReadOnly);

			// pag_card_matri
			$this->pag_card_matri->SetDbValueDef($rsnew, $this->pag_card_matri->CurrentValue, NULL, $this->pag_card_matri->ReadOnly);

			// tipo_card_matri
			$this->tipo_card_matri->SetDbValueDef($rsnew, $this->tipo_card_matri->CurrentValue, NULL, $this->tipo_card_matri->ReadOnly);

			// num_card_matri
			$this->num_card_matri->SetDbValueDef($rsnew, $this->num_card_matri->CurrentValue, NULL, $this->num_card_matri->ReadOnly);

			// venc_card_matri
			$this->venc_card_matri->SetDbValueDef($rsnew, $this->venc_card_matri->CurrentValue, NULL, $this->venc_card_matri->ReadOnly);

			// doc1_matri
			$this->doc1_matri->SetDbValueDef($rsnew, $this->doc1_matri->CurrentValue, NULL, $this->doc1_matri->ReadOnly);

			// doc2_matri
			$this->doc2_matri->SetDbValueDef($rsnew, $this->doc2_matri->CurrentValue, NULL, $this->doc2_matri->ReadOnly);

			// doc3_matri
			$this->doc3_matri->SetDbValueDef($rsnew, $this->doc3_matri->CurrentValue, NULL, $this->doc3_matri->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "matriculalist.php", $this->TableVar, TRUE);
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
if (!isset($matricula_edit)) $matricula_edit = new cmatricula_edit();

// Page init
$matricula_edit->Page_Init();

// Page main
$matricula_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$matricula_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var matricula_edit = new ew_Page("matricula_edit");
matricula_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = matricula_edit.PageID; // For backward compatibility

// Form object
var fmatriculaedit = new ew_Form("fmatriculaedit");

// Validate form
fmatriculaedit.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($matricula->id_afiliado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id_afiliado");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($matricula->id_afiliado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_plan");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($matricula->id_plan->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_valor_matri");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($matricula->valor_matri->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_valor_men_matri");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($matricula->valor_men_matri->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bol_matri");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($matricula->bol_matri->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_num_card_matri");
			if (elm && !ew_CheckCreditCard(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($matricula->num_card_matri->FldErrMsg()) ?>");

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
fmatriculaedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmatriculaedit.ValidateRequired = true;
<?php } else { ?>
fmatriculaedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmatriculaedit.Lists["x_id_afiliado"] = {"LinkField":"x_id_afiliado","Ajax":true,"AutoFill":false,"DisplayFields":["x_dociden_afiliado","x_apell_afiliado","x_nomb_afiliado",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmatriculaedit.Lists["x_id_plan"] = {"LinkField":"x_id_plan","Ajax":null,"AutoFill":false,"DisplayFields":["x_tipo_plan","x_time_plan","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $matricula_edit->ShowPageHeader(); ?>
<?php
$matricula_edit->ShowMessage();
?>
<form name="fmatriculaedit" id="fmatriculaedit" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="matricula">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewGrid"><tr><td>
<table id="tbl_matriculaedit" class="table table-bordered table-striped">
<?php if ($matricula->id_afiliado->Visible) { // id_afiliado ?>
	<tr id="r_id_afiliado">
		<td><span id="elh_matricula_id_afiliado"><?php echo $matricula->id_afiliado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $matricula->id_afiliado->CellAttributes() ?>>
<?php if ($matricula->id_afiliado->getSessionValue() <> "") { ?>
<span<?php echo $matricula->id_afiliado->ViewAttributes() ?>>
<?php echo $matricula->id_afiliado->ViewValue ?></span>
<input type="hidden" id="x_id_afiliado" name="x_id_afiliado" value="<?php echo ew_HtmlEncode($matricula->id_afiliado->CurrentValue) ?>">
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
<input type="hidden" data-field="x_id_afiliado" name="x_id_afiliado" id="x_id_afiliado" value="<?php echo ew_HtmlEncode($matricula->id_afiliado->CurrentValue) ?>"<?php echo $wrkonchange ?>>
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
var oas = new ew_AutoSuggest("x_id_afiliado", fmatriculaedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_id_afiliado") + ar[i] : "";
	return dv;
}
fmatriculaedit.AutoSuggests["x_id_afiliado"] = oas;
</script>
<?php } ?>
<?php echo $matricula->id_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->tipo_matri->Visible) { // tipo_matri ?>
	<tr id="r_tipo_matri">
		<td><span id="elh_matricula_tipo_matri"><?php echo $matricula->tipo_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->tipo_matri->CellAttributes() ?>>
<span id="el_matricula_tipo_matri" class="control-group">
<div id="tp_x_tipo_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_tipo_matri" id="x_tipo_matri" value="{value}"<?php echo $matricula->tipo_matri->EditAttributes() ?>></div>
<div id="dsl_x_tipo_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->tipo_matri->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($matricula->tipo_matri->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
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
<?php echo $matricula->tipo_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->id_plan->Visible) { // id_plan ?>
	<tr id="r_id_plan">
		<td><span id="elh_matricula_id_plan"><?php echo $matricula->id_plan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $matricula->id_plan->CellAttributes() ?>>
<span id="el_matricula_id_plan" class="control-group">
<select data-field="x_id_plan" id="x_id_plan" name="x_id_plan"<?php echo $matricula->id_plan->EditAttributes() ?>>
<?php
if (is_array($matricula->id_plan->EditValue)) {
	$arwrk = $matricula->id_plan->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($matricula->id_plan->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fmatriculaedit.Lists["x_id_plan"].Options = <?php echo (is_array($matricula->id_plan->EditValue)) ? ew_ArrayToJson($matricula->id_plan->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $matricula->id_plan->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->valor_matri->Visible) { // valor_matri ?>
	<tr id="r_valor_matri">
		<td><span id="elh_matricula_valor_matri"><?php echo $matricula->valor_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->valor_matri->CellAttributes() ?>>
<span id="el_matricula_valor_matri" class="control-group">
<input type="text" data-field="x_valor_matri" name="x_valor_matri" id="x_valor_matri" size="30" placeholder="<?php echo ew_HtmlEncode($matricula->valor_matri->PlaceHolder) ?>" value="<?php echo $matricula->valor_matri->EditValue ?>"<?php echo $matricula->valor_matri->EditAttributes() ?>>
</span>
<?php echo $matricula->valor_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->valor_men_matri->Visible) { // valor_men_matri ?>
	<tr id="r_valor_men_matri">
		<td><span id="elh_matricula_valor_men_matri"><?php echo $matricula->valor_men_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->valor_men_matri->CellAttributes() ?>>
<span id="el_matricula_valor_men_matri" class="control-group">
<input type="text" data-field="x_valor_men_matri" name="x_valor_men_matri" id="x_valor_men_matri" size="30" placeholder="<?php echo ew_HtmlEncode($matricula->valor_men_matri->PlaceHolder) ?>" value="<?php echo $matricula->valor_men_matri->EditValue ?>"<?php echo $matricula->valor_men_matri->EditAttributes() ?>>
</span>
<?php echo $matricula->valor_men_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->conv_matri->Visible) { // conv_matri ?>
	<tr id="r_conv_matri">
		<td><span id="elh_matricula_conv_matri"><?php echo $matricula->conv_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->conv_matri->CellAttributes() ?>>
<span id="el_matricula_conv_matri" class="control-group">
<input type="text" data-field="x_conv_matri" name="x_conv_matri" id="x_conv_matri" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($matricula->conv_matri->PlaceHolder) ?>" value="<?php echo $matricula->conv_matri->EditValue ?>"<?php echo $matricula->conv_matri->EditAttributes() ?>>
</span>
<?php echo $matricula->conv_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->bol_matri->Visible) { // bol_matri ?>
	<tr id="r_bol_matri">
		<td><span id="elh_matricula_bol_matri"><?php echo $matricula->bol_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->bol_matri->CellAttributes() ?>>
<span id="el_matricula_bol_matri" class="control-group">
<input type="text" data-field="x_bol_matri" name="x_bol_matri" id="x_bol_matri" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($matricula->bol_matri->PlaceHolder) ?>" value="<?php echo $matricula->bol_matri->EditValue ?>"<?php echo $matricula->bol_matri->EditAttributes() ?>>
</span>
<?php echo $matricula->bol_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->cuenta_matri->Visible) { // cuenta_matri ?>
	<tr id="r_cuenta_matri">
		<td><span id="elh_matricula_cuenta_matri"><?php echo $matricula->cuenta_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->cuenta_matri->CellAttributes() ?>>
<span id="el_matricula_cuenta_matri" class="control-group">
<input type="text" data-field="x_cuenta_matri" name="x_cuenta_matri" id="x_cuenta_matri" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($matricula->cuenta_matri->PlaceHolder) ?>" value="<?php echo $matricula->cuenta_matri->EditValue ?>"<?php echo $matricula->cuenta_matri->EditAttributes() ?>>
</span>
<?php echo $matricula->cuenta_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->termino1_matri->Visible) { // termino1_matri ?>
	<tr id="r_termino1_matri">
		<td><span id="elh_matricula_termino1_matri"><?php echo $matricula->termino1_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->termino1_matri->CellAttributes() ?>>
<span id="el_matricula_termino1_matri" class="control-group">
<div id="tp_x_termino1_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_termino1_matri[]" id="x_termino1_matri[]" value="{value}"<?php echo $matricula->termino1_matri->EditAttributes() ?>></div>
<div id="dsl_x_termino1_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->termino1_matri->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($matricula->termino1_matri->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox"><input type="checkbox" data-field="x_termino1_matri" name="x_termino1_matri[]" id="x_termino1_matri_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $matricula->termino1_matri->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $matricula->termino1_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->termino2_matri->Visible) { // termino2_matri ?>
	<tr id="r_termino2_matri">
		<td><span id="elh_matricula_termino2_matri"><?php echo $matricula->termino2_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->termino2_matri->CellAttributes() ?>>
<span id="el_matricula_termino2_matri" class="control-group">
<div id="tp_x_termino2_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_termino2_matri[]" id="x_termino2_matri[]" value="{value}"<?php echo $matricula->termino2_matri->EditAttributes() ?>></div>
<div id="dsl_x_termino2_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->termino2_matri->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($matricula->termino2_matri->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox"><input type="checkbox" data-field="x_termino2_matri" name="x_termino2_matri[]" id="x_termino2_matri_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $matricula->termino2_matri->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $matricula->termino2_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->termino3_matri->Visible) { // termino3_matri ?>
	<tr id="r_termino3_matri">
		<td><span id="elh_matricula_termino3_matri"><?php echo $matricula->termino3_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->termino3_matri->CellAttributes() ?>>
<span id="el_matricula_termino3_matri" class="control-group">
<div id="tp_x_termino3_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_termino3_matri[]" id="x_termino3_matri[]" value="{value}"<?php echo $matricula->termino3_matri->EditAttributes() ?>></div>
<div id="dsl_x_termino3_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->termino3_matri->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($matricula->termino3_matri->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox"><input type="checkbox" data-field="x_termino3_matri" name="x_termino3_matri[]" id="x_termino3_matri_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $matricula->termino3_matri->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $matricula->termino3_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->pag_card_matri->Visible) { // pag_card_matri ?>
	<tr id="r_pag_card_matri">
		<td><span id="elh_matricula_pag_card_matri"><?php echo $matricula->pag_card_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->pag_card_matri->CellAttributes() ?>>
<span id="el_matricula_pag_card_matri" class="control-group">
<div id="tp_x_pag_card_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_pag_card_matri" id="x_pag_card_matri" value="{value}"<?php echo $matricula->pag_card_matri->EditAttributes() ?>></div>
<div id="dsl_x_pag_card_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->pag_card_matri->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($matricula->pag_card_matri->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_pag_card_matri" name="x_pag_card_matri" id="x_pag_card_matri_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $matricula->pag_card_matri->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $matricula->pag_card_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->tipo_card_matri->Visible) { // tipo_card_matri ?>
	<tr id="r_tipo_card_matri">
		<td><span id="elh_matricula_tipo_card_matri"><?php echo $matricula->tipo_card_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->tipo_card_matri->CellAttributes() ?>>
<span id="el_matricula_tipo_card_matri" class="control-group">
<div id="tp_x_tipo_card_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_tipo_card_matri" id="x_tipo_card_matri" value="{value}"<?php echo $matricula->tipo_card_matri->EditAttributes() ?>></div>
<div id="dsl_x_tipo_card_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->tipo_card_matri->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($matricula->tipo_card_matri->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_tipo_card_matri" name="x_tipo_card_matri" id="x_tipo_card_matri_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $matricula->tipo_card_matri->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $matricula->tipo_card_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->num_card_matri->Visible) { // num_card_matri ?>
	<tr id="r_num_card_matri">
		<td><span id="elh_matricula_num_card_matri"><?php echo $matricula->num_card_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->num_card_matri->CellAttributes() ?>>
<span id="el_matricula_num_card_matri" class="control-group">
<input type="text" data-field="x_num_card_matri" name="x_num_card_matri" id="x_num_card_matri" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($matricula->num_card_matri->PlaceHolder) ?>" value="<?php echo $matricula->num_card_matri->EditValue ?>"<?php echo $matricula->num_card_matri->EditAttributes() ?>>
</span>
<?php echo $matricula->num_card_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->venc_card_matri->Visible) { // venc_card_matri ?>
	<tr id="r_venc_card_matri">
		<td><span id="elh_matricula_venc_card_matri"><?php echo $matricula->venc_card_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->venc_card_matri->CellAttributes() ?>>
<span id="el_matricula_venc_card_matri" class="control-group">
<input type="text" data-field="x_venc_card_matri" name="x_venc_card_matri" id="x_venc_card_matri" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($matricula->venc_card_matri->PlaceHolder) ?>" value="<?php echo $matricula->venc_card_matri->EditValue ?>"<?php echo $matricula->venc_card_matri->EditAttributes() ?>>
</span>
<?php echo $matricula->venc_card_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->doc1_matri->Visible) { // doc1_matri ?>
	<tr id="r_doc1_matri">
		<td><span id="elh_matricula_doc1_matri"><?php echo $matricula->doc1_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->doc1_matri->CellAttributes() ?>>
<span id="el_matricula_doc1_matri" class="control-group">
<div id="tp_x_doc1_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_doc1_matri[]" id="x_doc1_matri[]" value="{value}"<?php echo $matricula->doc1_matri->EditAttributes() ?>></div>
<div id="dsl_x_doc1_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->doc1_matri->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($matricula->doc1_matri->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox"><input type="checkbox" data-field="x_doc1_matri" name="x_doc1_matri[]" id="x_doc1_matri_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $matricula->doc1_matri->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $matricula->doc1_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->doc2_matri->Visible) { // doc2_matri ?>
	<tr id="r_doc2_matri">
		<td><span id="elh_matricula_doc2_matri"><?php echo $matricula->doc2_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->doc2_matri->CellAttributes() ?>>
<span id="el_matricula_doc2_matri" class="control-group">
<div id="tp_x_doc2_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_doc2_matri[]" id="x_doc2_matri[]" value="{value}"<?php echo $matricula->doc2_matri->EditAttributes() ?>></div>
<div id="dsl_x_doc2_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->doc2_matri->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($matricula->doc2_matri->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox"><input type="checkbox" data-field="x_doc2_matri" name="x_doc2_matri[]" id="x_doc2_matri_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $matricula->doc2_matri->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $matricula->doc2_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->doc3_matri->Visible) { // doc3_matri ?>
	<tr id="r_doc3_matri">
		<td><span id="elh_matricula_doc3_matri"><?php echo $matricula->doc3_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->doc3_matri->CellAttributes() ?>>
<span id="el_matricula_doc3_matri" class="control-group">
<div id="tp_x_doc3_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_doc3_matri[]" id="x_doc3_matri[]" value="{value}"<?php echo $matricula->doc3_matri->EditAttributes() ?>></div>
<div id="dsl_x_doc3_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->doc3_matri->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($matricula->doc3_matri->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox"><input type="checkbox" data-field="x_doc3_matri" name="x_doc3_matri[]" id="x_doc3_matri_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $matricula->doc3_matri->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $matricula->doc3_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($matricula->doc4_matri->Visible) { // doc4_matri ?>
	<tr id="r_doc4_matri">
		<td><span id="elh_matricula_doc4_matri"><?php echo $matricula->doc4_matri->FldCaption() ?></span></td>
		<td<?php echo $matricula->doc4_matri->CellAttributes() ?>>
<span id="el_matricula_doc4_matri" class="control-group">
<div id="tp_x_doc4_matri" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_doc4_matri[]" id="x_doc4_matri[]" value="{value}"<?php echo $matricula->doc4_matri->EditAttributes() ?>></div>
<div id="dsl_x_doc4_matri" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $matricula->doc4_matri->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($matricula->doc4_matri->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox"><input type="checkbox" data-field="x_doc4_matri" name="x_doc4_matri[]" id="x_doc4_matri_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $matricula->doc4_matri->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $matricula->doc4_matri->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<span id="el_matricula_id_empleado" class="control-group">
<input type="hidden" data-field="x_id_empleado" name="x_id_empleado" id="x_id_empleado" value="<?php echo ew_HtmlEncode($matricula->id_empleado->CurrentValue) ?>">
</span>
<input type="hidden" data-field="x_id_matricula" name="x_id_matricula" id="x_id_matricula" value="<?php echo ew_HtmlEncode($matricula->id_matricula->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fmatriculaedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$matricula_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$matricula_edit->Page_Terminate();
?>
