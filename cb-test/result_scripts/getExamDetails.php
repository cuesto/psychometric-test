<?php

function getSecQuesNum($qNo)
{
    $qNo++;
    if($qNo<=70)
    {
        $qNum=0;
        
        $secNo=$qNo%5;
        
        $qNum=floor($qNo/5);
    
        if($secNo==0)
        {
          $secNo=5;
        }
        else
        {
           $qNum++;    
        }         
        
    }
    else
    {
         $secNo=($qNo-70)%4;
          $qNum=floor(($qNo-70)/4);
        
        if($secNo==0)
        {
            $secNo=5;
        }
        else
        {
            $qNum++;
        }
        
        $qNum=$qNum+14;
    }
    
     echo "sec num is : ".($secNo-1);
     echo "<br/>qno is :".($qNum-1);   
}

function globalQuesNo($secNo,$qNo)
{
    if($qNo<14)
  echo ($qNo*5)+$secNo;
else
{
  if($secNo==4)$secNo=3;
  
  $temp=$qNo-14;
  $no=($qNo*5)-$temp+$secNo;
  
  echo $no;
  
}
}
//getSecQuesNum(57);

globalQuesNo(3,13);


?>