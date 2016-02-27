<?php

// Global variable for table object
$afiliado = NULL;

//
// Table class for afiliado
//
class cafiliado extends cTable {
	var $id_afiliado;
	var $dociden_afiliado;
	var $apell_afiliado;
	var $nomb_afiliado;
	var $direcc_afiliado;
	var $email_afiliado;
	var $cel_afiliado;
	var $genero_afiliado;
	var $fe_afiliado;
	var $telemerg_afiliado;
	var $talla_afiliado;
	var $peso_afiliado;
	var $altu_afiliado;
	var $localresdi_afiliado;
	var $telf_fijo_afiliado;
	var $coleg_afiliado;
	var $seguro_afiliado;
	var $tiposangre_afiliado;
	var $contacto_afiliado;
	var $st_afiliado;
	var $foto_afiliado;
	var $st_notificado;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'afiliado';
		$this->TableName = 'afiliado';
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

		// id_afiliado
		$this->id_afiliado = new cField('afiliado', 'afiliado', 'x_id_afiliado', 'id_afiliado', '`id_afiliado`', '`id_afiliado`', 3, -1, FALSE, '`id_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_afiliado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_afiliado'] = &$this->id_afiliado;

		// dociden_afiliado
		$this->dociden_afiliado = new cField('afiliado', 'afiliado', 'x_dociden_afiliado', 'dociden_afiliado', '`dociden_afiliado`', '`dociden_afiliado`', 200, -1, FALSE, '`dociden_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dociden_afiliado'] = &$this->dociden_afiliado;

