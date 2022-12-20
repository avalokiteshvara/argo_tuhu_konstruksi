<?php

function myfield_data($table)
{
    $CI = &get_instance();
    $sql = 'SELECT COLUMN_KEY,
                 COLUMN_COMMENT,
                 COLUMN_NAME,
                 DATA_TYPE,
                 CHARACTER_MAXIMUM_LENGTH,
                 NUMERIC_PRECISION,
                 COLUMN_DEFAULT,
                 COLUMN_TYPE
          FROM INFORMATION_SCHEMA.Columns
          WHERE UPPER(TABLE_NAME) = '. $CI->db->escape(strtoupper($table));

    if (($query = $CI->db->query($sql)) === false) {
        return false;
    }
    $query = $query->result_object();

    $retval = array();
    for ($i = 0, $c = count($query); $i < $c; ++$i) {
        $retval[$i] = new stdClass();
        $retval[$i]->name = $query[$i]->COLUMN_NAME;
        $retval[$i]->type = $query[$i]->DATA_TYPE;
        $retval[$i]->max_length = ($query[$i]->CHARACTER_MAXIMUM_LENGTH > 0) ? $query[$i]->CHARACTER_MAXIMUM_LENGTH : $query[$i]->NUMERIC_PRECISION;
        $retval[$i]->default = $query[$i]->COLUMN_DEFAULT;
        $retval[$i]->comment = $query[$i]->COLUMN_COMMENT;

        if ($query[$i]->DATA_TYPE === 'enum') {
            $str = str_replace(array('enum', '(', "'", ')'), array(''), $query[$i]->COLUMN_TYPE);
            $retval[$i]->enum_vals = explode(',', $str);
        }
        $retval[$i]->primary_key = (int) ($query[$i]->COLUMN_KEY === 'PRI');
    }

    return $retval;
}

function IsNullOrEmptyString($question)
{
    return (!isset($question) || trim($question) === '');
}

function mysqldateformat($tanggal = '00-00-0000'){
  $exp_tanggal = explode('-',$tanggal);
  //tahun-bulan-tanggal
  return $exp_tanggal[2] . '-' . $exp_tanggal[1] . '-' . $exp_tanggal[0];
}

function bulan_indo($param)
{
    $arr_bulan = array('Januari','Februari','Maret',
                     'April','Mei','Juni','Juli',
                     'Agustus','September','Oktober',
                     'November','Desember', );

    return $arr_bulan[$param - 1];
}
