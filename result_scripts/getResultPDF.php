<?php
function cb_ci($ref_no)
{

include('../../../dompdf/dompdf_config.inc.php');
include 'Barcode39.php';
include 'drawPie.php';
include 'drawBar1.php';
include 'drawBar2.php';
include 'drawHor1.php';
include 'drawHor2.php';

$refNumber='170113000397';
$studName='Anjali Sharma';
$repDate= date('d-m-y');

$val1='34';
$val2='66';

$val3='20';
$val4='80';

$val5='40';
$val6='60';

$val7='70';
$val8='30';

$val9='25';
$val10='75';

$val11='65';
$val12='45';
$val13='25';
$val14='90';

$val15='45';
$val16='35';
$val17='75';
$val18='85';
$val19='15';

$val20='8.8';
$val21='2.8';
$val22='4.8';
$val23='6.8';

$val24='1.8';
$val25='8.8';
$val26='7.8';
$val27='3.8';
$val28='4.8';




    //call to create pie charts
    drawPie($val1,$val2,'resultPie1.png');
    drawPie($val3,$val4,'resultPie2.png');
    drawPie($val5,$val6,'resultPie3.png');
    drawPie($val7,$val8,'resultPie4.png');
    drawPie($val9,$val10,'resultPie5.png');
    
    drawBar1($val11,$val12,$val13,$val14,'resultBar1.png');
    drawBar2($val15,$val16,$val17,$val18,$val19,'resultBar2.png');
    
     drawBar3($val20,$val21,$val22,$val23,'resultBar3.png');
     drawBar4($val24,$val25,$val26,$val27,$val28,'resultBar4.png');
    
// set object and Pass RefNum
$bc = new Barcode39($refNumber);

// set text size
$bc->barcode_text_size = 5;

// set barcode bar thickness (thick bars)
$bc->barcode_bar_thick = 4;

// set barcode bar thickness (thin bars)
$bc->barcode_bar_thin = 2;

// save barcode JPG file
$bc->draw('bar3.jpg');


$allPage="<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<title>All Pages</title>
<link href='styleRam.css' rel='stylesheet' type='text/css' />
</head>
<body>
<div class='p1Table_01'>
	<div class='p1-CareerBreederLogo_'>
		<img src='images/p1_CareerBreederLogo.gif' width='342' height='102' alt='' />
	</div>
	<div class='p1-RefNo_'>
		Ref. No.: $refNumber
	</div>
	<div class='p1-BarcodeImage_'>
		<img  src='bar3.jpg' width='174' height='55' alt='' />
	</div>
	<div class='Ruler_'>
		<img   src='images/Ruler.jpg' width='709' height='14' alt='' />
	</div>
	<div class='Page1-15_'>
		 For,
	</div> 
	<div class='CandidateName_'>
		$studName
	</div>
	<div class='CareerIndicatorBanner_'>
		<img   src='images/CareerIndicatorBanner.gif' width='709' height='274' alt='' />
	</div>
	<div class='ReportPreparedOn_'>
		Report Prepared On
	</div>
	<div class='ReportDate_'>
	$repDate
	</div>
 	<div class='ConfidentialImage_'>
		<img   src='images/ConfidentialImage.gif' width='207' height='168' alt='' />
	</div>
 	<div class='p1Signature_'>
		<img   src='images/Signature.gif' width='175' height='96' alt='' />
	</div>
	<div class='Orpotech_'>
		<img   src='images/Orpotech.gif' width='330' height='83' alt='' />
	</div>
	<div class='CareerCouncellor_'>
		<img   src='images/CareerCouncellor.gif' width='216' height='48' alt='' />
	</div>	
	<div class='Page1-Footer_'>
		Career Breeder is an initiative of Oprotech Technologies Pvt Ltd. Copyright &copy; 2012. All rights reserved.
	</div>
</div>

<div class='p2Table_01'>
	<div class='p3-SiteLogo_'>
		<img  src='images/p2_careerBreederLogo.jpg'  width='247' height='76' alt='' />
	</div>
	<div class='p3-CareerIndicatorLogo_'>Career Indicator</div>
    <div class='page3-10_'></div><!--Ruler-->
	<div class='page3-13_'>
		Ref. No.: $refNumber
	</div>
	<div class='page3-17_'>
		<img   src='bar3.jpg' width='175' height='29' alt='' />
	</div>
	<div class='p3-Heading1_'>
    Chairman&rsquo;s message	</div>
	<div class='ChairmanImage_'>
		<img   src='images/ChairmanImage.jpg' width='264' height='424' alt='' />
	</div>
	<div class='p3Message_'>
		<p>I visualize the concept of Career Breeder as a Knowledge society. My dream is that people in different disciplines and walks of life would be able to pursue their careers as to make India a developed country.</p>
	</div>
	<div class='p3Signature_'>
		<img   src='images/Signature3.jpg' width='180' height='65' alt='' />
	</div>
	<div class='ChairmanName_'>
		Dr P.K. Manglik
	</div>
	<div class='ChairmanInfo_'>
	  <p>Narco Analyst C.B.I.<br />
		  MBBS, M.D. (Pysch.), F.I.P.S.</p>
		<p>Reg. No. - 29136 (U.P Medical Council)<br />
		  - 22791 (Delhi Medical Council)</p>
	</div>
	<div class='p3-Footer_'><span class='p2-Footer_'>contactus@careerbreeder.com | +91 90191 61133 | www.careerbreeder.com</span>	</div>
</div>

<div class='p3Table_03'>
	<div class='p2-careerBreederLogo_'>
		<img   src='images/p2_careerBreederLogo.jpg' width='249' height='76' alt='' />
	</div>
	<div class='p2-CareerIndicatorLogo_'>
		Career Indicator </div>
	<div class='p2Ruler_'>	</div>
	<div class='p2-RefNo_'>
		Ref. No.: $refNumber
	</div>
	<div class='p2-Image_'>
		<img   src='bar3.jpg' width='175' height='29' alt='' />
	</div>
	<div class='p2-header1_'>
		With this report you can
	</div>
		<div class='p2-list1_'>
		<ul type='disc'>
		
			<li> Identify job groups or broad occupational categories suitable for you</li>
			<li>  Choose a specific job or career</li>
			<li> Select a college major or course of study</li>
			<li> Identify strengths and potential weaknesses in your personality</li>
			<li>  Increase your job satisfaction</li>
			<li> Make a career transition or shift</li>
			<li> Plan your next steps in career development strategy</li>
			
		</ul>
	</div>
	
 <div class='p2-text14_'>
		<p>
		
		To lead a satisfying professional life, you should have a balance in all dimensions of your personality. Every individual has certain characteristics that determine the role of each dimension in your personality. Scarcity or deficiency with a difference of less then 20% is considered good.
		
		</p>
	</div>
	<div class='p2-header_'>
		Some Examples :  
	</div>
		<div class='p2-list2_'>
	<ul>
		<li>65% extrovert and 35% introvert is considered as good.</li>
		<li>	35% extrovert and 65% introvert is also considered as good.</li>
		<li>	10% extrovert and 90% introvert needs little improvement.</li>
	
	</ul>
	</div>
	
	<div class='p2-Footer_'>
		contactus@careerbreeder.com | +91 90191 61133 | www.careerbreeder.com
	</div>
</div>



<div class='p4Table_01'>

	<div class='page4-03_'>
		<img  src='images/p2_careerBreederLogo.jpg' width='246' height='74' alt='' />
	</div>

	<div class='page4-06_'>
		Career Indicator
	</div>
    <div class='page4-Ruler_'></div>
	<div class='RefNo_'>
		 Ref. No.: $refNumber
	</div>
	
	<div class='Barcode_'>
		<img  src='bar3.jpg' width='174' height='29' alt='' />
	</div>
	
	<div class='page4-18_'>
		Personality Analysis
	</div>
	
	<div class='page4-22_'>
		Thinking Process:
	</div>
	
	<div class='p4think1_'>
		<img src='images/p4think1.jpg' width='71' height='94' alt='' />
	</div>
	<div class='page4-26_'></div>
	<div class='page4-27_'>
		When we face any problem in life we mostly have two types of choices, first try some new and innovative solution for the problem and second we try our learned solutions <br/>( experiences ) , a solution which are already known, so by selection of solution we decide to solve the problem. 
	</div>
	
	<div class='CreativeThinker_'>
		Creative Thinker :
	</div>
	
	<div class='p4Think2_'>
		<img  src='images/p4Think2.jpg' width='61' height='58' alt='' />
	</div>
	
	<div class='page4-38_'>
		Person who are high in creative thinking try to find out some new &amp; Specific ways for the solution of any problem.
	</div>
	<div class='p4Graph1_'>
		<img  src='resultPie1.png' width='179' height='117' alt='' />
	</div>
	
	<div class='textPractThink_'>
		Practical Thinker
	</div>
	<div class='p4GClrPthink_'>	</div>
	<div class='textCreativeThink_'>Creative Thinker</div>
	<div class='p4PracticalThinker_'><img  src='images/p4PracticalThinker.jpg' width='63' height='65' alt='' />	</div>
		<div class='PracticalThinker_'>Practical Thinker :</div>
	<div class='p4GClrCthink_'>
	</div>
	<div class='page4-58_'>
		Person who are high in practical thinking try to find out solutions which are already known &amp; have high practical uses. 
	</div>
	<div class='page4-63_'>
		Behaviour Pattern :
	</div>
    <div class='page4-67_'>
        Behavior pattern is the second phase of problem solutions in which we decide our behavior pattern of the problem solving approach which would be classified again in two dimensions, first is determine type which suggest that there is urgency of solution and we will plan hard steps to solve the problem, on other side moody pattern suggest that there is no need of urgency plan, so we plan solution accordingly.
	</div>
	<div class='p4Herculus_'>
		<img  src='images/p4Herculus.jpg' width='67' height='16' alt='' />
	</div>	
	<div class='p4Herculus073_'>
		<img  src='images/p4Herculus-73.jpg' width='66' height='73' alt='' />
	</div>
	<div class='page4-74_'>	Determine Type :	</div>
	 <div class='p4Herculus076_'><img  src='images/p4Herculus-76.jpg' width='1' height='3' alt='' />	</div>
	<div class='page4-78_'>Person who are high in determine are very firm to find out the solution or result.</div>
		<div class='p4graph2_'>	<img  src='resultPie2.png' width='199' height='120' alt='' /></div>
	<div class='p4GClrMoody_'></div>
	<div class='p4Moody_'>Moody</div>
	<div class='p4Herculus087_'>
		<img  src='images/p4Herculus-87.jpg' width='1' height='15' alt='' />
	</div>
 	<div class='p4Determined_'>	Determined	</div>
	<div class='p4Donald_'>
		<img  src='images/p4Donald.jpg' width='67' height='35' alt='' />
	</div>
	<div class='p4GClrDetermined_'>	</div>
	<div class='p4Donald097_'><img  src='images/p4Donald-97.jpg' width='66' height='63' alt='' /></div>
	<div class='page4-98_'>	Moody : </div>
	 <div class='p4Donald100_'><img  src='images/p4Donald-100.jpg' width='1' height='2' alt='' /></div>
	<div class='page4-102_'>Person who are high in moody can easily deviate from their basic work and engage in other work.
	</div>
	<div class='p4Footer_'> contactus@careerbreeder.com | +91 90191 61133 | www.careerbreeder.com </div>
</div>

<div class='p5Table_01'>
	<div class='page5-03_'>
		<img  src='images/p2_careerBreederLogo.jpg' width='249' height='76' alt='' />	</div>
	<div class='page5-06_'>
		Career Indicator
	</div>
	<div class='page5-10_'>	</div>
	<div class='RefNo_'>
		Ref. No.: $refNumber
	</div>
	<div class='p5Barcode_'>
		<img  src='bar3.jpg' width='174' height='29' alt='' />
	</div>
	<div class='PersonalityAnalysis_'>
		Personality Analysis
	</div>
	<div class='SocialExpression_'>
		Social Expression :
	</div>
	<div class='p5-artist_'>
		<img  src='images/p5_artist.gif' width='107' height='82' alt='' />
	</div>
	<div class='p5-text14_'>
        Social expression is the pattern in which we decide to work on our solution, here again we have two major  route one in that we take help to solve the problem which called extrovert behavior and otherwise if we think that I am able to solve the problem by own then introvert behavior reflect.
	</div>
	<div class='page5-32_'>
		Extrovert :
	</div>
	<div class='p5GroupDisc_'>
		<img  src='images/p5GroupDisc.gif' width='56' height='48' alt='' />
	</div>
	<div class='p5-graph-intr_'>
		<img  src='resultPie3.png' width='208' height='140' alt='' />
	</div>
	<div class='p5-text-14-1_'>&amp;
		Person high in extrovert can easily express their idea  &amp; views in his social atmosphere.
	</div>
	<div class='page5-44_'>
        Introvert:
    </div>
	<div class='p5-introvert_'>
		<img src='images/p5_introvert.gif' width='50' height='47' alt='' />
	</div>
	<div class='p5-text14-2_'>
        Person who  are high in introvert can try some new way to express their ideas  &amp; thought other then general prescribe manner.
.
	</div>
	<div class='p5-text14-2050_'>	</div>
	<div class='p5-clrGraph-Introvert1_'></div>
	<div class='page5-55_'>
		Introvert
	</div>
	<div class='page5-60_'>
		Extrovert
	</div>
	<div class='p5-clrGraph-Extr-2_'></div>
	<div class='p5-decisionMakingHabbit_'></div>
	<div class='p5-findWay_'>
		<img  src='images/p5_findWay.gif' width='119' height='120' alt='' />
	</div>
	<div class='p5-text14-3_'>
        Decision making capacity is the fourth pattern in which person develop habit to solve the problem as early as possible on other there is security mildness thinking in which person know about solutions but invest time to verify the outcomes which take time and slightly delay the result.
	</div>
	<div class='p5-Quick_'>
		<img  src='images/p5_Quick.jpg' width='57' height='72' alt='' />
	</div>
	<div class='page5-76_'>Quick :</div>
	<div class='p5-graphQuick2_'>
		<img src='resultPie4.png' width='216' height='163' alt='' />
	</div>
	<div class='page5-81_'>
        Person who are high on quick have tendency to take risk.
	</div>
	<div class='page5-85_'>Security Minded :</div>
	<div class='p5-SecurityMind_'>
		<img  src='images/p5_SecurityMind.gif' width='57' height='54' alt='' />
	</div>
	<div class='p5-text-14-4_'>
        Person who are high on security minded can analyse their action deeply and take moderate level risk.
	</div>
	<div class='p5-grapgQuichClr-1_'></div>
	<div class='p5-quick_Text'>
		Quick	</div>
	<div class='p5-securityMinded_'>
		Security Minded
	</div>
	<div class='p5-graph-clr-2_'>
		
	</div>
	<div class='p5-footer_'>
		contactus@careerbreeder.com | +91 90191 61133 | www.careerbreeder.com 
	</div>
</div>


<div class='p6Table_01'>
	<div class='p6siteLogo_'>
		<img  src='images/p6siteLogo.gif' width='246' height='72' alt='' />
	</div>
	<div class='p6CareerIndicatorLogo_'>
		Career Indicator
	</div>
	<div class='p6-ruler_'></div>
	<div class='id6-13_'><span class='RefNo_'>Ref. No.: $refNumber </span>	</div>
	<div class='p6-personalityAnalysis_'>
		Personality Analysis
	</div>
	<div class='id6-19_'>
		<img  src='bar3.jpg' width='174' height='29' alt='' />
	</div>
    <div class='p6WorkAttitude_'>
		Work Attitude :</div>
	<div class='p6-painter_'>
		<img  src='images/p6_painter.gif' width='96' height='131' alt='' />
	</div>
	<div class='p6-text14-1_'>    Work attitude is the last pattern in which a person actually deal with solution process this show our ability to solve the problem, here again we have two dimension first rule bound attitude in which person try those ideas and facts which are prescribed by system and generate result on other side flexibility attitude show easiness of the work and complete task with perfectness.</div>
	<div class='p6-FlexHead1_'>Flexibility :</div>
	<div class='p6-flexibility_'>
		<img  src='images/p6_flexibility_new.png' width='61' height='41' alt='' />	</div>
	<div class='p6-text14-2_'>    Person who are high on flexibility  complete work according to other expectations.</div>
	<div class='p6-Graph-Flxblty_'>
		<img  src='resultPie5.png' width='214' height='129' alt='' />
	</div>
	<div class='id6-40_'>
	  Rule bound : 	
    </div>
	<div class='p6-ruleBound_'>
		<img  src='images/p6_ruleBound.gif' width='57' height='55' alt='' />
	</div>
	<div class='p6-text14-3_'>	
      Person who are high on rule bound are feel comfortable in following rules and guideline and take expert help.<br />
    </div>
	<div class='p6-Flexibility_Text'>
		Flexible
	</div>
	<div class='p6-GrphClr-1_'></div>
	<div class='p6-GrphClr-2_'></div>
	<div class='p6-RuleBoundText_'>
		Rule Bound
	</div>
	<div class='p6-Blank_'></div>
	<div class='p6-Footer_'>
		contactus@careerbreeder.com | +91 90191 61133 | www.careerbreeder.com
	</div>
</div>


<div class='p7Table_01'>

	<div class='p7-careerBreederLogo_'>
		<img   src='images/p2_careerBreederLogo.jpg' width='249' height='76' alt='' />
	</div>
	<div class='p7indicatorLogo_'>
		Career Indicator 
	</div>
	<div class='page7-10_'></div>
	<div class='p7RefNo_'> Ref. No.: $refNumber  </div>
	<div class='p7Heading1_'>Hari's psychological characteristics<br />
    and suggestions</div>
	<div class='p7Barcode_'> <img   src='bar3.jpg' width='174' height='29' alt='Barcode' /> </div>
	<div class='page7-22_'>
		<img   src='images/sec_mind_img.png' width='96' height='85' alt='' /></div>
	<div class='p7-Head1-sMinded_'>
	  Hari Is Security Minded :	</div>
	<div class='P7-head1_'><p>	  Characteristic	</p></div>
	  <div class='ListDiv1'>
	  <ul type='disc'>
	  <li>These are people who analyse things and then take decisions.</li>
	  <li>They are emotionally very mature.</li>
	  <li>They are generally called visionaries.</li>
	  <li>Their planning and goal-setting skills<br /> are perfect.</li>
	  <li>They are generally highly successful.</li>
	  </ul>
  </div> 
	<div class='headingSuggestion_'>
		<p >Suggestion</p>
	</div>	 
	<div class='p7list2_'>
		<ul type='disc'>
  	<li>Always try to finish your work plan and<br /> your plan must have a fixed Time Line.</li>
	  <li>Join adventure sports (river rafting,<br /> rock climbing).</li>
	  <li>Whatever work you do give it your 100%.</li>
	  <li>Play games like tennis, chess 	and/or<br /> horse riding.</li>
	  <li>Take part in cultural activities.</li>
	  </ul>
  </div>
	<div class='page7-52_'>
		<img   src='images/page7_70.gif' width='331' height='3' alt='' />
	</div>
	<div class='page7-53_'>
		<img   src='images/page7_70.gif' width='331' height='3' alt='' />
	</div>
	<div class='p7-list1054_'>
		 
	</div>
	<div class='page7-55_'>
		<img   src='images/page7_55.gif' width='87' height='101' alt='' />
	</div>
	<div class='p7-Char-h3_'> <p>
		Characteristic</p>
	</div>
	<div class='page7-62_'> <p>		Suggestion</p> </div>
	<div class='p7-list4_'>
		<ul type='disc'>
	  <li>Do not deviate from your work plan. <br /> Be firm.</li>
	  <li>Always make your work plan with the<br /> focus on the end result.</li>
	  <li>Always consult with a successful person<br /> for your career suggestions.</li>
	  <li>Make a routine or a day plan every day.</li>
	  <li>Learn to say NO in life.</li>
	   <li>Learn to say NO in life.</li>
	 
		</ul>
  </div>
  	<div class='p7-Head2-Moody_'> Hari Is Moody :	</div>
	<div class='p7-list3067_'>
<ul type='disc'>
	  <li>These are people who work<br /> according to their own schedule.</li>
	  <li>Give preference to their emotion at work.</li>
	  <li>They are very caring and get respect in<br /> society.</li>
	  <li>They are good motivators.</li>
	  <li>Take part in cultural activities.</li>
	   <li>Take part in cultural activities.</li>
</ul>
  </div> 
	
    <div class='page7-85_'>
		<img   src='images/page7_70.gif' width='332' height='4' alt='' />
	</div>
	<div class='page7-86_'>
		<img   src='images/page7_70.gif' width='332' height='5' alt='' />
	</div>
	<div class='p7-list3087_'> </div>
	<div class='P7-Footer_'>
		contactus@careerbreeder.com | +91 90191 61133 | www.careerbreeder.com 
	</div>
</div>

<div class='p8Table_01'>
	<div class='careerBreederLogo_'>
		<img   src='images/p2_careerBreederLogo.jpg' width='249' height='76' alt='' />	</div>
	<div class='id-p8CareerIndicatorLogo_' > Career Indicator</div>
	<div class='page8-10_'>	</div>
	<div class='p8-refNo_'>	  Ref. No.: $refNumber	</div>
	<div class='p8-barCode_'>
		<img   src='bar3.jpg' width='177' height='28' alt='' />
	</div>
	<div class='p8-headingMain_'>
		Musicians and Singers (Arts - Performing)
	</div>
	<div class='p8-text14-1_'>
		Musicians play one or more instruments. Singers sing musical arrangements.
	</div>
	<div class='p8-img-musicians_'>
		<img   src='images/p8_img_musicians.gif' width='166' height='255' alt='' />
	</div>
	<div class='p8-workHeading2_'>
		Work :
	</div>
	<div class='p8-Text14-2_'>
		Musicians play one or more instruments as soloists or asmembers of a musical group before audiences or for recording purposes. Singers sing musical arrangements as soloists or as members of vocal groups before audiences or for recording purposes. Musicians and singers perform with orchestras, choirs, opera companies and popular bands in establishments such as concert halls, lounges and theatres and in film, television and recording studios.
	</div>
	<div class='page8-31_'>
		Requirements:
	</div>
	<div class='page8-33_'>In general, you usually need a university degree, college diploma or other post-secondary specialized training in your area of work. You may need experience and to be able to demonstrate directing, creative or performing skills. You may need membership in a related guild or union. Many recent entrants have an undergraduate university degree, and almost 1 in 10 has a graduate degree.</div>
	<div class='page8-36_'>
		Title Examples:
	</div>
	<div class='page8-37_'>
		 Musician Opera Singer, Recording Artist, Singer Vocalist.
	</div>
	<div class='page8-39_'>		
        Degrees Associated With This Career:
	</div>
	<div class='page8-40_'>
		Certificate Diploma Bachelor Master Doctorate.
	</div>
	<div class='p8-contentWrapper042_'>
		 Art English (Composition) Drama Music.
	</div>
	<div class='page8-44_'>
        Useful Secondary School Subjects:
	</div>
	<div class='page8-47_'>
        Employers:
	</div>
	<div class='page8-49_'>People in this group work for film production, radio and television companies and stations; broadcasting departments; sound recording studios; record production, ballet and dance companies; symphony orchestras; bands; choirs; night clubs; dance academies; and private acting and dance schools. Many are self-employed.	</div>
	<div class='p8-Footer_'>
		contactus@careerbreeder.com | +91 90191 61133 | www.careerbreeder.com 
	</div>
	
</div>

<div class='p9Table_01'>
	<div class='p9-logoCBreeder_'>
		<img   src='images/p2_careerBreederLogo.jpg' width='249' height='76' alt='' />	</div>
	<div class='p9-careeLogo_'>
		Career Indicator
	</div>
	<div class='page9-10_'></div>
	<div class='p9-ruler_'></div>
	<div class='page9-12_'></div>
	<div class='p9-successGraph_'>
		<img   src='images/p9_successGraph.gif' width='100' height='99' alt='' />
	</div>
	<div class='p9-refNo_'>
		Ref. No.: $refNumber
	</div>
	<div class='p9-barcode_'>
		<img  src='bar3.jpg' width='173' height='29' alt='' />
	</div>
	<div class='p9-header-main1_'>
		Hari's Study Habits
	</div>
	<div class='page9-24_'>
		Study skill is the learn behaviour in which we develop certain pattern of studying which is support by our intelligence and our social and family surrounding. If we know correct way of learning and studying skill then we easily improve our performances in exam. Here we are giving some major factor which affects our studies directly; by knowing these we may easily improve our study related skills.
	</div>
	<div class='page9-26_'>
		<img  src='images/page9_26.gif' width='135' height='80' alt='' />
	</div>
	<div class='page9-29_'>
		Adjustment capacity :
	</div>
	<div class='page9-30_'>
		Adjustment capacity is the factor which shows the person's ability to manage his skill in minimizing hurdles and marching toward goal with the help of his surroundings.
	</div>
	<div class='page9-31_'>
		<img src='images/page9_31.gif' width='135' height='76' alt='' />
	</div>
	<div class='page9-33_'>
		Discipline Skill :
	</div>
	<div class='page9-34_'>
		Discipline is the tendency of an individual  to follow the given prescribe  guideline to his goal . Discipline skill reflects person ability to follow norm i.e. family, social-cultural, school. 
	</div>
	<div class='page9-37_'>
		Time management skill :
	</div>
	<div class='page9-38_'>
		<img   src='images/page9_38.gif' width='135' height='72' alt='' />
	</div>
	<div class='page9-39_'>
		Time management is skill by which we effectively plan our work and deliver it according to expectation, if we have high time management sense the we easily complete our task and make our self more successful than ever. 
	</div>
	<div class='page9-42_'>
		<img  src='images/page9_42.gif' width='135' height='95' alt='' />
	</div>
	<div class='page9-43_'>
		Stress Management :
	</div>
	<div class='page9-44_'>
		Stress management skill is the basic tool of our life by which we save our self for propose threat  and move  with positive frame of mind, if we have high stress management skill then it had seen the anxiety &amp; fear would not affect our performances.
	</div>
	<div class='page9-47_'>
		<img src='resultBar1.png' width='534' height='235' alt='' />
	</div>
	<div class='p9-Footer_'>
		contactus@careerbreeder.com | +91 90191 61133 | www.careerbreeder.com
	</div>
	
</div>

<div class='p10_tableContent'>	
	<div class='page10-03_'>
		<img src='images/p2_careerBreederLogo.jpg' width='249' height='76' alt='' />
    </div>
	<div class='p10-careerIndicatorLogo_'>
		Career Indicator 
	</div>
	<div class='page10-10_'> </div>
	<div class='page10-13_'>
		<img   src='images/page10_13.gif' width='88' height='58' alt='' />
	</div>
	<div class='p10-refNo_'>
		Ref. No.: $refNumber
	</div>
	<div class='p10-barcode-1_'>
		<img   src='bar3.jpg' width='177' height='30' alt='' />
	</div>
	<div class='page10-19_'>
		Hari's Success Graph
	</div>
	<div class='page10-21_'>
		Achievement of an action within a specific time and specific parameter which is expected or planed by an individual is Success. Here we are giving you basic's of success parameter by which you could easily analyse and improve your skill in success sphere.
	</div>
	<div class='page10-25_'>
		<img   src='images/page10_25.gif' width='130' height='81' alt='' />
	</div>
	<div class='p10Head-1_'>
		Body Confidence :
	</div>
	<div class='page10-28_'>
		Body Confidence Is an Idea or A Thought Image of A Person's Self Physical Appearance. This Concept Of Self Appearance Have Big Impact Of Our Thinking And Behaviour, If We Improve This Then It Will Change Our Thinking And Make Our Self Better Adjusted In Our Society And Personal Life.
	</div>
	<div class='page10-29_'> </div>
	<div class='page10-31_'>
		<img   src='images/page10_31.gif' width='129' height='86' alt='' />
	</div>
	<div class='P10Head2_'>
		Imagination Capacity : 
	</div>
	<div class='page10-33_'> </div>
	<div class='p10-txt14-2_'>
		Imagination Capacity Is The Power Of An Individual To Make New Images Or Sensations Which Are Not Present At That Moment, By This Capacity Person Could Easily Solve New Problems, Create New Dimension For Development And Start New Adjustments For The Betterment Of Life.
	</div>
	<div class='page10-37_'>
		<img   src='images/page10_37.gif' width='129' height='90' alt='' />
	</div>
	<div class='p10Interpersonal-Head_'>
		Interpersonal Relation Skill : 
	</div>
	<div class='page10-39_'></div>
	<div class='page10-40_'>
		Interpersonal Relation Is The Capacity Of Any Person To Make Personal, Social Or Business Relation With Other Person And Respectively Carry On The Relation With Mutual Benefit. This Skill Makes A Person More Acceptable Everywhere In Society And Convert Hard Labour To Smart Labour.
	</div>
	<div class='p10Head4_'>
		Problem Solving : 
	</div>
	<div class='page10-43_'>
		<img   src='images/page10_43.gif' width='130' height='84' alt='' />
	</div>
	<div class='page10-44_'>
		Problem Solving Is The Skill Or Strength Of Any Person To See The Coming Problem And Development Of A Specific Kind Of Confidence Or Hope For The Solution Of The Problem. This Is Basically A System To Approach Problem, If We Improve This Skill Then We Are In Good Shape To Counter Success  
	</div>
	<div class='p10Head5-1_'>
		Emotional Maturity : 
	</div>
	<div class='page10-48_'>
		<img   src='images/page10_48.gif' width='130' height='76' alt='' />
	</div>
	<div class='p10-50-text14_'>
		Emotional Maturity Is The Psychological Skill Of A Person Which Comprises By Intelligence, Emotion And Spiritualism. This Give Person Strength To Analyse And Take Proper Decision In Given Problem Which Is Acceptable By His Society.
	</div>
	<div class='p10-GraphDiv_'>
		<img src='resultBar2.png' width='678' height='260' alt='' />
	</div>
	<div class='P10-Footer_'>
		contactus@careerbreeder.com | +91 90191 61133 | www.careerbreeder.com 
	</div>
    
</div>

<div class='DoctorTable_01'>
	
	
	<div class='Doctor-BreederlOGO_'>
		<img  src='images/p2_careerBreederLogo.jpg' width='249' height='76' alt='' />	</div>
	
	
	<div class='Doctor-IndiLogo_'> Career Indicator </div>
	
	
	
	<div class='Doctor-Ruler_'>
		 
	</div>
	
	
	<div class='Doctor-RefNo-text_'>
		Ref. No.: $refNumber
	</div>
	
	
	<div class='Doctor-BarCode_'>
		<img   src='bar3.jpg' width='173' height='29' alt='' />
	</div>
	
	
	<div class='Doctor-hEAD1_'>Career Choice - Doctor</div>
	
	
	<div class='Doctor-imgDctr_'>
		<img  src='images/Doctor_imgDctr.gif' width='176' height='272' alt='' />
	</div>
	
	<div class='Doctor-head2_'>
		Personality Improvements :
	</div>
	
	
	<div class='Doctor-text1_'>
	  Hari, your introvertness level is high, you need to be little more extrovert. Here are the suggestions you need to incorporate to make yourself more competent.	</div>
	<div class='Doctor-sECMIND_'>
		<img   src='images/Doctor_sECMIND.gif' width='97' height='77' alt='' />
	</div>
	 
	<div class='Doctor-imgMoody_'>
		<img   src='images/Doctor_imgMoody.gif' width='121' height='76' alt='' />
	</div>
	
	
	<div class='Doctor-headSecMind_'>
		<p>Security Minded</p>
	</div>
	
	<div class='Doctor-HeadMoody_'>
		
		<p>Moody</p>
	</div>
	
	<div class='Doctor-Moody_'>
	
	<ul type='disc'>
		<li>Do not deviate from your work plan. 
	Be firm.</li>

		<li>	focus on the end result.</li>

		<li>Always consult with a successful person
	for your career suggestions.</li>

		<li>Make a routine or a day plan every day.</li>

		<li>Learn to say NO in life.</li>
</ul>
	</div>
	<div class='Doctor-38_'>
		<ul>
		<li>Always try to finish your work plan and
	your plan must have a fixed Time Line.</li>


 		<li>Join adventure sports (river rafting,
	rock climbing).</li>


 		<li>Whatever work you do give it your 100%.</li>



		<li>Play games like tennis, chess 	and/or
	horse riding.</li>

		<li>Take part in cultural activities.</li></ul>
	</div>
	<div class='Doctor-LineBottom_'>
		<img   src='images/Doctor_LineBottom.gif' width='332' height='2' alt='' />
	</div>
	
	<div class='Doctor-LineBottom1_'>
		<img src='images/Doctor_LineBottom.gif' width='331' height='2' alt='' />
	</div>
	
	
	<div class='Doctor-Footer_'>
		contactus@careerbreeder.com | +91 90191 61133 | www.careerbreeder.com
	</div>
	
</div>

<div class='psumTable_01'>
	 
	<div class='Summary1-CareerBreederLogo_'>
		<img  src='images/p2_careerBreederLogo.jpg' width='249' height='76' alt='' />	</div>
	<div class='Summary-HeaderWrapper004_'></div>
	<div class='Summary-HeaderWrapper005_'></div>
	<div class='Summary1-CareerReader-logo_'>Career Indicator</div>
	<div class='Summary-HeaderWrapper007_'></div>
	<div class='Summary-HeaderWrapper008_'></div>
	
	
	<div class='Summary1-Ruler_'></div>
	
	
	
	<div class='Summary1-Ref-No-txt_'>
		Ref. No.: $refNumber	
	</div>
	
	<div class='Summary1-Barcode_'>
		<img src='bar3.jpg' width='176' height='28' alt='' />
	</div>
	

	<div class='Summary1-21_'>
		Summary
	</div>
	
	<div class='Summary1-23_'>
		Hari's Personality :
	</div>
	
	<div class='Summary1-listTable_'>
	  <table width='100%' border='0' bgcolor='#e6eff8'>
        <tr>
          <td width='45%'>Where you focus your attention</td>
          <td width='17%' class='BlueTable'><div align='right' class='BlueTable'>Extraversion</div></td>
          <td width='8%'>40%</td>
          <td width='6%' class='ClrPink'>  <font color='#FF33CC'> and </font></td>
          <td width='17%'>Introversion</td>
          <td width='7%'>60%</td>
        </tr>
        <tr>
          <td>The way you take in information </td>
          <td class='BlueTable'><div align='right' class='BlueTable'>Sensing</div></td>
          <td>55%</td>
          <td class='ClrPink'><font color='#FF33CC'>and </font></td>
          <td>Intuition</td>
          <td>45%</td>
        </tr>
        <tr>
          <td>The way you make decisions </td>
          <td class='BlueTable'><div align='right' class='BlueTable'>Thinking</div></td>
          <td>45%</td>
          <td class='ClrPink'><font color='#FF33CC'>and </font></td>
          <td>Feeling </td>
          <td>55%</td>
        </tr>
        <tr>
          <td>How you deal with the outer world</td>
          <td class='BlueTable'><div align='right' class='BlueTable'>Judging</div></td>
          <td>70%</td>
          <td class='ClrPink'><font color='#FF33CC'>and </font></td>
          <td>Perceiving</td>
          <td>30%</td>
        </tr>
        <tr>
          <td>XYZ</td>
          <td class='BlueTable'><div align='right'></div></td>
          <td>&nbsp;</td>
          <td class='ClrPink'>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
	</div>
	 
	<div class='Summary1-Heading-graph_'>
		Hari's Study Habbit :
	</div>
	
	<div class='Summary1-HeadingGraph2_'>
		Hari's Success Skills :
	</div>
	<div class='Summary1-Graph2_'>
		<img  src='resultBar3.png' width='349' height='159' alt='' />
	</div>
	<div class='Summary1-Graph1_'>
		<img src='resultBar4.png' width='328' height='159' alt='' />
	</div>
	
	
	<div class='Summary1-HeadingCharacteristics_'>
		Hari's  Characteristics:
	</div>
	
	<div class='Summary1-Table2_'>
	<table width='100%' border='0' bgcolor='#e6eff8'>
        <tr>
          <td width='45%'>Where you focus your attention</td>
          <td width='17%' class='BlueTable'><div align='right' class='BlueTable'>Extraversion</div></td>
          <td width='10%'>40%</td>
          <td width='6%' class='ClrPink'><font color='#FF33CC'>and </font></td>
          <td width='16%'>Introversion</td>
          <td width='6%'>60%</td>
        </tr>
        <tr>
          <td>The way you take in information </td>
          <td class='BlueTable'><div align='right' class='BlueTable'>Sensing</div></td>
          <td>55%</td>
          <td class='ClrPink'><font color='#FF33CC'>and </font></td>
          <td>Intuition</td>
          <td>45%</td>
        </tr>
        <tr>
          <td>The way you make decisions </td>
          <td class='BlueTable'><div align='right' class='BlueTable'>Thinking</div></td>
          <td>45%</td>
          <td class='ClrPink'><font color='#FF33CC'>and</font>  </td>
          <td>Feeling </td>
          <td>55%</td>
        </tr>
        <tr>
          <td>How you deal with the outer world</td>
          <td class='BlueTable'><div align='right' class='BlueTable'>Judging</div></td>
          <td>70%</td>
          <td class='ClrPink'><font color='#FF33CC'>and </font></td>
          <td>Perceiving</td>
          <td>30%</td>
        </tr>
      </table>
		 
	</div>
	
	<div class='Summary1-BulbImg_'> <p>	
				 HariPrasad !  <br /><br />

				  Here is your Report	</p> 
	</div>
	<div class='Summary1-Pro-mtch_'>
		Professions Matching Hari's Profile :			
	</div>
	
	
	<div class='Summary1-Profess-List_'>
		<ul type='disc'>
			<li> Musicians and singers</li>
			<li>Dietitians</li>
			<li>Education Counsellors</li>
		</ul>
	</div>
	
	<div class='Summary1-45_'>
		Expert Recommends : 
	</div>
	
	
	<div class='Summary1-list-expert_'>
		<ul type='disc'>
		
		<li>You need to be more introvert than Extrovert.</li>
		<li>You need to be more quick in decision making.</li>
		<li>You need to be little more moody than determine type.</li>
		</ul>
	</div>
	
	<div class='Summary1-Footer_'>
		contactus@careerbreeder.com | +91 90191 61133 | www.careerbreeder.com
	</div>
	
</div>

<div class='p12Table_01'>
	<div class='Contact-us-CarrerIndicator_'>
		<div class='p11-careerIndicatorLogo_'>
		Career Indicator 
	</div>
	</div>
	<div class='Contact-us-Logo_'>
		<img src='images/p2_careerBreederLogo.jpg' width='249' height='76' alt='' />	</div>
	<div class='Contact-us-HeadinLine_'>
  </div>
	<div class='Contact-us-b1_'></div>
	<div class='Contact-us-RefNo_'>
		Ref No : $refNumber			
  </div>
	<div class='Contact-us-Barcode_'>
		<img src='bar3.jpg' width='177' height='30'  alt='Barcode'/>
	</div>
	<div class='Contact-us-Contact24X7Image_'>
		<img src='images/Contact-us_Contact24X7Image.gif' width='709' height='200' alt='' />
	</div>
	<div class='Contact-us-Contact24X7p2_'>
		<img src='images/Contact-us_Contact24X7p2.gif' width='439' height='213' alt='' />	</div>
	<div class='Contact-us-Address_' >
	<p style='padding-left:45px;'><br />
	 Corporate Office (Head Quarters):<br />
	 Career Breeder<br />
	 Hari Complex, Plot no 20, Sector 7,<br />
	 Ghansoli, Navi Mumbai<br />
	 Pin Code: 400701
     <br /><br />
	Registered Office:<br />
	Career Breeder<br />
	C/o Oprotech Technologies Pvt Ltd.<br />
	5/142 Awas Vikas Colony<br />
	Farrukhabad, U.P., India - 209625<br />
	</p>
  </div>
	<div class='Contact-us-ContactImageP3_'></div>
	<div class='Contact-us-EmailAddress_'>
		<strong>Email: contactus@careerbreeder.com<br />Tel: +91 90191 61133<br />
                Website: www.careerbreeder.com
		</strong>
	</div>
	<div class='Contact-us-ContactUsImageFooter_'>
		<img src='images/Contact-us_ContactUsImageFo.gif' width='709' height='26' alt='' />
	</div>
	<div class='Contact-us-Smily_'>
		<img src='images/Contact-us_Smily.gif' width='190' height='49' alt='' />	</div>
	<div class='Contact-us-DidYouKnowText_'>
	<strong>&nbsp;&nbsp;As part of a social responsibility, whenever you buy anything from
			us, a part of it goes for Social Welfare.</strong>	</div>
	<div class='Contact-us-ContactFooter_'>
		contactus@careerbreeder.com | +91 90191 61133 | www.careerbreeder.com 
	</div>
</div>
</body>
</html>";
    $dompdf = new DOMPDF();
    $dompdf->load_html($allPage);
    $dompdf->set_paper('A4','portrait'); 
    $dompdf->render();
    $dompdf->stream('report.pdf', array('Attachment' => false));
}

?>