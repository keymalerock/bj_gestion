<?php

// Global variable for table object
$respuesta = NULL;

//
// Table class for respuesta
//
class crespuesta extends cTable {
	var $id_respuesta;
	var $id_novedad;
	var $id_empleado;
	var $obs_resp;
	var $fe_resp;
	var $estado_resp;
	var $replica_resp;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'respuesta';
		$this->TableName = 'respuesta';
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

		// id_respuesta
		$this->id_respuesta = new cField('respuesta', 'respuesta', 'x_id_respuesta', 'id_respuesta', '`id_respuesta`', '`id_respuesta`', 3, -1, FALSE, '`id_respuesta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_respuesta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_respuesta'] = &$this->id_respuesta;

		// id_novedad
		$this->id_novedad = new cField('respuesta', 'respuesta', 'x_id_novedad', 'id_novedad', '`id_novedad`', '`id_novedad`', 3, -1, FALSE, '`id_novedad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_novedad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_novedad'] = &$this->id_novedad;

		// id_empleado
		$this->id_empleado = new cField('respuesta', 'respuesta', 'x_id_empleado', 'id_empleado', '`id_empleado`', '`id_empleado`', 3, -1, FALSE, '`id_empleado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_empleado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_empleado'] = &$this->id_empleado;

		// obs_resp
		$this->obs_resp = new cField('respuesta', 'respuesta', 'x_obs_resp', 'obs_resp', '`obs_resp`', '`obs_resp`', 201, -1, FALSE, '`obs_resp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['obs_resp'] = &$this->obs_resp;

		// fe_resp
		$this->fe_resp = new cField('respuesta', 'respuesta', 'x_fe_resp', 'fe_resp', '`fe_resp`', 'DATE_FORMAT(`fe_resp`, \'%Y/%m/%d\')', 135, 5, FALSE, '`fe_resp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fe_resp->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['fe_resp'] = &$this->fe_resp;

		// estado_resp
		$this->estado_resp = new cField('respuesta', 'respuesta', 'x_estado_resp', 'estado_resp', '`estado_resp`', '`estado_resp`', 16, -1, FALSE, '`estado_resp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->estado_resp->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['estado_resp'] = &$this->estado_resp;

		// replica_resp
		$this->replica_resp = new cField('respuesta', 'respuesta', 'x_replica_resp', 'replica_resp', '`replica_resp`', '`replica_resp`', 201, -1, FALSE, '`replica_resp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['replica_resp'] = &$this->replica_resp;
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
		if ($this->getCurrentMasterTable() == "novedad") {
			if ($this->id_novedad->getSessionValue() <> "")
				$sMasterFilter .= "`id_novedad`=" . ew_QuotedValue($this->id_novedad->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "novedad") {
			if ($this->id_novedad->getSessionValue() <> "")
				$sDetailFilter .= "`id_novedad`=" . ew_QuotedValue($this->id_novedad->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_novedad() {
		return "`id_novedad`=@id_novedad@";
	}

	// Detail filter
	function SqlDetailFilter_novedad() {
		return "`id_novedad`=@id_novedad@";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`respuesta`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = (CurrentUserID() != -1 &&  CurrentUserID() != 4)?"`id_empleado`='".CurrentUserID()."'":"";;
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
		return "`id_respuesta` DESC";
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
	var $UpdateTable = "`respuesta`";

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
			if (array_key_exists('id_respuesta', $rs))
				ew_AddFilter($where, ew_QuotedName('id_respuesta') . '=' . ew_QuotedValue($rs['id_respuesta'], $this->id_respuesta->FldDataType));
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
		return "`id_respuesta` = @id_respuesta@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_respuesta->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_respuesta@", ew_AdjustSql($this->id_respuesta->CurrentValue), $sKeyFilter); // Replace key value
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
			return "respuestalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "respuestalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("respuestaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("respuestaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "respuestaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("respuestaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("respuestaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("respuestadelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_respuesta->CurrentValue)) {
			$sUrl .= "id_respuesta=" . urlencode($this->id_respuesta->CurrentValue);
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
			$arKeys[] = @$_GET["id_respuesta"]; // id_respuesta

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
			$this->id_respuesta->CurrentValue = $key;
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
		$this->id_respuesta->setDbValue($rs->fields('id_respuesta'));
		$this->id_novedad->setDbValue($rs->fields('id_novedad'));
		$this->id_empleado->setDbValue($rs->fields('id_empleado'));
		$this->obs_resp->setDbValue($rs->fields('obs_resp'));
		$this->fe_resp->setDbValue($rs->fields('fe_resp'));
		$this->estado_resp->setDbValue($rs->fields('estado_resp'));
		$this->replica_resp->setDbValue($rs->fields('replica_resp'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_respuesta
		// id_novedad
		// id_empleado
		// obs_resp
		// fe_resp
		// estado_resp
		// replica_resp
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

		// fe_resp
		$this->fe_resp->ViewValue = $this->fe_resp->CurrentValue;
		$this->fe_resp->ViewValue = ew_FormatDateTime($this->fe_resp->ViewValue, 5);
		$this->fe_resp->ViewCustomAttributes = "";

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

		// id_respuesta
		$this->id_respuesta->LinkCustomAttributes = "";
		$this->id_respuesta->HrefValue = "";
		$this->id_respuesta->TooltipValue = "";

		// id_novedad
		$this->id_novedad->LinkCustomAttributes = "";
		$this->id_novedad->HrefValue = "";
		$this->id_novedad->TooltipValue = "";

		// id_empleado
		$this->id_empleado->LinkCustomAttributes = "";
		$this->id_empleado->HrefValue = "";
		$this->id_empleado->TooltipValue = "";

		// obs_resp
		$this->obs_resp->LinkCustomAttributes = "";
		$this->obs_resp->HrefValue = "";
		$this->obs_resp->TooltipValue = "";

		// fe_resp
		$this->fe_resp->LinkCustomAttributes = "";
		$this->fe_resp->HrefValue = "";
		$this->fe_resp->TooltipValue = "";

		// estado_resp
		$this->estado_resp->LinkCustomAttributes = "";
		$this->estado_resp->HrefValue = "";
		$this->estado_resp->TooltipValue = "";

		// replica_resp
		$this->replica_resp->LinkCustomAttributes = "";
		$this->replica_resp->HrefValue = "";
		$this->replica_resp->TooltipValue = "";

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
				if ($this->id_respuesta->Exportable) $Doc->ExportCaption($this->id_respuesta);
				if ($this->id_novedad->Exportable) $Doc->ExportCaption($this->id_novedad);
				if ($this->id_empleado->Exportable) $Doc->ExportCaption($this->id_empleado);
				if ($this->obs_resp->Exportable) $Doc->ExportCaption($this->obs_resp);
				if ($this->fe_resp->Exportable) $Doc->ExportCaption($this->fe_resp);
				if ($this->estado_resp->Exportable) $Doc->ExportCaption($this->estado_resp);
				if ($this->replica_resp->Exportable) $Doc->ExportCaption($this->replica_resp);
			} else {
				if ($this->id_respuesta->Exportable) $Doc->ExportCaption($this->id_respuesta);
				if ($this->id_novedad->Exportable) $Doc->ExportCaption($this->id_novedad);
				if ($this->id_empleado->Exportable) $Doc->ExportCaption($this->id_empleado);
				if ($this->estado_resp->Exportable) $Doc->ExportCaption($this->estado_resp);
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
					if ($this->id_respuesta->Exportable) $Doc->ExportField($this->id_respuesta);
					if ($this->id_novedad->Exportable) $Doc->ExportField($this->id_novedad);
					if ($this->id_empleado->Exportable) $Doc->ExportField($this->id_empleado);
					if ($this->obs_resp->Exportable) $Doc->ExportField($this->obs_resp);
					if ($this->fe_resp->Exportable) $Doc->ExportField($this->fe_resp);
					if ($this->estado_resp->Exportable) $Doc->ExportField($this->estado_resp);
					if ($this->replica_resp->Exportable) $Doc->ExportField($this->replica_resp);
				} else {
					if ($this->id_respuesta->Exportable) $Doc->ExportField($this->id_respuesta);
					if ($this->id_novedad->Exportable) $Doc->ExportField($this->id_novedad);
					if ($this->id_empleado->Exportable) $Doc->ExportField($this->id_empleado);
					if ($this->estado_resp->Exportable) $Doc->ExportField($this->estado_resp);
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

		if (CurrentUserLevel() =='3')  
		{  
		   $rsnew['estado_resp'] = '1';
		}

	// Evitar mas de una programacion en estado PROGRAMADO o CONFIRMADO

		   /* $consulta_estado = "SELECT COUNT(0) as TOTAL FROM programacion_sol                        
				WHERE ID_SOLICITUDES = '".$rsnew["ID_SOLICITUDES"]."' AND (ID_EST_PROGRAMACION_SOL = '2' OR ID_EST_PROGRAMACION_SOL = '3')";
			$ejecuta = $conn->Execute($consulta_estado);          
			$total_solicitudes_programadas = $ejecuta->fields["TOTAL"]; 
			if($total_solicitudes_programadas > 0){
				CurrentPage()->setFailureMessage("NO ES POSIBLE AGREGAR EL REGISTRO, YA EXISTE UNA SOLICITUD EN PROCESO");
				return FALSE;
			}*/    
		return TRUE;      
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"        
		if (!isset($conn)) $conn = ew_Connect();
		$sentencia = "UPDATE novedad SET estado_nov='2' WHERE (id_novedad='".$rsnew['id_novedad']."')"; 
		CurrentPage()->setMessage("<strong><font color='red'>Respuesta Nro:".$rsnew['id_novedad']."</font></strong>"); 
		$row = $conn->Execute($sentencia); 
		$conn->Close();  
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE 

		if (CurrentUserLevel() =='2')  
		{  
		   $rsnew['estado_resp'] = '2';                    
		}
		if (CurrentUserLevel() =='3')  
		{  

		   //$rsnew['estado_resp'] = '3';  
		   $rsnew['estado_resp'] = $this->estado_resp->CurrentValue ;
		}
		return TRUE;
	}          

	// Row Updated event 
	function Row_Updated($rsold, &$rsnew) {

		/*  var_dump(CurrentUserLevel());  */
		echo $this->estado_resp->CurrentValue;
		die(); 
			if (CurrentUserLevel() ==3 && $rsold['estado_resp']=='2')  
			{                                            
				if (!isset($conn)) $conn = ew_Connect();                                
				  $sentencia = "UPDATE novedad SET estado_nov='4' WHERE (id_novedad='".$rsold['id_novedad']."')";              
				$row = $conn->Execute($sentencia); 
				$conn->Close();     
			 }          
			 if ( $this->estado_resp->CurrentValue =='4' && CurrentUserLevel() ==3 ) {
					 if (!isset($conn)) $conn = ew_Connect();
					  $sentencia = "UPDATE novedad SET estado_nov='4' WHERE (id_novedad='".$rsold['id_novedad']."')";
					  $conn->Execute($sentencia); 
					  $conn->Close();      
			 }

			 // si el usuario es empleado y la respuesta es revisado por ejecutivo
			 if (CurrentUserLevel() ==2 && $rsold['estado_resp']=='1')  
			{                                            
				if (!isset($conn)) $conn = ew_Connect();            
				$sentencia = "UPDATE novedad SET estado_nov='3' WHERE (id_novedad='".$rsold['id_novedad']."')";                        
				$row = $conn->Execute($sentencia); 
				$conn->Close();     
			 }
			 elseif (CurrentUserLevel() ==2 && $rsold['estado_resp']=='2') { 
							 $rsnew['id_empleado'] = $rsold['id_empleado'];
							 if (!isset($conn)) $conn = ew_Connect();            
								$sentencia = "UPDATE novedad SET estado_nov='3' WHERE (id_novedad='".$rsold['id_novedad']."')";                        
								$row = $conn->Execute($sentencia); 
								$conn->Close();
			 }

			 // si fue aprobada enviar correo 
			 if (CurrentUserLevel() ==3 && $this->estado_resp->CurrentValue =='3')  
			{                                            
				  if (!isset($conn)) $conn = ew_Connect();
				  $sentencia = "SELECT novedad.id_afiliado as afil,
				  representantes.email_repres as email,
				  representantes.apell_repres as apel FROM
				  respuesta
				  Inner Join novedad ON respuesta.id_novedad = novedad.id_novedad
				  Inner Join representantes ON novedad.id_afiliado = representantes.id_afiliado
				  WHERE representantes.contact_d_repres = '1' AND
				  representantes.st_repres = 'Activo'
				  AND respuesta.id_respuesta = ".$this->id_respuesta->CurrentValue;
				  echo $sentencia;
				  die();
				  $row = $conn->Execute($sentencia);
				  $correo= $row->fields['email']; 
				  if (!empty($correo)){
				   $mail = new PHPMailer (true); 
				   $mail->IsSMTP(); 
				   try {      
					  $mail->CharSet = "UTF-8"; //importante
					  $mail -> AddAddress ( $row->fields['email'],'Ing.erick');
					  $mail -> Subject = 'Test App de boca junior';
					  $mail -> Body = 'Este es un correo en periodo de prueba aplicacion Boca Juniors';

					  //De parte de quien es el correo
					  $mail->SetFrom("bocajunior@gmail.com", "Notificacion");              

					 //para indicar que el correo es html
					  $mail -> IsHTML (true);   
					  $mail->Host = 'ssl://smtp.gmail.com';
					  $mail->Port = 465;//465;
					  $mail->SMTPAuth = true;
					  $mail->Username = 'mariagabriela3000@gmail.com';
					  $mail->Password = '250781julio';
					  $mail->Timeout=30;
					  if(!$mail->Send()) {
						  $emailnotificacion = "No se pudo enviar notificacion".$mail->ErrorInfo;
					  }
					  else {
						   $emailnotificacion = "Salida Exito";
					  }    
				   } catch (phpmailerException $e) {        
					 CurrentPage()->setMessage("Tipico ".$e->errorMessage()); //Errores de PhpMailer
				   } 
				   catch (Exception $e) {
					 CurrentPage()->setMessage("cualquier cosa ".$e->getMessage()); //Errores de cualquier otra cosa.
				   } 
				   CurrentPage()->setSuccessMessage("Correo Enviado al Alumno");
				  }
			 }

		 //die();
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

		if ($this->PageID == "list" || $this->PageID == "view") { // List/View page only
			if ($this->estado_resp->CurrentValue == 1) {  //si esta revisada por el ejecutivo
				$this->estado_resp->CellAttrs["style"] = "background-color: #ffcccc";
			}
		}
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
