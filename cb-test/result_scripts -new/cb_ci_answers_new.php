<?php
//result array

$solution_array = array();

/**
 * Format of the solution array
 * serial_number ==ZERO INDEX
 *     $solution['CP']['serial_number']['factor_no'] = 0; ==ZERO INDEX
 * $solution['CP']['serial_number']['question_no'] = 0;  ==ZERO INDEX  
 * $solution['CP']['serial_number']['first_choice'] = 'C';
 */


//Elementary dimensions started
$elementary_dimensions = array(
    0 => array(
        1 => array('answer' => 'C'),
        2 => array('answer' => 'P'),
        3 => array('answer' => 'P'),
        4 => array('answer' => 'P'),
        5 => array('answer' => 'C'),
        6 => array('answer' => 'P'),
        7 => array('answer' => 'C'),
        8 => array('answer' => 'P'),
        9 => array('answer' => 'P'),
        10 => array('answer' => 'P'),
        11 => array('answer' => 'P'),
        12 => array('answer' => 'P'),
        13 => array('answer' => 'C'),
        14 => array('answer' => 'C'),
        15 => array('answer' => 'P'),
        16 => array('answer' => 'C'),
        17 => array('answer' => 'C'),
        18 => array('answer' => 'P'),
        19 => array('answer' => 'C'),
        20 => array('answer' => 'C'),
        ),
    1 => array(
        1 => array('answer' => 'D'),
        2 => array('answer' => 'M'),
        3 => array('answer' => 'D'),
        4 => array('answer' => 'D'),
        5 => array('answer' => 'D'),
        6 => array('answer' => 'D'),
        7 => array('answer' => 'D'),
        8 => array('answer' => 'D'),
        9 => array('answer' => 'D'),
        10 => array('answer' => 'M'),
        11 => array('answer' => 'D'),
        12 => array('answer' => 'D'),
        13 => array('answer' => 'D'),
        14 => array('answer' => 'M'),
        15 => array('answer' => 'D'),
        16 => array('answer' => 'D'),
        17 => array('answer' => 'D'),
        18 => array('answer' => 'D'),
        19 => array('answer' => 'D'),
        20 => array('answer' => 'D'),
        ),
    2 => array(
        1 => array('answer' => 'E'),
        2 => array('answer' => 'E'),
        3 => array('answer' => 'E'),
        4 => array('answer' => 'I'),
        5 => array('answer' => 'E'),
        6 => array('answer' => 'E'),
        7 => array('answer' => 'E'),
        8 => array('answer' => 'E'),
        9 => array('answer' => 'E'),
        10 => array('answer' => 'E'),
        11 => array('answer' => 'E'),
        12 => array('answer' => 'E'),
        13 => array('answer' => 'E'),
        14 => array('answer' => 'E'),
        15 => array('answer' => 'I'),
        16 => array('answer' => 'I'),
        17 => array('answer' => 'E'),
        18 => array('answer' => 'E'),
        19 => array('answer' => 'I'),
        20 => array('answer' => 'E'),
        ),
    3 => array(
        1 => array('answer' => 'Q'),
        2 => array('answer' => 'S'),
        3 => array('answer' => 'S'),
        4 => array('answer' => 'Q'),
        5 => array('answer' => 'S'),
        6 => array('answer' => 'S'),
        7 => array('answer' => 'Q'),
        8 => array('answer' => 'S'),
        9 => array('answer' => 'Q'),
        10 => array('answer' => 'S'),
        11 => array('answer' => 'Q'),
        12 => array('answer' => 'S'),
        13 => array('answer' => 'S'),
        14 => array('answer' => 'Q'),
        ),
    4 => array(
        1 => array('answer' => 'R'),
        2 => array('answer' => 'R'),
        3 => array('answer' => 'R'),
        4 => array('answer' => 'R'),
        5 => array('answer' => 'R'),
        6 => array('answer' => 'R'),
        7 => array('answer' => 'R'),
        8 => array('answer' => 'F'),
        9 => array('answer' => 'F'),
        10 => array('answer' => 'R'),
        11 => array('answer' => 'R'),
        12 => array('answer' => 'R'),
        13 => array('answer' => 'R'),
        14 => array('answer' => 'F'),
        15 => array('answer' => 'F'),
        16 => array('answer' => 'R'),
        17 => array('answer' => 'R'),
        18 => array('answer' => 'R'),
        19 => array('answer' => 'R'),
        20 => array('answer' => 'R'),
        ),
    );


