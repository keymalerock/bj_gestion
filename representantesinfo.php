<?php

// Global variable for table object
$representantes = NULL;

//
// Table class for representantes
//
class crepresentantes extends cTable {
	var $id_representante;
	var $id_afiliado;
	var $dociden_repres;
	var $apell_repres;
	var $nomb_repres;
	var $telf_resi_repres;
	var $email_repres;
	var $par_repres;
	var $cel_repres;
	var $contact_e_repres;
	var $contact_d_repres;
	var $st_repres;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'representantes';
		$this->TableName = 'representantes';
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

		// id_representante
		$this->id_representante = new cField('representantes', 'representantes', 'x_id_representante', 'id_representante', '`id_representante`', '`id_representante`', 3, -1, FALSE, '`id_representante`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_representante->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_representante'] = &$this->id_representante;

		// id_afiliado
		$this->id_afiliado = new cField('representantes', 'representantes', 'x_id_afiliado', 'id_afiliado', '`id_afiliado`', '`id_afiliado`', 3, -1, FALSE, '`id_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['id_afiliado'] = &$this->id_afiliado;

		// dociden_repres
		$this->dociden_repres = new cField('representantes', 'representantes', 'x_dociden_repres', 'dociden_repres', '`dociden_repres`', '`dociden_repres`', 200, -1, FALSE, '`dociden_repres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dociden_repres'] = &$this->dociden_repres;

		// apell_repres
		$this->apell_repres = new cField('representantes', 'representantes', 'x_apell_repres', 'apell_repres', '`apell_repres`', '`apell_repres`', 200, -1, FALSE, '`apell_repres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['apell_repres'] = &$this->apell_repres;

		// nomb_repres
		$this->nomb_repres = new cField('representantes', 'representantes', 'x_nomb_repres', 'nomb_repres', '`nomb_repres`', '`nomb_repres`', 200, -1, FALSE, '`nomb_repres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nomb_repres'] = &$this->nomb_repres;

		// telf_resi_repres
		$this->telf_resi_repres = new cField('representantes', 'representantes', 'x_telf_resi_repres', 'telf_resi_repres', '`telf_resi_repres`', '`telf_resi_repres`', 200, -1, FALSE, '`telf_resi_repres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['telf_resi_repres'] = &$this->telf_resi_repres;

		// email_repres
		$this->email_repres = new cField('representantes', 'representantes', 'x_email_repres', 'email_repres', '`email_repres`', '`email_repres`', 200, -1, FALSE, '`email_repres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->email_repres->FldDefaultErrMsg = $Language->Phrase("IncorrectEmail");
		$this->fields['email_repres'] = &$this->email_repres;

		// par_repres
		$this->par_repres = new cField('representantes', 'representantes', 'x_par_repres', 'par_repres', '`par_repres`', '`par_repres`', 202, -1, FALSE, '`par_repres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['par_repres'] = &$this->par_repres;

		// cel_repres
		$this->cel_repres = new cField('representantes', 'representantes', 'x_cel_repres', 'cel_repres', '`cel_repres`', '`cel_repres`', 200, -1, FALSE, '`cel_repres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cel_repres'] = &$this->cel_repres;

		// contact_e_repres
		$this->contact_e_repres = new cField('representantes', 'representantes', 'x_contact_e_repres', 'contact_e_repres', '`contact_e_repres`', '`contact_e_repres`', 200, -1, FALSE, '`contact_e_repres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['contact_e_repres'] = &$this->contact_e_repres;

		// contact_d_repres
		$this->contact_d_repres = new cField('representantes', 'representantes', 'x_contact_d_repres', 'contact_d_repres', '`contact_d_repres`', '`contact_d_repres`', 200, -1, FALSE, '`contact_d_repres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['contact_d_repres'] = &$this->contact_d_repres;

		// st_repres
		$this->st_repres = new cField('representantes', 'representantes', 'x_st_repres', 'st_repres', '`st_repres`', '`st_repres`', 202, -1, FALSE, '`st_repres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['st_repres'] = &$this->st_repres;
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
		return "`representantes`";
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
	var $UpdateTable = "`representantes`";

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
			if (array_key_exists('id_representante', $rs))
				ew_AddFilter($where, ew_QuotedName('id_representante') . '=' . ew_QuotedValue($rs['id_representante'], $this->id_representante->FldDataType));
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
		return "`id_representante` = @id_representante@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_representante->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_representante@", ew_AdjustSql($this->id_representante->CurrentValue), $sKeyFilter); // Replace key value
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
			return "representanteslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "representanteslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("representantesview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("representantesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "representantesadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("representantesedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("representantesadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("representantesdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_representante->CurrentValue)) {
			$sUrl .= "id_representante=" . urlencode($this->id_representante->CurrentValue);
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
			$arKeys[] = @$_GET["id_representante"]; // id_representante

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
			$this->id_representante->CurrentValue = $key;
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// id_representante
		$this->id_representante->LinkCustomAttributes = "";
		$this->id_representante->HrefValue = "";
		$this->id_representante->TooltipValue = "";

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
				if ($this->id_representante->Exportable) $Doc->ExportCaption($this->id_representante);
				if ($this->id_afiliado->Exportable) $Doc->ExportCaption($this->id_afiliado);
				if ($this->dociden_repres->Exportable) $Doc->ExportCaption($this->dociden_repres);
				if ($this->apell_repres->Exportable) $Doc->ExportCaption($this->apell_repres);
				if ($this->nomb_repres->Exportable) $Doc->ExportCaption($this->nomb_repres);
				if ($this->telf_resi_repres->Exportable) $Doc->ExportCaption($this->telf_resi_repres);
				if ($this->email_repres->Exportable) $Doc->ExportCaption($this->email_repres);
				if ($this->par_repres->Exportable) $Doc->ExportCaption($this->par_repres);
				if ($this->cel_repres->Exportable) $Doc->ExportCaption($this->cel_repres);
				if ($this->contact_e_repres->Exportable) $Doc->ExportCaption($this->contact_e_repres);
				if ($this->contact_d_repres->Exportable) $Doc->ExportCaption($this->contact_d_repres);
				if ($this->st_repres->Exportable) $Doc->ExportCaption($this->st_repres);
			} else {
				if ($this->id_representante->Exportable) $Doc->ExportCaption($this->id_representante);
				if ($this->id_afiliado->Exportable) $Doc->ExportCaption($this->id_afiliado);
				if ($this->dociden_repres->Exportable) $Doc->ExportCaption($this->dociden_repres);
				if ($this->apell_repres->Exportable) $Doc->ExportCaption($this->apell_repres);
				if ($this->nomb_repres->Exportable) $Doc->ExportCaption($this->nomb_repres);
				if ($this->telf_resi_repres->Exportable) $Doc->ExportCaption($this->telf_resi_repres);
				if ($this->email_repres->Exportable) $Doc->ExportCaption($this->email_repres);
				if ($this->par_repres->Exportable) $Doc->ExportCaption($this->par_repres);
				if ($this->cel_repres->Exportable) $Doc->ExportCaption($this->cel_repres);
				if ($this->contact_e_repres->Exportable) $Doc->ExportCaption($this->contact_e_repres);
				if ($this->contact_d_repres->Exportable) $Doc->ExportCaption($this->contact_d_repres);
				if ($this->st_repres->Exportable) $Doc->ExportCaption($this->st_repres);
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
					if ($this->id_representante->Exportable) $Doc->ExportField($this->id_representante);
					if ($this->id_afiliado->Exportable) $Doc->ExportField($this->id_afiliado);
					if ($this->dociden_repres->Exportable) $Doc->ExportField($this->dociden_repres);
					if ($this->apell_repres->Exportable) $Doc->ExportField($this->apell_repres);
					if ($this->nomb_repres->Exportable) $Doc->ExportField($this->nomb_repres);
					if ($this->telf_resi_repres->Exportable) $Doc->ExportField($this->telf_resi_repres);
					if ($this->email_repres->Exportable) $Doc->ExportField($this->email_repres);
					if ($this->par_repres->Exportable) $Doc->ExportField($this->par_repres);
					if ($this->cel_repres->Exportable) $Doc->ExportField($this->cel_repres);
					if ($this->contact_e_repres->Exportable) $Doc->ExportField($this->contact_e_repres);
					if ($this->contact_d_repres->Exportable) $Doc->ExportField($this->contact_d_repres);
					if ($this->st_repres->Exportable) $Doc->ExportField($this->st_repres);
				} else {
					if ($this->id_representante->Exportable) $Doc->ExportField($this->id_representante);
					if ($this->id_afiliado->Exportable) $Doc->ExportField($this->id_afiliado);
					if ($this->dociden_repres->Exportable) $Doc->ExportField($this->dociden_repres);
					if ($this->apell_repres->Exportable) $Doc->ExportField($this->apell_repres);
					if ($this->nomb_repres->Exportable) $Doc->ExportField($this->nomb_repres);
					if ($this->telf_resi_repres->Exportable) $Doc->ExportField($this->telf_resi_repres);
					if ($this->email_repres->Exportable) $Doc->ExportField($this->email_repres);
					if ($this->par_repres->Exportable) $Doc->ExportField($this->par_repres);
					if ($this->cel_repres->Exportable) $Doc->ExportField($this->cel_repres);
					if ($this->contact_e_repres->Exportable) $Doc->ExportField($this->contact_e_repres);
					if ($this->contact_d_repres->Exportable) $Doc->ExportField($this->contact_d_repres);
					if ($this->st_repres->Exportable) $Doc->ExportField($this->st_repres);
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
