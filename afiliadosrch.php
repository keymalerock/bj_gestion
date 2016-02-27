<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "afiliadoinfo.php" ?>
<?php include_once "v_usuariosinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$afiliado_search = NULL; // Initialize page object first

class cafiliado_search extends cafiliado {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{CD332243-68AB-4368-BF10-F24F7E84F4D6}";

	// Table name
	var $TableName = 'afiliado';

	// Page object name
	var $PageObjName = 'afiliado_search';

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
			define("EW_PAGE_ID", 'search', TRUE);

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
		if (!$Security->CanSearch()) {
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

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$this->Page_Terminate("afiliadolist.php" . "?" . $sSrchStr); // Go to list page
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->dociden_afiliado); // dociden_afiliado
		$this->BuildSearchUrl($sSrchUrl, $this->apell_afiliado); // apell_afiliado
		$this->BuildSearchUrl($sSrchUrl, $this->nomb_afiliado); // nomb_afiliado
		$this->BuildSearchUrl($sSrchUrl, $this->email_afiliado); // email_afiliado
		$this->BuildSearchUrl($sSrchUrl, $this->cel_afiliado); // cel_afiliado
		$this->BuildSearchUrl($sSrchUrl, $this->genero_afiliado); // genero_afiliado
		$this->BuildSearchUrl($sSrchUrl, $this->fe_afiliado); // fe_afiliado
		$this->BuildSearchUrl($sSrchUrl, $this->talla_afiliado); // talla_afiliado
		$this->BuildSearchUrl($sSrchUrl, $this->peso_afiliado); // peso_afiliado
		$this->BuildSearchUrl($sSrchUrl, $this->altu_afiliado); // altu_afiliado
		$this->BuildSearchUrl($sSrchUrl, $this->localresdi_afiliado); // localresdi_afiliado
		$this->BuildSearchUrl($sSrchUrl, $this->telf_fijo_afiliado); // telf_fijo_afiliado
		$this->BuildSearchUrl($sSrchUrl, $this->st_afiliado); // st_afiliado
		$this->BuildSearchUrl($sSrchUrl, $this->st_notificado); // st_notificado
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// dociden_afiliado

		$this->dociden_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_dociden_afiliado"));
		$this->dociden_afiliado->AdvancedSearch->SearchOperator = $objForm->GetValue("z_dociden_afiliado");

		// apell_afiliado
		$this->apell_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_apell_afiliado"));
		$this->apell_afiliado->AdvancedSearch->SearchOperator = $objForm->GetValue("z_apell_afiliado");

		// nomb_afiliado
		$this->nomb_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nomb_afiliado"));
		$this->nomb_afiliado->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nomb_afiliado");

		// email_afiliado
		$this->email_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_email_afiliado"));
		$this->email_afiliado->AdvancedSearch->SearchOperator = $objForm->GetValue("z_email_afiliado");

		// cel_afiliado
		$this->cel_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_cel_afiliado"));
		$this->cel_afiliado->AdvancedSearch->SearchOperator = $objForm->GetValue("z_cel_afiliado");

		// genero_afiliado
		$this->genero_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_genero_afiliado"));
		$this->genero_afiliado->AdvancedSearch->SearchOperator = $objForm->GetValue("z_genero_afiliado");

		// fe_afiliado
		$this->fe_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_fe_afiliado"));
		$this->fe_afiliado->AdvancedSearch->SearchOperator = $objForm->GetValue("z_fe_afiliado");

		// talla_afiliado
		$this->talla_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_talla_afiliado"));
		$this->talla_afiliado->AdvancedSearch->SearchOperator = $objForm->GetValue("z_talla_afiliado");

		// peso_afiliado
		$this->peso_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_peso_afiliado"));
		$this->peso_afiliado->AdvancedSearch->SearchOperator = $objForm->GetValue("z_peso_afiliado");

		// altu_afiliado
		$this->altu_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_altu_afiliado"));
		$this->altu_afiliado->AdvancedSearch->SearchOperator = $objForm->GetValue("z_altu_afiliado");