//for CP
for ($i = 0; $i < 20; $i++) {
    $solution_array['CP'][$i]['factor_no'] = 0;
    $solution_array['CP'][$i]['question_no'] = $i;
    $solution_array['CP'][$i]['first_choice'] = $elementary_dimensions[0][($i + 1)]['answer'];
}

//for DM
for ($i = 0; $i < 20; $i++) {
    $solution_array['DM'][$i]['factor_no'] = 1;
    $solution_array['DM'][$i]['question_no'] = $i;
    $solution_array['DM'][$i]['first_choice'] = $elementary_dimensions[1][($i + 1)]['answer'];
}

//for EI
for ($i = 0; $i < 20; $i++) {
    $solution_array['EI'][$i]['factor_no'] = 2;
    $solution_array['EI'][$i]['question_no'] = $i;
    $solution_array['EI'][$i]['first_choice'] = $elementary_dimensions[2][($i + 1)]['answer'];
}

//for QS
for ($i = 0; $i < 14; $i++) {
    $solution_array['QS'][$i]['factor_no'] = 3;
    $solution_array['QS'][$i]['question_no'] = $i;
    $solution_array['QS'][$i]['first_choice'] = $elementary_dimensions[3][($i + 1)]['answer'];
}

//for RF
for ($i = 0; $i < 20; $i++) {
    $solution_array['RF'][$i]['factor_no'] = 4;
    $solution_array['RF'][$i]['question_no'] = $i;
    $solution_array['RF'][$i]['first_choice'] = $elementary_dimensions[4][($i + 1)]['answer'];
}


//Elementary elements ended


//BC - Body Confidence
$solution_array['BC'] = array(
    0 => array(
        'factor_no' => 0,
        'question_no' => 9,
        'first_choice' => 'Y',
        ),
    1 => array(
        'factor_no' => 2,
        'question_no' => 1,
        'first_choice' => 'Y',
        ),
    2 => array(
        'factor_no' => 2,
        'question_no' => 4,
        'first_choice' => 'Y',
        ),
    3 => array(
        'factor_no' => 2,
        'question_no' => 5,
        'first_choice' => 'Y',
        ),
    4 => array(
        'factor_no' => 2,
        'question_no' => 16,
        'first_choice' => 'Y',
        ),
    5 => array(
        'factor_no' => 2,
        'question_no' => 19,
        'first_choice' => 'Y',
        ),
    6 => array(
        'factor_no' => 3,
        'question_no' => 0,
        'first_choice' => 'N',
        ),
    7 => array(
        'factor_no' => 3,
        'question_no' => 4,
        'first_choice' => 'Y',
        ),
    8 => array(
        'factor_no' => 3,
        'question_no' => 6,
        'first_choice' => 'Y',
        ),
    9 => array(
        'factor_no' => 3,
        'question_no' => 13,
        'first_choice' => 'Y',
        ),
    10 => array(
        'factor_no' => 4,
        'question_no' => 10,
        'first_choice' => 'Y',
        ),
    );

//PS - Problem Solving
$solution_array['PS'] = array(
    0 => array(
        'factor_no' => 2,
        'question_no' => 10,
        'first_choice' => 'Y',
        ),
    1 => array(
        'factor_no' => 2,
        'question_no' => 12,
        'first_choice' => 'Y',
        ),
    2 => array(
        'factor_no' => 3,
        'question_no' => 8,
        'first_choice' => 'Y',
        ),
    3 => array(
        'factor_no' => 3,
        'question_no' => 9,
        'first_choice' => 'Y',
        ),
    4 => array(
        'factor_no' => 3,
        'question_no' => 13,
        'first_choice' => 'Y',
        ),
    5 => array(
        'factor_no' => 4,
        'question_no' => 0,
        'first_choice' => 'Y',
        ),
    6 => array(
        'factor_no' => 4,
        'question_no' => 2,
        'first_choice' => 'Y',
        ),
    7 => array(
        'factor_no' => 4,
        'question_no' => 4,
        'first_choice' => 'Y',
        ),
    8 => array(
        'factor_no' => 4,
        'question_no' => 6,
        'first_choice' => 'N',
        ),
    9 => array(
        'factor_no' => 4,
        'question_no' => 7,
        'first_choice' => 'N',
        ),
    );

