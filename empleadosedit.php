<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "empleadosinfo.php" ?>
<?php include_once "v_usuariosinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$empleados_edit = NULL; // Initialize page object first

class cempleados_edit extends cempleados {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'empleados';

	// Page object name
	var $PageObjName = 'empleados_edit';

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

		// Table object (empleados)
		if (!isset($GLOBALS["empleados"]) || get_class($GLOBALS["empleados"]) == "cempleados") {
			$GLOBALS["empleados"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["empleados"];
		}

		// Table object (v_usuarios)
		if (!isset($GLOBALS['v_usuarios'])) $GLOBALS['v_usuarios'] = new cv_usuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'empleados', TRUE);

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
			$this->Page_Terminate("empleadoslist.php");
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
		if (@$_GET["id_empleado"] <> "") {
			$this->id_empleado->setQueryStringValue($_GET["id_empleado"]);
		}

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
		if ($this->id_empleado->CurrentValue == "")
			$this->Page_Terminate("empleadoslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("empleadoslist.php"); // No matching record, return to list
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
		if (!$this->dociden_empleado->FldIsDetailKey) {
			$this->dociden_empleado->setFormValue($objForm->GetValue("x_dociden_empleado"));
		}
		if (!$this->nomb_empleado->FldIsDetailKey) {
			$this->nomb_empleado->setFormValue($objForm->GetValue("x_nomb_empleado"));
		}
		if (!$this->apell_empleado->FldIsDetailKey) {
			$this->apell_empleado->setFormValue($objForm->GetValue("x_apell_empleado"));
		}
		if (!$this->telf_empleado->FldIsDetailKey) {
			$this->telf_empleado->setFormValue($objForm->GetValue("x_telf_empleado"));
		}
		if (!$this->email_empleado->FldIsDetailKey) {
			$this->email_empleado->setFormValue($objForm->GetValue("x_email_empleado"));
		}
		if (!$this->st_empleado_p->FldIsDetailKey) {
			$this->st_empleado_p->setFormValue($objForm->GetValue("x_st_empleado_p"));
		}
		if (!$this->pass_empleado->FldIsDetailKey) {
			$this->pass_empleado->setFormValue($objForm->GetValue("x_pass_empleado"));
		}
		if (!$this->login_empleado->FldIsDetailKey) {
			$this->login_empleado->setFormValue($objForm->GetValue("x_login_empleado"));
		}
		if (!$this->id_perfil->FldIsDetailKey) {
			$this->id_perfil->setFormValue($objForm->GetValue("x_id_perfil"));
		}
		if (!$this->id_empleado->FldIsDetailKey)
			$this->id_empleado->setFormValue($objForm->GetValue("x_id_empleado"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_empleado->CurrentValue = $this->id_empleado->FormValue;
		$this->dociden_empleado->CurrentValue = $this->dociden_empleado->FormValue;
		$this->nomb_empleado->CurrentValue = $this->nomb_empleado->FormValue;
		$this->apell_empleado->CurrentValue = $this->apell_empleado->FormValue;
		$this->telf_empleado->CurrentValue = $this->telf_empleado->FormValue;
		$this->email_empleado->CurrentValue = $this->email_empleado->FormValue;
		$this->st_empleado_p->CurrentValue = $this->st_empleado_p->FormValue;
		$this->pass_empleado->CurrentValue = $this->pass_empleado->FormValue;
		$this->login_empleado->CurrentValue = $this->login_empleado->FormValue;
		$this->id_perfil->CurrentValue = $this->id_perfil->FormValue;
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
		$this->id_empleado->setDbValue($rs->fields('id_empleado'));
		$this->dociden_empleado->setDbValue($rs->fields('dociden_empleado'));
		$this->nomb_empleado->setDbValue($rs->fields('nomb_empleado'));
		$this->apell_empleado->setDbValue($rs->fields('apell_empleado'));
		$this->telf_empleado->setDbValue($rs->fields('telf_empleado'));
		$this->email_empleado->setDbValue($rs->fields('email_empleado'));
		$this->st_empleado_p->setDbValue($rs->fields('st_empleado_p'));
		$this->pass_empleado->setDbValue($rs->fields('pass_empleado'));
		$this->login_empleado->setDbValue($rs->fields('login_empleado'));
		$this->id_perfil->setDbValue($rs->fields('id_perfil'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_empleado->DbValue = $row['id_empleado'];
		$this->dociden_empleado->DbValue = $row['dociden_empleado'];
		$this->nomb_empleado->DbValue = $row['nomb_empleado'];
		$this->apell_empleado->DbValue = $row['apell_empleado'];
		$this->telf_empleado->DbValue = $row['telf_empleado'];
		$this->email_empleado->DbValue = $row['email_empleado'];
		$this->st_empleado_p->DbValue = $row['st_empleado_p'];
		$this->pass_empleado->DbValue = $row['pass_empleado'];
		$this->login_empleado->DbValue = $row['login_empleado'];
		$this->id_perfil->DbValue = $row['id_perfil'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_empleado
		// dociden_empleado
		// nomb_empleado
		// apell_empleado
		// telf_empleado
		// email_empleado
		// st_empleado_p
		// pass_empleado
		// login_empleado
		// id_perfil

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// dociden_empleado
			$this->dociden_empleado->ViewValue = $this->dociden_empleado->CurrentValue;
			$this->dociden_empleado->ViewCustomAttributes = "";

			// nomb_empleado
			$this->nomb_empleado->ViewValue = $this->nomb_empleado->CurrentValue;
			$this->nomb_empleado->ViewCustomAttributes = "";

			// apell_empleado
			$this->apell_empleado->ViewValue = $this->apell_empleado->CurrentValue;
			$this->apell_empleado->ViewCustomAttributes = "";

			// telf_empleado
			$this->telf_empleado->ViewValue = $this->telf_empleado->CurrentValue;
			$this->telf_empleado->ViewCustomAttributes = "";

			// email_empleado
			$this->email_empleado->ViewValue = $this->email_empleado->CurrentValue;
			$this->email_empleado->ViewCustomAttributes = "";

			// st_empleado_p
			if (ew_ConvertToBool($this->st_empleado_p->CurrentValue)) {
				$this->st_empleado_p->ViewValue = $this->st_empleado_p->FldTagCaption(2) <> "" ? $this->st_empleado_p->FldTagCaption(2) : "1";
			} else {
				$this->st_empleado_p->ViewValue = $this->st_empleado_p->FldTagCaption(1) <> "" ? $this->st_empleado_p->FldTagCaption(1) : "0";
			}
			$this->st_empleado_p->ViewCustomAttributes = "";

			// pass_empleado
			$this->pass_empleado->ViewValue = "********";
			$this->pass_empleado->ViewCustomAttributes = "";

			// login_empleado
			$this->login_empleado->ViewValue = $this->login_empleado->CurrentValue;
			$this->login_empleado->ViewCustomAttributes = "";

			// id_perfil
			if (strval($this->id_perfil->CurrentValue) <> "") {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->id_perfil->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_perfil, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_perfil->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_perfil->ViewValue = $this->id_perfil->CurrentValue;
				}
			} else {
				$this->id_perfil->ViewValue = NULL;
			}
			$this->id_perfil->ViewCustomAttributes = "";

			// dociden_empleado
			$this->dociden_empleado->LinkCustomAttributes = "";
			$this->dociden_empleado->HrefValue = "";
			$this->dociden_empleado->TooltipValue = "";

			// nomb_empleado
			$this->nomb_empleado->LinkCustomAttributes = "";
			$this->nomb_empleado->HrefValue = "";
			$this->nomb_empleado->TooltipValue = "";

			// apell_empleado
			$this->apell_empleado->LinkCustomAttributes = "";
			$this->apell_empleado->HrefValue = "";
			$this->apell_empleado->TooltipValue = "";

			// telf_empleado
			$this->telf_empleado->LinkCustomAttributes = "";
			$this->telf_empleado->HrefValue = "";
			$this->telf_empleado->TooltipValue = "";

			// email_empleado
			$this->email_empleado->LinkCustomAttributes = "";
			$this->email_empleado->HrefValue = "";
			$this->email_empleado->TooltipValue = "";

			// st_empleado_p
			$this->st_empleado_p->LinkCustomAttributes = "";
			$this->st_empleado_p->HrefValue = "";
			$this->st_empleado_p->TooltipValue = "";

			// pass_empleado
			$this->pass_empleado->LinkCustomAttributes = "";
			$this->pass_empleado->HrefValue = "";
			$this->pass_empleado->TooltipValue = "";

			// login_empleado
			$this->login_empleado->LinkCustomAttributes = "";
			$this->login_empleado->HrefValue = "";
			$this->login_empleado->TooltipValue = "";

			// id_perfil
			$this->id_perfil->LinkCustomAttributes = "";
			$this->id_perfil->HrefValue = "";
			$this->id_perfil->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// dociden_empleado
			$this->dociden_empleado->EditCustomAttributes = "";
			$this->dociden_empleado->EditValue = ew_HtmlEncode($this->dociden_empleado->CurrentValue);
			$this->dociden_empleado->PlaceHolder = ew_RemoveHtml($this->dociden_empleado->FldCaption());

			// nomb_empleado
			$this->nomb_empleado->EditCustomAttributes = "";
			$this->nomb_empleado->EditValue = ew_HtmlEncode($this->nomb_empleado->CurrentValue);
			$this->nomb_empleado->PlaceHolder = ew_RemoveHtml($this->nomb_empleado->FldCaption());

			// apell_empleado
			$this->apell_empleado->EditCustomAttributes = "";
			$this->apell_empleado->EditValue = ew_HtmlEncode($this->apell_empleado->CurrentValue);
			$this->apell_empleado->PlaceHolder = ew_RemoveHtml($this->apell_empleado->FldCaption());

			// telf_empleado
			$this->telf_empleado->EditCustomAttributes = "";
			$this->telf_empleado->EditValue = ew_HtmlEncode($this->telf_empleado->CurrentValue);
			$this->telf_empleado->PlaceHolder = ew_RemoveHtml($this->telf_empleado->FldCaption());

			// email_empleado
			$this->email_empleado->EditCustomAttributes = "";
			$this->email_empleado->EditValue = ew_HtmlEncode($this->email_empleado->CurrentValue);
			$this->email_empleado->PlaceHolder = ew_RemoveHtml($this->email_empleado->FldCaption());

			// st_empleado_p
			$this->st_empleado_p->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->st_empleado_p->FldTagValue(1), $this->st_empleado_p->FldTagCaption(1) <> "" ? $this->st_empleado_p->FldTagCaption(1) : $this->st_empleado_p->FldTagValue(1));
			$arwrk[] = array($this->st_empleado_p->FldTagValue(2), $this->st_empleado_p->FldTagCaption(2) <> "" ? $this->st_empleado_p->FldTagCaption(2) : $this->st_empleado_p->FldTagValue(2));
			$this->st_empleado_p->EditValue = $arwrk;

			// pass_empleado
			$this->pass_empleado->EditCustomAttributes = "";
			$this->pass_empleado->EditValue = ew_HtmlEncode($this->pass_empleado->CurrentValue);

			// login_empleado
			$this->login_empleado->EditCustomAttributes = "";
			$this->login_empleado->EditValue = ew_HtmlEncode($this->login_empleado->CurrentValue);
			$this->login_empleado->PlaceHolder = ew_RemoveHtml($this->login_empleado->FldCaption());

			// id_perfil
			$this->id_perfil->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_perfil, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_perfil->EditValue = $arwrk;

			// Edit refer script
			// dociden_empleado

			$this->dociden_empleado->HrefValue = "";

			// nomb_empleado
			$this->nomb_empleado->HrefValue = "";

			// apell_empleado
			$this->apell_empleado->HrefValue = "";

			// telf_empleado
			$this->telf_empleado->HrefValue = "";

			// email_empleado
			$this->email_empleado->HrefValue = "";

			// st_empleado_p
			$this->st_empleado_p->HrefValue = "";

			// pass_empleado
			$this->pass_empleado->HrefValue = "";

			// login_empleado
			$this->login_empleado->HrefValue = "";

			// id_perfil
			$this->id_perfil->HrefValue = "";
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
		if (!$this->dociden_empleado->FldIsDetailKey && !is_null($this->dociden_empleado->FormValue) && $this->dociden_empleado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dociden_empleado->FldCaption());
		}
		if (!$this->nomb_empleado->FldIsDetailKey && !is_null($this->nomb_empleado->FormValue) && $this->nomb_empleado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nomb_empleado->FldCaption());
		}
		if (!$this->apell_empleado->FldIsDetailKey && !is_null($this->apell_empleado->FormValue) && $this->apell_empleado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->apell_empleado->FldCaption());
		}
		if (!ew_CheckEmail($this->email_empleado->FormValue)) {
			ew_AddMessage($gsFormError, $this->email_empleado->FldErrMsg());
		}
		if ($this->st_empleado_p->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->st_empleado_p->FldCaption());
		}
		if (!$this->id_perfil->FldIsDetailKey && !is_null($this->id_perfil->FormValue) && $this->id_perfil->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id_perfil->FldCaption());
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
		if ($this->dociden_empleado->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`dociden_empleado` = '" . ew_AdjustSql($this->dociden_empleado->CurrentValue) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->dociden_empleado->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->dociden_empleado->CurrentValue, $sIdxErrMsg);
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

			// dociden_empleado
			$this->dociden_empleado->SetDbValueDef($rsnew, $this->dociden_empleado->CurrentValue, NULL, $this->dociden_empleado->ReadOnly);

			// nomb_empleado
			$this->nomb_empleado->SetDbValueDef($rsnew, $this->nomb_empleado->CurrentValue, "", $this->nomb_empleado->ReadOnly);

			// apell_empleado
			$this->apell_empleado->SetDbValueDef($rsnew, $this->apell_empleado->CurrentValue, "", $this->apell_empleado->ReadOnly);

			// telf_empleado
			$this->telf_empleado->SetDbValueDef($rsnew, $this->telf_empleado->CurrentValue, NULL, $this->telf_empleado->ReadOnly);

			// email_empleado
			$this->email_empleado->SetDbValueDef($rsnew, $this->email_empleado->CurrentValue, NULL, $this->email_empleado->ReadOnly);

			// st_empleado_p
			$this->st_empleado_p->SetDbValueDef($rsnew, ((strval($this->st_empleado_p->CurrentValue) == "1") ? "1" : "0"), 0, $this->st_empleado_p->ReadOnly);

			// pass_empleado
			$this->pass_empleado->SetDbValueDef($rsnew, $this->pass_empleado->CurrentValue, NULL, $this->pass_empleado->ReadOnly);

			// login_empleado
			$this->login_empleado->SetDbValueDef($rsnew, $this->login_empleado->CurrentValue, NULL, $this->login_empleado->ReadOnly);

			// id_perfil
			$this->id_perfil->SetDbValueDef($rsnew, $this->id_perfil->CurrentValue, 0, $this->id_perfil->ReadOnly);

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "empleadoslist.php", $this->TableVar, TRUE);
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
if (!isset($empleados_edit)) $empleados_edit = new cempleados_edit();

// Page init
$empleados_edit->Page_Init();

// Page main
$empleados_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empleados_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var empleados_edit = new ew_Page("empleados_edit");
empleados_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = empleados_edit.PageID; // For backward compatibility

// Form object
var fempleadosedit = new ew_Form("fempleadosedit");

// Validate form
fempleadosedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_dociden_empleado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($empleados->dociden_empleado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nomb_empleado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($empleados->nomb_empleado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_apell_empleado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($empleados->apell_empleado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_email_empleado");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($empleados->email_empleado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_st_empleado_p");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($empleados->st_empleado_p->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id_perfil");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($empleados->id_perfil->FldCaption()) ?>");

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
fempleadosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempleadosedit.ValidateRequired = true;
<?php } else { ?>
fempleadosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fempleadosedit.Lists["x_id_perfil"] = {"LinkField":"x_userlevelid","Ajax":null,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $empleados_edit->ShowPageHeader(); ?>
<?php
$empleados_edit->ShowMessage();
?>
<form name="fempleadosedit" id="fempleadosedit" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="empleados">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewGrid"><tr><td>
<table id="tbl_empleadosedit" class="table table-bordered table-striped">
<?php if ($empleados->dociden_empleado->Visible) { // dociden_empleado ?>
	<tr id="r_dociden_empleado">
		<td><span id="elh_empleados_dociden_empleado"><?php echo $empleados->dociden_empleado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $empleados->dociden_empleado->CellAttributes() ?>>
<span id="el_empleados_dociden_empleado" class="control-group">
<input type="text" data-field="x_dociden_empleado" name="x_dociden_empleado" id="x_dociden_empleado" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($empleados->dociden_empleado->PlaceHolder) ?>" value="<?php echo $empleados->dociden_empleado->EditValue ?>"<?php echo $empleados->dociden_empleado->EditAttributes() ?>>
</span>
<?php echo $empleados->dociden_empleado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($empleados->nomb_empleado->Visible) { // nomb_empleado ?>
	<tr id="r_nomb_empleado">
		<td><span id="elh_empleados_nomb_empleado"><?php echo $empleados->nomb_empleado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $empleados->nomb_empleado->CellAttributes() ?>>
<span id="el_empleados_nomb_empleado" class="control-group">
<input type="text" data-field="x_nomb_empleado" name="x_nomb_empleado" id="x_nomb_empleado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($empleados->nomb_empleado->PlaceHolder) ?>" value="<?php echo $empleados->nomb_empleado->EditValue ?>"<?php echo $empleados->nomb_empleado->EditAttributes() ?>>
</span>
<?php echo $empleados->nomb_empleado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($empleados->apell_empleado->Visible) { // apell_empleado ?>
	<tr id="r_apell_empleado">
		<td><span id="elh_empleados_apell_empleado"><?php echo $empleados->apell_empleado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $empleados->apell_empleado->CellAttributes() ?>>
<span id="el_empleados_apell_empleado" class="control-group">
<input type="text" data-field="x_apell_empleado" name="x_apell_empleado" id="x_apell_empleado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($empleados->apell_empleado->PlaceHolder) ?>" value="<?php echo $empleados->apell_empleado->EditValue ?>"<?php echo $empleados->apell_empleado->EditAttributes() ?>>
</span>
<?php echo $empleados->apell_empleado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($empleados->telf_empleado->Visible) { // telf_empleado ?>
	<tr id="r_telf_empleado">
		<td><span id="elh_empleados_telf_empleado"><?php echo $empleados->telf_empleado->FldCaption() ?></span></td>
		<td<?php echo $empleados->telf_empleado->CellAttributes() ?>>
<span id="el_empleados_telf_empleado" class="control-group">
<input type="text" data-field="x_telf_empleado" name="x_telf_empleado" id="x_telf_empleado" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($empleados->telf_empleado->PlaceHolder) ?>" value="<?php echo $empleados->telf_empleado->EditValue ?>"<?php echo $empleados->telf_empleado->EditAttributes() ?>>
</span>
<?php echo $empleados->telf_empleado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($empleados->email_empleado->Visible) { // email_empleado ?>
	<tr id="r_email_empleado">
		<td><span id="elh_empleados_email_empleado"><?php echo $empleados->email_empleado->FldCaption() ?></span></td>
		<td<?php echo $empleados->email_empleado->CellAttributes() ?>>
<span id="el_empleados_email_empleado" class="control-group">
<input type="text" data-field="x_email_empleado" name="x_email_empleado" id="x_email_empleado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($empleados->email_empleado->PlaceHolder) ?>" value="<?php echo $empleados->email_empleado->EditValue ?>"<?php echo $empleados->email_empleado->EditAttributes() ?>>
</span>
<?php echo $empleados->email_empleado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($empleados->st_empleado_p->Visible) { // st_empleado_p ?>
	<tr id="r_st_empleado_p">
		<td><span id="elh_empleados_st_empleado_p"><?php echo $empleados->st_empleado_p->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $empleados->st_empleado_p->CellAttributes() ?>>
<span id="el_empleados_st_empleado_p" class="control-group">
<div id="tp_x_st_empleado_p" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_st_empleado_p" id="x_st_empleado_p" value="{value}"<?php echo $empleados->st_empleado_p->EditAttributes() ?>></div>
<div id="dsl_x_st_empleado_p" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $empleados->st_empleado_p->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($empleados->st_empleado_p->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_st_empleado_p" name="x_st_empleado_p" id="x_st_empleado_p_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $empleados->st_empleado_p->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $empleados->st_empleado_p->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($empleados->pass_empleado->Visible) { // pass_empleado ?>
	<tr id="r_pass_empleado">
		<td><span id="elh_empleados_pass_empleado"><?php echo $empleados->pass_empleado->FldCaption() ?></span></td>
		<td<?php echo $empleados->pass_empleado->CellAttributes() ?>>
<span id="el_empleados_pass_empleado" class="control-group">
<input type="password" data-field="x_pass_empleado" name="x_pass_empleado" id="x_pass_empleado" value="<?php echo $empleados->pass_empleado->EditValue ?>" size="30" maxlength="250"<?php echo $empleados->pass_empleado->EditAttributes() ?>>
</span>
<?php echo $empleados->pass_empleado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($empleados->login_empleado->Visible) { // login_empleado ?>
	<tr id="r_login_empleado">
		<td><span id="elh_empleados_login_empleado"><?php echo $empleados->login_empleado->FldCaption() ?></span></td>
		<td<?php echo $empleados->login_empleado->CellAttributes() ?>>
<span id="el_empleados_login_empleado" class="control-group">
<input type="text" data-field="x_login_empleado" name="x_login_empleado" id="x_login_empleado" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($empleados->login_empleado->PlaceHolder) ?>" value="<?php echo $empleados->login_empleado->EditValue ?>"<?php echo $empleados->login_empleado->EditAttributes() ?>>
</span>
<?php echo $empleados->login_empleado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($empleados->id_perfil->Visible) { // id_perfil ?>
	<tr id="r_id_perfil">
		<td><span id="elh_empleados_id_perfil"><?php echo $empleados->id_perfil->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $empleados->id_perfil->CellAttributes() ?>>
<span id="el_empleados_id_perfil" class="control-group">
<select data-field="x_id_perfil" id="x_id_perfil" name="x_id_perfil"<?php echo $empleados->id_perfil->EditAttributes() ?>>
<?php
if (is_array($empleados->id_perfil->EditValue)) {
	$arwrk = $empleados->id_perfil->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($empleados->id_perfil->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fempleadosedit.Lists["x_id_perfil"].Options = <?php echo (is_array($empleados->id_perfil->EditValue)) ? ew_ArrayToJson($empleados->id_perfil->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $empleados->id_perfil->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_id_empleado" name="x_id_empleado" id="x_id_empleado" value="<?php echo ew_HtmlEncode($empleados->id_empleado->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fempleadosedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$empleados_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empleados_edit->Page_Terminate();
?>
