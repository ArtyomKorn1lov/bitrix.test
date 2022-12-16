<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
====
<p><b><?=GetMessage("SIMPLECOMP_CAT_TITLE")?></b></p>
<?php if(count($arResult["NEWS"]) > 0) { ?>
    <ul>
        <?php foreach($arResult["NEWS"] as $arNew) { ?>
            <li>
                <b>
                    <?= $arNew["NAME"]; ?>
                </b>
                - <?= date("d.m.Y", strtotime($arNew["ACTIVE_FROM"])) ?>
                <?php if(count($arNew["SECTIONS"]) > 0) { ?>
                    (<?php for($count = 0; $count < count($arNew["SECTIONS"]); $count++) { ?><?php if($count == count($arNew["SECTIONS"]) - 1) { ?><?= $arNew["SECTIONS"][$count]; ?><?php } else { ?><?= $arNew["SECTIONS"][$count]; ?>, <?php } ?><?php } ?>)
                <?php } ?>    
            </li>
            <?php if(count($arNew["PRODUCTS"]) > 0) { ?>
                <ul>
                <?php foreach($arNew["PRODUCTS"] as $arProduct) { ?>
                    <li>
                        <?php if(!is_null($arProduct["NAME"])) { ?>
                            <?= $arProduct["NAME"]; ?> -
                        <?php } ?>
                        <?php if(!is_null($arProduct["PROPERTY_PRICE_VALUE"])) { ?>
                            <?= $arProduct["PROPERTY_PRICE_VALUE"]; ?> -
                        <?php } ?>
                        <?php if(!is_null($arProduct["PROPERTY_MATERIAL_VALUE"])) { ?>
                            <?= $arProduct["PROPERTY_MATERIAL_VALUE"]; ?> -
                        <?php } ?>
                        <?php if(!is_null($arProduct["PROPERTY_ARTNUMBER_VALUE"])) { ?>
                            <?= $arProduct["PROPERTY_ARTNUMBER_VALUE"]; ?> 
                        <?php } ?>
                    </li>
                <?php } ?>
                </ul>
            <?php } ?>
        <?php } ?>
    </ul>
<?php } ?>
