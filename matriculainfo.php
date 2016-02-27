<?php

// Global variable for table object
$matricula = NULL;

//
// Table class for matricula
//
class cmatricula extends cTable {
	var $id_matricula;
	var $id_afiliado;
	var $tipo_matri;
	var $id_plan;
	var $valor_matri;
	var $valor_men_matri;
	var $conv_matri;
	var $id_empleado;
	var $bol_matri;
	var $cuenta_matri;
	var $termino1_matri;
	var $termino2_matri;
	var $termino3_matri;
	var $pag_card_matri;
	var $tipo_card_matri;
	var $num_card_matri;
	var $venc_card_matri;
	var $doc1_matri;
	var $doc2_matri;
	var $doc3_matri;
	var $doc4_matri;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'matricula';
		$this->TableName = 'matricula';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id_matricula
		$this->id_matricula = new cField('matricula', 'matricula', 'x_id_matricula', 'id_matricula', '`id_matricula`', '`id_matricula`', 3, -1, FALSE, '`id_matricula`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_matricula->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_matricula'] = &$this->id_matricula;

		// id_afiliado
		$this->id_afiliado = new cField('matricula', 'matricula', 'x_id_afiliado', 'id_afiliado', '`id_afiliado`', '`id_afiliado`', 3, -1, FALSE, '`id_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_afiliado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_afiliado'] = &$this->id_afiliado;

		// tipo_matri
		$this->tipo_matri = new cField('matricula', 'matricula', 'x_tipo_matri', 'tipo_matri', '`tipo_matri`', '`tipo_matri`', 16, -1, FALSE, '`tipo_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tipo_matri->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tipo_matri'] = &$this->tipo_matri;

		// id_plan
		$this->id_plan = new cField('matricula', 'matricula', 'x_id_plan', 'id_plan', '`id_plan`', '`id_plan`', 3, -1, FALSE, '`id_plan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_plan->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_plan'] = &$this->id_plan;

