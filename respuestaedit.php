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

$respuesta_edit = NULL; // Initialize page object first

class crespuesta_edit extends crespuesta {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'respuesta';

	// Page object name
	var $PageObjName = 'respuesta_edit';

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

		// Table object (respuesta)
		if (!isset($GLOBALS["respuesta"]) || get_class($GLOBALS["respuesta"]) == "crespuesta") {
			$GLOBALS["respuesta"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["respuesta"];
		}

		// Table object (novedad)
		if (!isset($GLOBALS['novedad'])) $GLOBALS['novedad'] = new cnovedad();

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'respuesta', TRUE);

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
			$this->Page_Terminate("respuestalist.php");
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
		if (@$_GET["id_respuesta"] <> "") {
			$this->id_respuesta->setQueryStringValue($_GET["id_respuesta"]);
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
		if ($this->id_respuesta->CurrentValue == "")
			$this->Page_Terminate("respuestalist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("respuestalist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->GetEditUrl();
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
		if (!$this->id_empleado->FldIsDetailKey) {
			$this->id_empleado->setFormValue($objForm->GetValue("x_id_empleado"));
		}
		if (!$this->obs_resp->FldIsDetailKey) {
			$this->obs_resp->setFormValue($objForm->GetValue("x_obs_resp"));
		}
		if (!$this->estado_resp->FldIsDetailKey) {
			$this->estado_resp->setFormValue($objForm->GetValue("x_estado_resp"));
		}
		if (!$this->replica_resp->FldIsDetailKey) {
			$this->replica_resp->setFormValue($objForm->GetValue("x_replica_resp"));
		}
		if (!$this->id_respuesta->FldIsDetailKey)
			$this->id_respuesta->setFormValue($objForm->GetValue("x_id_respuesta"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_respuesta->CurrentValue = $this->id_respuesta->FormValue;
		$this->id_empleado->CurrentValue = $this->id_empleado->FormValue;
		$this->obs_resp->CurrentValue = $this->obs_resp->FormValue;
		$this->estado_resp->CurrentValue = $this->estado_resp->FormValue;
		$this->replica_resp->CurrentValue = $this->replica_resp->FormValue;
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

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
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

			// obs_resp
			$this->obs_resp->ViewValue = $this->obs_resp->CurrentValue;
			$this->obs_resp->ViewCustomAttributes = "";

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

			// replica_resp
			$this->replica_resp->ViewValue = $this->replica_resp->CurrentValue;
			$this->replica_resp->ViewCustomAttributes = "";

			// id_empleado
			$this->id_empleado->LinkCustomAttributes = "";
			$this->id_empleado->HrefValue = "";
			$this->id_empleado->TooltipValue = "";

			// obs_resp
			$this->obs_resp->LinkCustomAttributes = "";
			$this->obs_resp->HrefValue = "";
			$this->obs_resp->TooltipValue = "";

			// estado_resp
			$this->estado_resp->LinkCustomAttributes = "";
			$this->estado_resp->HrefValue = "";
			$this->estado_resp->TooltipValue = "";

			// replica_resp
			$this->replica_resp->LinkCustomAttributes = "";
			$this->replica_resp->HrefValue = "";
			$this->replica_resp->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_empleado
			$this->id_empleado->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id_empleado`, `dociden_empleado` AS `DispFld`, `nomb_empleado` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `empleados`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_empleado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nomb_empleado`";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_empleado->EditValue = $arwrk;

			// obs_resp
			$this->obs_resp->EditCustomAttributes = "";
			$this->obs_resp->EditValue = $this->obs_resp->CurrentValue;
			$this->obs_resp->PlaceHolder = ew_RemoveHtml($this->obs_resp->FldCaption());

			// estado_resp
			$this->estado_resp->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id_x_estado_respuesta`, `estado_respuesta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `x_estado_respuesta`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->estado_resp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->estado_resp->EditValue = $arwrk;

			// replica_resp
			$this->replica_resp->EditCustomAttributes = "";
			$this->replica_resp->EditValue = $this->replica_resp->CurrentValue;
			$this->replica_resp->PlaceHolder = ew_RemoveHtml($this->replica_resp->FldCaption());

			// Edit refer script
			// id_empleado

			$this->id_empleado->HrefValue = "";

			// obs_resp
			$this->obs_resp->HrefValue = "";

			// estado_resp
			$this->estado_resp->HrefValue = "";

			// replica_resp
			$this->replica_resp->HrefValue = "";
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
		if (!$this->id_empleado->FldIsDetailKey && !is_null($this->id_empleado->FormValue) && $this->id_empleado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id_empleado->FldCaption());
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

			// id_empleado
			$this->id_empleado->SetDbValueDef($rsnew, $this->id_empleado->CurrentValue, NULL, $this->id_empleado->ReadOnly);

			// obs_resp
			$this->obs_resp->SetDbValueDef($rsnew, $this->obs_resp->CurrentValue, NULL, $this->obs_resp->ReadOnly);

			// estado_resp
			$this->estado_resp->SetDbValueDef($rsnew, $this->estado_resp->CurrentValue, NULL, $this->estado_resp->ReadOnly);

			// replica_resp
			$this->replica_resp->SetDbValueDef($rsnew, $this->replica_resp->CurrentValue, NULL, $this->replica_resp->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "respuestalist.php", $this->TableVar, TRUE);
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
		// si es el jefe y el estado es = 2  
		//(REVISADO POR EMPLEADO) entonces habilite boton email

		/*
		if (CurrentUserLevel() =='2')
		{   
			$this->estado_resp->Visible = FALSE;
			$this->obs_resp->Disabled = TRUE;
			$this->id_empleado->Disabled = TRUE;
		} */    
		if (CurrentUserLevel() =='2' && $this->estado_resp->CurrentValue != '1' )
		{                   
		   CurrentPage()->setWarningMessage("No se puede editar porque ya fue editado por un empleado"); 
		}    

		 // si usuario es nivel 3 (JEFE) no permite editar las observaciones    

		/*if (CurrentUserLevel() =='3')  
		{                   
			$this->replica_resp->Visible = FALSE;
			$this->estado_resp->Visible = FALSE;
		}
		if (CurrentUserLevel() =='3' && $this->estado_resp->CurrentValue =='4' ) 
		{                   

		   /*$this->estado_resp->Visible = FALSE; 
		   $this->id_empleado->Visible = FALSE; 
		   $this->obs_resp->Visible = FALSE;    
		   CurrentPage()->setWarningMessage("No se puede editar"); 
		}   */       
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
if (!isset($respuesta_edit)) $respuesta_edit = new crespuesta_edit();

// Page init
$respuesta_edit->Page_Init();

// Page main
$respuesta_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$respuesta_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var respuesta_edit = new ew_Page("respuesta_edit");
respuesta_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = respuesta_edit.PageID; // For backward compatibility

// Form object
var frespuestaedit = new ew_Form("frespuestaedit");

// Validate form
frespuestaedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_empleado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($respuesta->id_empleado->FldCaption()) ?>");

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
frespuestaedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frespuestaedit.ValidateRequired = true;
<?php } else { ?>
frespuestaedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frespuestaedit.Lists["x_id_empleado"] = {"LinkField":"x_id_empleado","Ajax":null,"AutoFill":false,"DisplayFields":["x_dociden_empleado","x_nomb_empleado","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frespuestaedit.Lists["x_estado_resp"] = {"LinkField":"x_id_x_estado_respuesta","Ajax":null,"AutoFill":false,"DisplayFields":["x_estado_respuesta","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
$(function(){
	var mi_sesion = <?php echo CurrentUserLevel();?>;
	if(mi_sesion == 3 && $("#x_estado_resp").val()== "1"){
		$("#x_estado_resp").attr('readonly', true);   
		$("#x_obs_resp").attr('readonly', true);

		//$("#btnAction").attr('disabled', true);
	}         
	if(mi_sesion == 2 && $("#x_estado_resp").val()== "1" ){ 
		$("#x_estado_resp").attr('readonly', true); 
		$("#x_obs_resp").attr('readonly', true);
		 $("#x_id_empleado").attr('readonly', true); 

	   // $("#x_id_empleado").attr('disabled', true);  
	   //$("#x_id_empleado").css("visibility", "hidden");

	}                 
	else                    
	{                                                              
		 if(mi_sesion == 2 && $("#x_estado_resp").val() != "1" ){ 
			 $("#btnAction").attr('disabled', true); 
		 }
	}               
	if(mi_sesion == 3 && $("#x_estado_resp").val()== "2" ){       
		$("#x_estado_resp").attr('disabled', false); 
		$("#x_id_empleado").attr('readonly', true);               
		$("#x_replica_resp").attr('readonly', true); 
		$("#btnAction").attr('disabled', false);
	}          
	if(mi_sesion == 3 &&( $("#x_estado_resp").val()== "4" || $("#x_estado_resp").val()== "3"  )){       
		$("#x_estado_resp").attr('disabled', true); 
		$("#x_obs_resp").attr('readonly', true);                
		$("#x_replica_resp").attr('readonly', true);
		$("#x_id_empleado").attr('readonly', true); 
		$("#btnAction").attr('disabled', true);
	}            
});                 
</script>
<?php $Breadcrumb->Render(); ?>
<?php $respuesta_edit->ShowPageHeader(); ?>
<?php
$respuesta_edit->ShowMessage();
?>
<form name="frespuestaedit" id="frespuestaedit" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="respuesta">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewGrid"><tr><td>
<table id="tbl_respuestaedit" class="table table-bordered table-striped">
<?php if ($respuesta->id_empleado->Visible) { // id_empleado ?>
	<tr id="r_id_empleado">
		<td><span id="elh_respuesta_id_empleado"><?php echo $respuesta->id_empleado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $respuesta->id_empleado->CellAttributes() ?>>
<span id="el_respuesta_id_empleado" class="control-group">
<select data-field="x_id_empleado" id="x_id_empleado" name="x_id_empleado"<?php echo $respuesta->id_empleado->EditAttributes() ?>>
<?php
if (is_array($respuesta->id_empleado->EditValue)) {
	$arwrk = $respuesta->id_empleado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($respuesta->id_empleado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$respuesta->id_empleado) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
frespuestaedit.Lists["x_id_empleado"].Options = <?php echo (is_array($respuesta->id_empleado->EditValue)) ? ew_ArrayToJson($respuesta->id_empleado->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $respuesta->id_empleado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($respuesta->obs_resp->Visible) { // obs_resp ?>
	<tr id="r_obs_resp">
		<td><span id="elh_respuesta_obs_resp"><?php echo $respuesta->obs_resp->FldCaption() ?></span></td>
		<td<?php echo $respuesta->obs_resp->CellAttributes() ?>>
<span id="el_respuesta_obs_resp" class="control-group">
<textarea data-field="x_obs_resp" name="x_obs_resp" id="x_obs_resp" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($respuesta->obs_resp->PlaceHolder) ?>"<?php echo $respuesta->obs_resp->EditAttributes() ?>><?php echo $respuesta->obs_resp->EditValue ?></textarea>
</span>
<?php echo $respuesta->obs_resp->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($respuesta->estado_resp->Visible) { // estado_resp ?>
	<tr id="r_estado_resp">
		<td><span id="elh_respuesta_estado_resp"><?php echo $respuesta->estado_resp->FldCaption() ?></span></td>
		<td<?php echo $respuesta->estado_resp->CellAttributes() ?>>
<span id="el_respuesta_estado_resp" class="control-group">
<select data-field="x_estado_resp" id="x_estado_resp" name="x_estado_resp"<?php echo $respuesta->estado_resp->EditAttributes() ?>>
<?php
if (is_array($respuesta->estado_resp->EditValue)) {
	$arwrk = $respuesta->estado_resp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($respuesta->estado_resp->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
frespuestaedit.Lists["x_estado_resp"].Options = <?php echo (is_array($respuesta->estado_resp->EditValue)) ? ew_ArrayToJson($respuesta->estado_resp->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $respuesta->estado_resp->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($respuesta->replica_resp->Visible) { // replica_resp ?>
	<tr id="r_replica_resp">
		<td><span id="elh_respuesta_replica_resp"><?php echo $respuesta->replica_resp->FldCaption() ?></span></td>
		<td<?php echo $respuesta->replica_resp->CellAttributes() ?>>
<span id="el_respuesta_replica_resp" class="control-group">
<textarea data-field="x_replica_resp" name="x_replica_resp" id="x_replica_resp" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($respuesta->replica_resp->PlaceHolder) ?>"<?php echo $respuesta->replica_resp->EditAttributes() ?>><?php echo $respuesta->replica_resp->EditValue ?></textarea>
</span>
<?php echo $respuesta->replica_resp->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_id_respuesta" name="x_id_respuesta" id="x_id_respuesta" value="<?php echo ew_HtmlEncode($respuesta->id_respuesta->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
frespuestaedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$respuesta_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$respuesta_edit->Page_Terminate();
?>
