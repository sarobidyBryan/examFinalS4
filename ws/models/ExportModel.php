<?php

class ExportModel
{
public static function formatVal($val, $unit = '')
{
    if ($val === null) return '-'; // PAS == mais ===
    $formatted = number_format($val, 0, ',', ' ');
    return $formatted . ($unit ? ' ' . $unit : '');
}


    public static function pair($valA, $valB)
    {
        return self::formatVal($valA, 'Ar') . ' / ' . self::formatVal($valB, 'Ar');
    }
}
