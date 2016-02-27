<!-- Begin Main Menu -->
<div class="ewMenu">
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(14, $Language->MenuPhrase("14", "MenuText"), "v_novedadeslist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(13, $Language->MenuPhrase("13", "MenuText"), "notificacionlist.php", -1, "", AllowListMenu('{CD332243-68AB-4368-BF10-F24F7E84F4D6}notificacion'), FALSE);
$RootMenu->AddMenuItem(12, $Language->MenuPhrase("12", "MenuText"), "matriculalist.php?cmd=resetall", -1, "", AllowListMenu('{CD332243-68AB-4368-BF10-F24F7E84F4D6}matricula'), FALSE);
$RootMenu->AddMenuItem(6, $Language->MenuPhrase("6", "MenuText"), "novedadlist.php", -1, "", AllowListMenu('{CD332243-68AB-4368-BF10-F24F7E84F4D6}novedad'), FALSE);
$RootMenu->AddMenuItem(7, $Language->MenuPhrase("7", "MenuText"), "respuestalist.php?cmd=resetall", -1, "", AllowListMenu('{CD332243-68AB-4368-BF10-F24F7E84F4D6}respuesta'), FALSE);
$RootMenu->AddMenuItem(9, $Language->MenuPhrase("9", "MenuText"), "userlevelslist.php", -1, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(11, $Language->MenuPhrase("11", "MenuText"), "x_estado_respuestalist.php", -1, "", AllowListMenu('{CD332243-68AB-4368-BF10-F24F7E84F4D6}x_estado_respuesta'), FALSE);
$RootMenu->AddMenuItem(1, $Language->MenuPhrase("1", "MenuText"), "afiliadolist.php", -1, "", AllowListMenu('{CD332243-68AB-4368-BF10-F24F7E84F4D6}afiliado'), FALSE);
$RootMenu->AddMenuItem(2, $Language->MenuPhrase("2", "MenuText"), "empleadoslist.php", -1, "", AllowListMenu('{CD332243-68AB-4368-BF10-F24F7E84F4D6}empleados'), FALSE);
$RootMenu->AddMenuItem(3, $Language->MenuPhrase("3", "MenuText"), "historiallist.php?cmd=resetall", -1, "", AllowListMenu('{CD332243-68AB-4368-BF10-F24F7E84F4D6}historial'), FALSE);
$RootMenu->AddMenuItem(4, $Language->MenuPhrase("4", "MenuText"), "planlist.php", -1, "", AllowListMenu('{CD332243-68AB-4368-BF10-F24F7E84F4D6}plan'), FALSE);
$RootMenu->AddMenuItem(5, $Language->MenuPhrase("5", "MenuText"), "representanteslist.php?cmd=resetall", -1, "", AllowListMenu('{CD332243-68AB-4368-BF10-F24F7E84F4D6}representantes'), FALSE);
$RootMenu->AddMenuItem(-1, $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
</div>
<!-- End Main Menu -->
