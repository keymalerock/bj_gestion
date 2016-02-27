<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "afiliadoinfo.php" ?>
<?php include_once "v_usuariosinfo.php" ?>
<?php include_once "historialgridcls.php" ?>
<?php include_once "matriculagridcls.php" ?>
<?php include_once "representantesgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$afiliado_edit = NULL; // Initialize page object first

class cafiliado_edit extends cafiliado {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'afiliado';

	// Page object name
	var $PageObjName = 'afiliado_edit';

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

		// Table object (afiliado)
		if (!isset($GLOBALS["afiliado"]) || get_class($GLOBALS["afiliado"]) == "cafiliado") {
			$GLOBALS["afiliado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["afiliado"];
		}

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'afiliado', TRUE);

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
			$this->Page_Terminate("afiliadolist.php");
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
		if (@$_GET["id_afiliado"] <> "") {
			$this->id_afiliado->setQueryStringValue($_GET["id_afiliado"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id_afiliado->CurrentValue == "")
			$this->Page_Terminate("afiliadolist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("afiliadolist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = "afiliadolist.php";
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		$this->foto_afiliado->Upload->Index = $objForm->Index;
		if ($this->foto_afiliado->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->foto_afiliado->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->foto_afiliado->CurrentValue = $this->foto_afiliado->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->dociden_afiliado->FldIsDetailKey) {
			$this->dociden_afiliado->setFormValue($objForm->GetValue("x_dociden_afiliado"));
		}
		if (!$this->apell_afiliado->FldIsDetailKey) {
			$this->apell_afiliado->setFormValue($objForm->GetValue("x_apell_afiliado"));
		}
		if (!$this->nomb_afiliado->FldIsDetailKey) {
			$this->nomb_afiliado->setFormValue($objForm->GetValue("x_nomb_afiliado"));
		}
		if (!$this->direcc_afiliado->FldIsDetailKey) {
			$this->direcc_afiliado->setFormValue($objForm->GetValue("x_direcc_afiliado"));
		}
		if (!$this->email_afiliado->FldIsDetailKey) {
			$this->email_afiliado->setFormValue($objForm->GetValue("x_email_afiliado"));
		}
		if (!$this->cel_afiliado->FldIsDetailKey) {
			$this->cel_afiliado->setFormValue($objForm->GetValue("x_cel_afiliado"));
		}
		if (!$this->genero_afiliado->FldIsDetailKey) {
			$this->genero_afiliado->setFormValue($objForm->GetValue("x_genero_afiliado"));
		}
		if (!$this->fe_afiliado->FldIsDetailKey) {
			$this->fe_afiliado->setFormValue($objForm->GetValue("x_fe_afiliado"));
			$this->fe_afiliado->CurrentValue = ew_UnFormatDateTime($this->fe_afiliado->CurrentValue, 5);
		}
		if (!$this->telemerg_afiliado->FldIsDetailKey) {
			$this->telemerg_afiliado->setFormValue($objForm->GetValue("x_telemerg_afiliado"));
		}
		if (!$this->talla_afiliado->FldIsDetailKey) {
			$this->talla_afiliado->setFormValue($objForm->GetValue("x_talla_afiliado"));
		}
		if (!$this->peso_afiliado->FldIsDetailKey) {
			$this->peso_afiliado->setFormValue($objForm->GetValue("x_peso_afiliado"));
		}
		if (!$this->altu_afiliado->FldIsDetailKey) {
			$this->altu_afiliado->setFormValue($objForm->GetValue("x_altu_afiliado"));
		}
		if (!$this->localresdi_afiliado->FldIsDetailKey) {
			$this->localresdi_afiliado->setFormValue($objForm->GetValue("x_localresdi_afiliado"));
		}
		if (!$this->telf_fijo_afiliado->FldIsDetailKey) {
			$this->telf_fijo_afiliado->setFormValue($objForm->GetValue("x_telf_fijo_afiliado"));
		}
		if (!$this->coleg_afiliado->FldIsDetailKey) {
			$this->coleg_afiliado->setFormValue($objForm->GetValue("x_coleg_afiliado"));
		}
		if (!$this->seguro_afiliado->FldIsDetailKey) {
			$this->seguro_afiliado->setFormValue($objForm->GetValue("x_seguro_afiliado"));
		}
		if (!$this->tiposangre_afiliado->FldIsDetailKey) {
			$this->tiposangre_afiliado->setFormValue($objForm->GetValue("x_tiposangre_afiliado"));
		}
		if (!$this->contacto_afiliado->FldIsDetailKey) {
			$this->contacto_afiliado->setFormValue($objForm->GetValue("x_contacto_afiliado"));
		}
		if (!$this->st_afiliado->FldIsDetailKey) {
			$this->st_afiliado->setFormValue($objForm->GetValue("x_st_afiliado"));
		}
		if (!$this->st_notificado->FldIsDetailKey) {
			$this->st_notificado->setFormValue($objForm->GetValue("x_st_notificado"));
		}
		if (!$this->id_afiliado->FldIsDetailKey)
			$this->id_afiliado->setFormValue($objForm->GetValue("x_id_afiliado"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_afiliado->CurrentValue = $this->id_afiliado->FormValue;
		$this->dociden_afiliado->CurrentValue = $this->dociden_afiliado->FormValue;
		$this->apell_afiliado->CurrentValue = $this->apell_afiliado->FormValue;
		$this->nomb_afiliado->CurrentValue = $this->nomb_afiliado->FormValue;
		$this->direcc_afiliado->CurrentValue = $this->direcc_afiliado->FormValue;
		$this->email_afiliado->CurrentValue = $this->email_afiliado->FormValue;
		$this->cel_afiliado->CurrentValue = $this->cel_afiliado->FormValue;
		$this->genero_afiliado->CurrentValue = $this->genero_afiliado->FormValue;
		$this->fe_afiliado->CurrentValue = $this->fe_afiliado->FormValue;
		$this->fe_afiliado->CurrentValue = ew_UnFormatDateTime($this->fe_afiliado->CurrentValue, 5);
		$this->telemerg_afiliado->CurrentValue = $this->telemerg_afiliado->FormValue;
		$this->talla_afiliado->CurrentValue = $this->talla_afiliado->FormValue;
		$this->peso_afiliado->CurrentValue = $this->peso_afiliado->FormValue;
		$this->altu_afiliado->CurrentValue = $this->altu_afiliado->FormValue;
		$this->localresdi_afiliado->CurrentValue = $this->localresdi_afiliado->FormValue;
		$this->telf_fijo_afiliado->CurrentValue = $this->telf_fijo_afiliado->FormValue;
		$this->coleg_afiliado->CurrentValue = $this->coleg_afiliado->FormValue;
		$this->seguro_afiliado->CurrentValue = $this->seguro_afiliado->FormValue;
		$this->tiposangre_afiliado->CurrentValue = $this->tiposangre_afiliado->FormValue;
		$this->contacto_afiliado->CurrentValue = $this->contacto_afiliado->FormValue;
		$this->st_afiliado->CurrentValue = $this->st_afiliado->FormValue;
		$this->st_notificado->CurrentValue = $this->st_notificado->FormValue;
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
		$this->dociden_afiliado->setDbValue($rs->fields('dociden_afiliado'));
		$this->apell_afiliado->setDbValue($rs->fields('apell_afiliado'));
		$this->nomb_afiliado->setDbValue($rs->fields('nomb_afiliado'));
		$this->direcc_afiliado->setDbValue($rs->fields('direcc_afiliado'));
		$this->email_afiliado->setDbValue($rs->fields('email_afiliado'));
		$this->cel_afiliado->setDbValue($rs->fields('cel_afiliado'));
		$this->genero_afiliado->setDbValue($rs->fields('genero_afiliado'));
		$this->fe_afiliado->setDbValue($rs->fields('fe_afiliado'));
		$this->telemerg_afiliado->setDbValue($rs->fields('telemerg_afiliado'));
		$this->talla_afiliado->setDbValue($rs->fields('talla_afiliado'));
		$this->peso_afiliado->setDbValue($rs->fields('peso_afiliado'));
		$this->altu_afiliado->setDbValue($rs->fields('altu_afiliado'));
		$this->localresdi_afiliado->setDbValue($rs->fields('localresdi_afiliado'));
		$this->telf_fijo_afiliado->setDbValue($rs->fields('telf_fijo_afiliado'));
		$this->coleg_afiliado->setDbValue($rs->fields('coleg_afiliado'));
		$this->seguro_afiliado->setDbValue($rs->fields('seguro_afiliado'));
		$this->tiposangre_afiliado->setDbValue($rs->fields('tiposangre_afiliado'));
		$this->contacto_afiliado->setDbValue($rs->fields('contacto_afiliado'));
		$this->st_afiliado->setDbValue($rs->fields('st_afiliado'));
		$this->foto_afiliado->Upload->DbValue = $rs->fields('foto_afiliado');
		$this->foto_afiliado->CurrentValue = $this->foto_afiliado->Upload->DbValue;
		$this->st_notificado->setDbValue($rs->fields('st_notificado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_afiliado->DbValue = $row['id_afiliado'];
		$this->dociden_afiliado->DbValue = $row['dociden_afiliado'];
		$this->apell_afiliado->DbValue = $row['apell_afiliado'];
		$this->nomb_afiliado->DbValue = $row['nomb_afiliado'];
		$this->direcc_afiliado->DbValue = $row['direcc_afiliado'];
		$this->email_afiliado->DbValue = $row['email_afiliado'];
		$this->cel_afiliado->DbValue = $row['cel_afiliado'];
		$this->genero_afiliado->DbValue = $row['genero_afiliado'];
		$this->fe_afiliado->DbValue = $row['fe_afiliado'];
		$this->telemerg_afiliado->DbValue = $row['telemerg_afiliado'];
		$this->talla_afiliado->DbValue = $row['talla_afiliado'];
		$this->peso_afiliado->DbValue = $row['peso_afiliado'];
		$this->altu_afiliado->DbValue = $row['altu_afiliado'];
		$this->localresdi_afiliado->DbValue = $row['localresdi_afiliado'];
		$this->telf_fijo_afiliado->DbValue = $row['telf_fijo_afiliado'];
		$this->coleg_afiliado->DbValue = $row['coleg_afiliado'];
		$this->seguro_afiliado->DbValue = $row['seguro_afiliado'];
		$this->tiposangre_afiliado->DbValue = $row['tiposangre_afiliado'];
		$this->contacto_afiliado->DbValue = $row['contacto_afiliado'];
		$this->st_afiliado->DbValue = $row['st_afiliado'];
		$this->foto_afiliado->Upload->DbValue = $row['foto_afiliado'];
		$this->st_notificado->DbValue = $row['st_notificado'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->altu_afiliado->FormValue == $this->altu_afiliado->CurrentValue && is_numeric(ew_StrToFloat($this->altu_afiliado->CurrentValue)))
			$this->altu_afiliado->CurrentValue = ew_StrToFloat($this->altu_afiliado->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_afiliado
		// dociden_afiliado
		// apell_afiliado
		// nomb_afiliado
		// direcc_afiliado
		// email_afiliado
		// cel_afiliado
		// genero_afiliado
		// fe_afiliado
		// telemerg_afiliado
		// talla_afiliado
		// peso_afiliado
		// altu_afiliado
		// localresdi_afiliado
		// telf_fijo_afiliado
		// coleg_afiliado
		// seguro_afiliado
		// tiposangre_afiliado
		// contacto_afiliado
		// st_afiliado
		// foto_afiliado
		// st_notificado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// dociden_afiliado
			$this->dociden_afiliado->ViewValue = $this->dociden_afiliado->CurrentValue;
			$this->dociden_afiliado->ViewCustomAttributes = "";

			// apell_afiliado
			$this->apell_afiliado->ViewValue = $this->apell_afiliado->CurrentValue;
			$this->apell_afiliado->ViewCustomAttributes = "";

			// nomb_afiliado
			$this->nomb_afiliado->ViewValue = $this->nomb_afiliado->CurrentValue;
			$this->nomb_afiliado->ViewCustomAttributes = "";

			// direcc_afiliado
			$this->direcc_afiliado->ViewValue = $this->direcc_afiliado->CurrentValue;
			$this->direcc_afiliado->ViewCustomAttributes = "";

			// email_afiliado
			$this->email_afiliado->ViewValue = $this->email_afiliado->CurrentValue;
			$this->email_afiliado->ViewCustomAttributes = "";

			// cel_afiliado
			$this->cel_afiliado->ViewValue = $this->cel_afiliado->CurrentValue;
			$this->cel_afiliado->ViewCustomAttributes = "";

			// genero_afiliado
			if (strval($this->genero_afiliado->CurrentValue) <> "") {
				switch ($this->genero_afiliado->CurrentValue) {
					case $this->genero_afiliado->FldTagValue(1):
						$this->genero_afiliado->ViewValue = $this->genero_afiliado->FldTagCaption(1) <> "" ? $this->genero_afiliado->FldTagCaption(1) : $this->genero_afiliado->CurrentValue;
						break;
					case $this->genero_afiliado->FldTagValue(2):
						$this->genero_afiliado->ViewValue = $this->genero_afiliado->FldTagCaption(2) <> "" ? $this->genero_afiliado->FldTagCaption(2) : $this->genero_afiliado->CurrentValue;
						break;
					default:
						$this->genero_afiliado->ViewValue = $this->genero_afiliado->CurrentValue;
				}
			} else {
				$this->genero_afiliado->ViewValue = NULL;
			}
			$this->genero_afiliado->ViewCustomAttributes = "";

			// fe_afiliado
			$this->fe_afiliado->ViewValue = $this->fe_afiliado->CurrentValue;
			$this->fe_afiliado->ViewValue = ew_FormatDateTime($this->fe_afiliado->ViewValue, 5);
			$this->fe_afiliado->ViewCustomAttributes = "";

			// telemerg_afiliado
			$this->telemerg_afiliado->ViewValue = $this->telemerg_afiliado->CurrentValue;
			$this->telemerg_afiliado->ViewCustomAttributes = "";

			// talla_afiliado
			$this->talla_afiliado->ViewValue = $this->talla_afiliado->CurrentValue;
			$this->talla_afiliado->ViewCustomAttributes = "";

			// peso_afiliado
			$this->peso_afiliado->ViewValue = $this->peso_afiliado->CurrentValue;
			$this->peso_afiliado->ViewCustomAttributes = "";

			// altu_afiliado
			$this->altu_afiliado->ViewValue = $this->altu_afiliado->CurrentValue;
			$this->altu_afiliado->ViewCustomAttributes = "";

			// localresdi_afiliado
			$this->localresdi_afiliado->ViewValue = $this->localresdi_afiliado->CurrentValue;
			$this->localresdi_afiliado->ViewCustomAttributes = "";

			// telf_fijo_afiliado
			$this->telf_fijo_afiliado->ViewValue = $this->telf_fijo_afiliado->CurrentValue;
			$this->telf_fijo_afiliado->ViewCustomAttributes = "";

			// coleg_afiliado
			$this->coleg_afiliado->ViewValue = $this->coleg_afiliado->CurrentValue;
			$this->coleg_afiliado->ViewCustomAttributes = "";

			// seguro_afiliado
			$this->seguro_afiliado->ViewValue = $this->seguro_afiliado->CurrentValue;
			$this->seguro_afiliado->ViewCustomAttributes = "";

			// tiposangre_afiliado
			$this->tiposangre_afiliado->ViewValue = $this->tiposangre_afiliado->CurrentValue;
			$this->tiposangre_afiliado->ViewCustomAttributes = "";

			// contacto_afiliado
			$this->contacto_afiliado->ViewValue = $this->contacto_afiliado->CurrentValue;
			$this->contacto_afiliado->ViewCustomAttributes = "";

			// st_afiliado
			$this->st_afiliado->ViewValue = $this->st_afiliado->CurrentValue;
			$this->st_afiliado->ViewCustomAttributes = "";

			// foto_afiliado
			if (!ew_Empty($this->foto_afiliado->Upload->DbValue)) {
				$this->foto_afiliado->ImageWidth = 200;
				$this->foto_afiliado->ImageHeight = 200;
				$this->foto_afiliado->ImageAlt = $this->foto_afiliado->FldAlt();
				$this->foto_afiliado->ViewValue = ew_UploadPathEx(FALSE, $this->foto_afiliado->UploadPath) . $this->foto_afiliado->Upload->DbValue;
			} else {
				$this->foto_afiliado->ViewValue = "";
			}
			$this->foto_afiliado->ViewCustomAttributes = "";

			// st_notificado
			$this->st_notificado->ViewValue = $this->st_notificado->CurrentValue;
			$this->st_notificado->ViewCustomAttributes = "";

			// dociden_afiliado
			$this->dociden_afiliado->LinkCustomAttributes = "";
			$this->dociden_afiliado->HrefValue = "";
			$this->dociden_afiliado->TooltipValue = "";

			// apell_afiliado
			$this->apell_afiliado->LinkCustomAttributes = "";
			$this->apell_afiliado->HrefValue = "";
			$this->apell_afiliado->TooltipValue = "";

			// nomb_afiliado
			$this->nomb_afiliado->LinkCustomAttributes = "";
			$this->nomb_afiliado->HrefValue = "";
			$this->nomb_afiliado->TooltipValue = "";

			// direcc_afiliado
			$this->direcc_afiliado->LinkCustomAttributes = "";
			$this->direcc_afiliado->HrefValue = "";
			$this->direcc_afiliado->TooltipValue = "";

			// email_afiliado
			$this->email_afiliado->LinkCustomAttributes = "";
			$this->email_afiliado->HrefValue = "";
			$this->email_afiliado->TooltipValue = "";

			// cel_afiliado
			$this->cel_afiliado->LinkCustomAttributes = "";
			$this->cel_afiliado->HrefValue = "";
			$this->cel_afiliado->TooltipValue = "";

			// genero_afiliado
			$this->genero_afiliado->LinkCustomAttributes = "";
			$this->genero_afiliado->HrefValue = "";
			$this->genero_afiliado->TooltipValue = "";

			// fe_afiliado
			$this->fe_afiliado->LinkCustomAttributes = "";
			$this->fe_afiliado->HrefValue = "";
			$this->fe_afiliado->TooltipValue = "";

			// telemerg_afiliado
			$this->telemerg_afiliado->LinkCustomAttributes = "";
			$this->telemerg_afiliado->HrefValue = "";
			$this->telemerg_afiliado->TooltipValue = "";

			// talla_afiliado
			$this->talla_afiliado->LinkCustomAttributes = "";
			$this->talla_afiliado->HrefValue = "";
			$this->talla_afiliado->TooltipValue = "";

			// peso_afiliado
			$this->peso_afiliado->LinkCustomAttributes = "";
			$this->peso_afiliado->HrefValue = "";
			$this->peso_afiliado->TooltipValue = "";

			// altu_afiliado
			$this->altu_afiliado->LinkCustomAttributes = "";
			$this->altu_afiliado->HrefValue = "";
			$this->altu_afiliado->TooltipValue = "";

			// localresdi_afiliado
			$this->localresdi_afiliado->LinkCustomAttributes = "";
			$this->localresdi_afiliado->HrefValue = "";
			$this->localresdi_afiliado->TooltipValue = "";

			// telf_fijo_afiliado
			$this->telf_fijo_afiliado->LinkCustomAttributes = "";
			$this->telf_fijo_afiliado->HrefValue = "";
			$this->telf_fijo_afiliado->TooltipValue = "";

			// coleg_afiliado
			$this->coleg_afiliado->LinkCustomAttributes = "";
			$this->coleg_afiliado->HrefValue = "";
			$this->coleg_afiliado->TooltipValue = "";

			// seguro_afiliado
			$this->seguro_afiliado->LinkCustomAttributes = "";
			$this->seguro_afiliado->HrefValue = "";
			$this->seguro_afiliado->TooltipValue = "";

			// tiposangre_afiliado
			$this->tiposangre_afiliado->LinkCustomAttributes = "";
			$this->tiposangre_afiliado->HrefValue = "";
			$this->tiposangre_afiliado->TooltipValue = "";

			// contacto_afiliado
			$this->contacto_afiliado->LinkCustomAttributes = "";
			$this->contacto_afiliado->HrefValue = "";
			$this->contacto_afiliado->TooltipValue = "";

			// st_afiliado
			$this->st_afiliado->LinkCustomAttributes = "";
			$this->st_afiliado->HrefValue = "";
			$this->st_afiliado->TooltipValue = "";

			// foto_afiliado
			$this->foto_afiliado->LinkCustomAttributes = "";
			if (!ew_Empty($this->foto_afiliado->Upload->DbValue)) {
				$this->foto_afiliado->HrefValue = ew_UploadPathEx(FALSE, $this->foto_afiliado->UploadPath) . $this->foto_afiliado->Upload->DbValue; // Add prefix/suffix
				$this->foto_afiliado->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->foto_afiliado->HrefValue = ew_ConvertFullUrl($this->foto_afiliado->HrefValue);
			} else {
				$this->foto_afiliado->HrefValue = "";
			}
			$this->foto_afiliado->HrefValue2 = $this->foto_afiliado->UploadPath . $this->foto_afiliado->Upload->DbValue;
			$this->foto_afiliado->TooltipValue = $this->foto_afiliado->ViewValue;
			if ($this->foto_afiliado->HrefValue == "") $this->foto_afiliado->HrefValue = "javascript:void(0);";
			$this->foto_afiliado->LinkAttrs["class"] = "ewTooltipLink";
			$this->foto_afiliado->LinkAttrs["data-tooltip-id"] = "tt_afiliado_x_foto_afiliado";
			$this->foto_afiliado->LinkAttrs["data-tooltip-width"] = $this->foto_afiliado->TooltipWidth;
			$this->foto_afiliado->LinkAttrs["data-placement"] = "right";

			// st_notificado
			$this->st_notificado->LinkCustomAttributes = "";
			$this->st_notificado->HrefValue = "";
			$this->st_notificado->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// dociden_afiliado
			$this->dociden_afiliado->EditCustomAttributes = "";
			$this->dociden_afiliado->EditValue = ew_HtmlEncode($this->dociden_afiliado->CurrentValue);
			$this->dociden_afiliado->PlaceHolder = ew_RemoveHtml($this->dociden_afiliado->FldCaption());

			// apell_afiliado
			$this->apell_afiliado->EditCustomAttributes = "";
			$this->apell_afiliado->EditValue = ew_HtmlEncode($this->apell_afiliado->CurrentValue);
			$this->apell_afiliado->PlaceHolder = ew_RemoveHtml($this->apell_afiliado->FldCaption());

			// nomb_afiliado
			$this->nomb_afiliado->EditCustomAttributes = "";
			$this->nomb_afiliado->EditValue = ew_HtmlEncode($this->nomb_afiliado->CurrentValue);
			$this->nomb_afiliado->PlaceHolder = ew_RemoveHtml($this->nomb_afiliado->FldCaption());

			// direcc_afiliado
			$this->direcc_afiliado->EditCustomAttributes = "";
			$this->direcc_afiliado->EditValue = ew_HtmlEncode($this->direcc_afiliado->CurrentValue);
			$this->direcc_afiliado->PlaceHolder = ew_RemoveHtml($this->direcc_afiliado->FldCaption());

			// email_afiliado
			$this->email_afiliado->EditCustomAttributes = "";
			$this->email_afiliado->EditValue = ew_HtmlEncode($this->email_afiliado->CurrentValue);
			$this->email_afiliado->PlaceHolder = ew_RemoveHtml($this->email_afiliado->FldCaption());

			// cel_afiliado
			$this->cel_afiliado->EditCustomAttributes = "";
			$this->cel_afiliado->EditValue = ew_HtmlEncode($this->cel_afiliado->CurrentValue);
			$this->cel_afiliado->PlaceHolder = ew_RemoveHtml($this->cel_afiliado->FldCaption());

			// genero_afiliado
			$this->genero_afiliado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->genero_afiliado->FldTagValue(1), $this->genero_afiliado->FldTagCaption(1) <> "" ? $this->genero_afiliado->FldTagCaption(1) : $this->genero_afiliado->FldTagValue(1));
			$arwrk[] = array($this->genero_afiliado->FldTagValue(2), $this->genero_afiliado->FldTagCaption(2) <> "" ? $this->genero_afiliado->FldTagCaption(2) : $this->genero_afiliado->FldTagValue(2));
			$this->genero_afiliado->EditValue = $arwrk;

			// fe_afiliado
			$this->fe_afiliado->EditCustomAttributes = "";
			$this->fe_afiliado->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fe_afiliado->CurrentValue, 5));
			$this->fe_afiliado->PlaceHolder = ew_RemoveHtml($this->fe_afiliado->FldCaption());

			// telemerg_afiliado
			$this->telemerg_afiliado->EditCustomAttributes = "";
			$this->telemerg_afiliado->EditValue = ew_HtmlEncode($this->telemerg_afiliado->CurrentValue);
			$this->telemerg_afiliado->PlaceHolder = ew_RemoveHtml($this->telemerg_afiliado->FldCaption());

			// talla_afiliado
			$this->talla_afiliado->EditCustomAttributes = "";
			$this->talla_afiliado->EditValue = ew_HtmlEncode($this->talla_afiliado->CurrentValue);
			$this->talla_afiliado->PlaceHolder = ew_RemoveHtml($this->talla_afiliado->FldCaption());

			// peso_afiliado
			$this->peso_afiliado->EditCustomAttributes = "";
			$this->peso_afiliado->EditValue = ew_HtmlEncode($this->peso_afiliado->CurrentValue);
			$this->peso_afiliado->PlaceHolder = ew_RemoveHtml($this->peso_afiliado->FldCaption());

			// altu_afiliado
			$this->altu_afiliado->EditCustomAttributes = "";
			$this->altu_afiliado->EditValue = ew_HtmlEncode($this->altu_afiliado->CurrentValue);
			$this->altu_afiliado->PlaceHolder = ew_RemoveHtml($this->altu_afiliado->FldCaption());
			if (strval($this->altu_afiliado->EditValue) <> "" && is_numeric($this->altu_afiliado->EditValue)) $this->altu_afiliado->EditValue = ew_FormatNumber($this->altu_afiliado->EditValue, -2, -1, -2, 0);

			// localresdi_afiliado
			$this->localresdi_afiliado->EditCustomAttributes = "";
			$this->localresdi_afiliado->EditValue = ew_HtmlEncode($this->localresdi_afiliado->CurrentValue);
			$this->localresdi_afiliado->PlaceHolder = ew_RemoveHtml($this->localresdi_afiliado->FldCaption());

			// telf_fijo_afiliado
			$this->telf_fijo_afiliado->EditCustomAttributes = "";
			$this->telf_fijo_afiliado->EditValue = ew_HtmlEncode($this->telf_fijo_afiliado->CurrentValue);
			$this->telf_fijo_afiliado->PlaceHolder = ew_RemoveHtml($this->telf_fijo_afiliado->FldCaption());

			// coleg_afiliado
			$this->coleg_afiliado->EditCustomAttributes = "";
			$this->coleg_afiliado->EditValue = ew_HtmlEncode($this->coleg_afiliado->CurrentValue);
			$this->coleg_afiliado->PlaceHolder = ew_RemoveHtml($this->coleg_afiliado->FldCaption());

			// seguro_afiliado
			$this->seguro_afiliado->EditCustomAttributes = "";
			$this->seguro_afiliado->EditValue = ew_HtmlEncode($this->seguro_afiliado->CurrentValue);
			$this->seguro_afiliado->PlaceHolder = ew_RemoveHtml($this->seguro_afiliado->FldCaption());

			// tiposangre_afiliado
			$this->tiposangre_afiliado->EditCustomAttributes = "";
			$this->tiposangre_afiliado->EditValue = ew_HtmlEncode($this->tiposangre_afiliado->CurrentValue);
			$this->tiposangre_afiliado->PlaceHolder = ew_RemoveHtml($this->tiposangre_afiliado->FldCaption());

			// contacto_afiliado
			$this->contacto_afiliado->EditCustomAttributes = "";
			$this->contacto_afiliado->EditValue = ew_HtmlEncode($this->contacto_afiliado->CurrentValue);
			$this->contacto_afiliado->PlaceHolder = ew_RemoveHtml($this->contacto_afiliado->FldCaption());

			// st_afiliado
			$this->st_afiliado->EditCustomAttributes = "";

			// foto_afiliado
			$this->foto_afiliado->EditCustomAttributes = "";
			if (!ew_Empty($this->foto_afiliado->Upload->DbValue)) {
				$this->foto_afiliado->ImageWidth = 200;
				$this->foto_afiliado->ImageHeight = 200;
				$this->foto_afiliado->ImageAlt = $this->foto_afiliado->FldAlt();
				$this->foto_afiliado->EditValue = ew_UploadPathEx(FALSE, $this->foto_afiliado->UploadPath) . $this->foto_afiliado->Upload->DbValue;
			} else {
				$this->foto_afiliado->EditValue = "";
			}
			if (!ew_Empty($this->foto_afiliado->CurrentValue))
				$this->foto_afiliado->Upload->FileName = $this->foto_afiliado->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->foto_afiliado);

			// st_notificado
			$this->st_notificado->EditCustomAttributes = "";

			// Edit refer script
			// dociden_afiliado

			$this->dociden_afiliado->HrefValue = "";

			// apell_afiliado
			$this->apell_afiliado->HrefValue = "";

			// nomb_afiliado
			$this->nomb_afiliado->HrefValue = "";

			// direcc_afiliado
			$this->direcc_afiliado->HrefValue = "";

			// email_afiliado
			$this->email_afiliado->HrefValue = "";

			// cel_afiliado
			$this->cel_afiliado->HrefValue = "";

			// genero_afiliado
			$this->genero_afiliado->HrefValue = "";

			// fe_afiliado
			$this->fe_afiliado->HrefValue = "";

			// telemerg_afiliado
			$this->telemerg_afiliado->HrefValue = "";

			// talla_afiliado
			$this->talla_afiliado->HrefValue = "";

			// peso_afiliado
			$this->peso_afiliado->HrefValue = "";

			// altu_afiliado
			$this->altu_afiliado->HrefValue = "";

			// localresdi_afiliado
			$this->localresdi_afiliado->HrefValue = "";

			// telf_fijo_afiliado
			$this->telf_fijo_afiliado->HrefValue = "";

			// coleg_afiliado
			$this->coleg_afiliado->HrefValue = "";

			// seguro_afiliado
			$this->seguro_afiliado->HrefValue = "";

			// tiposangre_afiliado
			$this->tiposangre_afiliado->HrefValue = "";

			// contacto_afiliado
			$this->contacto_afiliado->HrefValue = "";

			// st_afiliado
			$this->st_afiliado->HrefValue = "";

			// foto_afiliado
			if (!ew_Empty($this->foto_afiliado->Upload->DbValue)) {
				$this->foto_afiliado->HrefValue = ew_UploadPathEx(FALSE, $this->foto_afiliado->UploadPath) . $this->foto_afiliado->Upload->DbValue; // Add prefix/suffix
				$this->foto_afiliado->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->foto_afiliado->HrefValue = ew_ConvertFullUrl($this->foto_afiliado->HrefValue);
			} else {
				$this->foto_afiliado->HrefValue = "";
			}
			$this->foto_afiliado->HrefValue2 = $this->foto_afiliado->UploadPath . $this->foto_afiliado->Upload->DbValue;

			// st_notificado
			$this->st_notificado->HrefValue = "";
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
		if (!$this->dociden_afiliado->FldIsDetailKey && !is_null($this->dociden_afiliado->FormValue) && $this->dociden_afiliado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dociden_afiliado->FldCaption());
		}
		if (!$this->apell_afiliado->FldIsDetailKey && !is_null($this->apell_afiliado->FormValue) && $this->apell_afiliado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->apell_afiliado->FldCaption());
		}
		if (!$this->nomb_afiliado->FldIsDetailKey && !is_null($this->nomb_afiliado->FormValue) && $this->nomb_afiliado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nomb_afiliado->FldCaption());
		}
		if (!ew_CheckEmail($this->email_afiliado->FormValue)) {
			ew_AddMessage($gsFormError, $this->email_afiliado->FldErrMsg());
		}
		if ($this->genero_afiliado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->genero_afiliado->FldCaption());
		}
		if (!$this->fe_afiliado->FldIsDetailKey && !is_null($this->fe_afiliado->FormValue) && $this->fe_afiliado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->fe_afiliado->FldCaption());
		}
		if (!ew_CheckDate($this->fe_afiliado->FormValue)) {
			ew_AddMessage($gsFormError, $this->fe_afiliado->FldErrMsg());
		}
		if (!ew_CheckNumber($this->altu_afiliado->FormValue)) {
			ew_AddMessage($gsFormError, $this->altu_afiliado->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("historial", $DetailTblVar) && $GLOBALS["historial"]->DetailEdit) {
			if (!isset($GLOBALS["historial_grid"])) $GLOBALS["historial_grid"] = new chistorial_grid(); // get detail page object
			$GLOBALS["historial_grid"]->ValidateGridForm();
		}
		if (in_array("matricula", $DetailTblVar) && $GLOBALS["matricula"]->DetailEdit) {
			if (!isset($GLOBALS["matricula_grid"])) $GLOBALS["matricula_grid"] = new cmatricula_grid(); // get detail page object
			$GLOBALS["matricula_grid"]->ValidateGridForm();
		}
		if (in_array("representantes", $DetailTblVar) && $GLOBALS["representantes"]->DetailEdit) {
			if (!isset($GLOBALS["representantes_grid"])) $GLOBALS["representantes_grid"] = new crepresentantes_grid(); // get detail page object
			$GLOBALS["representantes_grid"]->ValidateGridForm();
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
		if ($this->dociden_afiliado->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`dociden_afiliado` = '" . ew_AdjustSql($this->dociden_afiliado->CurrentValue) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->dociden_afiliado->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->dociden_afiliado->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
		if ($this->email_afiliado->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`email_afiliado` = '" . ew_AdjustSql($this->email_afiliado->CurrentValue) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->email_afiliado->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->email_afiliado->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
		if ($this->cel_afiliado->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`cel_afiliado` = '" . ew_AdjustSql($this->cel_afiliado->CurrentValue) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->cel_afiliado->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->cel_afiliado->CurrentValue, $sIdxErrMsg);
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// dociden_afiliado
			$this->dociden_afiliado->SetDbValueDef($rsnew, $this->dociden_afiliado->CurrentValue, "", $this->dociden_afiliado->ReadOnly);

			// apell_afiliado
			$this->apell_afiliado->SetDbValueDef($rsnew, $this->apell_afiliado->CurrentValue, "", $this->apell_afiliado->ReadOnly);

			// nomb_afiliado
			$this->nomb_afiliado->SetDbValueDef($rsnew, $this->nomb_afiliado->CurrentValue, "", $this->nomb_afiliado->ReadOnly);

			// direcc_afiliado
			$this->direcc_afiliado->SetDbValueDef($rsnew, $this->direcc_afiliado->CurrentValue, NULL, $this->direcc_afiliado->ReadOnly);

			// email_afiliado
			$this->email_afiliado->SetDbValueDef($rsnew, $this->email_afiliado->CurrentValue, NULL, $this->email_afiliado->ReadOnly);

			// cel_afiliado
			$this->cel_afiliado->SetDbValueDef($rsnew, $this->cel_afiliado->CurrentValue, NULL, $this->cel_afiliado->ReadOnly);

			// genero_afiliado
			$this->genero_afiliado->SetDbValueDef($rsnew, $this->genero_afiliado->CurrentValue, "", $this->genero_afiliado->ReadOnly);

			// fe_afiliado
			$this->fe_afiliado->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fe_afiliado->CurrentValue, 5), ew_CurrentDate(), $this->fe_afiliado->ReadOnly);

			// telemerg_afiliado
			$this->telemerg_afiliado->SetDbValueDef($rsnew, $this->telemerg_afiliado->CurrentValue, NULL, $this->telemerg_afiliado->ReadOnly);

			// talla_afiliado
			$this->talla_afiliado->SetDbValueDef($rsnew, $this->talla_afiliado->CurrentValue, NULL, $this->talla_afiliado->ReadOnly);

			// peso_afiliado
			$this->peso_afiliado->SetDbValueDef($rsnew, $this->peso_afiliado->CurrentValue, NULL, $this->peso_afiliado->ReadOnly);

			// altu_afiliado
			$this->altu_afiliado->SetDbValueDef($rsnew, $this->altu_afiliado->CurrentValue, NULL, $this->altu_afiliado->ReadOnly);

			// localresdi_afiliado
			$this->localresdi_afiliado->SetDbValueDef($rsnew, $this->localresdi_afiliado->CurrentValue, NULL, $this->localresdi_afiliado->ReadOnly);

			// telf_fijo_afiliado
			$this->telf_fijo_afiliado->SetDbValueDef($rsnew, $this->telf_fijo_afiliado->CurrentValue, NULL, $this->telf_fijo_afiliado->ReadOnly);

			// coleg_afiliado
			$this->coleg_afiliado->SetDbValueDef($rsnew, $this->coleg_afiliado->CurrentValue, NULL, $this->coleg_afiliado->ReadOnly);

			// seguro_afiliado
			$this->seguro_afiliado->SetDbValueDef($rsnew, $this->seguro_afiliado->CurrentValue, NULL, $this->seguro_afiliado->ReadOnly);

			// tiposangre_afiliado
			$this->tiposangre_afiliado->SetDbValueDef($rsnew, $this->tiposangre_afiliado->CurrentValue, NULL, $this->tiposangre_afiliado->ReadOnly);

			// contacto_afiliado
			$this->contacto_afiliado->SetDbValueDef($rsnew, $this->contacto_afiliado->CurrentValue, NULL, $this->contacto_afiliado->ReadOnly);

			// st_afiliado
			$this->st_afiliado->SetDbValueDef($rsnew, $this->st_afiliado->CurrentValue, NULL, $this->st_afiliado->ReadOnly);

			// foto_afiliado
			if (!($this->foto_afiliado->ReadOnly) && !$this->foto_afiliado->Upload->KeepFile) {
				$this->foto_afiliado->Upload->DbValue = $rs->fields('foto_afiliado'); // Get original value
				if ($this->foto_afiliado->Upload->FileName == "") {
					$rsnew['foto_afiliado'] = NULL;
				} else {
					$rsnew['foto_afiliado'] = $this->foto_afiliado->Upload->FileName;
				}
			}

			// st_notificado
			$this->st_notificado->SetDbValueDef($rsnew, $this->st_notificado->CurrentValue, NULL, $this->st_notificado->ReadOnly);
			if (!$this->foto_afiliado->Upload->KeepFile) {
				if (!ew_Empty($this->foto_afiliado->Upload->Value)) {
					$rsnew['foto_afiliado'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->foto_afiliado->UploadPath), $rsnew['foto_afiliado']); // Get new file name
				}
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
					if (!$this->foto_afiliado->Upload->KeepFile) {
						if (!ew_Empty($this->foto_afiliado->Upload->Value)) {
							$this->foto_afiliado->Upload->SaveToFile($this->foto_afiliado->UploadPath, $rsnew['foto_afiliado'], TRUE);
						}
					}
				}

				// Update detail records
				if ($EditRow) {
					$DetailTblVar = explode(",", $this->getCurrentDetailTable());
					if (in_array("historial", $DetailTblVar) && $GLOBALS["historial"]->DetailEdit) {
						if (!isset($GLOBALS["historial_grid"])) $GLOBALS["historial_grid"] = new chistorial_grid(); // Get detail page object
						$EditRow = $GLOBALS["historial_grid"]->GridUpdate();
					}
					if (in_array("matricula", $DetailTblVar) && $GLOBALS["matricula"]->DetailEdit) {
						if (!isset($GLOBALS["matricula_grid"])) $GLOBALS["matricula_grid"] = new cmatricula_grid(); // Get detail page object
						$EditRow = $GLOBALS["matricula_grid"]->GridUpdate();
					}
					if (in_array("representantes", $DetailTblVar) && $GLOBALS["representantes"]->DetailEdit) {
						if (!isset($GLOBALS["representantes_grid"])) $GLOBALS["representantes_grid"] = new crepresentantes_grid(); // Get detail page object
						$EditRow = $GLOBALS["representantes_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
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

		// foto_afiliado
		ew_CleanUploadTempPath($this->foto_afiliado, $this->foto_afiliado->Upload->Index);
		return $EditRow;
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
			if (in_array("historial", $DetailTblVar)) {
				if (!isset($GLOBALS["historial_grid"]))
					$GLOBALS["historial_grid"] = new chistorial_grid;
				if ($GLOBALS["historial_grid"]->DetailEdit) {
					$GLOBALS["historial_grid"]->CurrentMode = "edit";
					$GLOBALS["historial_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["historial_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["historial_grid"]->setStartRecordNumber(1);
					$GLOBALS["historial_grid"]->id_afiliado->FldIsDetailKey = TRUE;
					$GLOBALS["historial_grid"]->id_afiliado->CurrentValue = $this->id_afiliado->CurrentValue;
					$GLOBALS["historial_grid"]->id_afiliado->setSessionValue($GLOBALS["historial_grid"]->id_afiliado->CurrentValue);
				}
			}
			if (in_array("matricula", $DetailTblVar)) {
				if (!isset($GLOBALS["matricula_grid"]))
					$GLOBALS["matricula_grid"] = new cmatricula_grid;
				if ($GLOBALS["matricula_grid"]->DetailEdit) {
					$GLOBALS["matricula_grid"]->CurrentMode = "edit";
					$GLOBALS["matricula_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["matricula_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["matricula_grid"]->setStartRecordNumber(1);
					$GLOBALS["matricula_grid"]->id_afiliado->FldIsDetailKey = TRUE;
					$GLOBALS["matricula_grid"]->id_afiliado->CurrentValue = $this->id_afiliado->CurrentValue;
					$GLOBALS["matricula_grid"]->id_afiliado->setSessionValue($GLOBALS["matricula_grid"]->id_afiliado->CurrentValue);
				}
			}
			if (in_array("representantes", $DetailTblVar)) {
				if (!isset($GLOBALS["representantes_grid"]))
					$GLOBALS["representantes_grid"] = new crepresentantes_grid;
				if ($GLOBALS["representantes_grid"]->DetailEdit) {
					$GLOBALS["representantes_grid"]->CurrentMode = "edit";
					$GLOBALS["representantes_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["representantes_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["representantes_grid"]->setStartRecordNumber(1);
					$GLOBALS["representantes_grid"]->id_afiliado->FldIsDetailKey = TRUE;
					$GLOBALS["representantes_grid"]->id_afiliado->CurrentValue = $this->id_afiliado->CurrentValue;
					$GLOBALS["representantes_grid"]->id_afiliado->setSessionValue($GLOBALS["representantes_grid"]->id_afiliado->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "afiliadolist.php", $this->TableVar, TRUE);
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
if (!isset($afiliado_edit)) $afiliado_edit = new cafiliado_edit();

// Page init
$afiliado_edit->Page_Init();

// Page main
$afiliado_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$afiliado_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var afiliado_edit = new ew_Page("afiliado_edit");
afiliado_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = afiliado_edit.PageID; // For backward compatibility

// Form object
var fafiliadoedit = new ew_Form("fafiliadoedit");

// Validate form
fafiliadoedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_dociden_afiliado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($afiliado->dociden_afiliado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_apell_afiliado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($afiliado->apell_afiliado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nomb_afiliado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($afiliado->nomb_afiliado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_email_afiliado");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($afiliado->email_afiliado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_genero_afiliado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($afiliado->genero_afiliado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_fe_afiliado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($afiliado->fe_afiliado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_fe_afiliado");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($afiliado->fe_afiliado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_altu_afiliado");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($afiliado->altu_afiliado->FldErrMsg()) ?>");

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
fafiliadoedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fafiliadoedit.ValidateRequired = true;
<?php } else { ?>
fafiliadoedit.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fafiliadoedit.MultiPage = new ew_MultiPage("fafiliadoedit",
	[["x_dociden_afiliado",1],["x_apell_afiliado",1],["x_nomb_afiliado",1],["x_direcc_afiliado",1],["x_email_afiliado",1],["x_cel_afiliado",1],["x_genero_afiliado",1],["x_fe_afiliado",1],["x_telemerg_afiliado",2],["x_talla_afiliado",2],["x_peso_afiliado",2],["x_altu_afiliado",2],["x_localresdi_afiliado",2],["x_telf_fijo_afiliado",1],["x_coleg_afiliado",2],["x_seguro_afiliado",2],["x_tiposangre_afiliado",2],["x_contacto_afiliado",2],["x_foto_afiliado",1]]
);

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $afiliado_edit->ShowPageHeader(); ?>
<?php
$afiliado_edit->ShowMessage();
?>
<form name="fafiliadoedit" id="fafiliadoedit" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="afiliado">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="afiliado_edit">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_afiliado1" data-toggle="tab"><?php echo $afiliado->PageCaption(1) ?></a></li>
		<li><a href="#tab_afiliado2" data-toggle="tab"><?php echo $afiliado->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_afiliado1">
<table class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_afiliadoedit1" class="table table-bordered table-striped">
<?php if ($afiliado->dociden_afiliado->Visible) { // dociden_afiliado ?>
	<tr id="r_dociden_afiliado">
		<td><span id="elh_afiliado_dociden_afiliado"><?php echo $afiliado->dociden_afiliado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $afiliado->dociden_afiliado->CellAttributes() ?>>
<span id="el_afiliado_dociden_afiliado" class="control-group">
<input type="text" data-field="x_dociden_afiliado" name="x_dociden_afiliado" id="x_dociden_afiliado" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($afiliado->dociden_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->dociden_afiliado->EditValue ?>"<?php echo $afiliado->dociden_afiliado->EditAttributes() ?>>
</span>
<?php echo $afiliado->dociden_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->apell_afiliado->Visible) { // apell_afiliado ?>
	<tr id="r_apell_afiliado">
		<td><span id="elh_afiliado_apell_afiliado"><?php echo $afiliado->apell_afiliado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $afiliado->apell_afiliado->CellAttributes() ?>>
<span id="el_afiliado_apell_afiliado" class="control-group">
<input type="text" data-field="x_apell_afiliado" name="x_apell_afiliado" id="x_apell_afiliado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($afiliado->apell_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->apell_afiliado->EditValue ?>"<?php echo $afiliado->apell_afiliado->EditAttributes() ?>>
</span>
<?php echo $afiliado->apell_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->nomb_afiliado->Visible) { // nomb_afiliado ?>
	<tr id="r_nomb_afiliado">
		<td><span id="elh_afiliado_nomb_afiliado"><?php echo $afiliado->nomb_afiliado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $afiliado->nomb_afiliado->CellAttributes() ?>>
<span id="el_afiliado_nomb_afiliado" class="control-group">
<input type="text" data-field="x_nomb_afiliado" name="x_nomb_afiliado" id="x_nomb_afiliado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($afiliado->nomb_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->nomb_afiliado->EditValue ?>"<?php echo $afiliado->nomb_afiliado->EditAttributes() ?>>
</span>
<?php echo $afiliado->nomb_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->direcc_afiliado->Visible) { // direcc_afiliado ?>
	<tr id="r_direcc_afiliado">
		<td><span id="elh_afiliado_direcc_afiliado"><?php echo $afiliado->direcc_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->direcc_afiliado->CellAttributes() ?>>
<span id="el_afiliado_direcc_afiliado" class="control-group">
<input type="text" data-field="x_direcc_afiliado" name="x_direcc_afiliado" id="x_direcc_afiliado" placeholder="<?php echo ew_HtmlEncode($afiliado->direcc_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->direcc_afiliado->EditValue ?>"<?php echo $afiliado->direcc_afiliado->EditAttributes() ?>>
</span>
<?php echo $afiliado->direcc_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->email_afiliado->Visible) { // email_afiliado ?>
	<tr id="r_email_afiliado">
		<td><span id="elh_afiliado_email_afiliado"><?php echo $afiliado->email_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->email_afiliado->CellAttributes() ?>>
<span id="el_afiliado_email_afiliado" class="control-group">
<input type="text" data-field="x_email_afiliado" name="x_email_afiliado" id="x_email_afiliado" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($afiliado->email_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->email_afiliado->EditValue ?>"<?php echo $afiliado->email_afiliado->EditAttributes() ?>>
</span>
<?php echo $afiliado->email_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->cel_afiliado->Visible) { // cel_afiliado ?>
	<tr id="r_cel_afiliado">
		<td><span id="elh_afiliado_cel_afiliado"><?php echo $afiliado->cel_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->cel_afiliado->CellAttributes() ?>>
<span id="el_afiliado_cel_afiliado" class="control-group">
<input type="text" data-field="x_cel_afiliado" name="x_cel_afiliado" id="x_cel_afiliado" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($afiliado->cel_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->cel_afiliado->EditValue ?>"<?php echo $afiliado->cel_afiliado->EditAttributes() ?>>
</span>
<?php echo $afiliado->cel_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->genero_afiliado->Visible) { // genero_afiliado ?>
	<tr id="r_genero_afiliado">
		<td><span id="elh_afiliado_genero_afiliado"><?php echo $afiliado->genero_afiliado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $afiliado->genero_afiliado->CellAttributes() ?>>
<span id="el_afiliado_genero_afiliado" class="control-group">
<div id="tp_x_genero_afiliado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_genero_afiliado" id="x_genero_afiliado" value="{value}"<?php echo $afiliado->genero_afiliado->EditAttributes() ?>></div>
<div id="dsl_x_genero_afiliado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $afiliado->genero_afiliado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($afiliado->genero_afiliado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_genero_afiliado" name="x_genero_afiliado" id="x_genero_afiliado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $afiliado->genero_afiliado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $afiliado->genero_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->fe_afiliado->Visible) { // fe_afiliado ?>
	<tr id="r_fe_afiliado">
		<td><span id="elh_afiliado_fe_afiliado"><?php echo $afiliado->fe_afiliado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $afiliado->fe_afiliado->CellAttributes() ?>>
<span id="el_afiliado_fe_afiliado" class="control-group">
<input type="text" data-field="x_fe_afiliado" name="x_fe_afiliado" id="x_fe_afiliado" placeholder="<?php echo ew_HtmlEncode($afiliado->fe_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->fe_afiliado->EditValue ?>"<?php echo $afiliado->fe_afiliado->EditAttributes() ?>>
<?php if (!$afiliado->fe_afiliado->ReadOnly && !$afiliado->fe_afiliado->Disabled && @$afiliado->fe_afiliado->EditAttrs["readonly"] == "" && @$afiliado->fe_afiliado->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_fe_afiliado" name="cal_x_fe_afiliado" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fafiliadoedit", "x_fe_afiliado", "%Y/%m/%d");
</script>
<?php } ?>
</span>
<?php echo $afiliado->fe_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->telf_fijo_afiliado->Visible) { // telf_fijo_afiliado ?>
	<tr id="r_telf_fijo_afiliado">
		<td><span id="elh_afiliado_telf_fijo_afiliado"><?php echo $afiliado->telf_fijo_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->telf_fijo_afiliado->CellAttributes() ?>>
<span id="el_afiliado_telf_fijo_afiliado" class="control-group">
<input type="text" data-field="x_telf_fijo_afiliado" name="x_telf_fijo_afiliado" id="x_telf_fijo_afiliado" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($afiliado->telf_fijo_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->telf_fijo_afiliado->EditValue ?>"<?php echo $afiliado->telf_fijo_afiliado->EditAttributes() ?>>
</span>
<?php echo $afiliado->telf_fijo_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->foto_afiliado->Visible) { // foto_afiliado ?>
	<tr id="r_foto_afiliado">
		<td><span id="elh_afiliado_foto_afiliado"><?php echo $afiliado->foto_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->foto_afiliado->CellAttributes() ?>>
<span id="el_afiliado_foto_afiliado" class="control-group">
<span id="fd_x_foto_afiliado">
<span class="btn btn-small fileinput-button"<?php if ($afiliado->foto_afiliado->ReadOnly || $afiliado->foto_afiliado->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_foto_afiliado" name="x_foto_afiliado" id="x_foto_afiliado">
</span>
<input type="hidden" name="fn_x_foto_afiliado" id= "fn_x_foto_afiliado" value="<?php echo $afiliado->foto_afiliado->Upload->FileName ?>">
<?php if (@$_POST["fa_x_foto_afiliado"] == "0") { ?>
<input type="hidden" name="fa_x_foto_afiliado" id= "fa_x_foto_afiliado" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_foto_afiliado" id= "fa_x_foto_afiliado" value="1">
<?php } ?>
<input type="hidden" name="fs_x_foto_afiliado" id= "fs_x_foto_afiliado" value="100">
</span>
<table id="ft_x_foto_afiliado" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $afiliado->foto_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_afiliado2">
<table class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_afiliadoedit2" class="table table-bordered table-striped">
<?php if ($afiliado->telemerg_afiliado->Visible) { // telemerg_afiliado ?>
	<tr id="r_telemerg_afiliado">
		<td><span id="elh_afiliado_telemerg_afiliado"><?php echo $afiliado->telemerg_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->telemerg_afiliado->CellAttributes() ?>>
<span id="el_afiliado_telemerg_afiliado" class="control-group">
<input type="text" data-field="x_telemerg_afiliado" name="x_telemerg_afiliado" id="x_telemerg_afiliado" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($afiliado->telemerg_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->telemerg_afiliado->EditValue ?>"<?php echo $afiliado->telemerg_afiliado->EditAttributes() ?>>
</span>
<?php echo $afiliado->telemerg_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->talla_afiliado->Visible) { // talla_afiliado ?>
	<tr id="r_talla_afiliado">
		<td><span id="elh_afiliado_talla_afiliado"><?php echo $afiliado->talla_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->talla_afiliado->CellAttributes() ?>>
<span id="el_afiliado_talla_afiliado" class="control-group">
<input type="text" data-field="x_talla_afiliado" name="x_talla_afiliado" id="x_talla_afiliado" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($afiliado->talla_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->talla_afiliado->EditValue ?>"<?php echo $afiliado->talla_afiliado->EditAttributes() ?>>
</span>
<?php echo $afiliado->talla_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->peso_afiliado->Visible) { // peso_afiliado ?>
	<tr id="r_peso_afiliado">
		<td><span id="elh_afiliado_peso_afiliado"><?php echo $afiliado->peso_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->peso_afiliado->CellAttributes() ?>>
<span id="el_afiliado_peso_afiliado" class="control-group">
<input type="text" data-field="x_peso_afiliado" name="x_peso_afiliado" id="x_peso_afiliado" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($afiliado->peso_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->peso_afiliado->EditValue ?>"<?php echo $afiliado->peso_afiliado->EditAttributes() ?>>
</span>
<?php echo $afiliado->peso_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->altu_afiliado->Visible) { // altu_afiliado ?>
	<tr id="r_altu_afiliado">
		<td><span id="elh_afiliado_altu_afiliado"><?php echo $afiliado->altu_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->altu_afiliado->CellAttributes() ?>>
<span id="el_afiliado_altu_afiliado" class="control-group">
<input type="text" data-field="x_altu_afiliado" name="x_altu_afiliado" id="x_altu_afiliado" size="30" placeholder="<?php echo ew_HtmlEncode($afiliado->altu_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->altu_afiliado->EditValue ?>"<?php echo $afiliado->altu_afiliado->EditAttributes() ?>>
</span>
<?php echo $afiliado->altu_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->localresdi_afiliado->Visible) { // localresdi_afiliado ?>
	<tr id="r_localresdi_afiliado">
		<td><span id="elh_afiliado_localresdi_afiliado"><?php echo $afiliado->localresdi_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->localresdi_afiliado->CellAttributes() ?>>
<span id="el_afiliado_localresdi_afiliado" class="control-group">
<input type="text" data-field="x_localresdi_afiliado" name="x_localresdi_afiliado" id="x_localresdi_afiliado" placeholder="<?php echo ew_HtmlEncode($afiliado->localresdi_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->localresdi_afiliado->EditValue ?>"<?php echo $afiliado->localresdi_afiliado->EditAttributes() ?>>
</span>
<?php echo $afiliado->localresdi_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->coleg_afiliado->Visible) { // coleg_afiliado ?>
	<tr id="r_coleg_afiliado">
		<td><span id="elh_afiliado_coleg_afiliado"><?php echo $afiliado->coleg_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->coleg_afiliado->CellAttributes() ?>>
<span id="el_afiliado_coleg_afiliado" class="control-group">
<input type="text" data-field="x_coleg_afiliado" name="x_coleg_afiliado" id="x_coleg_afiliado" size="30" maxlength="60" placeholder="<?php echo ew_HtmlEncode($afiliado->coleg_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->coleg_afiliado->EditValue ?>"<?php echo $afiliado->coleg_afiliado->EditAttributes() ?>>
</span>
<?php echo $afiliado->coleg_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->seguro_afiliado->Visible) { // seguro_afiliado ?>
	<tr id="r_seguro_afiliado">
		<td><span id="elh_afiliado_seguro_afiliado"><?php echo $afiliado->seguro_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->seguro_afiliado->CellAttributes() ?>>
<span id="el_afiliado_seguro_afiliado" class="control-group">
<input type="text" data-field="x_seguro_afiliado" name="x_seguro_afiliado" id="x_seguro_afiliado" size="30" maxlength="60" placeholder="<?php echo ew_HtmlEncode($afiliado->seguro_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->seguro_afiliado->EditValue ?>"<?php echo $afiliado->seguro_afiliado->EditAttributes() ?>>
</span>
<?php echo $afiliado->seguro_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->tiposangre_afiliado->Visible) { // tiposangre_afiliado ?>
	<tr id="r_tiposangre_afiliado">
		<td><span id="elh_afiliado_tiposangre_afiliado"><?php echo $afiliado->tiposangre_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->tiposangre_afiliado->CellAttributes() ?>>
<span id="el_afiliado_tiposangre_afiliado" class="control-group">
<input type="text" data-field="x_tiposangre_afiliado" name="x_tiposangre_afiliado" id="x_tiposangre_afiliado" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($afiliado->tiposangre_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->tiposangre_afiliado->EditValue ?>"<?php echo $afiliado->tiposangre_afiliado->EditAttributes() ?>>
</span>
<?php echo $afiliado->tiposangre_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($afiliado->contacto_afiliado->Visible) { // contacto_afiliado ?>
	<tr id="r_contacto_afiliado">
		<td><span id="elh_afiliado_contacto_afiliado"><?php echo $afiliado->contacto_afiliado->FldCaption() ?></span></td>
		<td<?php echo $afiliado->contacto_afiliado->CellAttributes() ?>>
<span id="el_afiliado_contacto_afiliado" class="control-group">
<input type="text" data-field="x_contacto_afiliado" name="x_contacto_afiliado" id="x_contacto_afiliado" size="30" maxlength="60" placeholder="<?php echo ew_HtmlEncode($afiliado->contacto_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->contacto_afiliado->EditValue ?>"<?php echo $afiliado->contacto_afiliado->EditAttributes() ?>>
</span>
<?php echo $afiliado->contacto_afiliado->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
	</div>
</div>
</td></tr></tbody></table>
<span id="el_afiliado_st_afiliado" class="control-group">
<input type="hidden" data-field="x_st_afiliado" name="x_st_afiliado" id="x_st_afiliado" value="<?php echo ew_HtmlEncode($afiliado->st_afiliado->CurrentValue) ?>">
</span>
<span id="el_afiliado_st_notificado" class="control-group">
<input type="hidden" data-field="x_st_notificado" name="x_st_notificado" id="x_st_notificado" value="<?php echo ew_HtmlEncode($afiliado->st_notificado->CurrentValue) ?>">
</span>
<input type="hidden" data-field="x_id_afiliado" name="x_id_afiliado" id="x_id_afiliado" value="<?php echo ew_HtmlEncode($afiliado->id_afiliado->CurrentValue) ?>">
<?php
	if (in_array("historial", explode(",", $afiliado->getCurrentDetailTable())) && $historial->DetailEdit) {
?>
<?php include_once "historialgrid.php" ?>
<?php } ?>
<?php
	if (in_array("matricula", explode(",", $afiliado->getCurrentDetailTable())) && $matricula->DetailEdit) {
?>
<?php include_once "matriculagrid.php" ?>
<?php } ?>
<?php
	if (in_array("representantes", explode(",", $afiliado->getCurrentDetailTable())) && $representantes->DetailEdit) {
?>
<?php include_once "representantesgrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fafiliadoedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$afiliado_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$afiliado_edit->Page_Terminate();
?>
