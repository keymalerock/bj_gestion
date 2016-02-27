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

$matricula_delete = NULL; // Initialize page object first

class cmatricula_delete extends cmatricula {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'matricula';

	// Page object name
	var $PageObjName = 'matricula_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("matriculalist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id_matricula->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("matriculalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in matricula class, matriculainfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
		$conn->BeginTrans();

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
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "matriculalist.php", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, ew_CurrentUrl());
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($matricula_delete)) $matricula_delete = new cmatricula_delete();

// Page init
$matricula_delete->Page_Init();

// Page main
$matricula_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$matricula_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var matricula_delete = new ew_Page("matricula_delete");
matricula_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = matricula_delete.PageID; // For backward compatibility

// Form object
var fmatriculadelete = new ew_Form("fmatriculadelete");

// Form_CustomValidate event
fmatriculadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmatriculadelete.ValidateRequired = true;
<?php } else { ?>
fmatriculadelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmatriculadelete.Lists["x_id_afiliado"] = {"LinkField":"x_id_afiliado","Ajax":true,"AutoFill":false,"DisplayFields":["x_dociden_afiliado","x_apell_afiliado","x_nomb_afiliado",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmatriculadelete.Lists["x_id_plan"] = {"LinkField":"x_id_plan","Ajax":null,"AutoFill":false,"DisplayFields":["x_tipo_plan","x_time_plan","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmatriculadelete.Lists["x_id_empleado"] = {"LinkField":"x_id_empleado","Ajax":null,"AutoFill":false,"DisplayFields":["x_dociden_empleado","x_nomb_empleado","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($matricula_delete->Recordset = $matricula_delete->LoadRecordset())
	$matricula_deleteTotalRecs = $matricula_delete->Recordset->RecordCount(); // Get record count
if ($matricula_deleteTotalRecs <= 0) { // No record found, exit
	if ($matricula_delete->Recordset)
		$matricula_delete->Recordset->Close();
	$matricula_delete->Page_Terminate("matriculalist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $matricula_delete->ShowPageHeader(); ?>
<?php
$matricula_delete->ShowMessage();
?>
<form name="fmatriculadelete" id="fmatriculadelete" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="matricula">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($matricula_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_matriculadelete" class="ewTable ewTableSeparate">
<?php echo $matricula->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($matricula->id_matricula->Visible) { // id_matricula ?>
		<td><span id="elh_matricula_id_matricula" class="matricula_id_matricula"><?php echo $matricula->id_matricula->FldCaption() ?></span></td>
<?php } ?>
<?php if ($matricula->id_afiliado->Visible) { // id_afiliado ?>
		<td><span id="elh_matricula_id_afiliado" class="matricula_id_afiliado"><?php echo $matricula->id_afiliado->FldCaption() ?></span></td>
<?php } ?>
<?php if ($matricula->tipo_matri->Visible) { // tipo_matri ?>
		<td><span id="elh_matricula_tipo_matri" class="matricula_tipo_matri"><?php echo $matricula->tipo_matri->FldCaption() ?></span></td>
<?php } ?>
<?php if ($matricula->id_plan->Visible) { // id_plan ?>
		<td><span id="elh_matricula_id_plan" class="matricula_id_plan"><?php echo $matricula->id_plan->FldCaption() ?></span></td>
<?php } ?>
<?php if ($matricula->valor_matri->Visible) { // valor_matri ?>
		<td><span id="elh_matricula_valor_matri" class="matricula_valor_matri"><?php echo $matricula->valor_matri->FldCaption() ?></span></td>
<?php } ?>
<?php if ($matricula->valor_men_matri->Visible) { // valor_men_matri ?>
		<td><span id="elh_matricula_valor_men_matri" class="matricula_valor_men_matri"><?php echo $matricula->valor_men_matri->FldCaption() ?></span></td>
<?php } ?>
<?php if ($matricula->conv_matri->Visible) { // conv_matri ?>
		<td><span id="elh_matricula_conv_matri" class="matricula_conv_matri"><?php echo $matricula->conv_matri->FldCaption() ?></span></td>
<?php } ?>
<?php if ($matricula->id_empleado->Visible) { // id_empleado ?>
		<td><span id="elh_matricula_id_empleado" class="matricula_id_empleado"><?php echo $matricula->id_empleado->FldCaption() ?></span></td>
<?php } ?>
<?php if ($matricula->doc4_matri->Visible) { // doc4_matri ?>
		<td><span id="elh_matricula_doc4_matri" class="matricula_doc4_matri"><?php echo $matricula->doc4_matri->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$matricula_delete->RecCnt = 0;
$i = 0;
while (!$matricula_delete->Recordset->EOF) {
	$matricula_delete->RecCnt++;
	$matricula_delete->RowCnt++;

	// Set row properties
	$matricula->ResetAttrs();
	$matricula->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$matricula_delete->LoadRowValues($matricula_delete->Recordset);

	// Render row
	$matricula_delete->RenderRow();
?>
	<tr<?php echo $matricula->RowAttributes() ?>>
<?php if ($matricula->id_matricula->Visible) { // id_matricula ?>
		<td<?php echo $matricula->id_matricula->CellAttributes() ?>>
<span id="el<?php echo $matricula_delete->RowCnt ?>_matricula_id_matricula" class="control-group matricula_id_matricula">
<span<?php echo $matricula->id_matricula->ViewAttributes() ?>>
<?php echo $matricula->id_matricula->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($matricula->id_afiliado->Visible) { // id_afiliado ?>
		<td<?php echo $matricula->id_afiliado->CellAttributes() ?>>
<span id="el<?php echo $matricula_delete->RowCnt ?>_matricula_id_afiliado" class="control-group matricula_id_afiliado">
<span<?php echo $matricula->id_afiliado->ViewAttributes() ?>>
<?php echo $matricula->id_afiliado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($matricula->tipo_matri->Visible) { // tipo_matri ?>
		<td<?php echo $matricula->tipo_matri->CellAttributes() ?>>
<span id="el<?php echo $matricula_delete->RowCnt ?>_matricula_tipo_matri" class="control-group matricula_tipo_matri">
<span<?php echo $matricula->tipo_matri->ViewAttributes() ?>>
<?php echo $matricula->tipo_matri->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($matricula->id_plan->Visible) { // id_plan ?>
		<td<?php echo $matricula->id_plan->CellAttributes() ?>>
<span id="el<?php echo $matricula_delete->RowCnt ?>_matricula_id_plan" class="control-group matricula_id_plan">
<span<?php echo $matricula->id_plan->ViewAttributes() ?>>
<?php echo $matricula->id_plan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($matricula->valor_matri->Visible) { // valor_matri ?>
		<td<?php echo $matricula->valor_matri->CellAttributes() ?>>
<span id="el<?php echo $matricula_delete->RowCnt ?>_matricula_valor_matri" class="control-group matricula_valor_matri">
<span<?php echo $matricula->valor_matri->ViewAttributes() ?>>
<?php echo $matricula->valor_matri->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($matricula->valor_men_matri->Visible) { // valor_men_matri ?>
		<td<?php echo $matricula->valor_men_matri->CellAttributes() ?>>
<span id="el<?php echo $matricula_delete->RowCnt ?>_matricula_valor_men_matri" class="control-group matricula_valor_men_matri">
<span<?php echo $matricula->valor_men_matri->ViewAttributes() ?>>
<?php echo $matricula->valor_men_matri->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($matricula->conv_matri->Visible) { // conv_matri ?>
		<td<?php echo $matricula->conv_matri->CellAttributes() ?>>
<span id="el<?php echo $matricula_delete->RowCnt ?>_matricula_conv_matri" class="control-group matricula_conv_matri">
<span<?php echo $matricula->conv_matri->ViewAttributes() ?>>
<?php echo $matricula->conv_matri->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($matricula->id_empleado->Visible) { // id_empleado ?>
		<td<?php echo $matricula->id_empleado->CellAttributes() ?>>
<span id="el<?php echo $matricula_delete->RowCnt ?>_matricula_id_empleado" class="control-group matricula_id_empleado">
<span<?php echo $matricula->id_empleado->ViewAttributes() ?>>
<?php echo $matricula->id_empleado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($matricula->doc4_matri->Visible) { // doc4_matri ?>
		<td<?php echo $matricula->doc4_matri->CellAttributes() ?>>
<span id="el<?php echo $matricula_delete->RowCnt ?>_matricula_doc4_matri" class="control-group matricula_doc4_matri">
<span<?php echo $matricula->doc4_matri->ViewAttributes() ?>>
<?php echo $matricula->doc4_matri->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$matricula_delete->Recordset->MoveNext();
}
$matricula_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fmatriculadelete.Init();
</script>
<?php
$matricula_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$matricula_delete->Page_Terminate();
?>
