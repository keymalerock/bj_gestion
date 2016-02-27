<?php

// Global variable for table object
$empleados = NULL;

//
// Table class for empleados
//
class cempleados extends cTable {
	var $id_empleado;
	var $dociden_empleado;
	var $nomb_empleado;
	var $apell_empleado;
	var $telf_empleado;
	var $email_empleado;
	var $st_empleado_p;
	var $pass_empleado;
	var $login_empleado;
	var $id_perfil;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'empleados';
		$this->TableName = 'empleados';
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

		// id_empleado
		$this->id_empleado = new cField('empleados', 'empleados', 'x_id_empleado', 'id_empleado', '`id_empleado`', '`id_empleado`', 3, -1, FALSE, '`id_empleado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_empleado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_empleado'] = &$this->id_empleado;

		// dociden_empleado
		$this->dociden_empleado = new cField('empleados', 'empleados', 'x_dociden_empleado', 'dociden_empleado', '`dociden_empleado`', '`dociden_empleado`', 200, -1, FALSE, '`dociden_empleado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dociden_empleado'] = &$this->dociden_empleado;

		// nomb_empleado
		$this->nomb_empleado = new cField('empleados', 'empleados', 'x_nomb_empleado', 'nomb_empleado', '`nomb_empleado`', '`nomb_empleado`', 200, -1, FALSE, '`nomb_empleado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nomb_empleado'] = &$this->nomb_empleado;