		// localresdi_afiliado
		$this->localresdi_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_localresdi_afiliado"));
		$this->localresdi_afiliado->AdvancedSearch->SearchOperator = $objForm->GetValue("z_localresdi_afiliado");

		// telf_fijo_afiliado
		$this->telf_fijo_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_telf_fijo_afiliado"));
		$this->telf_fijo_afiliado->AdvancedSearch->SearchOperator = $objForm->GetValue("z_telf_fijo_afiliado");

		// st_afiliado
		$this->st_afiliado->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_st_afiliado"));
		$this->st_afiliado->AdvancedSearch->SearchOperator = $objForm->GetValue("z_st_afiliado");

		// st_notificado
		$this->st_notificado->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_st_notificado"));
		$this->st_notificado->AdvancedSearch->SearchOperator = $objForm->GetValue("z_st_notificado");
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
			if (strval($this->st_afiliado->CurrentValue) <> "") {
				switch ($this->st_afiliado->CurrentValue) {
					case $this->st_afiliado->FldTagValue(1):
						$this->st_afiliado->ViewValue = $this->st_afiliado->FldTagCaption(1) <> "" ? $this->st_afiliado->FldTagCaption(1) : $this->st_afiliado->CurrentValue;
						break;
					case $this->st_afiliado->FldTagValue(2):
						$this->st_afiliado->ViewValue = $this->st_afiliado->FldTagCaption(2) <> "" ? $this->st_afiliado->FldTagCaption(2) : $this->st_afiliado->CurrentValue;
						break;
					default:
						$this->st_afiliado->ViewValue = $this->st_afiliado->CurrentValue;
				}
			} else {
				$this->st_afiliado->ViewValue = NULL;
			}
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

			// st_afiliado
			$this->st_afiliado->LinkCustomAttributes = "";
			$this->st_afiliado->HrefValue = "";
			$this->st_afiliado->TooltipValue = "";

			// st_notificado
			$this->st_notificado->LinkCustomAttributes = "";
			$this->st_notificado->HrefValue = "";
			$this->st_notificado->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// dociden_afiliado
			$this->dociden_afiliado->EditCustomAttributes = "";
			$this->dociden_afiliado->EditValue = ew_HtmlEncode($this->dociden_afiliado->AdvancedSearch->SearchValue);
			$this->dociden_afiliado->PlaceHolder = ew_RemoveHtml($this->dociden_afiliado->FldCaption());

			// apell_afiliado
			$this->apell_afiliado->EditCustomAttributes = "";
			$this->apell_afiliado->EditValue = ew_HtmlEncode($this->apell_afiliado->AdvancedSearch->SearchValue);
			$this->apell_afiliado->PlaceHolder = ew_RemoveHtml($this->apell_afiliado->FldCaption());

			// nomb_afiliado
			$this->nomb_afiliado->EditCustomAttributes = "";
			$this->nomb_afiliado->EditValue = ew_HtmlEncode($this->nomb_afiliado->AdvancedSearch->SearchValue);
			$this->nomb_afiliado->PlaceHolder = ew_RemoveHtml($this->nomb_afiliado->FldCaption());

			// email_afiliado
			$this->email_afiliado->EditCustomAttributes = "";
			$this->email_afiliado->EditValue = ew_HtmlEncode($this->email_afiliado->AdvancedSearch->SearchValue);
			$this->email_afiliado->PlaceHolder = ew_RemoveHtml($this->email_afiliado->FldCaption());

			// cel_afiliado
			$this->cel_afiliado->EditCustomAttributes = "";
			$this->cel_afiliado->EditValue = ew_HtmlEncode($this->cel_afiliado->AdvancedSearch->SearchValue);
			$this->cel_afiliado->PlaceHolder = ew_RemoveHtml($this->cel_afiliado->FldCaption());