//Communication Skill
$solution_array['CS'] = array(
    0 => array(
        'factor_no' => 2,
        'question_no' => 0,
        'first_choice' => 'Y',
        ),
    1 => array(
        'factor_no' => 2,
        'question_no' => 2,
        'first_choice' => 'Y',
        ),
    2 => array(
        'factor_no' => 2,
        'question_no' => 4,
        'first_choice' => 'Y',
        ),
    3 => array(
        'factor_no' => 2,
        'question_no' => 5,
        'first_choice' => 'Y',
        ),
    4 => array(
        'factor_no' => 2,
        'question_no' => 8,
        'first_choice' => 'Y',
        ),
    5 => array(
        'factor_no' => 2,
        'question_no' => 10,
        'first_choice' => 'Y',
        ),
    6 => array(
        'factor_no' => 2,
        'question_no' => 12,
        'first_choice' => 'Y',
        ),
    7 => array(
        'factor_no' => 2,
        'question_no' => 14,
        'first_choice' => 'N',
        ),
    8 => array(
        'factor_no' => 2,
        'question_no' => 16,
        'first_choice' => 'Y',
        ),
    9 => array(
        'factor_no' => 2,
        'question_no' => 17,
        'first_choice' => 'Y',
        ),
    );


//Discipline
$solution_array['D'] = array(
    0 => array(
        'factor_no' => 1,
        'question_no' => 0,
        'first_choice' => 'Y',
        ),
    1 => array(
        'factor_no' => 1,
        'question_no' => 2,
        'first_choice' => 'Y',
        ),
    2 => array(
        'factor_no' => 1,
        'question_no' => 3,
        'first_choice' => 'Y',
        ),
    3 => array(
        'factor_no' => 1,
        'question_no' => 4,
        'first_choice' => 'Y',
        ),
    4 => array(
        'factor_no' => 1,
        'question_no' => 6,
        'first_choice' => 'Y',
        ),
    5 => array(
        'factor_no' => 1,
        'question_no' => 7,
        'first_choice' => 'Y',
        ),
    6 => array(
        'factor_no' => 1,
        'question_no' => 8,
        'first_choice' => 'Y',
        ),
    7 => array(
        'factor_no' => 1,
        'question_no' => 11,
        'first_choice' => 'Y',
        ),
    8 => array(
        'factor_no' => 1,
        'question_no' => 13,
        'first_choice' => 'N',
        ),
    9 => array(
        'factor_no' => 1,
        'question_no' => 17,
        'first_choice' => 'Y',
        ),
    );


//Creativity
$solution_array['C'] = array(
    0 => array(
        'factor_no' => 0,
        'question_no' => 0,
        'first_choice' => 'Y',
        ),
    1 => array(
        'factor_no' => 0,
        'question_no' => 3,
        'first_choice' => 'N',
        ),
    2 => array(
        'factor_no' => 0,
        'question_no' => 5,
        'first_choice' => 'N',
        ),
    3 => array(
        'factor_no' => 0,
        'question_no' => 7,
        'first_choice' => 'N',
        ),
    4 => array(
        'factor_no' => 0,
        'question_no' => 10,
        'first_choice' => 'N',
        ),
    5 => array(
        'factor_no' => 0,
        'question_no' => 11,
        'first_choice' => 'N',
        ),
    6 => array(
        'factor_no' => 0,
        'question_no' => 12,
        'first_choice' => 'Y',
        ),
    7 => array(
        'factor_no' => 0,
        'question_no' => 15,
        'first_choice' => 'Y',
        ),
    8 => array(
        'factor_no' => 0,
        'question_no' => 17,
        'first_choice' => 'N',
        ),
    9 => array(
        'factor_no' => 0,
        'question_no' => 19,
        'first_choice' => 'Y',
        ),
    );