		// apell_empleado
		$this->apell_empleado = new cField('empleados', 'empleados', 'x_apell_empleado', 'apell_empleado', '`apell_empleado`', '`apell_empleado`', 200, -1, FALSE, '`apell_empleado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['apell_empleado'] = &$this->apell_empleado;

		// telf_empleado
		$this->telf_empleado = new cField('empleados', 'empleados', 'x_telf_empleado', 'telf_empleado', '`telf_empleado`', '`telf_empleado`', 200, -1, FALSE, '`telf_empleado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['telf_empleado'] = &$this->telf_empleado;

		// email_empleado
		$this->email_empleado = new cField('empleados', 'empleados', 'x_email_empleado', 'email_empleado', '`email_empleado`', '`email_empleado`', 200, -1, FALSE, '`email_empleado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->email_empleado->FldDefaultErrMsg = $Language->Phrase("IncorrectEmail");
		$this->fields['email_empleado'] = &$this->email_empleado;

		// st_empleado_p
		$this->st_empleado_p = new cField('empleados', 'empleados', 'x_st_empleado_p', 'st_empleado_p', '`st_empleado_p`', '`st_empleado_p`', 202, -1, FALSE, '`st_empleado_p`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->st_empleado_p->FldDataType = EW_DATATYPE_BOOLEAN;
		$this->fields['st_empleado_p'] = &$this->st_empleado_p;

		// pass_empleado
		$this->pass_empleado = new cField('empleados', 'empleados', 'x_pass_empleado', 'pass_empleado', '`pass_empleado`', '`pass_empleado`', 200, -1, FALSE, '`pass_empleado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['pass_empleado'] = &$this->pass_empleado;

		// login_empleado
		$this->login_empleado = new cField('empleados', 'empleados', 'x_login_empleado', 'login_empleado', '`login_empleado`', '`login_empleado`', 200, -1, FALSE, '`login_empleado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['login_empleado'] = &$this->login_empleado;

		// id_perfil
		$this->id_perfil = new cField('empleados', 'empleados', 'x_id_perfil', 'id_perfil', '`id_perfil`', '`id_perfil`', 3, -1, FALSE, '`id_perfil`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_perfil->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_perfil'] = &$this->id_perfil;
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

	// Table level SQL
	function SqlFrom() { // From
		return "`empleados`";
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
	var $UpdateTable = "`empleados`";

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
			if (array_key_exists('id_empleado', $rs))
				ew_AddFilter($where, ew_QuotedName('id_empleado') . '=' . ew_QuotedValue($rs['id_empleado'], $this->id_empleado->FldDataType));
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
		return "`id_empleado` = @id_empleado@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_empleado->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_empleado@", ew_AdjustSql($this->id_empleado->CurrentValue), $sKeyFilter); // Replace key value
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
			return "empleadoslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "empleadoslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("empleadosview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("empleadosview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "empleadosadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("empleadosedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("empleadosadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("empleadosdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_empleado->CurrentValue)) {
			$sUrl .= "id_empleado=" . urlencode($this->id_empleado->CurrentValue);
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
			$arKeys[] = @$_GET["id_empleado"]; // id_empleado

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
			$this->id_empleado->CurrentValue = $key;
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_empleado

		$this->id_empleado->CellCssStyle = "white-space: nowrap;";

		// dociden_empleado
		// nomb_empleado
		// apell_empleado
		// telf_empleado
		// email_empleado
		// st_empleado_p
		// pass_empleado
		// login_empleado
		// id_perfil
		// id_empleado

		$this->id_empleado->ViewValue = $this->id_empleado->CurrentValue;
		$this->id_empleado->ViewCustomAttributes = "";

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

		// id_empleado
		$this->id_empleado->LinkCustomAttributes = "";
		$this->id_empleado->HrefValue = "";
		$this->id_empleado->TooltipValue = "";

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
				if ($this->id_empleado->Exportable) $Doc->ExportCaption($this->id_empleado);
				if ($this->dociden_empleado->Exportable) $Doc->ExportCaption($this->dociden_empleado);
				if ($this->nomb_empleado->Exportable) $Doc->ExportCaption($this->nomb_empleado);
				if ($this->apell_empleado->Exportable) $Doc->ExportCaption($this->apell_empleado);
				if ($this->telf_empleado->Exportable) $Doc->ExportCaption($this->telf_empleado);
				if ($this->email_empleado->Exportable) $Doc->ExportCaption($this->email_empleado);
				if ($this->st_empleado_p->Exportable) $Doc->ExportCaption($this->st_empleado_p);
				if ($this->pass_empleado->Exportable) $Doc->ExportCaption($this->pass_empleado);
				if ($this->login_empleado->Exportable) $Doc->ExportCaption($this->login_empleado);
				if ($this->id_perfil->Exportable) $Doc->ExportCaption($this->id_perfil);
			} else {
				if ($this->dociden_empleado->Exportable) $Doc->ExportCaption($this->dociden_empleado);
				if ($this->nomb_empleado->Exportable) $Doc->ExportCaption($this->nomb_empleado);
				if ($this->apell_empleado->Exportable) $Doc->ExportCaption($this->apell_empleado);
				if ($this->telf_empleado->Exportable) $Doc->ExportCaption($this->telf_empleado);
				if ($this->email_empleado->Exportable) $Doc->ExportCaption($this->email_empleado);
				if ($this->st_empleado_p->Exportable) $Doc->ExportCaption($this->st_empleado_p);
				if ($this->pass_empleado->Exportable) $Doc->ExportCaption($this->pass_empleado);
				if ($this->login_empleado->Exportable) $Doc->ExportCaption($this->login_empleado);
				if ($this->id_perfil->Exportable) $Doc->ExportCaption($this->id_perfil);
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
					if ($this->id_empleado->Exportable) $Doc->ExportField($this->id_empleado);
					if ($this->dociden_empleado->Exportable) $Doc->ExportField($this->dociden_empleado);
					if ($this->nomb_empleado->Exportable) $Doc->ExportField($this->nomb_empleado);
					if ($this->apell_empleado->Exportable) $Doc->ExportField($this->apell_empleado);
					if ($this->telf_empleado->Exportable) $Doc->ExportField($this->telf_empleado);
					if ($this->email_empleado->Exportable) $Doc->ExportField($this->email_empleado);
					if ($this->st_empleado_p->Exportable) $Doc->ExportField($this->st_empleado_p);
					if ($this->pass_empleado->Exportable) $Doc->ExportField($this->pass_empleado);
					if ($this->login_empleado->Exportable) $Doc->ExportField($this->login_empleado);
					if ($this->id_perfil->Exportable) $Doc->ExportField($this->id_perfil);
				} else {
					if ($this->dociden_empleado->Exportable) $Doc->ExportField($this->dociden_empleado);
					if ($this->nomb_empleado->Exportable) $Doc->ExportField($this->nomb_empleado);
					if ($this->apell_empleado->Exportable) $Doc->ExportField($this->apell_empleado);
					if ($this->telf_empleado->Exportable) $Doc->ExportField($this->telf_empleado);
					if ($this->email_empleado->Exportable) $Doc->ExportField($this->email_empleado);
					if ($this->st_empleado_p->Exportable) $Doc->ExportField($this->st_empleado_p);
					if ($this->pass_empleado->Exportable) $Doc->ExportField($this->pass_empleado);
					if ($this->login_empleado->Exportable) $Doc->ExportField($this->login_empleado);
					if ($this->id_perfil->Exportable) $Doc->ExportField($this->id_perfil);
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