			// genero_afiliado
			$this->genero_afiliado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->genero_afiliado->FldTagValue(1), $this->genero_afiliado->FldTagCaption(1) <> "" ? $this->genero_afiliado->FldTagCaption(1) : $this->genero_afiliado->FldTagValue(1));
			$arwrk[] = array($this->genero_afiliado->FldTagValue(2), $this->genero_afiliado->FldTagCaption(2) <> "" ? $this->genero_afiliado->FldTagCaption(2) : $this->genero_afiliado->FldTagValue(2));
			$this->genero_afiliado->EditValue = $arwrk;

			// fe_afiliado
			$this->fe_afiliado->EditCustomAttributes = "";
			$this->fe_afiliado->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fe_afiliado->AdvancedSearch->SearchValue, 5), 5));
			$this->fe_afiliado->PlaceHolder = ew_RemoveHtml($this->fe_afiliado->FldCaption());

			// talla_afiliado
			$this->talla_afiliado->EditCustomAttributes = "";
			$this->talla_afiliado->EditValue = ew_HtmlEncode($this->talla_afiliado->AdvancedSearch->SearchValue);
			$this->talla_afiliado->PlaceHolder = ew_RemoveHtml($this->talla_afiliado->FldCaption());

			// peso_afiliado
			$this->peso_afiliado->EditCustomAttributes = "";
			$this->peso_afiliado->EditValue = ew_HtmlEncode($this->peso_afiliado->AdvancedSearch->SearchValue);
			$this->peso_afiliado->PlaceHolder = ew_RemoveHtml($this->peso_afiliado->FldCaption());

			// altu_afiliado
			$this->altu_afiliado->EditCustomAttributes = "";
			$this->altu_afiliado->EditValue = ew_HtmlEncode($this->altu_afiliado->AdvancedSearch->SearchValue);
			$this->altu_afiliado->PlaceHolder = ew_RemoveHtml($this->altu_afiliado->FldCaption());

			// localresdi_afiliado
			$this->localresdi_afiliado->EditCustomAttributes = "";
			$this->localresdi_afiliado->EditValue = $this->localresdi_afiliado->AdvancedSearch->SearchValue;
			$this->localresdi_afiliado->PlaceHolder = ew_RemoveHtml($this->localresdi_afiliado->FldCaption());

			// telf_fijo_afiliado
			$this->telf_fijo_afiliado->EditCustomAttributes = "";
			$this->telf_fijo_afiliado->EditValue = ew_HtmlEncode($this->telf_fijo_afiliado->AdvancedSearch->SearchValue);
			$this->telf_fijo_afiliado->PlaceHolder = ew_RemoveHtml($this->telf_fijo_afiliado->FldCaption());

			// st_afiliado
			$this->st_afiliado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->st_afiliado->FldTagValue(1), $this->st_afiliado->FldTagCaption(1) <> "" ? $this->st_afiliado->FldTagCaption(1) : $this->st_afiliado->FldTagValue(1));
			$arwrk[] = array($this->st_afiliado->FldTagValue(2), $this->st_afiliado->FldTagCaption(2) <> "" ? $this->st_afiliado->FldTagCaption(2) : $this->st_afiliado->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->st_afiliado->EditValue = $arwrk;

			// st_notificado
			$this->st_notificado->EditCustomAttributes = "";
			$this->st_notificado->EditValue = ew_HtmlEncode($this->st_notificado->AdvancedSearch->SearchValue);
			$this->st_notificado->PlaceHolder = ew_RemoveHtml($this->st_notificado->FldCaption());
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckDate($this->fe_afiliado->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fe_afiliado->FldErrMsg());
		}
		if (!ew_CheckNumber($this->altu_afiliado->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->altu_afiliado->FldErrMsg());
		}
		if (!ew_CheckInteger($this->st_notificado->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->st_notificado->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->dociden_afiliado->AdvancedSearch->Load();
		$this->apell_afiliado->AdvancedSearch->Load();
		$this->nomb_afiliado->AdvancedSearch->Load();
		$this->email_afiliado->AdvancedSearch->Load();
		$this->cel_afiliado->AdvancedSearch->Load();
		$this->genero_afiliado->AdvancedSearch->Load();
		$this->fe_afiliado->AdvancedSearch->Load();
		$this->talla_afiliado->AdvancedSearch->Load();
		$this->peso_afiliado->AdvancedSearch->Load();
		$this->altu_afiliado->AdvancedSearch->Load();
		$this->localresdi_afiliado->AdvancedSearch->Load();
		$this->telf_fijo_afiliado->AdvancedSearch->Load();
		$this->st_afiliado->AdvancedSearch->Load();
		$this->st_notificado->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "afiliadolist.php", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, ew_CurrentUrl());
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
if (!isset($afiliado_search)) $afiliado_search = new cafiliado_search();

// Page init
$afiliado_search->Page_Init();

// Page main
$afiliado_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$afiliado_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var afiliado_search = new ew_Page("afiliado_search");
afiliado_search.PageID = "search"; // Page ID
var EW_PAGE_ID = afiliado_search.PageID; // For backward compatibility

// Form object
var fafiliadosearch = new ew_Form("fafiliadosearch");

// Form_CustomValidate event
fafiliadosearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fafiliadosearch.ValidateRequired = true;
<?php } else { ?>
fafiliadosearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search
// Validate function for search

fafiliadosearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_fe_afiliado");
	if (elm && !ew_CheckDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($afiliado->fe_afiliado->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_altu_afiliado");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($afiliado->altu_afiliado->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_st_notificado");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($afiliado->st_notificado->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $afiliado_search->ShowPageHeader(); ?>
<?php
$afiliado_search->ShowMessage();
?>
<form name="fafiliadosearch" id="fafiliadosearch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="afiliado">
<input type="hidden" name="a_search" id="a_search" value="S">
<table class="ewGrid"><tr><td>
<table id="tbl_afiliadosearch" class="table table-bordered table-striped">
<?php if ($afiliado->dociden_afiliado->Visible) { // dociden_afiliado ?>
	<tr id="r_dociden_afiliado">
		<td><span id="elh_afiliado_dociden_afiliado"><?php echo $afiliado->dociden_afiliado->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_dociden_afiliado" id="z_dociden_afiliado" value="="></span></td>
		<td<?php echo $afiliado->dociden_afiliado->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_afiliado_dociden_afiliado" class="control-group">
<input type="text" data-field="x_dociden_afiliado" name="x_dociden_afiliado" id="x_dociden_afiliado" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($afiliado->dociden_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->dociden_afiliado->EditValue ?>"<?php echo $afiliado->dociden_afiliado->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($afiliado->apell_afiliado->Visible) { // apell_afiliado ?>
	<tr id="r_apell_afiliado">
		<td><span id="elh_afiliado_apell_afiliado"><?php echo $afiliado->apell_afiliado->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apell_afiliado" id="z_apell_afiliado" value="LIKE"></span></td>
		<td<?php echo $afiliado->apell_afiliado->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_afiliado_apell_afiliado" class="control-group">
<input type="text" data-field="x_apell_afiliado" name="x_apell_afiliado" id="x_apell_afiliado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($afiliado->apell_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->apell_afiliado->EditValue ?>"<?php echo $afiliado->apell_afiliado->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($afiliado->nomb_afiliado->Visible) { // nomb_afiliado ?>
	<tr id="r_nomb_afiliado">
		<td><span id="elh_afiliado_nomb_afiliado"><?php echo $afiliado->nomb_afiliado->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nomb_afiliado" id="z_nomb_afiliado" value="LIKE"></span></td>
		<td<?php echo $afiliado->nomb_afiliado->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_afiliado_nomb_afiliado" class="control-group">
<input type="text" data-field="x_nomb_afiliado" name="x_nomb_afiliado" id="x_nomb_afiliado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($afiliado->nomb_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->nomb_afiliado->EditValue ?>"<?php echo $afiliado->nomb_afiliado->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($afiliado->email_afiliado->Visible) { // email_afiliado ?>
	<tr id="r_email_afiliado">
		<td><span id="elh_afiliado_email_afiliado"><?php echo $afiliado->email_afiliado->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_email_afiliado" id="z_email_afiliado" value="LIKE"></span></td>
		<td<?php echo $afiliado->email_afiliado->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_afiliado_email_afiliado" class="control-group">
<input type="text" data-field="x_email_afiliado" name="x_email_afiliado" id="x_email_afiliado" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($afiliado->email_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->email_afiliado->EditValue ?>"<?php echo $afiliado->email_afiliado->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($afiliado->cel_afiliado->Visible) { // cel_afiliado ?>
	<tr id="r_cel_afiliado">
		<td><span id="elh_afiliado_cel_afiliado"><?php echo $afiliado->cel_afiliado->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_cel_afiliado" id="z_cel_afiliado" value="="></span></td>
		<td<?php echo $afiliado->cel_afiliado->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_afiliado_cel_afiliado" class="control-group">
<input type="text" data-field="x_cel_afiliado" name="x_cel_afiliado" id="x_cel_afiliado" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($afiliado->cel_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->cel_afiliado->EditValue ?>"<?php echo $afiliado->cel_afiliado->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($afiliado->genero_afiliado->Visible) { // genero_afiliado ?>
	<tr id="r_genero_afiliado">
		<td><span id="elh_afiliado_genero_afiliado"><?php echo $afiliado->genero_afiliado->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_genero_afiliado" id="z_genero_afiliado" value="="></span></td>
		<td<?php echo $afiliado->genero_afiliado->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_afiliado_genero_afiliado" class="control-group">
<div id="tp_x_genero_afiliado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_genero_afiliado" id="x_genero_afiliado" value="{value}"<?php echo $afiliado->genero_afiliado->EditAttributes() ?>></div>
<div id="dsl_x_genero_afiliado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $afiliado->genero_afiliado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($afiliado->genero_afiliado->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
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
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($afiliado->fe_afiliado->Visible) { // fe_afiliado ?>
	<tr id="r_fe_afiliado">
		<td><span id="elh_afiliado_fe_afiliado"><?php echo $afiliado->fe_afiliado->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_fe_afiliado" id="z_fe_afiliado" value="="></span></td>
		<td<?php echo $afiliado->fe_afiliado->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_afiliado_fe_afiliado" class="control-group">
<input type="text" data-field="x_fe_afiliado" name="x_fe_afiliado" id="x_fe_afiliado" placeholder="<?php echo ew_HtmlEncode($afiliado->fe_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->fe_afiliado->EditValue ?>"<?php echo $afiliado->fe_afiliado->EditAttributes() ?>>
<?php if (!$afiliado->fe_afiliado->ReadOnly && !$afiliado->fe_afiliado->Disabled && @$afiliado->fe_afiliado->EditAttrs["readonly"] == "" && @$afiliado->fe_afiliado->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_fe_afiliado" name="cal_x_fe_afiliado" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fafiliadosearch", "x_fe_afiliado", "%Y/%m/%d");
</script>
<?php } ?>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($afiliado->talla_afiliado->Visible) { // talla_afiliado ?>
	<tr id="r_talla_afiliado">
		<td><span id="elh_afiliado_talla_afiliado"><?php echo $afiliado->talla_afiliado->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_talla_afiliado" id="z_talla_afiliado" value="="></span></td>
		<td<?php echo $afiliado->talla_afiliado->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_afiliado_talla_afiliado" class="control-group">
<input type="text" data-field="x_talla_afiliado" name="x_talla_afiliado" id="x_talla_afiliado" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($afiliado->talla_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->talla_afiliado->EditValue ?>"<?php echo $afiliado->talla_afiliado->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($afiliado->peso_afiliado->Visible) { // peso_afiliado ?>
	<tr id="r_peso_afiliado">
		<td><span id="elh_afiliado_peso_afiliado"><?php echo $afiliado->peso_afiliado->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_peso_afiliado" id="z_peso_afiliado" value="="></span></td>
		<td<?php echo $afiliado->peso_afiliado->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_afiliado_peso_afiliado" class="control-group">
<input type="text" data-field="x_peso_afiliado" name="x_peso_afiliado" id="x_peso_afiliado" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($afiliado->peso_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->peso_afiliado->EditValue ?>"<?php echo $afiliado->peso_afiliado->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($afiliado->altu_afiliado->Visible) { // altu_afiliado ?>
	<tr id="r_altu_afiliado">
		<td><span id="elh_afiliado_altu_afiliado"><?php echo $afiliado->altu_afiliado->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_altu_afiliado" id="z_altu_afiliado" value="="></span></td>
		<td<?php echo $afiliado->altu_afiliado->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_afiliado_altu_afiliado" class="control-group">
<input type="text" data-field="x_altu_afiliado" name="x_altu_afiliado" id="x_altu_afiliado" size="30" placeholder="<?php echo ew_HtmlEncode($afiliado->altu_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->altu_afiliado->EditValue ?>"<?php echo $afiliado->altu_afiliado->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($afiliado->localresdi_afiliado->Visible) { // localresdi_afiliado ?>
	<tr id="r_localresdi_afiliado">
		<td><span id="elh_afiliado_localresdi_afiliado"><?php echo $afiliado->localresdi_afiliado->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_localresdi_afiliado" id="z_localresdi_afiliado" value="LIKE"></span></td>
		<td<?php echo $afiliado->localresdi_afiliado->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_afiliado_localresdi_afiliado" class="control-group">
<input type="text" data-field="x_localresdi_afiliado" name="x_localresdi_afiliado" id="x_localresdi_afiliado" placeholder="<?php echo ew_HtmlEncode($afiliado->localresdi_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->localresdi_afiliado->EditValue ?>"<?php echo $afiliado->localresdi_afiliado->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($afiliado->telf_fijo_afiliado->Visible) { // telf_fijo_afiliado ?>
	<tr id="r_telf_fijo_afiliado">
		<td><span id="elh_afiliado_telf_fijo_afiliado"><?php echo $afiliado->telf_fijo_afiliado->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_telf_fijo_afiliado" id="z_telf_fijo_afiliado" value="="></span></td>
		<td<?php echo $afiliado->telf_fijo_afiliado->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_afiliado_telf_fijo_afiliado" class="control-group">
<input type="text" data-field="x_telf_fijo_afiliado" name="x_telf_fijo_afiliado" id="x_telf_fijo_afiliado" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($afiliado->telf_fijo_afiliado->PlaceHolder) ?>" value="<?php echo $afiliado->telf_fijo_afiliado->EditValue ?>"<?php echo $afiliado->telf_fijo_afiliado->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($afiliado->st_afiliado->Visible) { // st_afiliado ?>
	<tr id="r_st_afiliado">
		<td><span id="elh_afiliado_st_afiliado"><?php echo $afiliado->st_afiliado->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_st_afiliado" id="z_st_afiliado" value="="></span></td>
		<td<?php echo $afiliado->st_afiliado->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_afiliado_st_afiliado" class="control-group">
<select data-field="x_st_afiliado" id="x_st_afiliado" name="x_st_afiliado"<?php echo $afiliado->st_afiliado->EditAttributes() ?>>
<?php
if (is_array($afiliado->st_afiliado->EditValue)) {
	$arwrk = $afiliado->st_afiliado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($afiliado->st_afiliado->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($afiliado->st_notificado->Visible) { // st_notificado ?>
	<tr id="r_st_notificado">
		<td><span id="elh_afiliado_st_notificado"><?php echo $afiliado->st_notificado->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_st_notificado" id="z_st_notificado" value="="></span></td>
		<td<?php echo $afiliado->st_notificado->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_afiliado_st_notificado" class="control-group">
<input type="text" data-field="x_st_notificado" name="x_st_notificado" id="x_st_notificado" size="30" placeholder="<?php echo ew_HtmlEncode($afiliado->st_notificado->PlaceHolder) ?>" value="<?php echo $afiliado->st_notificado->EditValue ?>"<?php echo $afiliado->st_notificado->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
</form>
<script type="text/javascript">
fafiliadosearch.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$afiliado_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$afiliado_search->Page_Terminate();
?>