//Emotional Maturity
$solution_array['EM'] = array(
    0 => array(
        'factor_no' => 1,
        'question_no' => 5,
        'first_choice' => 'N',
        ),
    1 => array(
        'factor_no' => 1,
        'question_no' => 11,
        'first_choice' => 'N',
        ),
    2 => array(
        'factor_no' => 2,
        'question_no' => 3,
        'first_choice' => 'N',
        ),
    3 => array(
        'factor_no' => 2,
        'question_no' => 6,
        'first_choice' => 'N',
        ),
    4 => array(
        'factor_no' => 2,
        'question_no' => 10,
        'first_choice' => 'Y',
        ),
    5 => array(
        'factor_no' => 2,
        'question_no' => 16,
        'first_choice' => 'Y',
        ),
    6 => array(
        'factor_no' => 3,
        'question_no' => 7,
        'first_choice' => 'Y',
        ),
    7 => array(
        'factor_no' => 4,
        'question_no' => 4,
        'first_choice' => 'N',
        ),
    8 => array(
        'factor_no' => 4,
        'question_no' => 15,
        'first_choice' => 'N',
        ),
    );
    
    
 //Flexibility
$solution_array['FL'] = array(
    0 => array(
        'factor_no' => 4,
        'question_no' => 1,
        'first_choice' => 'N',
        ),
    1 => array(
        'factor_no' => 4,
        'question_no' => 2,
        'first_choice' => 'Y',
        ),
    2 => array(
        'factor_no' => 4,
        'question_no' => 5,
        'first_choice' => 'N',
        ),
    3 => array(
        'factor_no' => 4,
        'question_no' => 6,
        'first_choice' => 'N',
        ),
    4 => array(
        'factor_no' => 4,
        'question_no' => 9,
        'first_choice' => 'N',
        ),
    5 => array(
        'factor_no' => 4,
        'question_no' => 11,
        'first_choice' => 'N',
        ),
    6 => array(
        'factor_no' => 4,
        'question_no' => 12,
        'first_choice' => 'N',
        ),
    7 => array(
        'factor_no' => 4,
        'question_no' => 14,
        'first_choice' => 'Y',
        ),
    8 => array(
        'factor_no' => 4,
        'question_no' => 15,
        'first_choice' => 'N',
        ),
    9 => array(
        'factor_no' => 4,
        'question_no' => 18,
        'first_choice' => 'Y',
        ),
    );   

//Stress Management
$solution_array['SM'] = array(
    0 => array(
        'factor_no' => 1,
        'question_no' => 1,
        'first_choice' => 'Y',
        ),
    1 => array(
        'factor_no' => 1,
        'question_no' => 3,
        'first_choice' => 'Y',
        ),
    2 => array(
        'factor_no' => 1,
        'question_no' => 8,
        'first_choice' => 'Y',
        ),
    3 => array(
        'factor_no' => 1,
        'question_no' => 10,
        'first_choice' => 'Y',
        ),
    4 => array(
        'factor_no' => 2,
        'question_no' => 4,
        'first_choice' => 'Y',
        ),
    5 => array(
        'factor_no' => 3,
        'question_no' => 7,
        'first_choice' => 'Y',
        ),
    6 => array(
        'factor_no' => 3,
        'question_no' => 8,
        'first_choice' => 'N',
        ),
    7 => array(
        'factor_no' => 4,
        'question_no' => 2,
        'first_choice' => 'N',
        ),
    );   
    

//Time Management
$solution_array['TM'] = array(
    0 => array(
        'factor_no' => 4,
        'question_no' => 0,
        'first_choice' => 'Y',
        ),
    1 => array(
        'factor_no' => 4,
        'question_no' => 1,
        'first_choice' => 'Y',
        ),
    2 => array(
        'factor_no' => 4,
        'question_no' => 3,
        'first_choice' => 'Y',
        ),
    3 => array(
        'factor_no' => 4,
        'question_no' => 5,
        'first_choice' => 'Y',
        ),
    4 => array(
        'factor_no' => 4,
        'question_no' => 6,
        'first_choice' => 'Y',
        ),
    5 => array(
        'factor_no' => 4,
        'question_no' => 7,
        'first_choice' => 'N',
        ),
    6 => array(
        'factor_no' => 4,
        'question_no' => 8,
        'first_choice' => 'Y',
        ),
    7 => array(
        'factor_no' => 4,
        'question_no' => 10,
        'first_choice' => 'Y',
        ),
    8 => array(
        'factor_no' => 4,
        'question_no' => 13,
        'first_choice' => 'N',
        ),
    9 => array(
        'factor_no' => 4,
        'question_no' => 15,
        'first_choice' => 'Y',
        ),
    );   

$interpretation= array(
    'personality' => array(
            
        ),
        
    'aptitude' => array(
            
        )
)    
    
?>