		// valor_matri
		$this->valor_matri = new cField('matricula', 'matricula', 'x_valor_matri', 'valor_matri', '`valor_matri`', '`valor_matri`', 5, -1, FALSE, '`valor_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->valor_matri->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['valor_matri'] = &$this->valor_matri;

		// valor_men_matri
		$this->valor_men_matri = new cField('matricula', 'matricula', 'x_valor_men_matri', 'valor_men_matri', '`valor_men_matri`', '`valor_men_matri`', 5, -1, FALSE, '`valor_men_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->valor_men_matri->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['valor_men_matri'] = &$this->valor_men_matri;

		// conv_matri
		$this->conv_matri = new cField('matricula', 'matricula', 'x_conv_matri', 'conv_matri', '`conv_matri`', '`conv_matri`', 200, -1, FALSE, '`conv_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['conv_matri'] = &$this->conv_matri;

		// id_empleado
		$this->id_empleado = new cField('matricula', 'matricula', 'x_id_empleado', 'id_empleado', '`id_empleado`', '`id_empleado`', 3, -1, FALSE, '`id_empleado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_empleado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_empleado'] = &$this->id_empleado;

		// bol_matri
		$this->bol_matri = new cField('matricula', 'matricula', 'x_bol_matri', 'bol_matri', '`bol_matri`', '`bol_matri`', 200, -1, FALSE, '`bol_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->bol_matri->FldDefaultErrMsg = $Language->Phrase("IncorrectEmail");
		$this->fields['bol_matri'] = &$this->bol_matri;

		// cuenta_matri
		$this->cuenta_matri = new cField('matricula', 'matricula', 'x_cuenta_matri', 'cuenta_matri', '`cuenta_matri`', '`cuenta_matri`', 200, -1, FALSE, '`cuenta_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cuenta_matri'] = &$this->cuenta_matri;

		// termino1_matri
		$this->termino1_matri = new cField('matricula', 'matricula', 'x_termino1_matri', 'termino1_matri', '`termino1_matri`', '`termino1_matri`', 200, -1, FALSE, '`termino1_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['termino1_matri'] = &$this->termino1_matri;

		// termino2_matri
		$this->termino2_matri = new cField('matricula', 'matricula', 'x_termino2_matri', 'termino2_matri', '`termino2_matri`', '`termino2_matri`', 200, -1, FALSE, '`termino2_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['termino2_matri'] = &$this->termino2_matri;

		// termino3_matri
		$this->termino3_matri = new cField('matricula', 'matricula', 'x_termino3_matri', 'termino3_matri', '`termino3_matri`', '`termino3_matri`', 200, -1, FALSE, '`termino3_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['termino3_matri'] = &$this->termino3_matri;

		// pag_card_matri
		$this->pag_card_matri = new cField('matricula', 'matricula', 'x_pag_card_matri', 'pag_card_matri', '`pag_card_matri`', '`pag_card_matri`', 16, -1, FALSE, '`pag_card_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->pag_card_matri->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pag_card_matri'] = &$this->pag_card_matri;

		// tipo_card_matri
		$this->tipo_card_matri = new cField('matricula', 'matricula', 'x_tipo_card_matri', 'tipo_card_matri', '`tipo_card_matri`', '`tipo_card_matri`', 200, -1, FALSE, '`tipo_card_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['tipo_card_matri'] = &$this->tipo_card_matri;

		// num_card_matri
		$this->num_card_matri = new cField('matricula', 'matricula', 'x_num_card_matri', 'num_card_matri', '`num_card_matri`', '`num_card_matri`', 200, -1, FALSE, '`num_card_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->num_card_matri->FldDefaultErrMsg = $Language->Phrase("IncorrectCreditCard");
		$this->fields['num_card_matri'] = &$this->num_card_matri;

		// venc_card_matri
		$this->venc_card_matri = new cField('matricula', 'matricula', 'x_venc_card_matri', 'venc_card_matri', '`venc_card_matri`', '`venc_card_matri`', 200, 2, FALSE, '`venc_card_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['venc_card_matri'] = &$this->venc_card_matri;

		// doc1_matri
		$this->doc1_matri = new cField('matricula', 'matricula', 'x_doc1_matri', 'doc1_matri', '`doc1_matri`', '`doc1_matri`', 200, -1, FALSE, '`doc1_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['doc1_matri'] = &$this->doc1_matri;

		// doc2_matri
		$this->doc2_matri = new cField('matricula', 'matricula', 'x_doc2_matri', 'doc2_matri', '`doc2_matri`', '`doc2_matri`', 200, -1, FALSE, '`doc2_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['doc2_matri'] = &$this->doc2_matri;

		// doc3_matri
		$this->doc3_matri = new cField('matricula', 'matricula', 'x_doc3_matri', 'doc3_matri', '`doc3_matri`', '`doc3_matri`', 200, -1, FALSE, '`doc3_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['doc3_matri'] = &$this->doc3_matri;

		// doc4_matri
		$this->doc4_matri = new cField('matricula', 'matricula', 'x_doc4_matri', 'doc4_matri', '`doc4_matri`', '`doc4_matri`', 200, -1, FALSE, '`doc4_matri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['doc4_matri'] = &$this->doc4_matri;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "afiliado") {
			if ($this->id_afiliado->getSessionValue() <> "")
				$sMasterFilter .= "`id_afiliado`=" . ew_QuotedValue($this->id_afiliado->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "afiliado") {
			if ($this->id_afiliado->getSessionValue() <> "")
				$sDetailFilter .= "`id_afiliado`=" . ew_QuotedValue($this->id_afiliado->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_afiliado() {
		return "`id_afiliado`=@id_afiliado@";
	}

	// Detail filter
	function SqlDetailFilter_afiliado() {
		return "`id_afiliado`=@id_afiliado@";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`matricula`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`matricula`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('id_matricula', $rs))
				ew_AddFilter($where, ew_QuotedName('id_matricula') . '=' . ew_QuotedValue($rs['id_matricula'], $this->id_matricula->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id_matricula` = @id_matricula@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_matricula->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_matricula@", ew_AdjustSql($this->id_matricula->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "matriculalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "matriculalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("matriculaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("matriculaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "matriculaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("matriculaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("matriculaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("matriculadelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_matricula->CurrentValue)) {
			$sUrl .= "id_matricula=" . urlencode($this->id_matricula->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["id_matricula"]; // id_matricula

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id_matricula->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->id_matricula->Exportable) $Doc->ExportCaption($this->id_matricula);
				if ($this->id_afiliado->Exportable) $Doc->ExportCaption($this->id_afiliado);
				if ($this->tipo_matri->Exportable) $Doc->ExportCaption($this->tipo_matri);
				if ($this->id_plan->Exportable) $Doc->ExportCaption($this->id_plan);
				if ($this->valor_matri->Exportable) $Doc->ExportCaption($this->valor_matri);
				if ($this->valor_men_matri->Exportable) $Doc->ExportCaption($this->valor_men_matri);
				if ($this->conv_matri->Exportable) $Doc->ExportCaption($this->conv_matri);
				if ($this->id_empleado->Exportable) $Doc->ExportCaption($this->id_empleado);
				if ($this->bol_matri->Exportable) $Doc->ExportCaption($this->bol_matri);
				if ($this->cuenta_matri->Exportable) $Doc->ExportCaption($this->cuenta_matri);
				if ($this->termino1_matri->Exportable) $Doc->ExportCaption($this->termino1_matri);
				if ($this->termino2_matri->Exportable) $Doc->ExportCaption($this->termino2_matri);
				if ($this->termino3_matri->Exportable) $Doc->ExportCaption($this->termino3_matri);
				if ($this->pag_card_matri->Exportable) $Doc->ExportCaption($this->pag_card_matri);
				if ($this->tipo_card_matri->Exportable) $Doc->ExportCaption($this->tipo_card_matri);
				if ($this->num_card_matri->Exportable) $Doc->ExportCaption($this->num_card_matri);
				if ($this->venc_card_matri->Exportable) $Doc->ExportCaption($this->venc_card_matri);
				if ($this->doc1_matri->Exportable) $Doc->ExportCaption($this->doc1_matri);
				if ($this->doc2_matri->Exportable) $Doc->ExportCaption($this->doc2_matri);
				if ($this->doc3_matri->Exportable) $Doc->ExportCaption($this->doc3_matri);
				if ($this->doc4_matri->Exportable) $Doc->ExportCaption($this->doc4_matri);
			} else {
				if ($this->id_matricula->Exportable) $Doc->ExportCaption($this->id_matricula);
				if ($this->id_afiliado->Exportable) $Doc->ExportCaption($this->id_afiliado);
				if ($this->tipo_matri->Exportable) $Doc->ExportCaption($this->tipo_matri);
				if ($this->id_plan->Exportable) $Doc->ExportCaption($this->id_plan);
				if ($this->valor_matri->Exportable) $Doc->ExportCaption($this->valor_matri);
				if ($this->valor_men_matri->Exportable) $Doc->ExportCaption($this->valor_men_matri);
				if ($this->conv_matri->Exportable) $Doc->ExportCaption($this->conv_matri);
				if ($this->id_empleado->Exportable) $Doc->ExportCaption($this->id_empleado);
				if ($this->bol_matri->Exportable) $Doc->ExportCaption($this->bol_matri);
				if ($this->cuenta_matri->Exportable) $Doc->ExportCaption($this->cuenta_matri);
				if ($this->termino1_matri->Exportable) $Doc->ExportCaption($this->termino1_matri);
				if ($this->termino2_matri->Exportable) $Doc->ExportCaption($this->termino2_matri);
				if ($this->termino3_matri->Exportable) $Doc->ExportCaption($this->termino3_matri);
				if ($this->pag_card_matri->Exportable) $Doc->ExportCaption($this->pag_card_matri);
				if ($this->tipo_card_matri->Exportable) $Doc->ExportCaption($this->tipo_card_matri);
				if ($this->num_card_matri->Exportable) $Doc->ExportCaption($this->num_card_matri);
				if ($this->venc_card_matri->Exportable) $Doc->ExportCaption($this->venc_card_matri);
				if ($this->doc1_matri->Exportable) $Doc->ExportCaption($this->doc1_matri);
				if ($this->doc2_matri->Exportable) $Doc->ExportCaption($this->doc2_matri);
				if ($this->doc3_matri->Exportable) $Doc->ExportCaption($this->doc3_matri);
				if ($this->doc4_matri->Exportable) $Doc->ExportCaption($this->doc4_matri);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->id_matricula->Exportable) $Doc->ExportField($this->id_matricula);
					if ($this->id_afiliado->Exportable) $Doc->ExportField($this->id_afiliado);
					if ($this->tipo_matri->Exportable) $Doc->ExportField($this->tipo_matri);
					if ($this->id_plan->Exportable) $Doc->ExportField($this->id_plan);
					if ($this->valor_matri->Exportable) $Doc->ExportField($this->valor_matri);
					if ($this->valor_men_matri->Exportable) $Doc->ExportField($this->valor_men_matri);
					if ($this->conv_matri->Exportable) $Doc->ExportField($this->conv_matri);
					if ($this->id_empleado->Exportable) $Doc->ExportField($this->id_empleado);
					if ($this->bol_matri->Exportable) $Doc->ExportField($this->bol_matri);
					if ($this->cuenta_matri->Exportable) $Doc->ExportField($this->cuenta_matri);
					if ($this->termino1_matri->Exportable) $Doc->ExportField($this->termino1_matri);
					if ($this->termino2_matri->Exportable) $Doc->ExportField($this->termino2_matri);
					if ($this->termino3_matri->Exportable) $Doc->ExportField($this->termino3_matri);
					if ($this->pag_card_matri->Exportable) $Doc->ExportField($this->pag_card_matri);
					if ($this->tipo_card_matri->Exportable) $Doc->ExportField($this->tipo_card_matri);
					if ($this->num_card_matri->Exportable) $Doc->ExportField($this->num_card_matri);
					if ($this->venc_card_matri->Exportable) $Doc->ExportField($this->venc_card_matri);
					if ($this->doc1_matri->Exportable) $Doc->ExportField($this->doc1_matri);
					if ($this->doc2_matri->Exportable) $Doc->ExportField($this->doc2_matri);
					if ($this->doc3_matri->Exportable) $Doc->ExportField($this->doc3_matri);
					if ($this->doc4_matri->Exportable) $Doc->ExportField($this->doc4_matri);
				} else {
					if ($this->id_matricula->Exportable) $Doc->ExportField($this->id_matricula);
					if ($this->id_afiliado->Exportable) $Doc->ExportField($this->id_afiliado);
					if ($this->tipo_matri->Exportable) $Doc->ExportField($this->tipo_matri);
					if ($this->id_plan->Exportable) $Doc->ExportField($this->id_plan);
					if ($this->valor_matri->Exportable) $Doc->ExportField($this->valor_matri);
					if ($this->valor_men_matri->Exportable) $Doc->ExportField($this->valor_men_matri);
					if ($this->conv_matri->Exportable) $Doc->ExportField($this->conv_matri);
					if ($this->id_empleado->Exportable) $Doc->ExportField($this->id_empleado);
					if ($this->bol_matri->Exportable) $Doc->ExportField($this->bol_matri);
					if ($this->cuenta_matri->Exportable) $Doc->ExportField($this->cuenta_matri);
					if ($this->termino1_matri->Exportable) $Doc->ExportField($this->termino1_matri);
					if ($this->termino2_matri->Exportable) $Doc->ExportField($this->termino2_matri);
					if ($this->termino3_matri->Exportable) $Doc->ExportField($this->termino3_matri);
					if ($this->pag_card_matri->Exportable) $Doc->ExportField($this->pag_card_matri);
					if ($this->tipo_card_matri->Exportable) $Doc->ExportField($this->tipo_card_matri);
					if ($this->num_card_matri->Exportable) $Doc->ExportField($this->num_card_matri);
					if ($this->venc_card_matri->Exportable) $Doc->ExportField($this->venc_card_matri);
					if ($this->doc1_matri->Exportable) $Doc->ExportField($this->doc1_matri);
					if ($this->doc2_matri->Exportable) $Doc->ExportField($this->doc2_matri);
					if ($this->doc3_matri->Exportable) $Doc->ExportField($this->doc3_matri);
					if ($this->doc4_matri->Exportable) $Doc->ExportField($this->doc4_matri);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