		// apell_afiliado
		$this->apell_afiliado = new cField('afiliado', 'afiliado', 'x_apell_afiliado', 'apell_afiliado', '`apell_afiliado`', '`apell_afiliado`', 200, -1, FALSE, '`apell_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['apell_afiliado'] = &$this->apell_afiliado;

		// nomb_afiliado
		$this->nomb_afiliado = new cField('afiliado', 'afiliado', 'x_nomb_afiliado', 'nomb_afiliado', '`nomb_afiliado`', '`nomb_afiliado`', 200, -1, FALSE, '`nomb_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nomb_afiliado'] = &$this->nomb_afiliado;

		// direcc_afiliado
		$this->direcc_afiliado = new cField('afiliado', 'afiliado', 'x_direcc_afiliado', 'direcc_afiliado', '`direcc_afiliado`', '`direcc_afiliado`', 201, -1, FALSE, '`direcc_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['direcc_afiliado'] = &$this->direcc_afiliado;

		// email_afiliado
		$this->email_afiliado = new cField('afiliado', 'afiliado', 'x_email_afiliado', 'email_afiliado', '`email_afiliado`', '`email_afiliado`', 200, -1, FALSE, '`email_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->email_afiliado->FldDefaultErrMsg = $Language->Phrase("IncorrectEmail");
		$this->fields['email_afiliado'] = &$this->email_afiliado;

		// cel_afiliado
		$this->cel_afiliado = new cField('afiliado', 'afiliado', 'x_cel_afiliado', 'cel_afiliado', '`cel_afiliado`', '`cel_afiliado`', 200, -1, FALSE, '`cel_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cel_afiliado'] = &$this->cel_afiliado;

		// genero_afiliado
		$this->genero_afiliado = new cField('afiliado', 'afiliado', 'x_genero_afiliado', 'genero_afiliado', '`genero_afiliado`', '`genero_afiliado`', 200, -1, FALSE, '`genero_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['genero_afiliado'] = &$this->genero_afiliado;

		// fe_afiliado
		$this->fe_afiliado = new cField('afiliado', 'afiliado', 'x_fe_afiliado', 'fe_afiliado', '`fe_afiliado`', 'DATE_FORMAT(`fe_afiliado`, \'%Y/%m/%d\')', 133, 5, FALSE, '`fe_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fe_afiliado->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['fe_afiliado'] = &$this->fe_afiliado;

		// telemerg_afiliado
		$this->telemerg_afiliado = new cField('afiliado', 'afiliado', 'x_telemerg_afiliado', 'telemerg_afiliado', '`telemerg_afiliado`', '`telemerg_afiliado`', 200, -1, FALSE, '`telemerg_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['telemerg_afiliado'] = &$this->telemerg_afiliado;

		// talla_afiliado
		$this->talla_afiliado = new cField('afiliado', 'afiliado', 'x_talla_afiliado', 'talla_afiliado', '`talla_afiliado`', '`talla_afiliado`', 200, -1, FALSE, '`talla_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['talla_afiliado'] = &$this->talla_afiliado;

		// peso_afiliado
		$this->peso_afiliado = new cField('afiliado', 'afiliado', 'x_peso_afiliado', 'peso_afiliado', '`peso_afiliado`', '`peso_afiliado`', 200, -1, FALSE, '`peso_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['peso_afiliado'] = &$this->peso_afiliado;

		// altu_afiliado
		$this->altu_afiliado = new cField('afiliado', 'afiliado', 'x_altu_afiliado', 'altu_afiliado', '`altu_afiliado`', '`altu_afiliado`', 4, -1, FALSE, '`altu_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->altu_afiliado->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['altu_afiliado'] = &$this->altu_afiliado;

		// localresdi_afiliado
		$this->localresdi_afiliado = new cField('afiliado', 'afiliado', 'x_localresdi_afiliado', 'localresdi_afiliado', '`localresdi_afiliado`', '`localresdi_afiliado`', 201, -1, FALSE, '`localresdi_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['localresdi_afiliado'] = &$this->localresdi_afiliado;

		// telf_fijo_afiliado
		$this->telf_fijo_afiliado = new cField('afiliado', 'afiliado', 'x_telf_fijo_afiliado', 'telf_fijo_afiliado', '`telf_fijo_afiliado`', '`telf_fijo_afiliado`', 200, -1, FALSE, '`telf_fijo_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['telf_fijo_afiliado'] = &$this->telf_fijo_afiliado;

		// coleg_afiliado
		$this->coleg_afiliado = new cField('afiliado', 'afiliado', 'x_coleg_afiliado', 'coleg_afiliado', '`coleg_afiliado`', '`coleg_afiliado`', 200, -1, FALSE, '`coleg_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['coleg_afiliado'] = &$this->coleg_afiliado;

		// seguro_afiliado
		$this->seguro_afiliado = new cField('afiliado', 'afiliado', 'x_seguro_afiliado', 'seguro_afiliado', '`seguro_afiliado`', '`seguro_afiliado`', 200, -1, FALSE, '`seguro_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['seguro_afiliado'] = &$this->seguro_afiliado;

		// tiposangre_afiliado
		$this->tiposangre_afiliado = new cField('afiliado', 'afiliado', 'x_tiposangre_afiliado', 'tiposangre_afiliado', '`tiposangre_afiliado`', '`tiposangre_afiliado`', 200, -1, FALSE, '`tiposangre_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['tiposangre_afiliado'] = &$this->tiposangre_afiliado;

		// contacto_afiliado
		$this->contacto_afiliado = new cField('afiliado', 'afiliado', 'x_contacto_afiliado', 'contacto_afiliado', '`contacto_afiliado`', '`contacto_afiliado`', 200, -1, FALSE, '`contacto_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['contacto_afiliado'] = &$this->contacto_afiliado;

		// st_afiliado
		$this->st_afiliado = new cField('afiliado', 'afiliado', 'x_st_afiliado', 'st_afiliado', '`st_afiliado`', '`st_afiliado`', 16, -1, FALSE, '`st_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->st_afiliado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['st_afiliado'] = &$this->st_afiliado;

		// foto_afiliado
		$this->foto_afiliado = new cField('afiliado', 'afiliado', 'x_foto_afiliado', 'foto_afiliado', '`foto_afiliado`', '`foto_afiliado`', 200, -1, TRUE, '`foto_afiliado`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['foto_afiliado'] = &$this->foto_afiliado;

		// st_notificado
		$this->st_notificado = new cField('afiliado', 'afiliado', 'x_st_notificado', 'st_notificado', '`st_notificado`', '`st_notificado`', 16, -1, FALSE, '`st_notificado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->st_notificado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['st_notificado'] = &$this->st_notificado;
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

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "historial") {
			$sDetailUrl = $GLOBALS["historial"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&id_afiliado=" . $this->id_afiliado->CurrentValue;
		}
		if ($this->getCurrentDetailTable() == "matricula") {
			$sDetailUrl = $GLOBALS["matricula"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&id_afiliado=" . $this->id_afiliado->CurrentValue;
		}
		if ($this->getCurrentDetailTable() == "representantes") {
			$sDetailUrl = $GLOBALS["representantes"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&id_afiliado=" . $this->id_afiliado->CurrentValue;
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "afiliadolist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`afiliado`";
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
	var $UpdateTable = "`afiliado`";

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

		// Cascade update detail field 'id_afiliado'
		if (!is_null($rsold) && (isset($rs['id_afiliado']) && $rsold['id_afiliado'] <> $rs['id_afiliado'])) {
			if (!isset($GLOBALS["matricula"])) $GLOBALS["matricula"] = new cmatricula();
			$rscascade = array();
			$rscascade['id_afiliado'] = $rs['id_afiliado']; 
			$GLOBALS["matricula"]->Update($rscascade, "`id_afiliado` = " . ew_QuotedValue($rsold['id_afiliado'], EW_DATATYPE_NUMBER));
		}
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('id_afiliado', $rs))
				ew_AddFilter($where, ew_QuotedName('id_afiliado') . '=' . ew_QuotedValue($rs['id_afiliado'], $this->id_afiliado->FldDataType));
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

		// Cascade delete detail table 'historial'
		if (!isset($GLOBALS["historial"])) $GLOBALS["historial"] = new chistorial();
		$rscascade = array();
		$GLOBALS["historial"]->Delete($rscascade, "`id_afiliado` = " . ew_QuotedValue($rs['id_afiliado'], EW_DATATYPE_NUMBER));

		// Cascade delete detail table 'matricula'
		if (!isset($GLOBALS["matricula"])) $GLOBALS["matricula"] = new cmatricula();
		$rscascade = array();
		$GLOBALS["matricula"]->Delete($rscascade, "`id_afiliado` = " . ew_QuotedValue($rs['id_afiliado'], EW_DATATYPE_NUMBER));

		// Cascade delete detail table 'representantes'
		if (!isset($GLOBALS["representantes"])) $GLOBALS["representantes"] = new crepresentantes();
		$rscascade = array();
		$GLOBALS["representantes"]->Delete($rscascade, "`id_afiliado` = " . ew_QuotedValue($rs['id_afiliado'], EW_DATATYPE_NUMBER));
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id_afiliado` = @id_afiliado@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_afiliado->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_afiliado@", ew_AdjustSql($this->id_afiliado->CurrentValue), $sKeyFilter); // Replace key value
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
			return "afiliadolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "afiliadolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("afiliadoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("afiliadoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "afiliadoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("afiliadoedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("afiliadoedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("afiliadoadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("afiliadoadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("afiliadodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_afiliado->CurrentValue)) {
			$sUrl .= "id_afiliado=" . urlencode($this->id_afiliado->CurrentValue);
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
			$arKeys[] = @$_GET["id_afiliado"]; // id_afiliado

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
			$this->id_afiliado->CurrentValue = $key;
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
		$this->st_notificado->setDbValue($rs->fields('st_notificado'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_afiliado

		$this->id_afiliado->CellCssStyle = "white-space: nowrap;";

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
		// id_afiliado

		$this->id_afiliado->ViewValue = $this->id_afiliado->CurrentValue;
		$this->id_afiliado->ViewCustomAttributes = "";

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

		// id_afiliado
		$this->id_afiliado->LinkCustomAttributes = "";
		$this->id_afiliado->HrefValue = "";
		$this->id_afiliado->TooltipValue = "";

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
		$this->foto_afiliado->LinkAttrs["data-tooltip-id"] = "tt_afiliado_x" . @$this->RowCnt . "_foto_afiliado";
		$this->foto_afiliado->LinkAttrs["data-tooltip-width"] = $this->foto_afiliado->TooltipWidth;
		$this->foto_afiliado->LinkAttrs["data-placement"] = "right";

		// st_notificado
		$this->st_notificado->LinkCustomAttributes = "";
		$this->st_notificado->HrefValue = "";
		$this->st_notificado->TooltipValue = "";

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
				if ($this->id_afiliado->Exportable) $Doc->ExportCaption($this->id_afiliado);
				if ($this->dociden_afiliado->Exportable) $Doc->ExportCaption($this->dociden_afiliado);
				if ($this->apell_afiliado->Exportable) $Doc->ExportCaption($this->apell_afiliado);
				if ($this->nomb_afiliado->Exportable) $Doc->ExportCaption($this->nomb_afiliado);
				if ($this->direcc_afiliado->Exportable) $Doc->ExportCaption($this->direcc_afiliado);
				if ($this->email_afiliado->Exportable) $Doc->ExportCaption($this->email_afiliado);
				if ($this->cel_afiliado->Exportable) $Doc->ExportCaption($this->cel_afiliado);
				if ($this->genero_afiliado->Exportable) $Doc->ExportCaption($this->genero_afiliado);
				if ($this->fe_afiliado->Exportable) $Doc->ExportCaption($this->fe_afiliado);
				if ($this->telemerg_afiliado->Exportable) $Doc->ExportCaption($this->telemerg_afiliado);
				if ($this->talla_afiliado->Exportable) $Doc->ExportCaption($this->talla_afiliado);
				if ($this->peso_afiliado->Exportable) $Doc->ExportCaption($this->peso_afiliado);
				if ($this->altu_afiliado->Exportable) $Doc->ExportCaption($this->altu_afiliado);
				if ($this->localresdi_afiliado->Exportable) $Doc->ExportCaption($this->localresdi_afiliado);
				if ($this->telf_fijo_afiliado->Exportable) $Doc->ExportCaption($this->telf_fijo_afiliado);
				if ($this->coleg_afiliado->Exportable) $Doc->ExportCaption($this->coleg_afiliado);
				if ($this->seguro_afiliado->Exportable) $Doc->ExportCaption($this->seguro_afiliado);
				if ($this->tiposangre_afiliado->Exportable) $Doc->ExportCaption($this->tiposangre_afiliado);
				if ($this->contacto_afiliado->Exportable) $Doc->ExportCaption($this->contacto_afiliado);
				if ($this->st_afiliado->Exportable) $Doc->ExportCaption($this->st_afiliado);
				if ($this->foto_afiliado->Exportable) $Doc->ExportCaption($this->foto_afiliado);
				if ($this->st_notificado->Exportable) $Doc->ExportCaption($this->st_notificado);
			} else {
				if ($this->dociden_afiliado->Exportable) $Doc->ExportCaption($this->dociden_afiliado);
				if ($this->apell_afiliado->Exportable) $Doc->ExportCaption($this->apell_afiliado);
				if ($this->nomb_afiliado->Exportable) $Doc->ExportCaption($this->nomb_afiliado);
				if ($this->direcc_afiliado->Exportable) $Doc->ExportCaption($this->direcc_afiliado);
				if ($this->email_afiliado->Exportable) $Doc->ExportCaption($this->email_afiliado);
				if ($this->cel_afiliado->Exportable) $Doc->ExportCaption($this->cel_afiliado);
				if ($this->genero_afiliado->Exportable) $Doc->ExportCaption($this->genero_afiliado);
				if ($this->fe_afiliado->Exportable) $Doc->ExportCaption($this->fe_afiliado);
				if ($this->telemerg_afiliado->Exportable) $Doc->ExportCaption($this->telemerg_afiliado);
				if ($this->talla_afiliado->Exportable) $Doc->ExportCaption($this->talla_afiliado);
				if ($this->peso_afiliado->Exportable) $Doc->ExportCaption($this->peso_afiliado);
				if ($this->altu_afiliado->Exportable) $Doc->ExportCaption($this->altu_afiliado);
				if ($this->localresdi_afiliado->Exportable) $Doc->ExportCaption($this->localresdi_afiliado);
				if ($this->telf_fijo_afiliado->Exportable) $Doc->ExportCaption($this->telf_fijo_afiliado);
				if ($this->coleg_afiliado->Exportable) $Doc->ExportCaption($this->coleg_afiliado);
				if ($this->seguro_afiliado->Exportable) $Doc->ExportCaption($this->seguro_afiliado);
				if ($this->tiposangre_afiliado->Exportable) $Doc->ExportCaption($this->tiposangre_afiliado);
				if ($this->contacto_afiliado->Exportable) $Doc->ExportCaption($this->contacto_afiliado);
				if ($this->st_afiliado->Exportable) $Doc->ExportCaption($this->st_afiliado);
				if ($this->foto_afiliado->Exportable) $Doc->ExportCaption($this->foto_afiliado);
				if ($this->st_notificado->Exportable) $Doc->ExportCaption($this->st_notificado);
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
					if ($this->id_afiliado->Exportable) $Doc->ExportField($this->id_afiliado);
					if ($this->dociden_afiliado->Exportable) $Doc->ExportField($this->dociden_afiliado);
					if ($this->apell_afiliado->Exportable) $Doc->ExportField($this->apell_afiliado);
					if ($this->nomb_afiliado->Exportable) $Doc->ExportField($this->nomb_afiliado);
					if ($this->direcc_afiliado->Exportable) $Doc->ExportField($this->direcc_afiliado);
					if ($this->email_afiliado->Exportable) $Doc->ExportField($this->email_afiliado);
					if ($this->cel_afiliado->Exportable) $Doc->ExportField($this->cel_afiliado);
					if ($this->genero_afiliado->Exportable) $Doc->ExportField($this->genero_afiliado);
					if ($this->fe_afiliado->Exportable) $Doc->ExportField($this->fe_afiliado);
					if ($this->telemerg_afiliado->Exportable) $Doc->ExportField($this->telemerg_afiliado);
					if ($this->talla_afiliado->Exportable) $Doc->ExportField($this->talla_afiliado);
					if ($this->peso_afiliado->Exportable) $Doc->ExportField($this->peso_afiliado);
					if ($this->altu_afiliado->Exportable) $Doc->ExportField($this->altu_afiliado);
					if ($this->localresdi_afiliado->Exportable) $Doc->ExportField($this->localresdi_afiliado);
					if ($this->telf_fijo_afiliado->Exportable) $Doc->ExportField($this->telf_fijo_afiliado);
					if ($this->coleg_afiliado->Exportable) $Doc->ExportField($this->coleg_afiliado);
					if ($this->seguro_afiliado->Exportable) $Doc->ExportField($this->seguro_afiliado);
					if ($this->tiposangre_afiliado->Exportable) $Doc->ExportField($this->tiposangre_afiliado);
					if ($this->contacto_afiliado->Exportable) $Doc->ExportField($this->contacto_afiliado);
					if ($this->st_afiliado->Exportable) $Doc->ExportField($this->st_afiliado);
					if ($this->foto_afiliado->Exportable) $Doc->ExportField($this->foto_afiliado);
					if ($this->st_notificado->Exportable) $Doc->ExportField($this->st_notificado);
				} else {
					if ($this->dociden_afiliado->Exportable) $Doc->ExportField($this->dociden_afiliado);
					if ($this->apell_afiliado->Exportable) $Doc->ExportField($this->apell_afiliado);
					if ($this->nomb_afiliado->Exportable) $Doc->ExportField($this->nomb_afiliado);
					if ($this->direcc_afiliado->Exportable) $Doc->ExportField($this->direcc_afiliado);
					if ($this->email_afiliado->Exportable) $Doc->ExportField($this->email_afiliado);
					if ($this->cel_afiliado->Exportable) $Doc->ExportField($this->cel_afiliado);
					if ($this->genero_afiliado->Exportable) $Doc->ExportField($this->genero_afiliado);
					if ($this->fe_afiliado->Exportable) $Doc->ExportField($this->fe_afiliado);
					if ($this->telemerg_afiliado->Exportable) $Doc->ExportField($this->telemerg_afiliado);
					if ($this->talla_afiliado->Exportable) $Doc->ExportField($this->talla_afiliado);
					if ($this->peso_afiliado->Exportable) $Doc->ExportField($this->peso_afiliado);
					if ($this->altu_afiliado->Exportable) $Doc->ExportField($this->altu_afiliado);
					if ($this->localresdi_afiliado->Exportable) $Doc->ExportField($this->localresdi_afiliado);
					if ($this->telf_fijo_afiliado->Exportable) $Doc->ExportField($this->telf_fijo_afiliado);
					if ($this->coleg_afiliado->Exportable) $Doc->ExportField($this->coleg_afiliado);
					if ($this->seguro_afiliado->Exportable) $Doc->ExportField($this->seguro_afiliado);
					if ($this->tiposangre_afiliado->Exportable) $Doc->ExportField($this->tiposangre_afiliado);
					if ($this->contacto_afiliado->Exportable) $Doc->ExportField($this->contacto_afiliado);
					if ($this->st_afiliado->Exportable) $Doc->ExportField($this->st_afiliado);
					if ($this->foto_afiliado->Exportable) $Doc->ExportField($this->foto_afiliado);
					if ($this->st_notificado->Exportable) $Doc->ExportField($this->st_notificado);
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
