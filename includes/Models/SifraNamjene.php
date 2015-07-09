<?php

/**
 * Created by PhpStorm.
 * User: Sasa
 * Date: 29/06/2015
 * Time: 14:18
 */
class SifraNamjene extends DB
{
    protected static $table_name="sifre_namjene";

    public $id;
    public $sifra;
    public $naziv;
    public $definicija;
    public $klasifikacija;

    public static function dropdown($id)
    {
        $options = self::all();
        foreach ($options as $option){
        echo "<select><option value=$option->id";
        if ($option->id == $id)  echo " selected=\"selected\">";
        echo "$option->sifra.' '.$option->naziv'</option></select>";
        }
    }
}
