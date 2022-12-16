<?php 
addEventHandler("main", "OnBeforeEventAdd", "ChangeDataFromMail");
function ChangeDataFromMail(&$event, &$lid, &$arFields) {
    if ($event !== "FEEDBACK_FORM") 
        return;
    if ($GLOBALS["USER"]->IsAuthorized())
        $arFields["AUTHOR"] = "Пользователь авторизован: {$GLOBALS["USER"]->GetID()} ({$GLOBALS["USER"]->GetLogin()})
         {$GLOBALS["USER"]->GetFullName()}, данные из формы: {$arFields["AUTHOR"]}";
    else
        $arFields["AUTHOR"] = "Пользователь не авторизован, данные из формы: {$arFields["AUTHOR"]}";        
    CEventLog::Add([
        "SEVERITY" => "INFO",
        "AUDIT_TYPE_ID" => "CHANGE_MAIL_DATA",
        "MODULE_ID" => "main",
        "ITEM_ID" => $GLOBALS["USER"]->GetID(),
        "DESCRIPTION" => "Замена данных в отсылаемом письме – {$arFields["AUTHOR"]}"
    ]);
};
?>