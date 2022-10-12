   <?php
    function _group_by($array, $key) {
    $return = array();
    foreach($array as $val) {
        $return[$val[$key]][] = $val;
    }
    return $return;
}

$list= [
[   'No' => 101,
    'Paper_id' => 'WE3P-1',
    'Title' => "a1",
    'Author' => 'ABC',
    'Aff_list' => "University of South Florida, Tampa, United States",
    'Abstracts' => "SLA"
] ,
[   'No' => 101,
    'Paper_id' => 'WE3P-1',
    'Title' => "a2",
    'Author' => 'DEF',
    'Aff_list' => "University of South Florida, Tampa, United States",
    'Abstracts' => "SLA"
] ,
[    'No' => 104, 
    'Paper_id' => 'TUSA-3',
    'Title' => "a3",
    'Author' => 'GH1',
    'Aff_list' => "University of Alcala, Alcala de Henares, Spain",
    'Abstracts' => "Microwave"
] ];
echo '<pre>';
print_r(_group_by($list, 'No'));
echo '</pre>';